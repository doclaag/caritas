<script setup>
import { ref, onMounted } from 'vue';
import axios from 'axios';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

const principales = ref([]);
const secundarias = ref([]);
const loading = ref(true);
const error = ref(null); // Variable para manejar errores

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
</script>

<template>
    <AppLayout title="Categories">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Categorías
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <!-- Mensaje de error -->
                    <div v-if="error" class="text-center text-red-500">
                        {{ error }}
                    </div>

                    <!-- Cargando -->
                    <div v-if="loading" class="text-center text-gray-500">
                        Cargando categorías...
                    </div>

                    <!-- Categorías principales y secundarias -->
                    <div v-else class="flex flex-wrap">
                        <!-- Categorías principales -->
                        <div class="w-full md:w-1/2 pr-4">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Principales</h3>
                            <div v-if="principales.length === 0" class="text-center text-gray-500 mb-4">
                                No hay categorías principales disponibles.
                            </div>
                            <div v-else>
                                <div v-for="category in principales" :key="category.id" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ category.nombre_categoria }}</h3>
                                    <p class="text-gray-700">{{ category.descripcion_categoria }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Categorías secundarias -->
                        <div class="w-full md:w-1/2 pl-4">
                            <h3 class="font-semibold text-lg text-gray-800 mb-4">Secundarias</h3>
                            <div v-if="secundarias.length === 0" class="text-center text-gray-500 mb-4">
                                No hay categorías secundarias disponibles.
                            </div>
                            <div v-else>
                                <div v-for="category in secundarias" :key="category.id" class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded">
                                    <h3 class="font-semibold text-lg text-gray-800">{{ category.nombre_categoria }}</h3>
                                    <p class="text-gray-700">{{ category.descripcion_categoria }}</p>
                                </div>
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
