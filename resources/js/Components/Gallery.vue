<script setup>
defineProps({});
</script>

<template>
  <div class="card relative text-white flex flex-col justify-end h-[350px] overflow-hidden group">
    <img
      :src="'/images/about.png'"
      class="w-full h-full object-cover transition-transform duration-500 ease-in-out group-hover:scale-105"
      alt="About"
    />
    <!-- Overlay -->
    <div
      class="absolute inset-0 bg-white/40 transition-opacity duration-500 ease-in-out group-hover:opacity-0"
    ></div>
  </div>
</template>
