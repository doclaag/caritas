<script setup>
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';

const searchQuery = ref('');
const filterType = ref('all');
const principales = ref([]);
const secundarias = ref([]);
const loading = ref(true);
const error = ref(null);

const filteredItems = computed(() => {
    let items = [];
    if (filterType.value === 'all' || filterType.value === 'rutas') {
        items = items.concat(principales.value);
    }
    if (filterType.value === 'all' || filterType.value === 'etiquetas') {
        items = items.concat(secundarias.value);
    }
    if (searchQuery.value) {
        items = items.filter(item => item.nombre_categoria.toLowerCase().includes(searchQuery.value.toLowerCase()));
    }
    return items;
});

onMounted(async () => {
    try {
        await loadCategories();
        await loadTags();
    } catch (err) {
        error.value = err.message || 'Error desconocido al cargar las categorías';
    } finally {
        loading.value = false;
    }
});

const loadCategories = async () => {
    try {
        const response = await axios.get('/categories', { withCredentials: true });
        principales.value = response.data.principales;
        secundarias.value = response.data.secundarias;
    } catch (err) {
        error.value = err.response?.data?.message || 'Error desconocido al cargar las categorías';
    }
};

const loadTags = async () => {
    try {
        const response = await axios.get('/tags', { withCredentials: true });
        secundarias.value = response.data;
    } catch (err) {
        error.value = err.response?.data?.message || 'Error desconocido al cargar las etiquetas';
    }
};
</script>

<template>
    <div>
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Búsqueda</h3>
        <form class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
            <div class="mb-4 flex space-x-4">
                <div class="flex-1">
                    <label for="search" class="block text-gray-700">Buscar</label>
                    <input type="text" id="search" v-model="searchQuery" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                </div>
                <div class="flex-1">
                    <label for="filter" class="block text-gray-700">Filtrar por tipo</label>
                    <select id="filter" v-model="filterType" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <option value="all">Todos</option>
                        <option value="rutas">Rutas</option>
                        <option value="etiquetas">Etiquetas</option>
                    </select>
                </div>
            </div>
        </form>
        <div class="p-4 bg-gray-100 border border-gray-300 rounded max-h-64 overflow-y-auto">
            <div v-if="filteredItems.length === 0" class="text-center text-gray-500 mb-4">
                No hay resultados disponibles.
            </div>
            <div v-else>
                <div v-for="item in filteredItems" :key="item.id" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
                    <h3 class="font-semibold text-lg text-gray-800">{{ item.nombre_categoria }}</h3>
                    <p class="text-gray-700">{{ item.descripcion_categoria }}</p>
                </div>
            </div>
        </div>
    </div>
</template>