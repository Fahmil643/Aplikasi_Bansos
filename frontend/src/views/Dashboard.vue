<template>
  <div class="space-y-6">
    <!-- Hero Section -->
    <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col md:flex-row items-center justify-between gap-6">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Proses Penilaian Bantuan Sosial</h2>
        <p class="text-gray-500 text-sm mt-1 max-w-xl">Monitor status perhitungan bobot kriteria dan perangkingan alternatif terbaru.</p>
      </div>
      <div class="flex items-center gap-3 w-full md:w-auto">
        <button @click="$emit('change-page', 'criteria')" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-white border border-gray-200 text-gray-700 font-medium rounded-xl text-sm hover:bg-gray-50 transition-colors w-full md:w-auto">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M440-160q-17 0-28.5-11.5T400-200v-240L168-736q-15-20-4.5-42t36.5-22h560q26 0 36.5 22t-4.5 42L560-440v240q0 17-11.5 28.5T520-160h-80Zm40-308 198-252H282l198 252Zm0 0Z"/></svg>
          Atur Kriteria
        </button>
        <button @click="$emit('change-page', 'matrix')" class="flex items-center justify-center gap-2 px-4 py-2.5 bg-teal-700 text-white font-medium rounded-xl text-sm hover:bg-teal-800 transition-colors shadow-lg shadow-teal-700/20 w-full md:w-auto">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          Mulai Perhitungan Baru
        </button>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <StatCard 
        title="Total Kriteria" 
        :value="stats.total_kriteria" 
        description="Penghasilan, Tanggungan, dll"
        colorClass="bg-teal-500"
        iconBgClass="bg-teal-50"
        iconTextClass="text-teal-600"
      >
        <template #icon>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
          </svg>
        </template>
      </StatCard>

      <StatCard 
        title="Alternatif / Calon" 
        :value="stats.total_alternatif.toLocaleString()" 
        subtitle="+12 Baru"
        subtitleColor="text-green-500"
        colorClass="bg-blue-500"
        iconBgClass="bg-blue-50"
        iconTextClass="text-blue-600"
      >
        <template #icon>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
        </template>
      </StatCard>

      <StatCard 
        title="Rasio Konsistensi" 
        :value="stats.rasio_konsistensi" 
        subtitle="Konsisten (CR < 0.1)"
        subtitleColor="text-green-600 bg-green-100 px-2 py-0.5 rounded text-[10px]"
        colorClass="bg-amber-500"
        iconBgClass="bg-amber-50"
        iconTextClass="text-amber-600"
      >
        <template #icon>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
        </template>
      </StatCard>

      <StatCard 
        title="Layak Bantuan" 
        :value="stats.layak_bantuan" 
        description="Kuota tersedia: 1000"
        colorClass="bg-purple-500"
        iconBgClass="bg-purple-50"
        iconTextClass="text-purple-600"
      >
        <template #icon>
          <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
          </svg>
        </template>
      </StatCard>
    </div>

    <!-- Bottom Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      
      <!-- Chart / Distribution -->
      <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <div>
            <h3 class="font-bold text-gray-900">Distribusi Bobot Kriteria Prioritas</h3>
            <p class="text-xs text-gray-500">Nilai Eigenvector Normalisasi</p>
          </div>
          <button class="text-gray-400 hover:text-gray-600">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
          </button>
        </div>
        
        <div class="space-y-6">
          <div v-for="(item, index) in criteria" :key="index">
            <div class="flex justify-between items-end mb-1">
              <span class="text-sm font-semibold text-gray-700">{{ item.name }}</span>
              <span class="text-sm font-bold text-teal-600">{{ item.value }}%</span>
            </div>
            <div class="h-2 w-full bg-gray-100 rounded-full overflow-hidden">
              <div 
                class="h-full rounded-full transition-all duration-1000 ease-out" 
                :class="item.color" 
                :style="{ width: item.value + '%' }"
              ></div>
            </div>
          </div>
        </div>
      </div>

      <!-- Top Ranking -->
      <div class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm flex flex-col">
        <div class="mb-4">
          <h3 class="font-bold text-gray-900">Top 5 Alternatif</h3>
          <p class="text-xs text-gray-500">Berdasarkan skor global AHP tertinggi</p>
        </div>

        <div class="flex-1 space-y-4">
          <div v-for="(alt, index) in topAlternatives" :key="index" class="flex items-center gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors cursor-default" :class="index === 0 ? 'bg-yellow-50 border border-yellow-100' : ''">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-bold text-sm" :class="index === 0 ? 'bg-yellow-100 text-yellow-700' : 'bg-gray-100 text-gray-500'">
              {{ index + 1 }}
            </div>
            <div class="flex-1">
              <h4 class="text-sm font-bold text-gray-900">{{ alt.name }}</h4>
              <p class="text-xs text-gray-500">{{ alt.kecamatan }}</p>
            </div>
            <div class="text-right">
              <span class="block text-sm font-bold" :class="index === 0 ? 'text-teal-700' : 'text-gray-900'">{{ alt.score }}</span>
              <span class="text-[10px] text-gray-400 uppercase">Score</span>
            </div>
          </div>
        </div>

        <button class="mt-6 w-full py-2 text-xs font-bold text-teal-700 hover:bg-teal-50 rounded-lg transition-colors uppercase tracking-wider">
          Lihat Semua Ranking
        </button>
      </div>
    
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import StatCard from '../components/StatCard.vue';

const emit = defineEmits(['change-page']);

const stats = ref({
  total_kriteria: 0,
  total_alternatif: 0,
  rasio_konsistensi: 0,
  layak_bantuan: 0
});

const criteria = ref([]);
const topAlternatives = ref([]);
let pollingInterval = null;

const fetchData = async () => {
    try {
        const response = await fetch('http://localhost:8000/api/dashboard.php');
        if (response.ok) {
            const data = await response.json();
            
            // Update Stats
            if (data.stats) {
                stats.value = data.stats;
            }

            // Update Criteria
            if (data.criteria_distribution) {
                criteria.value = data.criteria_distribution; 
            }

            // Update Top Alternatives
            if (data.top_alternatives) {
                topAlternatives.value = data.top_alternatives;
            }
        }
    } catch (e) {
        console.log("Backend not running or CORS issue");
    }
};

onMounted(() => {
    fetchData(); // Initial fetch
    // Auto-refresh every 2 seconds
    pollingInterval = setInterval(fetchData, 2000);
});

onUnmounted(() => {
    if (pollingInterval) {
        clearInterval(pollingInterval);
    }
});
</script>
