<script setup>
import NavLink from '@/Components/NavLink.vue';
</script>

<template>
    <nav class="flex space-x-4 mt-4">
        <NavLink :href="route('busqueda')" :active="route().current('busqueda')">
            Búsqueda
        </NavLink>
        <NavLink :href="route('rutas')" :active="route().current('rutas')">
            Rutas
        </NavLink>
        <NavLink :href="route('etiquetas')" :active="route().current('etiquetas')">
            Etiquetas
        </NavLink>
    </nav>
</template>
