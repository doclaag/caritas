<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import ToastNotification from '@/Components/ToastNotification.vue';
import CustomTagGreen from '@/Components/CustomTagGreen.vue';
import Search from '@/Pages/Categories/search.vue'; // Importa el nuevo componente

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

const extraField = ref(''); // Campo extra que has solicitado

const successMessage = ref('');
const displayingToken = ref(false);

const notification = ref({
  visible: false,
  message: '',
  type: 'success'
});

const showNotification = (message, type = 'success') => {
  notification.value.message = message;
  notification.value.type = type;
  notification.value.visible = true;
  setTimeout(() => {
    notification.value.visible = false;
  }, 3000);
};

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
            showNotification('Etiqueta creada con éxito');
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
        showNotification('Etiqueta actualizada con éxito');
    } catch (err) {
        etiquetaFormError.value = err.response?.data?.message || 'Error desconocido al actualizar la etiqueta';
    }
};

const deactivateEtiqueta = async (etiqueta) => {
    try {
        await axios.put(`/tags/${etiqueta.id}`, {
            estado: false
        }, { withCredentials: true });
        await loadTags();
        successMessage.value = 'Etiqueta desactivada con éxito';
        displayingToken.value = true;
        showNotification('Etiqueta desactivada con éxito');
        // Limpiar campos del formulario
        nombreEtiqueta.value = '';
        descripcionEtiqueta.value = '';
        selectedEtiqueta.value = null;
    } catch (err) {
        error.value = err.response?.data?.message || 'Error desconocido al desactivar la etiqueta';
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

const clearEtiquetaForm = () => {
    nombreEtiqueta.value = '';
    descripcionEtiqueta.value = '';
    selectedEtiqueta.value = null;
};

const etiquetas = computed(() => {
    return secundarias.value.map(etiqueta => ({
        id: etiqueta.id,
        nombre_etiqueta: etiqueta.nombre_etiqueta,
        color: 'green'
    }));
});

const selectedTags = ref([]);
const resetTags = ref(false);

const updateSelectedTags = (newSelectedTags) => {
    selectedTags.value = newSelectedTags;
    if (newSelectedTags.length === 1) {
        const selectedTag = secundarias.value.find(tag => tag.id === newSelectedTags[0]);
        if (selectedTag) {
            nombreEtiqueta.value = selectedTag.nombre_etiqueta;
            descripcionEtiqueta.value = selectedTag.descripcion_etiqueta;
            selectedEtiqueta.value = selectedTag; // Asegúrate de actualizar selectedEtiqueta
        }
    } else {
        clearEtiquetaForm();
    }
};
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
                            <Search />
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
                                    <PrimaryButton type="submit">
                                        {{ selectedRuta ? 'Actualizar Ruta' : 'Crear Ruta' }}
                                    </PrimaryButton>
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
                                <div class="flex space-x-4">
                                    <PrimaryButton type="submit">
                                        {{ selectedEtiqueta ? 'Actualizar Etiqueta' : 'Crear Etiqueta' }}
                                    </PrimaryButton>
                                    <PrimaryButton type="button" @click="clearEtiquetaForm">
                                        Limpiar Etiqueta
                                    </PrimaryButton>
                                    <PrimaryButton type="button" @click="deactivateEtiqueta(selectedEtiqueta)" v-if="selectedEtiqueta">
                                        Desactivar Etiqueta
                                    </PrimaryButton>
                                </div>

                                <div class="mt-4">
                                    <CustomTagGreen :label="'Etiquetas'" :items="etiquetas" :selected-items="selectedTags" @update-selected-items="updateSelectedTags" :reset="resetTags" />
                                </div>

                                <div v-if="etiquetaFormError" class="text-red-500 text-center mb-4">
                                    {{ etiquetaFormError }}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ToastNotification v-if="notification.visible" :message="notification.message" :type="notification.type" @close="notification.visible = false" />
    </AppLayout>
</template>