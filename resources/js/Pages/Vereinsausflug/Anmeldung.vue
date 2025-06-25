<script setup lang="ts">
import TextInput from "@/Components/Forms/TextInput.vue";
import Options from "@/Components/Forms/Options.vue";
import { computed, reactive, ref, useTemplateRef } from "vue";
import Button from "@/Components/Forms/Button.vue";
import { Head, router } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";

export interface Participant {
    name: string | null;
    street: string | null;
    zip_code: string | null;
    city: string | null;
    email: string | null;
    phone: string | null;
    type: "ea" | "verein" | null;
    hasErrors: boolean;
    primary: boolean;
}

const formElement = useTemplateRef("form-element");

const participants = ref<Participant[]>([]);

let newParticipant = reactive<Participant>({
    name: null,
    street: null,
    zip_code: null,
    city: null,
    email: null,
    phone: null,
    type: null,
    hasErrors: false,
    primary: false,
});

function isStringDirty(value: string | null): boolean {
    return !!value && value.trim() !== "";
}

function isFormDirty(): boolean {
    return (
        isStringDirty(newParticipant.name) ||
        isStringDirty(newParticipant.street) ||
        isStringDirty(newParticipant.zip_code) ||
        isStringDirty(newParticipant.city) ||
        isStringDirty(newParticipant.email) ||
        isStringDirty(newParticipant.phone)
    );
}

function addParticipant(): boolean {
    const formIsValid = formElement.value?.reportValidity();

    if (!formIsValid) {
        return false;
    }

    if (participants.value.length === 0) {
        newParticipant.primary = true;
    }

    participants.value.push(newParticipant);
    resetParticipantForm();

    return true;
}

function removeParticipant(participant: Participant): void {
    participants.value = participants.value.filter((p) => p !== participant);
}

