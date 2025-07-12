<template>
  <div v-if="show" class="fixed inset-0 z-50 bg-black bg-opacity-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-6xl mx-auto max-h-[95vh] flex flex-col overflow-hidden">
      <!-- Modal Header -->
      <div class="flex items-center justify-between p-3 border-b border-gray-200 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-t-lg">
        <h3 class="text-xl font-semibold flex items-center text-white">
          <i class="fas fa-search-plus mr-2"></i>
          <span v-if="!currentLogView && !showIdcCadOptions && !currentIdcCadView && !showMspView">Zoom Master Card Specification</span>
          <span v-if="currentLogView === 'status'">Zoom Master Card Status Log</span>
          <span v-if="currentLogView === 'approval'">Zoom Master Card Approval Log</span>
          <span v-if="currentLogView === 'maintenance'">Zoom Master Card Maintenance Log</span>
          <span v-if="showIdcCadOptions">Option</span>
          <span v-if="currentIdcCadView === 'idc'">View Master Card IDC Diagram</span>
          <span v-if="currentIdcCadView === 'cad'">CAD File Help Table</span>
          <span v-if="showMspView">Machine Selecting Procedure</span>
        </h3>
        <button type="button" @click="closeModal" class="text-white hover:text-gray-200 focus:outline-none">
          <i class="fas fa-power-off text-xl"></i>
        </button>
      </div>

      <!-- Main Content Area (if no log/IDC/CAD/MSP is shown) -->
      <div v-if="!currentLogView && !showIdcCadOptions && !currentIdcCadView && !showMspView" class="p-4 flex-grow overflow-auto">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
          <!-- Left Column -->
          <div class="md:col-span-2">
            <div class="grid grid-cols-2 gap-2 text-sm">
              <div class="flex items-center">
                <label class="font-medium w-24">AC#:</label>
                <input type="text" :value="mc.ac_number" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
              <div class="flex items-center">
                <input type="text" :value="mc.customer_name" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
              <div class="flex items-center">
                <label class="font-medium w-24">MCS#:</label>
                <input type="text" :value="mc.mc_seq" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
              <div class="flex items-center">
                <input type="text" :value="mc.mc_model" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
              <div class="flex items-center">
                <label class="font-medium w-24">Status:</label>
                <input type="text" :value="mc.status" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
              <div class="flex items-center">
                <label class="font-medium w-24">User ID:</label>
                <input type="text" :value="mc.user_id" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
              <div class="flex items-center col-span-2">
                <label class="font-medium w-24">Note:</label>
                <input type="text" :value="mc.note" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
              <div class="flex items-center col-span-2">
                <label class="font-medium w-24">Approved:</label>
                <input type="text" :value="mc.approved" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-gray-100" readonly />
              </div>
            </div>
          </div>
          
          <!-- Right Column for Log Buttons -->
          <div class="md:col-span-1 flex flex-col justify-start items-end space-y-2">
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 w-full max-w-[150px]" @click="showLog('status')">Status Log</button>
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 w-full max-w-[150px]" @click="showLog('approval')">Approval Log</button>
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 w-full max-w-[150px]" @click="showLog('maintenance')">Maint. Log</button>
          </div>
        </div>

        <!-- PRODUCT Section -->
        <div class="border p-4 rounded-lg shadow-sm mb-4 bg-gray-50">
          <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">PRODUCT</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
            <div class="flex items-center col-span-full">
              <label class="w-32 font-medium">P/Design:</label>
              <input type="text" :value="mc.p_design" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
              <label class="w-20 font-medium ml-4">Flute:</label>
              <input type="text" :value="mc.flute" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
              <label class="w-24 font-medium ml-4">Part No:</label>
              <input type="text" :value="mc.part" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex items-center col-span-full">
              <label class="w-32 font-medium">Description:</label>
              <input type="text" :value="mc.description" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-40 font-medium">System Gross Area:</label>
              <input type="text" :value="mc.sys_gross_area" class="w-24 border border-gray-300 rounded px-2 py-1 text-right bg-gray-100" readonly />
              <span class="ml-1">m2</span>
            </div>
            <div class="flex items-center">
              <label class="w-40 font-medium">System Gross Weight:</label>
              <input type="text" :value="mc.sys_gross_weight" class="w-24 border border-gray-300 rounded px-2 py-1 text-right bg-gray-100" readonly />
              <span class="ml-1">kg</span>
            </div>
            <div class="flex items-center">
              <label class="w-40 font-medium">Input Gross Area:</label>
              <input type="text" :value="mc.input_gross_area" class="w-24 border border-gray-300 rounded px-2 py-1 text-right bg-gray-100" readonly />
              <span class="ml-1">m2</span>
            </div>
            <div class="flex items-center">
              <label class="w-40 font-medium">Input Net Weight:</label>
              <input type="text" :value="mc.input_net_weight" class="w-24 border border-gray-300 rounded px-2 py-1 text-right bg-gray-100" readonly />
              <span class="ml-1">kg</span>
            </div>
            <div class="flex items-center">
              <label class="w-40 font-medium">Input Net Area:</label>
              <input type="text" :value="mc.input_net_area" class="w-24 border border-gray-300 rounded px-2 py-1 text-right bg-gray-100" readonly />
              <span class="ml-1">m2</span>
            </div>
          </div>
        </div>

        <!-- B/QUALITY DB Section -->
        <div class="border p-4 rounded-lg shadow-sm mb-4 bg-gray-50">
          <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">B/QUALITY DB</h4>
          <div class="grid grid-cols-1 md:grid-cols-4 gap-3 text-sm">
            <div class="flex flex-col">
              <label class="text-xs font-medium text-gray-600">B</label>
              <input type="text" :value="mc.b_quality" class="border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex flex-col">
              <label class="text-xs font-medium text-gray-600">1L</label>
              <input type="text" :value="mc.l1_quality" class="border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex flex-col">
              <label class="text-xs font-medium text-gray-600">A/C/E</label>
              <input type="text" :value="mc.ace_quality" class="border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex flex-col">
              <label class="text-xs font-medium text-gray-600">2L</label>
              <input type="text" :value="mc.l2_quality" class="border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>

            <div class="flex items-center col-span-full">
              <label class="w-16 font-medium">SO:</label>
              <input type="text" :value="mc.so_data" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex items-center col-span-full">
              <label class="w-16 font-medium">WO:</label>
              <input type="text" :value="mc.wo_data" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex items-center col-span-full">
              <label class="w-16 font-medium">ID:</label>
              <input type="text" :value="mc.id_data" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex items-center col-span-full">
              <label class="w-16 font-medium">ED:</label>
              <input type="text" :value="mc.ed_data" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
          </div>
        </div>

        <!-- CORRUGATING Section -->
        <div class="border p-4 rounded-lg shadow-sm mb-4 bg-gray-50">
          <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">CORRUGATING</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
            <div>
              <label class="block font-medium">Score L:</label>
              <div class="flex items-center space-x-1">
                <input type="text" :value="mc.score_l_1" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="font-bold">+</span>
                <input type="text" :value="mc.score_l_2" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="font-bold">+</span>
                <input type="text" :value="mc.score_l_3" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="font-bold">+</span>
                <input type="text" :value="mc.score_l_4" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="font-bold">+</span>
                <input type="text" :value="mc.score_l_total" class="w-20 border border-gray-300 rounded px-2 py-1 bg-gray-100 text-right" readonly />
              </div>
            </div>
            <div>
              <label class="block font-medium">Sheet Length:</label>
              <div class="flex items-center space-x-1">
                <input type="text" :value="mc.sheet_length" class="w-24 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="ml-1">mm</span>
              </div>
            </div>
            <div>
              <label class="block font-medium">Score W:</label>
              <div class="flex items-center space-x-1">
                <input type="text" :value="mc.score_w_1" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="font-bold">+</span>
                <input type="text" :value="mc.score_w_2" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="font-bold">+</span>
                <input type="text" :value="mc.score_w_3" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="font-bold">=</span>
                <input type="text" :value="mc.score_w_total" class="w-20 border border-gray-300 rounded px-2 py-1 bg-gray-100 text-right" readonly />
              </div>
            </div>
            <div>
              <label class="block font-medium">Sheet Width:</label>
              <div class="flex items-center space-x-1">
                <input type="text" :value="mc.sheet_width" class="w-24 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
                <span class="ml-1">mm</span>
              </div>
            </div>
            <div class="flex items-center">
              <label class="w-20 font-medium">P/Size:</label>
              <input type="text" :value="mc.p_size" class="w-24 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
              <span class="ml-1">mm</span>
            </div>
            <div class="flex items-center">
              <label class="w-24 font-medium">Corr. Out:</label>
              <input type="text" :value="mc.corr_out" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-32 font-medium">Conv. Out 1 x 2:</label>
              <input type="text" :value="mc.conv_out_1x2" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-24 font-medium">Pcs-to-Joint:</label>
              <input type="text" :value="mc.pcs_to_joint" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-24 font-medium">Cremase:</label>
              <input type="text" :value="mc.crease" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-24 font-medium">Chem.Coat:</label>
              <input type="text" :value="mc.chem_coat" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-24 font-medium">R/F Tape:</label>
              <input type="text" :value="mc.rf_tape" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="col-span-full flex justify-end">
              <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300" @click="showIdcCadOptions = true">IDC/CAD</button>
            </div>
          </div>
        </div>

        <!-- CONVERTING Section -->
        <div class="border p-4 rounded-lg shadow-sm mb-4 bg-gray-50">
          <h4 class="font-bold text-gray-800 mb-3 border-b pb-2">CONVERTING</h4>
          <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-sm">
            <div class="flex items-center">
              <label class="w-28 font-medium">Print Color:</label>
              <input type="text" :value="mc.print_color_1" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
              <span class="font-bold ml-1">+</span>
              <input type="text" :value="mc.print_color_2" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
              <span class="font-bold ml-1">+</span>
              <input type="text" :value="mc.print_color_3" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Pit Block#:</label>
              <input type="text" :value="mc.pit_block_no" class="w-24 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Hand Hole:</label>
              <input type="checkbox" :checked="mc.hand_hole" class="form-checkbox h-5 w-5 text-blue-600" disabled />
            </div>

            <div class="flex items-center">
              <label class="w-28 font-medium">Print Area(%):</label>
              <input type="text" :value="mc.print_area_1" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
              <span class="font-bold ml-1">+</span>
              <input type="text" :value="mc.print_area_2" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
              <span class="font-bold ml-1">+</span>
              <input type="text" :value="mc.print_area_3" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Glueing:</label>
              <input type="checkbox" :checked="mc.glueing" class="form-checkbox h-5 w-5 text-blue-600" disabled />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Rotary D/Cut:</label>
              <input type="checkbox" :checked="mc.rotary_d_cut" class="form-checkbox h-5 w-5 text-blue-600" disabled />
            </div>
            
            <div class="flex items-center">
              <label class="w-28 font-medium">D/Cut Sheet:</label>
              <input type="checkbox" :checked="mc.d_cut_sheet" class="form-checkbox h-5 w-5 text-blue-600" disabled />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Wrapping:</label>
              <input type="checkbox" :checked="mc.wrapping" class="form-checkbox h-5 w-5 text-blue-600" disabled />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Full Block Print:</label>
              <input type="checkbox" :checked="mc.full_block_print" class="form-checkbox h-5 w-5 text-blue-600" disabled />
            </div>

            <div class="flex items-center">
              <label class="w-28 font-medium">D/Cut Block#:</label>
              <input type="text" :value="mc.d_cut_block_no" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Bdl/Pallet:</label>
              <input type="text" :value="mc.bdl_pallet" class="w-24 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="col-span-full"></div>

            <div class="flex items-center">
              <label class="w-28 font-medium">D/Cut Mould:</label>
              <input type="checkbox" :checked="mc.d_cut_mould" class="form-checkbox h-5 w-5 text-blue-600" disabled />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Peel-Off%:</label>
              <input type="text" :value="mc.peel_off_percent" class="w-24 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-start flex-col">
              <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 w-28 mb-1" @click="showMsp">MSP</button>
              <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 w-28">Sub-Material</button>
            </div>

            <div class="flex items-center">
              <label class="w-28 font-medium">Finishing:</label>
              <input type="text" :value="mc.finishing_1" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
              <span class="font-bold ml-1">+</span>
              <input type="text" :value="mc.finishing_2" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>
            <div class="flex items-center">
              <label class="w-28 font-medium">Bdle. String-Pcs:</label>
              <input type="text" :value="mc.bdle_string_pcs_1" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
              <span class="font-bold ml-1">+</span>
              <input type="text" :value="mc.bdle_string_pcs_2" class="w-16 border border-gray-300 rounded px-2 py-1 bg-white text-right" readonly />
            </div>

            <div class="flex items-center col-span-full">
              <label class="w-28 font-medium">Item Remark:</label>
              <input type="text" :value="mc.item_remark" class="flex-grow border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
          </div>
        </div>
      </div>

      <!-- IDC/CAD Options View -->
      <div v-if="showIdcCadOptions" class="p-4 flex-grow flex flex-col items-center justify-center">
        <div class="border p-6 rounded-lg shadow-md bg-gray-50 flex flex-col items-start space-y-4">
          <h4 class="font-bold text-gray-800 mb-2">Option</h4>
          <div class="flex flex-col space-y-2">
            <label class="inline-flex items-center">
              <input type="radio" class="form-radio text-blue-600 h-4 w-4" value="idc" v-model="selectedIdcCadOption">
              <span class="ml-2 text-gray-700">By IDC</span>
            </label>
            <label class="inline-flex items-center">
              <input type="radio" class="form-radio text-blue-600 h-4 w-4" value="cad" v-model="selectedIdcCadOption">
              <span class="ml-2 text-gray-700">By CAD</span>
            </label>
          </div>
          <div class="flex justify-end w-full mt-4 space-x-2">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="selectIdcCadOption">OK</button>
            <button class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400" @click="exitIdcCadView">Exit</button>
          </div>
        </div>
      </div>

      <!-- IDC Diagram View -->
      <div v-if="currentIdcCadView === 'idc'" class="p-4 flex-grow overflow-auto flex flex-col items-center justify-center text-xs font-mono">
        <div class="relative bg-white border border-gray-400 p-4 min-w-[600px] min-h-[400px] flex flex-col items-center justify-center">
          <div class="absolute top-2 left-4 text-sm font-bold">0201 - Regular Slotted Carton [RSC]</div>
          <pre class="whitespace-pre text-sm leading-tight">
        +-----+--------------------------+--------------------------+----------+
        |     |                          |                          |          | 122
        |     |                          |                          |          |
        |     !--------------------------!--------------------------!          |
        |     |                          |                          |          | 297
        |     |                          |                          |          | 541
        |     !--------------------------!--------------------------!          |
        |     |                          |                          |          | 122
        +-----+--------------------------+--------------------------+----------+
              ^            ^                          ^            ^
              |            |                          |            |
              30          396                        243          396
                                           -------------------- 1305 --------------------

                                                         FLUTE
                                                         II II II
                                                         II II II
                                                         II II II
          </pre>
          <div class="flex justify-center mt-auto w-full">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="exitIdcCadView">Exit</button>
          </div>
        </div>
      </div>

      <!-- CAD File Help Table View -->
      <div v-if="currentIdcCadView === 'cad'" class="p-4 flex-grow flex flex-col h-full">
        <div class="flex-grow overflow-auto border border-gray-200 rounded-md shadow-sm mb-4">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50 sticky top-0">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. CAD FILENAME</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">REMARKS</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-if="cadData.length === 0">
                <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No CAD files found.</td>
              </tr>
              <tr v-for="(entry, index) in cadData" :key="index">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.filename }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.remarks }}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="flex justify-center mt-auto space-x-2">
          <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Zoom</button>
          <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="exitIdcCadView">Exit</button>
        </div>
      </div>

      <!-- Log View Area -->
      <div v-if="currentLogView" class="p-4 flex-grow overflow-auto">
        <!-- Status Log Table -->
        <div v-if="currentLogView === 'status'" class="flex flex-col h-full">
          <div class="flex-grow overflow-auto border border-gray-200 rounded-md shadow-sm mb-4">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="logData.length === 0">
                  <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No records found.</td>
                </tr>
                <tr v-for="(entry, index) in logData" :key="index">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.no }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.date }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.time }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.user_id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.action }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="flex items-center space-x-2 mt-auto">
            <label class="text-sm font-medium">No.:</label>
            <input type="text" class="w-16 border border-gray-300 rounded px-2 py-1 text-right" value="0" />
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Search</button>
            <input type="text" class="flex-grow border border-gray-300 rounded px-2 py-1" value="cccc" />
          </div>
          <div class="flex justify-center mt-4">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="closeModal">Exit</button>
          </div>
        </div>

        <!-- Approval Log Table -->
        <div v-if="currentLogView === 'approval'" class="flex flex-col h-full">
          <div class="flex-grow overflow-auto border border-gray-200 rounded-md shadow-sm mb-4">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="logData.length === 0">
                  <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No records found.</td>
                </tr>
                <tr v-for="(entry, index) in logData" :key="index">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.date }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.time }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.user_id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.action }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="flex items-center space-x-2 mt-auto">
            <label class="text-sm font-medium">Date:</label>
            <input type="text" class="w-32 border border-gray-300 rounded px-2 py-1" value="00/00/0000" />
            <button class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Search</button>
            <input type="text" class="flex-grow border border-gray-300 rounded px-2 py-1" value="dd/mm/yyyy" />
          </div>
          <div class="flex justify-center mt-4">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="closeModal">Exit</button>
          </div>
        </div>

        <!-- Maintenance Log Table -->
        <div v-if="currentLogView === 'maintenance'" class="flex flex-col h-full">
          <div class="flex-grow overflow-auto border border-gray-200 rounded-md shadow-sm mb-4">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User ID 2</th>
                  <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="logData.length === 0">
                  <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No records found.</td>
                </tr>
                <tr v-for="(entry, index) in logData" :key="index">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.no }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.date }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.time }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.user_id }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.user_id_2 }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ entry.action }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="flex items-center space-x-2 mt-auto">
            <label class="text-sm font-medium">Date:</label>
            <input type="text" class="w-32 border border-gray-300 rounded px-2 py-1" value="00/00/0000" />
            <button class="px-6 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300">Search</button>
            <input type="text" class="flex-grow border border-gray-300 rounded px-2 py-1" value="dd/mm/yyyy" />
          </div>
          <div class="flex justify-center mt-4">
            <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="closeModal">Exit</button>
          </div>
        </div>
      </div>

      <!-- Machine Selecting Procedure (MSP) View -->
      <div v-if="showMspView" class="p-4 flex-grow flex flex-col h-full">
        <div class="border p-4 rounded-lg shadow-sm mb-4 bg-gray-50">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <div>
              <label class="inline-flex items-center mr-4">
                <input type="radio" class="form-radio text-blue-600 h-4 w-4" name="releaseType" value="release" checked>
                <span class="ml-2 text-gray-700">Release Work Order</span>
              </label>
              <label class="inline-flex items-center">
                <input type="radio" class="form-radio text-blue-600 h-4 w-4" name="releaseType" value="non-release">
                <span class="ml-2 text-gray-700">Non-Release</span>
              </label>
            </div>
            <div class="flex items-center justify-end">
              <label class="font-medium mr-2">Max.FG Level:</label>
              <input type="text" value="0" class="w-16 border border-gray-300 rounded px-2 py-1 text-right bg-white" readonly />
              <span class="ml-2 text-sm text-gray-600">No W/Order once F/G on-hand hits maximum level; 0 = No Control</span>
            </div>
          </div>

          <div class="flex-grow overflow-auto border border-gray-200 rounded-md shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
              <thead class="bg-gray-50 sticky top-0">
                <tr>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Step</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mch Code</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Machine Name</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">P/Day</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No-Up</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Setup Min+/- Min</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Setup Min</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net Min</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target Speed</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Speed +/-</th>
                  <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Net SpeedSpecial Instruction</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-if="mspData.length === 0">
                  <td colspan="11" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No machine selecting procedure data found.</td>
                </tr>
                <tr v-for="(row, index) in mspData" :key="index" :class="{'bg-blue-100': row.step === 10}">
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.step }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.mch_code }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.machine_name }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.p_day }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.no_up }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.setup_min_plus_min }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.setup_min }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.net_min }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.target_speed }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.speed_plus_minus }}</td>
                  <td class="px-3 py-2 whitespace-nowrap text-gray-900">{{ row.net_speed_special_instruction }}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="flex justify-between items-center mt-4 text-sm">
            <div class="flex items-center">
              <label class="font-medium mr-2">Total Prod Day:</label>
              <input type="text" value="2" class="w-16 border border-gray-300 rounded px-2 py-1 text-right bg-white" readonly />
            </div>
            <div class="flex items-center">
              <label class="font-medium mr-2">Total Up:</label>
              <input type="text" value="4/1" class="w-16 border border-gray-300 rounded px-2 py-1 text-right bg-white" readonly />
            </div>
            <div class="flex items-center">
              <label class="font-medium mr-2">Process:</label>
              <input type="text" value="CORRUGATING" class="w-32 border border-gray-300 rounded px-2 py-1 bg-white" readonly />
            </div>
          </div>
        </div>
        <div class="flex justify-center mt-auto">
          <button class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" @click="closeModal">Exit</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, watch } from 'vue';

