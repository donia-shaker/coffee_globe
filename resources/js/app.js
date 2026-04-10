import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { createI18n } from "vue-i18n";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

const messagesLocales = Object.fromEntries(
    Object.entries(import.meta.glob("./locales/*.json", { eager: true })).map(
        ([key, value]) => [key.slice(10, -5), value.default]
    )
);

// Create Vue app and register the translation function globally
createInertiaApp({
    title: (title) => `${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        // Read locale from Inertia shared props (set by server from URL prefix)
        const serverLocale = props.initialPage.props.locale || "ar";

        const i18n = createI18n({
            locale: serverLocale,
            messages: messagesLocales,
            legacy: false,
        });

        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(i18n);

        // Add translation method globally for JSON-based multilingual fields
        app.config.globalProperties.$tt = function (translations) {
            return translations[this.$i18n.locale] || translations["en"] || translations["ar"] || "";
        };

        app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
