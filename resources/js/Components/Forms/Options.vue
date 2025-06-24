<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
});
const model = defineModel();

const props = defineProps<{
    id: string;
    values: {
        [key: string]: string;
    };
    legend?: string;
    bordered?: boolean;
    required?: boolean;
}>();
</script>

<template>
    <fieldset
        :class="{
            'rounded-xl border px-2 pt-4': bordered,
        }"
    >
        <legend
            v-if="legend"
            class="text-sm font-medium text-gray-900"
        >
            {{ legend }}
        </legend>

        <template v-for="(label, value) in values">
            <div class="mb-4 flex items-center">
                <input
                    :id="id + '-' + value"
                    type="radio"
                    :name="id"
                    :value="value"
                    class="h-4 w-4 border-gray-300 focus:ring-2 focus:ring-blue-300 "
                    :checked="model === value"
                    @change="model = value"
                    :required="required"
                />
                <label
                    :for="id + '-' + value"
                    class="ms-2 block text-sm font-medium text-gray-900 "
                >
                    {{ label }}
                </label>
            </div>
        </template>
    </fieldset>
</template>

<style scoped></style>
