<script setup lang="ts">
import { ref } from "vue";
import { usePage } from "@inertiajs/vue3";
import ProductCard from "./ProductCard.vue";
import Products from "./Products.vue";
const props = defineProps({
    products: Object,
});
</script>

<template>
    <div class="container mx-auto py-6">
        <div
            class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 pb-10 gap-4"
        >
            <ProductCard
                v-for="product in products"
                :key="product.id"
                :product="product"
            />
        </div>
    </div>
</template>
