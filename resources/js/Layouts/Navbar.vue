<template>
  <header class="bg-transparent shadow border-b-5-amber-400">
    <div class="mx-auto py-6 px-4 sm:px-6 lg:px-8">
      <div class="flex justify-between items-center">
        <!-- Profile & Dark Mode -->
        <div class="relative inline-block text-left">
 
          <div class="flex gap-2">
            <button
              @click="toggleDropdown"
              type="button"
              class="cursor-pointer"
              id="menu-button"
            >
 
              <ChevronDownIcon class="w-5 h-5 text-white-500 inline-block" :class="{ 'rotate-180': is_open }" />
              <img
                :src="user.profile_photo_url"
                alt=""
                class="inline-block rounded-full h-10 w-10 overflow-hidden focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
              >
            </button>

            <!-- <DarkModeToggle
              class="switch-button cursor-pointer"
              v-model="is_dark_mode"
              @change="CheckTheme"
            /> -->
          </div>

          <!-- Dropdown Menu -->
          <div
            v-if="is_open"
            class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-[#673ab74f] ring-1 ring-black ring-opacity-5 focus:outline-none"
            role="menu"
            aria-orientation="vertical"
            aria-labelledby="menu-button"
            tabindex="-1"
          >
            <div class="py-1" role="none">
              <!-- ✅ Inertia Link -->
              <Link
                href="/Profile/Profile"
                class="nav_links"
                role="menuitem"
                tabindex="-1"
                id="menu-item-1"
                :class="{ 'bg-[var(--active-bg-color)]': $page.url.startsWith('/Profile/Profile') }"
              >
                <UserIcon class="h-5 w-5 inline-block" />
                <span class="mr-2">الملف الشخصي</span>
              </Link>

              <Link
                href="#"
                class="nav_links"
                role="menuitem"
                tabindex="-1"
                id="menu-item-2"
                @click="Logout"
              >
                <ArrowUturnRightIcon class="h-5 w-5 inline-block" />
                <span class="mr-3">تسجيل الخروج</span>
              </Link>
            </div>
          </div>
        </div>

        <!-- Toggle Aside -->
        <div class="flex items-center space-x-4">
          <button
            @click="$emit('toggle-aside')"
            class="cursor-pointer relative inline-flex items-center justify-center p-0.5 mb-2 me-2 overflow-hidden text-sm font-medium text-gray-900 rounded-lg group bg-gradient-to-br from-purple-600 to-blue-500 group-hover:from-purple-600 group-hover:to-blue-500 hover:text-white dark:text-white focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800"
          >
            <span
              class="relative px-5 py-2.5 transition-all ease-in duration-75 bg-white dark:bg-gray-900 rounded-md group-hover:bg-transparent group-hover:dark:bg-transparent"
            >
              <Bars3Icon class="w-5 h-5 text-white-500" />
            </span>
          </button>
        </div>
      </div>
    </div>
  </header>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import DarkModeToggle from '@/Components/DarkModeToggle.vue'

import {
  UserIcon,
  ArrowUturnRightIcon,
  Bars3Icon,
  ChevronDownIcon
} from '@heroicons/vue/24/outline'
import { toast } from 'vue3-toastify'


const props = defineProps({
  user: Object,
  constants: Object
})

const emit = defineEmits(['toggle-aside'])
const is_open = ref(false)
const is_dark_mode = ref(false)

const toggleDropdown = () => {
  is_open.value = !is_open.value
}

const Logout = () => {
  router.post(route('logout') , {
    onSuccess: () => {
       toast.success( "تم تسجيل الخروج بنجاح") ;
    }
  }) ;
}

const root = document.documentElement

function CheckTheme() {
  if (is_dark_mode.value === true) {
    localStorage.setItem('is_dark_mode', true)
    root.style.setProperty('--img', 'url("/images/dark_mode.jpg")')
    root.style.setProperty('--main-text-color', '#ffffff')
  } else {
    localStorage.setItem('is_dark_mode', false)
    root.style.setProperty('--img', 'url("/images/light_mode.jpg")')
    root.style.setProperty('--main-text-color', '#000000')
  }
}

onMounted(() => {
  if (localStorage.getItem('is_dark_mode') === 'true') {
    is_dark_mode.value = true
    CheckTheme()
  } else {
    is_dark_mode.value = false
    CheckTheme()
  }

  watch(is_dark_mode, () => {
    CheckTheme()
  })
})
</script>
