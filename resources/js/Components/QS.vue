<template>
    <div class="container py-48">
        <div
            class="mx-auto md:min-h-[700px] md:p-10 rounded-3xlc:\Users\donia\Downloads\Group 1171276105.svg flex items-center justify-center relative bg-no-repeat bg-cover md:mx-20"
            style="background-image: url('/images/contact.svg')"
        >
            <div class="lg:w-[60%] w-full mx-auto bg-[#fdf6ef99] p-4 pb-10 pt-28 md:p-10 rounded-xl">
                <!-- Progress -->
                <div class="mb-6">
                    <div
                        class="h-2 w-full bg-[#50170c33] rounded-full overflow-hidden"
                    >
                        <div
                            class="h-full bg-primary transition-all duration-300"
                            :style="{ width: progress + '%' }"
                        ></div>
                    </div>
                    <p class="text-sm text-main mt-2 text-center">
                        سؤال {{ currentIndex + 1 }} / {{ questions.length }}
                    </p>
                </div>

                <!-- Question -->
                <h2 class="text-lg md:text-3xl text-center text-main font-bold mb-4">
                    {{ currentQuestion.question }}
                </h2>

                <!-- Options -->
                <div class="space-y-3">
                    <button
                        v-for="(option, index) in currentQuestion.options"
                        :key="index"
                        @click="selectedOption = index"
                        class="w-full p-3 rounded-full ring-1 ring-transparent text-center font-bold text-main transition"
                        :class="
                            selectedOption === index
                                ? 'bg-primary text-white'
                                : 'bg-[#fdf6ef99] hover:bg-[#fdf6ef99]'
                        "
                    >
                        {{ option }}
                    </button>
                </div>

                <!-- Next -->
                <div class="mt-6 text-right">
                    <button
                        @click="nextQuestion"
                        :disabled="selectedOption === null"
                        class="px-6 py-2 rounded-full w-full bg-[#2d432433] font-bold text-main disabled:opacity-50"
                    >
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed } from "vue";

const questions = ref([
    {
        question: "What is your favorite color?",
        options: ["Red", "Blue", "Green", "Black"],
    },
    {
        question: "Which device do you use?",
        options: ["Mobile", "Tablet", "Laptop", "Desktop"],
    },
    {
        question: "Preferred payment method?",
        options: ["Cash", "Card", "Apple Pay", "STC Pay"],
    },
]);

const currentIndex = ref(0);
const selectedOption = ref(null);

const currentQuestion = computed(() => {
    return questions.value[currentIndex.value];
});

const progress = computed(() => {
    return ((currentIndex.value + 1) / questions.value.length) * 100;
});

const nextQuestion = () => {
    if (currentIndex.value < questions.value.length - 1) {
        currentIndex.value++;
        selectedOption.value = null;
    } else {
        alert("Quiz finished 🎉");
    }
};
</script>
