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
    branches: Object,
    langs: Object,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("branches", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="branches"
            tableName="branches"
            tableAction="أضافة فرع"
            :tableActionLink="route('branches.create')"
            :tableLink="route('branches.index')"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">الفروع</h4>
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
                    <th class="sortable" data-column="address">العوان ⬍</th>
                    <th class="sortable" data-column="is_active">رقم االهاتف ⬍</th>
                    <th class="sortable" data-column="is_active">الحالة ⬍</th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr v-for="branch in branches.data" :key="branch.id">
                    <td>{{ branch.id }}</td>
                    <td v-for="lang in langs" :key="lang.code">
                        {{ branch.name[lang.code] }}
                    </td>
                    <td>{{ branch.address['ar'] }}</td>

                    <td>{{ branch.phone }}</td>

                    <td>
                        <TableStatus :active="branch.is_active" />
                    </td>
                    <td>
                        <ActiveAction
                            :active="branch.is_active"
                            @click="
                                openModal(
                                    route('branches.active', branch.id),
                                    'danger',
                                    branch.id
                                )
                            "
                        />
                        <Link :href="route('branches.edit', branch.id)">
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
