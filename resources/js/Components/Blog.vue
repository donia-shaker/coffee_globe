<script setup>
import { Link } from "@inertiajs/vue3";
import Button from "./Button.vue";

defineProps({
    blog: Object,
});

// دالة لتحويل التاريخ
const formatDate = (dateString, locale = "ar") => {
    const date = new Date(dateString);
    return new Intl.DateTimeFormat(locale, {
        year: "numeric",
        month: "long",
        day: "numeric",
    }).format(date);
};
</script>
<template>
    <div class="relative bg-bg_light dark:bg-bg_dark">
        <div class="container py-[80px] pt-[140px]">
            <h3
                class="text-2xl dark:text-white font-bold max-w-[900px]"
                style="line-height: 1.7"
            >
                {{ $tt(blog.title) }}
            </h3>
            <div class="date flex items-center dark:text-white font-bold gap-2 text-sm pt-4 pb-2">
                <i class="fa-regular fa-calendar"></i>
                <!-- بالعربي -->
                <p class="ltr:hidden">
                    {{ formatDate(blog.created_at, "ar") }}
                </p>

                <!-- بالإنجليزي -->
                <p class="rtl:hidden">
                    {{ formatDate(blog.created_at, "en-US") }}
                </p>
            </div>
            <div
                class="w-full h-[200px] md:h-[300px] lg:h-[600px] relative overflow-hidden mt-10 rounded-40"
            >
                <img
                    :src="blog.media?.url ?? '/images/new.png'"
                    alt="صالة عرض"
                    class="absolute inset-0 w-full h-full object-cover object-center"
                />
            </div>
            <div class="content mt-10">
                <p class="ltr:text-justify rtl:text-justify text-md md:text-lg dark:text-white" v-html="$tt(blog.description)"></p>
            </div>
        </div>
    </div>
</template>
