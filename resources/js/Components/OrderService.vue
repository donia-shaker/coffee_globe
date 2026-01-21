<script setup lang="ts">
import Button from "./Button.vue";
</script>

<template>
    <section
        class="relative bg-bg_green py-[60px] text-background text-center overflow-hidden"
    >
        <div class="container relative z-10">
            <div class="mb-10 text-center w-full">
                <h2
                    class="text-3xl sm:text-4xl md:text-6xl font-bold mb-4"
                    style="line-height: 1.5"
                >
                    {{ $t("request_quote_or_sample") }}
                </h2>
                <p
                    class="mx-auto max-w-[700px] md:text-xl"
                    style="line-height: 1.8"
                >
                    {{ $t("contact_for_green_coffee") }}
                </p>
            </div>
            <div
                class="flex flex-wrap flex-column md:flex-row gap-4 justify-center"
            >
                <Button type="third" text="" class="w-[200px]">
                    {{ $t("start_partnership_now") }}
                    <i class="fas fa-arrow-left mx-2  ltr:scale-x-[-1]"></i>
                </Button>
            </div>
        </div>
    </section>
</template>
