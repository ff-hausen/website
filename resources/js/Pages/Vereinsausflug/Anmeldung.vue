<script setup lang="ts">
import TextInput from "@/Components/Forms/TextInput.vue";
import Options from "@/Components/Forms/Options.vue";
import { computed, reactive, ref, useTemplateRef } from "vue";
import Button from "@/Components/Forms/Button.vue";
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

interface Participant {
    name: string | null;
    street: string | null;
    zipCode: string | null;
    city: string | null;
    email: string | null;
    phone: string | null;
    type: "ea" | "verein" | null;
}

const formElement = useTemplateRef("form-element");

const participants = ref<Participant[]>([]);

let form = reactive<Participant>({
    name: null,
    street: null,
    zipCode: null,
    city: null,
    email: null,
    phone: null,
    type: null,
});

function addParticipant(): void {
    const formIsValid = formElement.value?.reportValidity();

    if (formIsValid) {
        participants.value.push(form);
        resetParticipantForm();
    }
}

function resetParticipantForm() {
    form = reactive<Participant>({
        name: null,
        street: null,
        zipCode: null,
        city: null,
        email: null,
        phone: null,
        type: null,
    });
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
    return participants.value.reduce(
        (carry, participant) => price(participant) + carry,
        0,
    );
});

function submitRegistration(): void {
    //
}
</script>

<template>
    <Head title="Vereinsausflug" />

    <MainLayout>
        <section class="mx-auto my-6 max-w-2xl">
            <h1 class="mb-3 text-4xl font-bold underline">
                Anmeldung zum Vereinsausflug 2025
            </h1>
            <p>am 27.09. – 28.09.2025</p>
        </section>

        <div class="mx-auto flex max-w-3xl flex-row justify-between gap-8">
            <form class="w-sm" ref="form-element">
                <TextInput
                    id="name"
                    autocomplete="home name"
                    required
                    v-model="form.name"
                    >Vor- und Nachname
                </TextInput>
                <TextInput
                    id="street"
                    autocomplete="home street-address"
                    required
                    v-model="form.street"
                    >Straße/Hausnummer
                </TextInput>
                <TextInput
                    id="zip"
                    autocomplete="home postal-code"
                    required
                    v-model="form.zipCode"
                    >Postleitzahl
                </TextInput>
                <TextInput
                    id="city"
                    autocomplete="home address-level2"
                    required
                    v-model="form.city"
                    >Stadt
                </TextInput>
                <TextInput
                    id="email"
                    type="email"
                    autocomplete="home email"
                    required
                    v-model="form.email"
                    >E-Mail Adresse
                </TextInput>
                <TextInput
                    id="phone"
                    type="tel"
                    autocomplete="home tel"
                    v-model="form.phone"
                    >Telefon (freiwillig)
                </TextInput>
                <Options
                    id="type"
                    :values="{
                        verein: 'Vereinsmitglied/Freund (150 € p. P.)',
                        ea: 'Einsatzabteilung (90 € p. P.)',
                    }"
                    v-model="form.type"
                    required
                ></Options>
                <Button type="submit" @click="addParticipant">
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        class="me-2 h-3.5 w-3.5"
                        aria-hidden="true"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke-width="1.5"
                        stroke="currentColor"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M12 4.5v15m7.5-7.5h-15"
                        />
                    </svg>
                    Teilnehmer:in hinzufügen
                </Button>
            </form>

            <section class="mb-6 flex w-sm flex-col items-end">
                <h2 class="text-xl font-medium">Teilnehmer:innen:</h2>

                <div class="-mx-4 my-8 flow-root sm:mx-0">
                    <table class="divide-y divide-gray-300">
                        <tbody>
                            <tr v-if="participants.length === 0">
                                <td
                                    colspan="2"
                                    class="border-b border-gray-200 italic"
                                >
                                    Noch keine Teilnehmer:innen
                                </td>
                            </tr>
                            <tr
                                v-for="participant in participants"
                                class="border-b border-gray-200"
                            >
                                <td
                                    class="py-4 pr-3 pl-4 text-sm font-medium whitespace-nowrap text-gray-900 sm:pl-6"
                                >
                                    {{ participant.name }}
                                </td>
                                <td
                                    class="py-5 pr-4 pl-3 text-right text-sm text-gray-500 sm:pr-0"
                                >
                                    {{ price(participant) }} €
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th
                                    scope="row"
                                    class="hidden pt-4 pr-3 pl-4 text-right text-sm font-semibold text-gray-900 sm:table-cell sm:pl-0"
                                >
                                    Gesamtbetrag:
                                </th>
                                <th
                                    scope="row"
                                    class="pt-4 pr-3 pl-4 text-left text-sm font-semibold text-gray-900 sm:hidden"
                                >
                                    Gesamtbetrag:
                                </th>
                                <td
                                    class="pt-4 pr-4 pl-3 text-right text-sm font-semibold text-gray-900 sm:pr-0"
                                >
                                    {{ totalAmount }} €
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <Button
                    :disabled="participants.length === 0"
                    @click="submitRegistration"
                    >Anmeldung absenden
                </Button>
            </section>
        </div>
    </MainLayout>
</template>

<style scoped></style>
