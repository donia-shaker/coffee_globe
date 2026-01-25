<script setup lang="ts">
import About from "@/Components/About.vue";
import Blogs from "@/Components/Blogs.vue";
import ContactCards from "@/Components/ContactCards.vue";
import ContactForm from "@/Components/ContactForm.vue";
import ExpertTeam from "@/Components/ExpertTeam.vue";
import Features from "@/Components/Features.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import HeroSlide from "@/Components/HeroSlide.vue";
import Join from "@/Components/Join.vue";
import OrderService from "@/Components/OrderService.vue";
import PageTitle from "@/Components/PageTitle.vue";
import PartnerService from "@/Components/PartnerService.vue";
import Values from "@/Components/Values.vue";
import WhyUs from "@/Components/WhyUs.vue";
defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    blogs: Object,
    page: Object,
});
</script>

<template>
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