const props = defineProps({
  show: Boolean,
  mcData: Object, // The Master Card data to display
});

const emit = defineEmits(['close']);

const currentLogView = ref(null); // 'status', 'approval', 'maintenance', or null
const showIdcCadOptions = ref(false);
const currentIdcCadView = ref(null); // 'idc', 'cad', or null
const selectedIdcCadOption = ref('idc'); // Default to 'By IDC'
const logData = ref([]);
const cadData = ref([]); // Data for CAD File Help Table
const showMspView = ref(false); // New state for MSP view
const mspData = ref([
  { step: 10, mch_code: '01', machine_name: 'CORR 250', p_day: 2, no_up: '4/1', setup_min_plus_min: '', setup_min: '', net_min: '', target_speed: 170, speed_plus_minus: '', net_speed_special_instruction: 170 },
  { step: 20, mch_code: '25', machine_name: 'FPS II', p_day: 0, no_up: '1/1', setup_min_plus_min: '', setup_min: '', net_min: '', target_speed: '', speed_plus_minus: '', net_speed_special_instruction: '' },
  { step: 30, mch_code: '21', machine_name: 'STITCH B', p_day: 0, no_up: '1/1', setup_min_plus_minus: '', setup_min: '', net_min: '', target_speed: '', speed_plus_minus: '', net_speed_special_instruction: '' },
  { step: 40, mch_code: 'FG', machine_name: 'FG', p_day: 0, no_up: '1/1', setup_min_plus_minus: '', setup_min: '', net_min: '', target_speed: '', speed_plus_minus: '', net_speed_special_instruction: '' },
]);

