<script setup>
import Button from "./Button.vue";
import FQsData from "./FQsData.vue";

defineProps({
    fqs: Object,
});
</script>

<template>
    <section class="relative py-[80px] text-center overflow-hidden">
        <div class="container relative z-10 ">
            <h2
                class="text-3xl text-main sm:text-4xl font-bold mb-10"
                style="line-height: 1.5"
            >
                {{ $t("fqs") }}
            </h2>
            <FQsData :fqs="fqs"/>

            <a href="/fqs" class="flex justify-center mt-10">
                <Button type="primary" text="">
                    {{ $t("show_more") }}
                    <i class="fas fa-arrow-left mx-2  ltr:scale-x-[-1]"></i>
                </Button>
            </a>
        </div>
    </section>
</template>
