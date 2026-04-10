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
        <title>{{ $t('terms_of_service') }} - Coffee Globe</title>
        <meta name="description" :content="locale === 'ar' ? 'شروط استخدام موقع كوفى جلوب - يرجى قراءة هذه الشروط بعناية قبل استخدام موقعنا الإلكتروني.' : 'Terms of Service for Coffee Globe - Please read these terms carefully before using our website.'">
        <meta name="robots" content="index, follow">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en/terms-of-service' : 'https://coffeeglobe.sa/terms-of-service'">
        <meta property="og:title" :content="$t('terms_of_service') + ' - Coffee Globe'">
        <meta property="og:description" :content="locale === 'ar' ? 'شروط استخدام موقع كوفى جلوب' : 'Terms of Service - Coffee Globe'">
        <meta property="og:image" content="https://coffeeglobe.sa/images/logo.png">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en/terms-of-service' : 'https://coffeeglobe.sa/terms-of-service'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="$t('terms_of_service') + ' - Coffee Globe'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'شروط استخدام موقع كوفى جلوب' : 'Terms of Service - Coffee Globe'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/logo.png">
    </Head>

    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: $t('home'), url: '/' },
                { name: $t('terms_of_service'), url: '/terms-of-service' },
            ],
        }"
    />

    <div class="bg-gradient-to-b from-background/30 to-transparent">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <PageTitle :title="$t('terms_of_service')" :image="null"></PageTitle>

        <section class="container py-16 px-4 max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-8 md:p-12 shadow-sm text-main leading-relaxed">
                <h2 class="text-2xl md:text-3xl font-bold mb-6 text-main">{{ $t('terms_of_service') }}</h2>
                <p class="mb-4 text-gray-500">{{ $t('last_updated') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_accept') }}</h3>
                <p class="mb-4">{{ $t('terms_accept_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_usage') }}</h3>
                <p class="mb-2">{{ $t('terms_usage_text') }}</p>
                <ul class="list-disc rtl:pr-6 ltr:pl-6 mb-4 space-y-1">
                    <li>{{ $t('terms_usage_1') }}</li>
                    <li>{{ $t('terms_usage_2') }}</li>
                    <li>{{ $t('terms_usage_3') }}</li>
                </ul>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_content') }}</h3>
                <p class="mb-4">{{ $t('terms_content_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_ip') }}</h3>
                <p class="mb-4">{{ $t('terms_ip_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_liability') }}</h3>
                <p class="mb-4">{{ $t('terms_liability_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_external') }}</h3>
                <p class="mb-4">{{ $t('terms_external_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_modifications') }}</h3>
                <p class="mb-4">{{ $t('terms_modifications_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_governing_law') }}</h3>
                <p class="mb-4">{{ $t('terms_governing_law_text') }}</p>

                <h3 class="text-lg md:text-xl font-bold mb-3 mt-8 text-secondary">{{ $t('terms_contact') }}</h3>
                <p class="mb-4">{{ $t('terms_contact_text') }}</p>
                <ul class="list-disc rtl:pr-6 ltr:pl-6 mb-4 space-y-1">
                    <li>{{ $t('email') }}: info@coffeeglobe.sa</li>
                    <li><a href="/contact" class="text-secondary underline hover:text-primary transition">{{ $t('contact_us_link') }}</a></li>
                </ul>
            </div>
        </section>
        <Footer></Footer>
    </div>
</template>