function resetParticipantForm() {
    newParticipant = reactive<Participant>({
        name: null,
        street: null,
        zip_code: null,
        city: null,
        email: null,
        phone: null,
        type: null,
        hasErrors: false,
        primary: false,
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

const submitted = ref(false);
const hasErrors = computed(() => {
    return participants.value.reduce((carry, p) => p.hasErrors || carry, false);
});

function submitRegistration(): void {
    // Add participant if someone typed new information but didn't add them themselves.
    if (isFormDirty() && !addParticipant()) {
        return;
    }

    router.post(
        route("ausflug.anmeldung"),
        {
            participants: participants.value,
        },
        {
            preserveScroll: true,
            onSuccess() {
                submitted.value = true;
            },
            onError(e) {
                // get key of e
                const keys = Object.keys(e);

                keys.forEach((key) => {
                    if (
                        key.substring(0, "participants.".length) !==
                        "participants."
                    ) {
                        return;
                    }

                    const split = key.split(/\./);
                    const index = parseInt(split[1]);

                    participants.value[index].hasErrors = true;
                });
            },
        },
    );
}
</script>

<template>
    <Head title="Vereinsausflug" />

    <MainLayout>
        <section>
            <article class="mx-auto my-6 max-w-2xl">
                <h1 class="mb-3 text-4xl font-bold underline">
                    Anmeldung zum Vereinsausflug 2025
                </h1>
                <p class="mb-4">am 27.09. – 28.09.2025</p>

                <div class="flex flex-col gap-4">
                    <p>
                        Liebe Kameradinnen und Kameraden, <br />
                        liebe Freunde der FF Hausen e.V.,
                    </p>

                    <p>
                        wir laden euch herzlich zu unserem diesjährigen
                        Vereinsausflug ein – dieses Mal geht es in die schöne
                        Stadt Wuppertal mit einem Abstecher nach Siegen!
                    </p>

                    <p>
                        Wir freuen uns auf ein spannendes, geselliges Wochenende
                        mit euch – mit interessanten Führungen, gutem Essen und
                        vielen schönen Momenten.
                    </p>

                    <h2 class="text-2xl font-bold">
                        🚌 Reiseplan im Überblick:
                    </h2>

                    <p class="font-bold">Samstag, 27.09.2025</p>

                    <ul class="list-inside list-disc sm:list-outside">
                        <li>
                            <span class="font-medium">08:00 Uhr</span> – Abfahrt
                            am Ellerfeld (FF Hausen)
                        </li>
                        <li>
                            <span class="font-medium">09:30 Uhr</span> –
                            Frühstückspause <br />
                            Serways Raststätte Fernthal West, BAB3,
                            Rasthausstraße 10–12, 53577 Neustadt (Wied)
                        </li>
                        <li>
                            <span class="font-medium">12:00 Uhr</span> – Ankunft
                            Wuppertal City
                        </li>
                        <li>
                            <span class="font-medium">14:00 Uhr</span> –
                            Stadtführung in Wuppertal
                        </li>
                        <li>
                            <span class="font-medium">17:30 Uhr</span> –
                            Check-in im B&B Hotel Wuppertal-City <br />
                            (Hofaue 4, 42103 Wuppertal)
                        </li>
                        <li>
                            <span class="font-medium">19:00 Uhr</span> –
                            Gemeinsames Abendessen <br />
                            Wagner am Mäuerchen, Mäuerchen 4, 42103 Wuppertal
                        </li>
                    </ul>

                    <p class="font-bold">Sonntag, 28.09.2025</p>

                    <ul class="list-inside list-disc sm:list-outside">
                        <li>
                            <span class="font-medium">10:00 Uhr</span> – Abfahrt
                            nach Siegen
                        </li>
                        <li>
                            <span class="font-medium">11:30 Uhr</span> – Besuch
                            des Oberen Schlosses & Siegerlandmuseums inkl.
                            Führung
                        </li>
                        <li>
                            <span class="font-medium">14:00 Uhr</span> –
                            Stadtführung durch Kay: von der Oberstadt in die
                            Unterstadt
                        </li>
                        <li>
                            <span class="font-medium">17:30 Uhr</span> –
                            Gemeinsames Abendessen <br />
                            (Geplant: „Zum Häutebacher“, zwei Alternativen sind
                            angefragt)
                        </li>
                        <li>
                            <span class="font-medium">20:00 Uhr</span> –
                            Heimfahrt nach Frankfurt
                        </li>
                    </ul>
                    <p>Wir freuen uns auf ein tolles Wochenende mit euch!</p>

                    <p>
                        Mit kameradschaftlichen Grüßen <br />
                        <span class="font-bold">Kay & Heiko</span> <br />
                        Freiwillige Feuerwehr Frankfurt Hausen e.V.
                    </p>
                </div>
            </article>

            <hr class="mb-12" />

            <div v-if="submitted">
                <p class="my-16 h-svh text-center text-xl font-medium">
                    Vielen Dank. Um die Anmeldung abzuschließen, klicke bitte
                    auf den Link in der E-Mail.
                </p>
            </div>
            <div
                v-else
                class="mx-auto flex max-w-3xl flex-col justify-between gap-8 sm:flex-row"
            >
                <form class="w-sm" ref="form-element">
                    <TextInput
                        id="name"
                        autocomplete="home name"
                        required
                        v-model="newParticipant.name"
                        >Vor- und Nachname
                    </TextInput>
                    <TextInput
                        id="street"
                        autocomplete="home street-address"
                        required
                        v-model="newParticipant.street"
                        >Straße/Hausnummer
                    </TextInput>
                    <TextInput
                        id="zip"
                        autocomplete="home postal-code"
                        required
                        v-model="newParticipant.zip_code"
                        >Postleitzahl
                    </TextInput>
                    <TextInput
                        id="city"
                        autocomplete="home address-level2"
                        required
                        v-model="newParticipant.city"
                        >Stadt
                    </TextInput>
                    <TextInput
                        id="email"
                        type="email"
                        autocomplete="home email"
                        :required="participants.length === 0"
                        v-model="newParticipant.email"
                        >E-Mail Adresse
                        <span v-if="participants.length > 0">(freiwillig)</span>
                    </TextInput>
                    <TextInput
                        id="phone"
                        type="tel"
                        autocomplete="home tel"
                        v-model="newParticipant.phone"
                        >Telefon (freiwillig)
                    </TextInput>
                    <Options
                        id="type"
                        :values="{
                            verein: 'Vereinsmitglied/Freund (150 € p. P.)',
                            ea: 'Einsatzabteilung (90 € p. P.)',
                        }"
                        v-model="newParticipant.type"
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

                <section
                    class="mb-6 flex w-sm flex-col items-center sm:items-end"
                >
                    <h2 class="text-xl font-medium">Teilnehmer:innen:</h2>

                    <div class="-mx-4 mb-8 flow-root sm:mx-0">
                        <table class="divide-y divide-gray-300">
                            <tbody>
                                <tr v-if="participants.length === 0">
                                    <td
                                        colspan="3"
                                        class="border-b border-gray-200 italic"
                                    >
                                        Noch keine Teilnehmer:innen
                                    </td>
                                </tr>
                                <tr
                                    class="border-b border-gray-200"
                                    v-for="participant in participants"
                                    :key="participant.name!"
                                >
                                    <td
                                        class="pr-3 pl-4 text-sm whitespace-nowrap sm:pl-6"
                                        :class="[
                                            participant.hasErrors
                                                ? 'font-bold text-red-400'
                                                : 'font-medium text-gray-900',
                                        ]"
                                    >
                                        {{ participant.name }}
                                    </td>
                                    <td
                                        class="pr-4 pl-3 text-right text-sm text-gray-500 sm:pr-0"
                                    >
                                        {{ price(participant) }} €
                                    </td>
                                    <td
                                        class="pr-4 pl-3 text-right text-sm text-gray-500 sm:pr-0"
                                    >
                                        <Button
                                            size="small"
                                            color="alternative"
                                            @click="
                                                removeParticipant(participant)
                                            "
                                        >
                                            <svg
                                                xmlns="http://www.w3.org/2000/svg"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                class="size-3.5"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"
                                                />
                                            </svg>
                                        </Button>
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
                                        class="pt-4 pr-4 pl-3 text-sm font-semibold text-gray-900 sm:pr-0"
                                    >
                                        {{ totalAmount }} €
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div
                        v-if="hasErrors"
                        class="mb-4 text-sm font-medium text-red-400"
                    >
                        Bei den rot markierten Teilnehmer:innen gab es Probleme
                        mit den Daten. Bitte entferne sie und füge sie neu
                        hinzu.
                    </div>

                    <Button
                        :disabled="
                            hasErrors ||
                            (participants.length === 0 && !isFormDirty())
                        "
                        @click="submitRegistration"
                        >Anmeldung absenden
                    </Button>
                </section>
            </div>
        </section>
    </MainLayout>
</template>

<style scoped></style>
