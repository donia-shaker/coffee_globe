<script setup lang="ts">
import { Swiper, SwiperSlide } from "swiper/vue"; // Import Swiper and SwiperSlide from swiper/vue
import { Autoplay, Navigation, Pagination, Scrollbar } from "swiper/modules";
import "swiper/css";
import "swiper/css/scrollbar";
import "swiper/css/pagination";

defineProps({
    // services: Object,
});

const modules = [Autoplay, Pagination, Navigation, Scrollbar];

const rating = 3;
const maxStars = 5;
</script>

<template>
    <div>
        <div class="relative py-[100px] text-center overflow-hidden">
            <div class="container relative z-10">
                <h2
                    class="text-3xl text-main sm:text-4xl font-bold mb-10"
                    style="line-height: 1.5"
                >
                    {{ $t("customer_reviews") }}
                </h2>
                <div class="">
                    <swiper
                        :modules="modules"
                        :slides-per-view="1"
                        :loop="true"
                        :pagination="{ clickable: true }"
                        :speed="700"
                        :space-between="80"
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
                        class="w-full max-w-full h-[320px] relative"
                    >
                        <swiper-slide
                            v-for="brand in 6"
                            :key="brand"
                            class="relative h-auto"
                        >
                            <div
                                class="p-4 text-start bg-background_two rounded-2xl h-auto"
                            >
                                <div class="flex items-center gap-4">
                                    <div class="">
                                        <img
                                            src="/images/service.png"
                                            alt=""
                                            class="w-[60px] h-[60px] rounded-full object-contain"
                                            :style="{
                                                transform:
                                                    $i18n.locale === 'en'
                                                        ? 'rotateY(180deg)'
                                                        : 'none',
                                            }"
                                        />
                                    </div>
                                    <h3
                                        class="text-3xl text-main sm:text-xl font-bold mb-1"
                                        style="line-height: 1.5"
                                    >
                                        محمد العتيبي
                                    </h3>
                                </div>
                                <div
                                    class="mt-6 flex items-center gap-1 text-[#EA864D]"
                                >
                                    <i
                                        v-for="star in maxStars"
                                        :key="star"
                                        class="fas fa-star"
                                        :class="
                                            star > rating ? 'opacity-30' : ''
                                        "
                                    ></i>
                                    <span
                                        class="text-main mx-2 font-bold text-xl"
                                        >5.0</span
                                    >
                                </div>
                                <p
                                    class="text-main text-sm text-secondary my-2  leading-[1.7] h-[100px] overflow-y-auto hide-scroll"
                                >
                                    تحميص احترافـــــــــــي يبرز الخصائص الحسية
                                    لكل نوع بن تحميص احترافـــــــــــي يبرز
                                    الخصائص الحسية لكل نوع بن تحميص
                                    احترافـــــــــــي يبرز الخصائص الحسية لكل
                                    نوع بن تحميص احترافـــــــــــي يبرز الخصائص
                                    الحسية لكل نوع بن
                                </p>
                            </div></swiper-slide
                        ></swiper
                    >
                </div>
            </div>
        </div>
    </div>
</template>
<style scoped>
::v-deep .swiper-pagination-bullet-active {
    background: #8e301e !important;
}
</style>
