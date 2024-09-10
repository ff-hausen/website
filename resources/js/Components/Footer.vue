<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
</script>

<template>
    <footer class="mt-6 w-full px-4 sm:px-8">
        <!--        class="mx-4 flex max-w-6xl flex-col items-center justify-between border-t border-solid border-gray-200 py-6 leading-6 md:mx-12 md:flex-row"-->
        <div
            class="mx-auto flex max-w-6xl flex-col items-center justify-between border-t border-gray-200 py-6 leading-6 md:flex-row"
        >
            <div class="flex text-center text-sm text-slate-600">
                <h2 class="sr-only mr-4">Footer</h2>
                <p>&copy; 2024 Freiwillige Feuerwehr Frankfurt Hausen e.V.</p>
            </div>
            <div class="leading-none">&nbsp;</div>
            <div
                class="flex flex-wrap items-center justify-evenly text-sm font-semibold text-slate-900"
            >
                <Link :href="route('impressum')">Impressum</Link>
                <!--            <div class="mx-4 h-4 w-px bg-slate-200 leading-6"></div>-->
            </div>
        </div>
    </footer>
</template>

<style scoped></style>
