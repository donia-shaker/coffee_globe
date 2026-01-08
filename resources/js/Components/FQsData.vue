<script setup>
import { ref } from "vue";

const faqs = ref([
    {
        question: "كيف يمكنني إنشاء حساب؟",
        answer: "يمكنك إنشاء حساب بسهولة من خلال صفحة التسجيل وإدخال بياناتك.",
        open: true,
    },
    {
        question: "هل الخدمة مجانية؟",
        answer: "نعم، يوجد خطة مجانية بالإضافة إلى خطط مدفوعة بمميزات إضافية.",
        open: false,
    },
    {
        question: "كيف أتواصل مع الدعم الفني؟",
        answer: "يمكنك التواصل معنا عبر صفحة اتصل بنا أو البريد الإلكتروني.",
        open: false,
    },
]);

const toggle = (index) => {
    faqs.value.forEach((faq, i) => {
        faq.open = i === index ? !faq.open : false; // واحد فقط مفتوح
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
                <span class="text-main font-bold">{{ faq.question }}</span>

                <img
                    src="/images/polygon.svg"
                    alt=""
                    :class="[
                        'w-5 h-5 transform transition-transform duration-300 text-gray-700',
                        faq.open ? '' : 'rotate-180',
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
                    {{ faq.answer }}
                </div>
            </transition>
        </div>
    </div>
</template>
