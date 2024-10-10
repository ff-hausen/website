<script setup lang="ts">
import { inject, onMounted, Ref, ref } from "vue";
import { route as ziggyRoute } from "ziggy-js";
import dayjs from "dayjs";
import { CalenderEvent } from "@/types/calendar";
import InformationModal from "@/Components/InformationModal.vue";
import Badge from "@/Components/Badge.vue";
import axios from "axios";

const route = inject<typeof ziggyRoute>("route")!;

const props = defineProps<{
    department: string;
}>();

const events: Ref<Array<CalenderEvent>> = ref([]);

const dataIsLoading = ref(true);

onMounted(async () => {
    const result = await axios.get(route("calendar.index"), {
        params: {
            department: props.department,
        },
    });
    events.value = result.data.data;
    dataIsLoading.value = false;
});

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
                <h1 class="text-base font-semibold leading-6 text-gray-900">
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
                    <div v-if="dataIsLoading">
                        <div
                            role="status"
                            class="flex items-center justify-center gap-2"
                        >
                            <svg
                                aria-hidden="true"
                                class="h-6 w-6 animate-spin fill-red-600 text-gray-200 dark:text-gray-600"
                                viewBox="0 0 100 101"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                            >
                                <path
                                    d="M100 50.5908C100 78.2051 77.6142 100.591 50 100.591C22.3858 100.591 0 78.2051 0 50.5908C0 22.9766 22.3858 0.59082 50 0.59082C77.6142 0.59082 100 22.9766 100 50.5908ZM9.08144 50.5908C9.08144 73.1895 27.4013 91.5094 50 91.5094C72.5987 91.5094 90.9186 73.1895 90.9186 50.5908C90.9186 27.9921 72.5987 9.67226 50 9.67226C27.4013 9.67226 9.08144 27.9921 9.08144 50.5908Z"
                                    fill="currentColor"
                                />
                                <path
                                    d="M93.9676 39.0409C96.393 38.4038 97.8624 35.9116 97.0079 33.5539C95.2932 28.8227 92.871 24.3692 89.8167 20.348C85.8452 15.1192 80.8826 10.7238 75.2124 7.41289C69.5422 4.10194 63.2754 1.94025 56.7698 1.05124C51.7666 0.367541 46.6976 0.446843 41.7345 1.27873C39.2613 1.69328 37.813 4.19778 38.4501 6.62326C39.0873 9.04874 41.5694 10.4717 44.0505 10.1071C47.8511 9.54855 51.7191 9.52689 55.5402 10.0491C60.8642 10.7766 65.9928 12.5457 70.6331 15.2552C75.2735 17.9648 79.3347 21.5619 82.5849 25.841C84.9175 28.9121 86.7997 32.2913 88.1811 35.8758C89.083 38.2158 91.5421 39.6781 93.9676 39.0409Z"
                                    fill="currentFill"
                                />
                            </svg>
                            Termine werden geladen...
                        </div>
                    </div>
                    <div
                        class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 sm:rounded-lg"
                        v-if="!dataIsLoading"
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
                                        class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6"
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
                                        class="whitespace-nowrap px-3 py-4 text-center text-sm text-gray-500"
                                        colspan="3"
                                    >
                                        Keine Termine vorhanden.
                                    </td>
                                </tr>
                                <tr v-for="event in events" :key="event.id">
                                    <td
                                        class="whitespace-nowrap px-3 py-4 align-text-top text-sm text-gray-500"
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
                                        class="flex flex-col gap-1 whitespace-nowrap py-4 pl-4 pr-3 align-text-top text-sm font-medium text-gray-900 sm:pl-6"
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
                                        class="whitespace-nowrap px-3 py-4 align-text-top text-sm text-gray-500"
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
