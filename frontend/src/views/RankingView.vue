<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Hasil Perangkingan</h2>
        <p class="text-gray-500 text-sm mt-1">Daftar prioritas penerima bantuan berdasarkan skor akhir AHP.</p>
      </div>
      <button @click="exportToCSV" class="flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
        </svg>
        Export Laporan
      </button>
    </div>

    <!-- Stats Summary -->
     <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
       <div class="bg-indigo-50 border border-indigo-100 p-6 rounded-xl flex items-center justify-between">
         <div>
           <p class="text-xs font-bold text-indigo-500 uppercase tracking-widest mb-1">Kuota Tersedia</p>
           <h3 class="text-2xl font-bold text-indigo-900">1,000</h3>
         </div>
         <div class="h-10 w-10 bg-indigo-200 rounded-lg flex items-center justify-center text-indigo-700">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
         </div>
       </div>
       <div class="bg-emerald-50 border border-emerald-100 p-6 rounded-xl flex items-center justify-between">
         <div>
           <p class="text-xs font-bold text-emerald-500 uppercase tracking-widest mb-1">Layak Bantuan</p>
           <h3 class="text-2xl font-bold text-emerald-900">{{ eligibleCount }}</h3>
         </div>
         <div class="h-10 w-10 bg-emerald-200 rounded-lg flex items-center justify-center text-emerald-700">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
         </div>
       </div>
       <div class="bg-gray-50 border border-gray-100 p-6 rounded-xl flex items-center justify-between">
         <div>
           <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Total Calon</p>
           <h3 class="text-2xl font-bold text-gray-700">{{ rankingList.length }}</h3>
         </div>
          <div class="h-10 w-10 bg-gray-200 rounded-lg flex items-center justify-center text-gray-500">
           <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
          </svg>
         </div>
       </div>
    </div>

    <!-- Ranking Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 w-16">Rank</th>
            <th class="px-6 py-4">Nama Penerima</th>
            <th class="px-6 py-4 text-center">Skor Akhir</th>
            <th class="px-6 py-4 text-center">Status</th>
            <th class="px-6 py-4 text-right">Detail</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="(item, index) in rankingList" :key="item.id" class="group hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4">
              <div 
                class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm" 
                :class="index < 3 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500'"
              >
                {{ index + 1 }}
              </div>
            </td>
            <td class="px-6 py-4">
              <div class="font-bold text-gray-900">{{ item.name }}</div>
              <div class="text-xs text-gray-500">{{ item.kecamatan }}</div>
            </td>
            <td class="px-6 py-4 text-center">
              <span class="text-sm font-bold text-teal-700">{{ item.score.toFixed(4) }}</span>
            </td>
            <td class="px-6 py-4 text-center">
              <span 
                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold"
                :class="item.isEligible 
                  ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' 
                  : 'bg-red-50 text-red-600 border border-red-100'"
              >
                {{ item.isEligible ? 'LAYAK' : 'TIDAK LAYAK' }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <button class="text-gray-400 hover:text-teal-600 group-hover:visible transition-colors">
                Lihat Nilai &rarr;
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue';

const rankingList = ref([]);

const fetchRankings = async () => {
    try {
        const response = await fetch('http://localhost:8000/api/rankings.php');
        if (response.ok) {
            rankingList.value = await response.json();
        }
    } catch (e) {
        console.error("Error fetching rankings:", e);
    }
};

const eligibleCount = computed(() => {
    return rankingList.value.filter(item => item.isEligible).length;
});

const exportToCSV = () => {
  if (rankingList.value.length === 0) {
    alert("Tidak ada data untuk diexport");
    return;
  }

  // Headers
  const headers = ["Rank", "Nama Penerima", "Kecamatan", "Skor Akhir", "Status"];
  
  // Rows
  const rows = rankingList.value.map((item, index) => [
    index + 1,
    `"${item.name}"`, 
    `"${item.kecamatan}"`,
    item.score.toFixed(4),
    item.isEligible ? "LAYAK" : "TIDAK LAYAK"
  ]);

  // Combine
  const csvContent = [
    headers.join(","),
    ...rows.map(e => e.join(","))
  ].join("\n");

  // Create Blob
  const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
  const url = URL.createObjectURL(blob);
  
  // Create Link and Click
  const link = document.createElement("a");
  link.setAttribute("href", url);
  link.setAttribute("download", "laporan_perangkingan_bansos.csv");
  link.style.visibility = "hidden";
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
};

onMounted(() => {
    fetchRankings();
});
</script>
