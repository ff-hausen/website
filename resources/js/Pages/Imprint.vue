<script setup lang="ts">
import { Head } from "@inertiajs/vue3";
import MainLayout from "@/Layouts/MainLayout.vue";
import Box from "@/Components/Box.vue";
</script>

<template>
    <Head title="Impressum" />

    <MainLayout title="Impressum">
        <Box class="md:max-w-[65ch] md:rounded-lg">
            <article class="prose prose-sm sm:prose-base">
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

                <p class="text-right text-gray-400">
                    Quelle:
                    <a href="https://www.e-recht24.de" class="text-gray-400">
                        https://www.e-recht24.de
                    </a>
                </p>
            </article>
        </Box>
    </MainLayout>
</template>

<style scoped></style>
