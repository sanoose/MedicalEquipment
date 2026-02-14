<!-- resources/js/Pages/SuppliesCards/Create.vue -->
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

defineOptions({ layout: AppLayout });

const form = useForm({
  name: '',
});

function Store() {
  form.post(route('SuppliesCards.store'), {
    onSuccess: () => toast.success('تمت إضافة المستلزم بنجاح'),
    onError: () => toast.error('تعذر الحفظ، تحقق من البيانات'),
  });
}
</script>

<template>
  <Head title="إضافة مستلزم طبي" />

  <div class="w-full py-10 px-10 bg-white" dir="rtl">
    <h1 class="text-3xl font-bold text-right mb-6">إضافة مستلزم طبي</h1>
    <hr />
    <br />

    <form @submit.prevent="Store()">
      <section>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">
              اسم المستلزم <span class="text-red-500">*</span>
            </label>

            <input
              v-model="form.name"
              type="text"
              required
              class="input-field"
              placeholder="مثال: فلتر، أنبوب، حساس..."
            />

            <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
          </div>
        </div>
      </section>

      <div class="mt-10 text-left">
        <button
          type="submit"
          class="px-8 py-3 text-white font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 transition duration-200"
          :disabled="form.processing"
        >
          {{ form.processing ? "...جاري الحفظ" : "حفظ" }}
        </button>
      </div>
    </form>
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
