<script setup lang="ts">
import TextInput from "@/Components/Laravel/TextInput.vue";
import InputLabel from "@/Components/Laravel/InputLabel.vue";
import { Link, useForm } from "@inertiajs/vue3";
import InputError from "@/Components/Laravel/InputError.vue";
import PrimaryButton from "@/Components/Laravel/PrimaryButton.vue";
import dayjs from "dayjs";
import relativeTime from "dayjs/plugin/relativeTime";
import "dayjs/locale/de";

dayjs.extend(relativeTime);
dayjs.locale("de");

defineProps<{
    passkeys: Array;
}>();

const form = useForm({
    name: "",
});

// const passkeys = [
//     { id: 3, name: "adipisicing", created_at: moment().subtract(2, "hours") },
//     { id: 2, name: "officia", created_at: moment().subtract(5, "days") },
//     { id: 1, name: "ut", created_at: moment().subtract(1, "months") },
// ];
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Passkeys verwalten
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Passkeys ermöglichen eine sicherere, nahtlose Authentifizierung
                auf unterstützten Geräten.
            </p>
        </header>

        <form @submit.prevent="form.post('/')" class="mt-6 space-y-6">
            <div>
                <InputLabel value="Passkey Name" />
                <TextInput
                    v-model="form.name"
                    id="passkey-name"
                    type="text"
                    class="mt-1 block w-full"
                />
                <InputError :message="form.errors.name" class="mt-2" />
            </div>
            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing"
                    >Passkey erstellen
                </PrimaryButton>
            </div>
        </form>

        <div class="mt-6 space-y-3">
            <h3 class="font-bold">Deine Passkeys</h3>

            <ul class="ml-3 flex flex-col gap-2">
                <li v-for="passkey in passkeys" :key="passkey.id">
                    <div class="flex items-center">
                        <div class="flex-grow flex-col">
                            <div class="font-bold">{{ passkey.name }}</div>
                            <div class="text-sm text-gray-500">
                                Erstellt
                                {{ dayjs(passkey.created_at).fromNow() }}
                            </div>
                        </div>
                        <div>
                            <Link
                                :href="route('passkey.destroy', passkey)"
                                method="delete"
                                as="button"
                                class="cursor-pointer rounded-lg border border-slate-400 px-4 py-1 transition-colors hover:bg-red-500 hover:text-white"
                            >
                                Löschen
                            </Link>
                        </div>
                    </div>
                </li>
                <li v-if="passkeys.length === 0">
                    <div class="text-sm text-gray-500">
                        Keine Passkeys vorhanden.
                    </div>
                </li>
            </ul>
        </div>
    </section>
</template>
