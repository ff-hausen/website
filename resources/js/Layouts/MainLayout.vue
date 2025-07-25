<script setup lang="ts">
import {
    Disclosure,
    DisclosureButton,
    DisclosurePanel,
    Menu,
    MenuButton,
    MenuItem,
    MenuItems,
} from "@headlessui/vue";
import { Bars3Icon, BellIcon, XMarkIcon } from "@heroicons/vue/24/outline";
import { type Component, computed, inject } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import Footer from "@/Components/Footer.vue";
import { MainNavigationItem, UserNavigationItem } from "@/types";
import LinkButton from "@/Components/LinkButton.vue";
import { route as ziggyRoute } from "ziggy-js";

const route = inject<typeof ziggyRoute>("route")!;

const page = usePage();
const user = computed(() => page.props.auth.user);

const { title, noFooter = false } = defineProps<{
    title?: string;
    noFooter?: boolean;
}>();

const navigation: Array<MainNavigationItem> = [
    // { name: 'Einsatzabteilung', href: '#', current: false },
    // { name: 'Jugendfeuerwehr', href: '#', current: false },
    // { name: 'Minifeuerwehr', href: '#', current: false },
];

const userNavigation: Array<UserNavigationItem> = [
    {
        name: "Dienstplan",
        href: route("dashboard"),
    },
    {
        name: "Wiki",
        href: "https://wiki.ff26.de",
    },
    {
        name: "Dein Profil",
        href: route("profile.edit"),
    },
    ...(page.props.auth.can.access_admin
        ? [
              {
                  name: "Verwaltung",
                  href: route("filament.admin.pages.dashboard"),
              },
          ]
        : []),
    {
        name: "Abmelden",
        href: route("logout"),
        method: "post",
    },
];
</script>

