<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Laporan Akhir</h2>
        <p class="text-gray-500 text-sm mt-1">Cetak dan unduh laporan hasil penilaian SPK Bansos.</p>
      </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 no-print">
      <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
          <h3 class="font-bold text-gray-900 mb-2">Laporan Penerima Bantuan</h3>
          <p class="text-gray-500 text-sm mb-4">Daftar lengkap penduduk yang dinyatakan layak menerima bantuan berdasarkan kuota.</p>
        </div>
        <div class="flex gap-3">
          <button 
            @click="exportPDF"
            :disabled="loading"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-red-50 text-red-700 font-medium rounded-lg text-sm hover:bg-red-100 transition-colors border border-red-100 disabled:opacity-50">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
            </svg>
            Export PDF
          </button>
           <button 
            @click="exportExcel"
            :disabled="loading"
            class="flex-1 flex items-center justify-center gap-2 px-4 py-2 bg-green-50 text-green-700 font-medium rounded-lg text-sm hover:bg-green-100 transition-colors border border-green-100 disabled:opacity-50">
             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            Export Excel
          </button>
        </div>
      </div>

       <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-between">
        <div>
          <h3 class="font-bold text-gray-900 mb-2">Laporan Detail Perhitungan</h3>
          <p class="text-gray-500 text-sm mb-4">Dokumen rinci mencakup matriks perbandingan, bobot kriteria, dan perhitungan skor.</p>
        </div>
        <button 
          @click="printDocument"
          class="w-full flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
          </svg>
          Cetak Dokumen Lengkap
        </button>
      </div>
    </div>

    <!-- Generated History -->
    <div class="no-print">
      <h3 class="font-bold text-gray-800 mb-4">Riwayat Laporan</h3>
      <div v-if="loading" class="flex justify-center py-12">
          <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-teal-600"></div>
      </div>
      <div v-else class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
        <table class="w-full text-sm text-left">
          <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
            <tr>
              <th class="px-6 py-4">Nama Laporan</th>
              <th class="px-6 py-4">Tanggal Dibuat</th>
              <th class="px-6 py-4">Oleh</th>
              <th class="px-6 py-4 text-center">Tipe</th>
              <th class="px-6 py-4 text-right">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-100">
            <tr v-for="item in historyList" :key="item.id" class="hover:bg-gray-50 transition-colors">
              <td class="px-6 py-4 font-bold text-gray-900">{{ item.name }}</td>
              <td class="px-6 py-4 text-gray-500">{{ item.date }}</td>
              <td class="px-6 py-4 text-gray-600">
                <div class="flex items-center gap-2">
                   <div class="w-5 h-5 rounded-full bg-teal-100 flex items-center justify-center text-[10px] text-teal-700 font-bold">
                     {{ item.author.charAt(0) }}
                   </div>
                   <span>{{ item.author }}</span>
                </div>
              </td>
              <td class="px-6 py-4 text-center">
                 <span 
                  class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-xs font-semibold uppercase tracking-wide"
                  :class="item.type === 'PDF' ? 'bg-red-50 text-red-600' : 'bg-green-50 text-green-600'"
                 >
                   {{ item.type }}
                 </span>
              </td>
              <td class="px-6 py-4 text-right">
                <button 
                  @click="downloadReport(item)"
                  class="text-teal-600 hover:text-teal-800 text-xs font-bold">
                  Unduh
                </button>
              </td>
            </tr>
            <tr v-if="historyList.length === 0">
              <td colspan="5" class="px-6 py-8 text-center text-gray-500 italic">Belum ada riwayat laporan.</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Hidden Print Section (For "Cetak Dokumen Lengkap") -->
    <div id="print-area" class="hidden print:block p-8">
        <div class="text-center mb-10 border-b-2 border-gray-900 pb-6">
            <h1 class="text-3xl font-black uppercase mb-1">Pemerintah Kabupaten Bandung</h1>
            <p class="text-lg">Sistem Pendukung Keputusan Penentuan Penerima Bansos (AHP)</p>
            <p class="text-sm text-gray-600 mt-2">Jl. Raya Soreang No. 1, Bandung, Jawa Barat</p>
        </div>

        <div class="mb-8">
            <h2 class="text-xl font-bold border-l-4 border-gray-900 pl-3 mb-4">I. Ringkasan Penerima Bantuan</h2>
            <table class="w-full border-collapse border border-gray-400 text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-400 p-2 text-left">Rank</th>
                        <th class="border border-gray-400 p-2 text-left">Nama Lengkap</th>
                        <th class="border border-gray-400 p-2 text-left">Kecamatan</th>
                        <th class="border border-gray-400 p-2 text-center">Skor Akhir</th>
                        <th class="border border-gray-400 p-2 text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(item, index) in rankings" :key="item.id">
                        <td class="border border-gray-400 p-2">{{ index + 1 }}</td>
                        <td class="border border-gray-400 p-2 font-bold">{{ item.name }}</td>
                        <td class="border border-gray-400 p-2">{{ item.kecamatan }}</td>
                        <td class="border border-gray-400 p-2 text-center font-mono">{{ item.score.toFixed(4) }}</td>
                        <td class="border border-gray-400 p-2 text-center">{{ item.isEligible ? 'LAYAK' : 'TIDAK LAYAK' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="mt-20 flex justify-between">
            <div class="text-center w-48">
                <p class="mb-16">Mengetahui,</p>
                <div class="border-b border-gray-900 font-bold">Admin Utama</div>
                <p class="text-xs text-gray-500">NIP: 19800101 200501 1 001</p>
            </div>
            <div class="text-center w-48">
                <p class="mb-16">Bandung, {{ new Date().toLocaleDateString('id-ID', {day: 'numeric', month: 'long', year: 'numeric'}) }}</p>
                <div class="border-b border-gray-900 font-bold">Budi Santoso</div>
                <p class="text-xs text-gray-500">Ketua Pelaksana</p>
            </div>
        </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue';
import jsPDF from 'jspdf';
import 'jspdf-autotable';
import * as XLSX from 'xlsx';

const historyList = ref([]);
const rankings = ref([]);
const loading = ref(false);

const fetchData = async () => {
    loading.value = true;
    try {
        // Fetch History
        const historyRes = await fetch('http://localhost:8000/api/reports.php');
        if (historyRes.ok) historyList.value = await historyRes.json();

        // Fetch Rankings for exports
        const rankingRes = await fetch('http://localhost:8000/api/rankings.php');
        if (rankingRes.ok) rankings.value = await rankingRes.json();
    } catch (e) {
        console.error("Error fetching data:", e);
        alert("Gagal mengambil data dari server.");
    } finally {
        loading.value = false;
    }
};

const exportPDF = () => {
    if (rankings.value.length === 0) return alert("Tidak ada data untuk diexport");

    const doc = new jsPDF();
    
    // Header
    doc.setFontSize(18);
    doc.text('LAPORAN PENERIMA BANTUAN SOSIAL', 14, 20);
    doc.setFontSize(10);
    doc.text('Dihasilkan secara otomatis oleh Sistem SPK Bansos', 14, 28);
    doc.text(`Tanggal Cetak: ${new Date().toLocaleString('id-ID')}`, 14, 34);
    
    // Table
    const tableData = rankings.value.map((item, index) => [
        index + 1,
        item.name,
        item.kecamatan,
        item.score.toFixed(4),
        item.isEligible ? 'LAYAK' : 'TIDAK LAYAK'
    ]);

    doc.autoTable({
        startY: 40,
        head: [['Rank', 'Nama Penerima', 'Kecamatan', 'Skor Akhir', 'Status']],
        body: tableData,
        theme: 'striped',
        headStyles: { fillStyle: [13, 148, 136] }, // Teal-600
    });

    doc.save(`Laporan_Penerima_Bansos_${new Date().getTime()}.pdf`);
};

const exportExcel = () => {
    if (rankings.value.length === 0) return alert("Tidak ada data untuk diexport");

    const data = rankings.value.map((item, index) => ({
        'Rank': index + 1,
        'Nama Penerima': item.name,
        'Kecamatan': item.kecamatan,
        'Skor Akhir': item.score.toFixed(4),
        'Status Kelayakan': item.isEligible ? 'LAYAK' : 'TIDAK LAYAK'
    }));

    const worksheet = XLSX.utils.json_to_sheet(data);
    const workbook = XLSX.utils.book_new();
    XLSX.utils.book_append_sheet(workbook, worksheet, "Penerima Bantuan");
    
    XLSX.writeFile(workbook, `Rekap_Penerima_Bansos_${new Date().getTime()}.xlsx`);
};

const printDocument = () => {
    if (rankings.value.length === 0) return alert("Tidak ada data untuk dicetak");
    window.print();
};

const downloadReport = (item) => {
    alert(`Mengunduh laporan: ${item.name}\n(Simulasi: File sedang disiapkan...)`);
    // In real app, this would be a link to storage or generated on the fly
};

onMounted(() => {
    fetchData();
});
</script>

<style>
@media print {
  .no-print {
    display: none !important;
  }
  body {
    background: white !important;
  }
}
</style>
