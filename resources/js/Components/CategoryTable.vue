<template>
    <div>
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Categorías Principales</h3>
        <div v-if="categories.length === 0" class="text-center text-gray-500 mb-4">
            No hay categorías disponibles.
        </div>
        <div v-else>
            <div class="bg-white">
                <div class="flex font-semibold">
                    <div class="py-2 px-4">Nombre</div>
                    <div class="py-2 px-4">Descripción</div>
                </div>
                <div v-for="(category, index) in categories" :key="category.id">
                    <div class="flex cursor-pointer hover:bg-gray-200" @click="selectCategory(index)">
                        <div class="py-2 px-4">{{ category.nombre_categoria }}</div>
                        <div class="py-2 px-4">{{ category.descripcion_categoria }}</div>
                    </div>
                    <div v-if="selectedIndex === index" class="bg-gray-100 border border-gray-300 rounded p-4">
                        <form @submit.prevent="submitEtiquetaForm">
                            <div class="mb-4">
                                <label for="nombre_etiqueta" class="block text-gray-700">Nombre de la Etiqueta</label>
                                <input type="text" id="nombre_etiqueta" :value="nombreEtiqueta" @input="$emit('update:nombreEtiqueta', $event.target.value)" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mb-4">
                                <label for="descripcion_etiqueta" class="block text-gray-700">Descripción</label>
                                <textarea id="descripcion_etiqueta" :value="descripcionEtiqueta" @input="$emit('update:descripcionEtiqueta', $event.target.value)" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required></textarea>
                            </div>
                            <div v-if="etiquetaFormError" class="text-center text-red-500 mb-4">
                                {{ etiquetaFormError }}
                            </div>
                            <PrimaryButton type="submit">Crear Etiqueta</PrimaryButton>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import PrimaryButton from '@/Components/PrimaryButton.vue';

export default {
    components: {
        PrimaryButton
    },
    props: {
        categories: {
            type: Array,
            required: true
        },
        selectedIndex: {
            type: Number,
            default: null
        },
        nombreEtiqueta: {
            type: String,
            required: true
        },
        descripcionEtiqueta: {
            type: String,
            required: true
        },
        etiquetaFormError: {
            type: String,
            default: null
        }
    },
    methods: {
        selectCategory(index) {
            this.$emit('select-category', index);
        },
        submitEtiquetaForm() {
            this.$emit('submit-etiqueta-form');
        }
    }
}
</script>

<style scoped>
.flex {
    display: flex;
}
.cursor-pointer {
    cursor: pointer;
}
.hover\:bg-gray-200:hover {
    background-color: #e2e8f0;
}
.bg-white {
    background-color: #ffffff;
}
.bg-gray-100 {
    background-color: #f7fafc;
}
.border {
    border-width: 1px;
}
.border-gray-300 {
    border-color: #d2d6dc;
}
.rounded {
    border-radius: 0.25rem;
}
.p-4 {
    padding: 1rem;
}
.mb-4 {
    margin-bottom: 1rem;
}
.mt-1 {
    margin-top: 0.25rem;
}
.block {
    display: block;
}
.w-full {
    width: 100%;
}
.shadow-sm {
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
.text-gray-700 {
    color: #4a5568;
}
.text-center {
    text-align: center;
}
.text-red-500 {
    color: #f56565;
}
</style>