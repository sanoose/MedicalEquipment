<!-- resources/js/Pages/EquipmentsOrders/Create.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { computed, ref, inject, watch } from 'vue'
import { ElSelect, ElOption, ElDialog } from 'element-plus'
import { toast } from 'vue3-toastify'
import ClientComponents from '@/Components/Common/ClientComponents.vue'

defineOptions({ layout: AppLayout })

const swal = inject('$swal')

const props = defineProps({
  clients: { type: Array, default: () => [] },
  equipments_cards: { type: Array, default: () => [] },
})

const form = useForm({
  client_id: '',
  note: '',

  // تُستخدم فقط إذا العميل لا يملك معدات (أول مرة)
  client_equipments: [
    { equipment_id: '', description: '', manufacturer: '', serial_number: '', note: '' },
  ],

  // تفاصيل الطلب الجديد
  details: [{ equipment_id: '', amount: 1 }],
})

const open_add_client = ref(false)

function onClientCreated(new_client) {
  if (!new_client?.id) return
  props.clients.unshift(new_client)
  form.client_id = new_client.id
  open_add_client.value = false
  toast.success('تمت إضافة العميل بنجاح')
}

const selected_client = computed(() => props.clients.find(c => String(c.id) === String(form.client_id)) || null)

const existing_client_equipments = computed(() => selected_client.value?.clients_equipments || [])
const client_has_equipments = computed(() => existing_client_equipments.value.length > 0)

const equipments_map = computed(() => {
  const m = {}
  for (const e of props.equipments_cards) m[e.id] = e
  return m
})

watch(
  () => form.client_id,
  () => {
    form.client_equipments = [{ equipment_id: '', description: '', manufacturer: '', serial_number: '', note: '' }]
    form.details = [{ equipment_id: '', amount: 1 }]
    form.note = ''
  }
)

function AddClientEquipmentRow() {
  form.client_equipments.push({ equipment_id: '', description: '', manufacturer: '', serial_number: '', note: '' })
}
function RemoveClientEquipmentRow(index) {
  if (form.client_equipments.length === 1) return
  form.client_equipments.splice(index, 1)
}

function AddDetailRow() {
  form.details.push({ equipment_id: '', amount: 1 })
}
function RemoveDetailRow(index) {
  if (form.details.length === 1) return
  form.details.splice(index, 1)
}

function Store() {
  // تنقية تفاصيل الطلب
  const clean_details = form.details.filter(d => d.equipment_id && Number(d.amount) > 0)
  form.details = clean_details.length ? clean_details : form.details

  // إذا العميل لديه معدات: لا نرسل client_equipments إطلاقًا
  if (client_has_equipments.value) {
    form.client_equipments = []
  } else {
    // أول مرة: ننقّي معدات العميل ونجعلها جاهزة للحفظ
    const clean_client_eq = form.client_equipments.filter(x =>
      x.equipment_id &&
      String(x.description || '').trim() !== '' &&
      String(x.manufacturer || '').trim() !== '' &&
      String(x.serial_number || '').trim() !== ''
    )
    form.client_equipments = clean_client_eq.length ? clean_client_eq : form.client_equipments
  }

  swal.fire({
    title: 'تأكيد الحفظ',
    html: `سيتم حفظ الطلب. ${client_has_equipments.value ? '' : '<br>وسيتم تسجيل معدات العميل أول مرة.'}<br>هل أنت متأكد؟`,
    icon: 'info',
    showCancelButton: true,
    confirmButtonColor: 'green',
    cancelButtonColor: 'gray',
    cancelButtonText: 'إلغاء الأمر',
    confirmButtonText: 'نعم، حفظ',
    preConfirm: () => {
      form.post(route('EquipmentsOrders.store'), {
        preserveScroll: true,
        onSuccess: () => toast.success('تم حفظ الطلب بنجاح'),
        onError: () => toast.error('تعذر الحفظ، تحقق من البيانات'),
      })
    },
  })
}
</script>

