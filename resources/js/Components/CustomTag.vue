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

const toggleItem = (itemId) => {
    const newSelectedItems = [...props.selectedItems];
    const index = newSelectedItems.indexOf(itemId);
    if (index > -1) {
        newSelectedItems.splice(index, 1);
    } else {
        newSelectedItems.push(itemId);
    }
    emit('update-selected-items', newSelectedItems);
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
                <div v-for="item in items" :key="item.id" class="px-3 py-1 rounded-full text-sm cursor-pointer"
                    :class="selectedItems.includes(item.id) ? 'bg-green-500 text-white' : 'bg-gray-200'"
                    @click="toggleItem(item.id)">
                    {{ item.nombre_etiqueta }}
                </div>
            </div>
        </div>
    </div>
</template>
