<script setup lang="ts">
import { useForm } from "laravel-precognition-vue-inertia";
import InputError from "@/Components/Laravel/InputError.vue";
import InputLabel from "@/Components/Laravel/InputLabel.vue";
import TextInput from "@/Components/Laravel/TextInput.vue";
import { inject } from "vue";
import { route as ziggyRoute } from "ziggy-js";
import { ChevronDownIcon } from "@heroicons/vue/16/solid";

const route = inject<typeof ziggyRoute>("route")!;

const { topics, selectedTopic = "" } = defineProps<{
    topics: string[];
    selectedTopic?: string;
}>();

const form = useForm("post", route("contact-form.send"), {
    name: "",
    email: "",
    topic: selectedTopic,
    message: "",
});
</script>

<template>
    <form
        @submit.prevent="
            form.submit({
                preserveScroll: true,
                onSuccess() {
                    form.reset();
                },
            })
        "
        class="lg:px-8"
    >
        <div class="mx-auto max-w-xl p-4 md:p-8 lg:max-w-lg">
            <div class="grid grid-cols-1 gap-x-8 gap-y-6 sm:grid-cols-2">
                <div class="sm:col-span-2">
                    <InputLabel for="name" value="Name" required />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        autocomplete="name"
                        @change="form.validate('name')"
                        aria-describedby="required-description"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.name"></InputError>
                </div>
                <div class="sm:col-span-2">
                    <InputLabel for="email" required>E-Mail Adresse</InputLabel>
                    <TextInput
                        type="email"
                        id="email"
                        name="email"
                        autocomplete="email"
                        v-model="form.email"
                        @change="form.validate('email')"
                        aria-describedby="required-description"
                        class="mt-1 block w-full"
                    />
                    <InputError :message="form.errors.email"></InputError>
                </div>
                <div class="sm:col-span-2">
                    <InputLabel for="topic" required>Thema</InputLabel>
                    <div class="mt-2 grid grid-cols-1">
                        <select
                            name="topic"
                            id="topic"
                            v-model="form.topic"
                            @change="form.validate('topic')"
                            aria-describedby="required-description"
                            class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pr-8 pl-3 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 focus:outline-2 focus:-outline-offset-2 focus:outline-red-600 sm:text-sm/6"
                        >
                            <option></option>
                            <option v-for="topic in topics" :key="topic">
                                {{ topic }}
                            </option>
                        </select>
                        <ChevronDownIcon
                            class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4"
                            aria-hidden="true"
                        />
                    </div>
                    <InputError :message="form.errors.topic" />
                </div>
                <div class="sm:col-span-2">
                    <InputLabel for="message" required>Nachricht</InputLabel>
                    <textarea
                        name="message"
                        id="message"
                        rows="4"
                        v-model="form.message"
                        @change="form.validate('message')"
                        aria-describedby="required-description"
                        class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline-2 focus:-outline-offset-2 focus:outline-red-600 sm:text-sm/6"
                    />

                    <InputError :message="form.errors.message" />
                </div>
            </div>
            <div class="mt-8 flex justify-between">
                <div class="text-sm">
                    <div aria-hidden="true" id="required-description">
                        <span class="text-red-700">*</span> Pflichtfeld
                    </div>

                    <Transition
                        enter-active-class="transition ease-in-out"
                        enter-from-class="opacity-0"
                        leave-active-class="transition ease-in-out"
                        leave-to-class="opacity-0"
                    >
                        <p
                            v-if="form.recentlySuccessful"
                            class="text-sm text-green-600"
                        >
                            Nachricht wurde gesendet.
                        </p>
                    </Transition>
                </div>
                <button
                    type="submit"
                    class="rounded-md bg-red-600 px-3.5 py-2.5 text-center text-sm font-semibold text-white shadow-xs hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600"
                >
                    Nachricht senden
                </button>
            </div>
        </div>
    </form>
</template>

<style scoped></style>