<template>
  <Head title="إضافة طلب معدات" />

  <div class="mx-auto px-4 py-8 w-full" dir="rtl">
    <h1 class="text-3xl font-bold text-blue-800 mb-6 pb-3 border-b border-gray-200">
      إضافة طلب معدات جديدة
    </h1>

    <!-- اختيار العميل -->
    <div class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200">
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">العميل</label>

          <el-select
            class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2"
            filterable
            clearable
            placeholder="اختر العميل"
            v-model="form.client_id"
          >
            <el-option
              v-for="c in props.clients"
              :key="c.id"
              :value="c.id"
              :label="`${c.entity_name} — ${c.client_name} (${c.phone})`"
            />
          </el-select>

          <div v-if="form.errors.client_id" class="mt-1 text-sm text-red-600">{{ form.errors.client_id }}</div>
        </div>

        <div class="text-left">
          <button
            type="button"
            @click="open_add_client = true"
            class="bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center shadow-md hover:shadow-lg"
          >
            + إضافة عميل
          </button>
        </div>
      </div>

      <div v-if="selected_client" class="mt-4 text-sm text-gray-600">
        <span class="font-bold">الجهة:</span> {{ selected_client.entity_name }}
        <span class="mx-2">—</span>
        <span class="font-bold">المسؤول:</span> {{ selected_client.client_name }}
        <span class="mx-2">—</span>
        <span class="font-bold">الهاتف:</span> {{ selected_client.phone }}
      </div>
    </div>

    <!-- باقي الصفحة -->
    <form v-if="form.client_id" class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200" @submit.prevent="Store">
      <!-- ملاحظات الطلب -->
      <div class="mb-6">
        <label class="block text-sm font-medium text-gray-700 mb-1">ملاحظات الطلب</label>
        <textarea
          v-model="form.note"
          rows="3"
          placeholder="أي ملاحظات تخص الطلب..."
          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
        />
        <div v-if="form.errors.note" class="mt-1 text-sm text-red-600">{{ form.errors.note }}</div>
      </div>

      <!-- إذا لديه معدات: عرض فقط -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-8" v-if="client_has_equipments">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
          <div class="font-bold text-gray-800">
            معدات العميل المسجلة في النظام
            <span class="text-sm text-green-700 font-medium mr-2">(موجودة مسبقًا)</span>
          </div>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-[#32506c]">
              <tr class="text-right">
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">المعدة</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">Serial</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الشركة</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الوصف</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">ملاحظات</th>
              </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="x in existing_client_equipments" :key="x.id" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4 text-sm text-gray-700">
                  {{ equipments_map[x.equipment_id]?.name || '-' }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700 whitespace-nowrap"  >
                  {{ x.serial_number }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  {{ x.manufacturer }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  {{ x.description }}
                </td>
                <td class="px-6 py-4 text-sm text-gray-700">
                  {{ x.note || '-' }}
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- إذا لا يملك معدات: إدخال معدات العميل الحالية (أول مرة) -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 mb-8" v-else>
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
          <div class="font-bold text-gray-800">
            تسجيل معدات العميل الحالية (أول مرة)
          </div>

          <button
            type="button"
            @click="AddClientEquipmentRow"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center shadow-md hover:shadow-lg"
          >
            + إضافة سطر
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-[#32506c]">
              <tr class="text-right">
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">المعدة</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الوصف</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الشركة</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">Serial</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">ملاحظات</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">عمليات</th>
              </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(row, index) in form.client_equipments" :key="index" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                  <el-select
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2"
                    filterable
                    clearable
                    placeholder="اختر المعدة"
                    v-model="row.equipment_id"
                  >
                    <el-option
                      v-for="e in props.equipments_cards"
                      :key="e.id"
                      :value="e.id"
                      :label="e.name"
                    />
                  </el-select>
                  <div v-if="form.errors[`client_equipments.${index}.equipment_id`]" class="mt-1 text-sm text-red-600">
                    {{ form.errors[`client_equipments.${index}.equipment_id`] }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <input
                    v-model="row.description"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
                    placeholder="وصف مختصر"
                  />
                  <div v-if="form.errors[`client_equipments.${index}.description`]" class="mt-1 text-sm text-red-600">
                    {{ form.errors[`client_equipments.${index}.description`] }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <input
                    v-model="row.manufacturer"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
                    placeholder="الشركة المصنعة"
                  />
                  <div v-if="form.errors[`client_equipments.${index}.manufacturer`]" class="mt-1 text-sm text-red-600">
                    {{ form.errors[`client_equipments.${index}.manufacturer`] }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <input
                    v-model="row.serial_number"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
                    placeholder="Serial Number"
                    dir="ltr"
                  />
                  <div v-if="form.errors[`client_equipments.${index}.serial_number`]" class="mt-1 text-sm text-red-600">
                    {{ form.errors[`client_equipments.${index}.serial_number`] }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <input
                    v-model="row.note"
                    type="text"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
                    placeholder="ملاحظات (اختياري)"
                  />
                </td>

                <td class="px-6 py-4">
                  <button
                    type="button"
                    @click="RemoveClientEquipmentRow(index)"
                    class="px-4 py-2 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition"
                    :disabled="form.client_equipments.length === 1"
                  >
                    حذف
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <div v-if="form.errors.client_equipments" class="p-4 text-sm text-red-600">
            {{ form.errors.client_equipments }}
          </div>
        </div>
      </div>

      <!-- تفاصيل الطلب الجديد (دائماً) -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
          <div class="font-bold text-gray-800">تفاصيل الطلب (المعدات المطلوبة)</div>

          <button
            type="button"
            @click="AddDetailRow"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center shadow-md hover:shadow-lg"
          >
            + إضافة سطر
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-[#32506c]">
              <tr class="text-right">
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">المعدة المطلوبة</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الكمية</th>
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">عمليات</th>
              </tr>
            </thead>

            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(row, index) in form.details" :key="index" class="hover:bg-gray-50 transition">
                <td class="px-6 py-4">
                  <el-select
                    class="w-full px-2 py-1 border border-gray-300 rounded-lg focus:ring-2"
                    filterable
                    clearable
                    placeholder="اختر المعدة المطلوبة"
                    v-model="row.equipment_id"
                  >
                    <el-option
                      v-for="e in props.equipments_cards"
                      :key="e.id"
                      :value="e.id"
                      :label="e.name"
                    />
                  </el-select>
                  <div v-if="form.errors[`details.${index}.equipment_id`]" class="mt-1 text-sm text-red-600">
                    {{ form.errors[`details.${index}.equipment_id`] }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <input
                    v-model.number="row.amount"
                    type="number"
                    min="1"
                    step="1"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition"
                    placeholder="1"
                  />
                  <div v-if="form.errors[`details.${index}.amount`]" class="mt-1 text-sm text-red-600">
                    {{ form.errors[`details.${index}.amount`] }}
                  </div>
                </td>

                <td class="px-6 py-4">
                  <button
                    type="button"
                    @click="RemoveDetailRow(index)"
                    class="px-4 py-2 rounded-lg border border-red-200 text-red-700 hover:bg-red-50 transition"
                    :disabled="form.details.length === 1"
                  >
                    حذف
                  </button>
                </td>
              </tr>
            </tbody>
          </table>

          <div v-if="form.errors.details" class="p-4 text-sm text-red-600">
            {{ form.errors.details }}
          </div>
        </div>
      </div>

      <!-- حفظ -->
      <div class="mt-8 text-left">
        <button
          class="bg-[#c5b26b] hover:bg-[#b3a15f] text-white font-medium py-2 px-6 rounded-lg transition duration-300 transform hover:-translate-y-0.5 hover:shadow-md"
          :disabled="form.processing"
        >
          {{ form.processing ? '...جاري الحفظ' : 'حفظ' }}
        </button>
      </div>
    </form>

    <!-- مودال إضافة عميل -->
    <el-dialog v-model="open_add_client" title="إضافة عميل" width="750px" align-center>
      <ClientComponents mode="maintenance_only" @created="onClientCreated" @cancel="open_add_client = false" />
    </el-dialog>
  </div>
</template>
