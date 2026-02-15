<!-- resources/js/Components/Common/EquipmentOrderDetails.vue -->
<script setup>
const props = defineProps({
  order: { type: Object, default: null },
  constants: { type: Object, default: () => ({}) },
})

const emit = defineEmits(['cancel'])

const StatusLabel = (v) => props.constants?.equipment_order_status?.[String(v)] || '-'

const StatusClass = (v) => {
  const s = String(v)
  if (s === '1') return 'bg-yellow-100 text-yellow-800'
  if (s === '2') return 'bg-green-100 text-green-800'
  if (s === '3') return 'bg-red-100 text-red-800'
  return 'bg-gray-100 text-gray-800'
}
</script>

<template>
  <div v-if="order" class="fixed inset-0 bg-black/40 z-50 flex items-center justify-center p-4" dir="rtl">
    <div class="bg-white w-full max-w-4xl rounded-2xl shadow-xl overflow-hidden">
      <!-- Header -->
      <div class="p-5 border-b flex items-center justify-between">
        <div class="font-bold text-lg text-gray-800">
          تفاصيل الطلب #{{ order.id }}
        </div>

        <button
          type="button"
          @click="emit('cancel')"
          class="px-4 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition"
        >
          إغلاق
        </button>
      </div>

      <!-- Body -->
      <div class="p-6 space-y-4">
        <!-- Summary cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
            <div class="text-sm text-gray-500">العميل</div>
            <div class="font-semibold text-gray-800 mt-1">
              {{ order.client?.entity_name || '-' }}
            </div>
            <div class="text-xs text-gray-600 mt-1">
              {{ order.client?.client_name || '-' }} — {{ order.client?.phone || '-' }}
            </div>
          </div>

          <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
            <div class="text-sm text-gray-500">الحالة</div>

            <!-- ✅ نفس Badge الخاص بالجدول -->
            <div class="mt-2">
              <span
                class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                :class="StatusClass(order.equipment_order_status)"
              >
                {{ StatusLabel(order.equipment_order_status) }}
              </span>
            </div>

            <div class="text-xs text-gray-600 mt-2">
              تاريخ الطلب: {{ order.order_date || '-' }}
            </div>
          </div>
        </div>

        <!-- Details table -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden border border-gray-200">
          <div class="p-4 border-b border-gray-200 font-bold text-gray-800">
            المعدات المطلوبة
          </div>

          <div class="overflow-x-auto">
            <table class="w-full divide-y divide-gray-200">
              <thead class="bg-[#32506c]">
                <tr class="text-right">
                  <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">المعدة</th>
                  <th class="px-6 py-3 text-xs font-medium text-white uppercase tracking-wider">الكمية</th>
                </tr>
              </thead>

              <tbody class="bg-white divide-y divide-gray-200">
                <tr
                  v-for="d in (order.details || [])"
                  :key="d.id"
                  class="hover:bg-gray-50 transition"
                >
                  <td class="px-6 py-4 text-sm text-gray-700">
                    {{ d.equipment?.name || '-' }}
                  </td>
                  <td class="px-6 py-4 text-sm text-gray-700">
                    {{ d.amount }}
                  </td>
                </tr>

                <tr v-if="!(order.details || []).length">
                  <td colspan="2" class="px-6 py-6 text-center text-sm text-gray-500">
                    لا توجد تفاصيل لهذا الطلب.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Notes -->
        <div class="bg-gray-50 p-4 rounded-xl border border-gray-200">
          <div class="text-sm text-gray-500 mb-1">ملاحظات</div>
          <div class="text-sm text-gray-800 whitespace-pre-line">
            {{ order.note || '-' }}
          </div>
        </div>
      </div>

      <!-- Footer -->
      <div class="p-4 border-t border-gray-200 flex justify-end">
        <button
          type="button"
          @click="emit('cancel')"
          class="bg-[#32506c] hover:bg-[#2b4258] text-white font-medium py-2 px-6 rounded-lg transition shadow-md"
        >
          إغلاق
        </button>
      </div>
    </div>
  </div>
</template>
