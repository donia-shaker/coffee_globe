<script setup lang="ts">
defineProps({
    experts: Object,
});
</script>
<template>
    <section class="relative bg-white py-[80px] text-center overflow-hidden">
        <div class="container relative z-10">
            <div class="mb-10 text-center w-full">
                <h2
                    class="text-3xl text-main sm:text-4xl font-bold"
                    style="line-height: 1.5"
                >
                    {{ $t("expert_team") }}
                </h2>
                <p
                    class="text-primary mx-auto max-w-[700px]"
                    style="line-height: 1.8"
                >
                    {{ $t("expert_team_desc") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <div
                    class="text-main bg-background rounded-3xl   p-6 w-full"
                    v-for="expert in experts"
                >
                    <div class="w-full h-[220px] overflow-hidden rounded-3xl">
                        <img
                            :src="expert.media?.url ?? '/images/service.png'"
                            alt=""
                            class="w-full object-contain"
                            :style="{
                                transform:
                                    $i18n.locale === 'en'
                                        ? 'rotateY(180deg)'
                                        : 'none',
                            }"
                        />
                    </div>
                    <div class="text-center mt-6">
                        <h3 class="font-bold text-lg md:text-xl">
                            {{ $tt(expert.name) }}
                        </h3>
                        <p
                            class="text-sm text-primary py-2 h-[150px] overflow-y-auto hide-scroll"
                            style="line-height: 2"
                        >
                            {{ $tt(expert.text) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
