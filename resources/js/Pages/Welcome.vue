<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Hero from "@/Components/Hero.vue";
import IntroCard from "@/Components/IntroCard.vue";
import ContactSection from "@/Components/ContactSection.vue";

const { contactFormTopics } = defineProps<{
    contactFormTopics: string[];
}>();
</script>

<template>
    <Head title="Willkommen" />
    <MainLayout>
        <Hero
            image="/images/geraetehaus.webp"
            class="mb-4 h-[300px] bg-[top_20%_center] md:mb-12 md:h-[500px]"
        >
            <h1 class="pb-12 text-3xl md:text-4xl lg:text-5xl">
                Freiwillige Feuerwehr Hausen
            </h1>
            <p class="text-xl md:text-2xl lg:text-3xl">
                für euch im Einsatz seit 1882
            </p>
        </Hero>

        <IntroCard
            image="/images/intro/einsatzabteilung.webp"
            pull="left"
            class="bg-[right_15%_center]"
        >
            <h1 class="mb-4 text-2xl font-black">Einsatzabteilung</h1>
            <p class="text-base">Trifft sich jeden Freitag um 20:00 Uhr</p>
        </IntroCard>

        <IntroCard
            image="/images/intro/jugendfeuerwehr.webp"
            pull="right"
            class="bg-[left_10%_center]"
        >
            <h1 class="mb-4 text-2xl font-black">Jugendfeuerwehr</h1>
            <p class="text-base">Trifft sich jeden Dienstag um 18:00 Uhr</p>
        </IntroCard>

        <IntroCard
            image="/images/intro/minifeuerwehr.webp"
            pull="left"
            class="bg-[top_15%_center]"
        >
            <h1 class="mb-4 text-2xl font-black">Minifeuerwehr</h1>
            <p class="text-base">Trifft sich jeden Freitag um 16:30 Uhr</p>
        </IntroCard>

        <ContactSection
            id="contact"
            title="Schreib uns!"
            :contactFormTopics="contactFormTopics"
        >
            <div class="mb-4">
                Du hast Interesse uns zu unterstützen? Dann schreib uns gerne
                über das Kontaktformular und wir melden uns bei dir!
            </div>

            <div
                class="flex items-center gap-4 text-base font-bold text-red-600"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke-width="1.5"
                    stroke="currentColor"
                    class="size-6"
                >
                    <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"
                    />
                </svg>
                Im Falle eines Notfalls, wähle bitte die Rufnummer 112.
            </div>
        </ContactSection>
    </MainLayout>
</template>
