<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "./Button.vue";
</script>

<template>
    <div class="">
    <div
        class="bg-main relative bg-cover bg-center bg-no-repeat"
        style="background-image: url('/images/mask_2.png')"
    >
            <!-- الصورة في الشاشات الصغيرة (تبدأ من 0) -->
            <div
                class="absolute -top-32 w-[80%]  h-[80%] sm:w-[70%] sm:h-[70%]  left-1/2 -translate-x-1/2 translate-y-0 md:-top-20 md:w-[50%] md:h-[60%] bg-cover bg-center bg-no-repeat lg:hidden"
                style="background-image: url('/images/CTA1.svg')"
            ></div>

            <!-- مساحة لترك النص بعد الصورة -->
            <div class="pt-64 lg:pt-[80px] px-6 lg:px-20 py-[80px]">
                <!-- الصورة في الشاشات الكبيرة -->
                <div
                    class="hidden lg:block absolute -top-10 h-[150%] w-1/2 bg-no-repeat bg-cover rtl:left-0 ltr:right-0"
                    :style="{
                        backgroundImage: `url('/images/CTA1.svg')`,
                        backgroundPosition: 'center',
                        transform:
                            $i18n.locale === 'en' ? 'rotateY(180deg)' : 'none',
                    }"
                ></div>

                <div
                    class="max-w-[750px] relative z-10 text-center mx-auto lg:text-start lg:mx-0"
                >
                    <h4 class="text-3xl lg:text-5xl text-white font-bold">
                        {{ $t("cta_title") }}
                    </h4>

                    <p class="text-lg lg:text-xl text-white leading-10">
                        {{ $t("cta_text") }}
                    </p>

                    <a href="/#contact" class=" flex justify-center">
                        <Button type="secondary" text="">
                            {{ $t("explore") }}
                            <i class="fas fa-arrow-left mx-2"></i>
                        </Button>
                        </a
                    >
                </div>
            </div>
        </div>
    </div>
</template>
