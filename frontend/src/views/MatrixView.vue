<template>
  <div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
      <div>
        <h2 class="text-xl font-bold text-gray-900">Matriks Perbandingan Berpasangan</h2>
        <p class="text-gray-500 text-sm mt-1">Input nilai perbandingan antar kriteria untuk menghitung bobot prioritas.</p>
      </div>
      <div class="flex gap-3">
        <button @click="resetValues" class="flex items-center justify-center gap-2 px-4 py-2 bg-white border border-gray-200 text-gray-700 font-medium rounded-lg text-sm hover:bg-gray-50 transition-colors">
          Reset Nilai
        </button>
        <button @click="calculateAHP" :disabled="isSaving" class="flex items-center justify-center gap-2 px-4 py-2 bg-teal-700 text-white font-medium rounded-lg text-sm hover:bg-teal-800 transition-colors shadow-sm disabled:opacity-50">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 36v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
          </svg>
          {{ isSaving ? 'Menyimpan...' : 'Hitung & Simpan' }}
        </button>
      </div>
    </div>

    <!-- Results Alert -->
    <div v-if="results" class="p-4 rounded-xl border flex items-start gap-3" 
        :class="results.isConsistent ? 'bg-green-50 border-green-200 text-green-800' : 'bg-red-50 border-red-200 text-red-800'">
        <svg v-if="results.isConsistent" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
        </svg>
        <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mt-0.5" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
        </svg>
        <div>
            <h4 class="font-bold text-sm">{{ results.isConsistent ? 'Konsistensi Diterima' : 'Tidak Konsisten' }}</h4>
            <p class="text-xs mt-1">Consistency Ratio (CR): <strong>{{ results.CR.toFixed(4) }}</strong>. {{ results.isConsistent ? 'Bobot telah diperbarui.' : 'Mohon tinjau kembali perbandingan Anda agar CR <= 0.1.' }}</p>
        </div>
    </div>

    <!-- Matrix Table -->
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden p-6">
      <div class="flex items-center justify-between mb-6">
        <h3 class="font-bold text-gray-800">Perbandingan Kriteria</h3>
        <span class="text-xs px-2 py-1 bg-blue-50 text-blue-700 rounded border border-blue-100 font-medium">Skala Saaty (1-9)</span>
      </div>

      <div class="overflow-x-auto">
        <table class="w-full text-sm text-center border-collapse">
          <thead>
            <tr>
              <th class="p-3 border border-gray-100 bg-gray-50 text-gray-500 font-medium text-left min-w-[150px]">Kriteria</th>
              <th v-for="(col, index) in criteria" :key="index" class="p-3 border border-gray-100 bg-gray-50 text-gray-700 font-bold min-w-[80px]">
                {{ col.code }}
              </th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(row, rowIndex) in criteria" :key="rowIndex">
              <td class="p-3 border border-gray-100 font-bold text-gray-700 text-left bg-gray-50">
                <div class="flex flex-col">
                  <span>{{ row.code }}</span>
                  <span class="text-[10px] text-gray-400 font-normal">{{ row.name }}</span>
                </div>
              </td>
              <td v-for="(col, colIndex) in criteria" :key="colIndex" class="p-2 border border-gray-100">
                <!-- Diagonal is always 1 -->
                <div v-if="rowIndex === colIndex" class="flex justify-center">
                  <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-gray-100 text-gray-500 font-bold text-xs select-none">1</span>
                </div>
                
                <!-- Lower triangle (inverse) calculation would presumably happen here or auto-filled, 
                     but for UI mockup we just show input for upper triangle or read-only/inverse for lower -->
                <div v-else-if="rowIndex < colIndex">
                  <select 
                    :value="matrix[`${rowIndex}-${colIndex}`] || 1" 
                    @change="onComparisonChange(rowIndex, colIndex, $event.target.value)"
                    class="w-full p-1.5 border border-gray-200 rounded-md text-xs focus:ring-1 focus:ring-teal-500 focus:border-teal-500">
                     <option value="1">1 - Sama</option>
                     <option value="2">2 - Nilai Antara</option>
                     <option value="3">3 - Sedikit Lebih</option>
                     <option value="4">4 - Nilai Antara</option>
                     <option value="5">5 - Lebih Penting</option>
                     <option value="6">6 - Nilai Antara</option>
                     <option value="7">7 - Sangat Penting</option>
                     <option value="8">8 - Nilai Antara</option>
                     <option value="9">9 - Mutlak</option>
                     <option value="0.33">1/3 - Sedikit Kurang</option>
                     <option value="0.2">1/5 - Kurang</option>
                  </select>
                </div>

                <div v-else class="flex justify-center">
                  <span class="text-gray-400 text-xs italic font-mono">{{ (matrix[`${rowIndex}-${colIndex}`] || 1).toFixed(2) }}</span>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <div class="mt-6 p-4 bg-gray-50 rounded-xl border border-gray-100 text-xs text-gray-500">
        <p class="font-semibold mb-1 text-gray-700">Keterangan Skala:</p>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
            <div><span class="font-bold text-gray-900">1</span> : Sama penting</div>
            <div><span class="font-bold text-gray-900">3</span> : Sedikit lebih penting</div>
            <div><span class="font-bold text-gray-900">5</span> : Lebih penting</div>
            <div><span class="font-bold text-gray-900">7</span> : Sangat lebih penting</div>
            <div><span class="font-bold text-gray-900">9</span> : Mutlak lebih penting</div>
            <div><span class="font-bold text-gray-900">2,4,6,8</span> : Nilai antara</div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from 'vue';

