import { resolve } from "path";
import { defineConfig, normalizePath } from "vite";
import laravel from "laravel-vite-plugin";
import { viteStaticCopy } from "vite-plugin-static-copy";

export default defineConfig({
    server: {
        cors: true, // This will enable CORS for all origins during development
        // Or for specific origins:
        // cors: {
        //   origin: 'http://192.168.3.77:8000', // Allow requests from your backend
        //   methods: 'GET,HEAD,PUT,PATCH,POST,DELETE',
        //   credentials: true,
        // },
    },
    plugins: [
        laravel({
            input: [
                "resources/css/app.css",
                "resources/js/app.js",
                "resources/css/auth.css",
                "resources/css/timeline.css",
                "resources/js/import/leaflet.js",
                "resources/js/import/tagify.js",
            ],
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
                        resolve(__dirname, "./node_modules/chart.js/dist/*")
                    ),
                    dest: "chart.js",
                },
            ],
        }),
    ],
});
