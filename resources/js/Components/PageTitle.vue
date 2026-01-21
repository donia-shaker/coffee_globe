<script setup>
import { defineProps } from "vue";

// استقبال الـ prop
defineProps({
    title: String,
});
</script>

<template>
    <div class="relative container xl:pt-8">
        <div
            class="xl:min-h-fit h-[500px] xl:h-[520px] overflow-hidden rounded-b-3xl"
        >
            <div class="relative xl:hidden w-full">
                <div class="absolute w-full mt-6 xl:mt-0">
                    <img
                        src="/images/mask_4.svg"
                        alt=""
                        class="w-full h-auto object-contain rounded-3xl"
                        :style="{
                            transform:
                                $i18n.locale === 'en'
                                    ? 'rotateY(180deg)'
                                    : 'none',
                        }"
                    />
                </div>
            </div>
            <div class="relative hidden xl:block w-full h-auto">
                <div class="absolute w-full mt-6 xl:mt-0">
                    <img
                        src="/images/layout_4.svg"
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
            <div class="relative hidden xl:block w-full h-auto">
                <div class="absolute w-full mt-6 xl:mt-0">
                    <img
                        src="/images/about_bg.svg"
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
                class="relative z-10 flex flex-col lg:flex-row justify-center lg:justify-between items-center h-full"
            >
                <!-- النص -->

                <h1
                    class="text-4xl sm:text-6xl font-bold text-background my-6 px-10 rtl:xl:pr-24 ltr:xl:pl-24 max-w-[400px]"
                    style="line-height: 1.5"
                >
                    {{ title }}
                </h1>
            </div>
        </div>
    </div>
</template>
