<script setup>
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

import NavLinkFiles from '@/Components/NavLinkFiles.vue';

const files = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const loading = ref(true);
const error = ref(null);
const searchTerm = ref('');

const fetchFiles = async (page = 1) => {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(`/files/list?page=${page}`, {
            headers: {
                'Accept': 'application/json'
            },
            withCredentials: true
        });
        files.value = response.data.data || response.data;
        currentPage.value = response.data.current_page || 1;
        totalPages.value = response.data.last_page || 1;
    } catch (err) {
        error.value = err.message || 'Error desconocido al cargar los archivos';
    } finally {
        loading.value = false;
    }
};

onMounted(() => {
    fetchFiles();
});

const goToPage = (page) => {
    if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
        fetchFiles(page);
    }
};

const nextPage = () => {
    if (currentPage.value < totalPages.value) {
        goToPage(currentPage.value + 1);
    }
};

const prevPage = () => {
    if (currentPage.value > 1) {
        goToPage(currentPage.value - 1);
    }
};

const filteredFiles = computed(() => {
    if (!searchTerm.value) {
        return files.value;
    }
    return files.value.filter(file => file.name.toLowerCase().includes(searchTerm.value.toLowerCase()));
});
</script>

<template>
    <AppLayout title="File List">
        <template #header>
            <NavLinkFiles />
        </template>

        <div class="py-5 flex items-center justify-center space-x-8">
            <!-- Search Bar -->
            <div class="flex items-center space-x-2">
                <img src="/img/search.svg" class="w-6 h-6" />
                <input type="text" placeholder="Buscar archivos..." class="border rounded-md px-3 py-2" v-model="searchTerm" />
            </div>
            <!-- Search Bar- Busqueda comentada no se sabe si se va a implementar-->
            <!-- <div class="flex items-center space-x-2">
                <img src="/img/search.svg" class="w-6 h-6" />
                <input type="text" placeholder="Buscar etiquetas..." class="border rounded-md px-3 py-2" />
            </div> -->
        </div>

        <div class="py-5">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Mensaje de error -->
                    <div v-if="error" class="text-center text-red-500">
                        {{ error }}
                    </div>

                    <!-- Cargando -->
                    <div v-if="loading" class="text-center text-gray-500">
                        Cargando archivos...
                    </div>

                    <!-- Lista de archivos -->
                    <div v-else>
                        <div v-if="filteredFiles.length === 0" class="text-center text-gray-500">
                            No hay archivos disponibles.
                        </div>
                        <div v-else>
                            <ul>
                                <li v-for="file in filteredFiles" :key="file.name" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ file.name }}</h3>
                                    <p class="text-gray-700">{{ file.url }}</p>
                                </li>
                            </ul>

                            <!-- Paginación -->
                            <div class="flex justify-center mt-4">
                                <button @click="prevPage" :disabled="currentPage === 1" class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400">
                                    Anterior
                                </button>
                                <span class="px-4 py-2 mx-1">{{ currentPage }} de {{ totalPages }}</span>
                                <button @click="nextPage" :disabled="currentPage === totalPages" class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400">
                                    Siguiente
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DialogModal -->
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
