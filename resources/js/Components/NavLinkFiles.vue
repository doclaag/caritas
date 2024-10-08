<script setup>
    import NavLink from '@/Components/NavLink.vue';
</script>

<template>
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
