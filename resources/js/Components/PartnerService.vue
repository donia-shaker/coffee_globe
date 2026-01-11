<script setup lang="ts">
defineProps({
    service_companies: Object,
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
                    {{ $t("core_services_partners") }}
                </h2>
                <p
                    class="text-primary mx-auto max-w-[700px]"
                    style="line-height: 1.8"
                >
                    {{ $t("partners_support_desc") }}
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-16">
                <div
                    class="text-main bg-bg_green_two rounded-xl w-full p-6"
                    v-for="service_company in service_companies"
                >
                    <div class="text-center">
                        <h3 class="font-bold text-lg md:text-xl">
                            {{ $tt(service_company.name) }}
                        </h3>
                        <p
                            class="text-sm text-primary py-2 h-[80px] overflow-y-auto hide-scroll"
                            style="line-height: 2"
                        >
                            {{ $tt(service_company.text) }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
