import "../css/app.css";
import "./bootstrap";

import { createInertiaApp } from "@inertiajs/vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { createApp, h } from "vue";
import { ZiggyVue } from "../../vendor/tightenco/ziggy";
import { createI18n } from "vue-i18n";

import "ant-design-vue/dist/reset.css";
import Antd from "ant-design-vue";
import * as allIcon from "@ant-design/icons-vue/es";

const appName = import.meta.env.VITE_APP_NAME || "Laravel";

const messagesLocales = Object.fromEntries(
    Object.entries(import.meta.glob("./locales/*.json", { eager: true })).map(
        ([key, value]) => [key.slice(10, -5), value.default]
    )
);

// Create a Vue app
const i18n = createI18n({
    locale: "ar", // Default language
    messages: messagesLocales,
    legacy: false, // ✅ تشغيل Composition API
});

const filterIcons = [
    "default",
    "createFromIconfontCN",
    "getTwoToneColor",
    "setTwoToneColor",
];

// Create Vue app and register the translation function globally
createInertiaApp({
    // title: (title) => `${title} - ${appName}`,
    title: (title) => `${appName} - ${title}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        ),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(Antd)
            .use(i18n);

        // Add translation method globally
        app.config.globalProperties.$tt = function (translations) {
            return translations[this.$i18n.locale] || translations["en"]; // Default to 'en' if translation not found
        };

        Object.keys(allIcon)
            .filter((k) => !filterIcons.includes(k))
            .forEach((k) => {
                app.component(allIcon[k].displayName, allIcon[k]);
            });

        app.mount(el);
    },
    progress: {
        color: "#4B5563",
    },
});
