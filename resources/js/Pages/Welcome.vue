<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import MainLayout from "@/Layouts/MainLayout.vue";
import Hero from "@/Components/Hero.vue";

</script>

<template>
    <Head title="Willkommen" />
    <MainLayout>
        <Hero image="/images/geraetehaus.jpg" class="h-[500px] bg-[top_20%_center]">
            <h1 class="text-3xl lg:text-6xl pb-12">Freiwillige Feuerwehr Hausen</h1>
            <p class="text-xl lg:text-3xl">Für euch im Einsatz seit 1882</p>
        </Hero>

      <div class="text-center text-2xl mt-8 text-red-500 font-black">🚨 Work in progress!</div>
    </MainLayout>
</template>
