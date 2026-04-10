<script setup lang="ts">
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { defineAsyncComponent } from "vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

// Lazy load below-fold components
const About = defineAsyncComponent(() => import("@/Components/About.vue"));
const ExpertTeam = defineAsyncComponent(() => import("@/Components/ExpertTeam.vue"));
const Values = defineAsyncComponent(() => import("@/Components/Values.vue"));
const Join = defineAsyncComponent(() => import("@/Components/Join.vue"));
const Footer = defineAsyncComponent(() => import("@/Components/Footer.vue"));

defineProps({
    values: Object,
    experts: Object,
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
        <title>{{ locale === 'ar' ? 'من نحن - كوفى جلوب | Coffee Globe' : 'About Us - Coffee Globe' }}</title>
        <meta name="description" :content="locale === 'ar'
            ? 'تعرف على كوفى جلوب - خبرتنا في عالم تجارة القهوة اليمنية، فريقنا من الخبراء، وقيمنا التي نلتزم بها لتقديم أفضل أنواع البن اليمني.'
            : 'Learn about Coffee Globe - our expertise in Yemen coffee trading, our expert team, and our commitment to delivering the finest Yemeni coffee.'
        ">
        <meta name="keywords" :content="locale === 'ar'
            ? 'من نحن, كوفى جلوب, فريق العمل, خبراء قهوة, تجارة بن يمني, قيمنا'
            : 'about us, Coffee Globe, our team, coffee experts, Yemen coffee trading, our values'
        ">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en/about' : 'https://coffeeglobe.sa/about'">
        <meta property="og:title" :content="locale === 'ar' ? 'من نحن - كوفى جلوب | Coffee Globe' : 'About Us - Coffee Globe'">
        <meta property="og:description" :content="locale === 'ar'
            ? 'تعرف على كوفى جلوب - خبرتنا في عالم تجارة القهوة اليمنية وفريقنا من الخبراء'
            : 'Learn about Coffee Globe - our expertise in Yemen coffee trading and our expert team'
        ">
        <meta property="og:image" content="https://coffeeglobe.sa/images/about.svg">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en/about' : 'https://coffeeglobe.sa/about'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="locale === 'ar' ? 'من نحن - كوفى جلوب | Coffee Globe' : 'About Us - Coffee Globe'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'تعرف على كوفى جلوب - خبرتنا في عالم تجارة القهوة اليمنية' : 'Learn about Coffee Globe - expertise in Yemen coffee trading'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/about.svg">
    </Head>

    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: locale === 'ar' ? 'الرئيسية' : 'Home', url: '/' },
                { name: locale === 'ar' ? 'من نحن' : 'About Us', url: '/about' },
            ],
        }"
    />

    <div class="bg-background">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <PageTitle :title="$tt(page.name)" :image="page.media?.url??null"></PageTitle>
        <About :about_page_data="about_page_data"></About>
        <Values :values="values"></Values>
        <ExpertTeam id="expert_team" :experts="experts"></ExpertTeam>
        <Join :about_page_data="about_page_data"></Join>
        <Footer></Footer>
    </div>
</template>
