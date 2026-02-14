<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Array,
    from: Number,
    to: Number,
    total: Number,
    currentPage: Number,
    lastPage: Number
});
</script>

<template>
    <div class="bg-gray-50 px-6 py-3 flex items-center justify-between border-t border-gray-200">
        <!-- Mobile Pagination -->
        <div class="flex-1 flex items-center justify-between sm:hidden space-x-4 rtl:space-x-reverse">
            <!-- معلومات الصفحة للهاتف -->
            <div class="text-sm text-gray-700">
                <span class="font-medium">{{ from }}   -    {{ to }}   </span> من
                <span class="font-medium">{{ total }}</span>
            </div>

            <!-- أزرار التنقل للهاتف -->
            <div class="flex space-x-2 rtl:space-x-reverse">
                <Link 
                    v-if="currentPage > 1"
                    :href="links[0].url"
                    class="px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    preserve-scroll
                >
                    السابق
                </Link>
                <Link 
                    v-if="currentPage < lastPage"
                    :href="links[links.length - 1].url"
                    class="px-3 py-1.5 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                    preserve-scroll
                >
                    التالي
                </Link>
            </div>
        </div>

        <!-- Desktop Pagination -->
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between ">
            <!-- معلومات الصفحة لسطح المكتب (نفس المحتوى لكن بتنسيق مختلف) -->
            <div class="text-sm text-gray-700">
                عرض
                <span class="font-medium">{{ from }}</span>
                إلى
                <span class="font-medium">{{ to }}</span>
                من
                <span class="font-medium">{{ total }}</span>
                نتائج
            </div>

            <!-- أزرار الترقيم لسطح المكتب -->
            <div class="flex items-center space-x-6 rtl:space-x-reverse">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <Link 
                        v-for="(link, index) in links"
                        :key="index"
                        :href="link.url || '#'"
                        :class="{
                            'bg-[#32506c] text-white border-[#32506c]': link.active,
                            'bg-white border-gray-300 text-gray-500 hover:bg-gray-50': !link.active && link.url,
                            'hidden': !link.url,
                            'rounded-r-md': index === 0,
                            'rounded-l-md': index === links.length - 1
                        }"
                        class="relative inline-flex items-center px-4 py-2 border text-sm font-medium"
                        preserve-scroll
                        :disabled="!link.url"
                    >
                        <span v-if="index === 0">
                            <i class="fas fa-chevron-right"></i>
                        </span>
                        <span v-else-if="index === links.length - 1">
                            <i class="fas fa-chevron-left"></i>
                        </span>
                        <span v-else>
                            {{ link.label }}
                        </span>
                    </Link>
                </nav>
            </div>
        </div>
    </div>
</template>