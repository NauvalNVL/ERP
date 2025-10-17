<template>
  <div class="w-full">
    <div class="flex items-center justify-between mb-3">
      <div class="text-sm text-gray-600">{{ rows.length }} DO in current period</div>
      <div class="space-x-2">
        <button class="px-3 py-1.5 bg-gray-100 border rounded text-sm" @click="refresh" :disabled="loading">Refresh</button>
        <button class="px-3 py-1.5 bg-blue-600 text-white rounded text-sm disabled:opacity-50" @click="emitPrepare" :disabled="selected.length === 0 || loading">Prepare Invoice ({{ selected.length }})</button>
      </div>
    </div>
    <div class="overflow-auto border rounded">
      <table class="min-w-full text-sm">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-3 py-2 w-10"><input type="checkbox" :checked="allChecked" @change="toggleAll($event)" /></th>
            <th class="px-3 py-2 text-left">DO Number</th>
            <th class="px-3 py-2 text-left">Date</th>
            <th class="px-3 py-2 text-left">Customer</th>
            <th class="px-3 py-2 text-right">Amount</th>
            <th class="px-3 py-2 text-left">Currency</th>
            <th class="px-3 py-2 text-left">Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="row in rows" :key="row.do_number" class="border-t">
            <td class="px-3 py-2"><input type="checkbox" :value="row.do_number" v-model="selected" /></td>
            <td class="px-3 py-2 font-medium">{{ row.do_number }}</td>
            <td class="px-3 py-2">{{ row.do_date }}</td>
            <td class="px-3 py-2 truncate">{{ row.customer_code }} - {{ row.customer_name }}</td>
            <td class="px-3 py-2 text-right">{{ formatAmount(row.amount) }}</td>
            <td class="px-3 py-2">{{ row.currency }}</td>
            <td class="px-3 py-2"><span :class="badgeClass(row.status)">{{ row.status || '—' }}</span></td>
          </tr>
          <tr v-if="!loading && rows.length === 0">
            <td colspan="7" class="px-3 py-6 text-center text-gray-500">No DO found for current period</td>
          </tr>
          <tr v-if="loading">
            <td colspan="7" class="px-3 py-6 text-center text-gray-500">Loading...</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed, watch } from 'vue';

const props = defineProps({
  customerCode: { type: String, default: '' }
});

const emit = defineEmits(['prepare']);

const rows = ref([]);
const loading = ref(false);
const selected = ref([]);

const allChecked = computed(() => rows.value.length > 0 && selected.value.length === rows.value.length);

const toggleAll = (e) => {
  if (e.target.checked) {
    selected.value = rows.value.map(r => r.do_number);
  } else {
    selected.value = [];
  }
};

const formatAmount = (n) => {
  const val = Number(n || 0);
  return new Intl.NumberFormat('id-ID').format(val);
};

const badgeClass = (s) => {
  if (s === 'Invoiced') return 'inline-block px-2 py-0.5 rounded bg-green-100 text-green-700';
  return 'inline-block px-2 py-0.5 rounded bg-yellow-100 text-yellow-700';
};

const refresh = async () => {
  loading.value = true;
  try {
    let url = '/api/invoices/current-period-do';
    if (props.customerCode) {
      url += `?customer_code=${encodeURIComponent(props.customerCode)}`;
    }
    const res = await fetch(url, { headers: { Accept: 'application/json' } });
    rows.value = res.ok ? await res.json() : [];
  } catch (e) {
    rows.value = [];
  } finally {
    loading.value = false;
  }
};

const emitPrepare = () => {
  emit('prepare', selected.value.slice());
};

// Watch for customer code changes and refresh
watch(() => props.customerCode, () => {
  refresh();
});

onMounted(refresh);
</script>

<style scoped>
</style>


