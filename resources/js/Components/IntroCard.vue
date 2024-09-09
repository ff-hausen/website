<script setup lang="ts">
const { image, pull = "left" } = defineProps<{
    image: string;
    pull?: "left" | "right";
}>();
</script>

<template>
    <div
        class="my-2 h-[400px] overflow-hidden rounded-xl bg-cover bg-no-repeat"
        :style="{
            backgroundImage: 'url(' + image + ')',
        }"
    >
        <div class="relative h-full" :class="'mask-' + pull">
            <div
                class="absolute flex h-full flex-col items-start justify-center px-8 text-white"
                :class="pull + '-0'"
            >
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>
.mask-left {
    background: linear-gradient(
        90deg,
        hsla(0, 0%, 0%, 1),
        hsla(0, 0%, 0%, 0.8) 30%,
        hsla(0, 0%, 0%, 0.4) 60%,
        hsla(0, 0%, 0%, 0) 100%
    );
}

.mask-right {
    background: linear-gradient(
        -90deg,
        hsla(0, 0%, 0%, 1),
        hsla(0, 0%, 0%, 0.8) 30%,
        hsla(0, 0%, 0%, 0.4) 60%,
        hsla(0, 0%, 0%, 0) 100%
    );
}
</style>
