<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
    <AppLayout title="File-Upload">
        <template #header>
            <div class="flex">

                <!-- Navigation Links -->
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

                    <!-- Botón para subir el archivo -->
                    <PrimaryButton v-if=" selectedFile " @click=" uploadFile " class="mt-4 w-full justify-center">
                        Subir Archivo
                    </button>
                    <div>
                    <!-- Checkbox para "estado" -->
                    <label>
                    <input type="checkbox" v-model="form.estado" true-value="1" false-value="0" />
                    Estado/Eliminado
                    </label>

                    <!-- Checkbox para "publico" -->
                    <label>
                    <input type="checkbox" v-model="form.publico" true-value="1" false-value="0" />
                    Público
                    </label>
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
                estado: 0,   // Valor por defecto 0 (desmarcado)
                publico: 0,  // Valor por defecto 0 (desmarcado)
            },
            selectedFile: null // Archivo seleccionado
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

                // Validar el tamaño del archivo (máximo 10 MB)
                const maxSize = 10 * 1024 * 1024; // 10 MB en bytes
                if ( file.size > maxSize ) {
                    alert( 'El archivo es demasiado grande. El tamaño máximo permitido es 10 MB.' );
                    return;
                }

                this.selectedFile = file;
                console.log( file );
            }
        },
        async uploadFile() {
            if ( !this.selectedFile ) {
                alert( 'Por favor, selecciona un archivo primero.' );
                return;
            }

            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('estado', this.form.estado); // Agregar estado al FormData
            formData.append('publico', this.form.publico); // Agregar publico al FormData

            try {
                const response = await fetch( '/upload', {
                    method: 'POST',
                    body: formData,
                } );

                if ( !response.ok ) {
                    throw new Error( 'Error en la carga del archivo.' );
                }

                this.selectedFile = null; // Resetear archivo seleccionado
                this.$refs.fileInput.value = ''; // Limpiar el input
                this.displayingToken = true; // Mostrar el DialogModal
            } catch ( error ) {
                console.error( error.message );
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
