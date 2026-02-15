<!-- resources/js/Pages/EquipmentsMaintenances/Index.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3'
import { ref, inject } from 'vue'
import * as constants from '@/JsFiles/constants.js'
import Pagination from '@/Components/Pagination.vue'
import { toast } from 'vue3-toastify'
import { ElSelect, ElOption } from 'element-plus'

defineOptions({ layout: AppLayout })

const swal = inject('$swal')
const page = usePage().props.auth

const props = defineProps(['maintenances', 'filters'])

const open_index = ref(null)

const form = useForm({
  search: props.filters?.search || '',
  maintenance_status: props.filters?.maintenance_status || '',
  perPage: props.filters?.perPage || 10,
})

const perPageOptions = [5, 10, 15, 20, 25, 50, 100, 200, 1000]

const ApplyFilters = () => {
  form.get(route('EquipmentsMaintenances.index'), {
    preserveState: true,
    preserveScroll: true,
    replace: true,
  })
}

const StatusLabel = (v) => constants.maintenance_status?.[String(v)] || '-'

const StatusClass = (v) => {
  const s = String(v);
  if (s === '1') return 'bg-yellow-100 text-yellow-800';
  if (s === '2') return 'bg-green-100 text-green-800';
  if (s === '3') return 'bg-red-100 text-red-800';
  return 'bg-gray-100 text-gray-800';
};

const ChangeStatus = (m, maintenance_status) => {
  const title = constants.maintenance_status[String(maintenance_status)] || '—'

  swal.fire({
    title: `تغيير الحالة`,
    html: `
      سيتم تغيير حالة الطلب رقم
      <span class="text-blue-500 font-bold">#${m.id}</span><br>
      إلى <span class="font-bold">${title}</span><br>
      هل أنت متأكد؟
    `,
    icon: maintenance_status == 3 ? 'warning' : 'info',
    showCancelButton: true,
    confirmButtonColor: maintenance_status == 2 ? 'green' : maintenance_status == 3 ? 'red' : 'yellowgreen',
    cancelButtonColor: 'gray',
    cancelButtonText: 'إلغاء الأمر',
    confirmButtonText: 'نعم',
    preConfirm: () => {
      router.visit(
        route('EquipmentsMaintenances.ChangeStatus', { id: m.id, maintenance_status }),
        {
          method: 'get',
          preserveState: true,
          preserveScroll: true,
          onSuccess: () => toast.success('تم تحديث الحالة'),
          onError: () => toast.error('حدث خطأ لم يتم تغيير الحالة'),
        }
      )
    },
  })
}


const DeleteMaintenance = (m) => {
  swal.fire({
    title: `حذف الطلب`,
    html: `
      سيتم حذف طلب الصيانة رقم
      <span class="text-blue-500 font-bold">#${m.id}</span><br>
      هل أنت متأكد؟
    `,
    icon: 'error',
    showCancelButton: true,
    confirmButtonColor: 'red',
    cancelButtonColor: 'gray',
    cancelButtonText: 'إلغاء الأمر',
    confirmButtonText: 'نعم',
    preConfirm: () => {
      router.visit(route('EquipmentsMaintenances.delete', { id: m.id }), {
        method: 'delete',
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => toast.success('تم حذف الطلب بنجاح'),
        onError: () => toast.error('حدث خطأ لم تتم عملية الحذف'),
      })
    },
  })
}
</script>

