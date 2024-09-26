<script setup lang="ts">
import MainLayout from "@/Layouts/MainLayout.vue";
import { Head, useForm } from "@inertiajs/vue3";
import Box from "@/Components/Box.vue";
import PrimaryButton from "@/Components/Laravel/PrimaryButton.vue";
import SecondaryButton from "@/Components/Laravel/SecondaryButton.vue";
import { OAuthClient } from "@/types";

const props = defineProps<{
    client: OAuthClient;
    scopes: Array<{ id: string; description: string }>;
    authToken: string;
    state: string;
}>();

const form = useForm({
    state: props.state,
    client_id: props.client.id,
    auth_token: props.authToken,
});
</script>

<template>
    <MainLayout title="Autorisierung">
        <Head title="Autorisierung" />

        <Box class="sm:max-w-md sm:rounded-lg">
            <div class="flex flex-col gap-4">
                <!-- Introduction -->
                <p>
                    <strong>{{ client.name }}</strong> möchte auf Ihr Konto
                    zugreifen.
                </p>

                <!-- Scope List -->
                <div v-if="scopes.length > 0">
                    <p>
                        <strong>Diese Anwendung wird Folgendes können:</strong>
                    </p>

                    <ul class="list-inside list-disc">
                        <li v-for="scope in scopes" :key="scope.id">
                            {{ scope.description }}
                        </li>
                    </ul>
                </div>

                <div class="flex justify-between">
                    <!-- Authorize Button -->
                    <form
                        @submit.prevent="
                            form.post(route('passport.authorizations.approve'))
                        "
                    >
                        <PrimaryButton>Autorisieren</PrimaryButton>
                    </form>

                    <!-- Cancel Button -->
                    <form
                        @submit.prevent="
                            form.delete(route('passport.authorizations.deny'))
                        "
                    >
                        <SecondaryButton type="submit"
                            >Abbrechen
                        </SecondaryButton>
                    </form>
                </div>
            </div>
        </Box>
    </MainLayout>
</template>

<style scoped></style>
