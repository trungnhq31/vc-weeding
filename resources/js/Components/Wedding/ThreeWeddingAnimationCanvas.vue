<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch } from 'vue';
import * as THREE from 'three';

const props = withDefaults(defineProps<{
    progress?: number;
    canvasOpacity?: number;
}>(), {
    progress: 0,
    canvasOpacity: 1,
});

const canvasRef = ref<HTMLCanvasElement | null>(null);

let renderer: THREE.WebGLRenderer | null = null;
let scene: THREE.Scene | null = null;
let camera: THREE.PerspectiveCamera | null = null;
let geometry: THREE.BufferGeometry | null = null;
let material: THREE.ShaderMaterial | null = null;
let animationFrameId: number | null = null;

const uniforms = {
    uProgress: { value: 0.0 },
    uTime: { value: 0.0 },
    uSize: { value: 20.0 },
    uColorPhase1: { value: new THREE.Color('#F59E0B') }, // Champagne Gold
    uColorPhase2: { value: new THREE.Color('#EC4899') }, // Soft Rose Gold
    uColorPhase3: { value: new THREE.Color('#881337') }, // Warm Rosewood Accent
};

// Custom GLSL Vertex Shader for 3D Particle Morphing & Wave Motion
const vertexShader = `
attribute vec3 aPositionTarget;
attribute float aRandomScale;
attribute float aPhase;

uniform float uProgress;
uniform float uTime;
uniform float uSize;

varying float vProgress;
varying float vRandom;

void main() {
    vProgress = uProgress;
    vRandom = aRandomScale;
    
    // Smooth non-linear interpolation between initial float state and 3D target shape
    float easedProgress = smoothstep(0.0, 1.0, uProgress);
    vec3 mixedPosition = mix(position, aPositionTarget, easedProgress);
    
    // Organic liquid wave motion
    float waveX = sin(mixedPosition.y * 3.0 + uTime * 1.8 + aPhase) * 0.15 * (1.0 - easedProgress * 0.3);
    float waveY = cos(mixedPosition.x * 3.0 + uTime * 1.5 + aPhase) * 0.15 * (1.0 - easedProgress * 0.3);
    float waveZ = sin(mixedPosition.z * 4.0 + uTime * 2.0) * 0.1;
    
    mixedPosition += vec3(waveX, waveY, waveZ);

    vec4 modelPosition = modelMatrix * vec4(mixedPosition, 1.0);
    vec4 viewPosition = viewMatrix * modelPosition;
    vec4 projectedPosition = projectionMatrix * viewPosition;

    gl_Position = projectedPosition;
    
    // Dynamic point size scaling with perspective and zoom
    float sizeFactor = (1.0 + sin(uTime * 2.0 + aPhase) * 0.25) * aRandomScale;
    gl_PointSize = uSize * sizeFactor * (1.0 + uProgress * 0.4) * (1.0 / -viewPosition.z);
}
`;

// Custom GLSL Fragment Shader for Romantic Starlight Halo Effect
const fragmentShader = `
uniform vec3 uColorPhase1;
uniform vec3 uColorPhase2;
uniform vec3 uColorPhase3;
uniform float uProgress;

varying float vProgress;
varying float vRandom;

void main() {
    // Distance from center of particle point to render smooth soft circle halo
    float distanceToCenter = length(gl_PointCoord - vec2(0.5));
    float strength = 0.08 / (distanceToCenter + 0.05) - 0.08;
    strength = clamp(strength, 0.0, 1.0);

    // Color gradient transition across scroll phases (Champagne -> Rose Gold -> Deep Rosewood)
    vec3 colorMix;
    if (vProgress < 0.5) {
        colorMix = mix(uColorPhase1, uColorPhase2, vProgress * 2.0);
    } else {
        colorMix = mix(uColorPhase2, uColorPhase3, (vProgress - 0.5) * 2.0);
    }

    float alpha = strength * (0.6 + sin(vRandom * 10.0) * 0.2 + vProgress * 0.3);
    gl_FragColor = vec4(colorMix, clamp(alpha, 0.0, 0.95));
}
`;

