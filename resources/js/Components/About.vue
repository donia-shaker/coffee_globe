<script setup>
import SectionTitle from "./SectionTitle.vue";

defineProps({
    about_page_data: Object,
});
</script>
<template>
    <div class="py-[80px] bg-fifth">
        <div
            class="container flex flex-wrap text-center md:text-start relative justify-between"
        >
            <div class="w-full md:w-[50%] order-2 md:order-1 md:px-10">
                <h2
                    class="text-3xl text-main sm:text-4xl font-bold mb-10"
                    style="line-height: 1.5"
                >
                    {{ $t("latest_news") }}
                </h2>

                <div class="text text-primary xl:text-lg mt-4 mb-10">
                    <p class="text">
                        {{ $tt(about_page_data[0]["text"]) }}
                    </p>
                </div>
            </div>
            <div
                class="w-full md:w-[50%] flex items-center order-1 md:order-2 mb-14 md:mb-0 md:px-10 h-full"
            >
                <img
                    src="/images/about.svg"
                    class="w-full h-full object-contain"
                    :alt="$tt(about_page_data[0]['name'])"
                />
            </div>
        </div>
    </div>
</template>

<style scoped>
.text {
    line-height: 2.2;
}
.column_card {
    width: calc(100% / 2 - 200px);
}
</style>
