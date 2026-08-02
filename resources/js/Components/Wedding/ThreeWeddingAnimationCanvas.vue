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
let animationFrameId: number | null = null;

// 3D Objects Group
let ringGroom: THREE.Mesh | null = null;
let ringBride: THREE.Mesh | null = null;
let waxSealMedallion: THREE.Mesh | null = null;
let heartGem: THREE.Mesh | null = null;
let particles: THREE.Points | null = null;

// Smooth Lerp Variables for 60fps Scroll Tracking
let targetProgress = 0;
let currentProgress = 0;

const uniforms = {
    uProgress: { value: 0.0 },
    uTime: { value: 0.0 },
    uSize: { value: 20.0 },
    uColorPhase1: { value: new THREE.Color('#F59E0B') }, // Champagne Gold
    uColorPhase2: { value: new THREE.Color('#EC4899') }, // Soft Rose Gold
    uColorPhase3: { value: new THREE.Color('#881337') }, // Deep Rosewood
};

// Custom GLSL Shaders for Particle Background
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
    
    float easedProgress = smoothstep(0.0, 1.0, uProgress);
    vec3 mixedPosition = mix(position, aPositionTarget, easedProgress);
    
    float waveX = sin(mixedPosition.y * 3.0 + uTime * 1.8 + aPhase) * 0.15 * (1.0 - easedProgress * 0.3);
    float waveY = cos(mixedPosition.x * 3.0 + uTime * 1.5 + aPhase) * 0.15 * (1.0 - easedProgress * 0.3);
    float waveZ = sin(mixedPosition.z * 4.0 + uTime * 2.0) * 0.1;
    
    mixedPosition += vec3(waveX, waveY, waveZ);

    vec4 modelPosition = modelMatrix * vec4(mixedPosition, 1.0);
    vec4 viewPosition = viewMatrix * modelPosition;
    vec4 projectedPosition = projectionMatrix * viewPosition;

    gl_Position = projectedPosition;
    
    float sizeFactor = (1.0 + sin(uTime * 2.0 + aPhase) * 0.25) * aRandomScale;
    gl_PointSize = uSize * sizeFactor * (1.0 + uProgress * 0.4) * (1.0 / -viewPosition.z);
}
`;

const fragmentShader = `
uniform vec3 uColorPhase1;
uniform vec3 uColorPhase2;
uniform vec3 uColorPhase3;
uniform float uProgress;

varying float vProgress;
varying float vRandom;

