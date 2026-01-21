<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    blog: Object,
});
</script>
<template>
    <div class="relative border group border-primary rounded-3xl">
        <div class="p-4 pb-2">
            <div class="imag rounded-[20px] overflow-hidden relative h-[300px]">
                <img
                    :src="blog.media?.url ?? '/images/service.png'"
                    :alt="$tt(blog.name)"
                    class="absolute inset-0 w-full h-full object-cover transition-transform duration-300 group-hover:scale-110"
                />
                <div
                    class="absolute inset-0 bg-gray_normal bg-opacity-50 scale-x-0 rtl:origin-right ltr:origin-left transition-transform duration-500 group-hover:scale-x-100"
                ></div>
            </div>
        </div>
        <div class="px-4 text-start mb-10">
            <h3
                class="text-xl text-main sm:text-xl font-bold mb-3 mt-1"
                style="line-height: 1.5"
            >
                {{ $tt(blog.name) }}
            </h3>
            <p class="text-main text-sm text-secondary leading-[1.7]">
                {{ $tt(blog.text).slice(0, 120) + "..." }}
            </p>
            <Link :href="'blog/' + blog.id">
                <div
                    class="text-primary flex items-end mt-2 text-sm xl:text-md font-bold"
                >
                    {{ $t("read_more") }}
                    <i class="fas fa-arrow-left mx-2 ltr:scale-x-[-1]"></i>
                </div>
            </Link>
        </div>
    </div>
</template>
