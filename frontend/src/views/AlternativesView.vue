<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Data Alternatif</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola data calon penerima bantuan sosial.</p>
      </div>
      <div class="flex gap-3">
         <button class="flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
          </svg>
          Import Data
        </button>
        <button @click="openModal()" class="flex items-center justify-center gap-2 px-4 py-2 bg-teal-700 text-white font-medium rounded-lg text-sm hover:bg-teal-800 transition-colors">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
          </svg>
          Tambah Calon
        </button>
      </div>
    </div>

    <!-- Filters & Search -->
    <div class="flex flex-col sm:flex-row gap-4 bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
      <div class="relative flex-1">
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
        <input 
          v-model="searchQuery"
          type="text" 
          class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-1 focus:ring-teal-500 focus:border-teal-500 sm:text-sm transition-colors" 
          placeholder="Cari nama atau NIK..." 
        />
      </div>
      <div class="sm:w-48">
        <select v-model="selectedKecamatan" class="block w-full pl-3 pr-10 py-2 text-base border-gray-200 focus:outline-none focus:ring-teal-500 focus:border-teal-500 sm:text-sm rounded-lg bg-gray-50">
          <option>Semua Kecamatan</option>
          <option>Kec. Hegarmanah</option>
          <option>Kec. Sukasari</option>
          <option>Kec. Cidadap</option>
          <option>Kec. Coblong</option>
        </select>
      </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 w-12">#</th>
            <th class="px-6 py-4">Nama Lengkap</th>
            <th class="px-6 py-4">NIK</th>
            <th class="px-6 py-4">Alamat / Kecamatan</th>
            <th class="px-6 py-4">Status</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="(item, index) in paginatedAlternatives" :key="item.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 text-gray-400">{{ (currentPage - 1) * itemsPerPage + index + 1 }}</td>
            <td class="px-6 py-4 font-bold text-gray-900">{{ item.name }}</td>
            <td class="px-6 py-4 text-gray-600 font-mono text-xs">{{ item.nik }}</td>
            <td class="px-6 py-4 text-gray-600">
              <div class="line-clamp-1">{{ item.address }}</div>
              <div class="text-xs text-gray-400 mt-0.5">{{ item.kecamatan }}</div>
            </td>
            <td class="px-6 py-4">
              <span 
                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                :class="item.status === 'active' 
                  ? 'bg-green-50 text-green-700' 
                  : 'bg-gray-100 text-gray-600'"
              >
                {{ item.status === 'active' ? 'Aktif' : 'Tidak Aktif' }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="openEvalModal(item)" class="text-teal-600 hover:text-teal-800 text-xs font-medium mr-2">
                  Input Nilai
                </button>
                <div class="h-4 w-px bg-gray-200 mx-1"></div>
                <button @click="openModal(item)" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                  </svg>
                </button>
                <button @click="deleteAlternative(item.id)" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
      
      <!-- Pagination -->
      <div class="bg-gray-50 px-6 py-4 border-t border-gray-200 flex items-center justify-between">
        <span class="text-xs text-gray-500">Menampilkan {{ showingStart }}-{{ showingEnd }} dari {{ filteredAlternatives.length }} data</span>
        <div class="flex gap-2">
          <button 
            @click="prevPage" 
            :disabled="currentPage === 1"
            class="px-3 py-1 border border-gray-200 bg-white rounded-md text-xs font-medium text-gray-500 disabled:opacity-50 hover:bg-gray-50 disabled:hover:bg-white transition-colors"
          >
            Sebelumnya
          </button>
          <button 
            @click="nextPage"
            :disabled="currentPage === totalPages"
            class="px-3 py-1 border border-gray-200 bg-white rounded-md text-xs font-medium text-gray-700 disabled:opacity-50 disabled:text-gray-400 hover:bg-gray-50 disabled:hover:bg-white transition-colors"
          >
            Selanjutnya
          </button>
        </div>
      </div>
    </div>
  </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ isEditing ? 'Edit Alternatif' : 'Tambah Alternatif' }}</h3>
        
        <form @submit.prevent="saveAlternative" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input v-model="form.name" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" placeholder="Nama lengkap..." required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">NIK</label>
            <input v-model="form.nik" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" placeholder="Nomor Induk Kependudukan" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
            <textarea v-model="form.address" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" placeholder="Alamat lengkap..."></textarea>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Kecamatan</label>
               <select v-model="form.kecamatan" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                  <option value="Kec. Hegarmanah">Kec. Hegarmanah</option>
                  <option value="Kec. Sukasari">Kec. Sukasari</option>
                  <option value="Kec. Cidadap">Kec. Cidadap</option>
                  <option value="Kec. Coblong">Kec. Coblong</option>
               </select>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
               <select v-model="form.status" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                  <option value="active">Aktif</option>
                  <option value="inactive">Tidak Aktif</option>
               </select>
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 mt-6">
            <button type="button" @click="closeModal" class="px-4 py-2 text-gray-700 font-medium hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
            <button type="submit" class="px-4 py-2 bg-teal-700 text-white font-medium rounded-xl hover:bg-teal-800 transition-colors shadow-lg shadow-teal-700/20">Simpan</button>
          </div>
        </form>
      </div>
    </div>

    <!-- Evaluation Modal -->
    <div v-if="showEvalModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl max-h-[90vh] overflow-y-auto">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Input Nilai</h3>
        <p class="text-sm text-gray-500 mb-4">Calon: <span class="font-bold text-gray-800">{{ evalForm.alternative_name }}</span></p>
        
        <form @submit.prevent="saveEvaluations" class="space-y-4">
          <div v-for="criterion in criteriaList" :key="criterion.id">
            <label class="block text-sm font-medium text-gray-700 mb-1">
                {{ criterion.name }} ({{ criterion.code }})
            </label>
            <div class="relative">
                <input 
                    v-model="evalForm.values[criterion.id]" 
                    type="number" 
                    step="0.01"
                    class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" 
                    placeholder="Masukkan nilai..." 
                    required
                >
            </div>
          </div>

          <div class="flex items-center justify-end gap-3 mt-6">
            <button type="button" @click="closeEvalModal" class="px-4 py-2 text-gray-700 font-medium hover:bg-gray-100 rounded-xl transition-colors">Batal</button>
            <button type="submit" class="px-4 py-2 bg-teal-700 text-white font-medium rounded-xl hover:bg-teal-800 transition-colors shadow-lg shadow-teal-700/20">Simpan Nilai</button>
          </div>
        </form>
      </div>
    </div>