const mc = ref({
  // Default/placeholder values, these will be overwritten by props.mcData
  ac_number: '',
  customer_name: '',
  mc_seq: '',
  mc_model: '',
  status: '',
  user_id: '',
  date: '', // Assuming date is part of the data
  note: '',
  approved: '',
  p_design: '',
  flute: '',
  part: '',
  description: '',
  sys_gross_area: '',
  sys_gross_weight: '',
  input_gross_area: '',
  input_net_weight: '',
  input_net_area: '',
  b_quality: '',
  l1_quality: '',
  ace_quality: '',
  l2_quality: '',
  so_data: '',
  wo_data: '',
  id_data: '',
  ed_data: '',
  score_l_1: '',
  score_l_2: '',
  score_l_3: '',
  score_l_4: '',
  score_l_total: '',
  sheet_length: '',
  score_w_1: '',
  score_w_2: '',
  score_w_3: '',
  score_w_total: '',
  sheet_width: '',
  p_size: '',
  corr_out: '',
  conv_out_1x2: '',
  pcs_to_joint: '',
  crease: '',
  chem_coat: '',
  rf_tape: '',
  print_color_1: '',
  print_color_2: '',
  print_color_3: '',
  pit_block_no: '',
  hand_hole: false,
  print_area_1: '',
  print_area_2: '',
  print_area_3: '',
  glueing: false,
  rotary_d_cut: false,
  d_cut_sheet: false,
  wrapping: false,
  full_block_print: false,
  d_cut_block_no: '',
  bdl_pallet: '',
  d_cut_mould: false,
  peel_off_percent: '',
  finishing_1: '',
  finishing_2: '',
  bdle_string_pcs_1: '',
  bdle_string_pcs_2: '',
  item_remark: '',
});

