<template>
  <header class="bg-white border-b border-gray-200 h-16 px-8 flex items-center justify-between sticky top-0 z-10">
    <div>
      <h2 class="text-xl font-bold text-gray-800">Dashboard Analisis</h2>
      <div class="flex items-center gap-2 text-xs text-gray-500 mt-0.5">
        <span class="font-medium text-teal-700">SPK</span>
        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span>Overview</span>
      </div>
    </div>

    <div class="flex items-center gap-6">
      <div class="flex items-center gap-2 px-3 py-1.5 bg-green-50 rounded-full border border-green-100">
        <div class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></div>
        <span class="text-xs font-bold text-green-700 uppercase tracking-wide">Sistem Aktif</span>
      </div>

      <div class="h-8 w-px bg-gray-200"></div>

      <div class="flex items-center gap-3">
        <div class="text-right hidden sm:block">
          <p class="text-sm font-bold text-gray-900 leading-tight">Admin Utama</p>
          <p class="text-xs text-gray-500">Superuser</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-gray-200 overflow-hidden border-2 border-white ring-2 ring-gray-100">
          <img src="https://ui-avatars.com/api/?name=Admin+Utama&background=fecaca&color=7f1d1d" alt="Admin" class="w-full h-full object-cover">
        </div>
      </div>
    </div>
  </header>
</template>
