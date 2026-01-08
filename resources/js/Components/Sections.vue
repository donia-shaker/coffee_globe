<script setup>
import { Link } from "@inertiajs/vue3";
import CategoryCard from "./CategoryCard.vue";

const props = defineProps({
    sections: Object, // نستخدم Array لأننا هنجلبها من الـ API
});
</script>
<template>
    <div class="container py-10">
        <h2 class="text-2xl pb-8 font-bold"> {{ $t('categories_page_text') }}</h2>

        <div
            class="grid gap-4 mb-4"
            style="grid-template-columns: repeat(auto-fill, minmax(320px, 1fr))"
        >
            <Link
                v-for="(section, i) in sections"
                :key="i"
                :href="`/section/${section.id}`"
                class="cursor-pointer border border-gray-300 rounded-xl w-full  overflow-hidden"
            >
                    <div
                        class="relative h-[140px] w-[60px] mx-auto w-full"
                    >
                        <img
                            :src="section.media?.url??''"
                            :alt="$tt(section.name)"
                            class="w-full h-[60px] absolute object-contain mb-3"
                        />
                    </div>
                    <div class="content text-center">
                        <h3
                            class="text-xs pt-1 text-gray-800"
                            :class="
                                active === '1' ? 'text-primary font-bold ' : ''
                            "
                        >
                            {{ $tt(section.name) }}
                        </h3>
                </div>
            </Link>
        </div>
    </div>
</template>
