<script setup lang="ts">
import Blog from "@/Components/Blog.vue";
import Footer from "@/Components/Footer.vue";
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    blog: Object,
});
</script>

<template>
    <div class="bg-background">
        <Header
            :contact_us_infos="contact_us_infos"
            :social_media_infos="social_media_infos"
        ></Header>
        <Blog :blog="blog"></Blog>
        <Footer></Footer>
    </div>
</template>
