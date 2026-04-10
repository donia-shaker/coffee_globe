<script setup lang="ts">
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { defineAsyncComponent } from "vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

// Lazy load below-fold components
const ContactForm = defineAsyncComponent(() => import("@/Components/ContactForm.vue"));
const Footer = defineAsyncComponent(() => import("@/Components/Footer.vue"));

defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    page: Object,
});

const page2 = usePage();
const locale = computed(() => page2.props.locale || 'ar');
</script>

<template>
    <Head>
        <title>{{ locale === 'ar' ? 'تواصل معنا - كوفى جلوب | Coffee Globe' : 'Contact Us - Coffee Globe' }}</title>
        <meta name="description" :content="locale === 'ar'
            ? 'تواصل مع كوفى جلوب للاستفسار عن خدمات تجارة القهوة اليمنية - نسعد بتلقي استفساراتكم وطلباتكم.'
            : 'Contact Coffee Globe for inquiries about Yemen coffee trading services. We are happy to receive your inquiries and requests.'
        ">
        <meta name="keywords" :content="locale === 'ar'
            ? 'تواصل معنا, اتصل بنا, كوفى جلوب, استفسار, تجارة قهوة'
            : 'contact us, get in touch, Coffee Globe, inquiry, coffee trading'
        ">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en/contact' : 'https://coffeeglobe.sa/contact'">
        <meta property="og:title" :content="locale === 'ar' ? 'تواصل معنا - كوفى جلوب | Coffee Globe' : 'Contact Us - Coffee Globe'">
        <meta property="og:description" :content="locale === 'ar' ? 'تواصل مع كوفى جلوب للاستفسار عن خدمات تجارة القهوة اليمنية' : 'Contact Coffee Globe for Yemen coffee trading inquiries'">
        <meta property="og:image" content="https://coffeeglobe.sa/images/contact.svg">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en/contact' : 'https://coffeeglobe.sa/contact'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="locale === 'ar' ? 'تواصل معنا - كوفى جلوب | Coffee Globe' : 'Contact Us - Coffee Globe'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'تواصل مع كوفى جلوب' : 'Contact Coffee Globe'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/contact.svg">
    </Head>

    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: locale === 'ar' ? 'الرئيسية' : 'Home', url: '/' },
                { name: locale === 'ar' ? 'تواصل معنا' : 'Contact Us', url: '/contact' },
            ],
        }"
    />

    <div class="bg-background">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <PageTitle :title="$tt(page?.name) ?? (locale === 'ar' ? 'تواصل معنا' : 'Contact Us')" :image="page?.media?.url ?? null"></PageTitle>
        <ContactForm
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></ContactForm>
        <Footer></Footer>
    </div>
</template>
