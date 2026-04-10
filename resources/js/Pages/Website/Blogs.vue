<script setup lang="ts">
import Blogs from "@/Components/Blogs.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    blogs: Object,
    page: Object,
});

const page2 = usePage();
const locale = computed(() => page2.props.locale || 'ar');
</script>

<template>
    <Head>
        <title>{{ locale === 'ar' ? 'المدونة - كوفى جلوب | Coffee Globe' : 'Blog - Coffee Globe' }}</title>
        <meta name="description" :content="locale === 'ar'
            ? 'اقرأ أحدث المقالات والأخبار عن عالم القهوة اليمنية - نصائح، أخبار الصناعة، وكل ما يهم عشاق البن اليمني من كوفى جلوب.'
            : 'Read the latest articles and news about the world of Yemeni coffee - tips, industry news, and everything for Yemeni coffee lovers from Coffee Globe.'
        ">
        <meta name="keywords" :content="locale === 'ar'
            ? 'مدونة, مقالات, قهوة يمنية, أخبار بن, نصائح قهوة, كوفى جلوب'
            : 'blog, articles, Yemen coffee, coffee news, coffee tips, Coffee Globe'
        ">
        <link rel="canonical" :href="locale === 'en' ? 'https://coffeeglobe.sa/en/blogs' : 'https://coffeeglobe.sa/blogs'">
        <meta property="og:title" :content="locale === 'ar' ? 'المدونة - كوفى جلوب | Coffee Globe' : 'Blog - Coffee Globe'">
        <meta property="og:description" :content="locale === 'ar'
            ? 'أحدث المقالات والأخبار عن عالم القهوة اليمنية'
            : 'Latest articles and news about the world of Yemeni coffee'
        ">
        <meta property="og:image" content="https://coffeeglobe.sa/images/logo.png">
        <meta property="og:url" :content="locale === 'en' ? 'https://coffeeglobe.sa/en/blogs' : 'https://coffeeglobe.sa/blogs'">
        <meta property="og:type" content="website">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="locale === 'ar' ? 'المدونة - كوفى جلوب | Coffee Globe' : 'Blog - Coffee Globe'">
        <meta name="twitter:description" :content="locale === 'ar' ? 'أحدث المقالات والأخبار عن عالم القهوة اليمنية' : 'Latest articles and news about Yemeni coffee'">
        <meta name="twitter:image" content="https://coffeeglobe.sa/images/logo.png">
    </Head>

    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: locale === 'ar' ? 'الرئيسية' : 'Home', url: '/' },
                { name: locale === 'ar' ? 'المدونة' : 'Blog', url: '/blogs' },
            ],
        }"
    />

    <div class="bg-gradient-to-b from-background/30 to-transparent">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <PageTitle :title="$tt(page.name)" :image="page.media?.url??null"></PageTitle>
        <Blogs :blogs="blogs"></Blogs>
        <Footer></Footer>
    </div>
</template>
