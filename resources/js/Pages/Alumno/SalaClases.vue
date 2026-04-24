<template>
    <AuthenticatedLayout>
        <div class="bg-slate-50 min-h-screen pb-12">
            
            <div class="bg-white border-b border-slate-200 py-8 mb-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <Link :href="route('alumno.dashboard')" class="text-sm font-medium text-slate-500 hover:text-blue-600 flex items-center mb-4">
                        &larr; Volver a Mis Cursos
                    </Link>
                    <h1 class="text-3xl font-extrabold text-slate-900 mb-2">{{ curso.titulo }}</h1>
                    <p class="text-slate-600 mb-6">{{ curso.descripcion }}</p>

                    <div class="bg-slate-100 rounded-xl p-4 border border-slate-200">
                        <div class="flex justify-between text-sm font-bold text-slate-700 mb-2">
                            <span>Tu Progreso: {{ modulosCompletosCount }} / {{ totalModulos }} módulos</span>
                            <span>{{ porcentajeProgreso }}%</span>
                        </div>
                        <div class="w-full bg-slate-200 h-2.5 rounded-full overflow-hidden">
                            <div class="bg-green-500 h-full transition-all duration-500" :style="{ width: porcentajeProgreso + '%' }"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col lg:flex-row gap-8">
                    
                    <div class="lg:w-2/3">
                        <div v-if="moduloActivo" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
                            
                            <div v-if="moduloActivo.url_video" class="aspect-video bg-slate-900 flex items-center justify-center relative group">
                                <div class="text-white text-center">
                                    <svg class="w-16 h-16 mx-auto mb-2 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                    <span class="font-semibold">Reproductor de Video</span>
                                </div>
                            </div>

                            <div class="p-8">
                                <h2 class="text-2xl font-bold text-slate-800 mb-4">{{ moduloActivo.titulo }}</h2>
                                <div class="prose max-w-none text-slate-600 mb-8 whitespace-pre-line">
                                    {{ moduloActivo.descripcion_contenido }}
                                </div>

                                <div class="mb-8 border-t border-slate-100 pt-6">
                                    <h4 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-3">Material Adjunto</h4>
                                    <div class="flex gap-3">
                                        <div class="flex items-center gap-2 bg-slate-50 px-4 py-2 rounded-lg border border-slate-200 text-sm text-slate-600 hover:bg-slate-100 cursor-pointer">
                                            📄 documento_guia.pdf
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-end pt-4 border-t border-slate-100">
                                    <div v-if="completados.includes(moduloActivo.id)" class="px-6 py-3 bg-green-50 text-green-700 font-bold rounded-xl border border-green-200 flex items-center gap-2">
                                        ✓ Módulo Completado
                                    </div>
                                    <Link v-else :href="route('alumno.modulos.completar', moduloActivo.id)" method="post" as="button" class="px-6 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl transition-colors">
                                        Marcar como completado
                                    </Link>
                                </div>
                            </div>
                        </div>
                        
                        <div v-else class="text-center py-20 bg-white rounded-2xl border border-slate-200 text-slate-500">
                            No hay módulos disponibles en este curso.
                        </div>
                    </div>

                    <div class="lg:w-1/3 space-y-4">
                        <h3 class="font-bold text-lg text-slate-800 px-1">Contenido del Curso</h3>
                        
                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-2 space-y-1">
                            <button v-for="(modulo, index) in curso.modulos" :key="modulo.id" 
                                    @click="isUnlocked(index) ? seleccionarModulo(modulo) : null"
                                    :class="[
                                        'w-full text-left px-4 py-3 rounded-xl flex items-center gap-3 transition-colors',
                                        moduloActivo?.id === modulo.id ? 'bg-blue-50 border border-blue-100' : 'hover:bg-slate-50',
                                        !isUnlocked(index) ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                                    ]">
                                
                                <div class="shrink-0 flex items-center justify-center w-8 h-8 rounded-full"
                                     :class="completados.includes(modulo.id) ? 'bg-green-100 text-green-600' : (!isUnlocked(index) ? 'bg-slate-100 text-slate-400' : 'bg-slate-100 text-slate-600')">
                                    <span v-if="completados.includes(modulo.id)">✓</span>
                                    <span v-else-if="!isUnlocked(index)">🔒</span>
                                    <span v-else class="text-sm font-bold">{{ index + 1 }}</span>
                                </div>
                                
                                <div class="flex-1 min-w-0">
                                    <p class="font-semibold text-sm truncate" :class="moduloActivo?.id === modulo.id ? 'text-blue-900' : 'text-slate-700'">
                                        {{ modulo.titulo }}
                                    </p>
                                </div>
                            </button>
                        </div>

                        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-6 mt-6">
                            <h3 class="font-bold text-slate-800 mb-2">Examen Final</h3>
                            <p class="text-sm text-slate-500 mb-4">Evalúa tus conocimientos para obtener la certificación.</p>
                            
                            <div v-if="examenDesbloqueado">
                                <Link href="#" class="block w-full text-center py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl transition-colors">
                                    Realizar Examen
                                </Link>
                            </div>
                            <div v-else class="w-full text-center py-3 bg-slate-100 text-slate-400 font-bold rounded-xl flex items-center justify-center gap-2 border border-slate-200">
                                🔒 Completa los módulos
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Link } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';

const props = defineProps({
    curso: Object,
    completados: Array
});

// Estado Reactivo
const moduloActivo = ref(null);

// Inicializar el primer módulo desbloqueado al cargar la página
onMounted(() => {
    if (props.curso.modulos && props.curso.modulos.length > 0) {
        // Buscar el primer módulo no completado, o el último si todos están listos
        const primerIncompleto = props.curso.modulos.find(m => !props.completados.includes(m.id));
        moduloActivo.value = primerIncompleto || props.curso.modulos[0];
    }
});

// Lógica de Desbloqueo Secuencial
const isUnlocked = (index) => {
    if (index === 0) return true; // El módulo 1 siempre está desbloqueado
    const moduloAnterior = props.curso.modulos[index - 1];
    return props.completados.includes(moduloAnterior.id);
};

// Cambiar de módulo al hacer clic en la playlist
const seleccionarModulo = (modulo) => {
    moduloActivo.value = modulo;
};

// Computados para la barra de progreso y examen
const totalModulos = computed(() => props.curso.modulos ? props.curso.modulos.length : 0);
const modulosCompletosCount = computed(() => props.completados.length);
const porcentajeProgreso = computed(() => {
    if (totalModulos.value === 0) return 0;
    return Math.round((modulosCompletosCount.value / totalModulos.value) * 100);
});

// El examen solo se habilita si todos los módulos están en el array de completados
const examenDesbloqueado = computed(() => {
    return totalModulos.value > 0 && modulosCompletosCount.value === totalModulos.value;
});
</script>