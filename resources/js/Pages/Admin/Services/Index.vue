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
    services: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("services", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="services"
            tableName="services"
            tableAction="أضافة  خدمة"
            :tableActionLink="route('services.create')"
            :tableLink="route('services.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">الخدمات </h4>
            </template>

            <template v-slot:thead>
                <tr>
                    <th class="sortable" data-column="id"># ⬍</th>
                    <th data-column="id">الصورة</th>
                    <th
                        v-for="lang in langs"
                        :key="lang.code"
                        class="sortable"
                        :data-column="`name_${lang.code}`"
                    >
                        {{ `الاسم ${lang.code}` }} ⬍
                    </th>
                    <th class="sortable" data-column="is_active">الحالة ⬍</th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr v-for="service in services.data" :key="service.id">
                    <td>{{ service.id }}</td>
                    <td>
                        <img
                            v-if="service.media"
                            class="img-fluid rounded"
                            :src="service.media.thumb_url"
                            height="60"
                            width="60"
                        />
                        <p v-else>لايوجد صورة</p>
                    </td>
                    <td>{{ service.name['ar'] }}</td>

                    <td>{{ service.name['en'] }}</td>

                    <td>
                        <TableStatus :active="service.is_active" />
                    </td>
                    <td>
                        <ActiveAction :active="service.is_active" @click="openModal(route('services.active', service.id), 'danger', service.id)"/>
                        <Link :href="route('services.edit', service.id)">
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
