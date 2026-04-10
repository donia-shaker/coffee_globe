<script setup lang="ts">
import ContactCards from "@/Components/ContactCards.vue";
import ContactForm from "@/Components/ContactForm.vue";
import Features from "@/Components/Features.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import HeroSlide from "@/Components/HeroSlide.vue";
import PageTitle from "@/Components/PageTitle.vue";
import QS from "@/Components/QS.vue";
import SchemaOrg from "@/Components/SchemaOrg.vue";
import { Head } from "@inertiajs/vue3";
defineProps({
    brands: Object,
    sections: Object,
    contact_us_infos: Object,
    social_media_infos: Object,
});
</script>

<template>
    <Head>
        <title>اختبار الذوق - كوفى جلوب | Coffee Globe</title>
        <meta name="description" content="اكتشف نوع القهوة اليمنية المناسب لك من خلال اختبار الذوق التفاعلي من كوفى جلوب.">
        <meta name="keywords" content="اختبار ذوق, قهوة يمنية, اختبار القهوة, كوفى جلوب">
        <link rel="canonical" href="https://coffeeglobe.sa/test">
        <meta name="robots" content="noindex, nofollow">
    </Head>

    <Header
        :contact_us_infos="contact_us_infos"
        :social_media_infos="social_media_infos"
    ></Header>
    <QS></QS>
    <Footer></Footer>
</template>
