<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DialogModal from '@/Components/DialogModal.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
</script>

<template>
    <AppLayout title="File-Upload">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Subir Archivo
            </h2>
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
                    </PrimaryButton>
                </div>
            </div>
        </div>

        <!-- DialogModal -->
        <DialogModal :show=" displayingToken " @close="displayingToken = false">
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
                    Close
                </PrimaryButton>
            </template>
        </DialogModal>
    </AppLayout>
</template>

<script>
export default {
    data() {
        return {
            selectedFile: null,
            displayingToken: false
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
            formData.append( 'file', this.selectedFile );

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
