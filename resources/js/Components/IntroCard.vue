<script setup lang="ts">
const { image, pull = "left" } = defineProps<{
    image: string;
    pull?: "left" | "right";
}>();

const maskClasses = [pull === "left" ? "md:mask-left" : "md:mask-right"];
const containerClasses =
    pull === "left"
        ? ["left-0", "items-start", "text-left"]
        : ["right-0", "items-end", "text-right"];
</script>

<template>
    <div
        class="my-2 h-[300px] overflow-hidden rounded-xl bg-cover bg-no-repeat md:h-[400px]"
        :style="{
            backgroundImage: 'url(' + image + ')',
        }"
    >
        <div class="mask-full relative h-full" :class="maskClasses">
            <div
                class="absolute flex h-full flex-col items-start justify-center gap-2 px-8 text-white md:w-[40%]"
                :class="containerClasses"
            >
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<style scoped>
.mask-full {
    background-color: hsla(0, 0%, 0%, 0.7);
}

@media (min-width: 768px) {
    .md\:mask-left {
        background: linear-gradient(
            90deg,
            hsla(0, 0%, 0%, 0.8) 30%,
            hsla(0, 0%, 0%, 0) 70%
        );
    }

    .md\:mask-right {
        background: linear-gradient(
            -90deg,
            hsla(0, 0%, 0%, 0.8) 30%,
            hsla(0, 0%, 0%, 0) 70%
        );
    }
}
</style>
