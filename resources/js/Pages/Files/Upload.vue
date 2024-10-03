<script setup>
import { ref, onMounted } from 'vue';
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import NavLink from '@/Components/NavLink.vue';
import axios from 'axios';

// categorias DDL llenado
const categoriasPrincipales = ref([]);
const categoriasSecundarias = ref([]);
onMounted(async () => {
    const response = await axios.get('/categories');
    categoriasPrincipales.value = response.data.principales;
    categoriasSecundarias.value = response.data.secundarias; 
});
</script>

<template>
    <AppLayout title="File-Upload">
        <template #header>
            <div class="flex">
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <NavLink :href=" route( 'list' ) " :active=" route().current( 'list' ) ">
                        Lista de Archivos
                    </NavLink>
                </div>
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <NavLink :href=" route( 'files' ) " :active=" route().current( 'files' ) ">
                        Subir Archivos
                    </NavLink>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                    <div class="upload">
                        <div class="flex items-center mb-4 p-4 bg-gray-100 border border-gray-300 rounded"
                            v-if=" selectedFile ">
                            <img src="/img/clip.svg" class="w-6 h-6" />
                            <span class="ml-2 text-lg text-gray-700">{{ selectedFile.name }}</span>
                        </div>
                        <input type="file" class="hidden" id="upload" name="file" accept=".pdf" ref="fileInput" />
                        <label for="upload"
                            class="flex items-center justify-center p-4 border-2 border-dashed border-gray-700 rounded cursor-pointer bg-gray-100 hover:bg-gray-200">
                            <div class="mr-2">
                                <img src="/img/file.svg" class="w-6 h-6" />
                            </div>
                            <span class="text-lg text-gray-700">Arrastra o selecciona tu archivo</span>
                        </label>
                    </div>
                    <!-- DDL Categorias     -->
                    <div class="mt-4">
                        <label for="categoria" class="block text-sm font-medium text-gray-700">Categorías disponibles</label>
                        <select id="categoria" v-model="form.categoria" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="">Seleccione una categoría</option>
                            <option v-for="categoria in categoriasPrincipales" :key="categoria.id" :value="categoria.id">
                                {{ categoria.nombre_categoria }}
                            </option>
                        </select>
                    </div>
                    <!-- DDL Etiquetas -->
                    <div class="mt-4">
                        <label for="categoriaSecundaria" class="block text-sm font-medium text-gray-700">Etiquetas</label>
                        <select id="categoriaSecundaria" v-model="form.categoriaSecundaria" class="mt-1 block w-full">
                            <option value="">Selecciona una o varias etiquetas</option>
                            <option v-for="categoria in categoriasSecundarias" :key="categoria.id" :value="categoria.id">
                                {{ categoria.nombre_categoria }}
                            </option>
                        </select>
                    </div>
                    <!-- Se comenta para pruebas  -->
                    <!-- Botón para subir el archivo -->
                    <!-- <PrimaryButton v-if=" selectedFile " @click=" uploadFile " class="mt-4 w-full justify-center">
                        Subir Archivo
                    </PrimaryButton> -->
                    <div>
                    <!-- Se comenta el siguiente codigo porqué el estado no lo va a poder manejar el usuario final -->
                    <!-- Checkbox para "estado" -->
                    <!-- <label>
                    <input type="checkbox" v-model="form.estado" true-value="1" false-value="0" />
                    Estado/Eliminado
                    </label> -->
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
    </div>
    </AppLayout>
</template>

<script>
export default {
    data() {
        return {
            form: {
                estado: 0,   
                publico: 0,  
                categoria: '', 
                categoriaSecundaria: '',
            },
            selectedFile: null 
        };
    },
    methods: {
        handleFileUpload( event ) {
            const file = event.target.files[ 0 ];
            if ( file ) {
                // Validar el tipo de archivo
                const validTypes = [ 'application/pdf' ];
                if ( !validTypes.includes( file.type ) ) {
                    alert( 'Tipo de archivo no permitido. Solo se permiten archivos PDF.' );
                    return;
                }

                this.selectedFile = file;
            }
        },
        validateAndUploadFile() {
            if ( !this.selectedFile ) {
                alert( 'Por favor, selecciona un archivo primero.' );
                return;
            }
            if ( !this.form.categoria ) {
                alert( 'Por favor, selecciona una categoría.' );
                return;
            }

            this.uploadFile();
        },
        // Funcion principal subida de archivos validaciones
        async uploadFile() {
            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('estado', this.form.estado); 
            formData.append('publico', this.form.publico);
            formData.append('categoria', this.form.categoria); 
            formData.append('categoriaSecundaria', this.form.categoriaSecundaria); 
            try {
                const response = await fetch('/files/upload', {
                    method: 'POST',
                    body: formData,
                });
                if (!response.ok) {
                    throw new Error('Error en la carga del archivo.');
                }
                this.selectedFile = null;
                this.$refs.fileInput.value = '';
                this.form.categoria = '';
                this.form.categoriaSecundaria = '';
                alert('Archivo subido exitosamente.');
            } catch (error) {
                console.error(error.message);
            }
        }
    },
    mounted() {
        this.$refs.fileInput.addEventListener( 'change', this.handleFileUpload );
    },
    beforeDestroy() {
        this.$refs.fileInput.removeEventListener( 'change', this.handleFileUpload );
    }
};
</script>