</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const alternativesList = ref([]);
let pollingInterval = null;

// Search & Filter State
const searchQuery = ref('');
const selectedKecamatan = ref('Semua Kecamatan');

// Computed Filter
const filteredAlternatives = computed(() => {
  return alternativesList.value.filter(item => {
    const name = item.name ? item.name.toLowerCase() : '';
    const nik = item.nik ? item.nik.toString() : '';
    const query = searchQuery.value.toLowerCase();

    const matchesSearch = name.includes(query) || nik.includes(query);
    
    const matchesKecamatan = selectedKecamatan.value === 'Semua Kecamatan' || 
                             item.kecamatan === selectedKecamatan.value;

    return matchesSearch && matchesKecamatan;
  });
});

// Pagination State
const currentPage = ref(1);
const itemsPerPage = 10;

// Computed Pagination
const totalPages = computed(() => Math.ceil(filteredAlternatives.value.length / itemsPerPage));

const paginatedAlternatives = computed(() => {
  const start = (currentPage.value - 1) * itemsPerPage;
  const end = start + itemsPerPage;
  return filteredAlternatives.value.slice(start, end);
});

const showingStart = computed(() => filteredAlternatives.value.length > 0 ? (currentPage.value - 1) * itemsPerPage + 1 : 0);
const showingEnd = computed(() => Math.min(currentPage.value * itemsPerPage, filteredAlternatives.value.length));

const nextPage = () => {
  if (currentPage.value < totalPages.value) currentPage.value++;
};

const prevPage = () => {
  if (currentPage.value > 1) currentPage.value--;
};

