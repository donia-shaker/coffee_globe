<script setup>
import Button from "./Button.vue";
import FQsData from "./FQsData.vue";
</script>

<template>
    <section class="relative py-[80px] text-center overflow-hidden">
        <div class="container relative z-10 ">
            <h2
                class="text-3xl text-main sm:text-4xl font-bold mb-10"
                style="line-height: 1.5"
            >
                {{ $t("latest_news") }}
            </h2>
            <FQsData/>

            <a href="/#contact" class="flex justify-center mt-10">
                <Button type="primary" text="">
                    {{ $t("explore") }}
                    <i class="fas fa-arrow-left mx-2"></i>
                </Button>
            </a>
        </div>
    </section>
</template>
