<script setup lang="ts">
import { Swiper, SwiperSlide } from "swiper/vue"; // Import Swiper and SwiperSlide from swiper/vue
import { Autoplay, Navigation, Pagination, Scrollbar } from "swiper/modules";
import "swiper/css";
import "swiper/css/scrollbar";
import "swiper/css/pagination";

defineProps({
    services: Object,
});

const modules = [Autoplay, Pagination, Navigation, Scrollbar];
</script>

<template>
    <div class="relative container h-screen -mt-32">
        <div class="relative hidden xl:block w-full">
            <div class="absolute w-full mt-6 xl:mt-0">
                <img
                    src="/images/layout_3.png"
                    alt=""
                    class="w-full h-auto object-contain"
                    :style="{
                        transform:
                            $i18n.locale === 'en' ? 'rotateY(180deg)' : 'none',
                    }"
                />
            </div>
        </div>
        <div
            class="relative z-10 flex flex-col min-h-[500px] xl:flex-row justify-between items-center mx-10 h-full"
        >
            <div class="md:w-[340px]">
                <h2
                    class="text-3xl text-main sm:text-4xl font-bold mb-6"
                    style="line-height: 1.5"
                >
                    منهجيتنــــــا المتكاملــــــة
                </h2>
                <p class="text-main text-md xl:text-xl mb-8 leading-[1.7]">
                    نقدم خدماتنا عبر منصات متخصصــــة لضمان أقصى درجات الجودة
                    والاستدامة في النوع والطعم في كل مرحلـــــــــة
                </p>
            </div>
            <div class="flex-1 mt-10 min-w-0">
                <swiper
                    :modules="modules"
                    :slides-per-view="1"
                    :loop="true"
                    :speed="700"
                    :breakpoints="{
                        0: {
                            slidesPerView: 1,
                        },
                        600: {
                            slidesPerView: 2,
                        },
                        800: {
                            slidesPerView: 3,
                        },
                        1500: {
                            slidesPerView: 3,
                        },
                    }"
                    class=" relative"
                >
                    <swiper-slide v-for="brand in 6" :key="brand">
                        <img
                                src="/images/card_bg.png"
                                alt=""
                                class="w-full h-auto object-contain"
                                :style="{
                                    transform:
                                        $i18n.locale === 'en'
                                            ? 'rotateY(180deg)'
                                            : 'none',
                                }"
                            />
                        <p>asdasd</p>
                    </swiper-slide></swiper
                >
            </div>
        </div>
    </div>
</template>
<style scoped>
.swiper-slide .card {
    text-align: center;
    font-size: 18px;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 200px;

    transition: all 1000ms ease, opacity 1000ms ease;
}
</style>
