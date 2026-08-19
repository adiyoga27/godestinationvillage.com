<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    value: { type: Number, required: true },
    duration: { type: Number, default: 1800 },
    prefix: { type: String, default: '' },
    suffix: { type: String, default: '' },
    decimals: { type: Number, default: 0 },
});

const display = ref(0);
const el = ref(null);
let observer = null;
let rafId = null;

const easeOutExpo = (t) => (t === 1 ? 1 : 1 - Math.pow(2, -10 * t));

const run = () => {
    const start = performance.now();
    const tick = (now) => {
        const progress = Math.min((now - start) / props.duration, 1);
        display.value = props.value * easeOutExpo(progress);
        if (progress < 1) {
            rafId = requestAnimationFrame(tick);
        } else {
            display.value = props.value;
        }
    };
    rafId = requestAnimationFrame(tick);
};

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            if (entries[0].isIntersecting) {
                run();
                observer.disconnect();
            }
        },
        { threshold: 0.4 }
    );
    if (el.value) observer.observe(el.value);
});

onBeforeUnmount(() => {
    observer?.disconnect();
    if (rafId) cancelAnimationFrame(rafId);
});
</script>

<template>
    <span ref="el">{{ prefix }}{{ display.toFixed(decimals) }}{{ suffix }}</span>
</template>