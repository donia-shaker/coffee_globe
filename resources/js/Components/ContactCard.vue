<script setup>
defineProps({
    name: String,
    value: String,
    icon: String,
});
</script>
<template>
    <div class="rounded-2xl p-2 flex items-center gap-4">
        <div class="rounded-full bg-white h-[50px] w-[50px] flex justify-center items-center">
            <i :class="icon" class="text-primary text-2xl"></i>
        </div>
        <div>
            <h3 class="text-md py-2 text-white font-semibold">{{ name }}</h3>
            <p class="text-gray-400" dir="ltr">{{ value }}</p>
        </div>
    </div>
</template>
