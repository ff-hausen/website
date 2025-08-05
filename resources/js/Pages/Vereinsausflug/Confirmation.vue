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
</script>

<template>
    <Head title="Vereinsausflug Anmeldung Bestätigung" />

    <MainLayout>
        <h1 class="mt-4 mb-8 text-2xl font-bold">Anmeldung erfolgreich</h1>

        <p class="mb-4">
            Deine Anmeldung ist bestätigt. Die folgende Zusammenfassung erhältst
            du auch per E-Mail.
        </p>

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
                            {{ participant.zip_code }} {{ participant.city }}
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
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th
                        scope="row"
                        colspan="4"
                        class="hidden px-3 py-4 text-right text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0"
                    >
                        Gesamtbetrag
                    </th>
                    <th
                        scope="row"
                        class="px-3 py-4 text-left text-sm font-semibold text-gray-900 sm:hidden"
                    >
                        Gesamtbetrag
                    </th>
                    <td
                        class="px-3 py-4 text-right text-sm font-semibold text-gray-900"
                    >
                        {{ totalAmount }} €
                    </td>
                </tr>
            </tfoot>
        </table>

        <p class="mb-4">
            Bitte überweise den Gesamtbetrag bis spätestens 01.08.2025 auf
            folgendes Konto:
        </p>
        <div class="font-bold">Frankfurter Sparkasse</div>
        <div>
            <span class="font-medium">IBAN:</span> DE51 5005 0201 0000 3191 29
        </div>
        <div><span class="font-medium">BIC:</span> HELADEF1822</div>
    </MainLayout>
</template>

<style scoped></style>
