<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Hero from "@/Components/Hero.vue";
import IntroCard from "@/Components/IntroCard.vue";
</script>

<template>
    <Head title="Willkommen" />
    <MainLayout>
        <Hero
            image="/images/geraetehaus.jpg"
            class="h-[300px] bg-[top_20%_center] md:h-[500px]"
        >
            <h1 class="pb-12 text-3xl lg:text-6xl">
                Freiwillige Feuerwehr Hausen
            </h1>
            <p class="text-xl lg:text-3xl">Für euch im Einsatz seit 1882</p>
        </Hero>

        <div class="my-8 text-center text-2xl font-black text-red-500">
            🚨 Work in progress!
        </div>

        <IntroCard
            image="/images/intro/einsatzabteilung.png"
            pull="left"
            class="bg-[top_center]"
        >
            <h1 class="mb-4 text-xl font-black">Einsatzabteilung</h1>
            <p class="text-base">Trifft sich jeden Freitag um 20:00 Uhr</p>
        </IntroCard>

        <IntroCard
            image="/images/intro/jugendfeuerwehr.png"
            pull="right"
            class="bg-[top_30%_center]"
        >
            <h1 class="mb-4 text-xl font-black">Jugendfeuerwehr</h1>
            <p class="text-base">Trifft sich jeden Dienstag um 18:00 Uhr</p>
        </IntroCard>

        <IntroCard
            image="/images/intro/minifeuerwehr.png"
            pull="left"
            class="bg-[top_15%_center]"
        >
            <h1 class="mb-4 text-xl font-black">Minifeuerwehr</h1>
            <p class="text-base">Trifft sich jeden Freitag um 16:30 Uhr</p>
        </IntroCard>
    </MainLayout>
</template>