// Mock data for logs
const mockLogs = {
  status: [
    { no: 1, date: '01/01/2023', time: '10:00:00', user_id: 'user1', action: 'Changed Status to Active' },
    { no: 2, date: '01/05/2023', time: '11:30:00', user_id: 'user2', action: 'Changed Status to Obsolete' },
  ],
  approval: [
    { date: '01/11/2018', time: '15:10:11', user_id: 'ppic01', action: 'Approve' },
    { date: '18/10/2018', time: '13:06:25', user_id: 'mc02', action: 'Approve' },
  ],
  maintenance: [
    { date: '01/11/2018', time: '08:43:09', user_id: 'mc01', user_id_2: 'mcs', action: 'Amend M/Card' },
    { date: '31/10/2018', time: '15:56:34', user_id: 'mc01', user_id_2: 'mcs', action: 'Amend M/Card' },
    { date: '18/10/2018', time: '13:00:48', user_id: 'mc02', user_id_2: 'mcs', action: 'New M/Card' },
  ],
};

// Mock data for CAD files
const mockCadFiles = [
  { filename: 'CAD001.dxf', remarks: 'Sample CAD file 1' },
  { filename: 'CAD002.dwg', remarks: 'Sample CAD file 2' },
];

watch(() => props.mcData, (newVal) => {
  if (newVal) {
    Object.assign(mc.value, newVal);
    // Add default values for new fields if they are not present in mcData
    mc.value.ac_number = newVal.ac_number || '000211-08';
    mc.value.customer_name = newVal.customer_name || 'ABDULLAH, BPK';
    mc.value.user_id = newVal.user_id || 'mc01';
    mc.value.date = newVal.date || '01/11/2018';
    mc.value.note = newVal.note || '';
    mc.value.approved = newVal.approved || 'Yes';
    mc.value.flute = newVal.flute || 'BF';
    mc.value.description = newVal.description || 'BOX BASO 4,5 KG';
    mc.value.sys_gross_area = newVal.sys_gross_area || '0.7205';
    mc.value.sys_gross_weight = newVal.sys_gross_weight || '0.3062';
    mc.value.input_gross_area = newVal.input_gross_area || '';
    mc.value.input_net_weight = newVal.input_net_weight || '';
    mc.value.input_net_area = newVal.input_net_area || '';
    mc.value.b_quality = newVal.b_quality || 'KL125';
    mc.value.l1_quality = newVal.l1_quality || 'ML125';
    mc.value.ace_quality = newVal.ace_quality || 'ML125';
    mc.value.l2_quality = newVal.l2_quality || '';
    mc.value.so_data = newVal.so_data || 'KL125';
    mc.value.wo_data = newVal.wo_data || 'KL125';
    mc.value.id_data = newVal.id_data || '393 L 240 W';
    mc.value.ed_data = newVal.ed_data || '396 L 243 W';
    mc.value.score_l_1 = newVal.score_l_1 || '30';
    mc.value.score_l_2 = newVal.score_l_2 || '396';
    mc.value.score_l_3 = newVal.score_l_3 || '243';
    mc.value.score_l_4 = newVal.score_l_4 || '396';
    mc.value.score_l_total = newVal.score_l_total || '1305';
    mc.value.sheet_length = newVal.sheet_length || '1310';
    mc.value.score_w_1 = newVal.score_w_1 || '122';
    mc.value.score_w_2 = newVal.score_w_2 || '297';
    mc.value.score_w_3 = newVal.score_w_3 || '122';
    mc.value.score_w_total = newVal.score_w_total || '541';
    mc.value.sheet_width = newVal.sheet_width || '541';
    mc.value.p_size = newVal.p_size || '2200';
    mc.value.corr_out = newVal.corr_out || '4';
    mc.value.conv_out_1x2 = newVal.conv_out_1x2 || '';
    mc.value.pcs_to_joint = newVal.pcs_to_joint || '1';
    mc.value.crease = newVal.crease || '1';
    mc.value.chem_coat = newVal.chem_coat || '';
    mc.value.rf_tape = newVal.rf_tape || '';
    mc.value.print_color_1 = newVal.print_color_1 || '00003';
    mc.value.print_color_2 = newVal.print_color_2 || '00006';
    mc.value.print_color_3 = newVal.print_color_3 || '';
    mc.value.pit_block_no = newVal.pit_block_no || '1731';
    mc.value.hand_hole = newVal.hand_hole || false;
    mc.value.print_area_1 = newVal.print_area_1 || '0.00';
    mc.value.print_area_2 = newVal.print_area_2 || '0.00';
    mc.value.print_area_3 = newVal.print_area_3 || '0.00';
    mc.value.glueing = newVal.glueing || false;
    mc.value.rotary_d_cut = newVal.rotary_d_cut || false;
    mc.value.d_cut_sheet = newVal.d_cut_sheet || false;
    mc.value.wrapping = newVal.wrapping || false;
    mc.value.full_block_print = newVal.full_block_print || false;
    mc.value.d_cut_block_no = newVal.d_cut_block_no || '';
    mc.value.bdl_pallet = newVal.bdl_pallet || '0';
    mc.value.d_cut_mould = newVal.d_cut_mould || false;
    mc.value.peel_off_percent = newVal.peel_off_percent || '0.00';
    mc.value.finishing_1 = newVal.finishing_1 || '002';
    mc.value.finishing_2 = newVal.finishing_2 || '';
    mc.value.bdle_string_pcs_1 = newVal.bdle_string_pcs_1 || '001';
    mc.value.bdle_string_pcs_2 = newVal.bdle_string_pcs_2 || '25';
    mc.value.item_remark = newVal.item_remark || 'SHEET HARUS KERAS,CETAKAN SESUAI ARTWORK,WARNA COLOUR';
  }
}, { immediate: true });

