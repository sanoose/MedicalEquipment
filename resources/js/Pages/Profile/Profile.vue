<script setup>
import { Head, useForm, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import { toast } from 'vue3-toastify';
import  * as  constants  from "@/JsFiles/constants.js" ; 
defineOptions({
    layout: AppLayout
});

const props = defineProps({
    user: Object
});

const form = useForm({
    name: props.user.name,
    email: props.user.email,
    current_password: '',
    password: '',
    password_confirmation: '',
    profile_photo: null,
});

const photoPreview = ref(props.user.profile_photo_url);
const photoInput = ref(null);

const selectNewPhoto = () => {
    photoInput.value.click();
};

const UpdatePhotoPreview = (event) => {
    const photo = event.target.files[0];
    
    if (!photo) return;
    
    form.profile_photo = photo;
    
    const reader = new FileReader();
    reader.onload = (e) => {
        photoPreview.value = e.target.result;
    };
    reader.readAsDataURL(photo);
};

const ProfileUpdate = () => {
    form.post(route('Profile.ProfileUpdate'), {
        preserveScroll: true,
        onSuccess: () => {
            form.reset('password', 'password_confirmation');
            if (form.profile_photo) {
                router.reload();
            }
            toast.success("تم تحديث بياناتك بنجاح") ;
        },
        onError: () => { 
            console.log(form.errors);
            
            toast.error("حدث خطأ في تحديث البيانات ") ;
        }
    });
};
</script>

<template>
    
  <Head title="الملف الشخصي للأدمن" />

  <div class="min-h-screen bg-white text-right">
    <div class="">
      <div class="max-w-4xl mx-auto ">
        <div class="bg-white overflow-hidden">
          <!-- Header with Photo -->
          <div class="px-6 py-6 border-b border-gray-200">
            <div class="flex items-center justify-start space-x-4 space-x-reverse">
              <div>
                <h1 class="text-2xl font-bold text-gray-800">{{ user.name }}</h1>
                <!-- <p class="text-gray-600">{{ user.email }}</p> -->
                <span class="inline-block mt-2 px-3 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs">
                    {{ constants.user_level[user.user_level] }}
                     
                </span>
              </div>
              <div class="relative group">
                <img 
                  :src="photoPreview" 
                  :alt="user.name"
                  class="h-24 w-24 rounded-full object-cover border-4 border-gray-100 shadow-sm"
                />
                <div class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-50 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                  <button
                    type="button"
                    @click="selectNewPhoto"
                    class="p-2 bg-indigo-600 rounded-full text-white hover:bg-indigo-700 focus:outline-none"
                  >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                  </button>
                </div>
                <input
                  ref="photoInput"
                  type="file"
                  class="hidden"
                  @change="UpdatePhotoPreview"
                  accept="image/*"
                />
              </div>
            </div>
          </div>

          <!-- Form -->
          <div class="px-6 py-6">
            <form @submit.prevent="ProfileUpdate" class="space-y-8">
              <div>
                <h2 class="text-xl font-semibold text-gray-800">تحديث الملف الشخصي</h2>
                <!-- <p class="mt-1 text-sm text-gray-600">قم بتحديث معلومات حسابك الشخصية</p> -->
              </div>

              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                  <label for="name" class="block text-sm font-medium text-gray-700 text-right">الاسم الكامل</label>
                  <input
                    type="text"
                    id="name"
                    v-model="form.name"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right"
                  />
                  <p v-if="form.errors.name" class="mt-2 text-sm text-red-600 text-right">{{ form.errors.name }}</p>
                </div>

                <!-- Email -->
                <div>
                  <label for="email" class="block text-sm font-medium text-gray-700 text-right">البريد الإلكتروني</label>
                  <input
                    type="text"
                    id="email"
                    v-model="form.email"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right"
                  />
                  <p v-if="form.errors.email" class="mt-2 text-sm text-red-600 text-right">{{ form.errors.email }}</p>
                </div>

                <!-- Current Password -->
                <div>
                  <label for="current_password" class="block text-sm font-medium text-gray-700 text-right">كلمة المرور الحالية</label>
                  <input
                    type="password"
                    id="current_password"
                    autocomplete="off"
                    v-model="form.current_password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right"
                  />
                  <p v-if="form.errors.current_password" class="mt-2 text-sm text-red-600 text-right">{{ form.errors.current_password }}</p>
                </div>

                <!-- New Password -->
                <div>
                  <label for="password" class="block text-sm font-medium text-gray-700 text-right">كلمة المرور الجديدة</label>
                  <input
                            autocomplete="off"
                    type="password"
                    id="password"
                    v-model="form.password"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right"
                  />
                  <p v-if="form.errors.password" class="mt-2 text-sm text-red-600 text-right">{{ form.errors.password }}</p>
                </div>

                <!-- Confirm Password -->
                <div>
                  <label for="password_confirmation" class="block text-sm font-medium text-gray-700 text-right">تأكيد كلمة المرور</label>
                  <input
                            autocomplete="off"
                    type="password"
                    id="password_confirmation"
                    v-model="form.password_confirmation"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm text-right"
                  />
                </div>
              </div>

              <div class="flex justify-start pt-6">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="inline-flex items-center px-6 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition ease-in-out duration-150"
                >
                  <span v-if="form.processing">
                    <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    جاري الحفظ...
                  </span>
                  <span v-else>
                    حفظ التغييرات
                  </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>