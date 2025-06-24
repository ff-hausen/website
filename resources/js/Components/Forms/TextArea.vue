<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});
const model = defineModel();

const props = defineProps<{
    id: string;
    rows?: number;
    placeholder?: string;
}>();
</script>

<template>
    <div class="mb-5">
        <label
            :for="id"
            class="mb-2 block text-sm font-medium text-gray-900 dark:text-white"
        >
            <slot />
        </label>
        <textarea
            :id="id"
            :rows="rows ?? 4"
            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white dark:placeholder-gray-400 dark:focus:border-blue-500 dark:focus:ring-blue-500"
            :placeholder="placeholder"
            required
            v-model="model"
            v-bind="$attrs"
        />
    </div>
</template>

<style scoped></style>
