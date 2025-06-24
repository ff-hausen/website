<script setup lang="ts">
import { inject } from "vue";
import { route as ziggyRoute } from "ziggy-js";
import dayjs from "dayjs";
import { CalenderEvent } from "@/types/calendar";
import InformationModal from "@/Components/InformationModal.vue";
import Badge from "@/Components/Badge.vue";

const route = inject<typeof ziggyRoute>("route")!;

const props = defineProps<{
    events: Array<CalenderEvent>;
}>();

function formatStartTime(event: CalenderEvent): string {
    if (event.all_day) {
        return dayjs(event.start_time).format("dd, DD.MM.YYYY");
    }

    return dayjs(event.start_time).format("dd, DD.MM.YYYY HH:mm [Uhr]");
}
</script>

<template>
    <div class="my-8 px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-base leading-6 font-semibold text-gray-900">
                    Termine
                </h1>
                <p class="mt-2 text-sm text-gray-700">
                    Alle Termine der Einsatzabteilung.
                </p>
            </div>
        </div>
        <div class="mt-8 flow-root">
            <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div
                    class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8"
                >
                    <div
                        class="overflow-hidden shadow-sm ring-1 ring-black/5 sm:rounded-lg"
                    >
                        <table class="min-w-full divide-y divide-gray-300">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        Datum
                                    </th>
                                    <th scope="col"></th>
                                    <th
                                        scope="col"
                                        class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-6"
                                    >
                                        Titel
                                    </th>
                                    <th
                                        scope="col"
                                        class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                                    >
                                        Verantwortliche:r
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                <tr v-if="events.length === 0">
                                    <td
                                        class="px-3 py-4 text-center text-sm whitespace-nowrap text-gray-500"
                                        colspan="4"
                                    >
                                        Keine Termine vorhanden.
                                    </td>
                                </tr>
                                <tr v-for="event in events" :key="event.id">
                                    <td
                                        class="px-3 py-4 align-text-top text-sm whitespace-nowrap text-gray-500"
                                    >
                                        {{ formatStartTime(event) }}
                                    </td>
                                    <td>
                                        <InformationModal
                                            v-if="event.description"
                                            title="Details"
                                            :text="event.description"
                                        />
                                    </td>
                                    <td
                                        class="flex flex-col gap-1 py-4 pr-3 pl-4 align-text-top text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6"
                                    >
                                        <div class="ml-0.5">
                                            {{ event.title }}
                                        </div>
                                        <div>
                                            <Badge
                                                :background-color="
                                                    event.type.background_color
                                                "
                                                :text-color="
                                                    event.type.text_color
                                                "
                                                v-if="event.type"
                                            >
                                                {{ event.type.name }}
                                            </Badge>
                                        </div>
                                    </td>
                                    <td
                                        class="px-3 py-4 align-text-top text-sm whitespace-nowrap text-gray-500"
                                    >
                                        <div
                                            v-if="
                                                event.responsible.length === 0
                                            "
                                        >
                                            ?
                                        </div>
                                        <div
                                            v-for="person in event.responsible"
                                            :key="person.id"
                                        >
                                            {{ person.full_name }}
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
