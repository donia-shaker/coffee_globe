<script setup>
import { ref } from "vue";

const props = defineProps({
    product: Object,
});

// الحالة النشطة لكل خيار
const selectedRoasting = ref(null);
const selectedGrinding = ref(null);

// دوال للتحديد عند الضغط
const selectRoasting = (value) => (selectedRoasting.value = value);
const selectGrinding = (value) => (selectedGrinding.value = value);

const roastingLevels = [20, 40, 60, 80, 100];
const grindingLevels = [20, 40, 60, 80, 100];
</script>

<template>
    <div class="relative bg-dark_background rounded-xl overflow-hidden">
        <!-- الصورة -->
        <div
            class="w-full h-[240px] relative group overflow-hidden"
        >
            <img
                :src="product.media?.url ?? '/images/product.png'"
                class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-110"
                alt=""
            />
        </div>

        <!-- الاسم والوصف -->
        <div class="p-4 border-b border-sixth">
            <h4 class="text-main font-bold text-md md:text-xl">
                {{ $tt(product.name) }}
            </h4>
            <p class="text-sm text-main_text">{{ $tt(product.description) }}</p>
        </div>

        <!-- درجات التحميص والطحن -->
        <div class="p-4 gap-4 text-main_text">
            <!-- التحميص -->
            <div class="flex items-center mb-2">
                <h5 class="text-main_text text-sm w-[100px]">
                    {{ $t("roasting_level") }}
                </h5>
                <span class="mx-1">:</span>
                <div class="flex text-sm">
                    <span
                        v-for="level in roastingLevels"
                        :key="'r' + level"
                        @click="selectRoasting(level)"
                        :class="[
                            'cursor-pointer px-2 py-1 ',
                            selectedRoasting === level
                                ? 'bg-white text-text_main font-bold rounded-xl border'
                                : 'bg-dark_background text-main_text',
                        ]"
                    >
                        {{ level }}
                    </span>
                </div>
            </div>

            <!-- الطحن -->
            <div class="flex items-center">
                <h5 class="text-main_text text-sm w-[90px]">
                    {{ $t("grinding_level") }}
                </h5>
                <span class="mx-1">:</span>
                <div class="flex text-sm">
                    <span
                        v-for="level in grindingLevels"
                        :key="'g' + level"
                        @click="selectGrinding(level)"
                        :class="[
                            'cursor-pointer px-2 py-1',
                            selectedGrinding === level
                                ? 'bg-white text-text_main font-bold   rounded-xl border'
                                : 'bg-dark_background text-main_text',
                        ]"
                    >
                        {{ level }}
                    </span>
                </div>
            </div>
        </div>

        <!-- الحجم -->
        <span
            class="bg-dark_background text-main_text text-sm rounded-2xl absolute top-2 rtl:right-1 ltr:left-1 text-center font-bold w-[80px] z-10 px-4 py-1"
        >
            {{ product.size }}
        </span>
    </div>
</template>
