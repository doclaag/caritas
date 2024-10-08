<script setup>
import { defineProps, watch } from 'vue';

const props = defineProps({
    label: {
        type: String,
        required: true
    },
    items: {
        type: Array,
        required: true
    },
    selectedItems: {
        type: Array,
        required: true
    },
    reset: {
        type: Boolean,
        default: false
    }
});

const emit = defineEmits(['update-selected-items']);

const selectItem = (itemId) => {
    emit('update-selected-items', [itemId]);
};

// Watch for changes in the reset prop to clear selected items
watch(() => props.reset, (newVal) => {
    if (newVal) {
        emit('update-selected-items', []);
    }
});
</script>

<template>
    <div class="space-y-8 p-4">
        <div class="space-y-2">
            <label class="text-sm font-medium">{{ label }}</label>
            <div class="flex flex-wrap gap-2">
                <div v-for="item in items" :key="item.id" class="px-3 py-1 rounded-full text-sm cursor-pointer bg-green-500 text-white"
                    :class="{ 'ring-2 ring-green-700': selectedItems.includes(item.id) }"
                    @click="selectItem(item.id)">
                    {{ item.nombre_etiqueta }}
                </div>
            </div>
        </div>
    </div>
</template>