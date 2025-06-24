<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});
const model = defineModel();

const props = defineProps<{
    id: string;
    placeholder?: string;
    type?: string;
}>();
</script>

<template>
    <div class="mb-5">
        <label
            :for="id"
            class="mb-2 block text-sm font-medium text-gray-900"
        >
            <slot />
        </label>
        <input
            :type="type ?? 'text'"
            :id="id"
            class="block w-full rounded-lg border border-gray-300 bg-gray-50 p-2.5 text-sm text-gray-900 focus:border-blue-500 focus:ring-blue-500 "
            :placeholder="placeholder"
            v-model="model"
            v-bind="$attrs"
        />
    </div>
</template>

<style scoped></style>
