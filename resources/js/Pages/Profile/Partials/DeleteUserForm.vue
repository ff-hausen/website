<script setup lang="ts">
import DangerButton from "@/Components/Laravel/DangerButton.vue";
import InputError from "@/Components/Laravel/InputError.vue";
import InputLabel from "@/Components/Laravel/InputLabel.vue";
import Modal from "@/Components/Laravel/Modal.vue";
import SecondaryButton from "@/Components/Laravel/SecondaryButton.vue";
import TextInput from "@/Components/Laravel/TextInput.vue";
import { useForm } from "@inertiajs/vue3";
import { nextTick, ref } from "vue";

const confirmingUserDeletion = ref(false);
const passwordInput = ref<HTMLInputElement | null>(null);

const form = useForm({
    password: "",
});

const confirmUserDeletion = () => {
    confirmingUserDeletion.value = true;

    nextTick(() => passwordInput.value?.focus());
};

const deleteUser = () => {
    form.delete(route("profile.destroy"), {
        preserveScroll: true,
        onSuccess: () => closeModal(),
        onError: () => passwordInput.value?.focus(),
        onFinish: () => {
            form.reset();
        },
    });
};

const closeModal = () => {
    confirmingUserDeletion.value = false;

    form.reset();
};
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900">Delete Account</h2>

            <p class="mt-1 text-sm text-gray-600">
                Sobald Dein Konto gelöscht ist, werden alle Ressourcen und Daten
                dauerhaft gelöscht. Vor der Löschung Deines Kontos lade bitte
                alle Daten und Informationen herunter, die Du aufbewahren
                möchtest.
            </p>
        </header>

        <DangerButton @click="confirmUserDeletion">Konto löschen</DangerButton>

        <Modal :show="confirmingUserDeletion" @close="closeModal">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900">
                    Bist Du sicher, dass Du Dein Konto löschen möchtest?
                </h2>

                <p class="mt-1 text-sm text-gray-600">
                    Sobald Dein Konto gelöscht ist, werden alle Ressourcen und
                    Daten dauerhaft gelöscht. Bitte gib Dein Passwort ein, um zu
                    bestätigen, dass Du Dein Konto endgültig löschen möchtest.
                </p>

                <div class="mt-6">
                    <InputLabel
                        for="password"
                        value="Password"
                        class="sr-only"
                    />

                    <TextInput
                        id="password"
                        ref="passwordInput"
                        v-model="form.password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="Password"
                        @keyup.enter="deleteUser"
                    />

                    <InputError :message="form.errors.password" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <SecondaryButton @click="closeModal">
                        Abbrechen
                    </SecondaryButton>

                    <DangerButton
                        class="ms-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteUser"
                    >
                        Konto löschen
                    </DangerButton>
                </div>
            </div>
        </Modal>
    </section>
</template>
