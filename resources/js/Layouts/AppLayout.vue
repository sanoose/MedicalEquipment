<script setup>
import Navbar from './Navbar.vue';
import Aside from './Aside.vue';
import { ref } from 'vue';
import { usePage } from '@inertiajs/vue3'
import  * as  constants  from "@/JsFiles/constants.js" ; 
import Loading from './Loading.vue';
const page = usePage()
 

const isAsideOpen = ref(true);

const toggleAside = () => {
  isAsideOpen.value = !isAsideOpen.value;
};
</script>


<template>
  <Loading /> 
  <div dir="rtl" class="min-h-screen     text-[var(--main-text-color)]   ">
    <Navbar
    :user="page.props.auth?.user"   :constants = "constants"
      @toggle-aside="toggleAside"
      :class="{ 'transition-all duration-300': true, 'md:mr-64': isAsideOpen }"
    />

 
    <div class="flex  ">
      <main
        class="flex-1 transition-all duration-300     overflow-auto   "
        :class="{ 'md:mr-64': isAsideOpen }"
      >
 
          <div class="max-w-full  sm:px-6 lg:px-8">
            <div class="px-4 py-4 sm:px-0   ">
              <slot />
            </div>
          </div>
 
      </main>
      <Aside :is_open ="isAsideOpen"     :user="page.props.auth?.user"   :constants = "constants"  />
    </div>
  </div>
</template>