const criteria = ref([]);
const matrix = reactive({}); // Stores pairs "indexA-indexB" : value
const results = ref(null); // Stores calculated weights and CR
const isSaving = ref(false);

// Fetch Criteria
const fetchCriteria = async () => {
    try {
        const response = await fetch('http://localhost:8000/api/criteria.php');
        if (response.ok) {
            criteria.value = await response.json();
            initializeMatrix();
        }
    } catch (e) {
        console.error("Error fetching criteria:", e);
    }
};

const initializeMatrix = () => {
    const n = criteria.value.length;
    for (let i = 0; i < n; i++) {
        for (let j = 0; j < n; j++) {
            if (i === j) {
                matrix[`${i}-${j}`] = 1;
            } else if (i < j) {
                 // Default to 1 (Equal importance) if not set
                 if (!matrix[`${i}-${j}`]) matrix[`${i}-${j}`] = 1;
            }
        }
    }
    // Set Inverse values
    updateInverseValues();
};

const updateInverseValues = () => {
   const n = criteria.value.length;
    for (let i = 0; i < n; i++) {
        for (let j = i + 1; j < n; j++) {
            const val = parseFloat(matrix[`${i}-${j}`] || 1);
            matrix[`${j}-${i}`] = 1 / val;
        }
    }
};

// Handle Change in Dropdown (Upper Triangle)
const onComparisonChange = (rowIndex, colIndex, val) => {
    matrix[`${rowIndex}-${colIndex}`] = parseFloat(val);
    // Update inverse (lower triangle)
    matrix[`${colIndex}-${rowIndex}`] = 1 / parseFloat(val);
};

// AHP Calculation
const calculateAHP = async () => {
    const n = criteria.value.length;
    let sumCols = new Array(n).fill(0);
    let weights = new Array(n).fill(0);
    
    // 1. Sum Columns
    for (let j = 0; j < n; j++) {
        for (let i = 0; i < n; i++) {
            sumCols[j] += parseFloat(matrix[`${i}-${j}`]);
        }
    }

    // 2. Normalize Matrix & Calculate Row Averages (Weights)
    for (let i = 0; i < n; i++) {
        let rowSum = 0;
        for (let j = 0; j < n; j++) {
            rowSum += parseFloat(matrix[`${i}-${j}`]) / sumCols[j];
        }
        weights[i] = rowSum / n;
    }

    // 3. Consistency Ratio (CR) Calculation
    // Eigen Vector = Matrix * Weights
    let eigenVector = new Array(n).fill(0);
    for (let i = 0; i < n; i++) {
         for (let j = 0; j < n; j++) {
            eigenVector[i] += parseFloat(matrix[`${i}-${j}`]) * weights[j];
        }
    }

    // Lambda Max = Sum(EigenVector / Weights) / n
    let lambdaMax = 0;
    for (let i = 0; i < n; i++) {
        lambdaMax += eigenVector[i] / weights[i];
    }
    lambdaMax = lambdaMax / n;

    // CI (Consistency Index) = (Lambda Max - n) / (n - 1)
    const CI = (lambdaMax - n) / (n - 1);

    // RI (Random Index) Table
    const RI_TABLE = { 1: 0, 2: 0, 3: 0.58, 4: 0.9, 5: 1.12, 6: 1.24, 7: 1.32, 8: 1.41, 9: 1.45, 10: 1.49 };
    const RI = RI_TABLE[n] || 1.49;

    // CR (Consistency Ratio) = CI / RI
    const CR = RI === 0 ? 0 : CI / RI;

    results.value = {
        weights: weights,
        CR: CR,
        isConsistent: CR <= 0.1
    };
    
    // Save to Database
    await saveWeightsToDB(weights);
};

const saveWeightsToDB = async (weights) => {
    isSaving.value = true;
    const payload = criteria.value.map((item, index) => ({
        id: item.id,
        weight: weights[index]
    }));

    try {
        const response = await fetch('http://localhost:8000/api/update_weights.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(payload)
        });

        if (response.ok) {
            alert(results.value.isConsistent 
                ? `Perhitungan Selesai! CR: ${results.value.CR.toFixed(4)} (Konsisten). Bobot telah disimpan.` 
                : `Perhitungan Selesai! CR: ${results.value.CR.toFixed(4)} (TIDAK Konsisten). Mohon perbaiki perbandingan.`);
        } else {
             alert("Gagal menyimpan bobot ke database.");
        }
    } catch (e) {
        console.error(e);
        alert("Terjadi kesalahan koneksi.");
    } finally {
        isSaving.value = false;
    }
};

const resetValues = () => {
    if(confirm("Reset semua nilai perbandingan ke 1?")) {
        const n = criteria.value.length;
         for (let i = 0; i < n; i++) {
            for (let j = i + 1; j < n; j++) {
                 matrix[`${i}-${j}`] = 1;
                 matrix[`${j}-${i}`] = 1;
            }
        }
        results.value = null;
    }
};

onMounted(() => {
    fetchCriteria();
});
</script>
