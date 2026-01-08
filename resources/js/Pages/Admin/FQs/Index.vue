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
    fqs: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("fqs", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="fqs"
            tableName="fqs"
            tableAction="أضافة الأسئلة الشائعة"
            :tableActionLink="route('fqs.create')"
            :tableLink="route('fqs.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2"> الأسئلة الشائعة </h4>
            </template>

            <template v-slot:thead>
                <tr>
                    <th class="sortable" data-column="id"># ⬍</th>
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
                <tr v-for="fq in fqs.data" :key="fq.id">
                    <td>{{ fq.id }}</td>
                    <td>{{ fq.name['ar'] }}</td>

                    <td>{{ fq.name['en'] }}</td>

                    <td>
                        <TableStatus :active="fq.is_active" />
                    </td>
                    <td>
                        <ActiveAction :active="fq.is_active" @click="openModal(route('fqs.active', fq.id), 'danger', fq.id)"/>
                        <Link :href="route('fqs.edit', fq.id)">
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
