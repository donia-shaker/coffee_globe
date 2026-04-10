<script setup lang="ts">
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { Head } from "@inertiajs/vue3";
import { useI18n } from "vue-i18n";

const { t, locale } = useI18n();

defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
});
</script>

<template>
    <Head>
        <title>{{ $t('privacy_policy') }} - Coffee Globe</title>
        <meta name="description" :content="locale === 'ar' ? 'سياسة الخصوصية لموقع كوفى جلوب - تعرف على كيفية حماية بياناتك الشخصية ومعلوماتك عند استخدام موقعنا.' : 'Privacy Policy for Coffee Globe - Learn how we protect your personal data and information when using our website.'">
        <meta name="robots" content="index, follow">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en/privacy-policy' : 'https://coffeeglobe.sa/privacy-policy'">
        <meta property="og:title" :content="$t('privacy_policy') + ' - Coffee Globe'">
        <meta property="og:description" :content="locale === 'ar' ? 'سياسة الخصوصية لموقع كوفى جلوب' : 'Privacy Policy - Coffee Globe'">
        <meta property="og:image" content="https://coffeeglobe.sa/images/logo.png">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en/privacy-policy' : 'https://coffeeglobe.sa/privacy-policy'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="$t('privacy_policy') + ' - Coffee Globe'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'سياسة الخصوصية لموقع كوفى جلوب' : 'Privacy Policy - Coffee Globe'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/logo.png">
    </Head>

    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: $t('home'), url: '/' },
                { name: $t('privacy_policy'), url: '/privacy-policy' },
            ],
        }"
    />

    <div class="bg-gradient-to-b from-background/30 to-transparent">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <PageTitle :title="$t('privacy_policy')" :image="null"></PageTitle>

        <section class="container py-16 px-4 max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm text-main leading-relaxed">
                <h2 class="text-2xl md:text-3xl font-bold mb-6 text-main">{{ $t('privacy_policy') }}</h2>
                <p class="mb-4 text-gray-500">{{ $t('last_updated') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('privacy_info_collect') }}</h3>
                <p class="mb-2">{{ $t('privacy_info_collect_text') }}</p>
                <ul class="list-disc rtl:pr-6 ltr:pl-6 mb-4 space-y-1">
                    <li>{{ $t('privacy_info_1') }}</li>
                    <li>{{ $t('privacy_info_2') }}</li>
                    <li>{{ $t('privacy_info_3') }}</li>
                    <li>{{ $t('privacy_info_4') }}</li>
                </ul>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('privacy_how_use') }}</h3>
                <p class="mb-2">{{ $t('privacy_how_use_text') }}</p>
                <ul class="list-disc rtl:pr-6 ltr:pl-6 mb-4 space-y-1">
                    <li>{{ $t('privacy_use_1') }}</li>
                    <li>{{ $t('privacy_use_2') }}</li>
                    <li>{{ $t('privacy_use_3') }}</li>
                    <li>{{ $t('privacy_use_4') }}</li>
                </ul>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('privacy_data_protection') }}</h3>
                <p class="mb-4">{{ $t('privacy_data_protection_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('privacy_cookies') }}</h3>
                <p class="mb-4">{{ $t('privacy_cookies_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('privacy_rights') }}</h3>
                <p class="mb-2">{{ $t('privacy_rights_text') }}</p>
                <ul class="list-disc rtl:pr-6 ltr:pl-6 mb-4 space-y-1">
                    <li>{{ $t('privacy_right_1') }}</li>
                    <li>{{ $t('privacy_right_2') }}</li>
                    <li>{{ $t('privacy_right_3') }}</li>
                    <li>{{ $t('privacy_right_4') }}</li>
                </ul>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('privacy_contact') }}</h3>
                <p class="mb-4">{{ $t('privacy_contact_text') }}</p>
                <ul class="list-disc rtl:pr-6 ltr:pl-6 mb-4 space-y-1">
                    <li>{{ $t('email') }}: info@coffeeglobe.sa</li>
                    <li><a href="/contact" class="text-secondary underline hover:text-primary transition">{{ $t('contact_us_link') }}</a></li>
                </ul>
            </div>
        </section>
        <Footer></Footer>
    </div>
</template>
