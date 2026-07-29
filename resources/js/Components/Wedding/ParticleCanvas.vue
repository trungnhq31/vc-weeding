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
    uColorDark: { value: new THREE.Color('#777777') },
    uColorLight: { value: new THREE.Color('#F59E0B') },
};

const vertexShader = `
attribute vec3 aPositionTarget;
uniform float uProgress;
uniform float uTime;
uniform float uSize;

varying float vProgress;

void main() {
    vProgress = uProgress;
    
    vec3 mixedPosition = mix(position, aPositionTarget, uProgress);
    
    // Liquid wave distortion during phase 2 & 3
    float wave = sin(mixedPosition.y * 6.0 + uTime * 2.0 + uProgress * 5.0) * (1.0 - uProgress * 0.4) * 0.2;
    mixedPosition.x += wave;
    mixedPosition.z += cos(mixedPosition.x * 5.0 + uTime * 1.5) * (1.0 - uProgress * 0.4) * 0.2;

    vec4 modelPosition = modelMatrix * vec4(mixedPosition, 1.0);
    vec4 viewPosition = viewMatrix * modelPosition;
    vec4 projectedPosition = projectionMatrix * viewPosition;

    gl_Position = projectedPosition;
    
    gl_PointSize = uSize * (1.0 + uProgress * 0.5) * (1.0 / -viewPosition.z);
}
`;

const fragmentShader = `
uniform vec3 uColorDark;
uniform vec3 uColorLight;
uniform float uProgress;

varying float vProgress;

void main() {
    float distanceToCenter = length(gl_PointCoord - vec2(0.5));
    float strength = 0.06 / (distanceToCenter + 0.04) - 0.06;
    strength = clamp(strength, 0.0, 1.0);

    vec3 finalColor = mix(uColorDark, uColorLight, vProgress);
    gl_FragColor = vec4(finalColor, strength * mix(0.65, 0.9, vProgress));
}
`;

const initThree = () => {
    if (!canvasRef.value) return;

    const width = window.innerWidth;
    const height = window.innerHeight;

    // Renderer setup
    renderer = new THREE.WebGLRenderer({
        canvas: canvasRef.value,
        alpha: true,
        antialias: true,
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);

    // Scene & Camera
    scene = new THREE.Scene();
    camera = new THREE.PerspectiveCamera(60, width / height, 0.1, 100);
    camera.position.z = 4;

    // Particle Count based on Mobile/Desktop
    const isMobile = width < 768;
    const count = isMobile ? 9000 : 35000;
    uniforms.uSize.value = isMobile ? 14.0 : 22.0;

    const positions = new Float32Array(count * 3);
    const targetPositions = new Float32Array(count * 3);

    for (let i = 0; i < count; i++) {
        const i3 = i * 3;

        // Dark initial state: Solitary ash cloud
        const radius = 1.2 + Math.random() * 1.5;
        const theta = Math.random() * Math.PI * 2;
        const phi = Math.acos((Math.random() * 2) - 1);

        positions[i3] = radius * Math.sin(phi) * Math.cos(theta);
        positions[i3 + 1] = radius * Math.sin(phi) * Math.sin(theta);
        positions[i3 + 2] = radius * Math.cos(phi);

        // Light target state: Expanded sparkle ring & luminous aura
        const targetRadius = 2.5 + Math.random() * 3.0;
        const targetTheta = Math.random() * Math.PI * 2;
        const targetPhi = Math.acos((Math.random() * 2) - 1);

        targetPositions[i3] = targetRadius * Math.sin(targetPhi) * Math.cos(targetTheta);
        targetPositions[i3 + 1] = targetRadius * Math.sin(targetPhi) * Math.sin(targetTheta);
        targetPositions[i3 + 2] = targetRadius * Math.cos(targetPhi);
    }

    geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('aPositionTarget', new THREE.BufferAttribute(targetPositions, 3));

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

    // Animation Loop
    const clock = new THREE.Clock();

    const tick = () => {
        const elapsedTime = clock.getElapsedTime();
        uniforms.uTime.value = elapsedTime;

        if (particles) {
            particles.rotation.y = elapsedTime * 0.05;
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
    uniforms.uSize.value = isMobile ? 14.0 : 22.0;
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
        id="webgl-canvas"
        class="fixed top-0 left-0 w-screen h-screen pointer-events-none z-10 transition-opacity duration-700"
        :style="{ opacity: canvasOpacity }"
    />
</template>
