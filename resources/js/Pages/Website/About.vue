<script setup lang="ts">
import About from "@/Components/About.vue";
import ContactCards from "@/Components/ContactCards.vue";
import ContactForm from "@/Components/ContactForm.vue";
import ExpertTeam from "@/Components/ExpertTeam.vue";
import Features from "@/Components/Features.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import HeroSlide from "@/Components/HeroSlide.vue";
import Join from "@/Components/Join.vue";
import PageTitle from "@/Components/PageTitle.vue";
import Values from "@/Components/Values.vue";
defineProps({
    values: Object,
    experts: Object,
    about_page_data: Object,
    contact_us_infos: Object,
    social_media_infos: Object,
});
</script>

<template>
    <div class="bg-background">

    <Header :contact_us_infos="contact_us_infos"  :social_media_infos="social_media_infos"></Header>
    <PageTitle :title="$t('about')"></PageTitle>
    <About :about_page_data="about_page_data"></About>
    <Values :values="values"></Values>
    <ExpertTeam :experts="experts"></ExpertTeam>
    <Join :about_page_data="about_page_data"></Join>
</div>  
    <!-- <Footer :sections="sections" :brands="brands" :contact_us_infos="contact_us_infos"  :social_media_infos="social_media_infos"></Footer> -->
</template>
