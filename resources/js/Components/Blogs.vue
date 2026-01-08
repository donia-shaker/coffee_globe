<script setup lang="ts">
import BlogCard from "./BlogCard.vue";
</script>

<template>
    <section class="py-[80px]">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <BlogCard v-for="value in 6" />
            </div>
        </div>
    </section>
</template>
