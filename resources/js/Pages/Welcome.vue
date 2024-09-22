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
            <h1 class="pb-12 text-3xl lg:text-6xl">
                Freiwillige Feuerwehr Hausen
            </h1>
            <p class="text-xl lg:text-3xl">für euch im Einsatz seit 1882</p>
        </Hero>

        <IntroCard
            image="/images/intro/einsatzabteilung.webp"
            pull="left"
            class="bg-[right_15%_center]"
        >
            <h1 class="mb-4 text-xl font-black">Einsatzabteilung</h1>
            <p class="text-base">Trifft sich jeden Freitag um 20:00 Uhr</p>
        </IntroCard>

        <IntroCard
            image="/images/intro/jugendfeuerwehr.webp"
            pull="right"
            class="bg-[left_10%_center]"
        >
            <h1 class="mb-4 text-xl font-black">Jugendfeuerwehr</h1>
            <p class="text-base">Trifft sich jeden Dienstag um 18:00 Uhr</p>
        </IntroCard>

        <IntroCard
            image="/images/intro/minifeuerwehr.webp"
            pull="left"
            class="bg-[top_15%_center]"
        >
            <h1 class="mb-4 text-xl font-black">Minifeuerwehr</h1>
            <p class="text-base">Trifft sich jeden Freitag um 16:30 Uhr</p>
        </IntroCard>

        <ContactSection
            id="contact"
            title="Schreib uns!"
            :contactFormTopics="contactFormTopics"
        >
            Proin volutpat consequat porttitor cras nullam gravida at. Orci
            molestie a eu arcu. Sed ut tincidunt integer elementum id sem. Arcu
            sed malesuada et magna.
        </ContactSection>
    </MainLayout>
</template>
