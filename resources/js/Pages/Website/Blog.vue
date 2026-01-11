<script setup lang="ts">
import Blog from "@/Components/Blog.vue";
import Header from "@/Components/Header.vue";
import PageTitle from "@/Components/PageTitle.vue";
defineProps({
    contact_us_infos: Object,
    social_media_infos: Object,
    blog: Object,
});
</script>

<template>
    <div class="bg-gradient-to-b from-background/30 to-transparent">

    <Header :contact_us_infos="contact_us_infos"  :social_media_infos="social_media_infos"></Header>
    <!-- <PageTitle :title="$t('blogs')"></PageTitle> -->
    <Blog :blog="blog"></Blog>

</div>  
    <!-- <Footer :sections="sections" :brands="brands" :contact_us_infos="contact_us_infos"  :social_media_infos="social_media_infos"></Footer> -->
</template>
