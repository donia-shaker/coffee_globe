<script setup lang="ts">
import ContactCards from "@/Components/ContactCards.vue";
import ContactForm from "@/Components/ContactForm.vue";
import Features from "@/Components/Features.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import HeroSlide from "@/Components/HeroSlide.vue";
import PageTitle from "@/Components/PageTitle.vue";
defineProps({
    brands: Object,
    sections: Object,
    contact_us_infos: Object,
    social_media_infos: Object,
});
</script>

<template>
    <Header :contact_us_infos="contact_us_infos"  :social_media_infos="social_media_infos"></Header>
    <PageTitle :title="$t('contact')"></PageTitle>
    <ContactForm :contact_us_infos="contact_us_infos"></ContactForm>
    <Footer :sections="sections" :brands="brands" :contact_us_infos="contact_us_infos"  :social_media_infos="social_media_infos"></Footer>
</template>
