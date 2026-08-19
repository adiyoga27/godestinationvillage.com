<script setup>
import { ref } from 'vue';

const props = defineProps({
    images: { type: Array, default: () => [] },
    alt: { type: String, default: '' },
});

const active = ref(0);

const prev = () => {
    active.value = (active.value - 1 + props.images.length) % props.images.length;
};
const next = () => {
    active.value = (active.value + 1) % props.images.length;
};
</script>

<template>
    <div class="overflow-hidden rounded-3xl border border-ink-100 bg-ink-50 shadow-[0_25px_50px_-12px_rgb(26_26_38/0.25)]">
        <div class="relative aspect-[16/10] w-full overflow-hidden">
            <transition name="fade">
                <img
                    v-for="(img, i) in images"
                    v-show="i === active"
                    :key="i"
                    :src="img"
                    :alt="alt"
                    class="absolute inset-0 h-full w-full object-cover"
                    loading="lazy"
                />
            </transition>

            <button
                v-if="images.length > 1"
                type="button"
                aria-label="Previous image"
                class="absolute left-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-ink-800 shadow-lg backdrop-blur transition hover:bg-white"
                @click="prev"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" /></svg>
            </button>
            <button
                v-if="images.length > 1"
                type="button"
                aria-label="Next image"
                class="absolute right-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/90 text-ink-800 shadow-lg backdrop-blur transition hover:bg-white"
                @click="next"
            >
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
            </button>

            <span v-if="images.length > 1" class="absolute bottom-4 right-4 rounded-full bg-ink-950/70 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                {{ active + 1 }} / {{ images.length }}
            </span>
        </div>

        <div
            v-if="images.length > 1"
            class="flex gap-3 overflow-x-auto bg-white p-3"
        >
            <button
                v-for="(img, i) in images"
                :key="i"
                type="button"
                :aria-label="`View image ${i + 1}`"
                class="h-16 w-20 flex-shrink-0 overflow-hidden rounded-xl transition"
                :class="i === active ? 'ring-2 ring-brand-600 ring-offset-2' : 'opacity-60 hover:opacity-100'"
                @click="active = i"
            >
                <img :src="img" :alt="`${alt} ${i + 1}`" class="h-full w-full object-cover" loading="lazy" />
            </button>
        </div>
    </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.35s ease;
}
.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style>