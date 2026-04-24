<template>
    <Head title="Mi Perfil" />

    <AuthenticatedLayout>
        <main class="max-w-6xl mx-auto py-8 px-4">
            
            <div class="bg-white border border-slate-200 rounded-2xl p-8 mb-6 shadow-sm flex items-center gap-6">
                <div class="w-24 h-24 shrink-0 rounded-full bg-gradient-to-br from-blue-500 to-purple-500 flex items-center justify-center text-white text-4xl font-bold shadow-inner">
                    {{ inicialNombre }}
                </div>
                
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 mb-2">{{ $page.props.auth.user.name }}</h1>
                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex items-center gap-1.5 text-slate-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                            {{ $page.props.auth.user.email }}
                        </div>
                        <span class="bg-slate-900 text-white text-xs font-semibold px-2.5 py-1 rounded-md uppercase">
                            {{ $page.props.auth.user.rol }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                
                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-sm font-semibold text-slate-800">Cursos Completados</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ estadisticas.cursos_completados }}</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-sm font-semibold text-slate-800">Certificados</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ estadisticas.certificados }}</div>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                    <div class="flex justify-between items-start mb-4">
                        <h3 class="text-sm font-semibold text-slate-800">Promedio</h3>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-purple-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                    </div>
                    <div class="text-3xl font-bold text-slate-900">{{ estadisticas.promedio }}%</div>
                </div>

            </div>
        </main>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

const page = usePage();

const props = defineProps({
    estadisticas: {
        type: Object,
        default: () => ({
            cursos_completados: 0,
            certificados: 0,
            promedio: 0
        })
    }
});

// Calcula la inicial usando el nombre real que viene de Laravel
const inicialNombre = computed(() => {
    const nombre = page.props.auth.user.name;
    return nombre ? nombre.charAt(0).toUpperCase() : 'A';
});
</script>