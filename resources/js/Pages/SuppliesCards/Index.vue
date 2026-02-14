<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';
 
import Pagination from '@/Components/Pagination.vue';
import { ElSelect, ElOption } from 'element-plus';
import * as constants from '@/JsFiles/constants.js';

import { toast } from 'vue3-toastify';
import { inject } from 'vue'; 

const swal = inject('$swal') ;

const auth_user = usePage().props.auth?.user || {};
const props = defineProps({ users: Object, filters: Object,  });

defineOptions({ layout: AppLayout });

const open_index = ref(null);

const form = useForm({
  search: props.filters.search || '',
 
  role: props.filters.role || '',
  per_page: props.filters.per_page || 5,
});

const per_page_options = [5, 10, 15, 20, 25, 50, 100];

function ApplyFilters() {
  form.get(route('Users.Index'), {
    preserveState: true,
    replace: true,
  });
}

function DeleteUser(user) {
  swal.fire({
    title: `حذف المستخدم؟`,
    html: `سيتم حذف المستخدم <strong class="text-blue-500">${user.name}</strong> نهائيًا.<br>هل أنت متأكد؟`,
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: 'red',
    cancelButtonColor: 'gray',
    cancelButtonText: 'إلغاء',
    confirmButtonText: 'نعم، حذف',
    preConfirm: () => {
      router.delete(route('Users.Delete', { id: user.id }), {
        preserveState: true,
        onSuccess: () => toast.success('تم حذف المستخدم بنجاح'),
        onError: () => toast.error('حدث خطأ أثناء الحذف'),
      });
    }
  });
}
</script>

<template>
  <Head title="عرض المستخدمين" />
  <div class="mx-auto px-4 py-8 w-full">
    <h1 class="text-3xl font-bold text-blue-800 mb-6 pb-3 border-b border-gray-200">عرض وإدارة المستخدمين</h1>

    <form class="bg-white p-6 rounded-xl shadow-md mb-8 border border-gray-200" @submit.prevent="ApplyFilters">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">البحث بالاسم أو البريد</label>
          <input v-model="form.search" type="text" placeholder="ابحث..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition">
        </div>

 

        <div v-if="auth_user.role <= 2">
          <label class="block text-sm font-medium text-gray-700 mb-1">نوع المستخدم</label>
          <select v-model="form.role" class="w-full py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition">
            <option value="">الكل</option>
            <option value="1">سوبر أدمن</option>
            <option value="2">أدمن  </option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-700 mb-1">عدد السجلات</label>
          <select v-model="form.per_page" @change="ApplyFilters" class="w-full py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#32506c] focus:border-[#32506c] transition">
            <option v-for="option in per_page_options" :value="option" :key="option">{{ option }}</option>
          </select>
        </div>
      </div>

      <div class="flex space-x-3">
        <button class="bg-[#c5b26b] hover:bg-[#b3a15f] text-white font-medium py-2 px-6 rounded-lg transition duration-300 transform hover:-translate-y-0.5 hover:shadow-md">بحث</button>
      </div>
    </form>

    <!-- الجدول -->
    <div class="bg-white rounded-xl shadow-md overflow-hidden border border-gray-200">
      <div class="overflow-x-auto table_container">
        <table class="divide-y divide-gray-200 w-full">
          <thead class="bg-blue-800">
            <tr class="text-right text-white text-xs font-medium">
              <th class="px-6 py-3">الاسم</th>
              <th class="px-6 py-3">الإيميل</th>
              <th class="px-6 py-3">الهاتف</th>
              <th class="px-6 py-3">الموقع</th>
 
              <th class="px-6 py-3">الصلاحية</th>
              <th class="px-6 py-3">العمليات</th>
            </tr>
          </thead>
          <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(user, index) in users.data" :key="user.id" class="hover:bg-gray-50">
              <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ user.name }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.email }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.phone }}</td>
              <td class="px-6 py-4 text-sm text-gray-600">{{ user.location || '-' }}</td>
 
              <td class="px-6 py-4 text-sm text-gray-600">{{ constants.role[user.role] }}</td>
              <td class="px-6 py-4 text-sm text-gray-700">
                <div class="relative inline-block text-left pl-[40px]"  >
                  <button @click="open_index = open_index === index ? null : index" class="text-gray-500 hover:text-gray-700 focus:outline-none">
                           <fa icon = "ellipsis-vertical" />    العمليات  
                            </button>

                  <div v-if="open_index === index" class="absolute z-10 right-[0px] mt-2 w-40 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                    <ul class="py-1 text-sm text-gray-700">
                      <li>
                        <Link :href="route('Users.Edit', { id: user.id })">
                          <button class="w-full text-right px-4 py-2 hover:bg-gray-100">
                            <fa class="ml-1 text-blue-500" :icon="'edit'" /> تعديل 
                          </button>
                        </Link>
                      </li>
                      <li>
                        <button @click="DeleteUser(user)" class="w-full text-right px-4 py-2 hover:bg-gray-100 text-red-600">
                          <fa class="ml-1" icon="trash-can" /> حذف
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

      <Pagination :links="users.links" :from="users.from" :to="users.to" :total="users.total" :currentPage="users.current_page" :lastPage="users.last_page" />
    </div>
  </div>
</template>