const initThree = () => {
    if (!canvasRef.value) return;

    const width = window.innerWidth;
    const height = window.innerHeight;

    // Renderer setup with soft transparent background
    renderer = new THREE.WebGLRenderer({
        canvas: canvasRef.value,
        alpha: true,
        antialias: true,
        powerPreference: 'high-performance',
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);

    // Scene & Camera
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(55, width / height, 0.1, 100);
    camera.position.z = 5.5;

    // Particle Count based on device performance
    const isMobile = width < 768;
    const count = isMobile ? 8000 : 28000;
    uniforms.uSize.value = isMobile ? 16.0 : 24.0;

    const positions = new Float32Array(count * 3);
    const targetPositions = new Float32Array(count * 3);
    const randomScales = new Float32Array(count);
    const phases = new Float32Array(count);

    for (let i = 0; i < count; i++) {
        const i3 = i * 3;

        // Phase 1: Ambient floating 3D starlight sphere
        const radius = 1.5 + Math.random() * 2.2;
        const theta = Math.random() * Math.PI * 2;
        const phi = Math.acos((Math.random() * 2) - 1);

        positions[i3] = radius * Math.sin(phi) * Math.cos(theta);
        positions[i3 + 1] = radius * Math.sin(phi) * Math.sin(theta);
        positions[i3 + 2] = radius * Math.cos(phi);

        // Phase 2 & 3: Parametric 3D Intertwined Heart Motif Target
        const t = Math.random() * Math.PI * 2;
        const streamType = i % 2 === 0 ? 1 : -1;
        
        // 3D Heart parametric equations scaled to fit view
        const scale = 0.12;
        const heartX = 16 * Math.pow(Math.sin(t), 3) * scale;
        const heartY = (13 * Math.cos(t) - 5 * Math.cos(2 * t) - 2 * Math.cos(3 * t) - Math.cos(4 * t)) * scale;
        const heartZ = streamType * Math.sin(t * 2) * 0.4 + (Math.random() - 0.5) * 0.3;

        // Scatter target slightly for realistic particle volume
        const dispersion = 0.15;
        targetPositions[i3] = heartX + (Math.random() - 0.5) * dispersion;
        targetPositions[i3 + 1] = heartY + (Math.random() - 0.5) * dispersion;
        targetPositions[i3 + 2] = heartZ + (Math.random() - 0.5) * dispersion;

        randomScales[i] = 0.5 + Math.random() * 1.0;
        phases[i] = Math.random() * Math.PI * 2;
    }

    geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('aPositionTarget', new THREE.BufferAttribute(targetPositions, 3));
    geometry.setAttribute('aRandomScale', new THREE.BufferAttribute(randomScales, 1));
    geometry.setAttribute('aPhase', new THREE.BufferAttribute(phases, 1));

    material = new THREE.ShaderMaterial({
        vertexShader,
        fragmentShader,
        uniforms,
        transparent: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
    });

    const particles = new THREE.Points(geometry, material);
    scene.add(particles);

    // Render loop
    const clock = new THREE.Clock();

    const tick = () => {
        const elapsedTime = clock.getElapsedTime();
        uniforms.uTime.value = elapsedTime;

        if (particles) {
            particles.rotation.y = elapsedTime * 0.04;
            particles.rotation.x = Math.sin(elapsedTime * 0.02) * 0.08;
        }

        if (renderer && scene && camera) {
            renderer.render(scene, camera);
        }

        animationFrameId = requestAnimationFrame(tick);
    };

    tick();
};

const handleResize = () => {
    if (!renderer || !camera) return;
    const width = window.innerWidth;
    const height = window.innerHeight;

    camera.aspect = width / height;
    camera.updateProjectionMatrix();

    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    const isMobile = width < 768;
    uniforms.uSize.value = isMobile ? 16.0 : 24.0;
};

watch(() => props.progress, (newVal) => {
    uniforms.uProgress.value = newVal;
});

onMounted(() => {
    initThree();
    window.addEventListener('resize', handleResize);
});

onUnmounted(() => {
    window.removeEventListener('resize', handleResize);
    if (animationFrameId !== null) {
        cancelAnimationFrame(animationFrameId);
    }
    if (geometry) geometry.dispose();
    if (material) material.dispose();
    if (renderer) renderer.dispose();
});
</script>

<template>
    <canvas 
        ref="canvasRef" 
        id="three-wedding-canvas"
        class="fixed top-0 left-0 w-screen h-screen pointer-events-none z-10 transition-opacity duration-700"
        :style="{ opacity: canvasOpacity }"
    />
</template>
