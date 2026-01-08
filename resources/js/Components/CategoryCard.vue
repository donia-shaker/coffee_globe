<script setup>
defineProps({
    active: Boolean,
    section: Object,
});
</script>

<template>
    <div
        class="p-2 mb-2 flex-shrink-0 min-w-[180px] bg-white transition-all duration-300 ease-in-out"
        :class="{
            'border-b-2 border-primary scale-105 ': active == 1,
            'border-b-2 border-gray-200': active == 0,
        }"
    >
        <div class="relative h-[60px] w-[60px] mx-auto w-full overflow-hidden">
            <img
                :src="section.media?.url??''"
                :alt="$tt(section.name)"
                class="w-full h-[60px] absolute object-contain mb-3"
            />
        </div>
        <div class="content text-center">
            <h3
                class="text-xs pt-1 text-gray-800"
                :class="active === '1' ? 'text-primary font-bold ' : ''"
            >
                {{ $tt(section.name) }}
            </h3>
        </div>
    </div>
</template>
