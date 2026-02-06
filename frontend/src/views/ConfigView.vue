<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Konfigurasi SPK</h2>
        <p class="text-gray-500 text-sm mt-1">Pengaturan sistem dan parameter metode AHP.</p>
      </div>
      <button class="flex items-center justify-center gap-2 px-4 py-2 bg-teal-700 text-white font-medium rounded-lg text-sm hover:bg-teal-800 transition-colors">
        Simpan Perubahan
      </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <!-- General Settings -->
      <div class="lg:col-span-2 space-y-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
          <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Pengaturan Umum</h3>
          <div class="space-y-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sistem</label>
              <input type="text" value="SPK Bansos - Metode AHP" class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500">
            </div>
            <div class="grid grid-cols-2 gap-4">
               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Periode Penilaian</label>
                  <select class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500">
                    <option>Desember 2025</option>
                    <option>November 2025</option>
                  </select>
               </div>
               <div>
                  <label class="block text-sm font-medium text-gray-700 mb-1">Kuota Penerima</label>
                  <input type="number" value="1000" class="block w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-teal-500 focus:border-teal-500">
               </div>
            </div>
          </div>
        </div>

        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
          <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">Parameter AHP</h3>
          <div class="space-y-4">
            <div>
               <label class="flex items-center justify-between">
                 <span class="block text-sm font-medium text-gray-700">Batas Rasio Konsistensi (CR)</span>
                 <span class="text-sm font-bold text-teal-600">0.1</span>
               </label>
               <input type="range" min="0.01" max="0.2" step="0.01" value="0.1" class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer mt-2 accent-teal-600">
               <p class="text-xs text-gray-500 mt-1">Nilai standar Saaty adalah &le; 0.1 (10%). Jika CR > 0.1 maka matriks dianggap tidak konsisten.</p>
            </div>
            <div>
              <label class="flex items-center gap-2">
                <input type="checkbox" checked class="w-4 h-4 text-teal-600 border-gray-300 rounded focus:ring-teal-500">
                <span class="text-sm text-gray-700">Otomatis normalisasi bobot jika pengguna lupa</span>
              </label>
            </div>
          </div>
        </div>
      </div>

      <!-- System Info & User -->
      <div class="space-y-6">
        <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm">
           <h3 class="font-bold text-gray-900 mb-4 border-b border-gray-100 pb-2">User Profile</h3>
           <div class="flex items-center gap-4 mb-4">
             <div class="w-12 h-12 rounded-full bg-gray-200 overflow-hidden">
                <img src="https://ui-avatars.com/api/?name=Admin+Utama&background=fecaca&color=7f1d1d" alt="Admin" class="w-full h-full object-cover">
             </div>
             <div>
               <h4 class="font-bold text-gray-900">Admin Utama</h4>
               <p class="text-xs text-gray-500">Superuser</p>
             </div>
           </div>
           <button class="w-full py-2 text-sm text-gray-600 border border-gray-200 rounded-lg hover:bg-gray-50">Ganti Password</button>
           <button class="w-full mt-2 py-2 text-sm text-red-600 border border-red-100 bg-red-50 rounded-lg hover:bg-red-100">Logout</button>
        </div>

        <div class="bg-teal-700 p-6 rounded-xl text-white shadow-lg shadow-teal-700/20">
          <h3 class="font-bold mb-2">Tentang Sistem</h3>
          <p class="text-sm text-teal-100 opacity-90 mb-4">Sistem Pendukung Keputusan (SPK) Penentuan Penerima Bantuan Sosial menggunakan Metode AHP (Analytic Hierarchy Process).</p>
          <div class="text-xs text-teal-200 pt-4 border-t border-teal-600">
            Version 1.0.0
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Logic for saving settings would go here
</script>
