<script setup>
import { computed } from "vue";

const props = defineProps({
    text: String,
    type: String,
    isNav: {
        type: Boolean,
        default: false,
    },
});

// تحديد الألوان بناءً على نوع الزر باستخدام computed
const color = computed(() =>
    props.type === "primary"
        ? "text-background"
        : props.type === "third"
          ? "text-secondary"
          : "text-primary",
);
const bg = computed(() =>
    props.type === "primary"
        ? "bg-primary"
        : props.type === "third"
          ? "bg-background"
          : "bg-background",
);

const border = computed(() =>
    props.type === "primary"
        ? "border-primary"
        : props.type === "third"
          ? "border-secondary"
          : "border-primary",
);
const hover = computed(() =>
    props.type === "primary"
        ? " before:bg-background  hover:text-primary"
        : props.type === "third"
          ? "before:bg-bg_green_two  hover:text-secondary"
          : "before:bg-primary  hover:text-background",
);

const navStyle = computed(() =>
    props.isNav === true
        ? " py-2 px-4 md:py-3 ltr:md:min-w-[88px] md:min-w-[98px] ltr:2xl:min-w-[120px] 2xl:min-w-[120px]"
        : "py-2 px-4 md:py-3  md:min-w-[120px] ",
);
</script>

<template>
    <button
        style="line-height: 1.5"
        :class="`relative overflow-hidden ${color} ${bg} whitespace-nowrap ${border} z-10 ${hover} ${navStyle} rounded-full font-bold border-2 border-main before:z-[-1] before:rounded-full before:-bottom-full before:transition-all before:duration-300 before:ease-in-out hover:before:w-full  before:absolute before:top-[50px] hover:before:top-0 before:-right-2 hover:before:right-0 before:w-0 before:h-full`"
    >
        <slot />
        {{ text }}
    </button>
</template>
