<script setup lang="ts">
import InputError from "@/Components/Laravel/InputError.vue";
import InputLabel from "@/Components/Laravel/InputLabel.vue";
import PrimaryButton from "@/Components/Laravel/PrimaryButton.vue";
import TextInput from "@/Components/Laravel/TextInput.vue";
import { Link, useForm, usePage } from "@inertiajs/vue3";

defineProps<{
    mustVerifyEmail?: Boolean;
    status?: String;
}>();

const user = usePage().props.auth.user;

const form = useForm({
    first_name: user.first_name,
    last_name: user.last_name,
    email: user.email,
});
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900">
                Profil Informationen
            </h2>

            <p class="mt-1 text-sm text-gray-600">
                Aktualisiere Deine Profilinformationen und die E-Mail-Adresse
                Deines Kontos.
            </p>
        </header>

        <form
            @submit.prevent="form.patch(route('profile.update'))"
            class="mt-6 space-y-6"
        >
            <div>
                <InputLabel for="first_name" value="Vorname" />

                <TextInput
                    id="first_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.first_name"
                    required
                    autofocus
                    autocomplete="given-name"
                />

                <InputError class="mt-2" :message="form.errors.first_name" />
            </div>
            <div>
                <InputLabel for="last_name" value="Nachname" />

                <TextInput
                    id="last_name"
                    type="text"
                    class="mt-1 block w-full"
                    v-model="form.last_name"
                    required
                    autocomplete="family-name"
                />

                <InputError class="mt-2" :message="form.errors.last_name" />
            </div>

            <div>
                <InputLabel for="email" value="E-Mail Adresse" />

                <TextInput
                    id="email"
                    type="email"
                    class="mt-1 block w-full"
                    v-model="form.email"
                    required
                    autocomplete="username"
                />

                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div v-if="mustVerifyEmail && user.email_verified_at === null">
                <p class="mt-2 text-sm text-gray-800">
                    Deine E-Mail-Adresse ist nicht verifiziert.
                    <Link
                        :href="route('verification.send')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        Klicke hier, um die Bestätigungs-E-Mail erneut zu
                        senden.
                    </Link>
                </p>

                <div
                    v-show="status === 'verification-link-sent'"
                    class="mt-2 text-sm font-medium text-green-600"
                >
                    Es wurde ein neuer Verifizierungslink an Deine
                    E-Mail-Adresse gesendet.
                </div>
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing"
                    >Speichern
                </PrimaryButton>

                <Transition
                    enter-active-class="transition ease-in-out"
                    enter-from-class="opacity-0"
                    leave-active-class="transition ease-in-out"
                    leave-to-class="opacity-0"
                >
                    <p
                        v-if="form.recentlySuccessful"
                        class="text-sm text-gray-600"
                    >
                        Gespeichert.
                    </p>
                </Transition>
            </div>
        </form>
    </section>
</template>
