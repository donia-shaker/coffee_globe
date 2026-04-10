<script setup lang="ts">
import { ref, onMounted, computed } from "vue";
import { Link, usePage } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";
import Button from "./Button.vue";

const mobileMenuOpen = ref(false);
defineProps({
    cartItems: Object,
    contact_us_infos: Object,
    social_media_infos: Object,
});
const { t, locale } = useI18n();
const page = usePage();

// Get current locale from server-side Inertia props
const currentLocale = computed(() => page.props.locale || 'ar');

// Generate locale-aware URL
const localeHref = (path: string) => {
    if (currentLocale.value === 'en') {
        return '/en' + (path === '/' ? '' : path);
    }
    return path;
};

// Get the base URL path for active state checking (strips /en prefix)
const basePath = computed(() => {
    const url = page.url;
    if (url.startsWith('/en')) {
        return url.slice(3) || '/';
    }
    return url;
});

// URL-based language switching for SEO
const switchLang = (lang: string) => {
    const currentPath = window.location.pathname;

    // Remove existing /en/ prefix if present
    let cleanPath = currentPath;
    if (cleanPath.startsWith('/en')) {
        cleanPath = cleanPath.slice(3) || '/';
    }

    // Navigate to the appropriate locale URL
    if (lang === 'en') {
        window.location.href = '/en' + (cleanPath === '/' ? '' : cleanPath);
    } else {
        window.location.href = cleanPath || '/';
    }
};

onMounted(() => {
    // Use requestAnimationFrame to batch DOM modifications and avoid forced reflows
    requestAnimationFrame(() => {
        // Set locale from server-side Inertia props (determined by URL prefix)
        const serverLocale = page.props.locale || 'ar';
        locale.value = serverLocale;

        // Set document attributes to match server locale
        document.documentElement.setAttribute("dir", serverLocale === "ar" ? "rtl" : "ltr");
        document.documentElement.setAttribute("lang", serverLocale);
    });
});
</script>

