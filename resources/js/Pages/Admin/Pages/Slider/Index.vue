<script setup>
import { ref } from "vue";
import { useSortTable } from "@/Composables/useSortTable";
import DashboardLayout from "@/Layouts/DashboardLayout.vue";
import { Link } from "@inertiajs/vue3";
import DataTableData from "@/Components/DataTableData.vue";
import TableStatus from "@/Components/TableStatus.vue";
import ActiveAction from "@/Components/ActiveAction.vue";
import EditAction from "@/Components/EditAction.vue";
import { useModal } from "@/Composables/useModal";
import FormModel from "@/Components/FormModel.vue";

const { openModal } = useModal();

// استيراد البيانات عبر الـ props
const props = defineProps({
    sliders: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("sliders", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="sliders"
            tableName="sliders"
            tableAction="أضافة  سلايد"
            :tableActionLink="route('sliders.create')"
            :tableLink="route('sliders.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">السلايدر </h4>
            </template>

            <template v-slot:thead>
                <tr>
                    <th class="sortable" data-column="id"># ⬍</th>
                    <th data-column="id">الصورة</th>
                    
                    <th class="sortable" data-column="is_active">الحالة ⬍</th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr v-for="slider in sliders.data" :key="slider.id">
                    <td>{{ slider.id }}</td>
                    <td>
                        <img
                            v-if="slider.media"
                            class="img-fluid rounded"
                            :src="slider.media.thumb_url"
                            height="60"
                            width="60"
                        />
                        <p v-else>لايوجد صورة</p>
                    </td>
                    <td>
                        <TableStatus :active="slider.is_active" />
                    </td>
                    <td>
                        <ActiveAction :active="slider.is_active" @click="openModal(route('sliders.active', slider.id), 'danger', slider.id)"/>
                        <Link :href="route('sliders.edit', slider.id)">
                            <EditAction />
                        </Link>
                    </td>
                </tr>
            </template>
        </DataTableData>
        
    </DashboardLayout>

</template>

<style scoped>
.sortable {
    cursor: pointer;
    user-select: none;
}
.sortable:hover {
    background-color: #f8f9fa;
}
</style>
