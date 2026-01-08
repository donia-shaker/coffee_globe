<script setup>
defineProps({
    main_center: Object,
});
</script>
<template>
    <div class="">
        <h4 class="text-2xl font-bold"> {{ $t('main_center') }} : {{ $tt(main_center.name) }}</h4>
        <div
            class="grid grid-cols-2 md:grid-cols-4 text-xs md:text-lg lg:grid-cols-5 pt-4 pb-10 gap-4"
        >
            <div class="flex  items-center gap-2 md:gap-4">
                <div
                    class="px-2 py-1 bg-icon_background rounded-lg h-[32px] w-[32px] flex justify-center items-center"
                >
                    <i class="fas fa-map-marker-alt text-dark_text"></i>
                    <!-- Outline version -->
                </div>
                <p class="m-0">{{ $tt(main_center.address) }}</p>
            </div>
            <div class="flex items-center gap-2 md:gap-4">
                <div
                    class="px-2 py-1 bg-icon_background rounded-lg h-[32px] w-[32px] flex justify-center items-center"
                >
                    <i class="fas fa-phone text-dark_text"></i>
                    <!-- Outline version -->
                </div>
                <p class="m-0">{{ main_center.phone }}</p>
            </div>
            <div class="flex items-center gap-2 md:gap-4">
                <div
                    class="px-2 py-1 bg-icon_background rounded-lg h-[32px] w-[32px] flex justify-center items-center"
                >
                    <i class="fas fa-envelope text-dark_text"></i>
                    <!-- Outline version -->
                </div>
                <p class="m-0">{{ main_center.email }}</p>
            </div>
            <div class="flex items-center gap-2 md:gap-4">
                <div
                    class="px-2 py-1 bg-icon_background rounded-lg h-[32px] w-[32px] flex justify-center items-center"
                >
                    <i class="fas fa-globe text-dark_text"></i>
                    <!-- Outline version -->
                </div>
                <p class="m-0">{{ main_center.website }}</p>
            </div>
            <div class="flex items-center gap-2 md:gap-4">
                <div
                    class="px-2 py-1 bg-icon_background rounded-lg h-[32px] w-[32px] flex justify-center items-center"
                >
                    <i class="fas fa-map-marker-alt text-dark_text"></i>
                    <!-- Outline version -->
                </div>
                <p class="m-0">{{ main_center.support }}</p>
            </div>
        </div>
    </div>
</template>
