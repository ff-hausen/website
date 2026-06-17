<script setup lang="ts">
import { Participant } from "@/Pages/Vereinsausflug/Anmeldung.vue";
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";

const props = defineProps<{
    participants: Participant[];
}>();

function type(type: "ea" | "verein" | null): string {
    switch (type) {
        case "ea":
            return "Einsatzabteilung";
        case "verein":
        default:
            return "Verein/Freunde";
    }
}

function price(participant: Participant): number {
    switch (participant.type) {
        case "ea":
            return 90;
        case "verein":
        default:
            return 150;
    }
}

const totalAmount = computed(() => {
    return props.participants.reduce((c, p) => c + (p.price ?? 0), 0);
});

const outstandingAmount = computed(() => {
    return props.participants
        .filter((p) => p.paid_at === null)
        .reduce((c, p) => c + (p.price ?? 0), 0);
});
</script>

<template>
    <Head title="Vereinsausflug Anmeldung Bestätigung" />

    <MainLayout>
        <h1 class="mt-4 mb-8 text-2xl font-bold">Anmeldung erfolgreich</h1>

        <p class="mb-4">
            Deine Anmeldung ist bestätigt. Die folgende Zusammenfassung hast du
            auch per Mail erhalten.
        </p>

        <!-- Desktop Tabelle -->
        <div class="hidden overflow-x-auto md:block">
            <table class="min-w-full divide-y divide-gray-300">
                <thead>
                    <tr>
                        <th
                            scope="col"
                            class="py-3.5 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:pl-0"
                        >
                            Name
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                            Adresse
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                            E-Mail/Telefon
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900"
                        >
                            Vereinsmitgliedschaft
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3.5 text-right text-sm font-semibold text-gray-900"
                        >
                            Betrag
                        </th>
                        <th
                            scope="col"
                            class="px-3 py-3.5 text-center text-sm font-semibold text-gray-900"
                        >
                            Bezahlt?
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr
                        v-for="participant in participants"
                        :key="participant.name!"
                    >
                        <td
                            class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-0"
                        >
                            {{ participant.name }}
                        </td>
                        <td
                            class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                        >
                            <div>{{ participant.street }}</div>
                            <div>
                                {{ participant.zip_code }}
                                {{ participant.city }}
                            </div>
                        </td>
                        <td
                            class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                        >
                            <div>{{ participant.email ?? "&mdash;" }}</div>
                            <div>{{ participant.phone ?? "&mdash;" }}</div>
                        </td>
                        <td
                            class="px-3 py-4 text-sm whitespace-nowrap text-gray-500"
                        >
                            {{ type(participant.type) }}
                        </td>
                        <td
                            class="px-3 py-4 text-right text-sm whitespace-nowrap text-gray-500"
                        >
                            {{ participant.price }} €
                        </td>
                        <td
                            class="px-3 py-4 text-center text-sm whitespace-nowrap"
                        >
                            <span
                                v-if="participant.paid_at !== null"
                                class="flex justify-center"
                            >
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="size-6 text-green-500"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>
                            </span>
                            <span v-else class="flex justify-center">
                                <svg
                                    xmlns="http://www.w3.org/2000/svg"
                                    fill="none"
                                    viewBox="0 0 24 24"
                                    stroke-width="1.5"
                                    stroke="currentColor"
                                    class="size-6 text-red-500"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                                    />
                                </svg>
                            </span>
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <th
                            scope="row"
                            colspan="4"
                            class="px-3 py-2 text-right text-sm font-semibold text-gray-900 sm:pl-0"
                        >
                            Gesamtbetrag
                        </th>
                        <td
                            class="px-3 py-2 text-right text-sm font-semibold text-gray-900"
                        >
                            {{ totalAmount }} €
                        </td>
                    </tr>
                    <tr>
                        <th
                            scope="row"
                            colspan="4"
                            class="px-3 py-2 text-right text-sm font-semibold text-gray-900 sm:pl-0"
                        >
                            Offener Betrag
                        </th>
                        <td
                            class="px-3 py-2 text-right text-sm font-semibold text-gray-900"
                        >
                            {{ outstandingAmount }} €
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <!-- Mobile Kartenansicht -->
        <div class="space-y-4 md:hidden">
            <div
                v-for="participant in participants"
                :key="participant.name!"
                class="rounded-lg border border-gray-300 bg-white p-4"
            >
                <div class="mb-3 flex items-start justify-between">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900">
                            {{ participant.name }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-500">
                            {{ type(participant.type) }}
                        </p>
                    </div>
                    <div
                        v-if="participant.paid_at !== null"
                        class="flex items-center gap-2 rounded-full border border-green-200 bg-green-50 px-3 py-1"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4 text-green-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                        <span class="text-xs font-medium text-green-700"
                            >Bezahlt</span
                        >
                    </div>
                    <div
                        v-else
                        class="flex items-center gap-2 rounded-full border border-red-200 bg-red-50 px-3 py-1"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            class="size-4 text-red-600"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"
                            />
                        </svg>
                        <span class="text-xs font-medium text-red-700"
                            >Ausstehend</span
                        >
                    </div>
                </div>

                <div class="space-y-2 border-t border-gray-200 pt-3">
                    <div>
                        <p class="text-xs font-medium text-gray-600 uppercase">
                            Adresse
                        </p>
                        <p class="text-sm text-gray-900">
                            {{ participant.street }}
                        </p>
                        <p class="text-sm text-gray-900">
                            {{ participant.zip_code }} {{ participant.city }}
                        </p>
                    </div>

                    <div v-if="participant.email || participant.phone">
                        <p class="text-xs font-medium text-gray-600 uppercase">
                            Kontakt
                        </p>
                        <p
                            v-if="participant.email"
                            class="text-sm text-gray-900"
                        >
                            {{ participant.email }}
                        </p>
                        <p
                            v-if="participant.phone"
                            class="text-sm text-gray-900"
                        >
                            {{ participant.phone }}
                        </p>
                    </div>

                    <div
                        class="flex justify-between border-t border-gray-200 pt-3"
                    >
                        <p class="text-xs font-medium text-gray-600 uppercase">
                            Betrag
                        </p>
                        <p class="text-sm font-semibold text-gray-900">
                            {{ participant.price }} €
                        </p>
                    </div>
                </div>
            </div>

            <div class="mt-6 border-t border-gray-300 pt-4">
                <div class="mb-3 flex justify-between">
                    <p class="text-sm font-semibold text-gray-900">
                        Gesamtbetrag
                    </p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ totalAmount }} €
                    </p>
                </div>
                <div class="flex justify-between">
                    <p class="text-sm font-semibold text-gray-900">
                        Offener Betrag
                    </p>
                    <p class="text-sm font-semibold text-gray-900">
                        {{ outstandingAmount }} €
                    </p>
                </div>
            </div>
        </div>

        <div v-if="outstandingAmount > 0" class="mt-8">
            <p class="mb-6 text-lg font-semibold">
                Bitte überweise den offenen Betrag bis spätestens 31.07.2026 auf
                folgendes Konto:
            </p>
            <div class="rounded-lg border border-gray-300 bg-gray-50 p-6">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <div>
                        <div
                            class="text-xs font-medium text-gray-600 uppercase"
                        >
                            Bank
                        </div>
                        <div class="mt-1 text-lg font-semibold text-gray-900">
                            Frankfurter Sparkasse
                        </div>
                    </div>
                    <div>
                        <div
                            class="text-xs font-medium text-gray-600 uppercase"
                        >
                            Empfänger
                        </div>
                        <div class="mt-1 text-lg font-semibold text-gray-900">
                            Freiwillige Feuerwehr Frankfurt-Hausen e.V.
                        </div>
                    </div>
                    <div>
                        <div
                            class="text-xs font-medium text-gray-600 uppercase"
                        >
                            IBAN
                        </div>
                        <div class="mt-1 font-mono text-base text-gray-900">
                            DE51 5005 0201 0000 3191 29
                        </div>
                    </div>
                    <div>
                        <div
                            class="text-xs font-medium text-gray-600 uppercase"
                        >
                            BIC
                        </div>
                        <div class="mt-1 font-mono text-base text-gray-900">
                            HELADEF1822
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </MainLayout>
</template>

<style scoped></style>
