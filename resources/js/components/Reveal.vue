<script setup>
import { ref, onMounted, onBeforeUnmount } from 'vue';

const props = defineProps({
    delay: { type: Number, default: 0 },
    y: { type: Number, default: 24 },
    as: { type: String, default: 'div' },
});

const el = ref(null);
let observer = null;
const visible = ref(false);

onMounted(() => {
    observer = new IntersectionObserver(
        (entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    visible.value = true;
                    observer.disconnect();
                }
            });
        },
        { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
    );
    if (el.value) observer.observe(el.value);
});

onBeforeUnmount(() => observer?.disconnect());
</script>

<template>
    <component
        :is="as"
        ref="el"
        class="transition-all duration-700 ease-out"
        :class="visible
            ? 'translate-y-0 opacity-100'
            : 'opacity-0'"
        :style="!visible ? { transform: `translateY(${y}px)` } : {}"
    >
        <slot />
    </component>
</template>