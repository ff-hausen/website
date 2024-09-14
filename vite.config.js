import { defineConfig } from "vite";
import laravel, { refreshPaths } from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: "resources/js/app.ts",
            ssr: "resources/js/ssr.ts",
            refresh: [...refreshPaths, "app/Filament/**"],
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
