<script setup lang="ts">
import Footer from "@/Components/Footer.vue";
import FQsData from "@/Components/FQsData.vue";
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    fqs: Object,
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
        <FQsData :fqs="fqs" class="py-20"></FQsData>
        <Footer></Footer>
    </div>
</template>
