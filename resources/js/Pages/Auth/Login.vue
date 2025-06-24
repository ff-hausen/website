<script setup lang="ts">
import Checkbox from "@/Components/Laravel/Checkbox.vue";
import InputError from "@/Components/Laravel/InputError.vue";
import InputLabel from "@/Components/Laravel/InputLabel.vue";
import PrimaryButton from "@/Components/Laravel/PrimaryButton.vue";
import TextInput from "@/Components/Laravel/TextInput.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Box from "@/Components/Box.vue";
import axios from "axios";
import {
    browserSupportsWebAuthn,
    startAuthentication,
} from "@simplewebauthn/browser";
import { inject, onMounted } from "vue";
import SecondaryButton from "@/Components/Laravel/SecondaryButton.vue";
import { route as ziggyRoute } from "ziggy-js";

const route = inject<typeof ziggyRoute>("route")!;

defineProps<{
    canResetPassword?: boolean;
    status?: string;
}>();

const form = useForm({
    email: "",
    password: "",
    passkey: "",
    remember: false,
});

const submit = () => {
    if (!form.email && !form.password && browserSupportsWebAuthn()) {
        authenticateWithPasskey();
        return;
    }

    form.post(route("login"), {
        onFinish: () => {
            form.reset("password");
        },
    });
};

async function authenticateWithPasskey(useBrowserAutofill = false) {
    const options = await axios(route("passkeys.authenticateOptions"), {
        params: {
            email: form.email,
        },
    });

    const answer = await startAuthentication(options.data, useBrowserAutofill);

    form.passkey = JSON.stringify(answer);
    form.post(route("passkeys.authenticate"));
}

onMounted(() => {
    authenticateWithPasskey(true);
});
</script>

<template>
    <MainLayout>
        <Head title="Login" />

        <Box class="sm:max-w-md sm:rounded-lg">
            <div v-if="status" class="mb-4 text-sm font-medium text-green-600">
                {{ status }}
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="email" value="E-Mail Adresse" />

                    <TextInput
                        id="email"
                        type="email"
                        class="mt-1 block w-full"
                        v-model="form.email"
                        autofocus
                        autocomplete="username webauthn"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                    <InputError class="mt-2" :message="form.errors.passkey" />
                </div>

                <div class="mt-4">
                    <InputLabel for="password" value="Passwort" />

                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        autocomplete="current-password"
                    />

                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4 block flex justify-between">
                    <label class="flex items-center">
                        <Checkbox
                            name="remember"
                            v-model:checked="form.remember"
                        />
                        <span class="ms-2 text-sm text-gray-600"
                            >Eingeloggt bleiben</span
                        >
                    </label>

                    <Link
                        v-if="canResetPassword"
                        :href="route('password.request')"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:outline-hidden"
                    >
                        Passwort vergessen?
                    </Link>
                </div>

                <div class="mt-4 flex items-center justify-end gap-4">
                    <div class="flex items-center">
                        <SecondaryButton
                            v-if="browserSupportsWebAuthn() && !form.password"
                            @click="authenticateWithPasskey()"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                stroke="currentColor"
                                class="mr-2 size-4"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"
                                />
                            </svg>
                            Login mit Passkey
                        </SecondaryButton>

                        <PrimaryButton
                            class="ms-4"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Login
                        </PrimaryButton>
                    </div>
                </div>
            </form>
        </Box>
    </MainLayout>
</template>
