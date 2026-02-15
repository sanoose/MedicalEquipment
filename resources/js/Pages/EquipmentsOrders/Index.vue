<!-- resources/js/Pages/EquipmentsOrders/Index.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, router, useForm } from '@inertiajs/vue3'
import { ref, inject } from 'vue'
import { toast } from 'vue3-toastify'
import Pagination from '@/Components/Pagination.vue'
import * as constants from '@/JsFiles/constants.js'

// ✅ الكمبوننت المطلوب (مودال)
import EquipmentOrderDetails from '@/Components/Common/EquipmentOrderDetails.vue'

defineOptions({ layout: AppLayout })

const swal = inject('$swal')

const props = defineProps({
  orders: Object,
  filters: Object,
})

const form = useForm({
  search: props.filters.search || '',
  equipment_order_status: props.filters.equipment_order_status || '',
  per_page: props.filters.per_page || 10,
})

const perPageOptions = [5, 10, 15, 20, 25, 50, 100, 200, 1000]

const ApplyFilters = () => {
  form.get(route('EquipmentsOrders.index'), {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  })
}

/* ====== Dropdown ====== */
const open_index = ref(null)

/* ====== Modal ====== */
const is_show = ref(false)
const current_order = ref(null)

const OpenDetails = (o) => {
  current_order.value = o
  is_show.value = true
}
const CloseDetails = () => {
  is_show.value = false
  current_order.value = null
}

/* ====== Status helpers ====== */
const StatusLabel = (v) => constants.equipment_order_status?.[String(v)] || '-'
const StatusClass = (v) => {
  const s = String(v)
  if (s === '1') return 'bg-yellow-100 text-yellow-800'
  if (s === '2') return 'bg-green-100 text-green-800'
  if (s === '3') return 'bg-red-100 text-red-800'
  return 'bg-gray-100 text-gray-800'
}

/* ====== ChangeStatus (دالة واحدة) ====== */
const ChangeStatus = (o, equipment_order_status) => {
  const title = StatusLabel(equipment_order_status)

  swal.fire({
    title: `تغيير الحالة`,
    html: `
      سيتم تغيير حالة الطلب رقم
      <span class="text-blue-500 font-bold">#${o.id}</span><br>
      إلى <span class="font-bold">${title}</span><br>
      هل أنت متأكد؟
    `,
    icon: equipment_order_status == 3 ? 'warning' : 'info',
    showCancelButton: true,
    confirmButtonColor: equipment_order_status == 2 ? 'green' : equipment_order_status == 3 ? 'red' : 'yellowgreen',
    cancelButtonColor: 'gray',
    cancelButtonText: 'إلغاء الأمر',
    confirmButtonText: 'نعم',
    preConfirm: () => {
      router.visit(route('EquipmentsOrders.ChangeStatus', { id: o.id, equipment_order_status }), {
        method: 'get',
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => toast.success('تم تحديث الحالة'),
        onError: () => toast.error('حدث خطأ لم يتم تغيير الحالة'),
      })
    },
  })
}
</script>

