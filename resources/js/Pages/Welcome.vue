<script setup>
import { Head, Link } from "@inertiajs/vue3";
import AuthenticationCardLogo from "@/Components/AuthenticationCardLogo.vue";
import { ref, onMounted, computed } from "vue";
import axios from "axios";

const props = defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});

const screenshotContainer = ref(null);
const docsCard = ref(null);
const docsCardContent = ref(null);
const background = ref(null);

function handleImageError() {
    screenshotContainer.value?.classList.add("!hidden");
    docsCard.value?.classList.add("!row-span-1");
    docsCardContent.value?.classList.add("!flex-row");
    background.value?.classList.add("!hidden");
}
// Public files listing logic
const files = ref([]);
const currentPage = ref(1);
const totalPages = ref(1);
const loading = ref(true);
const error = ref(null);
const searchTerm = ref("");

const fetchFiles = async (page = 1) => {
    loading.value = true;
    error.value = null;

    try {
        const response = await axios.get(`/public-files?page=${page}`, {
            headers: {
                Accept: "application/json",
            },
            withCredentials: true,
        });
        files.value = response.data.data || response.data;
        currentPage.value = response.data.current_page || 1;
        totalPages.value = response.data.last_page || 1;
    } catch (err) {
        error.value = err.message || "Error desconocido al cargar los archivos";
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
    return files.value.filter((file) =>
        file.name.toLowerCase().includes(searchTerm.value.toLowerCase())
    );
});
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-custom text-custom/50">
        <!-- <img ref="background" class="absolute left-0 top-0 w-full h-full object-cover"
     src="/img/ssscales.svg" /> -->
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[var(--color-red)] selection:text-white"
        >
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl items">
                <header
                    class="grid grid-cols-2 items-end gap-2 py-10 lg:grid-cols-3 lg:justify-end"
                >
                    <nav v-if="props.canLogin" class="flex flex-1 justify-end">
                        <Link
                            v-if="$page.props.auth.user"
                            :href="route('dashboard')"
                            class="rounded-md px-3 py-2 link-custom"
                        >
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link
                                :href="route('login')"
                                class="rounded-md px-3 py-2 link-custom"
                            >
                                Iniciar Sesión
                            </Link>

                            <Link
                                v-if="props.canRegister"
                                :href="route('register')"
                                class="rounded-md px-3 py-2 link-custom"
                            >
                                Registrarse
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6 flex justify-center">
                    <div
                        class="grid gap-6 lg:grid-cols-1 lg:gap-8 justify-items-center"
                    >
                        <AuthenticationCardLogo />
                    </div>
                </main>
                <!-- Public Files Listing -->
                <div class="py-5">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        <div
                            class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6"
                        >
                            <!-- Mensaje de error -->
                            <div v-if="error" class="text-center text-red-500">
                                {{ error }}
                            </div>

                            <!-- Cargando -->
                            <div
                                v-if="loading"
                                class="text-center text-gray-500"
                            >
                                Cargando archivos...
                            </div>

                            <!-- Lista de archivos -->
                            <div v-else>
                                <div
                                    v-if="filteredFiles.length === 0"
                                    class="text-center text-gray-500"
                                >
                                    No hay archivos disponibles.
                                </div>
                                <div v-else>
                                    <ul>
                                        <li
                                            v-for="file in filteredFiles"
                                            :key="file.name"
                                            class="mb-4 p-4 bg-gray-100 border border-gray-300 rounded"
                                        >
                                            <h3
                                                class="font-semibold text-lg text-gray-800"
                                            >
                                                {{ file.name }}
                                            </h3>
                                            <p class="text-gray-700">
                                                {{ file.url }}
                                            </p>
                                        </li>
                                    </ul>

                                    <!-- Paginación -->
                                    <div class="flex justify-center mt-4">
                                        <button
                                            @click="prevPage"
                                            :disabled="currentPage === 1"
                                            class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400"
                                        >
                                            Anterior
                                        </button>
                                        <span class="px-4 py-2 mx-1"
                                            >{{ currentPage }} de
                                            {{ totalPages }}</span
                                        >
                                        <button
                                            @click="nextPage"
                                            :disabled="
                                                currentPage === totalPages
                                            "
                                            class="px-4 py-2 mx-1 bg-gray-300 rounded hover:bg-gray-400"
                                        >
                                            Siguiente
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <footer class="py-16 text-center text-sm text-custom">
                    Todos los derechos reservados &copy; 2024 -
                    {{ new Date().getFullYear() }} Caritas de Guatemala
                </footer>
            </div>
        </div>
    </div>
</template>
