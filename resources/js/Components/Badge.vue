<script setup lang="ts">
const props = defineProps<{
    backgroundColor?: string;
    textColor?: string;
}>();

//   <span class="inline-flex items-center rounded-md bg-gray-100 px-2 py-1 text-xs font-medium text-gray-600">Badge</span>

const colorStyle = {
    backgroundColor: props.backgroundColor ?? "#f3f4f6",
    color: props.textColor ?? "#4b5563",
};
</script>

<template>
    <span
        class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium"
        :style="colorStyle"
    >
        <slot />
    </span>
</template>

<style scoped></style>
