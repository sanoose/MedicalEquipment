<script setup>
import { ref } from 'vue';
 
 
import InputField from '@/Components/Fields/InputField.vue';
 
import { EnvelopeIcon, LockClosedIcon , ArrowLongRightIcon  } from '@heroicons/vue/24/outline'; // <-- هذا هو التعديل الوحيد
import { useForm   , Link, Head } from '@inertiajs/vue3';
 
import { toast } from 'vue3-toastify';
import Loading from "@/Layouts/Loading.vue" ; 
 

const form = useForm({
  email: 's',
  password: '1',
  remember: true
});

const Login = () => {
        form.post( route('login') ,  { preserveState : true , preserveScroll : true  , 
             onSuccess : () => {  
                // toast.success( "تم تسجيل الدخول بنجاح")  ;
             } ,
             onError : (err) => { toast.info( form.errors.email)  ; }   ,
             onFinish: () => {  }
        })
}
 
 
</script>

<template>
  <!-- <AuthHeader /> -->

  <Head :title="'تسجيل الدخول'" />
  
  <Loading   /> 
  <div class="min-h-screen flex items-center justify-center text-[var(--main-text-color)]">
    <div class="max-w-6xl w-full mx-auto shadow-xl rounded-lg overflow-hidden">
      <div class="md:flex">
         <div class="hidden md:block md:w-1/2">
          <div class="h-full flex items-center justify-center p-12">
          <Link :href="route('Dashboard')">    <img  src="/logo/logo.png"  alt="تسجيل الدخول" class="rounded-lg object-cover    w-80" /> </Link> 
    
          </div>
        </div>
 

         <div class="w-full md:w-1/2 p-8 sm:p-12">
          <div class="text-center mb-8">
            <h1 class="text-3xl font-bold">تسجيل الدخول        </h1>
            <p class="mt-2">أدخل بياناتك للوصول إلى حسابك</p>
          </div>

          <form class="space-y-6" @submit.prevent="Login">
            <InputField label="البريد الإلكتروني" v-model="form.email" type="text" placeholder="example@email.com"
                    autocomplete="username" required :icon="EnvelopeIcon"  dir="rtl"  :error="form.errors.email"
                    />

            <InputField label="كلمة المرور" v-model="form.password" type="password" placeholder="********" autocomplete="current-password"
             :icon="LockClosedIcon"  dir="rtl" :error="form.errors.password"
            />
     
            <div class="flex items-center justify-between">
              <div class="flex items-center">
                <input
                  id="remember-me" v-model="form.remember" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                />
                <label for="remember-me" class="mr-2 block text-sm">تذكرني</label>
              </div>

              <div class="text-sm">
                <Link :href="route('ForgotPassword')"  class="font-medium text-indigo-600 hover:text-indigo-500" > نسيت كلمة المرور ؟ </Link> 
              </div>
            </div>
            
            <div class="space-y-4">
   <button 
    type="submit" 
    class="w-full mt-4 flex justify-center items-center py-2.5 px-5 text-white font-medium rounded-lg bg-[#32506c] hover:bg-[#2a4459] transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-md"
  >
    تسجيل الدخول
  </button>
  
 
</div>
 
          </form>

     
        </div>
      </div>

    </div>
  </div>
</template>