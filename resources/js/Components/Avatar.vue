<script setup lang="ts">
import { createAvatar } from "@dicebear/core";
import { thumbs } from "@dicebear/collection";

const props = defineProps<{
    name: string;
}>();

const seed = new Date().toLocaleString();

const avatar = createAvatar(thumbs, {
    seed: props.name,
    radius: 50,
    shapeColor: ["b91c1c", "991b1b", "7f1d1d"],
    backgroundColor: ["fee2e2", "fecaca", "fca5a5", "f87171"],
});
</script>

<template>
    <span v-html="avatar.toString()"></span>
</template>

<style scoped></style>
