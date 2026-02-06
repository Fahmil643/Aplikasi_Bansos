<template>
  <aside class="w-64 bg-white border-r border-gray-200 h-screen fixed left-0 top-0 overflow-y-auto flex flex-col z-20">
    <div class="p-6 border-b border-gray-100 flex items-center gap-3">
      <div class="w-8 h-8 bg-teal-700 rounded-lg flex items-center justify-center text-white font-bold">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path d="M2 10a8 8 0 018-8v8h8a8 8 0 11-16 0z" />
          <path d="M12 2.252A8.014 8.014 0 0117.748 8H12V2.252z" />
        </svg>
      </div>
      <div>
        <h1 class="font-bold text-gray-800 text-sm">SPK Bansos</h1>
        <p class="text-xs text-gray-500 uppercase tracking-wider">Metode AHP</p>
      </div>
    </div>

    <div class="flex-1 py-6 px-3 space-y-8">
      <!-- Analisis & Data -->
      <div>
        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Analisis & Data</h3>
        <nav class="space-y-1">
          <button 
            v-for="item in menuItems.analysis" 
            :key="item.id"
            @click="$emit('change-page', item.id)"
            class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors"
            :class="currentPage === item.id ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
          >
            <component :is="item.icon" class="h-5 w-5" />
            {{ item.label }}
          </button>
        </nav>
      </div>

       <!-- Administrasi -->
      <div>
        <h3 class="px-3 text-xs font-semibold text-gray-400 uppercase tracking-widest mb-3">Administrasi</h3>
        <nav class="space-y-1">
          <button 
            v-for="item in menuItems.admin" 
            :key="item.id"
            @click="$emit('change-page', item.id)"
            class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg transition-colors"
             :class="currentPage === item.id ? 'bg-teal-50 text-teal-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'"
          >
           <component :is="item.icon" class="h-5 w-5" />
            {{ item.label }}
          </button>
        </nav>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
  currentPage: {
    type: String,
    default: 'dashboard'
  }
});

const emit = defineEmits(['change-page']);

// Inline SVG components map
const icons = {
  dashboard: {
    template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>`
  },
  criteria: {
    template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>`
  },
  alternatives: {
    template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>`
  },
  matrix: {
    template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>`
  },
  ranking: {
    template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>`
  },
  report: {
    template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>`
  },
  config: {
    template: `<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>`
  }
};

const menuItems = {
  analysis: [
    { id: 'dashboard', label: 'Dashboard Utama', icon: icons.dashboard },
    { id: 'criteria', label: 'Kriteria & Bobot', icon: icons.criteria },
    { id: 'alternatives', label: 'Data Alternatif', icon: icons.alternatives },
    { id: 'matrix', label: 'Matriks Perbandingan', icon: icons.matrix },
    { id: 'ranking', label: 'Hasil Perangkingan', icon: icons.ranking },
  ],
  admin: [
    { id: 'report', label: 'Laporan Akhir', icon: icons.report },
    { id: 'config', label: 'Konfigurasi SPK', icon: icons.config },
  ]
};
</script>