<template>
  <Head title="عرض طلبات الصيانة" />

  <div class="mx-auto px-4 py-8 w-full" dir="rtl">
    <h1 class="text-3xl font-bold text-blue-800 mb-6 pb-3 border-b border-gray-200">
      عرض وإدارة طلبات الصيانة
    </h1>

    <div class="flex flex-wrap gap-3 mb-5">
      <Link
        :href="route('EquipmentsMaintenances.create')"
        class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center shadow-md hover:shadow-lg"
      >
        + إضافة طلب
      </Link>
    </div>

    <!-- فلترة وبحث (نفس تصميمك) -->
    <form class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200" @submit.prevent="ApplyFilters">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">البحث</label>
          <input
            v-model="form.search"
            type="text"
            placeholder="ابحث بالعميل أو الجهاز أو السيريال..."
            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
          />
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">الحالة</label>
          <select
            v-model="form.maintenance_status"
            class="w-full py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
          >
            <option value="">الكل</option>
            <option value="1">قيد الانتظار</option>
            <option value="2">قبول</option>
            <option value="3">رفض</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">عدد السجلات</label>
          <select
            v-model="form.perPage"
            class="w-full py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
          >
            <option v-for="option in perPageOptions" :value="option" :key="option">
              {{ option }}
            </option>
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

    <!-- الجدول (نفس تصميمك) -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 outer_container">
      <div class="overflow-x-auto table_container">
        <table class="divide-y divide-gray-200 w-full" id="printable-table">
          <thead class="bg-[#32506c]">
            <tr class="text-right">
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">#</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">العميل</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الجهاز</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الرقم التسلسلي</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الشركة المصنعة</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الحالة</th>
              <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">العمليات</th>
            </tr>
          </thead>

          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="m, index in maintenances.data" :key="m.id" class="hover:bg-gray-50 transition">
              <td class="px-6 py-4 whitespace-nowrap">{{ m.id }}</td>

              <td class="px-6 py-4">
                <div class="text-sm font-medium text-gray-900">{{ m.client?.entity_name || '-' }}</div>
                <div class="text-xs text-gray-500" v-if="m.client?.client_name">
                  مسؤول: {{ m.client.client_name }} — {{ m.client.phone }}
                </div>
              </td>

              <td class="px-6 py-4 text-sm text-gray-500">
                {{ m.equipment?.name || '-' }}
              </td>

              <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap"  >
                {{ m.serial_number }}
              </td>

              <td class="px-6 py-4 text-sm text-gray-500">
                {{ m.manufacturer }}
              </td>

           <td class="px-6 py-4">
              <span
  class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
  :class="StatusClass(m.maintenance_status)"
>
  {{ StatusLabel(m.maintenance_status) }}
</span>
                </td>
              <!-- العمليات: Dropdown + تغيير الحالة داخل القائمة -->
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium relative">
                <div class="relative inline-block text-left pl-[70px]"   >
                  <button
                    @click="open_index = open_index === index ? null : index"
                    class="text-gray-500 hover:text-gray-700 focus:outline-none"
                  >
                    <i class="fas fa-ellipsis-v"></i> العمليات
                  </button>

                  <div
                    v-if="open_index === index"
                    class="absolute z-10 right-[-28px] mt-2 w-44 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                  >
                    <ul class="py-1 text-sm text-gray-700">
                   <li>
  <button
    @click="ChangeStatus(m, 2)"
    class="w-full text-right px-4 py-2 hover:bg-gray-100"
    v-if="m.maintenance_status != 2"
  >
    <fa class="ml-1 text-green-500" :icon="'check'" /> قبول
  </button>
</li>

<li>
  <button
    @click="ChangeStatus(m, 3)"
    class="w-full text-right px-4 py-2 hover:bg-gray-100"
    v-if="m.maintenance_status != 3"
  >
    <fa class="ml-1 text-red-500" :icon="'xmark'" /> رفض
  </button>
</li>

<li>
  <button
    @click="ChangeStatus(m, 1)"
    class="w-full text-right px-4 py-2 hover:bg-gray-100"
    v-if="m.maintenance_status != 1"
  >
    <fa class="ml-1 text-yellow-500" :icon="'hand'" /> قيد الانتظار
  </button>
</li>

                      <li>
                        <Link :href="route('EquipmentsMaintenances.edit', { id: m.id })">
                          <button class="w-full text-right px-4 py-2 hover:bg-gray-100">
                            <fa class="ml-1 text-blue-500" :icon="'edit'" /> تعديل
                          </button>
                        </Link>
                      </li>

                      <li>
                        <button
                          @click="DeleteMaintenance(m)"
                          class="w-full text-right px-4 py-2 hover:bg-gray-100 text-red-500"
                        >
                          <fa class="ml-1" :icon="'trash-can'" /> حذف
                        </button>
                      </li>
                    </ul>
                  </div>
                </div>
              </td>
            </tr>

            <tr v-if="!maintenances.data.length">
              <td class="px-6 py-8 text-center text-gray-500" colspan="7">لا توجد بيانات.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <Pagination
        :links="maintenances.links"
        :from="maintenances.from"
        :to="maintenances.to"
        :total="maintenances.total"
        :currentPage="maintenances.current_page"
        :lastPage="maintenances.last_page"
      />
    </div>
  </div>
</template>
