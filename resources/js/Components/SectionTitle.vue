<script setup>
const props = defineProps({
    title: String,
});
</script>

<template>
    <div
        class="flex items-center gap-4 bg-[#2A1A04] text-white rounded-xl py-6 px-8 w-fit text-2xl font-bold"
    >
        <!-- دائرة -->
        <span class="w-4 h-4 rounded-full bg-[#D9B25F]"></span>

        <!-- العنوان -->
        <h2 class="p-0 m-0 font-bold text-background">{{ title }}</h2>

        <!-- دائرة -->
        <span class="w-4 h-4 rounded-full bg-[#D9B25F]"></span>
    </div>
</template>
