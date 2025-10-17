<template>
  <TransitionRoot :show="open" as="template">
    <Dialog as="div" class="relative z-50" @close="close">
      <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0" enter-to="opacity-100" leave="ease-in duration-150" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <TransitionChild as="template" enter="ease-out duration-200" enter-from="opacity-0 translate-y-3 sm:scale-95" enter-to="opacity-100 translate-y-0 sm:scale-100" leave="ease-in duration-150" leave-from="opacity-100 translate-y-0 sm:scale-100" leave-to="opacity-0 translate-y-3 sm:scale-95">
            <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-xl bg-white shadow-2xl ring-1 ring-black/5">
              <div class="px-5 py-4 border-b bg-gradient-to-r from-blue-600 to-indigo-600">
                <div class="flex items-center justify-between">
                  <div class="flex items-center gap-2 text-white">
                    <CurrencyDollarIcon class="w-5 h-5"/>
                    <DialogTitle class="text-sm font-semibold">Customer Sales Tax or Sales Tax Exemption Table</DialogTitle>
                  </div>
                  <button class="text-white/90 hover:text-white" @click="close"><XIcon class="w-5 h-5"/></button>
                </div>
              </div>

              <div class="p-5">
                <div class="mb-3 flex items-center gap-3">
                  <div class="relative">
                    <SearchIcon class="w-4 h-4 text-gray-400 absolute left-2 top-2.5"/>
                    <input v-model.trim="query" type="text" placeholder="Search tax code or name" class="w-80 pl-8 pr-3 py-2 border rounded-md text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"/>
                  </div>
                  <span class="hidden sm:inline text-xs text-gray-500">Use Up/Down to navigate, Enter to select</span>
                  <span class="ml-auto text-xs text-gray-400" v-if="!loading">{{ filtered.length }} items</span>
                </div>

                <div class="overflow-hidden rounded-lg ring-1 ring-gray-200" ref="tableWrap" tabindex="0"
                     @keydown.down.prevent="move(1)" @keydown.up.prevent="move(-1)" @keydown.enter.prevent="confirm" @keydown.esc.prevent="close">
                  <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                      <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Index</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">S/Tax Code</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">S/Tax Name</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">S/Tax Exemption Reference#</th>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">Status</th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                      <tr v-if="loading">
                        <td colspan="5" class="px-3 py-8">
                          <div class="h-2 w-32 bg-gray-200 rounded animate-pulse mb-2"></div>
                          <div class="h-2 w-52 bg-gray-200 rounded animate-pulse"></div>
                        </td>
                      </tr>
                      <tr v-for="(row, idx) in filtered" :key="row._key" class="cursor-pointer hover:bg-blue-50/60" :class="idx === hi ? 'bg-blue-50' : ''" @click="setHi(idx)" @dblclick="confirm">
                        <td class="px-3 py-2 text-sm text-gray-700">{{ pad(idx + 1) }}</td>
                        <td class="px-3 py-2 text-sm font-medium text-gray-900">{{ row.code }}</td>
                        <td class="px-3 py-2 text-sm text-gray-700">{{ row.name }}</td>
                        <td class="px-3 py-2 text-sm text-gray-500">{{ row.exemption_ref || '' }}</td>
                        <td class="px-3 py-2">
                          <span :class="row.active ? 'bg-emerald-100 text-emerald-700 ring-emerald-600/20' : 'bg-gray-100 text-gray-700 ring-gray-600/20'" class="inline-flex items-center gap-1 rounded-full px-2.5 py-0.5 text-xs ring-1">
                            <CheckCircleIcon v-if="row.active" class="w-4 h-4"/>
                            <MinusCircleIcon v-else class="w-4 h-4"/>
                            {{ row.active ? 'Active' : 'Inactive' }}
                          </span>
                        </td>
                      </tr>
                      <tr v-if="!loading && filtered.length === 0">
                        <td colspan="5" class="px-3 py-10 text-center">
                          <div class="flex flex-col items-center gap-2 text-gray-500">
                            <BanIcon class="w-8 h-8 text-gray-300"/>
                            <div class="text-sm">No tax types found</div>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <div class="px-5 py-4 border-t bg-gray-50 flex items-center justify-end gap-2">
                <button class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md border hover:bg-white" @click="moreOptions" :disabled="loading">
                  <AdjustmentsIcon class="w-4 h-4"/> More Options
                </button>
                <button class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md border hover:bg-white" @click="zoom" :disabled="loading || filtered.length === 0">
                  <ArrowsExpandIcon class="w-4 h-4"/> Zoom
                </button>
                <button class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-md text-white bg-blue-600 hover:bg-blue-700 disabled:opacity-50" @click="confirm" :disabled="loading || filtered.length === 0">
                  <CheckIcon class="w-4 h-4"/> Select
                </button>
                <button class="inline-flex items-center gap-2 px-3 py-2 text-sm rounded-md border hover:bg-white" @click="close">
                  <XIcon class="w-4 h-4"/> Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref, computed, watch, onMounted } from 'vue';
