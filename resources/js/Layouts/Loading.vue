<script setup>
import { ref } from "vue";
import { router } from '@inertiajs/vue3'

let is_loading = ref(false);
router.on("start", () => {
  is_loading.value = true;
});
router.on("finish", () => {
  is_loading.value = false;
});
</script>

<template>
  <transition
    name="fade"
    :duration="{ enter: 500, leave: 800 }"
    v-if="is_loading"
  >
    <div class="loader-overlay">
      <div class="loader-container">
        <div class="loader-content">
          <span class="loader"></span>
          <h3 class="loader-text">... الرجاء الإنتظار</h3>
        </div>
      </div>
    </div>
  </transition>
</template>

<style scoped>
/* تأثيرات الانتقال */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.5s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

/* تصميم اللودر */
.loader-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.7);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  backdrop-filter: blur(3px);
}

.loader-container {
  background: white;
  padding: 2rem 3rem;
  border-radius: 12px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
  text-align: center;
  max-width: 90%;
  width: 300px;
}

.loader-content {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1rem;
}

.loader {
  width: 50px;
  height: 50px;
  border: 4px solid #f3f3f3;
  border-top: 4px solid #3b82f6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
}

.loader-text {
  color: #333;
  font-size: 1.1rem;
  margin: 0;
  font-weight: 500;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}
</style>