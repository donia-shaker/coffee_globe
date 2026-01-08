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
    main_centers: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("main_centers", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="main_centers"
            tableName="main_centers"
            :tableLink="route('main_centers.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2"> بيانات المركز الرئيسي</h4>
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
                    <th>العنوان </th>
                    <th>رقم الهاتف</th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr
                    v-for="main_center in main_centers.data"
                    :key="main_center.id"
                >
                    <td>{{ main_center.id }}</td>

                    <td v-for="lang in langs" :key="lang.code">
                        {{ main_center.name[lang.code] }}
                    </td>
                    <td>
                        {{ main_center.address['ar'] }}
                    </td>
                    <td>
                        {{ main_center.phone }}
                    </td>
                    <td>
                        <Link
                            :href="
                                route(
                                    'main_centers.edit',
                                    main_center.id
                                )
                            "
                        >
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
