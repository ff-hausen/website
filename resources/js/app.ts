import "./bootstrap";
import "../css/app.css";

import { createApp, DefineComponent, h } from "vue";
import { createInertiaApp, router } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import dayjs from "dayjs";
import de from "dayjs/locale/de";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

dayjs.locale(de);

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>("./Pages/**/*.vue"),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});

router.on("before", (event) => {
    const url = event.detail.visit.url;

    if (url.pathname.startsWith("/admin")) {
        event.preventDefault();
        window.location.href = url.href;
    }
});
