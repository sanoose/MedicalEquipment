<script setup>
import { usePage, Link } from '@inertiajs/vue3'
import { ref, computed } from 'vue'

const page = usePage()
const props = defineProps({
  user: Object,
  constants: Object,
  is_open: {
    type: Boolean,
    default: true
  }
})

const currentUrl = computed(() => page.url)
const role = computed(() => props.user.role)

const hasPermission = (allowedRole) => {
   return allowedRole.includes(role.value)
}

const menuItems = ref([
      { 
        name: 'لوحة التحكم', 
        path: 'Dashboard', 
        icon: 'home',
        allowedRole: [1, 2, 3, 4]
      },
   
      { 
        name: 'إدارة المستخدمين', 
        icon: 'users',
        allowedRole: [1, 2, 3],
        subItems: [
          { name: 'اضافة مستخدم', path: 'Users.Create', icon: 'plus' },
          { name: 'عرض المستخدمين', path: 'Users.Index', icon: 'eye' }
        ]
      },
     
      { 
        name: 'إدارة العملاء', 
        icon: 'fa-hospital',
        allowedRole: [1, 2, 3],
        subItems: [
          { name: 'اضافة عميل جديد', path: 'Clients.create', icon: 'plus' },
          { name: 'عرض بيانات العملاء', path: 'Clients.index', icon: 'eye' }
        ]
      },
        { 
          name: 'إدارة المعدات ',
          icon: 'tools',
          allowedRole: [1, 2, 3],
          subItems: [
            { name: 'إضافة جهاز جديد', path: 'EquipmentsCards.create', icon: 'plus' },
            { name: 'عرض الأجهزة', path: 'EquipmentsCards.index', icon: 'eye' }
          ]
        },
     
        { 
          name: 'إدارة المستلزمات ',
          icon: 'first-aid',
          allowedRole: [1, 2, 3],
          subItems: [
            { name: 'إضافة مستلزم جديد', path: 'SuppliesCards.create', icon: 'plus' },
            { name: 'عرض المستلزمات', path: 'SuppliesCards.index', icon: 'eye' }
          ]
        },
     
        { 
          name: 'إدارة طلبات الصيانة  ',
          icon: 'gear',
          allowedRole: [1, 2, 3],
          subItems: [
            { name: 'إضافة طلب جديد', path: 'EquipmentsMaintenances.create', icon: 'plus' },
            { name: 'عرض الطلبات', path: 'EquipmentsMaintenances.index', icon: 'eye' }
          ]
        },
        { 
          name: 'إدارة طلبات المعدات   ',
          icon: 'clipboard-check',
          allowedRole: [1, 2, 3],
          subItems: [
            { name: 'إضافة طلب جديد', path: 'EquipmentsOrders.create', icon: 'plus' },
            { name: 'عرض الطلبات', path: 'EquipmentsOrders.index', icon: 'eye' }
          ]
        },
        { 
          name: 'إدارة طلبات المستلزمات    ',
          icon: 'hand-holding-medical',
          allowedRole: [1, 2, 3],
          subItems: [
            { name: 'إضافة طلب جديد', path: 'SuppliesOrders.create', icon: 'plus' },
            { name: 'عرض الطلبات', path: 'SuppliesOrders.index', icon: 'eye' }
          ]
        },
     
 

      { name: 'تسجيل الخروج', path: 'logout', icon: 'reply'  ,   method: 'post'  ,   allowedRole: [1, 2 , 3, 4 ],  },
 

 ])

const openSubmenus = ref({})
const is_guide_menu_open = ref(false)

const IsSubmenuOpen = (index) => {
  return openSubmenus.value[index]
}

const ToggleSubmenu = (index) => {
  openSubmenus.value[index] = !openSubmenus.value[index]
}
</script>

<template>
  <aside
    class="w-64 space-y-6 py-7 px-2 absolute inset-y-0 right-0 transform transition duration-200 ease-in-out"
    :class="{
      'block md:translate-x-0': is_open,
      'hidden md:block md:translate-x-full': !is_open
    }"
  >
    <!-- Logo Section -->
    <div class="flex flex-col  items-center justify-center   h-20">
      <img src="/logo/logo.png" alt="Logo" class="h-16" />
     

      

