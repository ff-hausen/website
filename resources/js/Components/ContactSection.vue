<script setup lang="ts">
import ContactForm from "@/Components/ContactForm.vue";

defineProps<{
    contactFormTopics: string[];
    title: string;
}>();
</script>

<template>
    <section class="grid grid-cols-1 gap-2 lg:mt-8 lg:grid-cols-2">
        <div class="flex flex-col items-start p-8">
            <h2 class="text-3xl font-bold tracking-tight text-gray-900">
                {{ title }}
            </h2>
            <p class="mt-6 max-w-[64ch] text-lg leading-8 text-gray-600">
                <slot />
            </p>
            <dl class="mt-10 space-y-4 text-base leading-7 text-gray-600">
                <div class="flex gap-x-4">
                    <dt class="flex-none">
                        <span class="sr-only">Adresse</span>
                        <svg
                            class="h-7 w-6 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z"
                            />
                        </svg>
                    </dt>
                    <dd>Am Ellerfeld 18<br />60488 Frankfurt am Main</dd>
                </div>
                <div class="flex gap-x-4">
                    <dt class="flex-none">
                        <span class="sr-only">Telefon</span>
                        <svg
                            class="h-7 w-6 text-gray-400"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke-width="1.5"
                            stroke="currentColor"
                            aria-hidden="true"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"
                            />
                        </svg>
                    </dt>
                    <dd>
                        <a class="hover:text-gray-900" href="tel:06976752829"
                            >069 76752829</a
                        >
                    </dd>
                </div>
            </dl>
        </div>

        <ContactForm :topics="contactFormTopics" />
    </section>
</template>

<style scoped></style>
