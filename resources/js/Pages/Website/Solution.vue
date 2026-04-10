<script setup lang="ts">
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { defineAsyncComponent } from "vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

// Lazy load below-fold components
const PartnerService = defineAsyncComponent(() => import("@/Components/PartnerService.vue"));
const WhyUs = defineAsyncComponent(() => import("@/Components/WhyUs.vue"));
const OrderService = defineAsyncComponent(() => import("@/Components/OrderService.vue"));
const Footer = defineAsyncComponent(() => import("@/Components/Footer.vue"));

defineProps({
    service_companies: Object,
    why_uss: Object,
    about_page_data: Object,
    contact_us_infos: Object,
    social_media_infos: Object,
    page: Object,
});

const page2 = usePage();
const locale = computed(() => page2.props.locale || 'ar');
</script>

<template>
    <Head>
        <title>{{ locale === 'ar' ? 'حلولنا - كوفى جلوب | Coffee Globe' : 'Our Solutions - Coffee Globe' }}</title>
        <meta name="description" :content="locale === 'ar'
            ? 'اكتشف حلول كوفى جلوب المتكاملة في تجارة القهوة اليمنية - شركاؤنا ولماذا نحن الخيار الأفضل لتوريد أجود أنواع البن اليمني.'
            : 'Discover Coffee Globe integrated solutions in Yemen coffee trading - our partners and why we are the best choice for supplying the finest Yemeni coffee.'
        ">
        <meta name="keywords" :content="locale === 'ar'
            ? 'حلول, خدمات, شركاء, تجارة قهوة, توريد بن, كوفى جلوب'
            : 'solutions, services, partners, coffee trading, coffee supply, Coffee Globe'
        ">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en/solution' : 'https://coffeeglobe.sa/solution'">
        <meta property="og:title" :content="locale === 'ar' ? 'حلولنا - كوفى جلوب | Coffee Globe' : 'Our Solutions - Coffee Globe'">
        <meta property="og:description" :content="locale === 'ar'
            ? 'حلول كوفى جلوب المتكاملة في تجارة القهوة اليمنية'
            : 'Coffee Globe integrated solutions in Yemen coffee trading'
        ">
        <meta property="og:image" content="https://coffeeglobe.sa/images/service.png">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en/solution' : 'https://coffeeglobe.sa/solution'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="locale === 'ar' ? 'حلولنا - كوفى جلوب | Coffee Globe' : 'Our Solutions - Coffee Globe'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'حلول كوفى جلوب المتكاملة في تجارة القهوة اليمنية' : 'Coffee Globe integrated solutions in Yemen coffee trading'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/service.png">
    </Head>

    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: locale === 'ar' ? 'الرئيسية' : 'Home', url: '/' },
                { name: locale === 'ar' ? 'حلولنا' : 'Our Solutions', url: '/solution' },
            ],
        }"
    />

    <div class="bg-gradient-to-b from-background/30 to-transparent">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <PageTitle :title="$tt(page.name)" :image="page.media?.url??null"></PageTitle>
        <PartnerService :service_companies="service_companies"></PartnerService>
        <WhyUs :why_uss="why_uss"></WhyUs>
        <OrderService :about_page_data="about_page_data"></OrderService>
        <Footer></Footer>
    </div>
</template>
