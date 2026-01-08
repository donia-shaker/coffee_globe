<template>
    <div
        class="container w-full lg:w-[35%] md:px-8 py-[60px] my-4 border border-secondary rounded"
    >
        <ContactCard
            v-for="contact_us_info in contact_us_infos"
            :name="$tt(contact_us_info.name)"
            :value="$tt(contact_us_info.value)"
            :icon="contact_us_info.icon"
        ></ContactCard>
    </div>
</template>

<script setup>
import ContactCard from "./ContactCard.vue";
defineProps({
    contact_us_infos: Object,
});
// لا حاجة لأي سكربت حالياً
</script>
