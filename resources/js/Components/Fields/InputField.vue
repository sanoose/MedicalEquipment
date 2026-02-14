<script setup>
import { computed } from 'vue';

const props = defineProps({
  modelValue: [String, Number],
  label: String,
  type: {
    type: String,
    default: 'text'
  },
  placeholder: String,
  required: Boolean,
  disabled: Boolean,
  error: String,
  hint: String,
  icon: Function  , // اسم أيقونة Heroicon (مثل 'Envelope')
  dir: {
    type: String,
    default: 'rtl'
  }
});

const emit = defineEmits(['update:modelValue']);

const inputClass = computed(() => [
  'w-full px-4 py-2 border rounded-md focus:outline-none ',
  props.error ? 'border-red-500' : 'border-gray-300 focus:ring-2 focus:ring-blue-500',
  props.disabled ? 'bg-gray-100 cursor-not-allowed' : 'bg-white',
  props.icon ? 'pl-10' : '',
  props.dir === 'rtl' ? 'text-right' : 'text-left'
]);
</script>

<template>
  <div class="mb-4" :dir="dir">
    <label v-if="label" class="block text-sm font-medium text-gray-700 mb-1">
      {{ label }}
      <span v-if="required" class="text-red-500">*</span>
    </label>
    
    <div class="relative">
      <!-- إضافة أيقونة Heroicon إذا وجدت -->
      <div v-if="icon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
        <component :is="icon" class="h-5 w-5  " />
      </div>
      
      <input
        :type="type"
        :value="modelValue"
        @input="emit('update:modelValue', $event.target.value)"
        :placeholder="placeholder"
        :required="required"
        :disabled="disabled"
        :class="inputClass"
        autocomplete="off"
      />
    </div>
    
    <p v-if="error" class="mt-1 text-sm text-red-600">{{ error }}</p>
    <p v-if="hint && !error" class="mt-1 text-sm text-gray-500">{{ hint }}</p>
  </div>
</template>


 