<script setup lang="ts">
import InputError from "@/Components/Laravel/InputError.vue";
import InputLabel from "@/Components/Laravel/InputLabel.vue";
import PrimaryButton from "@/Components/Laravel/PrimaryButton.vue";
import TextInput from "@/Components/Laravel/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Box from "@/Components/Box.vue";

defineProps<{
    status?: string;
}>();

const form = useForm({
    email: "",
});

const submit = () => {
    form.post(route("password.email"));
};
</script>

<template>
    <MainLayout>
        <Head title="Passwort vergessen" />

        <Box class="sm:max-w-md sm:rounded-lg">
            <div class="mb-4 text-sm text-gray-600">
                Du hast Dein Passwort vergessen? Kein Problem! Gib einfach Deine
                E-Mail Adresse ein und wir senden Dir einen Link zum
                Zurücksetzen des Passworts zu mit dem Du ein neues Passwort
                wählen kannst.
            </div>

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
                        required
                        autofocus
                        autocomplete="username"
                    />

                    <InputError class="mt-2" :message="form.errors.email" />
                </div>

                <div class="mt-4 flex items-center justify-end">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        E-Mail zum Zurücksetzen senden
                    </PrimaryButton>
                </div>
            </form>
        </Box>
    </MainLayout>
</template>
