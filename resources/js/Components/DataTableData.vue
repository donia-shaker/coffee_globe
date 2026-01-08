<script setup>
import { Link, router } from "@inertiajs/vue3";
import { ref } from "vue";
import Alert from "./Alert.vue";

const props = defineProps({
  tableName: String,
  tableAction: String,
  tableActionLink: String,
  paginationData: Object,
  columns: Array,
  rows: Array,
  tableLink: String,
  search: String,
});

const searchQuery = ref(props.search || ""); // تخزين قيمة البحث في `ref`
const perPage = ref(10); // متغير لتحديد عدد العناصر لكل صفحة

// تحديث البحث
const updateSearch = () => {
  router.get(
    `${props.tableLink}`, // استخدام `tableName` بدلاً من `search`
    { search: searchQuery.value },
    { preserveState: true, replace: true }
  );
};

// تحديث عدد العناصر في الصفحة
const updatePerPage = () => {
  router.get(
    `${props.tableLink}`, // استخدام `tableName` كمسار صحيح
    { per_page: perPage.value, search: searchQuery.value },
    { preserveState: true, replace: true }
  );
};
</script>

<template>
  <div class="container-xxl flex-grow-1 container-p-y">
    <Alert></Alert>

    <div class="my-3">
      <slot name="header"></slot>
    </div>

    <div class="card p-4">
      <div class="container mt-4">
        <div class="card-header p-0">
          <div class="add w-fit" v-if="props.tableAction">
            <Link :href="props.tableActionLink">
              <button class="create-new btn add" type="button">
                <span>
                  <i class="bx bx-plus me-sm-2"></i>
                  <span class="d-none d-sm-inline-block">{{ props.tableAction }}</span>
                </span>
              </button>
            </Link>
          </div>
          <div class="mt-4 d-flex justify-content-between">
            <div>
              <select class="w-fit border border-gray-300 rounded py-1" v-model="perPage" @change="updatePerPage">
                <option value="10">10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
              </select>
            </div>
            <div class="mb-3 w-25">
              <input v-model="searchQuery" @input="updateSearch" type="text" class="form-control border border-gray-300 rounded" placeholder="البحث..." />
            </div>
          </div>
        </div>

        <!-- 📊 الجدول -->
        <div class="table-responsive my-2 border-top border-bottom">
          <table class="table rounded table-hover">
            <thead class="table-light">
              <slot name="thead"></slot> <!-- Table Head Slot -->
            </thead>
            <tbody>
              <slot name="tbody"></slot> <!-- Table Body Slot -->
            </tbody>
          </table>
        </div>

        <!-- 📌 التصفح بين الصفحات -->
        <nav class="mt-3" v-if="props.paginationData">
          <ul class="pagination justify-content-center">
            <!-- Prev Button -->
            <li class="page-item" :class="{ disabled: !props.paginationData.prev_page_url }">
              <button v-if="props.paginationData.prev_page_url" @click="router.get(props.paginationData.prev_page_url)" class="page-link">
                Prev
              </button>
            </li>

            <!-- Page Numbers -->
            <li
              class="page-item"
              :class="{ active: props.paginationData.current_page === page }"
              v-for="page in Array(props.paginationData.last_page).fill().map((_, i) => i + 1)" 
              :key="page"
            >
              <button @click="router.get(`/${props.tableName}?page=${page}`)" class="page-link">{{ page }}</button>
            </li>

            <!-- Next Button -->
            <li class="page-item" :class="{ disabled: !props.paginationData.next_page_url }">
              <button v-if="props.paginationData.next_page_url" @click="router.get(props.paginationData.next_page_url)" class="page-link">
                Next
              </button>
            </li>
          </ul>
        </nav>
      </div>
    </div>
  </div>
</template>
