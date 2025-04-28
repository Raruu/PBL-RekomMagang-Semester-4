import { resolve } from "path";
import { defineConfig, normalizePath } from "vite";
import laravel from "laravel-vite-plugin";
import { viteStaticCopy } from "vite-plugin-static-copy";

export default defineConfig({
    plugins: [
        laravel({
            input: ["resources/css/app.css", "resources/js/app.js"],
            refresh: true,
        }),
        viteStaticCopy({
            targets: [
                {
                    src: normalizePath(
                        resolve(
                            __dirname,
                            "./node_modules/@coreui/icons/sprites/*"
                        )
                    ),
                    dest: "@coreui/icons/sprites",
                },
                {
                    src: normalizePath(
                        resolve(
                            __dirname,
                            "./node_modules/chart.js/dist/*"
                        )
                    ),
                    dest: "chart.js",
                },
            ],
        }),
    ],
});
