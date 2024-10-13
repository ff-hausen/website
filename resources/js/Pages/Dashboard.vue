<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Calendar from "@/Components/Calendar.vue";
import { CalenderEvent } from "@/types/calendar";

defineProps<{
    events: Array<CalenderEvent>;
}>();
</script>

<template>
    <Head title="Dashboard" />

    <MainLayout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800">
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <Calendar :events="events" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>
