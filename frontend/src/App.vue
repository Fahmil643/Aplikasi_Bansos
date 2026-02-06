<script setup>
import { ref, computed } from 'vue';
import Sidebar from './components/Sidebar.vue'
import Header from './components/Header.vue'
import Dashboard from './views/Dashboard.vue'
import CriteriaView from './views/CriteriaView.vue'
import AlternativesView from './views/AlternativesView.vue'
import MatrixView from './views/MatrixView.vue'
import RankingView from './views/RankingView.vue'
import ReportView from './views/ReportView.vue'
import ConfigView from './views/ConfigView.vue'
import PlaceholderView from './components/PlaceholderView.vue'

const activePage = ref('dashboard');

const pageTitles = {
  dashboard: 'Dashboard Utama',
  criteria: 'Kriteria & Bobot',
  alternatives: 'Data Alternatif',
  matrix: 'Matriks Perbandingan',
  ranking: 'Hasil Perangkingan',
  report: 'Laporan Akhir',
  config: 'Konfigurasi SPK',
};

const pageTitle = computed(() => pageTitles[activePage.value] || 'Halaman Belum Ada');
</script>

<template>
  <div class="flex h-screen bg-gray-50 font-sans text-gray-900">
    <Sidebar :current-page="activePage" @change-page="(id) => activePage = id" />
    <div class="flex-1 flex flex-col ml-64 min-w-0"> <!-- ml-64 to offset fixed sidebar -->
      <Header />
      <main class="flex-1 p-8 overflow-y-auto">
        <Dashboard v-if="activePage === 'dashboard'" @change-page="(id) => activePage = id" />
        <CriteriaView v-else-if="activePage === 'criteria'" />
        <AlternativesView v-else-if="activePage === 'alternatives'" />
        <MatrixView v-else-if="activePage === 'matrix'" />
        <RankingView v-else-if="activePage === 'ranking'" />
        <ReportView v-else-if="activePage === 'report'" />
        <ConfigView v-else-if="activePage === 'config'" />
        <PlaceholderView v-else :title="pageTitle" />
      </main>
    </div>
  </div>
</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');

:root {
  font-family: 'Inter', sans-serif;
}
</style>
