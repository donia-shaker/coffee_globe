<script setup lang="ts">
import SectionTitle from "./SectionTitle.vue";

defineProps({
    features : Object
});

</script>

<template>
    <div>
        <div
            class="relative py-[60px] text-center bg-[#e7e4da] overflow-hidden"
        >
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
                <SectionTitle class="mb-10 mx-auto" :title="$t('features')" />
                <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                    <div
                        class="text-main md:text-xl text-sm font-bold bg-[#dbc9ae] rounded-xl w-full px-6 py-4"
                        v-for="feature in features"
                    >
                        <p class="m-0">{{$tt(feature.name) }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
