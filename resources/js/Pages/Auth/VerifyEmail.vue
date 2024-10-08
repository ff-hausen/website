<script setup lang="ts">
import { computed, inject } from "vue";
import PrimaryButton from "@/Components/Laravel/PrimaryButton.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Box from "@/Components/Box.vue";
import { route as ziggyRoute } from "ziggy-js";

const route = inject<typeof ziggyRoute>("route")!;

const props = defineProps<{
    status?: string;
}>();

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};

const verificationLinkSent = computed(
    () => props.status === "verification-link-sent",
);
</script>

<template>
    <MainLayout>
        <Head title="E-Mail bestätigen" />

        <Box class="sm:max-w-md sm:rounded-lg">
            <div class="mb-4 text-sm text-gray-600">
                Bevor wir anfangen, kannst Du bitte Deine E-Mail-Adresse
                bestätigen, indem Du auf den Link klickst, den wir Dir gerade
                geschickt haben? Wenn Du die E-Mail nicht erhalten hast,
                schicken wir Dir gerne eine neue.
            </div>

            <div
                class="mb-4 text-sm font-medium text-green-600"
                v-if="verificationLinkSent"
            >
                Es wurde ein neuer Verifizierungslink an die E-Mail-Adresse
                gesendet, die Du bei der Registrierung angegeben hast.
            </div>

            <form @submit.prevent="submit">
                <div class="mt-4 flex items-center justify-between">
                    <PrimaryButton
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        E-Mail erneut senden
                    </PrimaryButton>

                    <Link
                        :href="route('logout')"
                        method="post"
                        as="button"
                        class="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >Abmelden
                    </Link>
                </div>
            </form>
        </Box>
    </MainLayout>
</template>
