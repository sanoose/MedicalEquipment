<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { toast } from 'vue3-toastify';
defineProps({
    status: String,
});

const form = useForm({
    email: '',
});

const submit = () => {
    form.post(route('password.email')  , {
        onSuccess: () => {
            toast.success('تم إرسال رابط إعادة تعيين كلمة المرور إلى بريدك الإلكتروني.');
        },
        onError: () => {
            toast.error(' حدث خطأ لم يتم ارسال رابط إعادة التعيين.');
        },
    });
};
</script>

<template >
    <Head title="Forgot Password" />

    <AuthenticationCard  dir="rtl" >
      

        <div class="mb-4 text-sm text-gray-600">
            <div  > <Link  href="/"><img src="/logo/logo.png"    alt="تسجيل الدخول"  class=" block m-auto  rounded-lg object-cover  w-40"  />   </Link>  </div>
            <br>
            هل نسيت كلمة مرورك؟ لا مشكلة. ما عليك سوى تزويدنا بعنوان بريدك الإلكتروني وسنرسل إليك رابط إعادة تعيين كلمة المرور لاختيار كلمة مرور جديدة.
        </div>

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit"  >
            <div>
                <InputLabel for="email" value="الإيميل" />
                <TextInput
                    id="email"
                    v-model="form.email"
                    type="email"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="username"
                />
                <InputError class="mt-2" :message="form.errors.email" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link  href="/login" class="text-white bg-gradient-to-r from-cyan-500 to-blue-500 hover:bg-gradient-to-bl focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2"> 
                       رجوع </Link>  
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    ارسال رابط إعادة تعيين كلمة المرور 
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>
