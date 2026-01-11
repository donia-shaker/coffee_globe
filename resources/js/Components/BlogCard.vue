<script setup>
import { Link } from "@inertiajs/vue3";

defineProps({
    blog: Object,
});
</script>
<template>
    <div class="relative border border-primary rounded-3xl">
        <div class="p-4 pb-2">
            <img
                :src="blog.media?.url ?? '/images/service.png'"
                :alt="$tt(blog.name)"
                class="w-full h-auto rounded-2xl object-contain"
                :style="{
                    transform:
                        $i18n.locale === 'en' ? 'rotateY(180deg)' : 'none',
                }"
            />
        </div>
        <div class="px-4 text-start mb-10">
            <h3
                class="text-3xl text-main sm:text-xl font-bold mb-3 mt-1"
                style="line-height: 1.5"
            >
                {{ $tt(blog.name) }}
            </h3>
            <p class="text-main text-sm text-secondary leading-[1.7]">
                {{$tt(blog.text).slice(0, 120) + "..." }}
            </p>
            <Link :href="'blog/'+blog.id">
                <div class="text-primary flex items-end mt-2 font-bold">
                    {{ $t("read_more") }}
                    <i class="fas fa-arrow-left mx-2"></i>
                </div>
            </Link>
        </div>
    </div>
</template>
