<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import Button from "./Button.vue";
</script>

<template>
    <div
        class="bg-main relative bg-cover bg-center bg-no-repeat overflow-hidden"
        style="background-image: url('/images/mask_2.png')"
    >
        <div
            class="hidden md:block absolute -top-80 h-[200%] w-1/2 bg-no-repeat bg-cover rtl:left-0 ltr:right-0"
            :style="{
                backgroundImage: `url('/images/CTA.svg')`,
                backgroundPosition: 'center',
                transform: $i18n.locale === 'en' ? 'rotateY(180deg)' : 'none',
            }"
        ></div>
        <div class="container rounded-xl relative overflow-hidden">
            <!-- مساحة لترك النص بعد الصورة -->
            <div class="pt-[80px] md:px-6 md:px-20 py-[60px]">
                <!-- الصورة في الشاشات الكبيرة -->
                <!-- الصورة في الشاشات الكبيرة -->

                <div
                    class="max-w-[750px] text-white relative z-10 text-center mx-auto md:text-start md:mx-0"
                >
                    <h4
                        class="text-3xl md:text-6xl  font-bold"
                        style="line-height: 1.5"
                    >
                        {{ $t("cta_title") }}
                    </h4>

                    <p class="text-lg md:text-2xl  mb-10 leading-10">
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
