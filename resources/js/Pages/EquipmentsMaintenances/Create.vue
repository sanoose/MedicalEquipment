<!-- resources/js/Pages/EquipmentsMaintenances/Create.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref, computed } from 'vue';
import { ElSelect, ElOption, ElDialog } from 'element-plus';
import { toast } from 'vue3-toastify';

import ClientComponents from '@/Components/Common/ClientComponents.vue';

defineOptions({ layout: AppLayout });

const props = defineProps({
  clients: { type: Array, default: () => [] },
  equipments_cards: { type: Array, default: () => [] },
});

/* ====== فورم الصيانة ====== */
const form = useForm({
  client_id: '',
  equipment_id: '',
  description: '',
  manufacturer: '',
  serial_number: '',
  note: '',
  // maintenance_status لا يظهر هنا (السيرفر يضعه 1 تلقائياً)
});

const canShowFields = computed(() => !!form.client_id);

/* ====== مودال إضافة عميل ====== */
const showAddClientModal = ref(false);

/**
 * هذه الدالة تستقبل العميل الجديد من الكمبوننت
 * وتضيفه مباشرة للقائمة وتحددّه في الكمبو بوكس
 */
function onClientCreated(newClient) {
       console.log(   "newClient" + newClient  );
  if (!newClient?.id) return;

  // أضف للـ props list محلياً (بدون reload)
  props.clients.unshift(newClient);

  // عيّنه كعميل مختار
  form.client_id = newClient.id;
 
    
  showAddClientModal.value = false;
  toast.success('تمت إضافة العميل بنجاح');
}

/* ====== حفظ الصيانة ====== */
function StoreMaintenance() {
  form.post(route('EquipmentsMaintenances.store'), {
    onSuccess: () => toast.success('تم حفظ طلب الصيانة بنجاح'),
    onError: () => toast.error('تعذر الحفظ، تحقق من البيانات'),
  });
}
</script>

<template>
  <Head title="إضافة طلب صيانة" />

  <div class="w-full py-10 px-10 bg-white" dir="rtl">
    <h1 class="text-3xl font-bold text-right mb-6">إضافة طلب صيانة معدات</h1>
    <hr />
    <br />

    <!-- العميل + زر إضافة عميل -->
    <section>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-end">
        <div>
          <label class="block text-sm font-medium text-gray-700">
            العميل <span class="text-red-500">*</span>
          </label>

          <el-select
            v-model="form.client_id"
            placeholder="اختر العميل"
            class="w-full"
            filterable
            clearable
          >
            <el-option
              v-for="c in props.clients"
              :key="c.id"
              :label="`${c.entity_name} — ${c.client_name} (${c.phone})`"
              :value="c.id"
            />
          </el-select>

          <p v-if="form.errors.client_id" class="error-text">{{ form.errors.client_id }}</p>
        </div>

        <div class="text-right">
          <button
            type="button"
            @click="showAddClientModal = true"
            class="px-6 py-2 text-white font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 transition"
          >
            + إضافة عميل
          </button>
        </div>
      </div>
    </section>

    <!-- الحقول تظهر بعد اختيار عميل -->
    <form v-if="canShowFields" class="mt-6" @submit.prevent="StoreMaintenance()">
      <section>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <!-- الجهاز -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">
              الجهاز المطلوب صيانته <span class="text-red-500">*</span>
            </label>

            <el-select
              v-model="form.equipment_id"
              placeholder="اختر الجهاز"
              class="w-full"
              filterable
              clearable
            >
              <el-option
                v-for="e in props.equipments_cards"
                :key="e.id"
                :label="e.name"
                :value="e.id"
              />
            </el-select>

            <p v-if="form.errors.equipment_id" class="error-text">{{ form.errors.equipment_id }}</p>
          </div>

          <!-- الوصف -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">
              الوصف <span class="text-red-500">*</span>
            </label>
            <input v-model="form.description" type="text" class="input-field" required placeholder="وصف مختصر للمعدة أو العطل" />
            <p v-if="form.errors.description" class="error-text">{{ form.errors.description }}</p>
          </div>

          <!-- الشركة المصنعة -->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              الشركة المصنعة <span class="text-red-500">*</span>
            </label>
            <input v-model="form.manufacturer" type="text" class="input-field" required placeholder="مثال: Philips / GE / Dräger" />
            <p v-if="form.errors.manufacturer" class="error-text">{{ form.errors.manufacturer }}</p>
          </div>

          <!-- السيريال -->
          <div>
            <label class="block text-sm font-medium text-gray-700">
              الرقم التسلسلي <span class="text-red-500">*</span>
            </label>
            <input v-model="form.serial_number" type="text" class="input-field" required placeholder="Serial Number" />
            <p v-if="form.errors.serial_number" class="error-text">{{ form.errors.serial_number }}</p>
          </div>

          <!-- ملاحظات -->
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
            <textarea v-model="form.note" rows="3" class="input-field" placeholder="أي تفاصيل إضافية..."></textarea>
            <p v-if="form.errors.note" class="error-text">{{ form.errors.note }}</p>
          </div>

        </div>
      </section>

      <div class="mt-10 text-left">
        <button
          type="submit"
          class="px-8 py-3 text-white font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 transition"
          :disabled="form.processing"
        >
          {{ form.processing ? "...جاري الحفظ" : "حفظ" }}
        </button>
      </div>
    </form>

    <!-- مودال: استدعاء كومبوننت إضافة العميل -->
    <el-dialog v-model="showAddClientModal" title="إضافة عميل" width="750px" align-center>
      <ClientComponents
        mode="maintenance_only"
        @created="onClientCreated"
        @cancel="showAddClientModal = false"
      />
    </el-dialog>
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
