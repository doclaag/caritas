<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import { ref } from 'vue';

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
    screenshotContainer.value?.classList.add('!hidden');
    docsCard.value?.classList.add('!row-span-1');
    docsCardContent.value?.classList.add('!flex-row');
    background.value?.classList.add('!hidden');
}
</script>

<template>
    <Head title="Welcome" />
    <div class="bg-custom text-custom/50">
        <!-- <img ref="background" class="absolute left-0 top-0 w-full h-full object-cover"
     src="/img/ssscales.svg" /> -->
        <div
            class="relative min-h-screen flex flex-col items-center justify-center selection:bg-[var(--color-red)] selection:text-white">
            <div class="relative w-full max-w-2xl px-6 lg:max-w-7xl items">
                <header class="grid grid-cols-2 items-end gap-2 py-10 lg:grid-cols-3 lg:justify-end">
                    <nav v-if="props.canLogin" class="flex flex-1 justify-end">
                        <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                            class="rounded-md px-3 py-2 link-custom">
                            Dashboard
                        </Link>

                        <template v-else>
                            <Link :href="route('login')"
                                class="rounded-md px-3 py-2 link-custom">
                                Iniciar Sesión
                            </Link>

                            <Link v-if="props.canRegister" :href="route('register')"
                                class="rounded-md px-3 py-2 link-custom">
                                Registrarse
                            </Link>
                        </template>
                    </nav>
                </header>

                <main class="mt-6 flex justify-center">
                    <div class="grid gap-6 lg:grid-cols-1 lg:gap-8 justify-items-center">
                        <AuthenticationCardLogo/>
                    </div>
                </main>

                <footer class="py-16 text-center text-sm text-custom">
                    Todos los derechos reservados &copy; 2024 - {{ new Date().getFullYear() }} Caritas de Guatemala
                </footer>
            </div>
        </div>
    </div>
</template>