import { Dialog, DialogPanel, DialogTitle, TransitionChild, TransitionRoot } from '@headlessui/vue'
import { SearchIcon, XIcon, CurrencyDollarIcon, CheckIcon, AdjustmentsIcon, ArrowsExpandIcon, CheckCircleIcon, MinusCircleIcon, BanIcon } from '@heroicons/vue/outline'

const props = defineProps({
  open: { type: Boolean, default: false },
});
const emit = defineEmits(['close', 'select']);

const loading = ref(false);
const query = ref('');
const rows = ref([]);
const hi = ref(0);
const tableWrap = ref(null);

const normalize = (items) => {
  return (items || []).map((it, i) => {
    const code = it.code || it.tax_code || it.TaxCode || it.Code || it.taxtype || it.type || it.id || '';
    const name = it.name || it.description || it.tax_name || it.TaxName || it.Name || '';
    const status = (it.status ?? it.is_active ?? it.active ?? true) ? true : false;
    return {
      _key: code + '-' + i,
      code,
      name,
      active: !!status,
      exemption_ref: it.exemption_ref || it.reference || '',
      raw: it,
    };
  });
};

const fetchData = async () => {
  loading.value = true;
  try {
    const res = await fetch('/api/material-management/tax-types', { headers: { Accept: 'application/json' } });
    const data = res.ok ? await res.json() : [];
    rows.value = normalize(Array.isArray(data) ? data : (data.data || []));
  } catch (e) {
    rows.value = [];
  } finally {
    loading.value = false;
  }
};

const filtered = computed(() => {
  const q = query.value.trim().toLowerCase();
  const list = rows.value;
  if (!q) return list;
  return list.filter(r => (r.code + ' ' + r.name).toLowerCase().includes(q));
});

const setHi = (idx) => {
  if (idx < 0 || idx >= filtered.value.length) return;
  hi.value = idx;
};
const move = (delta) => {
  if (filtered.value.length === 0) return;
  const next = (hi.value + delta + filtered.value.length) % filtered.value.length;
  hi.value = next;
};
const pad = (n) => String(n).padStart(2, '0');

const confirm = () => {
  const r = filtered.value[hi.value];
  if (!r) return;
  emit('select', r);
  emit('close');
};
const close = () => emit('close');

const moreOptions = () => {
  // Placeholder for parity with CPS
  // Could open another modal for advanced filters later
};
const zoom = () => {
  // Placeholder for parity with CPS (e.g., enlarge table)
  if (tableWrap.value) tableWrap.value.focus();
};

watch(() => props.open, (v) => {
  if (v) {
    fetchData();
    hi.value = 0;
    setTimeout(() => tableWrap.value && tableWrap.value.focus(), 0);
  }
});

onMounted(() => {
  if (props.open) fetchData();
});
</script>

<style scoped>
</style>


