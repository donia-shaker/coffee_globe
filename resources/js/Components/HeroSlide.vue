<script setup>
import { Swiper, SwiperSlide } from "swiper/vue";
import {
    Autoplay,
    Navigation,
    Pagination,
    Scrollbar,
    EffectFade,
} from "swiper/modules";
import "swiper/css";
import { Link } from "@inertiajs/vue3";
import Button from "./Button.vue";

const modules = [Autoplay, Pagination, Navigation, Scrollbar, EffectFade];

const props = defineProps({
    sliders: Object, // نستخدم Array لأننا هنجلبها من الـ API
});
</script>

<template>
    <div class="container xl:pt-8">
        <div class="card">
            <!-- المنتجات -->
            <swiper
                ref="swiperRef"
                :modules="modules"
                :slides-per-view="1"
                :loop="sliders.length > 1"
                :centered-slides="true"
                effect="fade"
                :fade-effect="{ crossFade: true }"
                :speed="1000"
                :autoplay="{
                    delay: 8000,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: false,
                    stopOnLastSlide: false,
                    waitForTransition: false,
                }"
                :navigation="false"
                :pagination="true"
                class="content relative w-full py-8 h-auto xl:h-screen"
            >
                <swiper-slide
                    v-for="slider in sliders"
                    :key="slider.id"
                    class="xl:min-h-fit"
                >
                    <div class="relative xl:hidden w-full">
                        <div class="absolute w-full mt-6 xl:mt-0">
                            <img
                                src="/images/layout_2.png"
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
                    <div class="relative hidden xl:block w-full">
                        <div class="absolute w-full mt-6 xl:mt-0">
                            <img
                                src="/images/layout.png"
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
                        <div class="absolute w-full mt-6 xl:mt-0">
                            <img
                                src="/images/mask.png"
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
                        class="relative z-10 flex flex-col xl:flex-row justify-between items-center h-full"
                    >
                        <!-- النص -->
                        <div
                            class="w-full xl:w-[55%] flex flex-col items-center mt-14 xl:-mt-36 px-4 rtl:xl:pr-12 ltr:xl:pl-12"
                        >
                            <div class="w-full xl:w-[90%]">
                                <div class="mb-6 xl:mb-10 mx-10 xl:mx-20">
                                    <img src="/images/logo.png" alt="" class="w-[80px]  md:w-[100px]" />
                                </div>
                                <h1
                                    class="text-2xl sm:text-4xl font-bold mb-6"
                                    style="line-height: 1.5"
                                >
                                    {{ $tt(slider.title) }}
                                </h1>
                                <p
                                    class="text-primary paragraph text-md xl:text-2xl font-bold mb-8 leading-[1.7]"
                                >
                                    {{ $tt(slider.text) }}
                                </p>
                                <div class="flex justify-end">
                                    <Button type="primary" text="">
                                        {{ $t("explore") }}
                                        <i class="fas fa-arrow-left mx-2"></i>
                                    </Button>
                                </div>
                            </div>
                        </div>

                        <div class="w-full xl:w-[44%] mt-2 p-10 xl:mt-0">
                            <img
                                :src="slider.media?.url ?? '/images/slider.png'"
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
                </swiper-slide>
            </swiper>
        </div>
    </div>
</template>

<style scoped>
.swiper-slide {
    background-repeat: no-repeat;
    background-size: cover;
    background-position: center;
    position: relative;
    opacity: 0;
    transition: opacity 1s ease-in-out;
}

.swiper-slide.swiper-slide-active {
    opacity: 1;
}

/* رجّعت هذا الجزء لأنه كان ناقص ويسبب الخطأ */
.swiper-button-prev,
.swiper-button-next {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #2a1f05;
    opacity: 0.8;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    top: 45%;
    transform: translateY(-50%);
    z-index: 10;
}

.swiper-button-prev::after,
.swiper-button-next::after {
    font-size: 16px;
    font-weight: bold;
}

.paragraph {
    line-height: 1.6;
}

.swiper-pagination-bullet {
    opacity: 0.3 !important;
    height: 12px !important;
    width: 12px !important;
}

.swiper-pagination-bullet-active {
    background: #2a1f05 !important;
    opacity: 1 !important;
}
</style>
