<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Kriteria & Bobot</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola kriteria penilaian dan bobot prioritas AHP.</p>
      </div>
      <button @click="openModal()" class="flex items-center justify-center gap-2 px-4 py-2 bg-teal-700 text-white font-medium rounded-lg text-sm hover:bg-teal-800 transition-colors">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Tambah Kriteria
      </button>
    </div>

    <!-- Alert Total Bobot -->
    <div v-if="totalWeight !== 100" class="flex items-center justify-between p-4 rounded-xl border border-amber-200 bg-amber-50 text-amber-700">
      <div class="flex items-center gap-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
          <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <span class="text-sm font-medium">Total bobot saat ini {{ totalWeight }}%. Pastikan total bobot mencapai 100% untuk hasil yang akurat.</span>
      </div>
      <!-- <button class="text-sm font-bold text-amber-800 hover:text-amber-900">Perbaiki</button> -->
    </div>

    <!-- Data Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">
      <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 font-medium border-b border-gray-200">
          <tr>
            <th class="px-6 py-4 w-20">Kode</th>
            <th class="px-6 py-4">Nama Kriteria</th>
            <th class="px-6 py-4 w-32">Bobot (%)</th>
            <th class="px-6 py-4 w-32">Atribut</th>
            <th class="px-6 py-4 text-right">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
          <tr v-for="item in criteriaList" :key="item.id" class="hover:bg-gray-50 transition-colors">
            <td class="px-6 py-4 font-bold text-gray-900">{{ item.code }}</td>
            <td class="px-6 py-4 text-gray-800">{{ item.name }}</td>
            <td class="px-6 py-4">
              <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-50 text-blue-700">
                {{ (item.weight * 100).toFixed(0) }}%
              </span>
            </td>
            <td class="px-6 py-4">
              <span 
                class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium border"
                :class="item.type === 'benefit' 
                  ? 'bg-emerald-50 text-emerald-700 border-emerald-100' 
                  : 'bg-rose-50 text-rose-700 border-rose-100'"
              >
                {{ item.type === 'benefit' ? 'Benefit' : 'Cost' }}
              </span>
            </td>
            <td class="px-6 py-4 text-right">
              <div class="flex items-center justify-end gap-2">
                <button @click="openModal(item)" class="p-1.5 text-gray-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-lg transition-colors">
                   <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                  </svg>
                </button>
                <button @click="deleteCriteria(item.id)" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                  </svg>
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Form -->
    <div v-if="showModal" class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center p-4">
      <div class="bg-white rounded-2xl w-full max-w-md p-6 shadow-xl">
        <h3 class="text-lg font-bold text-gray-900 mb-4">{{ isEditing ? 'Edit Kriteria' : 'Tambah Kriteria' }}</h3>
        
        <form @submit.prevent="saveCriteria" class="space-y-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Kode</label>
            <input v-model="form.code" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" placeholder="Contoh: K1" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kriteria</label>
            <input v-model="form.name" type="text" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" placeholder="Nama kriteria..." required>
          </div>
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Bobot (%)</label>
              <input v-model.number="form.weightVal" type="number" step="0.1" max="100" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none" placeholder="0-100" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-1">Atribut</label>
               <select v-model="form.type" class="w-full px-3 py-2 border border-gray-200 rounded-xl focus:ring-2 focus:ring-teal-500 focus:border-teal-500 outline-none">
                  <option value="benefit">Benefit</option>
                  <option value="cost">Cost</option>
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

  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue';

const criteriaList = ref([]);
let pollingInterval = null;

// Modal & Form State
const showModal = ref(false);
const isEditing = ref(false);
const form = ref({
  id: null,
  code: '',
  name: '',
  weightVal: 0, // Stores percentage 0-100
  type: 'benefit'
});

// Fetch Data
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

// Computed
const totalWeight = computed(() => {
  return Math.round(criteriaList.value.reduce((acc, item) => acc + (parseFloat(item.weight) * 100), 0));
});

// Actions
const openModal = (item = null) => {
  if (item) {
    isEditing.value = true;
    form.value = { 
        id: item.id, 
        code: item.code, 
        name: item.name, 
        weightVal: item.weight * 100, 
        type: item.type 
    };
  } else {
    isEditing.value = false;
    form.value = { id: null, code: '', name: '', weightVal: 0, type: 'benefit' };
  }
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
};

const saveCriteria = async () => {
    const payload = {
        id: form.value.id,
        code: form.value.code,
        name: form.value.name,
        weight: form.value.weightVal / 100, // Convert % to decimal
        type: form.value.type
    };

    const method = isEditing.value ? 'PUT' : 'POST';
    
    try {
        const response = await fetch('http://localhost:8000/api/criteria.php', {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            await fetchCriteria(); // Refresh immediately
            closeModal();
        } else {
            alert("Gagal menyimpan data.");
        }
    } catch (e) {
        console.error(e);
        alert("Terjadi kesalahan koneksi.");
    }
};

const deleteCriteria = async (id) => {
    if (!confirm("Apakah Anda yakin ingin menghapus kriteria ini?")) return;

    try {
        const response = await fetch(`http://localhost:8000/api/criteria.php?id=${id}`, {
            method: 'DELETE'
        });

        if (response.ok) {
            await fetchCriteria();
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
    fetchCriteria();
    pollingInterval = setInterval(fetchCriteria, 2000);
});

onUnmounted(() => {
    if (pollingInterval) clearInterval(pollingInterval);
});
</script>
