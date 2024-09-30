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
                        <div class="upload__info" v-if="selectedFile">
                            <svg t="1581822650945" class="clip" viewBox="0 0 1024 1024" version="1.1"
                                xmlns="http://www.w3.org/2000/svg" p-id="3250" width="20" height="20">
                                <path
                                    d="M645.51621918 141.21142578c21.36236596 0 41.79528808 4.04901123 61.4025879 12.06298852a159.71594214 159.71594214 0 0 1 54.26367236 35.87255836c15.84503198 16.07739258 27.76959252 34.13726783 35.78356909 54.13513184 7.86071778 19.30572486 11.76635766 39.80291724 11.76635767 61.53607177 0 21.68371583-3.90563989 42.22045875-11.76635767 61.54101586-8.01397729 19.99291992-19.95831275 38.02807617-35.78356909 54.08569313l-301.39672877 302.0839231c-9.21038818 9.22027564-20.15112281 16.48278832-32.74310277 21.77270508-12.29040503 4.81036401-24.54125953 7.19329834-36.82177783 7.19329834-12.29040503 0-24.56103516-2.38293433-36.85638427-7.19329834-12.63647461-5.28991675-23.53271461-12.55737281-32.7381587-21.77270508-9.55151367-9.58117675-16.69042992-20.44775367-21.50573731-32.57995583-4.7856443-11.61804223-7.15869117-23.91339135-7.15869188-36.9255979 0-13.14074708 2.37304688-25.55474854 7.16363524-37.19256639 4.81036401-11.94927954 11.94927954-22.78619408 21.50079395-32.55029274l278.11614966-278.46221923c6.45172119-6.51104737 14.22344971-9.75421118 23.27563501-9.75421119 8.8692627 0 16.54705787 3.24316383 23.03338622 9.75421119 6.47644019 6.49127173 9.73937964 14.18389916 9.73937964 23.08282495 0 9.0521853-3.26293945 16.81896972-9.73937964 23.32012891L366.97489888 629.73773218c-6.32812477 6.2935183-9.48724342 14.08007836-9.48724415 23.30529736 0 9.06701684 3.15417457 16.75964356 9.48724414 23.08776904 6.80273414 6.50610328 14.55963111 9.75915528 23.26574683 9.75915527 8.67150855 0 16.43334961-3.253052 23.27563501-9.76409935l301.37695313-302.04931665c18.93988037-18.96459937 28.40734887-42.04742432 28.40734814-69.25836158 0-27.16149926-9.4674685-50.26409912-28.40734815-69.22869849-19.44415283-19.13269043-42.55664086-28.72375464-69.31274438-28.72375536-26.97363258 0-49.99218727 9.59106422-69.1001587 28.72375536L274.3370815 536.89227319a159.99774146 159.99774146 0 0 0-35.80828883 54.33288526c-8.0337522 19.65179443-12.04321289 40.2824707-12.04321289 61.79809618 0 21.20910645 4.00451661 41.81011963 12.04321289 61.79809547 8.17218018 20.34393287 20.10168481 38.36920166 35.80828883 54.08569312 16.225708 16.06256104 34.30535888 28.13049292 54.23400854 36.15930176 19.91381813 8.0337522 40.47033667 12.06793189 61.64978002 12.0679326 21.13989281 0 41.70135474-4.03417969 61.63000513-12.0679326 19.91876221-8.02386474 38.01818872-20.09674073 54.2241211-36.15435768l300.86773656-301.53515601c6.47644019-6.50115991 14.23828125-9.76904273 23.28057912-9.76904344 8.88903833 0 16.56188941 3.26293945 23.04821776 9.76904344 6.48632836 6.48632836 9.7245481 14.17895508 9.7245481 23.06799269 0 9.09667992-3.23822046 16.8535769-9.7245481 23.37451172L552.40379244 815.35449242c-22.00012231 22.01989722-47.32745362 38.88336158-75.986938 50.49151564C449.10209565 877.14270043 420.37834101 882.78857422 390.21592671 882.78857422c-30.01904297 0-58.74279761-5.64587378-86.20587183-16.94256616-28.6842041-11.60815406-54.00659203-28.47161842-76.00671362-50.49151564a226.19586182 226.19586182 0 0 1-50.13061524-75.90289354A226.86328125 226.86328125 0 0 1 160.9697104 653.04797364c0-30.08331323 5.62115479-58.88122559 16.90795899-86.38385035 11.40545654-28.37768578 28.11566138-53.75939917 50.13061523-76.15997313h0.24719287L530.14164643 189.20135474c15.69177247-15.731323 33.68737817-27.70037818 53.98681641-35.89727735C604.09666377 145.26043701 624.55430562 141.23120141 645.51127583 141.23120141V141.21142578z"
                                    p-id="3251"></path>
                            </svg>
                            <span class="upload__filename">{{ selectedFile.name }}</span>
                        </div>
                        <input type="file" class="upload__input" id="upload" name="file" accept=".pdf,.doc,.docx"
                            ref="fileInput" @change="handleFileUpload" />
                        <label for="upload" class="upload__label">
                            <div class="upload__icon">
                                <svg t="1581822650945" class="icon" viewBox="0 0 1024 1024" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" p-id="3252" width="40" height="40">
                                    <path
                                        d="M891.61035729 440.38964271l-56.86174026-56.8620281c-8.69763268-8.69835091-20.61011049-13.24816141-33.10473787-13.2481614-0.01609785 0-0.03217773 0-0.04826355 0-0.07608027 0-0.14406277 0-0.22013995 0.00311958-12.49719237 0.0131967-24.38540663 4.69468641-33.07666688 13.36291576l-32.89215445 32.89224591c-11.27905104 11.27706917-26.83245835 17.56125693-42.36113368 17.56125693-15.59487908 0-31.1650547-6.26901395-42.45774176-17.6064148-12.17441364-12.08578468-18.89917851-28.58292548-18.89917851-45.75731241v-84.55851311h-75.78736185c-24.48994448 0-44.35125778 19.92717119-44.35125778 44.23796195v75.17807031c0 34.7354436 27.83229556 62.56879049 62.4624043 62.56879048h77.21940761v79.64496066c0 51.33133306 41.73121108 93.29364044 93.00276736 93.29364044s93.00518712-41.96230963 93.00518712-93.29364044v-79.64496066h79.62508977c34.62716362 0 62.47163429-27.82978351 62.47163429-62.56879048v-75.17807031c0-24.31079076-19.86631186-44.23796195-44.35125778-44.23796195h-75.78736186v84.55851311c0 18.896776-7.04854262 37.48992965-20.5061031 51.02421558z"
                                        p-id="3253"></path>
                                </svg>
                            </div>
                            <span class="upload__text">Arrastra o selecciona tu archivo</span>
                        </label>
                    </div>

                    <!-- Botón para subir el archivo -->
                    <button v-if="selectedFile" class="upload__button" @click="uploadFile">
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
        handleFileUpload(event) {
            const file = event.target.files[0];
            if (file) {
                // Validar el tipo de archivo
                const validTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!validTypes.includes(file.type)) {
                    alert('Tipo de archivo no permitido. Solo se permiten archivos PDF, DOC y DOCX.');
                    return;
                }

                // Validar el tamaño del archivo (máximo 10 MB)
                const maxSize = 10 * 1024 * 1024; // 10 MB en bytes
                if (file.size > maxSize) {
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es 10 MB.');
                    return;
                }

                this.selectedFile = file;
                console.log(file);
            }
        },
        async uploadFile() {
            if (!this.selectedFile) {
                alert('Por favor, selecciona un archivo primero.');
                return;
            }

            const formData = new FormData();
            formData.append('file', this.selectedFile);
            formData.append('estado', this.form.estado); // Agregar estado al FormData
            formData.append('publico', this.form.publico); // Agregar publico al FormData

            try {
                const response = await fetch('/upload', {
                    method: 'POST',
                    body: formData,
                });

                if (!response.ok) {
                    throw new Error('Error en la carga del archivo.');
                }

                alert('Archivo subido con éxito.');
                this.selectedFile = null; // Resetear archivo seleccionado
                this.$refs.fileInput.value = ''; // Limpiar el input
            } catch (error) {
                alert(error.message);
            }
        }
    },
    mounted() {
        this.$refs.fileInput.addEventListener('change', this.handleFileUpload);
    },
    beforeDestroy() {
        this.$refs.fileInput.removeEventListener('change', this.handleFileUpload);
    }
};
</script>

<style scoped>
.upload {
    position: relative;
    width: 100%;
    max-width: 400px;
    margin: 0 auto;
}

.upload__info {
    display: flex;
    align-items: center;
    margin-bottom: 10px;
}

.clip {
    fill: #4a5568;
}

.upload__filename {
    margin-left: 10px;
    font-size: 16px;
    color: #4a5568;
}

.upload__input {
    display: none;
}

.upload__label {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 10px;
    border: 2px dashed #4a5568;
    border-radius: 5px;
    cursor: pointer;
    background-color: #f7fafc;
}

.upload__icon {
    margin-right: 10px;
}

.upload__text {
    font-size: 16px;
    color: #4a5568;
}

.upload__button {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 10px;
    border: none;
    border-radius: 5px;
    background-color: #4a5568;
    color: #fff;
    font-size: 16px;
    cursor: pointer;
}

.upload__button:hover {
    background-color: #2d3748;
}
</style>