<template>
    <div class="flex min-h-screen flex-col">
        <Disclosure as="nav" class="bg-red-600" v-slot="{ open }">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 items-center justify-between">
                    <div class="flex items-center">
                        <div class="shrink-0">
                            <Link href="/" class="flex items-center">
                                <img
                                    class="h-10"
                                    src="/images/ffhausen-logo.png"
                                    alt=""
                                    aria-hidden="true"
                                />
                                <div
                                    class="ml-2 hidden flex-col justify-center text-justify leading-tight font-black text-white md:block"
                                >
                                    <div>Freiwillige Feuerwehr</div>
                                    <div>Frankfurt Hausen</div>
                                </div>
                            </Link>
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline space-x-4">
                                <Link
                                    v-for="item in navigation"
                                    :key="item.name"
                                    :href="item.href"
                                    :class="[
                                        item.current
                                            ? 'bg-red-700 text-white'
                                            : 'text-white hover:bg-red-500/75',
                                        'rounded-md px-3 py-2 text-sm font-medium',
                                    ]"
                                    :aria-current="
                                        item.current ? 'page' : undefined
                                    "
                                    >{{ item.name }}
                                </Link>
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block" v-if="user">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button
                                type="button"
                                class="relative rounded-full bg-red-600 p-1 text-red-200 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-red-600 focus:outline-hidden"
                            >
                                <span class="absolute -inset-1.5" />
                                <span class="sr-only">View notifications</span>
                                <BellIcon class="h-6 w-6" aria-hidden="true" />
                            </button>

                            <!-- Profile dropdown -->
                            <Menu as="div" class="relative ml-3">
                                <div>
                                    <MenuButton
                                        class="relative flex max-w-xs items-center rounded-full bg-red-600 text-sm focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-red-600 focus:outline-hidden"
                                    >
                                        <span class="absolute -inset-1.5" />
                                        <span class="sr-only"
                                            >Open user menu</span
                                        >
                                        <img
                                            v-if="user.image_url"
                                            class="h-8 w-8 rounded-full"
                                            :src="user.image_url"
                                            alt=""
                                        />
                                    </MenuButton>
                                </div>
                                <transition
                                    enter-active-class="transition ease-out duration-100"
                                    enter-from-class="transform opacity-0 scale-95"
                                    enter-to-class="transform opacity-100 scale-100"
                                    leave-active-class="transition ease-in duration-75"
                                    leave-from-class="transform opacity-100 scale-100"
                                    leave-to-class="transform opacity-0 scale-95"
                                >
                                    <MenuItems
                                        class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-hidden"
                                    >
                                        <MenuItem
                                            v-for="item in userNavigation"
                                            :key="item.name"
                                            v-slot="{ active }"
                                        >
                                            <component
                                                :is="
                                                    item.method &&
                                                    item.method !== 'get'
                                                        ? LinkButton
                                                        : Link
                                                "
                                                :href="item.href"
                                                :method="item.method ?? 'get'"
                                                :class="[
                                                    active ? 'bg-gray-100' : '',
                                                    'block w-full px-4 py-2 text-left text-sm text-gray-700',
                                                ]"
                                            >
                                                {{ item.name }}
                                            </component>
                                        </MenuItem>
                                    </MenuItems>
                                </transition>
                            </Menu>
                        </div>
                    </div>
                    <div class="hidden md:block" v-else>
                        <Link
                            :href="route('login')"
                            class="block px-4 py-2 text-sm text-gray-200"
                            >Mitgliederbereich
                        </Link>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <!-- Mobile menu button -->
                        <DisclosureButton
                            class="relative inline-flex items-center justify-center rounded-md bg-red-600 p-2 text-red-200 hover:bg-red-500/75 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-red-600 focus:outline-hidden"
                        >
                            <span class="absolute -inset-0.5" />
                            <span class="sr-only">Open main menu</span>
                            <Bars3Icon
                                v-if="!open"
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                            <XMarkIcon
                                v-else
                                class="block h-6 w-6"
                                aria-hidden="true"
                            />
                        </DisclosureButton>
                    </div>
                </div>
            </div>

            <DisclosurePanel class="md:hidden">
                <div class="space-y-1 px-2 pt-2 pb-3 sm:px-3">
                    <DisclosureButton
                        v-for="item in navigation"
                        :key="item.name"
                        as="Link"
                        :href="item.href"
                        :class="[
                            item.current
                                ? 'bg-red-700 text-white'
                                : 'text-white hover:bg-red-500/75',
                            'block rounded-md px-3 py-2 text-base font-medium',
                        ]"
                        :aria-current="item.current ? 'page' : undefined"
                        >{{ item.name }}
                    </DisclosureButton>
                </div>
                <div class="border-t border-red-700 pt-4 pb-3" v-if="user">
                    <div class="flex items-center px-5">
                        <div class="shrink-0">
                            <img
                                v-if="user.image_url"
                                class="h-10 w-10 rounded-full"
                                :src="user.image_url"
                                alt=""
                            />
                        </div>
                        <div class="ml-3">
                            <div class="text-base font-medium text-white">
                                {{ user.first_name }} {{ user.last_name }}
                            </div>
                            <div class="text-sm font-medium text-red-300">
                                {{ user.email }}
                            </div>
                        </div>
                        <button
                            type="button"
                            class="relative ml-auto shrink-0 rounded-full bg-red-600 p-1 text-red-200 hover:text-white focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-red-600 focus:outline-none"
                        >
                            <span class="absolute -inset-1.5" />
                            <span class="sr-only">View notifications</span>
                            <BellIcon class="h-6 w-6" aria-hidden="true" />
                        </button>
                    </div>
                    <div class="mt-3 space-y-1 px-2">
                        <DisclosureButton
                            v-for="item in userNavigation"
                            :key="item.name"
                            :href="item.href"
                            :method="item.method ?? 'get'"
                            :as="
                                item.method && item.method !== 'get'
                                    ? LinkButton
                                    : Link
                            "
                            class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-white hover:bg-red-500/75"
                        >
                            {{ item.name }}
                        </DisclosureButton>
                    </div>
                </div>
                <div class="border-t border-red-700 pt-4 pb-3" v-else>
                    <div class="mt-3 space-y-1 px-2">
                        <DisclosureButton
                            :href="route('login')"
                            :as="Link"
                            class="block w-full rounded-md px-3 py-2 text-left text-base font-medium text-white hover:bg-red-500/75"
                        >
                            Mitgliederbereich
                        </DisclosureButton>
                    </div>
                </div>
            </DisclosurePanel>
        </Disclosure>

        <header class="bg-white shadow-xs" v-if="title">
            <div class="mx-auto max-w-7xl px-4 py-4 sm:px-6 lg:px-8">
                <h1 class="text-lg leading-6 font-semibold text-gray-900">
                    {{ title }}
                </h1>
            </div>
        </header>
        <main class="grow">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
        <Footer v-if="!noFooter" />
    </div>
</template>

<style scoped></style>
