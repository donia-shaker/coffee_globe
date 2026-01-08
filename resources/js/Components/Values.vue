<script setup lang="ts">
import SectionTitle from "./SectionTitle.vue";

defineProps({
    features: Object,
});
</script>

<template>
    <div>
        <div class="relative py-[60px] text-center bg-main overflow-hidden">
            <!-- Left Background Image -->
            <div
                class="absolute left-0 top-0 h-full w-[300px] bg-no-repeat bg-left bg-cover"
                style="background-image: url('/images/features_1.svg')"
            ></div>

            <!-- Right Background Image -->
            <div
                class="absolute right-0 top-0 h-full w-[300px] bg-no-repeat bg-right bg-cover"
                style="background-image: url('/images/features_2.svg')"
            ></div>

            <div class="container relative z-10">
                <h2
                    class="text-3xl text-background sm:text-4xl font-bold mb-10"
                    style="line-height: 1.5"
                >
                    {{ $t("latest_news") }}
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-3 xl:grid-cols-4 gap-10">
                    <div
                        class="text-main  bg-background_two flex items-center gap-4 rounded-xl w-full p-2"
                        v-for="feature in 4"
                    >
                    <div class="h-[120px] w-[160px] bg-background rounded-xl flex justify-center items-center">
                        <img src="/images/icon_1.svg" alt="">
                    </div>
                        <div class="text-start">
                            <h3 class="font-bold text-lg md:text-xl">الجودة</h3>
                            <p class="text-sm" style="line-height: 1.5;">التزام صارم بأعلى معايير الجودة في كل مرحلة</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
