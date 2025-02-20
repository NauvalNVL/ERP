@extends('layouts.app')

@section('title', 'Define Printer - ERP System')

@section('content')
<div class="container mx-auto mt-10 p-6 bg-white shadow-lg rounded-lg">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Define Printer</h2>
    <div class="flex items-center mb-4">
        <label for="printerCode" class="block text-sm font-medium text-gray-700 mr-2">Printer Code:</label>
        <input type="text" id="printerCode" placeholder="Enter printer code" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button class="ml-2 p-2 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-700 transition duration-300 transform hover:scale-105" onclick="showModal()">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>
    </div>

    <!-- Printer Details -->
    <div id="printerDetails" class="mt-6 hidden">
        <h3 class="text-lg font-semibold">Printer Information</h3>
        <div class="grid grid-cols-2 gap-4 mt-2">
            <div>
                <label class="block text-sm font-medium text-gray-700">Printer Name:</label>
                <span id="printerName" class="block text-gray-600"></span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Printer Type:</label>
                <span id="printerType" class="block text-gray-600"></span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Printer Driver:</label>
                <span id="printerDriver" class="block text-gray-600"></span>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700">Logical Name:</label>
                <span id="logicalName" class="block text-gray-600"></span>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div id="printerModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Printer Table</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Code</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Name</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Type</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Driver</th>
                                <th class="py-2 px-4 border-b text-left text-sm font-medium text-gray-700">Logical Name</th>
                            </tr>
                        </thead>
                        <tbody id="printerTableBody">
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="setSelectedPrinter('/EXCEL', 'Excel File', '3-Excel Writer', 'Dump', '-')">
                                <td class="py-2 px-4 border-b text-sm text-gray-600">/EXCEL</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Excel File</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">3-Excel Writer</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Dump</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">-</td>
                            </tr>
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="setSelectedPrinter('PRINTER1', 'Epson LQ2180-SALES 1', '1-Printer', 'Epson', 'dlk01_P1')">
                                <td class="py-2 px-4 border-b text-sm text-gray-600">PRINTER1</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson LQ2180-SALES 1</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">1-Printer</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">dlk01_P1</td>
                            </tr>
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="setSelectedPrinter('PRINTER2', 'Epson LQ2180-SALES 2', '1-Printer', 'Epson', 'dlk02_P1')">
                                <td class="py-2 px-4 border-b text-sm text-gray-600">PRINTER2</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson LQ2180-SALES 2</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">1-Printer</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">dlk02_P1</td>
                            </tr>
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="setSelectedPrinter('PRINTER3', 'Epson LQ2180-SALES 3', '1-Printer', 'Epson', 'dlk03_P1')">
                                <td class="py-2 px-4 border-b text-sm text-gray-600">PRINTER3</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson LQ2180-SALES 3</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">1-Printer</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">dlk03_P1</td>
                            </tr>
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="setSelectedPrinter('PRINTER4', 'Epson LQ2180-FINANCE 1', '1-Printer', 'Epson', 'dlk04_P1')">
                                <td class="py-2 px-4 border-b text-sm text-gray-600">PRINTER4</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson LQ2180-FINANCE 1</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">1-Printer</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">dlk04_P1</td>
                            </tr>
                            <tr class="hover:bg-gray-50 cursor-pointer" onclick="setSelectedPrinter('PRINTER5', 'Epson LQ2180-FINANCE 2', '1-Printer', 'Epson', 'dlk05_P1')">
                                <td class="py-2 px-4 border-b text-sm text-gray-600">PRINTER5</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson LQ2180-FINANCE 2</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">1-Printer</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">Epson</td>
                                <td class="py-2 px-4 border-b text-sm text-gray-600">dlk05_P1</td>
                            </tr>
                            <!-- Tambahkan baris lain sesuai kebutuhan -->
                        </tbody>
                    </table>
                </div>
                <div class="mt-4 flex justify-center space-x-2">
                    <button id="ok-btn" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-300" onclick="closeModal()">Close</button>
                    <button class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 transition duration-300" onclick="confirmSelection()">Select</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Info Dialog -->
    <div id="infoDialog" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-11/12 max-w-3xl shadow-lg rounded-md bg-white">
            <div class="mt-3 text-left">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Printer Information</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="flex justify-between">
                        <span class="font-medium">Printer Code:</span>
                        <input type="text" id="dialogPrinterCode" class="text-gray-600 border border-gray-300 rounded-md px-2" />
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Printer Name:</span>
                        <input type="text" id="dialogPrinterName" class="text-gray-600 border border-gray-300 rounded-md px-2" />
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Printer Type:</span>
                        <select id="dialogPrinterType" class="text-gray-600 border border-gray-300 rounded-md px-2">
                            <option value="3-Excel Writer">3-Excel Writer</option>
                            <option value="1-Printer">1-Printer</option>
                            <!-- Tambahkan pilihan lain sesuai kebutuhan -->
                        </select>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Printer Driver:</span>
                        <input type="text" id="dialogPrinterDriver" class="text-gray-600 border border-gray-300 rounded-md px-2" />
                        <button class="ml-2 p-2 bg-blue-600 text-white rounded-md shadow-md hover:bg-blue-700 transition duration-300" onclick="showDriverModal()">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                        </button>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium">Logical Name:</span>
                        <input type="text" id="dialogLogicalName" class="text-gray-600 border border-gray-300 rounded-md px-2" />
                    </div>
                </div>
                <p class="mt-4 text-gray-600 font-semibold">Guide:</p>
                <p class="text-red-600">Printer Type 1 - Network Printer</p>
                <p class="text-blue-600">Printer Driver, Internal</p>
                <p class="text-black">This is Server based Printer which prints report from Server to Network Printer. This is good for ASCII and ANSI character sets. Logical Name is needed based on Server Printer setup.</p>
                <p class="text-blue-600">Printer Driver, Universal</p>
                <p class="text-black">This is Windows PC based Printers which prints report from PC to IP Network Printers. This is good for International character sets like Thai, Vietnam, etc. Logical Name is needed based on PC Printer setup.</p>
                <p class="text-blue-600">Printer Driver, MSPDF</p>
                <p class="text-black">This is available for CPS 2020 and Windows 10 onwards and Business Forms must be in Universal programming code.</p>
                <p class="text-blue-600">Printer Driver, XPS</p>
                <p class="text-black">This is available for CPS 2020 and Windows 7 onwards and Business Forms must be in Universal programming code.</p>
                <p class="text-red-600">Printer Type 2 - Report Viewer</p>
                <p class="text-black">This prints the report into Screen Viewer. Printer Driver is DUMP and Logical Name is blank.</p>
                <p class="text-red-600">Printer Type 3 - Excel Writer</p>
                <p class="text-black">This prints the report into Excel. Printer Driver is DUMP and Logical Name is blank.</p>
                <p class="text-red-600">Printer Type 4 - UNIX/LINUX CRON Printer</p>
                <p class="text-black">This is good for Distributed Computing architecture where report is processed in Server and sent over to Network Printer.</p>
                <div class="mt-4 flex justify-center space-x-2">
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300" onclick="updatePrinterTable()">Update</button>
                    <button class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 transition duration-300" onclick="closeInfoDialog()">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Printer Driver Modal -->
    <div id="driverModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-8 border w-11/12 max-w-4xl shadow-2xl rounded-xl bg-white">
            <div class="mt-3">
                <h3 class="text-2xl font-bold text-gray-900 mb-6 text-center">Printer Driver Selection</h3>
                <div class="overflow-x-auto rounded-xl shadow-md border border-gray-100">
                    <table class="min-w-full bg-white">
                        <thead class="bg-gradient-to-r from-blue-500 to-blue-600">
                            <tr>
                                <th class="py-3 px-6 text-left text-sm font-semibold text-white">Code</th>
                                <th class="py-3 px-6 text-left text-sm font-semibold text-white">Name</th>
                                <th class="py-3 px-6 text-left text-sm font-semibold text-white">Path</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('Dump')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">Dump</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Dump</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/dump</td>
                            </tr>
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('Epson')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">Epson</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Epson Printer</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/epson</td>
                            </tr>
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('HP Laser')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">HP Laser</td>
                                <td class="py-4 px-6 text-sm text-gray-600">HP Laser PCL - A4</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/hplaser</td>
                            </tr>
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('HP Laser2')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">HP Laser2</td>
                                <td class="py-4 px-6 text-sm text-gray-600">HP Laser PCL - A4 - Top Zero Margin</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/hplaser2</td>
                            </tr>
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('IBM ProPrinter')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">IBM ProPrinter</td>
                                <td class="py-4 px-6 text-sm text-gray-600">IBM ProPrinter</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/ibmproprinter</td>
                            </tr>
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('Star Printer')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">Star Printer</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Star Printer</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/starprinter</td>
                            </tr>
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('Universal HP Laser')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">Universal HP Laser</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Universal HP Laser Printer - A4</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/universalhplaser</td>
                            </tr>
                            <tr class="hover:bg-blue-50 transition-colors duration-200 cursor-pointer" onclick="selectDriver('Universal HP Laser2')">
                                <td class="py-4 px-6 text-sm font-medium text-gray-700">Universal HP Laser2</td>
                                <td class="py-4 px-6 text-sm text-gray-600">Universal HP Laser Printer - A3</td>
                                <td class="py-4 px-6 text-sm text-gray-500 font-mono">/p/mix/lib/drivers/pt/universalhplaser2</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button class="px-6 py-2.5 bg-blue-500 text-white rounded-lg hover:bg-gray-600 transition duration-300 ease-in-out transform hover:scale-105 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-opacity-50" onclick="closeDriverModal()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let selectedPrinter = {
        code: '',
        name: '',
        type: '',
        driver: '',
        logicalName: ''
    };

    function showModal() {
        document.getElementById('printerModal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('printerModal').classList.add('hidden');
    }

    function setSelectedPrinter(code, name, type, driver, logicalName) {
        selectedPrinter = { code, name, type, driver, logicalName }; // Simpan informasi printer yang dipilih
    }

    function confirmSelection() {
        document.getElementById('dialogPrinterCode').value = selectedPrinter.code; // Tampilkan kode di dialog
        document.getElementById('dialogPrinterName').value = selectedPrinter.name; // Tampilkan nama printer di dialog
        document.getElementById('dialogPrinterType').value = selectedPrinter.type; // Tampilkan tipe printer di dialog
        document.getElementById('dialogPrinterDriver').value = selectedPrinter.driver; // Tampilkan driver printer di dialog
        document.getElementById('dialogLogicalName').value = selectedPrinter.logicalName; // Tampilkan nama logis di dialog
        document.getElementById('infoDialog').classList.remove('hidden'); // Tampilkan dialog informasi
        closeModal();
    }

    function updatePrinterTable() {
        // Update printer table dengan nilai baru
        const tableRows = document.querySelectorAll('#printerTableBody tr');
        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            if (cells[0].innerText === selectedPrinter.code) {
                cells[0].innerText = document.getElementById('dialogPrinterCode').value; // Update Printer Code
                cells[1].innerText = document.getElementById('dialogPrinterName').value; // Update Printer Name
                cells[2].innerText = document.getElementById('dialogPrinterType').value; // Update Printer Type
                cells[3].innerText = document.getElementById('dialogPrinterDriver').value; // Update Printer Driver
                cells[4].innerText = document.getElementById('dialogLogicalName').value; // Update Logical Name
            }
        });
        closeInfoDialog();
    }

    function closeInfoDialog() {
        document.getElementById('infoDialog').classList.add('hidden');
    }

    function showDriverModal() {
        document.getElementById('driverModal').classList.remove('hidden');
    }

    function closeDriverModal() {
        document.getElementById('driverModal').classList.add('hidden');
    }

    function selectDriver(driverName) {
        document.getElementById('dialogPrinterDriver').value = driverName; // Set driver name in the input
        closeDriverModal(); // Close the driver modal
    }
</script>
@endsection