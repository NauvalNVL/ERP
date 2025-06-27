<script setup>
import AppLayout from "@/Layouts/AppLayout.vue";
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import {
  TabGroup,
  TabList,
  Tab,
  TabPanels,
  TabPanel,
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue';

const props = defineProps({
    config: Object,
});

const form = useForm({ ...props.config });
const successMessage = ref('');
const errorMessage = ref('');
const processing = ref(false);

// Modal state
const isTransactionTypeModalOpen = ref(false);
const isPurchaserModalOpen = ref(false);
const isReceiveLocationModalOpen = ref(false);
const isTaxGroupModalOpen = ref(false);
const isFlowGuideModalOpen = ref(false);
const isLocationTableModalOpen = ref(false);
const isSourceCodeTableModalOpen = ref(false);
const isRcAccrualNoteModalOpen = ref(false);
const isGLDistTableModalOpen = ref(false);
const isRtLocationTableModalOpen = ref(false);
const isDnSourceCodeTableModalOpen = ref(false);
const isCnSourceCodeTableModalOpen = ref(false);
const isIsGlSourceCodeTableModalOpen = ref(false);
const isMiLocationModalOpen = ref(false);
const isMiGlSourceCodeTableModalOpen = ref(false);
const isMoGlSourceCodeTableModalOpen = ref(false);
const isOldaCategoryModalOpen = ref(false);
const isOldaSkuModalOpen = ref(false);

const categories = ref([
  'PR', 'PO', 'RC', 'RT', 'DN', 'CN', 'IS', 'MI', 'MO', 'LT', 'OLDA'
]);

// Initialize arrays for remarks if they don't exist
if (!form.rc_ap_remarks) form.rc_ap_remarks = ['', '', '', '', ''];
if (!form.rc_gl_remarks) form.rc_gl_remarks = ['', '', '', '', ''];
if (!form.rt_ap_remarks) form.rt_ap_remarks = ['', '', '', '', ''];
if (!form.rt_gl_remarks) form.rt_gl_remarks = ['', '', '', '', ''];
if (!form.dn_ap_remark) form.dn_ap_remark = '';
if (!form.dn_gl_remarks) form.dn_gl_remarks = ['', '', '', '', ''];
if (!form.cn_gl_remarks) form.cn_gl_remarks = ['', '', '', '', ''];
if (!form.cn_ap_remark) form.cn_ap_remark = '';
if (!form.is_gl_remarks) form.is_gl_remarks = ['', '', '', '', ''];
if (!form.is_cancel_option) form.is_cancel_option = '1-Allow 1 Previous Month Cancellation';
if (!form.is_gl_source) form.is_gl_source = 'GL';
if (!form.is_gl_source_desc) form.is_gl_source_desc = 'ADJ';
if (!form.is_gl_ref) form.is_gl_ref = 'IS-IS#';
if (!form.is_gl_posting) form.is_gl_posting = 'N-No';
if (!form.mi_gl_remarks) form.mi_gl_remarks = ['', '', '', '', ''];
if (!form.mo_gl_remarks) form.mo_gl_remarks = ['', '', '', '', ''];
if (!form.mi_location) form.mi_location = '';
if (!form.mi_gl_dist_edit) form.mi_gl_dist_edit = 'N';
if (!form.mi_gl_source) form.mi_gl_source = 'GL';
if (!form.mi_gl_source_desc) form.mi_gl_source_desc = 'ADJ';
if (!form.mi_gl_ref) form.mi_gl_ref = 'MI-MI#';
if (!form.mo_gl_dist_edit) form.mo_gl_dist_edit = 'N';
if (!form.mo_gl_source) form.mo_gl_source = 'GL';
if (!form.mo_gl_source_desc) form.mo_gl_source_desc = 'ADJ';
if (!form.mo_gl_ref) form.mo_gl_ref = 'MO-MO#';
if (!form.olda_online_daily_alert) form.olda_online_daily_alert = 'Y';
if (!form.olda_name) form.olda_name = 'PT. MBI';
if (!form.olda_email) form.olda_email = 'no-reply@ptmbi.com';
if (!form.olda_password) form.olda_password = '';
if (!form.olda_sku_reorder) form.olda_sku_reorder = 'Y';
if (!form.olda_po_due_delivery) form.olda_po_due_delivery = 'Y';
if (!form.olda_sku_reorder_control_step1) form.olda_sku_reorder_control_step1 = 'Y';
if (!form.olda_po_due_total_item_min) form.olda_po_due_total_item_min = 50;
if (!form.olda_po_due_total_item_max) form.olda_po_due_total_item_max = 150;

// Add DN options array
const dnApRemarkOptions = ref([
    'DN-DN#',
    'CN-SUPP CN#',
    'D1-DN Note1',
    'D2-DN Note2',
    'N-None'
]);

// Add CN options
const cnApRemarkOptions = ref([
    'CN-CN#',
    'DN-SUPP DN#',
    'C1-CN Note1',
    'C2-CN Note2',
    'N-None'
]);

// Add IS options
const isGlRemarkOptions = ref([
    'IS-IS#',
    'IR-Issue Ref#',
    'S#-SKU#',
    'SN-SKU Name',
    'SQ-SKU Qty',
    'N-None'
]);

const isCancelOptions = ref([
    '0-No Cancellation',
    '1-Allow 1 Previous Month Cancellation'
]);

const miGlRemarkOptions = ref([
    'MI-MI#',
    'MR-Miss Issue Ref#',
    'S#-SKU#',
    'SN-SKU Name',
    'SQ-SKU Qty',
    'N-None'
]);

const moGlRemarkOptions = ref([
    'N-None',
    'MO-MO#',
    'SO-Stock-Out Ref#',
    'S#-SKU#',
    'SN-SKU Name',
    'SQ-SKU Qty'
]);

const saveConfiguration = () => {
    processing.value = true;
    successMessage.value = '';
    errorMessage.value = '';

    form.put(route('mm-configs.update', props.config.id), {
        onSuccess: () => {
            processing.value = false;
            successMessage.value = 'Configuration saved successfully.';
            setTimeout(() => {
                successMessage.value = '';
            }, 3000);
        },
        onError: (errors) => {
            processing.value = false;
            errorMessage.value = Object.values(errors)[0] || 'An error occurred. Please try again.';
            setTimeout(() => {
                errorMessage.value = '';
            }, 5000);
        },
    });
};

// Modal functions
const openTransactionTypeModal = () => {
    isTransactionTypeModalOpen.value = true;
};

const openPurchaserModal = () => {
    isPurchaserModalOpen.value = true;
};

const openReceiveLocationModal = () => {
    isReceiveLocationModalOpen.value = true;
};

const openTaxGroupModal = () => {
    isTaxGroupModalOpen.value = true;
};

const openFlowGuideModal = () => {
    isFlowGuideModalOpen.value = true;
};

const openLocationTableModal = () => {
    isLocationTableModalOpen.value = true;
};

const openRtLocationTableModal = () => {
    isRtLocationTableModalOpen.value = true;
};

const openSourceCodeTableModal = () => {
    isSourceCodeTableModalOpen.value = true;
};

const openRcAccrualNoteModal = () => {
    isRcAccrualNoteModalOpen.value = true;
};

const openGLDistTableModal = () => {
    isGLDistTableModalOpen.value = true;
};

const openDnSourceCodeTableModal = () => {
    isDnSourceCodeTableModalOpen.value = true;
};

const openCnSourceCodeTableModal = () => {
    isCnSourceCodeTableModalOpen.value = true;
};

const openIsGlSourceCodeTableModal = () => {
    isIsGlSourceCodeTableModalOpen.value = true;
};

const openMiLocationModal = () => {
    isMiLocationModalOpen.value = true;
};

const openMiGlSourceCodeTableModal = () => {
    isMiGlSourceCodeTableModalOpen.value = true;
};

const openMoGlSourceCodeTableModal = () => {
    isMoGlSourceCodeTableModalOpen.value = true;
};

const openOldaCategoryModal = () => {
    isOldaCategoryModalOpen.value = true;
};

const openOldaSkuModal = () => {
    isOldaSkuModalOpen.value = true;
};

const selectTransactionType = (type) => {
    form.po_tran_type = type.code;
    isTransactionTypeModalOpen.value = false;
    isMoGlSourceCodeTableModalOpen.value = false;
};

const selectPurchaser = (purchaser) => {
    form.po_purchaser = purchaser.id;
    isPurchaserModalOpen.value = false;
};

const selectReceiveLocation = (location) => {
    form.po_receive_location = location.code;
    isReceiveLocationModalOpen.value = false;
};

const selectTaxGroup = (taxGroup) => {
    form.po_tax_group = taxGroup.code;
    isTaxGroupModalOpen.value = false;
};

const selectLocation = (location) => {
    form.rc_location = location.code;
    isLocationTableModalOpen.value = false;
};

const selectRtLocation = (location) => {
    form.rt_location = location.code;
    isRtLocationTableModalOpen.value = false;
};

const selectSourceCode = (source) => {
    form.rc_ap_source = source.code;
    isSourceCodeTableModalOpen.value = false;
};

const selectGLDist = (dist) => {
    form.rc_ap_gl_dist = dist.code;
    isGLDistTableModalOpen.value = false;
};

const selectDnSourceCode = (source) => {
    form.dn_ap_source = source.code;
    isDnSourceCodeTableModalOpen.value = false;
};

const selectCnSourceCode = (source) => {
    form.cn_ap_source = source.code;
    isCnSourceCodeTableModalOpen.value = false;
};

const selectIsGlSourceCode = (source) => {
    form.is_gl_source = source.code;
    isIsGlSourceCodeTableModalOpen.value = false;
};

const selectMiLocation = (location) => {
    form.mi_location = location.code;
    isMiLocationModalOpen.value = false;
};

const selectMiGlSourceCode = (source) => {
    form.mi_gl_source = source.code;
    form.mi_gl_source_desc = source.name.split(' ').slice(1).join(' '); // Basic logic to get desc
    isMiGlSourceCodeTableModalOpen.value = false;
};

const selectMoGlSourceCode = (source) => {
    form.mo_gl_source = source.code;
    form.mo_gl_source_desc = source.name.split(' ').slice(1).join(' '); // Basic logic to get desc
    isMoGlSourceCodeTableModalOpen.value = false;
};

// Demo data for selection tables
const locationOptions = ref([
    { code: 'BP', name: 'GUDANG BAHAN PEMBANTU' },
    { code: 'IMPORT', name: 'SPARE PARTS WAREHOUSE' },
    { code: 'KIM', name: 'KARYA INDAH MULTIGUNA' },
    { code: 'MBI', name: 'MBI CIKANDE' },
    { code: 'MBIOFS', name: 'MBI CIKANDE OFFSET' },
    { code: 'MBI01', name: 'MBI OFFICE' },
    { code: 'SPP', name: 'GUDANG SPAREPART' },
    { code: 'TRK', name: 'GUDANG SPAREPART TRAILER' }
]);

const sourceCodeOptions = ref([
    { code: 'AP-DO', name: 'AP PEMBEBANAN MANUAL NO PR/PO' },
    { code: 'AP-INV', name: 'INVOICE SUPPLIER' },
    { code: 'AP-KRTS', name: 'AP PENERIMAAN KERTAS' },
    { code: 'AP-NCPS', name: 'AP NON CPS' },
    { code: 'AP-RC', name: 'PENERIMAAN BARANG RC' },
    { code: 'AP-RT', name: 'RETUR BARANG' },
    { code: 'AP-DN', name: 'PENGURANGАN HUTАNG' },
    { code: 'AP-CN', name: 'PENAMBAHAN HUTANG' }
]);

const glDistOptions = ref([
    { code: 'ADM', name: 'BIAYA BANK', account: '630901-00-00-00' },
    { code: 'ALAT PABRIK', name: 'BEBAN PERALATAN PABRIK DIGITAL P', account: '612007-00-00-01' },
    { code: 'ALAT PABRIK', name: 'BEBAN PERALATAN PABRIK', account: '612007-00-00-01' },
    { code: 'AS BANGUNAN', name: 'ASURANSI BANGUNAN', account: '612016-00-00-01' },
    { code: 'AS YMHD', name: 'ASURANSI YMHD', account: '214020-00-00-00' },
    { code: 'ASTEK', name: 'BEBAN ASTEK PRODUKSI BULANAN', account: '611001-00-00-08' },
    { code: 'ASTEK-YMHD', name: 'ASTEK YMHD', account: '214004-00-00-00' },
    { code: 'ASTEK-MKT', name: 'BEBAN ASTEK PENJUALAN', account: '620101-00-00-08' },
    { code: 'ASURANSI', name: 'BEBAN ASURANSI KEND', account: '631101-00-00-00' },
    { code: 'AYAT SILANG', name: 'POS SILANG', account: '111401-00-00-00' },
    { code: 'HUTANG', name: 'PENGURANG HUTANG', account: '210001-00-00-00' }
]);

const rcApRemarkOptions = ref([
    'PO-PO#',
    'RC-RC#',
    'DO-SUPP DO#',
    'IV-SUPP IV#',
    'P1-PO Note 1',
    'P2-PO Note 2',
    'N-None'
]);

const rtApRemarkOptions = ref([
    'PO-PO#',
    'RT-RT#',
    'RC-RC#',
    'DO-SUPP DO#',
    'IV-SUPP IV#',
    'CN-SUPP CN#',
    'P1-PO Note 1',
    'P2-PO Note 2',
    'N-None'
]);

const rcGlRemarkOptions = ref([
    'PO-PO#+PO Date | RC#+RC Date',
    'DO-SUPP DO#+DO Date',
    'IV-SUPP IV#+IV Date',
    'P1-PO Note 1',
    'P2-PO Note 2',
    'All-AP AC# + Name',
    'SI-SKU# + Qty | UOM | U.Price',
    'R1-RC SKU Item Note 1',
    'R2-RC SKU Item Note 2',
    'K1-PO SKU Item Note 1',
    'K2-PO SKU Item Note 2',
    'K3-PO SKU Item Note 3',
    'K4-PO SKU Item Note 4',
    'K5-PO SKU Item Note 5',
    'N-None'
]);

const rtGlRemarkOptions = ref([
    'PO-PO#+PO Date | RC#+RC Date',
    'DO-SUPP DO#+DO Date',
    'IV-SUPP IV#+IV Date',
    'CN-SUPP CN#+CN Date',
    'P1-PO Note 1',
    'P2-PO Note 2',
    'All-AP AC# + Name',
    'SI-SKU# + Qty | UOM | U.Price',
    'T1-RT SKU Item Note 1',
    'T2-RT SKU Item Note 2',
    'R1-RC SKU Item Note 1',
    'R2-RC SKU Item Note 2',
    'K1-PO SKU Item Note 1',
    'K2-PO SKU Item Note 2',
    'K3-PO SKU Item Note 3',
    'N-None'
]);

const taxGroupOptions = ref([
    { code: 'NON', name: 'NON PPN' },
    { code: 'PPH21J', name: 'PPH 21 JASA 2.5%' },
    { code: 'PPH21S', name: 'PPH SEWA 2.5%' },
    { code: 'PPH21J1', name: 'PPH JASA 2% + PPN 11%' },
    { code: 'PPH2J25', name: 'PPH JASA 2% + PPN' },
    { code: 'PPH2J11', name: 'PPH JASA 2% + PPN 11%' },
    { code: 'PPH2S25', name: 'PPH SEWA 2% + PPN' }
]);

const transactionTypeOptions = ref([
    { code: 'PO', name: 'PO', group: 'PO' }
]);

const purchaserOptions = ref([
    { id: 'CHRS', name: 'CHRISTINE', type: 'PU', email: 'christine@ptmbi.com' },
    { id: 'MKT', name: 'INTERNAL SALES', type: 'PU/RQ', email: '' },
    { id: 'MRSH', name: 'MARSIH', type: 'PU', email: 'marsih@ptmbi.com' },
    { id: 'NKTA', name: 'NIKITA', type: 'PU', email: 'nikita@multiindustry.com' },
    { id: 'PURST', name: 'PURCHASING', type: 'PU', email: 'purst@ptmbi1.com' }
]);

const defaultUnitPriceOptions = ref([
    '0-No Default, input price',
    '1-SKU Item Price',
    '2-Last PO SKU Price',
    '3-If SKU Item Price=Zero, take last PO SKU Price',
    '4-If last PO SKU Price=Zero, take SKU Item Price',
    '5-Take last PO SKU Price + Discount',
    '6-If SKU Item Price=Zero, take last PO SKU Price + Discount'
]);

const glSourceCodeOptions = ref([
    { code: 'GL-ADJ', name: 'JURNAL ADJUSTMENT' },
    { code: 'GL-BANK', name: 'JURNAL BANK' },
    { code: 'GL-CASH', name: 'JURNAL CASH GL' },
    { code: 'GL-KRTS', name: 'TRANSAKSI KERTAS' },
    { code: 'GL-MEM', name: 'JURNAL MEMORIAL' },
    { code: 'GL-MIG', name: 'DATA MIGRASI GL' }
]);

// Demo data for OLDA modals
const oldaCategoryOptions = ref([
    { category: 'BA1.01', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.02', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.03', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.04', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.05', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.06', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.07', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.08', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.10', name: 'LOCKNUT', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.12', name: 'BEARING', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.13', name: 'PUSH BOTOM', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.14', name: 'FITING ANGIN', email: true, active: 'Yes', obsolete: 'Yes' },
    { category: 'BA1.15', name: 'FITING ANGIN', email: true, active: 'Yes', obsolete: 'Yes' },
]);

const oldaSkuOptions = ref([
    { sku: '007-S01227', name: 'SERVICE AC SERVO DRIVER TIPE D4S0-S60 SPEA', category: '007', category_name: 'JASA PP MESIN', email: true },
    { sku: '000055', name: 'BOX SLEEVE DALAM PT. DELTAPACK', category: '201', category_name: 'SUBCON BOX KIM', email: true },
    { sku: '000055A', name: 'BOX TOP OCTABIN PT. DELTAPACK', category: '201', category_name: 'SUBCON BOX KIM', email: true },
    { sku: '000055A', name: 'BOX SLEEVE LUAR PT. DELTAPACK', category: '201', category_name: 'SUBCON BOX KIM', email: true },
    { sku: '000055A', name: 'BOX BOTTOM OCTABIN PT. DELTAPACK', category: '201', category_name: 'SUBCON BOX KIM', email: true },
    { sku: '000202', name: 'BOX TBE 12 X 350 ML NEW', category: '201', category_name: 'SUBCON BOX KIM', email: true },
    { sku: '001-A01001', name: 'ANNELING WIRE 2.8MM (KAWAT PRESS BALLER)', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A02001', name: 'ARMEX BAKING SODA POWDER (25KG/BAG)', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A02002', name: 'ARMEX BAKING SODA POWDER (25KG/BAG)', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A02003', name: 'ARMEX BAKING SODA POWDER (25KG/BAG)', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A03001', name: 'ALUMINIUM CHLOROHYDRANT AIETAGARD 4040 (ACH)', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A04001', name: 'AVAL BULAT SCM', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A05001', name: 'AD OAIX', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A06001', name: 'ANTIFOAM PKG-1800', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
    { sku: '001-A01001', name: 'BORAX', category: '001', category_name: 'BAHAN PEMBANTU', email: true },
]);

const oldaSkuSearchTerm = ref('');
const filteredOldaSkuOptions = ref([...oldaSkuOptions.value]);

const searchOldaSku = () => {
    if (!oldaSkuSearchTerm.value) {
        filteredOldaSkuOptions.value = [...oldaSkuOptions.value];
        return;
    }
    filteredOldaSkuOptions.value = oldaSkuOptions.value.filter(sku =>
        sku.sku.toLowerCase().includes(oldaSkuSearchTerm.value.toLowerCase()) ||
        sku.name.toLowerCase().includes(oldaSkuSearchTerm.value.toLowerCase())
    );
};

const tickAllSkus = () => {
  filteredOldaSkuOptions.value.forEach(sku => sku.email = true);
};

const untickAllSkus = () => {
  filteredOldaSkuOptions.value.forEach(sku => sku.email = false);
};
</script>

<template>
  <AppLayout title="Define Configuration">
    <template #header>
      <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Material Management > System Requirement > Standard Setup > Define Configuration
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
          <div class="p-6 sm:px-20 bg-white border-b border-gray-200">
            <!-- Success/Error Messages -->
            <div v-if="successMessage" class="mb-4 bg-green-50 border border-green-500 text-green-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">Success!</strong>
              <span class="block sm:inline"> {{ successMessage }}</span>
            </div>
            
            <div v-if="errorMessage" class="mb-4 bg-red-50 border border-red-500 text-red-700 px-4 py-3 rounded relative" role="alert">
              <strong class="font-bold">Error!</strong>
              <span class="block sm:inline"> {{ errorMessage }}</span>
            </div>
            
            <div class="w-full">
              <TabGroup>
                <TabList class="flex p-1 space-x-1 bg-blue-900/20 rounded-xl">
                  <Tab
                    v-for="category in categories"
                    :key="category"
                    v-slot="{ selected }"
                    as="template"
                  >
                    <button
                      :class="[
                        'w-full py-2.5 text-sm leading-5 font-medium text-blue-700 rounded-lg',
                        'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60',
                        selected ? 'bg-white shadow' : 'text-blue-100 hover:bg-white/[0.12] hover:text-white'
                      ]"
                    >
                      {{ category }}
                    </button>
                  </Tab>
                </TabList>

                <TabPanels class="mt-2">
                  <!-- PR Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">PR > Purchase Requisition</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- PR Online Approval -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">PR Online Approval</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_online_approval" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">Y-Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_online_approval" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                        <!-- PR Total Level -->
                        <div>
                          <label for="pr_total_level" class="block text-sm font-medium text-gray-700">PR Total Level</label>
                          <div class="flex items-center">
                            <input type="number" v-model="form.pr_total_level" id="pr_total_level" class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                          </div>
                        </div>
                        <!-- PR Approval Flow -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">PR Approval Flow</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_approval_flow" value="A" class="form-radio">
                              <span class="ml-2">A-3-Level</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_approval_flow" value="B" class="form-radio">
                              <span class="ml-2">B-2-Level</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_approval_flow" value="C" class="form-radio">
                              <span class="ml-2">C-1-Level</span>
                            </label>
                            <button 
                              type="button" 
                              @click="openFlowGuideModal" 
                              class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              Flow Guide
                            </button>
                          </div>
                        </div>
                        <!-- PR Price -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">PR Price</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_price" value="Y" class="form-radio">
                              <span class="ml-2">Y-Activate</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_price" value="N" class="form-radio">
                              <span class="ml-2">N-Disable</span>
                            </label>
                            <span class="text-gray-500">[Only for Requestor]</span>
                          </div>
                        </div>
                        <!-- PR GL DIST# -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">PR GL DIST#</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_gl_dist" value="Y" class="form-radio">
                              <span class="ml-2">Y-Allowed Edit</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.pr_gl_dist" value="N" class="form-radio">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                      </div>
                      
                      <div class="mt-6 space-y-4">
                        <div class="bg-gray-50 p-4 rounded">
                          <h4 class="font-medium text-gray-800 mb-2">PR Approval Flow</h4>
                          <p class="text-sm text-gray-600">Once the decision has input, it cannot be changed and consult Prestige Atlantic Asia if you want to re-configure</p>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded">
                          <h4 class="font-medium text-gray-800 mb-2">PR Price</h4>
                          <ol class="list-decimal ml-5 text-sm text-gray-600">
                            <li>When Yes, Requestor can input Price and run Price History Help</li>
                            <li>When No, Requestor cannot input Price and run Price History Help</li>
                          </ol>
                        </div>
                        
                        <div class="bg-gray-50 p-4 rounded">
                          <h4 class="font-medium text-gray-800 mb-2">PR Level</h4>
                          <ol class="list-decimal ml-5 text-sm text-gray-600">
                            <li>Department Manager to Approve PR</li>
                            <li>Purchaser Manager to Approve PR</li>
                            <li>General Manager to Approve PR</li>
                          </ol>
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- PO Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">PO > Purchase Order</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- PO Online Approval -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">PO Online Approval</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_online_approval" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">Y-Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_online_approval" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                        <!-- PO Level -->
                        <div>
                          <label for="po_level" class="block text-sm font-medium text-gray-700">PO Level</label>
                          <input type="number" v-model="form.po_level" id="po_level" class="mt-1 block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <!-- Tran Type -->
                        <div>
                          <label for="po_tran_type" class="block text-sm font-medium text-gray-700">Tran Type</label>
                          <div class="mt-1 flex items-center">
                            <input type="text" v-model="form.po_tran_type" id="po_tran_type" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="openTransactionTypeModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              ...
                            </button>
                          </div>
                        </div>
                        <!-- Purchaser -->
                        <div>
                          <label for="po_purchaser" class="block text-sm font-medium text-gray-700">Purchaser</label>
                          <div class="mt-1 flex items-center">
                            <input type="text" v-model="form.po_purchaser" id="po_purchaser" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="openPurchaserModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              ...
                            </button>
                          </div>
                        </div>
                        <!-- Receive Location -->
                        <div>
                          <label for="po_receive_location" class="block text-sm font-medium text-gray-700">Receive Location</label>
                          <div class="mt-1 flex items-center">
                            <input type="text" v-model="form.po_receive_location" id="po_receive_location" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="openReceiveLocationModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              ...
                            </button>
                          </div>
                        </div>
                        <!-- Tax Group -->
                        <div>
                          <label for="po_tax_group" class="block text-sm font-medium text-gray-700">Tax Group</label>
                          <div class="mt-1 flex items-center">
                            <input type="text" v-model="form.po_tax_group" id="po_tax_group" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="openTaxGroupModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              ...
                            </button>
                          </div>
                        </div>
                        <!-- Lock to PR -->
                        <div class="col-span-1 md:col-span-2">
                          <label class="block text-sm font-medium text-gray-700">Lock to PR</label>
                          <div class="mt-2 flex flex-wrap items-center gap-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_lock_to_pr" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Y-Yes PO must have PR</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_lock_to_pr" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">N-Hybrid, PR is not necessary for PO</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_lock_to_pr" value="D" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">D-Go PO Directly</span>
                            </label>
                          </div>
                        </div>
                        <!-- PO Note -->
                        <div class="col-span-1 md:col-span-2">
                          <label class="block text-sm font-medium text-gray-700">PO Note</label>
                          <div class="mt-2 flex items-center gap-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_note" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Y-Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_note" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">N-No</span>
                            </label>
                            <span class="text-gray-500">[From Last PO Note]</span>
                          </div>
                        </div>
                        <!-- PO Min Price% -->
                        <div>
                          <label for="po_min_price" class="block text-sm font-medium text-gray-700">PO Min Price%</label>
                          <input type="text" v-model="form.po_min_price" id="po_min_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <!-- PO Max Price% -->
                        <div>
                          <label for="po_max_price" class="block text-sm font-medium text-gray-700">PO Max Price%</label>
                          <input type="text" v-model="form.po_max_price" id="po_max_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                        </div>
                        <!-- Default Unit Price -->
                        <div class="col-span-1 md:col-span-2">
                          <label for="po_default_unit_price" class="block text-sm font-medium text-gray-700">Default Unit Price</label>
                          <select v-model="form.po_default_unit_price" id="po_default_unit_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option v-for="option in defaultUnitPriceOptions" :key="option" :value="option">{{ option }}</option>
                          </select>
                        </div>
                        <!-- Amend Unit Price (When PO is Completed) -->
                        <div class="col-span-1 md:col-span-2">
                          <label class="block text-sm font-medium text-gray-700">Amend Unit Price</label>
                          <div class="mt-2 flex items-center gap-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_amend_unit_price_completed" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Y-Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_amend_unit_price_completed" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">N-No</span>
                            </label>
                            <span class="text-gray-500">[When PO is Completed]</span>
                          </div>
                        </div>
                        <!-- Amend Unit Price (When PO is Outstanding or Partial Completed) -->
                        <div class="col-span-1 md:col-span-2">
                          <label class="block text-sm font-medium text-gray-700">Amend Unit Price</label>
                          <div class="mt-2 flex items-center gap-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_amend_unit_price_outstanding" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Y-Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_amend_unit_price_outstanding" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">N-No</span>
                            </label>
                            <span class="text-gray-500">[When PO is Outstanding or Partial Completed]</span>
                          </div>
                        </div>
                        <!-- PO GL DIST# -->
                        <div class="col-span-1 md:col-span-2">
                          <label class="block text-sm font-medium text-gray-700">PO GL DIST#</label>
                          <div class="mt-2 flex items-center gap-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_gl_dist" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Y-Allowed Edit</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.po_gl_dist" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>
                  
                  <!-- RC Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">RC > Receive Note, Receive from Supplier</h3>
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- RC Location -->
                        <div>
                          <label for="rc_location" class="block text-sm font-medium text-gray-700">Location:</label>
                          <div class="mt-1 flex items-center">
                            <input type="text" v-model="form.rc_location" id="rc_location" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="openLocationTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              ...
                            </button>
                          </div>
                        </div>
                      </div>
                      
                      <!-- RC MFG Date and GL DIST options -->
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- RC MFG Date -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">RC MFG Date:</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.rc_mfg_date" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">Y-Editable</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.rc_mfg_date" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">N-Locked</span>
                            </label>
                          </div>
                        </div>
                        
                        <!-- RC GL DIST# -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">RC GL DIST#:</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.rc_gl_dist_edit" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">Y-Allowed Edit</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.rc_gl_dist_edit" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Post RC Section -->
                      <div class="mt-6">
                        <h4 class="text-md font-medium text-white bg-green-500 p-2">Post RC</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <!-- AP Source -->
                          <div>
                            <label for="rc_ap_source" class="block text-sm font-medium text-gray-700">AP Source:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.rc_ap_source" id="rc_ap_source" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <input type="text" v-model="form.rc_ap_source_desc" id="rc_ap_source_desc" class="ml-2 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" readonly>
                              <button type="button" @click="openSourceCodeTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                        </div>
                        
                        <!-- AP Ref# and AP Source# Selectors -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <!-- AP Ref# -->
                          <div>
                            <label for="rc_ap_ref" class="block text-sm font-medium text-gray-700">AP Ref#:</label>
                            <select v-model="form.rc_ap_ref" id="rc_ap_ref" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="IV-SUPP IV#">IV-SUPP IV#</option>
                              <option value="N-SKIP">N-SKIP</option>
                            </select>
                          </div>
                          
                          <!-- AP Source# -->
                          <div>
                            <label for="rc_ap_source_num" class="block text-sm font-medium text-gray-700">AP Source#:</label>
                            <select v-model="form.rc_ap_source_num" id="rc_ap_source_num" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="PO-PO#">PO-PO#</option>
                              <option value="RC-RC#">RC-RC#</option>
                              <option value="DO-SUPP DO#">DO-SUPP DO#</option>
                              <option value="IV-SUPP IV#">IV-SUPP IV#</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- AP Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.rc_ap_remarks" :key="`ap_remark_${index}`">
                            <label :for="`rc_ap_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">AP Remark {{ index + 1 }}:</label>
                            <select 
                              v-model="form.rc_ap_remarks[index]" 
                              :id="`rc_ap_remark_${index + 1}`" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option v-for="option in rcApRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- GL Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.rc_gl_remarks" :key="`gl_remark_${index}`">
                            <label :for="`rc_gl_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">GL Remark {{ index + 1 }}:</label>
                            <select 
                              v-model="form.rc_gl_remarks[index]" 
                              :id="`rc_gl_remark_${index + 1}`" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option v-for="option in rcGlRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- Post Accrued Section -->
                        <div class="grid grid-cols-1 gap-4 mt-6">
                          <div>
                            <label class="block text-sm font-medium text-gray-700">Post Accrued:</label>
                            <div class="mt-2 flex items-center space-x-4">
                              <label class="inline-flex items-center">
                                <input type="radio" v-model="form.rc_post_accrued" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                <span class="ml-2">Y-Yes, Post as Accrued</span>
                              </label>
                            </div>
                          </div>
                          
                          <!-- AP GL DIST# -->
                          <div>
                            <label for="rc_ap_gl_dist" class="block text-sm font-medium text-gray-700">AP GL DIST#:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.rc_ap_gl_dist" id="rc_ap_gl_dist" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <button type="button" @click="openGLDistTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                          
                          <!-- Accrued Note -->
                          <div>
                            <p class="text-red-500 text-sm font-medium">*This is provision for accrued Purchase Credit for goods received but invoice not yet received.</p>
                          </div>
                        </div>
                        
                        <!-- RC Accrual Note Button -->
                        <div class="mt-4">
                          <button 
                            type="button" 
                            @click="openRcAccrualNoteModal" 
                            class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded text-gray-700 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                          >
                            RC Accrual Note
                          </button>
                        </div>
                      </div>
                    </div>
                  </TabPanel>
                  
                  <!-- RT Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">RT > Return Note, Return to Supplier</h3>
                      
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- RT GL DIST# -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">RT GL DIST#:</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.rt_gl_dist_edit" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">Y-Allowed Edit</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.rt_gl_dist_edit" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Post RT Section -->
                      <div class="mt-6">
                        <h4 class="text-md font-medium text-white bg-green-500 p-2">Post RT</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <!-- AP Source -->
                          <div>
                            <label for="rt_ap_source" class="block text-sm font-medium text-gray-700">AP Source:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.rt_ap_source" id="rt_ap_source" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="AP">
                              <input type="text" v-model="form.rt_ap_source_desc" id="rt_ap_source_desc" class="ml-2 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="RT" readonly>
                              <button type="button" @click="openSourceCodeTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                        </div>
                        
                        <!-- AP Ref# and AP Source# Selectors -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <!-- AP Ref# -->
                          <div>
                            <label for="rt_ap_ref" class="block text-sm font-medium text-gray-700">AP Ref#:</label>
                            <select v-model="form.rt_ap_ref" id="rt_ap_ref" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="RT-RT#">RT-RT#</option>
                              <option value="CN-SUPP CN#">CN-SUPP CN#</option>
                              <option value="N-SKIP">N-SKIP</option>
                            </select>
                          </div>
                          
                          <!-- AP Source# -->
                          <div>
                            <label for="rt_ap_source_num" class="block text-sm font-medium text-gray-700">AP Source#:</label>
                            <select v-model="form.rt_ap_source_num" id="rt_ap_source_num" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="CN-SUPP CN#">CN-SUPP CN#</option>
                              <option value="RT-RT#">RT-RT#</option>
                              <option value="PO-PO#">PO-PO#</option>
                              <option value="RC-RC#">RC-RC#</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- AP Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.rt_ap_remarks" :key="`rt_ap_remark_${index}`">
                            <label :for="`rt_ap_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">AP Remark {{ index + 1 }}:</label>
                            <select 
                              v-model="form.rt_ap_remarks[index]" 
                              :id="`rt_ap_remark_${index + 1}`" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option v-for="option in rtApRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- GL Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.rt_gl_remarks" :key="`rt_gl_remark_${index}`">
                            <label :for="`rt_gl_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">GL Remark {{ index + 1 }}:</label>
                            <select 
                              v-model="form.rt_gl_remarks[index]" 
                              :id="`rt_gl_remark_${index + 1}`" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option v-for="option in rtGlRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- DN Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">DN > Debit Note, issue Debit Note to Supplier</h3>
                      
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- DN GL DIST# -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">DN GL DIST#:</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.dn_gl_dist_edit" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">Y-Allowed Edit</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.dn_gl_dist_edit" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Post DN Section -->
                      <div class="mt-6">
                        <h4 class="text-md font-medium text-white bg-green-500 p-2">Post DN</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <!-- AP Source -->
                          <div>
                            <label for="dn_ap_source" class="block text-sm font-medium text-gray-700">AP Source:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.dn_ap_source" id="dn_ap_source" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="AP">
                              <input type="text" v-model="form.dn_ap_source_desc" id="dn_ap_source_desc" class="ml-2 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="DN" readonly>
                              <button type="button" @click="openDnSourceCodeTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                        </div>
                        
                        <!-- AP Ref# and AP Source# Selectors -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <!-- AP Ref# -->
                          <div>
                            <label for="dn_ap_ref" class="block text-sm font-medium text-gray-700">AP Ref#:</label>
                            <select v-model="form.dn_ap_ref" id="dn_ap_ref" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="DN-DN#">DN-DN#</option>
                              <option value="N-SKIP">N-SKIP</option>
                            </select>
                          </div>
                          
                          <!-- AP Source# -->
                          <div>
                            <label for="dn_ap_source_num" class="block text-sm font-medium text-gray-700">AP Source#:</label>
                            <select v-model="form.dn_ap_source_num" id="dn_ap_source_num" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="CN-SUPP CN#">CN-SUPP CN#</option>
                              <option value="DN-DN#">DN-DN#</option>
                            </select>
                          </div>
                          
                          <!-- AP Remark -->
                          <div>
                            <label for="dn_ap_remark" class="block text-sm font-medium text-gray-700">AP Remark:</label>
                            <select v-model="form.dn_ap_remark" id="dn_ap_remark" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option v-for="option in dnApRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- GL Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.dn_gl_remarks" :key="`dn_gl_remark_${index}`">
                            <label :for="`dn_gl_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">GL Remark {{ index + 1 }}:</label>
                            <select 
                              v-model="form.dn_gl_remarks[index]" 
                              :id="`dn_gl_remark_${index + 1}`" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option value="N-None">N-None</option>
                              <option value="DN-DN#">DN-DN#</option>
                              <option value="CN-SUPP CN#">CN-SUPP CN#</option>
                              <option value="D1-DN Note1">D1-DN Note1</option>
                              <option value="D2-DN Note2">D2-DN Note2</option>
                              <option value="All-AP AC# + Name">All-AP AC# + Name</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- CN Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">CN > Credit Note, issue Credit Note to Supplier</h3>
                      
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- CN GL DIST# -->
                        <div>
                          <label class="block text-sm font-medium text-gray-700">CN GL DIST#:</label>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.cn_gl_dist_edit" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">Y-Allowed Edit</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.cn_gl_dist_edit" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                              <span class="ml-2">N-No</span>
                            </label>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Post CN Section -->
                      <div class="mt-6">
                        <h4 class="text-md font-medium text-white bg-green-500 p-2">Post CN</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <!-- AP Source -->
                          <div>
                            <label for="cn_ap_source" class="block text-sm font-medium text-gray-700">AP Source:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.cn_ap_source" id="cn_ap_source" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="AP">
                              <input type="text" v-model="form.cn_ap_source_desc" id="cn_ap_source_desc" class="ml-2 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="CN" readonly>
                              <button type="button" @click="openCnSourceCodeTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                        </div>
                        
                        <!-- AP Ref# and AP Source# Selectors -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <!-- AP Ref# -->
                          <div>
                            <label for="cn_ap_ref" class="block text-sm font-medium text-gray-700">AP Ref#:</label>
                            <select v-model="form.cn_ap_ref" id="cn_ap_ref" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="CN-CN#">CN-CN#</option>
                              <option value="N-SKIP">N-SKIP</option>
                            </select>
                          </div>
                          
                          <!-- AP Source# -->
                          <div>
                            <label for="cn_ap_source_num" class="block text-sm font-medium text-gray-700">AP Source#:</label>
                            <select v-model="form.cn_ap_source_num" id="cn_ap_source_num" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="DN-SUPP DN#">DN-SUPP DN#</option>
                              <option value="CN-CN#">CN-CN#</option>
                            </select>
                          </div>
                          
                          <!-- AP Remark -->
                          <div>
                            <label for="cn_ap_remark" class="block text-sm font-medium text-gray-700">AP Remark:</label>
                            <select v-model="form.cn_ap_remark" id="cn_ap_remark" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option v-for="option in cnApRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- GL Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.cn_gl_remarks" :key="`cn_gl_remark_${index}`">
                            <label :for="`cn_gl_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">GL Remark {{ index + 1 }}:</label>
                            <select 
                              v-model="form.cn_gl_remarks[index]" 
                              :id="`cn_gl_remark_${index + 1}`" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option value="N-None">N-None</option>
                              <option value="CN-CN#">CN-CN#</option>
                              <option value="DN-SUPP DN#">DN-SUPP DN#</option>
                              <option value="C1-CN Note1">C1-CN Note1</option>
                              <option value="C2-CN Note2">C2-CN Note2</option>
                              <option value="All-AP AC# + Name">All-AP AC# + Name</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- IS Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">IS > Issue Note, Production Consumption</h3>
                      
                      <!-- Cancel IS Option -->
                      <div class="grid grid-cols-1 gap-4">
                        <div>
                          <label for="is_cancel_option" class="block text-sm font-medium text-gray-700">Cancel IS:</label>
                          <select v-model="form.is_cancel_option" id="is_cancel_option" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option v-for="option in isCancelOptions" :key="option" :value="option">{{ option }}</option>
                          </select>
                        </div>
                      </div>
                      
                      <!-- Warning Message -->
                      <div class="text-red-600 text-sm font-medium">
                        *You are allowed to cancel previous month IS provided Costing Period is not closed. But stock is stock back into Current Period.
                      </div>
                      
                      <!-- IS GL DIST# -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700">IS GL DIST#:</label>
                        <div class="mt-2 flex items-center space-x-4">
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.is_gl_dist_edit" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <span class="ml-2">Y-Allowed Edit</span>
                          </label>
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.is_gl_dist_edit" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <span class="ml-2">N-No</span>
                          </label>
                        </div>
                      </div>
                      
                      <!-- Post IS Section -->
                      <div class="mt-6">
                        <h4 class="text-md font-medium text-white bg-green-500 p-2">Post IS</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <!-- GL Source -->
                          <div>
                            <label for="is_gl_source" class="block text-sm font-medium text-gray-700">GL Source:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.is_gl_source" id="is_gl_source" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="GL">
                              <input type="text" v-model="form.is_gl_source_desc" id="is_gl_source_desc" class="ml-2 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="ADJ" readonly>
                              <button type="button" @click="openIsGlSourceCodeTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                        </div>
                        
                        <!-- GL Ref# -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div>
                            <label for="is_gl_ref" class="block text-sm font-medium text-gray-700">GL Ref#:</label>
                            <select v-model="form.is_gl_ref" id="is_gl_ref" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="IS-IS#">IS-IS#</option>
                            </select>
                          </div>
                        </div>
                        
                        <!-- GL Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.is_gl_remarks" :key="`is_gl_remark_${index}`">
                            <label :for="`is_gl_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">GL Remark {{ index + 1 }}:</label>
                            <select 
                              v-model="form.is_gl_remarks[index]" 
                              :id="`is_gl_remark_${index + 1}`" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option v-for="option in isGlRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      
                      <!-- Perpetual Inventory Posting Section -->
                      <div class="mt-6 border rounded-lg p-4">
                        <h4 class="font-medium text-gray-800 mb-2">Perpetual Inventory Posting</h4>
                        
                        <div class="grid grid-cols-1 gap-4">
                          <!-- GL Posting -->
                          <div>
                            <label class="block text-sm font-medium text-gray-700">GL Posting:</label>
                            <div class="mt-2 flex items-center space-x-4">
                              <label class="inline-flex items-center">
                                <input type="radio" v-model="form.is_gl_posting" value="Y-Yes" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                <span class="ml-2">Y-Yes</span>
                              </label>
                              <label class="inline-flex items-center">
                                <input type="radio" v-model="form.is_gl_posting" value="N-No" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                                <span class="ml-2">N-No</span>
                              </label>
                            </div>
                          </div>
                          
                          <!-- Note -->
                          <div class="mt-4">
                            <p class="text-sm text-gray-700 font-medium">Note:</p>
                            <ol class="list-decimal pl-5 text-sm text-gray-700 space-y-1">
                              <li>Perpetual Inventory Posting setting is for IS, MI, and MO.</li>
                              <li>If yes, Posting GL Accounts must be setup and link first.</li>
                            </ol>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- MI Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">MI > Miscellaneous Issue Note</h3>

                      <!-- Location -->
                      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                          <label for="mi_location" class="block text-sm font-medium text-gray-700">Location:</label>
                          <div class="mt-1 flex items-center">
                            <input type="text" v-model="form.mi_location" id="mi_location" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <button type="button" @click="openMiLocationModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                              ...
                            </button>
                          </div>
                        </div>
                      </div>

                      <!-- MI GL DIST# -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700">MI GL DIST#:</label>
                        <div class="mt-2 flex items-center space-x-4">
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.mi_gl_dist_edit" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <span class="ml-2">Y-Allowed Edit</span>
                          </label>
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.mi_gl_dist_edit" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <span class="ml-2">N-No</span>
                          </label>
                        </div>
                      </div>

                      <!-- Post MI Section -->
                      <div class="mt-6">
                        <h4 class="text-md font-medium text-white bg-green-500 p-2">Post MI</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <!-- GL Source -->
                          <div>
                            <label for="mi_gl_source" class="block text-sm font-medium text-gray-700">GL Source:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.mi_gl_source" id="mi_gl_source" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="GL">
                              <input type="text" v-model="form.mi_gl_source_desc" id="mi_gl_source_desc" class="ml-2 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="ADJ" readonly>
                              <button type="button" @click="openMiGlSourceCodeTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- GL Ref# -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div>
                            <label for="mi_gl_ref" class="block text-sm font-medium text-gray-700">GL Ref#:</label>
                            <select v-model="form.mi_gl_ref" id="mi_gl_ref" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="MI-MI#">MI-MI#</option>
                            </select>
                          </div>
                        </div>

                        <!-- GL Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.mi_gl_remarks" :key="`mi_gl_remark_${index}`">
                            <label :for="`mi_gl_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">GL Remark {{ index + 1 }}:</label>
                            <select
                              v-model="form.mi_gl_remarks[index]"
                              :id="`mi_gl_remark_${index + 1}`"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option v-for="option in miGlRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- MO Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-4">
                      <h3 class="text-lg font-medium leading-6 text-gray-900 bg-yellow-200 p-2">MO > Miscellaneous: Stock-Out Note, Inventory Stock-Out</h3>

                      <!-- MO GL DIST# -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700">MO GL DIST#:</label>
                        <div class="mt-2 flex items-center space-x-4">
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.mo_gl_dist_edit" value="Y" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <span class="ml-2">Y-Allowed Edit</span>
                          </label>
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.mo_gl_dist_edit" value="N" class="form-radio h-4 w-4 text-indigo-600 transition duration-150 ease-in-out">
                            <span class="ml-2">N-No</span>
                          </label>
                        </div>
                      </div>

                      <!-- Post MO Section -->
                      <div class="mt-6">
                        <h4 class="text-md font-medium text-white bg-green-500 p-2">Post MO</h4>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                          <!-- GL Source -->
                          <div>
                            <label for="mo_gl_source" class="block text-sm font-medium text-gray-700">GL Source:</label>
                            <div class="mt-1 flex items-center">
                              <input type="text" v-model="form.mo_gl_source" id="mo_gl_source" class="block w-20 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="GL">
                              <input type="text" v-model="form.mo_gl_source_desc" id="mo_gl_source_desc" class="ml-2 block w-40 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm" placeholder="ADJ" readonly>
                              <button type="button" @click="openMoGlSourceCodeTableModal" class="ml-2 inline-flex items-center px-2.5 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                ...
                              </button>
                            </div>
                          </div>
                        </div>

                        <!-- GL Ref# -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div>
                            <label for="mo_gl_ref" class="block text-sm font-medium text-gray-700">GL Ref#:</label>
                            <select v-model="form.mo_gl_ref" id="mo_gl_ref" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                              <option value="MO-MO#">MO-MO#</option>
                            </select>
                          </div>
                        </div>

                        <!-- GL Remarks -->
                        <div class="grid grid-cols-1 gap-4 mt-4">
                          <div v-for="(_, index) in form.mo_gl_remarks" :key="`mo_gl_remark_${index}`">
                            <label :for="`mo_gl_remark_${index + 1}`" class="block text-sm font-medium text-gray-700">GL Remark {{ index + 1 }}:</label>
                            <select
                              v-model="form.mo_gl_remarks[index]"
                              :id="`mo_gl_remark_${index + 1}`"
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"
                            >
                              <option v-for="option in moGlRemarkOptions" :key="option" :value="option">{{ option }}</option>
                            </select>
                          </div>
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- LT Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="p-4">
                      <p class="font-bold">*Reserved*</p>
                    </div>
                  </TabPanel>

                  <!-- OLDA Tab Panel -->
                  <TabPanel
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <div class="space-y-6 p-4">
                      <!-- On-Line Daily Alert Section -->
                      <div class="border rounded-lg p-4 space-y-4">
                        <div class="flex items-center space-x-4">
                          <label class="font-medium text-gray-700">On-Line Daily Alert:</label>
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.olda_online_daily_alert" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">Yes</span>
                          </label>
                          <label class="inline-flex items-center">
                            <input type="radio" v-model="form.olda_online_daily_alert" value="N" class="form-radio h-4 w-4 text-indigo-600">
                            <span class="ml-2">No</span>
                          </label>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                          <label for="olda_name" class="block text-sm font-medium text-gray-700">Name:</label>
                          <input type="text" v-model="form.olda_name" id="olda_name" class="md:col-span-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                          <label for="olda_email" class="block text-sm font-medium text-gray-700">Email:</label>
                          <input type="email" v-model="form.olda_email" id="olda_email" class="md:col-span-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-center">
                          <label for="olda_password" class="block text-sm font-medium text-gray-700">Password:</label>
                          <input type="password" v-model="form.olda_password" id="olda_password" class="md:col-span-2 mt-1 block w-full rounded-md border-gray-300 shadow-sm sm:text-sm">
                        </div>
                      </div>

                      <!-- Daily Alert Report Section -->
                      <div class="border rounded-lg p-4 space-y-4">
                        <h4 class="font-medium text-gray-800">Daily Alert Report</h4>
                        <div class="flex items-center space-x-8">
                          <div class="flex items-center space-x-4">
                            <label class="font-medium text-gray-700">SKU Reorder:</label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.olda_sku_reorder" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.olda_sku_reorder" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">No</span>
                            </label>
                          </div>
                          <div class="flex items-center space-x-4">
                            <label class="font-medium text-gray-700">PO Due Delivery:</label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.olda_po_due_delivery" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Yes</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.olda_po_due_delivery" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">No</span>
                            </label>
                          </div>
                        </div>
                      </div>

                      <!-- SKU Reorder Control Section -->
                      <div class="border rounded-lg p-4 space-y-4">
                        <h4 class="font-medium text-gray-800">SKU Reorder Control</h4>
                        <div>
                          <p class="text-sm font-medium text-gray-700">Step 1: SKU without Reorder</p>
                          <div class="mt-2 flex items-center space-x-4">
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.olda_sku_reorder_control_step1" value="Y" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">Yes Skip Email</span>
                            </label>
                            <label class="inline-flex items-center">
                              <input type="radio" v-model="form.olda_sku_reorder_control_step1" value="N" class="form-radio h-4 w-4 text-indigo-600">
                              <span class="ml-2">No</span>
                            </label>
                          </div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                          <div>
                            <p class="text-sm font-medium text-gray-700">Step 2: Setup by Category</p>
                            <button @click="openOldaCategoryModal" type="button" class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                              Setup Email Yes/No
                            </button>
                          </div>
                          <div>
                            <p class="text-sm font-medium text-gray-700">Step 3: Setup by SKU</p>
                            <button @click="openOldaSkuModal" type="button" class="mt-2 inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                              Setup Email Yes/No
                            </button>
                          </div>
                        </div>
                      </div>

                      <!-- PO Due Delivery Control Section -->
                      <div class="border rounded-lg p-4 space-y-4">
                        <h4 class="font-medium text-gray-800">PO Due Delivery Control</h4>
                        <div class="flex items-center space-x-4">
                          <label for="olda_po_due_total_item_min" class="text-sm font-medium text-gray-700">Total PO Item:</label>
                          <input type="number" v-model="form.olda_po_due_total_item_min" id="olda_po_due_total_item_min" class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm sm:text-sm">
                          <input type="number" v-model="form.olda_po_due_total_item_max" class="mt-1 block w-24 rounded-md border-gray-300 shadow-sm sm:text-sm">
                        </div>
                      </div>
                    </div>
                  </TabPanel>

                  <!-- Other Tab Panels -->
                  <TabPanel
                    v-for="category in categories.filter(c => c !== 'PR' && c !== 'PO' && c !== 'RC' && c !== 'RT' && c !== 'DN' && c !== 'CN' && c !== 'IS' && c !== 'MI' && c !== 'MO' && c !== 'LT' && c !== 'OLDA')"
                    :key="category"
                    :class="['bg-white rounded-xl p-3', 'focus:outline-none focus:ring-2 ring-offset-2 ring-offset-blue-400 ring-white ring-opacity-60']"
                  >
                    <h3 class="text-lg font-medium leading-6 text-gray-900">{{ category }}</h3>
                    <p class="mt-4 text-gray-500">Content for {{ category }} coming soon.</p>
                  </TabPanel>
                </TabPanels>
              </TabGroup>
            </div>
            
            <div class="flex justify-end mt-6">
              <button 
                @click="saveConfiguration" 
                type="button" 
                class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150"
                :disabled="processing"
              >
                <span v-if="processing" class="mr-2">
                  <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                </span>
                {{ processing ? 'Saving...' : 'Save' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AppLayout>

  <!-- Transaction Type Modal -->
  <TransitionRoot appear :show="isTransactionTypeModalOpen" as="template">
    <Dialog as="div" @close="isTransactionTypeModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Transaction Type Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Group</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="type in transactionTypeOptions" :key="type.code" @click="selectTransactionType(type)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ type.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ type.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ type.group }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isTransactionTypeModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Purchaser Modal -->
  <TransitionRoot appear :show="isPurchaserModalOpen" as="template">
    <Dialog as="div" @close="isPurchaserModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-lg transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Purchaser Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchaser ID</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Purchaser Name</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="purchaser in purchaserOptions" :key="purchaser.id" @click="selectPurchaser(purchaser)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ purchaser.id }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ purchaser.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ purchaser.type }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ purchaser.email }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <div class="mt-4 text-sm text-gray-500">
                <p>*Note: PU - Purchaser | RQ - Requestor</p>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isPurchaserModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Receive Location Modal -->
  <TransitionRoot appear :show="isReceiveLocationModalOpen" as="template">
    <Dialog as="div" @close="isReceiveLocationModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Receive Destination Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="location in locationOptions" :key="location.code" @click="selectReceiveLocation(location)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isReceiveLocationModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Tax Group Modal -->
  <TransitionRoot appear :show="isTaxGroupModalOpen" as="template">
    <Dialog as="div" @close="isTaxGroupModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Tax Group Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="taxGroup in taxGroupOptions" :key="taxGroup.code" @click="selectTaxGroup(taxGroup)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ taxGroup.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ taxGroup.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-between">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="() => {}"
                >
                  Zoom
                </button>
                <div class="space-x-3">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="() => {}"
                  >
                    Select
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="isTaxGroupModalOpen = false"
                  >
                    Exit
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  
  <!-- PR Approval Flow Guide Modal -->
  <TransitionRoot appear :show="isFlowGuideModalOpen" as="template">
    <Dialog as="div" @close="isFlowGuideModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                PR Approval Flow Model
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-blue-600 text-white">
                    <tr>
                      <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider">FLOW MODEL</th>
                      <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider">REQUESTOR</th>
                      <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider">PR LEVEL 1</th>
                      <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider">PR LEVEL 2</th>
                      <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider">PR LEVEL 3</th>
                      <th scope="col" class="px-3 py-2 text-center text-xs font-medium uppercase tracking-wider">PO</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr class="bg-yellow-100">
                      <td class="px-3 py-2 text-center font-medium">A</td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="px-3 py-2 text-center font-medium">B</td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-red-200">
                        <span class="inline-block w-5 h-5 bg-red-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                    </tr>
                    <tr>
                      <td class="px-3 py-2 text-center font-medium">C</td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-red-200">
                        <span class="inline-block w-5 h-5 bg-red-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-red-200">
                        <span class="inline-block w-5 h-5 bg-red-500 rounded-sm"></span>
                      </td>
                      <td class="px-3 py-2 text-center bg-green-200">
                        <span class="inline-block w-5 h-5 bg-green-500 rounded-sm"></span>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <div class="mt-4 flex items-center">
                <div class="flex items-center space-x-2 mr-4">
                  <span class="inline-block w-4 h-4 bg-green-500 rounded-sm"></span>
                  <span class="text-sm">- Yes</span>
                </div>
                <div class="flex items-center space-x-2">
                  <span class="inline-block w-4 h-4 bg-red-500 rounded-sm"></span>
                  <span class="text-sm">- No</span>
                </div>
              </div>
              
              <div class="mt-4 space-y-2 text-sm">
                <p>Flow A: PR approval need Approver L#1, Approver L#2 and Approver L#3.</p>
                <p>Flow B: PR approval need Approver L#1 and Approver L#2.</p>
                <p>Flow C: PR approval need Approver L#1.</p>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isFlowGuideModalOpen = false"
                >
                  Close
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Location Table Modal -->
  <TransitionRoot appear :show="isLocationTableModalOpen" as="template">
    <Dialog as="div" @close="isLocationTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Location Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="location in locationOptions" :key="location.code" @click="selectLocation(location)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isLocationTableModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  
  <!-- Source Code Table Modal -->
  <TransitionRoot appear :show="isSourceCodeTableModalOpen" as="template">
    <Dialog as="div" @close="isSourceCodeTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Source Code Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE CODE</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE NAME</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="source in sourceCodeOptions" :key="source.code" @click="selectSourceCode(source)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isSourceCodeTableModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  
  <!-- GL Distribution Table Modal -->
  <TransitionRoot appear :show="isGLDistTableModalOpen" as="template">
    <Dialog as="div" @close="isGLDistTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-3xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                AP G/L Distribution Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">DL DIST#</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GL Dist Name</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">GL Account#</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Link to GL</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="dist in glDistOptions" :key="dist.code" @click="selectGLDist(dist)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dist.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dist.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ dist.account }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">OK</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Normal</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isGLDistTableModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  
  <!-- RC Accrual Note Modal -->
  <TransitionRoot appear :show="isRcAccrualNoteModalOpen" as="template">
    <Dialog as="div" @close="isRcAccrualNoteModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                RC Accrual Note
              </DialogTitle>
              
              <div class="p-4 border rounded-lg">
                <ol class="list-decimal space-y-2 ml-5">
                  <li>If Post Accrued = Y, then you have activated Post RC as Accrued.</li>
                  <li>RC without Supplier Invoice# is auto registered as RC Accrued.</li>
                  <li>RC Accrued will be default to end of the month. For Example:
                    <div class="text-red-500 ml-5">
                      Current Period: 04/2016<br />
                      Document Date for Accrued Transaction when post to Financial Management will be 30/04/2016
                    </div>
                  </li>
                  <li>RC Accrued will not post to AP, it will only post to GL. It will DR GL Purchase and CR GL Accrued.</li>
                  <li>Then later when Supplier present the Invoice, you will input into AP module.</li>
                  <li>In AP Module, you will DR GL Accrued (to reverse out) and CR GL Creditors Control.</li>
                </ol>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isRcAccrualNoteModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- RT Location Table Modal -->
  <TransitionRoot appear :show="isRtLocationTableModalOpen" as="template">
    <Dialog as="div" @close="isRtLocationTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Location Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="location in locationOptions" :key="location.code" @click="selectRtLocation(location)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isRtLocationTableModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
  
  <!-- Source Code Table Modal for DN -->
  <TransitionRoot appear :show="isDnSourceCodeTableModalOpen" as="template">
    <Dialog as="div" @close="isDnSourceCodeTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Source Code Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE CODE</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE NAME</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="source in sourceCodeOptions" :key="source.code" @click="selectDnSourceCode(source)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-between">
                <div class="space-x-3">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="() => {}"
                  >
                    Select
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="isDnSourceCodeTableModalOpen = false"
                  >
                    Exit
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Source Code Table Modal for CN -->
  <TransitionRoot appear :show="isCnSourceCodeTableModalOpen" as="template">
    <Dialog as="div" @close="isCnSourceCodeTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Source Code Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE CODE</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE NAME</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="source in sourceCodeOptions" :key="source.code" @click="selectCnSourceCode(source)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-between">
                <div class="space-x-3">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="() => {}"
                  >
                    Select
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="isCnSourceCodeTableModalOpen = false"
                  >
                    Exit
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Source Code Table Modal for IS -->
  <TransitionRoot appear :show="isIsGlSourceCodeTableModalOpen" as="template">
    <Dialog as="div" @close="isIsGlSourceCodeTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Source Code Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE CODE</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE NAME</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="source in glSourceCodeOptions" :key="source.code" @click="selectIsGlSourceCode(source)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-between">
                <div class="space-x-3">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="() => {}"
                  >
                    Select
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="isIsGlSourceCodeTableModalOpen = false"
                  >
                    Exit
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- MI Location Table Modal -->
  <TransitionRoot appear :show="isMiLocationModalOpen" as="template">
    <Dialog as="div" @close="isMiLocationModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Location Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Code</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="location in locationOptions" :key="location.code" @click="selectMiLocation(location)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ location.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-end space-x-3">
                <button
                  type="button"
                  class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                  @click="isMiLocationModalOpen = false"
                >
                  Exit
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Source Code Table Modal for MI -->
  <TransitionRoot appear :show="isMiGlSourceCodeTableModalOpen" as="template">
    <Dialog as="div" @close="isMiGlSourceCodeTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Source Code Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE CODE</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE NAME</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="source in glSourceCodeOptions" :key="source.code" @click="selectMiGlSourceCode(source)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-between">
                <div class="space-x-3">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="() => {}"
                  >
                    Select
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="isMiGlSourceCodeTableModalOpen = false"
                  >
                    Exit
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- Source Code Table Modal for MO -->
  <TransitionRoot appear :show="isMoGlSourceCodeTableModalOpen" as="template">
    <Dialog as="div" @close="isMoGlSourceCodeTableModalOpen = false" class="relative z-10">
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
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild
            as="template"
            enter="duration-300 ease-out"
            enter-from="opacity-0 scale-95"
            enter-to="opacity-100 scale-100"
            leave="duration-200 ease-in"
            leave-from="opacity-100 scale-100"
            leave-to="opacity-0 scale-95"
          >
            <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">
                Source Code Table
              </DialogTitle>
              
              <div class="border rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50">
                    <tr>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE CODE</th>
                      <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SOURCE NAME</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="source in glSourceCodeOptions" :key="source.code" @click="selectMoGlSourceCode(source)" class="cursor-pointer hover:bg-blue-50">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.code }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ source.name }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>

              <div class="mt-4 flex justify-between">
                <div class="space-x-3">
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="() => {}"
                  >
                    Select
                  </button>
                  <button
                    type="button"
                    class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200 focus:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                    @click="isMoGlSourceCodeTableModalOpen = false"
                  >
                    Exit
                  </button>
                </div>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- OLDA Category Modal -->
  <TransitionRoot appear :show="isOldaCategoryModalOpen" as="template">
    <Dialog as="div" @close="isOldaCategoryModalOpen = false" class="relative z-10">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
            <DialogPanel class="w-full max-w-2xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">Setup Category Email Yes/No</DialogTitle>
              <div class="max-h-96 overflow-y-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50 sticky top-0">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Active</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Obsolete</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="cat in oldaCategoryOptions" :key="cat.category">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ cat.category }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ cat.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <input type="checkbox" v-model="cat.email" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                      </td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ cat.active }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ cat.obsolete }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="mt-4 flex justify-end">
                <button type="button" @click="isOldaCategoryModalOpen = false" class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200">
                  Close
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

  <!-- OLDA SKU Modal -->
    <TransitionRoot appear :show="isOldaSkuModalOpen" as="template">
    <Dialog as="div" @close="isOldaSkuModalOpen = false" class="relative z-10">
      <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0" enter-to="opacity-100" leave="duration-200 ease-in" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 bg-black bg-opacity-25" />
      </TransitionChild>
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center">
          <TransitionChild as="template" enter="duration-300 ease-out" enter-from="opacity-0 scale-95" enter-to="opacity-100 scale-100" leave="duration-200 ease-in" leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
            <DialogPanel class="w-full max-w-4xl transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium leading-6 text-gray-900 mb-4">Setup SKU Email Yes/No</DialogTitle>
              <div class="max-h-96 overflow-y-auto border rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                  <thead class="bg-gray-50 sticky top-0">
                    <tr>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU#</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">SKU Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category Name</th>
                      <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-200">
                    <tr v-for="sku in filteredOldaSkuOptions" :key="sku.sku">
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sku.sku }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sku.name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sku.category }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ sku.category_name }}</td>
                      <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        <input type="checkbox" v-model="sku.email" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              <div class="mt-4 flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <label for="olda_sku_search" class="text-sm font-medium">SKU#:</label>
                    <input type="text" v-model="oldaSkuSearchTerm" id="olda_sku_search" class="block w-64 rounded-md border-gray-300 shadow-sm sm:text-sm">
                    <button @click="searchOldaSku" type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Search
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <button @click="tickAllSkus" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Tick All
                    </button>
                    <button @click="untickAllSkus" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        UnTick All
                    </button>
                </div>
              </div>
              <div class="mt-4 flex justify-end">
                <button type="button" @click="isOldaSkuModalOpen = false" class="inline-flex justify-center rounded-md border border-transparent bg-blue-100 px-4 py-2 text-sm font-medium text-blue-900 hover:bg-blue-200">
                  Close
                </button>
              </div>
            </DialogPanel>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>