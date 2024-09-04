<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
})

const props = defineProps<{
    image: string
}>()
</script>

<template>
    <div
        v-bind="$attrs"
        class="absolute left-0 w-full bg-cover bg-no-repeat"
        :style="{
            backgroundImage: 'url(' + props.image + ')',
        }"
    >
        <div class="bg-[hsla(0,0%,0%,0.6)] h-full">
            <div class="flex flex-col h-full text-white justify-center items-center text-center font-black px-4">
                <slot></slot>
            </div>
        </div>
    </div>
    <div v-bind="$attrs" class="invisible"></div>
</template>

<style scoped>

</style>
