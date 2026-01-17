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
    <div class="relative container min-h-[500px] mt-20 xl:-mt-32">
        <div class="bg-[#FBE9D9] rounded-3xl py-10 xl:bg-[unset]">
            <div class="relative hidden xl:block w-full">
                <div class="absolute w-full mt-6 xl:mt-0">
                    <img
                        src="/images/layout_3.png"
                        alt=""
                        class="w-full h-auto object-contain"
                        :style="{
                            transform:
                                $i18n.locale === 'en'
                                    ? 'rotateY(180deg)'
                                    : 'none',
                        }"
                    />
                </div>
            </div>
            <div
                class="relative z-10 flex flex-col xl:pt-16 min-h-[400px] xl:flex-row justify-between items-center xl:mx-10"
            >
                <div class="xl:w-[450px] mx-4">
                    <h2
                        class="text-3xl text-main sm:text-4xl font-bold mb-6"
                        style="line-height: 1.5"
                    >
                        منهجيتنــــــا المتكاملــــــة
                    </h2>
                    <p class="text-main text-md xl:text-xl mb-8 leading-[1.7]">
                        نقدم خدماتنا عبر منصات متخصصــــة لضمان أقصى درجات
                        الجودة والاستدامة في النوع والطعم في كل مرحلـــــــــة
                    </p>
                </div>
                <div class="w-full lg:mt-14 px-4 min-w-0 overflow-hidden">
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
                            v-for="service in services"
                            :key="service"
                            class="relative h-[360px] md:h-[300px]"
                        >
                            <img
                                src="/images/card_bg.png"
                                alt=""
                                class="w-full h-auto rounded-xl object-contain"
                                :style="{
                                    transform:
                                        $i18n.locale === 'en'
                                            ? 'rotateY(180deg)'
                                            : 'none',
                                }" />
                            <div class="absolute w-full h-full top-0">
                                <div class="p-4 pb-2">
                                    <img
                                        :src="
                                            service.media?.url ??
                                            '/images/service.png'
                                        "
                                        :alt="$tt(service.name)"
                                        class="w-full h-auto rounded-xl object-contain"
                                        :style="{
                                            transform:
                                                $i18n.locale === 'en'
                                                    ? 'rotateY(180deg)'
                                                    : 'none',
                                        }"
                                    />
                                </div>
                                <div class="px-4 w-[75%]">
                                    <h3
                                        class="text-3xl text-main sm:text-xl font-bold mb-1"
                                        style="line-height: 1.5"
                                    >
                                        {{ $tt(service.name) }}
                                    </h3>
                                    <p
                                        class="text-main text-sm text-secondary leading-[1.7]"
                                    >
                                        {{ $tt(service.text) }}
                                    </p>
                                </div>
                            </div>
                            <div class="absolute w-[23%] bottom-0 left-0">
                                <img
                                    src="/images/layer_1.svg"
                                    alt=""
                                    class="w-full h-auto rounded-xl object-contain"
                                    :style="{
                                        transform:
                                            $i18n.locale === 'en'
                                                ? 'rotateY(180deg)'
                                                : 'none',
                                    }"
                                /></div></swiper-slide
                    ></swiper>
                </div>
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
