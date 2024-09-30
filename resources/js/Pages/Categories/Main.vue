<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import CategoryTable from '@/Components/CategoryTable.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const principales = ref([]);
const secundarias = ref([]);
const loading = ref(true);
const error = ref(null);

const nombreCategoria = ref('');
const descripcionCategoria = ref('');
const formError = ref(null);

const nombreEtiqueta = ref('');
const descripcionEtiqueta = ref('');
const etiquetaFormError = ref(null);

const activeTab = ref('rutas');
const selectedRuta = ref(null);
const selectedIndex = ref(null);

const searchQuery = ref('');
const filterType = ref('all');

onMounted(async () => {
    try {
        const response = await axios.get('/categories', { withCredentials: true });
        principales.value = response.data.principales;
        secundarias.value = response.data.secundarias;
    } catch (err) {
        error.value = err.message || 'Error desconocido al cargar las categorías';
    } finally {
        loading.value = false;
    }
});

const submitForm = async () => {
    try {
        const response = await axios.post('/categories', {
            nombre_categoria: nombreCategoria.value,
            descripcion_categoria: descripcionCategoria.value,
        }, { withCredentials: true });
        nombreCategoria.value = '';
        descripcionCategoria.value = '';
        formError.value = null;
        await loadCategories();
    } catch (err) {
        formError.value = err.message || 'Error desconocido al enviar el formulario';
    }
};

const submitEtiquetaForm = async () => {
    try {
        const response = await axios.post('/categories', {
            nombre_categoria: nombreEtiqueta.value,
            descripcion_categoria: descripcionEtiqueta.value,
        }, { withCredentials: true });
        nombreEtiqueta.value = '';
        descripcionEtiqueta.value = '';
        etiquetaFormError.value = null;
        await loadCategories();
    } catch (err) {
        etiquetaFormError.value = err.message || 'Error desconocido al enviar el formulario';
    }
};

const loadCategories = async () => {
    try {
        const response = await axios.get('/categories', { withCredentials: true });
        principales.value = response.data.principales;
        secundarias.value = response.data.secundarias;
    } catch (err) {
        error.value = err.message || 'Error desconocido al cargar las categorías';
    }
};

const handleSelectCategory = (index) => {
    selectedIndex.value = index;
};

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
</script>

<template>
    <AppLayout title="Categories">
        <template #header>
            <nav class="flex space-x-4 mt-4">
                <button @click="activeTab = 'busqueda'" :class="{'text-blue-500': activeTab === 'busqueda'}">Búsqueda</button>
                <button @click="activeTab = 'rutas'" :class="{'text-blue-500': activeTab === 'rutas'}">Rutas</button>
                <button @click="activeTab = 'etiquetas'" :class="{'text-blue-500': activeTab === 'etiquetas'}">Etiquetas</button>
            </nav>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div v-if="error" class="text-center text-red-500">
                        {{ error }}
                    </div>

                    <div v-if="loading" class="text-center text-gray-500">
                        Cargando categorías...
                    </div>

                    <div v-else>
                        <div v-if="activeTab === 'busqueda'">
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

                        <div v-if="activeTab === 'rutas'">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Rutas</h3>
                            <form @submit.prevent="submitForm" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
                                <div class="mb-4">
                                    <label for="nombre_categoria" class="block text-gray-700">Nombre de la Ruta</label>
                                    <input type="text" id="nombre_categoria" v-model="nombreCategoria" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                </div>
                                <div class="mb-4">
                                    <label for="descripcion_categoria" class="block text-gray-700">Descripción</label>
                                    <textarea id="descripcion_categoria" v-model="descripcionCategoria" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                                </div>
                                <div class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded max-h-64 overflow-y-auto">
                                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Rutas Creadas</h3>
                                    <div v-if="principales.length === 0" class="text-center text-gray-500 mb-4">
                                        No hay rutas disponibles.
                                    </div>
                                    <div v-else>
                                        <div v-for="category in principales.slice(0, 5)" :key="category.id" @click="selectRuta(category)" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded cursor-pointer hover:bg-gray-200">
                                            <h3 class="font-semibold text-lg text-gray-800">{{ category.nombre_categoria }}</h3>
                                            <p class="text-gray-700">{{ category.descripcion_categoria }}</p>
                                        </div>
                                    </div>
                                </div>
                                <div v-if="formError" class="text-center text-red-500 mb-4">
                                    {{ formError }}
                                </div>
                                <PrimaryButton type="submit">Crear Ruta</PrimaryButton>
                            </form>
                            <div v-if="selectedRuta" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
                                <h3 class="font-semibold text-lg text-gray-800 mb-4">Detalles de la Ruta</h3>
                                <p><strong>Nombre:</strong> {{ selectedRuta.nombre_categoria }}</p>
                                <p><strong>Descripción:</strong> {{ selectedRuta.descripcion_categoria }}</p>
                            </div>
                        </div>

                        <div v-if="activeTab === 'etiquetas'">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Etiquetas</h3>
                            <CategoryTable 
                                :categories="principales" 
                                :selectedIndex="selectedIndex"
                                :nombreEtiqueta="nombreEtiqueta"
                                :descripcionEtiqueta="descripcionEtiqueta"
                                :etiquetaFormError="etiquetaFormError"
                                @select-category="handleSelectCategory"
                                @update:nombreEtiqueta="nombreEtiqueta = $event"
                                @update:descripcionEtiqueta="descripcionEtiqueta = $event"
                                @submit-etiqueta-form="submitEtiquetaForm"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <DialogModal :show="displayingToken" @close="displayingToken = false">
            <template #title>
                Éxito
            </template>
            <template #content>
                <div>
                    Su archivo ha sido agregado con éxito.
                </div>
            </template>
            <template #footer>
                <PrimaryButton @click="displayingToken = false">
                    Cerrar
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>