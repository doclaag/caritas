<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import CustomLabel from '@/Components/CustomLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import NavLink from '@/Components/NavLink.vue';
import axios from 'axios';
import ToastNotification from '@/Components/ToastNotification.vue';
import DialogModal from '@/Components/DialogModal.vue';

const categoriasPrincipales = ref([]);
const etiquetas = ref([]);
const selectedTags = ref([]);
const showModal = ref(false);
const modalMessage = ref('');
const selectedFile = ref(null);
const form = ref({
    estado: 0,
    publico: 0,
    categoria: '',
    tag: '',
});
const notifications = ref([]);

onMounted(async () => {
    try {
        const response = await axios.get('/categories');
        categoriasPrincipales.value = response.data.principales;

        const etiquetasResponse = await axios.get('/tags');
        etiquetas.value = etiquetasResponse.data;
        console.log('Etiquetas cargadas:', etiquetas.value);
    } catch (error) {
        console.error('Error al cargar categorías o etiquetas:', error);
    }
});

const handleFileUpload = (event) => {
    const file = event.target.files[0];
    if (file) {
        const validTypes = ['application/pdf'];
        if (!validTypes.includes(file.type)) {
            addNotification('error', 'Tipo de archivo no permitido. Solo se permiten archivos PDF.');
            return;
        }
        selectedFile.value = file;
    }
};

const validateAndUploadFile = async () => {
    if (!selectedFile.value) {
        addNotification('error', 'Por favor, selecciona un archivo primero.');
        return;
    }
    if (!form.value.categoria) {
        addNotification('error', 'Por favor, selecciona una categoría.');
        return;
    }

    try {
        await uploadFile();
    } catch (error) {
        if (error.response && error.response.status === 409) {
            showModal.value = true;
            modalMessage.value = error.response.data.message;
        } else {
            addNotification('error', 'Error al subir el archivo');
        }
    }
};

const uploadFile = async (action = '') => {
    const formData = new FormData();
    formData.append('file', selectedFile.value);
    formData.append('estado', form.value.estado);
    formData.append('publico', form.value.publico);
    formData.append('categoria', form.value.categoria);
    formData.append('tags', JSON.stringify(selectedTags.value));

    if (action) {
        formData.append('action', action);
    }

    const response = await axios.post('/files/upload', formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        },
    });

    if (response.status === 200) {
        addNotification('success', 'Archivo subido exitosamente');
        selectedFile.value = null;
        form.value.categoria = '';
        form.value.publico = 0;
        selectedTags.value = [];
    }
};

const handleModalAction = async (action) => {
    showModal.value = false;
    await uploadFile(action);
};

const updateSelectedTags = (tags) => {
    selectedTags.value = tags.filter(id => id !== null);
    form.value.tag = selectedTags.value.join(','); // Cambia 'tag' a un array de IDs
};

const addNotification = (type, message) => {
    notifications.value.push({ type, message });
    setTimeout(() => {
        notifications.value.shift();
    }, 3000);
};
</script>

<style scoped>
.upload {
    border: 1px dashed #ccc;
    padding: 20px;
    text-align: center;
    border-radius: 5px;
}
</style>

<template>
    <AppLayout title="File-Upload">
        <template #header>
            <div class="flex">
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <NavLink :href="route('list')" :active="route().current('list')">
                        Lista de Archivos
                    </NavLink>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <NavLink :href="route('files')" :active="route().current('files')">
                        Subir Archivos
                    </NavLink>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="upload">
                        <div
                            class="flex items-center mb-4 p-4 bg-gray-100 border border-gray-300 rounded"
                            v-if="selectedFile"
                        >
                            <img src="/img/clip.svg" class="w-6 h-6" />
                            <span class="ml-2 text-lg text-gray-700">{{ selectedFile.name }}</span>
                        </div>
                        <input
                            type="file"
                            class="hidden"
                            id="upload"
                            name="file"
                            accept=".pdf"
                            ref="fileInput"
                            @change="handleFileUpload"
                        />
                        <label
                            for="upload"
                            class="flex items-center justify-center p-4 border-2 border-dashed border-gray-700 rounded cursor-pointer bg-gray-100 hover:bg-gray-200"
                        >
                            <div class="mr-2">
                                <img src="/img/file.svg" class="w-6 h-6" />
                            </div>
                            <span class="text-lg text-gray-700">Arrastra o selecciona tu archivo</span>
                        </label>
                    </div>
                   <!-- DDL Categorias -->
                   <div class="mt-4">
                        <label for="categoria" class="block text-sm font-medium text-gray-700">Categorías disponibles</label>
                        <select id="categoria" v-model="form.categoria" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Seleccione una categoría</option>
                            <option v-for="categoria in categoriasPrincipales" :key="categoria.id" :value="categoria.id">
                                {{ categoria.nombre_categoria }}
                            </option>
                        </select>
                    </div>
                    <div class="mt-4">
                        <CustomLabel
    :label="'Etiquetas'"
    :items="etiquetas"
    :selected-items="selectedTags"
    @update-selected-items="updateSelectedTags"
    :categoria="form.categoria"
/>

                    </div>
                    <!-- Checkbox para "publico" -->
                    <label>
                        <input type="checkbox" v-model="form.publico" true-value="1" false-value="0" />
                        Público
                    </label>
                    <!-- Botón para cargar archivo -->
                    <PrimaryButton @click="validateAndUploadFile" class="mt-4 w-full justify-center">
                        Carga Archivo
                    </PrimaryButton>
                </div>
            </div>
        </div>

        <!-- Renderizar notificaciones -->
        <div v-for="(notification, index) in notifications" :key="index">
            <ToastNotification :type="notification.type" :message="notification.message" />
        </div>

        <!-- Modal para renombrar o reemplazar archivo -->
        <DialogModal v-if="showModal" @close="showModal = false">
            <template #title>Archivo Existente</template>
            <template #content>{{ modalMessage }}</template>
            <template #footer>
                <PrimaryButton @click="() => handleModalAction('rename')">Renombrar</PrimaryButton>
                <PrimaryButton @click="() => handleModalAction('replace')">Reemplazar</PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>
