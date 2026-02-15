<!-- resources/js/Components/Common/ClientComponents.vue -->
<script setup>
import { useForm } from '@inertiajs/vue3';
import { computed } from 'vue';
import { ElSelect, ElOption } from 'element-plus';
import { toast } from 'vue3-toastify';
import * as constants from '@/JsFiles/constants.js';

/**
 * props:
 * - mode:
 *   - "maintenance_only" => منشأة صحية فقط (مستشفى/مصحة)
 *   - "full" => (لو تحتاج لاحقاً) كامل الأنواع
 */
const props = defineProps({
  mode: { type: String, default: 'maintenance_only' },
});

const emit = defineEmits(['created', 'cancel']);

const form = useForm({
  client_type: 1,          // تثبيت منشأة صحية في وضع الصيانة
  client_subtype: 1,
  entity_name: 'sa',
  client_name: 'sa',
  phone: '09521',
  note: 'notes1',
});

const allowedSubtypes = computed(() => {
  // في وضع الصيانة: فقط (1 مستشفى) أو (2 مصحة)
  if (props.mode === 'maintenance_only') return ['1', '2'];
  return ['1', '2', '3', '4'];
});

function Store() {
  // راوت حفظ العميل (أنشئه أو استخدم الموجود عندك)
  form.post(route('Clients.StoreQuick'), {
    preserveScroll: true,
    onSuccess: (page) => {
      // الأفضل أن ترجع من السيرفر العميل الجديد في:
      // session()->flash('new_client', $client);
      const newClient = page?.props?.flash?.new_client || page?.props?.new_client || null;
        console.log(newClient );
        
      if (newClient) {
        emit('created', newClient);
      } else {
        toast.success('تم حفظ العميل');
        emit('cancel');
      }
    },
    onError: () => toast.error('تعذر حفظ العميل، تحقق من البيانات'),
  });
}
</script>

<template>
  <div dir="rtl">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

      <!-- نوع المنشأة (مستشفى/مصحة) -->
      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">
          نوع المنشأة <span class="text-red-500">*</span>
        </label>

        <select v-model="form.client_subtype" placeholder="اختر النوع" class="w-full" filterable clearable>
          <option
            v-for="(label, key) in constants.client_subtype"
            :key="key"
            :label="label"
            :value="parseInt(key)"
            v-show="allowedSubtypes.includes(String(key))"
          />
        </select>

        <p v-if="form.errors.client_subtype" class="error-text">{{ form.errors.client_subtype }}</p>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">
          اسم الجهة <span class="text-red-500">*</span>
        </label>
        <input v-model="form.entity_name" type="text" class="input-field" placeholder="اسم المستشفى/المصحة" />
        <p v-if="form.errors.entity_name" class="error-text">{{ form.errors.entity_name }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">
          اسم المسؤول <span class="text-red-500">*</span>
        </label>
        <input v-model="form.client_name" type="text" class="input-field" placeholder="اسم الشخص المسؤول" />
        <p v-if="form.errors.client_name" class="error-text">{{ form.errors.client_name }}</p>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700">
          رقم الهاتف <span class="text-red-500">*</span>
        </label>
        <input v-model="form.phone" type="text" class="input-field" placeholder="رقم الهاتف" />
        <p v-if="form.errors.phone" class="error-text">{{ form.errors.phone }}</p>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
        <textarea v-model="form.note" rows="3" class="input-field" placeholder="أي ملاحظات..."></textarea>
        <p v-if="form.errors.note" class="error-text">{{ form.errors.note }}</p>
      </div>
    </div>

    <div class="mt-6 flex items-center justify-between">
      <button
        type="button"
        class="px-6 py-2 rounded-lg border border-gray-300 hover:bg-gray-50 transition"
        @click="$emit('cancel')"
      >
        إلغاء
      </button>

      <button
        type="button"
        class="px-6 py-2 text-white font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 transition"
        :disabled="form.processing"
        @click="Store()"
      >
        {{ form.processing ? "...جاري الحفظ" : "حفظ العميل" }}
      </button>
    </div>
  </div>
</template>

<style scoped>
.input-field {
  @apply w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition;
}
.error-text {
  @apply mt-1 text-sm text-red-600;
}
</style>
