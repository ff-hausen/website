<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Box from "@/Components/Box.vue";
</script>

<template>
    <Head title="Impressum" />

    <MainLayout title="Impressum">
        <Box class="lg:max-w-[900px] lg:rounded-lg">
            <!--        <div class="mx-auto mt-4 max-w-[700px] rounded-xl bg-white px-8 py-4">-->
            <p>
                Freiwillige Feuerwehr Frankfurt-Hausen e.V.<br />
                Am Ellerfeld 18<br />
                60488 Frankfurt am Main
            </p>

            <p>
                Vereinsregister: VR8346<br />
                Registergericht: Amtsgericht Frankfurt am Main
            </p>

            <p>
                <strong>Vertreten durch:</strong><br />
                Andreas Gombert<br />
                Moritz Krieger<br />
                Regina Nardi<br />
                Stevie W&uuml;rker
            </p>

            <h2>Kontakt</h2>
            <p>
                Telefon: 069 76752829<br />
                E-Mail: info@ff-frankfurt-hausen.de
            </p>

            <h2>
                Verbraucher&shy;streit&shy;beilegung/Universal&shy;schlichtungs&shy;stelle
            </h2>
            <p>
                Wir sind nicht bereit oder verpflichtet, an
                Streitbeilegungsverfahren vor einer
                Verbraucherschlichtungsstelle teilzunehmen.
            </p>

            <p class="source">
                Quelle:
                <a href="https://www.e-recht24.de">https://www.e-recht24.de</a>
            </p>
        </Box>
    </MainLayout>
</template>

<style scoped>
h2 {
    @apply text-lg font-bold;
}

p {
    @apply mb-4;
}

.source {
    @apply text-gray-400;
}

.source a {
    @apply text-blue-300 underline;
}
</style>
