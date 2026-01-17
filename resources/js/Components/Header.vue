<script setup lang="ts">
import { ref, onMounted, nextTick } from "vue";
import { Link, router, usePage } from "@inertiajs/vue3";
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

// تغيير اللغة
const switchLang = (lang: string) => {
    locale.value = lang;
    localStorage.setItem("lang", lang);
    localStorage.setItem("dir", lang === "ar" ? "rtl" : "ltr");

    document.documentElement.setAttribute("dir", lang === "ar" ? "rtl" : "ltr");
    document.documentElement.setAttribute("lang", lang === "ar" ? "ar" : "en");

    nextTick(() => {
        location.reload();
    });
};

onMounted(() => {
    const savedLang = localStorage.getItem("lang");
    const savedDir = localStorage.getItem("dir");

    if (savedLang) {
        locale.value = savedLang;
    }

    if (savedDir) {
        document.documentElement.setAttribute("dir", savedDir);
        document.documentElement.setAttribute(
            "lang",
            savedDir == "ltr" ? "en" : "ar"
        );
    } else {
        document.documentElement.setAttribute("dir", "rtl");
        document.documentElement.setAttribute("lang", "ar");
    }
});
</script>

<template>
    <!-- الـ Navbar -->
    <div class="absolute w-full my-2 mt-6 md:mt-10 z-50">
        <div class="container">
            <nav
                class="flex justify-between items-start py-2 mx-auto px-4 md:mt-10 lg:mt-0 xl:px-4"
                :class="mobileMenuOpen ? 'bg-background xl:bg-[unset]' : ''"
            >
                <div
                    class="mx-10 xl:mx-20 w-[80px] mt-2 md:w-[100px] relative"
                    :class="mobileMenuOpen ? 'mb-4' : 'mb-10'"
                >
                    <img
                        src="/images/logo.png"
                        alt=""
                        class="w-full h-full object-content-fit"
                    />
                </div>
                <!-- Desktop Menu -->
                <div class="hidden xl:block -scale-2">
                    <ul
                        class="flex gap-3 font-bold text-main items-end mb-2 text-sm xl:text-base ltr:text-sm"
                    >
                        <li>
                            <Link href="/">
                                <Button
                                    :type="
                                        page.url === '/' || page.url === '/home'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('home')"
                            /></Link>
                        </li>
                        <li>
                            <Link href="/about">
                                <Button
                                    :type="
                                        page.url === '/about'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('about')"
                                />
                            </Link>
                        </li>
                        <li>
                            <Link href="/solution">
                                <Button
                                    :type="
                                        page.url  === '/solution'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('our_solutions')"
                                />
                            </Link>
                        </li>
                        <li>
                            <Link href="/blogs">
                                <Button
                                    :type="
                                        page.url  === '/blogs'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('blogs')"
                                />
                            </Link>
                        </li>

                        <li>
                            <Link href="/fqs">
                                <Button
                                    :type="
                                        page.url  === '/fqs'
                                            ? 'primary'
                                            : 'secondary'
                                    "
                                    :text="$t('fqs')"
                                />
                            </Link>
                        </li>
                        <li>
                            <a>
                                <Button
                                    :type="
                                        page.url  === '/contact'
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
                                    <Button :type="'secondary'" text="">
                                        <i class="fas fa-globe px-1"></i>
                                        <span>EN</span>
                                    </Button>
                                </div>

                                <div
                                    class="hidden xl:flex rtl:hidden cursor-pointer font-bold items-center justify-end gap-1 text-main hover:text-primary"
                                    @click="switchLang('ar')"
                                >
                                    <Button :type="'secondary'" text="">
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
                        <Link href="/">{{ $t("home") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link href="/about">{{ $t("about") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link href="/solution">{{ $t("our_solutions") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link href="/blogs">{{ $t("blogs") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link href="/fqs">{{ $t("fqs") }}</Link>
                    </li>
                    <li class="hover:text-primary text-main transition">
                        <Link href="#contact">{{ $t("contact") }}</Link>
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
