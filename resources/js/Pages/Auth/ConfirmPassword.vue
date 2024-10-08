<script setup lang="ts">
import InputError from "@/Components/Laravel/InputError.vue";
import InputLabel from "@/Components/Laravel/InputLabel.vue";
import PrimaryButton from "@/Components/Laravel/PrimaryButton.vue";
import TextInput from "@/Components/Laravel/TextInput.vue";
import { Head, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Box from "@/Components/Box.vue";
import { route as ziggyRoute } from "ziggy-js";
import { inject } from "vue";

const route = inject<typeof ziggyRoute>("route")!;

const form = useForm({
    password: "",
});

const submit = () => {
    form.post(route("password.confirm"), {
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <MainLayout>
        <Head title="Passwort bestätigen" />

        <Box class="sm:max-w-md sm:rounded-lg">
            <div class="mb-4 text-sm text-gray-600">
                Dies ist ein sicherer Bereich der Anwendung. Bitte bestätige
                Dein Passwort, bevor Du fortfährst.
            </div>

            <form @submit.prevent="submit">
                <div>
                    <InputLabel for="password" value="Password" />
                    <TextInput
                        id="password"
                        type="password"
                        class="mt-1 block w-full"
                        v-model="form.password"
                        required
                        autocomplete="current-password"
                        autofocus
                    />
                    <InputError class="mt-2" :message="form.errors.password" />
                </div>

                <div class="mt-4 flex justify-end">
                    <PrimaryButton
                        class="ms-4"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Bestätigen
                    </PrimaryButton>
                </div>
            </form>
        </Box>
    </MainLayout>
</template>
