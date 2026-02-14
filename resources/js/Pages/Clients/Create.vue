<!-- resources/js/Pages/Clients/Create.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import { ElSelect, ElOption } from 'element-plus';
import * as constants from '@/JsFiles/constants.js';
import { computed, watch } from 'vue';

defineOptions({ layout: AppLayout });

const form = useForm({
  client_type: '',
  client_subtype: '',
  entity_name: '',
  client_name: '',
  phone: '',
  note: '',
  pdf_file: null, // للشركات (جهة عامة/خاصة)
});

const allowedSubtypes = computed(() => {
  const t = parseInt(form.client_type || 0);
  if (t === 1) return ['1', '2']; // منشأة صحية: مستشفى/مصحة
  if (t === 2) return ['3', '4']; // شركة: جهة عامة/خاصة
  return [];
});

const isHealthFacility = computed(() => parseInt(form.client_type || 0) === 1);
const isCompanyEntity = computed(() => parseInt(form.client_type || 0) === 2);
const isPublicOrPrivate = computed(() => ['3', '4'].includes(String(form.client_subtype)));

watch(
  () => form.client_type,
  () => {
    // عند تغيير النوع الرئيسي: صفّر النوع الفرعي + الحقول المرتبطة
    form.client_subtype = '';
    form.pdf_file = null;
    form.clearErrors();
  }
);

watch(
  () => form.client_subtype,
  (v) => {
    // إذا أصبح الاختيار شركة (جهة عامة/خاصة) أخفِ/صفّر حقول الإدخال العادية
    if (['3', '4'].includes(String(v))) {
      form.entity_name = '';
      form.client_name = '';
      form.phone = '';
      form.note = '';
    } else {
      form.pdf_file = null;
    }
    form.clearErrors();
  }
);

 

function Store() {
  form.post(route('Clients.store'), {
    onSuccess: () => toast.success('تم إضافة العميل بنجاح'),
    onError: () => toast.error('حدث خطأ أثناء الحفظ'),
  });
}

const pdfUrl = computed(() => {
  const st = String(form.client_subtype || '');
  if (st === '3') return '/storage/pdf/public.pdf';   // جهة عامة
  if (st === '4') return '/storage/pdf/private.pdf';  // جهة خاصة
  return null;
});
</script>

<template>
  <Head title="إضافة عميل" />

  <div class="w-full py-10 px-10 bg-white" dir="rtl">
    <h1 class="text-3xl font-bold text-right mb-6">نموذج إضافة عميل</h1>
    <hr />
    <br />

    <!-- 1) قسم الاختيارات الأساسية -->
    <section>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <!-- client_type -->
        <div>
          <label class="block text-sm font-medium text-gray-700">
            نوع العميل <span class="text-red-500">*</span>
          </label>

          <el-select v-model="form.client_type" placeholder="اختر نوع العميل" class="w-full" filterable>
            <el-option
              v-for="(label, key) in constants.client_type"
              :key="key"
              :label="label"
              :value="parseInt(key)"
            />
          </el-select>

          <p v-if="form.errors.client_type" class="error-text">{{ form.errors.client_type }}</p>
        </div>

        <!-- client_subtype -->
        <div>
          <label class="block text-sm font-medium text-gray-700">
            نوع فرعي <span class="text-red-500">*</span>
          </label>

          <el-select
            v-model="form.client_subtype"
            placeholder="اختر النوع الفرعي"
            class="w-full"
            filterable
            :disabled="!form.client_type"
          >
            <el-option
              v-for="(label, key) in constants.client_subtype"
              :key="key"
              :label="label"
              :value="parseInt(key)"
              v-show="allowedSubtypes.includes(String(key))"
            />
          </el-select>

          <p v-if="form.errors.client_subtype" class="error-text">{{ form.errors.client_subtype }}</p>
        </div>

      </div>
    </section>

    <!-- 2) منشأة صحية: حقول إدخال + زر حفظ -->
    <form v-if="isHealthFacility" @submit.prevent="Store()">
      <section class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">
              اسم الجهة <span class="text-red-500">*</span>
            </label>
            <input v-model="form.entity_name" type="text" required class="input-field" placeholder="اسم المستشفى/المصحة" />
            <p v-if="form.errors.entity_name" class="error-text">{{ form.errors.entity_name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">
              اسم العميل/المسؤول <span class="text-red-500">*</span>
            </label>
            <input v-model="form.client_name" type="text" required class="input-field" placeholder="اسم الشخص المسؤول" />
            <p v-if="form.errors.client_name" class="error-text">{{ form.errors.client_name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">
              رقم الهاتف <span class="text-red-500">*</span>
            </label>
            <input v-model="form.phone" type="text" required class="input-field" placeholder="رقم الهاتف" />
            <p v-if="form.errors.phone" class="error-text">{{ form.errors.phone }}</p>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">ملاحظات</label>
            <textarea v-model="form.note" rows="3" class="input-field" placeholder="أي ملاحظات إضافية"></textarea>
            <p v-if="form.errors.note" class="error-text">{{ form.errors.note }}</p>
          </div>

        </div>
      </section>

      <div class="mt-10 text-left">
        <button
          type="submit"
          class="px-8 py-3 text-white font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 transition duration-200"
          :disabled="form.processing || !form.client_subtype"
        >
          {{ form.processing ? "...جاري الحفظ" : "حفظ البيانات" }}
        </button>
      </div>
    </form>

   
  <!-- شركة (جهة عامة/خاصة): تحميل PDF فقط -->
  <div v-if="isCompanyEntity && isPublicOrPrivate" class="mt-6">
    <div class="p-5 border border-gray-200 rounded-lg bg-gray-50">
      <div class="flex items-start justify-between gap-4">
        <div>
          <h2 class="text-lg font-bold text-gray-800">تحميل ملف PDF</h2>
          <p class="text-sm text-gray-600 mt-1">
           "هذا العميل من نوع (جهة عامة/جهة خاصة). يمكنك تحميل النموذج الخاص به فقط."
          </p>
        </div>

        <a
          v-if="pdfUrl"
          :href="pdfUrl"
          class="px-6 py-2 text-white font-semibold rounded-lg bg-emerald-600 hover:bg-emerald-700 transition duration-200"
          download
          target="_blank"
          rel="noopener"
        >
          تحميل PDF
        </a>

        <button
          v-else
          type="button"
          class="px-6 py-2 text-white font-semibold rounded-lg bg-gray-400 cursor-not-allowed"
          disabled
        >
          تحميل PDF
        </button>
      </div>
    </div>
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
