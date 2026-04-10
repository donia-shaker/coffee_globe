<script setup lang="ts">
import Footer from "@/Components/Footer.vue";
import FQsData from "@/Components/FQsData.vue";
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    fqs: Object,
    page: Object,
});

const page2 = usePage();
const locale = computed(() => page2.props.locale || 'ar');
</script>

<template>
    <Head>
        <title>{{ locale === 'ar' ? 'الأسئلة الشائعة - كوفى جلوب | Coffee Globe' : 'FAQ - Coffee Globe' }}</title>
        <meta name="description" :content="locale === 'ar'
            ? 'إجابات على أكثر الأسئلة شيوعاً عن خدمات كوفى جلوب في تجارة القهوة اليمنية - تعرف على كل ما يهمك عن منتجاتنا وخدماتنا.'
            : 'Answers to the most frequently asked questions about Coffee Globe Yemen coffee trading services. Learn everything about our products and services.'
        ">
        <meta name="keywords" :content="locale === 'ar'
            ? 'أسئلة شائعة, FAQ, استفسارات, كوفى جلوب, قهوة يمنية, تجارة بن'
            : 'FAQ, frequently asked questions, Coffee Globe, Yemen coffee, coffee trading'
        ">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en/fqs' : 'https://coffeeglobe.sa/fqs'">
        <meta property="og:title" :content="locale === 'ar' ? 'الأسئلة الشائعة - كوفى جلوب | Coffee Globe' : 'FAQ - Coffee Globe'">
        <meta property="og:description" :content="locale === 'ar'
            ? 'إجابات على أكثر الأسئلة شيوعاً عن خدمات كوفى جلوب'
            : 'Answers to the most frequently asked questions about Coffee Globe'
        ">
        <meta property="og:image" content="https://coffeeglobe.sa/images/logo.png">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en/fqs' : 'https://coffeeglobe.sa/fqs'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="locale === 'ar' ? 'الأسئلة الشائعة - كوفى جلوب | Coffee Globe' : 'FAQ - Coffee Globe'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'إجابات على أكثر الأسئلة شيوعاً عن خدمات كوفى جلوب' : 'Answers to frequently asked questions about Coffee Globe'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/logo.png">
    </Head>

    <SchemaOrg
        type="faq"
        :data="{
            items: (fqs || []).map((fq: any) => ({
                question: $tt(fq.name),
                answer: $tt(fq.text)?.replace(/<[^>]*>/g, ''),
            })),
        }"
    />
    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: locale === 'ar' ? 'الرئيسية' : 'Home', url: '/' },
                { name: locale === 'ar' ? 'الأسئلة الشائعة' : 'FAQ', url: '/fqs' },
            ],
        }"
    />

    <div class="bg-gradient-to-b from-background/30 to-transparent">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <PageTitle :title="$tt(page.name)" :image="page.media?.url??null"></PageTitle>
        <FQsData :fqs="fqs" class="py-20"></FQsData>
        <Footer></Footer>
    </div>
</template>
