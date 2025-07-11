<template>
  <TransitionRoot appear :show="show" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-50">
      <TransitionChild
        as="template"
        enter="duration-300 ease-out"
        enter-from="opacity-0"
        enter-to="opacity-100"
        leave="duration-200 ease-in"
        leave-from="opacity-100"
        leave-to="opacity-0"
      >
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>

      <div class="fixed inset-0 overflow-y-auto">
        <div
          class="flex min-h-full items-center justify-center p-4 text-center"
        >
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel
              class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all"
            >
              <DialogTitle
                as="h3"
                class="text-lg font-medium leading-6 text-gray-900"
              >
                ISO Currency Code
              </DialogTitle>
              <div class="mt-2">
                    <input
                        type="text"
                        v-model="searchTerm"
                  placeholder="Search by code or name..."
                  class="w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                />
            </div>

              <div class="mt-4 max-h-96 overflow-y-auto">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50 sticky top-0">
                    <tr>
                      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISO Currency Code</th>
                      <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ISO Currency Name</th>
                        </tr>
                    </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-if="loading" class="px-4 py-4 text-center">
                        <td colspan="2">Loading...</td>
                        </tr>
                    <tr v-for="currency in filteredCurrencies" :key="currency.iso_code" @click="selectedCurrency = currency" :class="{'bg-blue-100': selectedCurrency && selectedCurrency.iso_code === currency.iso_code}" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-4 py-2 whitespace-nowrap text-sm font-medium">{{ currency.iso_code }}</td>
                      <td class="px-4 py-2 whitespace-nowrap text-sm">{{ currency.currency_name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

              <div class="mt-4 flex justify-end space-x-2">
                    <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-gray-200 px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-gray-500 focus-visible:ring-offset-2"
                  @click="closeModal"
                >
                  Exit
                    </button>
                    <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="selectCurrency"
                  :disabled="!selectedCurrency"
                >
                  Select
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
import { ref, watch, computed, onMounted } from 'vue'
import {
  TransitionRoot,
  TransitionChild,
  Dialog,
  DialogPanel,
  DialogTitle,
} from '@headlessui/vue'

const props = defineProps({
  show: Boolean,
})

const emit = defineEmits(['close', 'select'])

const isoCurrencies = ref([])
const loading = ref(true)
const searchTerm = ref('')
const selectedCurrency = ref(null)

const fetchIsoCurrencies = async () => {
    loading.value = true;
    try {
        const response = await fetch('/api/iso-currencies');
        if (response.ok) {
            isoCurrencies.value = await response.json();
        } else {
            console.error("Failed to fetch ISO currencies");
        }
    } catch (error) {
        console.error("Error fetching ISO currencies:", error);
    } finally {
        loading.value = false;
    }
}

const filteredCurrencies = computed(() => {
    if (!searchTerm.value) {
        return isoCurrencies.value;
    }
    const search = searchTerm.value.toLowerCase();
    return isoCurrencies.value.filter(currency => {
        const codeMatch = currency.iso_code.toLowerCase().includes(search);
        const nameMatch = currency.currency_name.toLowerCase().includes(search);
        return codeMatch || nameMatch;
    });
});

function closeModal() {
  emit('close')
}

function selectCurrency() {
    if(selectedCurrency.value) {
        emit('select', selectedCurrency.value)
        closeModal()
    }
}

watch(() => props.show, (newValue) => {
    if (newValue && isoCurrencies.value.length === 0) {
        fetchIsoCurrencies()
    }
})

onMounted(() => {
    if (props.show) {
        fetchIsoCurrencies()
    }
})
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}
</style> 