<template>
    <!-- الـ Navbar -->
    <div class="absolute w-full my-2 mt-6 md:mt-8 2xl:mt-10 z-50">
        <div class="container">
            <nav
                class="flex justify-between items-start py-2 mx-auto px-4 md:mt-10 lg:mt-0 xl:px-4"
                :class="mobileMenuOpen ? 'bg-background xl:bg-[unset]' : ''"
            >
                <div
                    class="mx-10 xl:mx-20 w-[80px] mt-2 w-[300px] 2xl:w-[100px] relative"
                    :class="mobileMenuOpen ? 'mb-4' : 'mb-10'"
                >
                    <Link :href="localeHref('/')">
                        <picture>
                            <source srcset="/images/logo.webp" type="image/webp">
                            <img
                                src="/images/logo.png"
                                :alt="currentLocale === 'ar' ? 'كوفى جلوب - Coffee Globe' : 'Coffee Globe'"
                                class="w-full h-full object-content-fit"
                                width="100" height="100"
                                fetchpriority="high"
                            />
                        </picture>
                    </Link>
                </div>
                <!-- Desktop Menu -->
                <div class="hidden xl:block">
                    <ul
                        class="flex gap-3 font-bold text-main items-end mb-2 text-sm xl:text-base ltr:text-sm"
                    >
                        <li>
                            <Link :href="localeHref('/')">
                                <Button
                                    :isNav="true"
                                    :type="
                                        basePath === '/' || basePath === '/home'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('home')"
                            /></Link>
                        </li>
                        <li>
                            <Link :href="localeHref('/about')">
                                <Button
                                    :isNav="true"
                                    :type="
                                        basePath === '/about' || basePath === '/about#expert_team'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('about')"
                                />
                            </Link>
                        </li>
                        <li>
                            <Link :href="localeHref('/solution')">
                                <Button
                                    :isNav="true"
                                    :type="
                                        basePath === '/solution'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('our_solutions')"
                                />
                            </Link>
                        </li>
                        <li>
                            <Link :href="localeHref('/blogs')">
                                <Button
                                    :isNav="true"
                                    :type="
                                        basePath === '/blogs'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('blogs')"
                                />
                            </Link>
                        </li>

                        <li>
                            <Link :href="localeHref('/fqs')">
                                <Button
                                    :isNav="true"
                                    :type="
                                        basePath === '/fqs'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('fqs')"
                                />
                            </Link>
                        </li>
                        <li>
                            <a :href="localeHref('/#contact')">
                                <Button
                                    :isNav="true"
                                    :type="
                                        basePath === '/#contact'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('contact')"
                                />
                            </a>
                        </li>
                        <li>
                            <div class="hidden xl:flex">
                                <div
                                    class="hidden xl:flex ltr:hidden cursor-pointer font-bold items-center justify-end gap-1 text-main hover:text-primary"
                                    @click="switchLang('en')"
                                >
                                    <Button
                                        :isNav="true"
                                        :type="'secondary'"
                                        text=""
                                    >
                                        <i class="fas fa-globe px-1"></i>
                                        <span>EN</span>
                                    </Button>
                                </div>

                                <div
                                    class="hidden xl:flex rtl:hidden cursor-pointer font-bold items-center justify-end gap-1 text-main hover:text-primary"
                                    @click="switchLang('ar')"
                                >
                                    <Button
                                        :isNav="true"
                                        :type="'secondary'"
                                        text=""
                                    >
                                        <i class="fas fa-globe px-1"></i>
                                        <span>AR</span>
                                    </Button>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- زر الموبايل -->
                <div class="xl:hidden">
                    <button
                        :isnav="true"
                        @click="mobileMenuOpen = !mobileMenuOpen"
                        class="text-2xl text-gray-700"
                    >
                        <i
                            :class="
                                mobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'
                            "
                        ></i>
                    </button>
                </div>
            </nav>

            <!-- Mobile Menu -->
            <div
                v-if="mobileMenuOpen"
                class="xl:hidden bg-background px-4 py-4"
            >
                <ul class="flex flex-col gap-6 font-bold text-gray-700 text-sm">
                    <li class="hover:text-primary text-main transition">
                        <Link :href="localeHref('/')">{{ $t("home") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link :href="localeHref('/about')">{{ $t("about") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link :href="localeHref('/solution')">{{ $t("our_solutions") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link :href="localeHref('/blogs')">{{ $t("blogs") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link :href="localeHref('/fqs')">{{ $t("fqs") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link :href="localeHref('/#contact')">{{ $t("contact") }}</Link>
                    </li>
                    <li>
                        <div
                            class="ltr:hidden cursor-pointer text-main"
                            @click="switchLang('en')"
                        >
                            <div class="font-bold">
                                <i class="fas fa-globe"></i> EN
                            </div>
                        </div>
                        <div
                            class="rtl:hidden cursor-pointer text-main"
                            @click="switchLang('ar')"
                        >
                            <div class="font-bold">
                                <i class="fas fa-globe"></i> AR
                            </div>
                        </div>
                    </li>

                    <li v-for="contact_us_info in contact_us_infos">
                        <div class="flex items-center gap-x-1 text-main">
                            <div class="flex items-center gap-x-2">
                                <i :class="$tt(contact_us_info.icon)"></i>
                                <span>{{ $tt(contact_us_info.name) }}:</span>
                            </div>
                            <span class="text-sm">{{
                                $tt(contact_us_info.value)
                            }}</span>
                        </div>
                    </li>
                    <li>
                        <div class="icons flex gap-x-6">
                            <a
                                v-for="social_media_info in social_media_infos"
                                :href="social_media_info.url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="text-main"
                            >
                                <i :class="social_media_info.icon"></i
                            ></a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<style>
.hide-scroll::-webkit-scrollbar {
    display: none;
}

.hide-scroll {
    -ms-overflow-style: none; /* IE & Edge */
    scrollbar-width: none; /* Firefox */
}
</style>
