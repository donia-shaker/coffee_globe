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
    users: Object,
    role: String,
});

// استخدام الدالة العامة للترتيب
const { sortColumn, sortDirection } = useSortTable("users", {
    sort: "id",
    direction: "desc",
    search: "",
    perPage: 10,
});
</script>

<template>
    <DashboardLayout>
        <DataTableData
            :paginationData="users"
            tableName="users"
            :tableAction="`أضافة ${ $t(role) }`"
            :tableActionLink="route('user.create', role)"
            :tableLink="route('users.index', role)"
        >
            <template v-slot:header>
                <h4 class="fw-bold py-2 mb-3 fs-2">{{ $t(role) }}</h4>
            </template>

            <template v-slot:thead>
                <tr>
                    <th class="sortable" data-column="id"># ⬍</th>
                    <th class="sortable" data-column="name">الاسم ⬍</th>
                    <th class="sortable" data-column="email">الإيميل ⬍</th>
                    <th v-if="role == 'supplier'" class="sortable" data-column="supplier">المورد ⬍</th>
                    <th class="sortable" data-column="is_active">الحالة ⬍</th>
                    <th>الإجراءات</th>
                </tr>
            </template>

            <template v-slot:tbody>
                <tr v-for="user in users.data" :key="user.id">
                    <td>{{ user.id }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td v-if="role == 'supplier'">{{ user.supplier?.name.ar }}</td>
                    <td>
                        <TableStatus :active="user.is_active" />
                    </td>
                    <td>
                        <ActiveAction
                            v-if="user.id != 1"
                            :active="user.is_active"
                            @click="
                                openModal(
                                    route('user.active', user.id),
                                    'danger',
                                    user.id
                                )
                            "
                        />
                        <Link :href="route('user.edit', user.id)">
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