<template>
  <Head title="طلبات المعدات" />

  <div class="mx-auto px-4 py-8 w-full" dir="rtl">
    <h1 class="text-3xl font-bold text-blue-800 mb-6 pb-3 border-b border-gray-200">
      عرض وإدارة طلبات المعدات
    </h1>

    <!-- ✅ مودال التفاصيل (نفس البيانات الموجودة في الصفحة) -->
    <EquipmentOrderDetails
      v-if="is_show"
      :order="current_order"
      :constants="constants"
      @cancel="CloseDetails"
    />

    <!-- Filters -->
    <form class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200" @submit.prevent="ApplyFilters">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
          <input
            v-model="form.search"
            type="text"
            placeholder="اسم المستشفى / الجهة أو اسم المسؤول..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
          <select
            v-model="form.equipment_order_status"
            class="w-full py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
          >
            <option value="">الكل</option>
            <option v-for="(label, value) in constants.equipment_order_status" :key="value" :value="value">
              {{ label }}
            </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">عدد السجلات</label>
          <select
            v-model="form.per_page"
            class="w-full py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
          >
            <option v-for="o in perPageOptions" :key="o" :value="o">{{ o }}</option>
          </select>
        </div>
      </div>

      <div class="flex space-x-3">
        <button
          class="bg-[#c5b26b] hover:bg-[#b3a15f] text-white font-medium py-2 px-6 rounded-lg transition duration-300 transform hover:-translate-y-0.5 hover:shadow-md"
        >
          بحث
        </button>
      </div>
    </form>

    <!-- Table -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 outer_container">
      <div class="overflow-x-auto table_container">
        <table class="divide-y divide-gray-200 w-full">
          <thead class="bg-[#32506c]">
            <tr class="text-right">
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">رقم الطلب</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">العميل</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">تاريخ الطلب</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">عدد الأصناف</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">إجمالي الكمية</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الحالة</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">العمليات</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(o, index) in orders.data" :key="o.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 text-sm text-gray-800 font-semibold">#{{ o.id }}</td>

              <td class="px-6 py-4 text-sm text-gray-700">
                <div class="font-medium">{{ o.client?.entity_name || '-' }}</div>
                <div class="text-xs text-gray-500">{{ o.client?.client_name || '-' }} -   {{ o.client?.phone || '-' }} </div>
              </td>

              <td class="px-6 py-4 text-sm text-gray-700">
                {{ o.order_date || '-' }}
              </td>

              <td class="px-6 py-4 text-sm text-gray-700">
                {{ o.details_count ?? (o.details?.length || 0) }}
              </td>

              <td class="px-6 py-4 text-sm text-gray-700">
                {{ o.total_amount ?? '-' }}
              </td>

              <td class="px-6 py-4">
                <span
                  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                  :class="StatusClass(o.equipment_order_status)"
                >
                  {{ StatusLabel(o.equipment_order_status) }}
                </span>
              </td>

              <!-- عمليات -->
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium relative">
                <div class="relative inline-block text-left pl-[70px]" >
                  <button @click="open_index = open_index === index ? null : index" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                    <i class="fas fa-ellipsis-v"></i> العمليات
                  </button>

                  <div
                    v-if="open_index === index"
                    class="absolute z-10 right-[-28px] mt-2 w-44 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <ul class="py-1 text-sm text-gray-700">
                      <li>
                        <button @click="OpenDetails(o)" class="w-full text-right px-4 py-2 hover:bg-gray-100">
                          <fa class="ml-1 text-blue-500" :icon="'eye'" /> عرض
                        </button>
                      </li>

                      <li>
                        <button v-if="o.equipment_order_status != 2" @click="ChangeStatus(o, 2)" class="w-full text-right px-4 py-2 hover:bg-gray-100">
                          <fa class="ml-1 text-green-500" :icon="'check'" /> قبول
                        </button>
                      </li>

                      <li>
                        <button v-if="o.equipment_order_status != 3" @click="ChangeStatus(o, 3)" class="w-full text-right px-4 py-2 hover:bg-gray-100">
                          <fa class="ml-1 text-red-500" :icon="'xmark'" /> رفض
                        </button>
                      </li>

                      <li>
                        <button v-if="o.equipment_order_status != 1" @click="ChangeStatus(o, 1)" class="w-full text-right px-4 py-2 hover:bg-gray-100">
                          <fa class="ml-1 text-yellow-500" :icon="'hand'" /> قيد الانتظار
                        </button>
                      </li>

                    </ul>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination
        :links="orders.links"
        :from="orders.from"
        :to="orders.to"
        :total="orders.total"
        :currentPage="orders.current_page"
        :lastPage="orders.last_page"
      />
    </div>
  </div>
</template>
