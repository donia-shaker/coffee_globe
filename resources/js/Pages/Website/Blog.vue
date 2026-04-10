<script setup lang="ts">
import Blog from "@/Components/Blog.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { Head } from "@inertiajs/vue3";
import { computed } from "vue";
import { usePage } from "@inertiajs/vue3";

const props = defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    blog: Object,
});

const page = usePage();
const locale = computed(() => page.props.locale || 'ar');

const stripHtml = (html: string) => html?.replace(/<[^>]*>/g, '').substring(0, 160) || '';

const blogImageUrl = computed(() => {
    if (!props.blog?.media?.url) return 'https://coffeeglobe.sa/images/logo.png';
    return props.blog.media.url.startsWith('http')
        ? props.blog.media.url
        : `https://coffeeglobe.sa${props.blog.media.url}`;
});

const blogCanonicalUrl = computed(() => {
    const base = `https://coffeeglobe.sa/blog/${props.blog?.id}`;
    return locale.value === 'en' ? `https://coffeeglobe.sa/en/blog/${props.blog?.id}` : base;
});
</script>

<template>
    <Head>
        <title>{{ $tt(blog?.name) + ' - ' + (locale === 'ar' ? 'كوفى جلوب' : 'Coffee Globe') }}</title>
        <meta name="description" :content="stripHtml($tt(blog?.text))">
        <meta name="keywords" :content="locale === 'ar'
            ? 'مدونة, مقال, قهوة يمنية, بن يمني, كوفى جلوب'
            : 'blog, article, Yemen coffee, Yemeni coffee beans, Coffee Globe'
        ">
        <link rel="canonical" :href="blogCanonicalUrl">
        <meta property="og:title" :content="$tt(blog?.name) + ' - ' + (locale === 'ar' ? 'كوفى جلوب' : 'Coffee Globe')">
        <meta property="og:description" :content="stripHtml($tt(blog?.text))">
        <meta property="og:image" :content="blogImageUrl">
        <meta property="og:image:width" content="1200">
        <meta property="og:image:height" content="630">
        <meta property="og:url" :content="blogCanonicalUrl">
        <meta property="og:type" content="article">
        <meta property="article:published_time" :content="blog?.created_at">
        <meta property="article:modified_time" :content="blog?.updated_at || blog?.created_at">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" :content="$tt(blog?.name) + ' - ' + (locale === 'ar' ? 'كوفى جلوب' : 'Coffee Globe')">
        <meta name="twitter:description" :content="stripHtml($tt(blog?.text))">
        <meta name="twitter:image" :content="blogImageUrl">
    </Head>

    <SchemaOrg
        type="blogPost"
        :data="{
            headline: $tt(blog?.name),
            image: blogImageUrl,
            datePublished: blog?.created_at,
            dateModified: blog?.updated_at || blog?.created_at,
            description: stripHtml($tt(blog?.text)),
            url: blogCanonicalUrl,
        }"
    />
    <SchemaOrg
        type="breadcrumb"
        :data="{
            items: [
                { name: locale === 'ar' ? 'الرئيسية' : 'Home', url: '/' },
                { name: locale === 'ar' ? 'المدونة' : 'Blog', url: '/blogs' },
                { name: $tt(blog?.name) },
            ],
        }"
    />

    <div class="bg-background">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <Blog :blog="blog"></Blog>
        <Footer></Footer>
    </div>
</template>
