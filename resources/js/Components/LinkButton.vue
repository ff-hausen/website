<script setup lang="ts">
import { InertiaLinkProps, Link } from "@inertiajs/vue3";

defineProps<Omit<InertiaLinkProps, "as" | "type">>();
</script>

<template>
    <Link as="button" type="button" v-bind="$props">
        <slot />
    </Link>
</template>

<style scoped></style>
