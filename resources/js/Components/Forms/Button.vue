<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});

withDefaults(
    defineProps<{
        type?: "button" | "submit" | "reset";
        disabled?: boolean;
        color?: "blue" | "alternative";
        size?: "small" | "default";
    }>(),
    {
        color: "blue",
        size: "default",
    },
);

const emit = defineEmits<{
    (e: "click"): void;
}>();
</script>

<!--  -->
<template>
    <button
        :type="type ?? 'button'"
        :class="{
            'bg-blue-700 text-white hover:bg-blue-800 focus:ring-blue-300':
                color === 'blue' && !disabled,
            'bg-blue-400 text-white':
                color === 'blue' && disabled,

            'border border-gray-200 bg-white text-gray-900 hover:bg-gray-100 hover:text-blue-700 focus:ring-gray-100 ':
                color === 'alternative' && !disabled,

            'cursor-not-allowed': disabled,
            'cursor-pointer focus:ring-4 focus:outline-hidden': !disabled,

            'px-5 py-2.5 text-sm': size === 'default',
            'px-3 py-2 text-xs': size === 'small',

            'w-full rounded-lg text-center font-medium sm:w-auto': true, // default
            'me-2 inline-flex items-center': true, // for icons
        }"
        :disabled="disabled"
        v-bind="$attrs"
        @click.prevent="emit('click')"
    >
        <slot />
    </button>
</template>

<style scoped></style>
