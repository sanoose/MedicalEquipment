<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, useForm, usePage , Link  } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';
import { ElSelect, ElOption } from 'element-plus';
import * as constants from '@/JsFiles/constants.js';



defineOptions({ layout: AppLayout });


const props = defineProps({
  user_data: Object,
 
});

const user = usePage().props.auth?.user || {};

const form = useForm({
  id: props.user_data.id,
  name: props.user_data.name,
  email: props.user_data.email,
  phone: props.user_data.phone,
  location: props.user_data.location || '',
  password: '',
  password_confirmation: '',
 
  role: props.user_data.role
});

function UpdateUser() {
  form.post(route('Users.Update'), {
    onSuccess: () => toast.success('تم تحديث بيانات المستخدم بنجاح'),
    onError: () => toast.error('حدث خطأ أثناء التحديث')
  });
}
</script>

<template>
  <Head title="تعديل المستخدم" />
  <div class="w-full py-10 px-10 bg-white" dir="rtl">
    <h1 class="text-3xl font-bold text-right mb-6">تعديل بيانات المستخدم</h1>
    <hr /><br />
    <form @submit.prevent="UpdateUser()">
      <section>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700">الاسم <span class="text-red-500">*</span></label>
            <input v-model="form.name" type="text" required class="input-field" />
            <p v-if="form.errors.name" class="error-text">{{ form.errors.name }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">البريد الإلكتروني <span class="text-red-500">*</span></label>
            <input v-model="form.email" type="email" required class="input-field" />
            <p v-if="form.errors.email" class="error-text">{{ form.errors.email }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">كلمة المرور الجديدة</label>
            <input v-model="form.password" type="password" class="input-field" placeholder="••••••"  autocomplete="off"  />
            <p v-if="form.errors.password" class="error-text">{{ form.errors.password }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">تأكيد كلمة المرور</label>
            <input v-model="form.password_confirmation" type="password" class="input-field" placeholder="••••••"   autocomplete="off" />
            <p v-if="form.errors.password_confirmation" class="error-text">{{ form.errors.password_confirmation }}</p>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700">رقم الهاتف <span class="text-red-500">*</span></label>
            <input v-model="form.phone" type="text" required class="input-field" />
            <p v-if="form.errors.phone" class="error-text">{{ form.errors.phone }}</p>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">الموقع</label>
            <textarea v-model="form.location" rows="3" class="input-field"></textarea>
            <p v-if="form.errors.location" class="error-text">{{ form.errors.location }}</p>
          </div>

          <div class="md:col-span-2">
            <label class="block text-sm font-medium text-gray-700">الصلاحية <span class="text-red-500">*</span></label>
            <el-select v-model="form.role" placeholder="اختر الصلاحية" class="w-full" filterable>
              <el-option
                v-for="(label, key) in constants.role"
                :key="key"
                :label="label"
                :value="parseInt(key)"
                v-show="parseInt(key) >= user.role"
              />
            </el-select>
            <p v-if="form.errors.role" class="error-text">{{ form.errors.role }}</p>
          </div>

        </div>
      </section>

      <div class="mt-10 text-left">

        <Link  :href = "route('Users.Index')"   class="px-8 py-3 text-white font-semibold rounded-lg bg-yellow-600 hover:bg-yellow-700 transition duration-200" :disabled="form.processing">
            <fa icon = "arrow-right" /> رجوع
            </Link>
            &nbsp;

        <button type="submit" class="px-8 py-3 text-white font-semibold rounded-lg bg-blue-600 hover:bg-blue-700 transition duration-200" :disabled="form.processing">
          {{ form.processing ? '...جاري التحديث' : 'تحديث البيانات' }}
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
