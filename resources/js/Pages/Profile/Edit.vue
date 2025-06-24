<script setup lang="ts">
import DeleteUserForm from "./Partials/DeleteUserForm.vue";
import UpdatePasswordForm from "./Partials/UpdatePasswordForm.vue";
import UpdateProfileInformationForm from "./Partials/UpdateProfileInformationForm.vue";
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import ManagePasskeysForm, {
    Passkey,
} from "@/Pages/Profile/Partials/ManagePasskeysForm.vue";

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
    passkeys: Array<Passkey>;
}>();
</script>

<template>
    <Head title="Profil" />

    <MainLayout>
        <template #header>
            <h2 class="text-xl leading-tight font-semibold text-gray-800">
                Profil
            </h2>
        </template>

        <div class="py-12">
            <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                <div class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8">
                    <UpdateProfileInformationForm
                        :must-verify-email="mustVerifyEmail"
                        :status="status"
                        class="max-w-xl"
                    />
                </div>

                <!-- mx-auto my-6 w-full overflow-hidden bg-white px-6 py-4 shadow-md sm:max-w-md sm:rounded-lg -->
                <div class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8">
                    <UpdatePasswordForm class="max-w-xl" />
                </div>

                <div class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8">
                    <ManagePasskeysForm :passkeys="passkeys" class="max-w-xl" />
                </div>

                <div class="bg-white p-4 shadow-sm sm:rounded-lg sm:p-8">
                    <DeleteUserForm class="max-w-xl" />
                </div>
            </div>
        </div>
    </MainLayout>
</template>
