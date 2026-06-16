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
    price?: number;
    paid_at?: Date;
    hasErrors: boolean;
    errors: string[];
    primary: boolean;
}

const props = defineProps<{
    isRegistrationOpen: boolean;
}>();

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
    errors: [],
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
        errors: [],
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
const participantErrors = computed(() => {
    const errors: string[] = [];
    participants.value.forEach((participant) => {
        participant.errors.forEach((error) => {
            errors.push(error);
        });
    });
    return errors;
});

function applyLastAddress(): void {
    if (participants.value.length === 0) {
        return;
    }

    const latestParticipant = participants.value[participants.value.length - 1];

    newParticipant.street = latestParticipant.street;
    newParticipant.zip_code = latestParticipant.zip_code;
    newParticipant.city = latestParticipant.city;
}

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
                    const value = e[key];

                    participants.value[index].hasErrors = true;
                    participants.value[index].errors.push(value);
                });
            },
        },
    );
}
</script>

<template>
    <Head title="Vereinsausflug 2026" />

    <MainLayout>
        <section v-if="isRegistrationOpen">
            <article class="mx-auto my-6 max-w-2xl">
                <h1 class="mb-3 text-4xl font-bold underline">
                    Anmeldung zum Vereinsausflug 2026 nach Karlsruhe und
                    Heidelberg
                </h1>
                <p class="mb-4">am 26.9. &ndash; 27.9.2026</p>

                <div class="flex flex-col gap-4">
                    <p>
                        Liebe Kameradinnen und Kameraden, <br />
                        liebe Freunde der FF Hausen e.V.,
                    </p>

                    <p>
                        hiermit möchten wir euch herzlich zu unserem
                        <span class="font-medium"
                            >Ausflug 2026 der Freiwilligen Feuerwehr
                            Frankfurt-Hausen</span
                        >
                        einladen. In diesem Jahr führt uns die Reise am
                        <span class="font-medium">26. und 27.09.2026</span>
                        nach Karlsruhe, Schwetzingen und Heidelberg.
                    </p>

                    <p>
                        Wir freuen uns auf ein schönes, geselliges Wochenende
                        mit euch – mit interessanten Besichtigungen, gemeinsamen
                        Führungen, gutem Essen und hoffentlich bestem
                        Reisewetter.
                    </p>

                    <h2 class="text-2xl font-bold">
                        🚌 Reiseplan im Überblick:
                    </h2>

                    <p class="font-bold">Samstag, 26.09.2026</p>

                    <ul class="list-inside list-disc sm:list-outside">
                        <li>
                            <span class="font-medium">08:00 Uhr</span> – Abfahrt
                            am Feuerwehrhaus „Am Ellerfeld 18“ in Richtung
                            Karlsruhe
                        </li>
                        <li>Fahrt mit einem Reisebus der Firma Schlosser</li>
                        <li>Frühstückspause auf einem Autobahnrastplatz</li>
                        <li>
                            <span class="font-medium">11:00 Uhr</span> – Besuch
                            der Freiwilligen Feuerwehr Karlsruhe-Durlach mit
                            Führung durch die Feuerwache
                            <br />
                            Die Feuerwehr Durlach ist eine der ältesten, wenn
                            nicht die älteste Freiwillige Feuerwehr in
                            Deutschland.
                        </li>
                        <li>
                            <span class="font-medium">ab ca. 12:30 Uhr</span> –
                            Zeit zur freien Verfügung in Karlsruhe-Durlach
                        </li>
                        <li>
                            <span class="font-medium">14:00 Uhr</span> –
                            Gemeinsame 1,5-stündige Führung durch Durlach
                        </li>
                        <li>
                            Anschließend Weiterfahrt mit dem Bus zum Novotel
                            Karlsruhe
                        </li>
                        <li>
                            <span class="font-medium">17:30 Uhr</span> – Treffen
                            an der Rezeption und gemeinsamer Spaziergang zum
                            Abendessen in Karlsruhe
                        </li>
                    </ul>

                    <p class="font-bold">Sonntag, 27.09.2026</p>

                    <ul class="list-inside list-disc sm:list-outside">
                        <li>
                            Nach dem Frühstück im Hotel treffen wir uns an der
                            Rezeption
                        </li>
                        <li>
                            <span class="font-medium">10:00 Uhr</span> – Abfahrt
                            mit dem Bus nach Schwetzingen
                        </li>
                        <li>
                            Gemeinsame Besichtigung von Schloss Schwetzingen
                            <br />
                            Wer möchte, kann im Anschluss auch den sehr
                            sehenswerten Schlossgarten besuchen.
                        </li>
                        <li>Zeit zur freien Verfügung in Schwetzingen</li>
                        <li>
                            <span class="font-medium">14:00 Uhr</span> –
                            Weiterfahrt nach Heidelberg
                        </li>
                        <li>
                            Zeit zur freien Verfügung in Heidelberg
                            <br />
                            Zum Zeitpunkt unseres Besuchs findet der
                            „Heidelberger Herbst“ statt, inklusive
                            verkaufsoffenem Sonntag. Das größte und älteste
                            Stadtfest der Region bietet Live-Musik,
                            Kunsthandwerkermärkte und einen riesigen Flohmarkt.
                        </li>
                        <li>
                            <span class="font-medium">16:30 Uhr</span> –
                            Weiterfahrt mit dem Bus nach Darmstadt
                        </li>
                        <li>
                            Gemeinsames Abendessen im Restaurant Bölle
                            <br />
                            <span class="font-medium">Hinweis:</span> Das
                            Abendessen ist nicht im Preis enthalten und muss
                            selbst gezahlt werden.
                        </li>
                        <li>
                            <span class="font-medium">ca. 20:30 Uhr</span> –
                            Rückfahrt nach Frankfurt
                        </li>
                        <li>
                            <span class="font-medium">ca. 21:00 Uhr</span> –
                            Ankunft in Frankfurt
                        </li>
                    </ul>

                    <h2 class="text-2xl font-bold">💶 Kosten</h2>

                    <p>
                        Der Preis beträgt
                        <span class="font-medium">150 € pro Person</span>.
                        Mitglieder der Einsatzabteilung zahlen
                        <span class="font-medium">90 €</span>.
                    </p>

                    <p>
                        Im Preis enthalten sind die Busfahrt, die Eintritte und
                        Führungen am Samstag und Sonntag, die Übernachtung mit
                        Frühstück sowie das Abendessen am Samstagabend ohne
                        Getränke.
                    </p>

                    <h2 class="text-2xl font-bold">📝 Anmeldung</h2>

                    <p>
                        Die Anmeldung muss bis zum
                        <span class="font-medium">15.07.2026</span>
                        erfolgen.
                    </p>

                    <h2 class="text-2xl font-bold">🏦 Bezahlung</h2>

                    <p>
                        Die Bezahlung muss bis zum
                        <span class="font-medium">31.07.2026</span>
                        auf folgendes Konto erfolgen:
                    </p>

                    <p>
                        <span class="font-medium">IBAN:</span>
                        DE51 5005 0201 0000 3191 29
                    </p>

                    <p>
                        Wir freuen uns auf eine schöne Fahrt bei hoffentlich
                        gutem Reisewetter!
                    </p>

                    <p>
                        Mit kameradschaftlichen Grüßen <br />
                        <span class="font-bold">
                            Michael Winter, Friedrich Lamp, Marco Nardi, Heiko
                            Horvath und Jürgen Wandtke
                        </span>
                        <br />
                        Freiwillige Feuerwehr Frankfurt-Hausen
                    </p>
                </div>
            </article>

            <hr class="mb-12" />

            <div v-if="submitted">
                <p class="my-16 h-svh text-center text-xl font-medium">
                    Vielen Dank! Bitte klicke auf den Link in der E-Mail, um
                    deine Anmeldung abzuschließen. Schau zur Sicherheit auch in
                    deinem Spam-Ordner nach.
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
                    >
                        <span class="flex justify-between">
                            Straße/Hausnummer
                            <button
                                type="button"
                                v-if="participants.length > 0"
                                @click="applyLastAddress"
                                class="cursor-pointer text-sm font-medium text-blue-700 underline"
                            >
                                Gleiche Adresse?
                            </button>
                        </span>
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
                        Bei den rot markierten Teilnehmer:innen gab es folgende
                        Probleme bei der Eingabe:

                        <ul class="list-inside list-disc">
                            <li v-for="error in participantErrors">
                                {{ error }}
                            </li>
                        </ul>

                        Bitte entferne sie und füge sie neu hinzu.
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
        <section v-else>
            <h1 class="mb-3 text-4xl font-bold underline">
                Anmeldung zum Vereinsausflug 2026
            </h1>
            <p class="mb-4 font-bold">
                🚫 Die Anmeldefrist ist leider bereits abgelaufen!
            </p>
        </section>
    </MainLayout>
</template>

<style scoped></style>
