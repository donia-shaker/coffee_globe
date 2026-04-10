<script setup lang="ts">
import Header from "@/Components/Header.vue";
import HeroSlide from "@/Components/HeroSlide.vue";
import { defineAsyncComponent } from "vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

// Eager load above-fold components
// Lazy load below-fold components for better performance
const CTA = defineAsyncComponent(() => import("@/Components/CTA.vue"));
const Services = defineAsyncComponent(() => import("@/Components/Services.vue"));
const CustomerReviews = defineAsyncComponent(() => import("@/Components/CustomerReviews.vue"));
const FQS = defineAsyncComponent(() => import("@/Components/FQS.vue"));
const ContactForm = defineAsyncComponent(() => import("@/Components/ContactForm.vue"));
const HomeBlogs = defineAsyncComponent(() => import("@/Components/HomeBlogs.vue"));
const Footer = defineAsyncComponent(() => import("@/Components/Footer.vue"));

defineProps({
    sliders: Object,
    contact_us_infos: Object,
    client_reviews: Object,
    fqs: Object,
    blogs: Object,
    about_page_data: Object,
    social_media_infos: Object,
    services: Object,
});

const page = usePage();
const locale = computed(() => page.props.locale || 'ar');
</script>

<template>
    <Head>
        <title>{{ locale === 'ar' ? 'كوفى جلوب - تجارة القهوة اليمنية الفاخرة | Coffee Globe' : 'Coffee Globe - Premium Yemen Coffee Trading' }}</title>
        <meta name="description" :content="locale === 'ar'
            ? 'شركة كوفى جلوب لخدمات تجارة البن اليمنى - نقدم أجود أنواع القهوة اليمنية الفاخرة. تجارة وتصدير بن يمني عالي الجودة مع حلول متكاملة لشركائنا.'
            : 'Coffee Globe - Leading Yemen coffee trading company. We offer the finest Yemeni coffee beans, export services, and integrated solutions for our partners.'
        ">
        <meta name="keywords" :content="locale === 'ar'
            ? 'قهوة يمنية, بن يمني, تجارة قهوة, كوفى جلوب, قهوة عربية, تصدير قهوة, Yemen coffee'
            : 'Yemen coffee, Yemeni coffee beans, coffee trading, Coffee Globe, Arabic coffee, coffee export, premium coffee'
        ">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en' : 'https://coffeeglobe.sa'">
        <meta property="og:title" :content="locale === 'ar' ? 'كوفى جلوب - تجارة القهوة اليمنية الفاخرة | Coffee Globe' : 'Coffee Globe - Premium Yemen Coffee Trading'">
        <meta property="og:description" :content="locale === 'ar'
            ? 'شركة كوفى جلوب لخدمات تجارة البن اليمنى - أجود أنواع القهوة اليمنية الفاخرة'
            : 'Coffee Globe - The finest Yemeni coffee trading company'
        ">
        <meta property="og:image" content="https://coffeeglobe.sa/images/bg_slide.png">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en' : 'https://coffeeglobe.sa'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="locale === 'ar' ? 'كوفى جلوب - تجارة القهوة اليمنية الفاخرة' : 'Coffee Globe - Premium Yemen Coffee Trading'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'شركة كوفى جلوب لخدمات تجارة البن اليمنى - أجود أنواع القهوة اليمنية' : 'Coffee Globe - The finest Yemeni coffee trading company'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/bg_slide.png">
    </Head>

    <SchemaOrg
        type="organization"
        :data="{
            description: locale === 'ar'
                ? 'شركة رائدة في تجارة وتصدير القهوة اليمنية الفاخرة'
                : 'A leading company in trading and exporting premium Yemeni coffee',
            telephone: contact_us_infos ? contact_us_infos[0]?.value : '',
            sameAs: social_media_infos ? social_media_infos.map(s => s.url) : [],
        }"
    />
    <SchemaOrg
        type="localBusiness"
        :data="{
            telephone: contact_us_infos ? contact_us_infos[0]?.value : '',
            email: contact_us_infos ? contact_us_infos.find((c: any) => c.icon?.includes('envelope'))?.value : '',
            sameAs: social_media_infos ? social_media_infos.map(s => s.url) : [],
        }"
    />

    <div class="bg-background">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <HeroSlide id="home" :sliders="sliders"></HeroSlide>
        <Services :services="services"></Services>
        <CustomerReviews :client_reviews="client_reviews"></CustomerReviews>
        <CTA class="bg-fifth"></CTA>
        <FQS :fqs="fqs"></FQS>
        <HomeBlogs :blogs="blogs"></HomeBlogs>
        <ContactForm
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></ContactForm>
        <Footer></Footer>
    </div>
</template>

<style>
html {
    scroll-behavior: smooth;
}
</style>
