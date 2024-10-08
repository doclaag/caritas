<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'
import { CheckCircle, XCircle, Info, AlertTriangle, X } from 'lucide-vue-next'

const props = defineProps<{
  type: 'success' | 'error' | 'info' | 'warning'
  message: string
  duration?: number
}>()

const emit = defineEmits(['close'])

const isVisible = ref(true)
const timer = ref<number | null>(null)

const icons = {
  success: CheckCircle,
  error: XCircle,
  info: Info,
  warning: AlertTriangle,
}

const colors = {
  success: 'bg-green-500',
  error: 'bg-red-600',
  info: 'bg-blue-500',
  warning: 'bg-yellow-500',
}

const titles = {
  success: 'Éxito',
  error: 'Error',
  info: 'Info',
  warning: 'Advertencia',
}

onMounted(() => {
  const duration = props.duration ?? 5000
  timer.value = setTimeout(() => {
    isVisible.value = false
    emit('close')
  }, duration)
})

onUnmounted(() => {
  if (timer.value) {
    clearTimeout(timer.value)
  }
})

const close = () => {
  isVisible.value = false
  emit('close')
}
</script>

<template>
  <Transition name="fade">
    <div v-if="isVisible" :class="['fixed top-4 right-4 max-w-sm w-full shadow-lg rounded-lg pointer-events-auto ring-1 ring-black ring-opacity-5 overflow-hidden', colors[type]]">
      <div class="p-4">
        <div class="flex items-start">
          <div class="flex-shrink-0">
            <component :is="icons[type]" :class="['h-6 w-6 text-white']" />
          </div>
          <div class="ml-3 w-0 flex-1 pt-0.5">
            <p class="text-sm font-medium text-white">{{ titles[type] }}</p>
            <p class="mt-1 text-sm text-white">{{ message }}</p>
          </div>
          <div class="ml-4 flex-shrink-0 flex">
            <button
              @click="close"
              class="bg-white rounded-md inline-flex text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
              <span class="sr-only">Close</span>
              <X class="h-5 w-5" />
            </button>
          </div>
        </div>
      </div>
    </div>
  </Transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
