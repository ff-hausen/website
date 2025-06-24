<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});

defineProps<{
    type?: "button" | "submit" | "reset";
    disabled?: boolean;
}>();

const emit = defineEmits<{
    (e: "click"): void;
}>();
</script>

<template>
    <button
        :type="type ?? 'button'"
        :class="{
            'cursor-not-allowed bg-blue-400 dark:bg-blue-500': disabled,
            'cursor-pointer bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 focus:outline-hidden dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800':
                !disabled,
            'me-2 inline-flex items-center': true, // for icons
            'w-full rounded-lg px-5 py-2.5 text-center text-sm font-medium text-white sm:w-auto': true, // default
        }"
        :disabled="disabled"
        v-bind="$attrs"
        @click="emit('click')"
    >
        <slot />
    </button>
</template>

<style scoped></style>