void main() {
    float distanceToCenter = length(gl_PointCoord - vec2(0.5));
    float strength = 0.08 / (distanceToCenter + 0.05) - 0.08;
    strength = clamp(strength, 0.0, 1.0);

    vec3 colorMix;
    if (vProgress < 0.5) {
        colorMix = mix(uColorPhase1, uColorPhase2, vProgress * 2.0);
    } else {
        colorMix = mix(uColorPhase2, uColorPhase3, (vProgress - 0.5) * 2.0);
    }

    float alpha = strength * (0.6 + sin(vRandom * 10.0) * 0.2 + vProgress * 0.3);
    gl_FragColor = vec4(colorMix, clamp(alpha, 0.0, 0.85));
}
`;

const initThree = () => {
    if (!canvasRef.value) return;

    const width = window.innerWidth;
    const height = window.innerHeight;

    renderer = new THREE.WebGLRenderer({
        canvas: canvasRef.value,
        alpha: true,
        antialias: true,
        powerPreference: 'high-performance',
    });
    renderer.setSize(width, height);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    renderer.setClearColor(0x000000, 0);

    scene = new THREE.Scene();

    camera = new THREE.PerspectiveCamera(55, width / height, 0.1, 100);
    camera.position.set(0, 0, 5.5);

    // --- LIGHTING FOR 3D MODELS ---
    const ambientLight = new THREE.AmbientLight(0xffffff, 1.2);
    scene.add(ambientLight);

    const dirLight1 = new THREE.DirectionalLight(0xffdfb0, 2.5); // Warm Gold Light
    dirLight1.position.set(5, 5, 4);
    scene.add(dirLight1);

    const dirLight2 = new THREE.DirectionalLight(0xffb0d0, 2.0); // Rose Light
    dirLight2.position.set(-5, -3, 2);
    scene.add(dirLight2);

    const pointLight = new THREE.PointLight(0xf59e0b, 3, 10);
    pointLight.position.set(0, 0, 3);
    scene.add(pointLight);

    // --- 3D MODEL 1: GROOM WEDDING RING (Dát Vàng 24K) ---
    const ringGeoGroom = new THREE.TorusGeometry(0.55, 0.09, 32, 64);
    const goldMaterial = new THREE.MeshStandardMaterial({
        color: 0xf59e0b,
        metalness: 0.9,
        roughness: 0.15,
        wireframe: false,
    });
    ringGroom = new THREE.Mesh(ringGeoGroom, goldMaterial);
    ringGroom.position.set(2.2, 1.2, 0);
    ringGroom.rotation.x = Math.PI / 4;
    scene.add(ringGroom);

    // --- 3D MODEL 2: BRIDE WEDDING RING (Đính Kim Cương Rose Gold) ---
    const ringGeoBride = new THREE.TorusGeometry(0.42, 0.07, 32, 64);
    const roseGoldMaterial = new THREE.MeshStandardMaterial({
        color: 0xec4899,
        metalness: 0.85,
        roughness: 0.2,
    });
    ringBride = new THREE.Mesh(ringGeoBride, roseGoldMaterial);
    ringBride.position.set(2.7, 1.0, 0.3);
    ringBride.rotation.y = Math.PI / 3;
    scene.add(ringBride);

    // --- 3D MODEL 3: 3D WAX SEAL MEDALLION (Tem Sáp Niêm Phong) ---
    const sealGeo = new THREE.CylinderGeometry(0.45, 0.45, 0.08, 32);
    const sealMaterial = new THREE.MeshStandardMaterial({
        color: 0x881337,
        metalness: 0.6,
        roughness: 0.3,
    });
    waxSealMedallion = new THREE.Mesh(sealGeo, sealMaterial);
    waxSealMedallion.position.set(-2.4, -1.0, -0.5);
    waxSealMedallion.rotation.x = Math.PI / 2;
    scene.add(waxSealMedallion);

    // --- 3D MODEL 4: 3D HEART GEMSTONE ---
    const heartShape = new THREE.Shape();
    const x = 0, y = 0;
    heartShape.moveTo(x + 0.25, y + 0.25);
    heartShape.bezierCurveTo(x + 0.25, y + 0.25, x + 0.2, y, x, y);
    heartShape.bezierCurveTo(x - 0.3, y, x - 0.3, y + 0.35, x - 0.3, y + 0.35);
    heartShape.bezierCurveTo(x - 0.3, y + 0.55, x - 0.1, y + 0.77, x + 0.25, y + 0.95);
    heartShape.bezierCurveTo(x + 0.6, y + 0.77, x + 0.8, y + 0.55, x + 0.8, y + 0.35);
    heartShape.bezierCurveTo(x + 0.8, y + 0.35, x + 0.8, y, x + 0.5, y);
    heartShape.bezierCurveTo(x + 0.35, y, x + 0.25, y + 0.25, x + 0.25, y + 0.25);

    const extrudeSettings = { depth: 0.1, bevelEnabled: true, bevelSegments: 3, steps: 1, bevelSize: 0.05, bevelThickness: 0.05 };
    const heartGeo = new THREE.ExtrudeGeometry(heartShape, extrudeSettings);
    heartGeo.center();
    
    const heartMat = new THREE.MeshStandardMaterial({
        color: 0xf43f5e,
        metalness: 0.7,
        roughness: 0.2,
    });
    heartGem = new THREE.Mesh(heartGeo, heartMat);
    heartGem.scale.set(0.6, 0.6, 0.6);
    heartGem.position.set(-2.0, 1.5, -1.0);
    scene.add(heartGem);

    // --- 3D PARTICLES BACKGROUND ---
    const count = width < 768 ? 6000 : 18000;
    uniforms.uSize.value = width < 768 ? 16.0 : 24.0;

    const positions = new Float32Array(count * 3);
    const targetPositions = new Float32Array(count * 3);
    const randomScales = new Float32Array(count);
    const phases = new Float32Array(count);

    for (let i = 0; i < count; i++) {
        const i3 = i * 3;
        const radius = 1.5 + Math.random() * 2.2;
        const theta = Math.random() * Math.PI * 2;
        const phi = Math.acos((Math.random() * 2) - 1);

        positions[i3] = radius * Math.sin(phi) * Math.cos(theta);
        positions[i3 + 1] = radius * Math.sin(phi) * Math.sin(theta);
        positions[i3 + 2] = radius * Math.cos(phi);

        const t = Math.random() * Math.PI * 2;
        const streamType = i % 2 === 0 ? 1 : -1;
        const scale = 0.12;
        const heartX = 16 * Math.pow(Math.sin(t), 3) * scale;
        const heartY = (13 * Math.cos(t) - 5 * Math.cos(2 * t) - 2 * Math.cos(3 * t) - Math.cos(4 * t)) * scale;
        const heartZ = streamType * Math.sin(t * 2) * 0.4 + (Math.random() - 0.5) * 0.3;

        targetPositions[i3] = heartX + (Math.random() - 0.5) * 0.15;
        targetPositions[i3 + 1] = heartY + (Math.random() - 0.5) * 0.15;
        targetPositions[i3 + 2] = heartZ + (Math.random() - 0.5) * 0.15;

        randomScales[i] = 0.5 + Math.random() * 1.0;
        phases[i] = Math.random() * Math.PI * 2;
    }

    const geometry = new THREE.BufferGeometry();
    geometry.setAttribute('position', new THREE.BufferAttribute(positions, 3));
    geometry.setAttribute('aPositionTarget', new THREE.BufferAttribute(targetPositions, 3));
    geometry.setAttribute('aRandomScale', new THREE.BufferAttribute(randomScales, 1));
    geometry.setAttribute('aPhase', new THREE.BufferAttribute(phases, 1));

    const material = new THREE.ShaderMaterial({
        vertexShader,
        fragmentShader,
        uniforms,
        transparent: true,
        depthWrite: false,
        blending: THREE.AdditiveBlending,
    });

    particles = new THREE.Points(geometry, material);
    scene.add(particles);

    // --- CONTINUOUS 60FPS RENDER & SCROLL ANIMATION TRACKING LOOP ---
    const clock = new THREE.Clock();

    const tick = () => {
        const elapsedTime = clock.getElapsedTime();
        uniforms.uTime.value = elapsedTime;

        // Smooth Lerp for 3D model scroll progress tracking
        currentProgress += (targetProgress - currentProgress) * 0.08;
        uniforms.uProgress.value = currentProgress;

        // 1. Move & Rotate 3D Wedding Rings along Scroll Path
        if (ringGroom && ringBride) {
            // Ring Groom flies from top-right down to center-left
            ringGroom.position.x = 2.2 - currentProgress * 4.4;
            ringGroom.position.y = 1.2 - currentProgress * 2.8;
            ringGroom.position.z = Math.sin(currentProgress * Math.PI) * 1.2;

            ringGroom.rotation.x = elapsedTime * 0.5 + currentProgress * Math.PI * 2;
            ringGroom.rotation.y = elapsedTime * 0.8 + currentProgress * Math.PI;

            // Ring Bride intertwines with Groom Ring
            ringBride.position.x = ringGroom.position.x + 0.35;
            ringBride.position.y = ringGroom.position.y - 0.2;
            ringBride.position.z = ringGroom.position.z + 0.2;

            ringBride.rotation.x = -elapsedTime * 0.6 + currentProgress * Math.PI * 2;
            ringBride.rotation.z = elapsedTime * 0.7;
        }

        // 2. Move & Spin 3D Wax Seal Medallion along Scroll Path
        if (waxSealMedallion) {
            waxSealMedallion.position.x = -2.4 + currentProgress * 4.8;
            waxSealMedallion.position.y = -1.0 + currentProgress * 2.2;
            waxSealMedallion.rotation.z = elapsedTime * 0.4 + currentProgress * Math.PI * 4;
            waxSealMedallion.rotation.y = Math.sin(currentProgress * Math.PI * 2) * 0.5;
        }

        // 3. Move & Float 3D Heart Gem
        if (heartGem) {
            heartGem.position.x = -2.0 + Math.sin(currentProgress * Math.PI * 1.5) * 3.5;
            heartGem.position.y = 1.5 - currentProgress * 3.2;
            heartGem.rotation.y = elapsedTime * 0.6 + currentProgress * Math.PI * 2;
            heartGem.rotation.z = Math.cos(elapsedTime * 0.5) * 0.3;
        }

        // 4. Rotate Background Particle Cloud
        if (particles) {
            particles.rotation.y = elapsedTime * 0.03 + currentProgress * 0.5;
            particles.rotation.x = Math.sin(elapsedTime * 0.02) * 0.08;
        }

        // 5. Dynamic Camera Pan & Zoom on Scroll
        if (camera) {
            camera.position.y = -currentProgress * 0.8;
            camera.position.z = 5.5 - Math.sin(currentProgress * Math.PI) * 0.8;
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
    targetProgress = newVal;
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
