<script setup>
import { computed } from "vue";

const props = defineProps({
    text: String,
    type: String,
});

// تحديد الألوان بناءً على نوع الزر باستخدام computed
const color = computed(() =>
    props.type === "primary" ? "text-background" : (props.type === "third" ? "text-secondary":"text-primary")
);
const bg = computed(() =>
    props.type === "primary" ? "bg-primary" : (props.type === "third" ? "bg-background":"bg-background")
);

const border = computed(() =>
    props.type === "primary" ? "border-primary" : (props.type === "third" ? "border-secondary":"border-primary")
);
const hover = computed(() =>
    props.type === "primary"
        ? " before:bg-background  hover:text-primary"
        : (props.type === "third" ? "before:bg-bg_green_two  hover:text-secondary":"before:bg-primary  hover:text-background") 
);
</script>

<template>
    <button
        :class="`relative overflow-hidden ${color} ${bg} whitespace-nowrap ${border} z-10 py-2 px-4 md:py-3  md:min-w-[120px] ${hover} rounded-full font-bold border-2 border-main before:z-[-1] before:rounded-full before:-bottom-full before:transition-all before:duration-300 before:ease-in-out hover:before:w-full  before:absolute before:top-[50px] hover:before:top-0 before:-right-2 hover:before:right-0 before:w-0 before:h-full`"
    >
        <slot />
        {{ text }}
    </button>
</template>
