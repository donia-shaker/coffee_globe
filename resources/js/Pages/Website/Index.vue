<script setup lang="ts">
import Header from "@/Components/Header.vue";
import HeroSlide from "@/Components/HeroSlide.vue";
import CTA from "@/Components/CTA.vue";
import Services from "@/Components/Services.vue";
import CustomerReviews from "@/Components/CustomerReviews.vue";
import FQS from "@/Components/FQS.vue";
import ContactForm from "@/Components/ContactForm.vue";
import HomeBlogs from "@/Components/HomeBlogs.vue";
import Footer from "@/Components/Footer.vue";
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
</script>

<template>
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