<div class="relative inline-block">
  <!-- توهج خلفي ثابت وأنيق -->
  <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 via-purple-500/20 to-cyan-500/20 
              blur-2xl rounded-lg"></div>
  
  <!-- توهج أمامي ناعم جداً -->
  <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/10 to-transparent 
              blur-sm rounded-lg"></div>
  
  <!-- النص -->
  <p class="text-sm text-center bg-gradient-to-r from-blue-400 via-cyan-300 to-blue-400 
            bg-clip-text text-transparent font-medium relative z-10
            drop-shadow-[0_0_12px_rgba(34,211,238,0.5)]">
    الهيئة العامة للمعدات والمستلزمات والأجهزة الطبية
  </p>
</div>



    </div>
    <br>
    <!-- User Info -->
    <div class="text-center"> 
      <p class="font-bold">الاسم : {{ props.user.name }}</p>
      <p class="text-sm">الرتبة: {{ constants.role[props.user.role] }}</p>
    </div>
    <nav>
      <ul class="space-y-2">
        <template v-for="(menuItem, index) in menuItems" :key="index">
          <!-- التحقق من الصلاحية قبل العرض -->
          <template v-if="hasPermission(menuItem.allowedRole)">
         
            <!-- العناصر ذات القوائم الفرعية -->
            <li v-if="menuItem.subItems">
              <button 
                v-if="!menuItem.is_guide_menu || is_guide_menu_open"
                @click="ToggleSubmenu(index)"
                class="aside_links w-full cursor-pointer"
                :class="{
                  'bg-[var(--active-bg-color)]': IsSubmenuOpen(index),
                }"
              >
                <fa :icon="menuItem.icon" class="w-5 h-5" />
                <span class="flex-1 mr-3 text-right whitespace-nowrap">
                  {{ menuItem.name }}
                </span>
                <fa :icon="'chevron-down'" 
                  class="w-4 h-4 transition-transform duration-200"
                  :class="{ 'rotate-180': IsSubmenuOpen(index) }"
                />
              </button>

              <Transition
                enter-active-class="transition duration-200 ease-out"
                enter-from-class="transform scale-95 opacity-0"
                enter-to-class="transform scale-100 opacity-100"
                leave-active-class="transition duration-200 ease-in"
                leave-from-class="transform scale-100 opacity-100"
                leave-to-class="transform scale-95 opacity-0"
              >
                <ul
                  v-if="IsSubmenuOpen(index) && (!menuItem.is_guide_menu || is_guide_menu_open)"
                  class="mr-4 mt-1 space-y-1 overflow-hidden"
                >
                  <li
                    v-for="(subItem, subIndex) in menuItem.subItems"
                    :key="subIndex"
                  >
                    <Link
                      :href="route(subItem.path)"
                      class="aside_links"
                      :class="{
                        'bg-[var(--active-bg-color)]': route().current(subItem.path)
                      }" 
                   
                    >
                      <fa :icon="subItem.icon" class="w-4 h-4" />
                      <span class="mr-3">{{ subItem.name }}</span>
                    </Link>
                  </li>
                </ul>
              </Transition>
            </li>

             <li v-else>
              <Link 
                v-if="menuItem.path != null"
                :href="route(menuItem.path)"
                class="aside_links w-full"
                :class="{
                  'bg-[var(--active-bg-color)]': route().current(menuItem.path) || currentUrl.startsWith(menuItem.path),
                }"
                    :method="menuItem?.method || 'get'"
              >
                <fa :icon="menuItem.icon" class="w-5 h-5" />
                <span class="mr-3">{{ menuItem.name }}</span>
              </Link>

              <button
                v-else
                class="aside_links w-full"
                :class="{ 'bg-blue-500': is_guide_menu_open }"
                @click="is_guide_menu_open = !is_guide_menu_open"
              >
                <fa :icon="menuItem.icon" class="w-5 h-5" />
                <span class="mr-3">{{ menuItem.name }}</span>
              </button>
            </li>
          </template>
        </template>
      </ul>
    </nav>
  </aside>
</template>

