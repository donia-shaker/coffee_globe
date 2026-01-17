<script setup lang="ts">
import { Swiper, SwiperSlide } from "swiper/vue"; // Import Swiper and SwiperSlide from swiper/vue
import { Autoplay, Navigation, Pagination, Scrollbar } from "swiper/modules";
import "swiper/css";
import "swiper/css/scrollbar";
import "swiper/css/pagination";
import { Link } from "@inertiajs/vue3";
import BlogCard from "./BlogCard.vue";

defineProps({
    blogs: Object,
});

const modules = [Autoplay, Pagination, Navigation, Scrollbar];
</script>

<template>
    <div>
        <div class="relative py-[80px] text-center overflow-hidden">
            <div class="container relative z-10">
                <h2
                    class="text-3xl text-main sm:text-4xl font-bold mb-10"
                    style="line-height: 1.5"
                >
                    {{ $t("latest_news") }}
                </h2>
                <div class="">
                    <swiper
                        :modules="modules"
                        :autoplay="{
                            delay: 3000,
                            disableOnInteraction: false,
                        }"
                        :slides-per-view="1"
                        :loop="true"
                        :speed="700"
                        :space-between="20"
                        :breakpoints="{
                            0: {
                                slidesPerView: 1,
                            },
                            600: {
                                slidesPerView: 2,
                            },
                            1500: {
                                slidesPerView: 3,
                            },
                        }"
                        class="w-full max-w-full h-full relative"
                    >
                        <swiper-slide
                            v-for="blog in blogs"
                            :key="blog"
                            class=""
                        >
                            <BlogCard :blog="blog" /> </swiper-slide
                    ></swiper>
                </div>
            </div>
        </div>
    </div>
</template>
