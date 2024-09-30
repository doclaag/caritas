<template>
    <div>
        <h3 class="font-semibold text-lg text-gray-800 mb-4">Categorías Principales</h3>
        <div v-if="categories.length === 0" class="text-center text-gray-500 mb-4">
            No hay categorías disponibles.
        </div>
        <div v-else>
            <table class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="py-2">Nombre</th>
                        <th class="py-2">Descripción</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="category in categories" :key="category.id" @click="selectCategory(category)" class="cursor-pointer hover:bg-gray-200">
                        <td class="py-2">{{ category.nombre_categoria }}</td>
                        <td class="py-2">{{ category.descripcion_categoria }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        categories: {
            type: Array,
            required: true
        }
    },
    methods: {
        selectCategory(category) {
            this.$emit('select-category', category);
        }
    }
}
</script>

<style scoped>
table {
    width: 100%;
    border-collapse: collapse;
}
th, td {
    border: 1px solid #ddd;
    padding: 8px;
}
</style>