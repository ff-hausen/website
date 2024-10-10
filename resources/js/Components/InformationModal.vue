<script setup lang="ts">
import { useTemplateRef } from "vue";
import { InformationCircleIcon } from "@heroicons/vue/24/outline";

defineProps<{
    title: string;
    text: string;
}>();

const modal = useTemplateRef("modal");
</script>

<template>
    <div class="">
        <button role="button" @click="modal!.showModal()">
            <InformationCircleIcon class="size-6" />
        </button>
        <dialog ref="modal" class="modal">
            <div class="modal-box">
                <form method="dialog">
                    <button
                        class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2"
                    >
                        ✕
                    </button>
                </form>
                <h3 class="mb-2 text-lg font-bold">{{ title }}</h3>
                <div class="prose" v-html="text"></div>
            </div>
            <form method="dialog" class="modal-backdrop">
                <button>close</button>
            </form>
        </dialog>
    </div>
</template>

<style scoped></style>
