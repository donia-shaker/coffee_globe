<script setup lang="ts">
defineProps({ order: Number, service: Object });
</script>

<template>
    <div class="mb-20">
        <h4 class="text-xl my-10 font-bold text-main">
            {{ $tt(service.name) }}
        </h4>

        <div
            :class="[
                'flex flex-col md:flex-row gap-4 md:gap-10 h-auto md:h-[600px]',
                order % 2 === 0 ? 'md:flex-row' : 'md:flex-row-reverse',
            ]"
        >
            <!-- الجانب الكبير -->
            <div class="flex-1 group relative w-full  rounded-xl h-[300px] md:h-[100%] overflow-hidden mb-4 md:mb-0">
                <img
                    :src="service.large_image?.url ?? '/images/serv_1.png'"
                    :alt="$tt(service.name)"
                    class="w-full h-full shadow-xl object-cover transition-transform duration-500 ease-in-out group-hover:scale-110"
                />
            </div>

            <!-- الجانب الصغير -->
            <div class="flex flex-col gap-4 md:gap-10 flex-1">
                <div class=" group relative w-full  rounded-xl h-[150px] md:h-1/2 overflow-hidden  gap-4 md:gap-10 flex-1">
                <img
                    :src="service.first_image?.url ?? '/images/serv_2.png'"
                    :alt="$tt(service.name)"
                    class="w-full h-full shadow-xl object-cover rounded-xl  transition-transform duration-500 ease-in-out group-hover:scale-110"
                /></div>
                <div class=" group relative w-full  rounded-xl h-[150px] md:h-1/2 overflow-hidden  gap-4 md:gap-10 flex-1">
                <img
                    :src="service.first_image?.url ?? '/images/serv_2.png'"
                    :alt="$tt(service.name)"
                    class="w-full h-full shadow-xl object-cover rounded-xl  transition-transform duration-500 ease-in-out group-hover:scale-110"
                /></div>
            </div>
        </div>
    </div>
</template>
