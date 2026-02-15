<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { Head, useForm } from '@inertiajs/vue3'
import { ref, inject } from 'vue'
import { ElSelect, ElOption, ElDialog } from 'element-plus'
import { toast } from 'vue3-toastify'
import ClientComponents from '@/Components/Common/ClientComponents.vue'

defineOptions({ layout: AppLayout })

const swal = inject('$swal')

const props = defineProps({
  clients: { type: Array, default: () => [] },
  supplies_cards: { type: Array, default: () => [] },
})

const form = useForm({
  client_id: '',
  note: '',
  details: [{ supply_id: '', amount: 1 }],
})

const open_add_client = ref(false)

function onClientCreated(new_client) {
  if (!new_client?.id) return
  props.clients.unshift(new_client)
  form.client_id = new_client.id
  open_add_client.value = false
  toast.success('تمت إضافة العميل بنجاح')
}

function AddRow() {
  form.details.push({ supply_id: '', amount: 1 })
}

function RemoveRow(index) {
  if (form.details.length === 1) return
  form.details.splice(index, 1)
}

function Store() {
  // تنقية: منع إرسال صفوف فارغة
  const clean = form.details.filter(d => d.supply_id && Number(d.amount) > 0)
  form.details = clean.length ? clean : form.details

  swal.fire({
    title: 'تأكيد الحفظ',
    html: `سيتم حفظ طلب المستلزمات الطبية.<br>هل أنت متأكد؟`,
    icon: 'info',
    showCancelButton: true,
    confirmButtonColor: 'green',
    cancelButtonColor: 'gray',
    cancelButtonText: 'إلغاء الأمر',
    confirmButtonText: 'نعم، حفظ',
    preConfirm: () => {
      form.post(route('SuppliesOrders.store'), {
        preserveScroll: true,
        onSuccess: () => toast.success('تم حفظ الطلب بنجاح'),
        onError: () => toast.error('تعذر الحفظ، تحقق من البيانات'),
      })
    },
  })
}
</script>

<template>
  <Head title="إضافة طلب مستلزمات" />

  <div class="mx-auto px-4 py-8 w-full" dir="rtl">
    <h1 class="text-3xl font-bold text-blue-800 mb-6 pb-3 border-b border-gray-200">
      إضافة طلب مستلزمات طبية
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
    </div>

    <!-- فورم الطلب -->
    <form v-if="form.client_id" class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200" @submit.prevent="Store">
      <!-- ملاحظات -->
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

      <!-- تفاصيل الطلب -->
      <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between">
          <div class="font-bold text-gray-800">تفاصيل المستلزمات المطلوبة</div>

          <button
            type="button"
            @click="AddRow"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition flex items-center shadow-md hover:shadow-lg"
          >
            + إضافة سطر
          </button>
        </div>

        <div class="overflow-x-auto">
          <table class="w-full divide-y divide-gray-200">
            <thead class="bg-[#32506c]">
              <tr class="text-right">
                <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">المستلزم</th>
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
                    placeholder="اختر المستلزم"
                    v-model="row.supply_id"
                  >
                    <el-option
                      v-for="s in props.supplies_cards"
                      :key="s.id"
                      :value="s.id"
                      :label="s.name"
                    />
                  </el-select>
                  <div v-if="form.errors[`details.${index}.supply_id`]" class="mt-1 text-sm text-red-600">
                    {{ form.errors[`details.${index}.supply_id`] }}
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
                    @click="RemoveRow(index)"
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
