<script setup lang="ts">
import { Swiper, SwiperSlide } from "swiper/vue"; // Import Swiper and SwiperSlide from swiper/vue
import { Autoplay, Navigation, Pagination, Scrollbar } from "swiper/modules";
import "swiper/css";
import "swiper/css/scrollbar";
import "swiper/css/pagination";
import { reactive } from "vue";

const props = defineProps({
    services: Object,
});
// اجعل كل خدمة reactive و أضف hovered
const reactiveServices = reactive(
    props.services.map((service) => ({ ...service, hovered: false })),
);
const modules = [Autoplay, Pagination, Navigation, Scrollbar];
</script>

<template>
    <div class="relative container min-h-[500px] mt-20 xl:-mt-48 2xl:-mt-32">
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
                <div class="xl:w-[320px] 2xl:w-[450px] mx-4">
                    <h2
                        class="text-3xl text-main sm:text-4xl xl:text-3xl 2xl:text-5xl font-bold mb-6 text-justify rtl"
                        style="line-height: 1.5"
                    >
                        {{ $t("our_integrated_methodology") }}
                    </h2>
                    <p
                        class="text-main text-md 2xl:text-xl mb-8 leading-[1.7] text-justify rtl"
                    >
                        {{ $t("services_quality_statement") }}
                    </p>
                </div>
                <div
                    class="w-full mt-10 2xl:mt-14 px-4 min-w-0 overflow-hidden"
                >
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
                            1200: {
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
                                :alt="$tt(service.name)"
                                class="w-full h-auto rounded-xl object-contain"
                                :style="{
                                    transform:
                                        $i18n.locale === 'en'
                                            ? 'rotateY(180deg)'
                                            : 'none',
                                }"
                            />
                            <div class="absolute w-full h-full top-0">
                                <div class="p-4 xl:pb-0 pb-2 2xl:pb-2">
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
                                <div class="px-4 ltr:pr-0 w-[75%]">
                                    <h3
                                        class="text-lg text-main 2xl:text-xl ltr:text-lg font-bold mb-1"
                                        style="line-height: 1.5"
                                    >
                                        {{ $tt(service.name) }}
                                    </h3>
                                    <p
                                        class="text-main text-[12px] 2xl:text-sm ltr:text-[12px] text-secondary leading-[1.7]"
                                    >
                                        {{ $tt(service.text) }}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="absolute w-[23%] bottom-0 rtl:left-0 ltr:right-0"
                            ><a :href="service.url??'#'">
                                <!-- الصورة الأصلية -->
                                <img
                                    src="/images/layer_1.svg"
                                    :alt="$tt(service.name)"
                                    class="w-full h-auto rounded-xl object-contain transition-opacity duration-500 opacity-100"
                                />
                                <!-- الصورة عند hover -->
                                <img
                                    src="/images/layer_6.svg"
                                    :alt="$tt(service.name)"
                                    class="absolute top-0 left-0 w-full h-auto object-contain transition-opacity duration-500 opacity-0 hover:opacity-100"
                                /> </a>
                            </div></swiper-slide
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

    transition:
        all 1000ms ease,
        opacity 1000ms ease;
}
</style>
