<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

defineOptions({
  layout: AppLayout
});

const props = defineProps({
  stats: Object
});
</script>

<template>
  <Head title="لوحة التحكم" />

  <div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        
        <!-- إجمالي الوثائق المصدرة -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
          <div class="p-6 flex items-center space-x-4 rtl:space-x-reverse">
            <div class="bg-blue-50 p-4 rounded-lg">
              <FontAwesomeIcon :icon="['fas', 'file-contract']" class="text-blue-600 text-2xl" />
            </div>
            <div>
              <h3 class="text-lg font-medium text-gray-500">إجمالي طلبات المستلزمات</h3>
              <p class="text-3xl font-bold text-gray-800">{{  0  }}</p>
            </div>
          </div>
        </div>

        <!-- إجمالي المكاتب -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
          <div class="p-6 flex items-center space-x-4 rtl:space-x-reverse">
            <div class="bg-indigo-50 p-4 rounded-lg">
              <FontAwesomeIcon :icon="['fas', 'building']" class="text-indigo-600 text-2xl" />
            </div>
            <div>
              <h3 class="text-lg font-medium text-gray-500">إجمالي العملاء</h3>
              <p class="text-3xl font-bold text-gray-800">{{   0  }}</p>
            </div>
          </div>
        </div>

        <!-- إجمالي المستخدمين -->
        <div class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition">
          <div class="p-6 flex items-center space-x-4 rtl:space-x-reverse">
            <div class="bg-amber-50 p-4 rounded-lg">
              <FontAwesomeIcon :icon="['fas', 'users']" class="text-amber-600 text-2xl" />
            </div>
            <div>
              <h3 class="text-lg font-medium text-gray-500">إجمالي طلبات الصيانة </h3>
              <p class="text-3xl font-bold text-gray-800">{{   0  }}</p>
            </div>
          </div>
        </div>

   

   

      

      </div>
    </div>
  </div>
</template>
