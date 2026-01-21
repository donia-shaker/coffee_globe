<script setup>
import { ref, watch } from "vue";

const props = defineProps({
    fqs: {
        type: Array,
        required: true,
    },
});

// state محلي قابل للتعديل
const faqs = ref([]);

// نسخ البيانات من props وإضافة open
watch(
    () => props.fqs,
    (newFaqs) => {
        faqs.value = newFaqs.map((faq, index) => ({
            ...faq,
            open: index === 0, // أول عنصر مفتوح (يمكن تغييره)
        }));
    },
    { immediate: true }
);

const toggle = (index) => {
    faqs.value.forEach((faq, i) => {
        faq.open = i === index ? !faq.open : false;
    });
};
</script>

<template>
    <div class="container xl:w-[70%]">
        <div
            v-for="(faq, index) in faqs"
            :key="index"
            class="border border-background_two my-4 rounded-2xl overflow-hidden"
        >
            <button
                @click="toggle(index)"
                class="w-full flex justify-between items-center p-5 text-right font-medium bg-background_two transition"
            >
                <span class="text-main font-bold">{{ $tt(faq.name) }}</span>

                <img
                    src="/images/polygon.svg"
                    alt=""
                    :class="[
                        'w-5 h-5 transform transition-transform duration-300 text-gray-700',
                        faq.open ? '' : 'rotate-[60deg]',
                    ]"
                />
            </button>

            <transition
                enter-active-class="transition-all duration-300 ease-out"
                enter-from-class="max-h-0 opacity-0"
                enter-to-class="max-h-40 opacity-100"
                leave-active-class="transition-all duration-200 ease-in"
                leave-from-class="max-h-40 opacity-100"
                leave-to-class="max-h-0 opacity-0"
            >
                <div
                    v-if="faq.open"
                    class="px-5 py-5 text-main text-start leading-relaxed"
                >
                    {{ $tt(faq.text) }}
                </div>
            </transition>
        </div>
    </div>
</template>
