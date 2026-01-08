<script setup lang="ts">
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import HeroSlide from "@/Components/HeroSlide.vue";
import About from "@/Components/About.vue";
import Features from "@/Components/Features.vue";
import CTA from "@/Components/CTA.vue";
import Services from "@/Components/Services.vue";
import CustomerReviews from "@/Components/CustomerReviews.vue";
import FQS from "@/Components/FQS.vue";
import ContactForm from "@/Components/ContactForm.vue";
import HomeBlogs from "@/Components/HomeBlogs.vue";
defineProps({
    sliders: Object,
    contact_us_infos: Object,
    about_page_data: Object,
    social_media_infos: Object,
    services: Object,
    features: Object,
    categories: Object,
    main_center: Object,
    branches: Object,
});
</script>

<template>
    <div class="bg-background">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <HeroSlide id="home" :sliders="sliders"></HeroSlide>
        <Services :services="services"></Services>
        <CustomerReviews></CustomerReviews>
        <CTA class="bg-fifth"></CTA>
        <FQS></FQS>
        <HomeBlogs></HomeBlogs>
        <!-- <About id="about" :about_page_data="about_page_data"></About>
        <Features class="bg-fifth" :features="features"></Features> -->
        <ContactForm
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></ContactForm>
        <!-- <Footer
            id="contact"
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Footer> -->
    </div>
</template>

<style>
html {
    scroll-behavior: smooth;
}
</style>
