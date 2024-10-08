<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
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
const selectedEtiqueta = ref(null);

const activeTab = ref('rutas');
const selectedRuta = ref(null);

const searchQuery = ref('');
const filterType = ref('all');

const extraField = ref(''); // Campo extra que has solicitado

const successMessage = ref('');
const displayingToken = ref(false);

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

const submitForm = async () => {
    try {
        if (selectedEtiqueta.value) {
            // Actualizar etiqueta existente
            await updateEtiquetaForm();
        } else {
            // Crear nueva etiqueta
            const response = await axios.post('/tags', {
                nombre_etiqueta: nombreEtiqueta.value,
                descripcion_etiqueta: descripcionEtiqueta.value,
                estado: true,
                usuarios_id: 1, // Reemplazar con el ID de usuario actual
            }, { withCredentials: true });
            nombreEtiqueta.value = '';
            descripcionEtiqueta.value = '';
            etiquetaFormError.value = null;
            await loadTags();
            successMessage.value = 'Etiqueta creada con éxito';
            displayingToken.value = true;
        }
    } catch (err) {
        etiquetaFormError.value = err.response?.data?.message || 'Error desconocido al enviar el formulario';
    }
};

const updateEtiquetaForm = async () => {
    if (!selectedEtiqueta.value) return;
    try {
        await axios.put(`/tags/${selectedEtiqueta.value.id}`, {
            nombre_etiqueta: nombreEtiqueta.value,
            descripcion_etiqueta: descripcionEtiqueta.value,
        }, { withCredentials: true });
        nombreEtiqueta.value = '';
        descripcionEtiqueta.value = '';
        selectedEtiqueta.value = null;
        etiquetaFormError.value = null;
        await loadTags();
        successMessage.value = 'Etiqueta actualizada con éxito';
        displayingToken.value = true;
    } catch (err) {
        etiquetaFormError.value = err.response?.data?.message || 'Error desconocido al actualizar la etiqueta';
    }
};

const deleteEtiqueta = async (etiqueta) => {
    try {
        await axios.delete(`/tags/${etiqueta.id}`, { withCredentials: true });
        await loadTags();
        successMessage.value = 'Etiqueta eliminada con éxito';
        displayingToken.value = true;
    } catch (err) {
        error.value = err.response?.data?.message || 'Error desconocido al eliminar la etiqueta';
    }
};

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

const selectRuta = (ruta) => {
    selectedRuta.value = ruta;
};

const selectEtiqueta = (etiqueta) => {
    selectedEtiqueta.value = etiqueta;
    nombreEtiqueta.value = etiqueta.nombre_etiqueta;
    descripcionEtiqueta.value = etiqueta.descripcion_etiqueta;
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
                        <!-- Tab Búsqueda -->
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

                        <!-- Tab Rutas -->
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
                                        <div v-for="category in principales.slice(0, 5)" :key="category.id" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded cursor-pointer" @click="selectRuta(category)">
                                            <h3 class="font-semibold text-lg text-gray-800">{{ category.nombre_categoria }}</h3>
                                            <p class="text-gray-700">{{ category.descripcion_categoria }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                                        {{ selectedRuta ? 'Actualizar Ruta' : 'Crear Ruta' }}
                                    </button>
                                </div>

                                <div v-if="formError" class="text-red-500 text-center mb-4">
                                    {{ formError }}
                                </div>
                            </form>
                        </div>

                        <!-- Tab Etiquetas -->
                        <div v-if="activeTab === 'etiquetas'">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Etiquetas</h3>
                            
                            <form @submit.prevent="submitForm" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
                                <div class="mb-4">
                                    <label for="nombre_etiqueta" class="block text-gray-700">Nombre de la Etiqueta</label>
                                    <input type="text" id="nombre_etiqueta" v-model="nombreEtiqueta" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                </div>
                                <div class="mb-4">
                                    <label for="descripcion_etiqueta" class="block text-gray-700">Descripción</label>
                                    <textarea id="descripcion_etiqueta" v-model="descripcionEtiqueta" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                                </div>
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-black-700">
                                        {{ selectedEtiqueta ? 'Actualizar Etiqueta' : 'Crear Etiqueta' }}
                                    </button>

                                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-black hover:bg-black-700">
                                        Limpiar Etiqueta 
                                    </button>

                                <div 
                                class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded max-h-64 overflow-y-auto">
                                    <h3 class="font-semibold text-lg text-gray-800 mb-4">Etiquetas Creadas</h3>
                                    <div v-if="secundarias.length === 0" class="text-center text-gray-500 mb-4">
                                        No hay etiquetas disponibles.
                                    </div>
                                    <div v-else>
                                        <div v-for="etiqueta in secundarias" :key="etiqueta.id" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded cursor-pointer" @click="selectEtiqueta(etiqueta)">
                                            <h3 class="font-semibold text-lg text-gray-800">{{ etiqueta.nombre_etiqueta }}</h3>
                                            <p class="text-gray-700">{{ etiqueta.descripcion_etiqueta }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    
                                </div>

                                <div v-if="etiquetaFormError" class="text-red-500 text-center mb-4">
                                    {{ etiquetaFormError }}
                                </div>
                            </form>
                        </div>
                    </div>

                    <div v-if="successMessage" class="text-green-500 text-center">
                        {{ successMessage }}
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
