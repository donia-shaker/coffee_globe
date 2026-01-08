<script setup lang="ts">
import { Swiper, SwiperSlide } from "swiper/vue"; // Import Swiper and SwiperSlide from swiper/vue
import { Autoplay, Navigation, Pagination, Scrollbar } from "swiper/modules";
import "swiper/css";
import "swiper/css/scrollbar";
import "swiper/css/pagination";
import { Link } from "@inertiajs/vue3";
import BlogCard from "./BlogCard.vue";

defineProps({
    // services: Object,
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
                            v-for="brand in 6"
                            :key="brand"
                            class=""
                        >
                            <BlogCard />
                            <!-- <div class="p-4 pb-2">
                                <img
                                    src="/images/service.png"
                                    alt=""
                                    class="w-full h-auto rounded-2xl object-contain"
                                    :style="{
                                        transform:
                                            $i18n.locale === 'en'
                                                ? 'rotateY(180deg)'
                                                : 'none',
                                    }"
                                />
                            </div>
                            <div class="px-4 text-start mb-10">
                                <h3
                                    class="text-3xl text-main sm:text-xl font-bold mb-1"
                                    style="line-height: 1.5"
                                >
                                    المحمصة
                                </h3>
                                <p
                                    class="text-main text-sm text-secondary leading-[1.7]"
                                >
                                    تحميص احترافـــــــــــي يبرز الخصائص الحسية
                                    لكل نوع بن
                                </p>
                                <Link href="#">
                                    <div class="text-primary flex items-end mt-2 font-bold"
                                        >{{ $t("read_more") }}
                                        <i class="fas fa-arrow-left mx-2"></i>
                                    </div>
                                </Link>
                            </div> -->
                        </swiper-slide></swiper
                    >
                </div>
            </div>
        </div>
    </div>
</template>
