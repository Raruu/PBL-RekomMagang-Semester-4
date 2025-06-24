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
                "resources/css/auth.css",
                "resources/css/timeline.css",
                "resources/css/mhs-feedback.css",
                "resources/css/lowongan/index.css",
                "resources/css/lowongan/create.css",
                "resources/css/lowongan/lanjutan.css",
                "resources/css/landing.css",
                "resources/css/Periode/edit.css",
                "resources/css/admin/profil.css",
                "resources/js/app.js",
                "resources/js/import/leaflet.js",
                "resources/js/import/tagify.js",
                "resources/js/admin/spk/edit-bobot.js",
                "resources/js/admin/spk/index.js",
                "resources/js/admin/statistik/index.js",
                "resources/js/admin/index.js",
                "resources/js/landing.js",
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
                        resolve(
                            __dirname,
                            "./node_modules/leaflet/dist/images/*"
                        )
                    ),
                    dest: "leaflet/dist/images",
                },
            ],
        }),
    ],
});