// Modal & Form State (Alternative)
const showModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null,
  name: '',
  nik: '',
  address: '',
  kecamatan: 'Kec. Hegarmanah', // Default
  status: 'active'
});

// Modal State (Evaluation)
const showEvalModal = ref(false);
const evalForm = ref({
    alternative_id: null,
    alternative_name: '',
    values: {} // criteria_id : value
});
const criteriaList = ref([]);

// Fetch Criteria for Evaluation
const fetchCriteria = async () => {
    try {
        const response = await fetch('http://localhost:8000/api/criteria.php');
        if (response.ok) {
            criteriaList.value = await response.json();
        }
    } catch (e) {
        console.error("Error fetching criteria:", e);
    }
};

const openEvalModal = async (item) => {
    evalForm.value.alternative_id = item.id;
    evalForm.value.alternative_name = item.name;
    evalForm.value.values = {};
    
    // Fetch existing values
    try {
        const response = await fetch(`http://localhost:8000/api/evaluations.php?alternative_id=${item.id}`);
        if (response.ok) {
           const data = await response.json();
           data.forEach(ev => {
               evalForm.value.values[ev.criteria_id] = ev.value;
           });
        }
    } catch (e) {
        console.error("Error fetching existing evaluations", e);
    }
    
    showEvalModal.value = true;
};

const closeEvalModal = () => {
    showEvalModal.value = false;
};

const saveEvaluations = async () => {
    const payload = {
        alternative_id: evalForm.value.alternative_id,
        values: Object.keys(evalForm.value.values).map(key => ({
            criteria_id: key,
            value: parseFloat(evalForm.value.values[key])
        }))
    };

    try {
        const response = await fetch('http://localhost:8000/api/evaluations.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            alert("Nilai berhasil disimpan.");
            closeEvalModal();
        } else {
            alert("Gagal menyimpan nilai.");
        }
    } catch (e) {
        console.error(e);
        alert("Terjadi kesalahan koneksi.");
    }
};

// Fetch Data
const fetchAlternatives = async () => {
    try {
        const response = await fetch('http://localhost:8000/api/alternatives.php');
        if (response.ok) {
            alternativesList.value = await response.json();
        }
    } catch (e) {
        console.error("Error fetching alternatives:", e);
    }
};

// Actions
const openModal = (item = null) => {
  if (item) {
    isEditing.value = true;
    form.value = { 
        id: item.id, 
        name: item.name, 
        nik: item.nik, 
        address: item.address, 
        kecamatan: item.kecamatan, 
        status: item.status 
    };
  } else {
    isEditing.value = false;
    form.value = { 
        id: null, 
        name: '', 
        nik: '', 
        address: '', 
        kecamatan: 'Kec. Hegarmanah', 
        status: 'active' 
    };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveAlternative = async () => {
    const payload = {
        id: form.value.id,
        name: form.value.name,
        nik: form.value.nik,
        address: form.value.address,
        kecamatan: form.value.kecamatan,
        status: form.value.status
    };

    const method = isEditing.value ? 'PUT' : 'POST';
    
    try {
        const response = await fetch('http://localhost:8000/api/alternatives.php', {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            await fetchAlternatives(); // Refresh immediately
            closeModal();
        } else {
            const errorData = await response.json();
            alert("Gagal menyimpan data: " + (errorData.message || "Unknown error"));
        }
    } catch (e) {
        console.error(e);
        alert("Terjadi kesalahan koneksi.");
    }
};

const deleteAlternative = async (id) => {
    if (!confirm("Apakah Anda yakin ingin menghapus data ini?")) return;

    try {
        const response = await fetch(`http://localhost:8000/api/alternatives.php?id=${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            await fetchAlternatives();
        } else {
            alert("Gagal menghapus data.");
        }
    } catch (e) {
        console.error(e);
        alert("Terjadi kesalahan koneksi.");
    }
};

// Lifecycle
onMounted(() => {
    fetchAlternatives();
    fetchCriteria(); // Load criteria for evaluation modal
    pollingInterval = setInterval(fetchAlternatives, 5000); // Poll every 5 seconds
});

onUnmounted(() => {
    if (pollingInterval) clearInterval(pollingInterval);
});
</script>