const showLog = (type) => {
  currentLogView.value = type;
  logData.value = mockLogs[type] || [];
  showIdcCadOptions.value = false;
  currentIdcCadView.value = null;
  showMspView.value = false; // Hide MSP view
};

const showMsp = () => {
  showMspView.value = true;
  currentLogView.value = null;
  showIdcCadOptions.value = false;
  currentIdcCadView.value = null;
};

const selectIdcCadOption = () => {
  currentIdcCadView.value = selectedIdcCadOption.value;
  showIdcCadOptions.value = false;
  currentLogView.value = null;
  showMspView.value = false; // Hide MSP view
  if (currentIdcCadView.value === 'cad') {
    cadData.value = mockCadFiles;
  }
};

const exitIdcCadView = () => {
  if (currentIdcCadView.value) {
    currentIdcCadView.value = null; // Go back to IDC/CAD options if IDC/CAD view is open
    showIdcCadOptions.value = true;
  } else {
    showIdcCadOptions.value = false; // Go back to main MC view if options are open
  }
};

const closeModal = () => {
  if (currentLogView.value) {
    currentLogView.value = null; // Go back to main MC view if a log is open
  } else if (currentIdcCadView.value) {
    exitIdcCadView(); // Use existing function to handle IDC/CAD exit flow
  } else if (showIdcCadOptions.value) {
    showIdcCadOptions.value = false; // Close IDC/CAD options and return to main view
  } else if (showMspView.value) {
    showMspView.value = false; // Go back to main MC view if MSP is open
  }
  else {
    emit('close'); // Close the whole modal if no log or IDC/CAD is open
  }
};
</script>

<style scoped>
/* Add any specific styles for this modal here */
input[type="text"][readonly] {
  background-color: #f3f4f6; /* Tailwind gray-100 */
  cursor: default;
}
</style> 