@extends('layouts.app')

@section('title', 'Define Salesperson')

@section('header', 'Define Salesperson')

@section('content')
<div class="bg-white rounded-lg shadow-md p-6">
    <!-- Top Toolbar -->
    <div class="flex items-center space-x-2 mb-6 border-b pb-3">
        <button class="border border-gray-300 p-1 rounded hover:bg-gray-100">
            <i class="fas fa-plus text-blue-500"></i>
        </button>
        <button class="border border-gray-300 p-1 rounded hover:bg-gray-100">
            <i class="fas fa-search text-blue-500"></i>
        </button>
        <button class="border border-gray-300 p-1 rounded hover:bg-gray-100">
            <i class="fas fa-print text-blue-500"></i>
        </button>
        <button class="border border-gray-300 p-1 rounded hover:bg-gray-100">
            <i class="fas fa-save text-blue-500"></i>
        </button>
    </div>

    <!-- Main Form -->
    <div class="mb-6">
        <div class="flex items-center justify-between mb-4">
            <div class="flex items-center">
                <label for="salespersonCode" class="block text-sm font-medium text-gray-700 w-32">Salesperson Code:</label>
                <div class="flex">
                    <input type="text" id="salespersonCode" name="salespersonCode" value="5101"
                        class="border border-gray-300 px-2 py-1 w-32">
                    <button id="searchBtn" class="bg-blue-100 border border-blue-500 px-2 py-1 ml-1">
                        <i class="fas fa-ellipsis-h text-blue-500"></i>
                    </button>
                </div>
            </div>
            <div class="flex space-x-2">
                <button id="recordBtn" class="bg-gray-200 border border-gray-400 px-3 py-1 text-sm">
                    Record
                </button>
                <button id="reviewBtn" class="bg-gray-200 border border-gray-400 px-3 py-1 text-sm">
                    Review
                </button>
            </div>
        </div>

        <div class="flex items-center mb-4">
            <label for="salespersonName" class="block text-sm font-medium text-gray-700 w-32">Salesperson Name:</label>
            <input type="text" id="salespersonName" name="salespersonName" value="ARLINE"
                class="border border-gray-300 px-2 py-1 w-64">
        </div>

        <div class="flex items-center mb-4">
            <label for="userId" class="block text-sm font-medium text-gray-700 w-32">User ID:</label>
            <div class="flex">
                <input type="text" id="userId" name="userId" value="mel"
                    class="border border-gray-300 px-2 py-1 w-32">
                <button id="userSearchBtn" class="bg-blue-100 border border-blue-500 px-2 py-1 ml-1">
                    <i class="fas fa-ellipsis-h text-blue-500"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Salesperson Selection Modal -->
<div id="salespersonModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="bg-gray-200 border border-gray-400 shadow-lg w-full max-w-md">
            <div class="p-2 bg-gray-300 border-b border-gray-400">
                <h3 class="text-center font-semibold text-sm">Salesperson Table</h3>
        </div>
            <div class="p-2 bg-gray-200">
                <div class="bg-white border border-gray-400 max-h-80 overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-gray-300 sticky top-0">
                            <tr>
                                <th class="py-1 px-2 border-b border-r border-gray-400 text-left font-semibold text-sm">CODE</th>
                                <th class="py-1 px-2 border-b border-gray-400 text-left font-semibold text-sm">NAME</th>
                    </tr>
                </thead>
                        <tbody id="modalSalespersonTable">
                    <tr class="hover:bg-blue-100 cursor-pointer bg-blue-200">
                                <td class="py-1 px-2 border-r border-gray-400">5101</td>
                                <td class="py-1 px-2">ARLINE</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5102</td>
                                <td class="py-1 px-2">AGUNG</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5103</td>
                                <td class="py-1 px-2">DEDI</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5104</td>
                                <td class="py-1 px-2">ELMO</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5105</td>
                                <td class="py-1 px-2">HASAN</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5106</td>
                                <td class="py-1 px-2">HASAN</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5107</td>
                                <td class="py-1 px-2">IN HOUSE</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5108</td>
                                <td class="py-1 px-2">IN HOUSE</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5109</td>
                                <td class="py-1 px-2">IN HOUSE FREELANCE JONO</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5110</td>
                                <td class="py-1 px-2">JONO</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5111</td>
                                <td class="py-1 px-2">KHOES TJ</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5112</td>
                                <td class="py-1 px-2">KURNIAWAN</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5113</td>
                                <td class="py-1 px-2">MEDI</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5114</td>
                                <td class="py-1 px-2">MELINA</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5115</td>
                                <td class="py-1 px-2">MULTI NATIONAL COMPANY</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5116</td>
                                <td class="py-1 px-2">ROBERT</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5118</td>
                                <td class="py-1 px-2">ROBERT PURBA</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5119</td>
                                <td class="py-1 px-2">SUSAN</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">5120</td>
                                <td class="py-1 px-2">TEDDY</td>
                    </tr>
                </tbody>
            </table>
                </div>
        </div>
        <div class="p-2 flex justify-center space-x-4 mt-2">
                <button id="modalSelectBtn" class="bg-gray-300 border border-gray-400 px-4 py-1 text-sm hover:bg-gray-400">Select</button>
                <button id="modalExitBtn" class="bg-gray-300 border border-gray-400 px-4 py-1 text-sm hover:bg-gray-400">Exit</button>
            </div>
        </div>
    </div>
</div>

<!-- User Selection Modal -->
<div id="userModal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black opacity-30"></div>
    <div class="absolute inset-0 flex items-center justify-center">
        <div class="bg-gray-200 border border-gray-400 shadow-lg w-full max-w-md">
            <div class="p-2 bg-gray-300 border-b border-gray-400">
                <h3 class="text-center font-semibold text-sm">User Table</h3>
        </div>
            <div class="p-2 bg-gray-200">
                <div class="bg-white border border-gray-400 max-h-80 overflow-y-auto">
                    <table class="w-full">
                        <thead class="bg-gray-300 sticky top-0">
                            <tr>
                                <th class="py-1 px-2 border-b border-r border-gray-400 text-left font-semibold text-sm">Code</th>
                                <th class="py-1 px-2 border-b border-r border-gray-400 text-left font-semibold text-sm">Name</th>
                                <th class="py-1 px-2 border-b border-gray-400 text-left font-semibold text-sm">Signout</th>
                    </tr>
                </thead>
                        <tbody id="modalUserTable">
                    <tr class="hover:bg-blue-100 cursor-pointer bg-blue-200">
                                <td class="py-1 px-2 border-r border-gray-400">mel</td>
                                <td class="py-1 px-2 border-r border-gray-400">MARKETING</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">ais</td>
                                <td class="py-1 px-2 border-r border-gray-400">SALES</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin</td>
                                <td class="py-1 px-2 border-r border-gray-400">James</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin2</td>
                                <td class="py-1 px-2 border-r border-gray-400">Ricky</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin3</td>
                                <td class="py-1 px-2 border-r border-gray-400">Lily</td>
                                <td class="py-1 px-2">Yes</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin4</td>
                                <td class="py-1 px-2 border-r border-gray-400">Kai</td>
                                <td class="py-1 px-2">Yes</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin5</td>
                                <td class="py-1 px-2 border-r border-gray-400">Amelia</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin6</td>
                                <td class="py-1 px-2 border-r border-gray-400">Zoe</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin7</td>
                                <td class="py-1 px-2 border-r border-gray-400">Alex</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin8</td>
                                <td class="py-1 px-2 border-r border-gray-400">Gin House</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin9</td>
                                <td class="py-1 px-2 border-r border-gray-400">Tina</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin10</td>
                                <td class="py-1 px-2 border-r border-gray-400">James</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin11a</td>
                                <td class="py-1 px-2 border-r border-gray-400">Lay Shin</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin12</td>
                                <td class="py-1 px-2 border-r border-gray-400">Chun Xue</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">admin14</td>
                                <td class="py-1 px-2 border-r border-gray-400">Thanisandi</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">sample1</td>
                                <td class="py-1 px-2 border-r border-gray-400">Dodi</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                    <tr class="hover:bg-blue-100 cursor-pointer">
                                <td class="py-1 px-2 border-r border-gray-400">sample2</td>
                                <td class="py-1 px-2 border-r border-gray-400">Tina</td>
                                <td class="py-1 px-2">No</td>
                    </tr>
                </tbody>
            </table>
                </div>
        </div>
        <div class="p-2 flex justify-center space-x-4 mt-2">
                <button id="userModalSelectBtn" class="bg-gray-300 border border-gray-400 px-4 py-1 text-sm hover:bg-gray-400">Select</button>
                <button id="userModalExitBtn" class="bg-gray-300 border border-gray-400 px-4 py-1 text-sm hover:bg-gray-400">Exit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Salesperson Modal
        const salespersonModal = document.getElementById('salespersonModal');
        const searchBtn = document.getElementById('searchBtn');
        const modalSelectBtn = document.getElementById('modalSelectBtn');
        const modalExitBtn = document.getElementById('modalExitBtn');
        const salespersonTable = document.getElementById('modalSalespersonTable');
        const salespersonCode = document.getElementById('salespersonCode');
        const salespersonName = document.getElementById('salespersonName');

        // User Modal
        const userModal = document.getElementById('userModal');
        const userSearchBtn = document.getElementById('userSearchBtn');
        const userModalSelectBtn = document.getElementById('userModalSelectBtn');
        const userModalExitBtn = document.getElementById('userModalExitBtn');
        const userTable = document.getElementById('modalUserTable');
        const userId = document.getElementById('userId');

        // Open Salesperson Modal
        searchBtn.addEventListener('click', function() {
            salespersonModal.classList.remove('hidden');
        });

        // Close Salesperson Modal
        modalExitBtn.addEventListener('click', function() {
            salespersonModal.classList.add('hidden');
        });

        // Select Salesperson
        modalSelectBtn.addEventListener('click', function() {
            const selectedRow = salespersonTable.querySelector('.bg-blue-200');
            if (selectedRow) {
                const code = selectedRow.cells[0].textContent;
                const name = selectedRow.cells[1].textContent;
                salespersonCode.value = code;
                salespersonName.value = name;
                salespersonModal.classList.add('hidden');
            }
        });

        // Handle row selection in Salesperson table
        salespersonTable.addEventListener('click', function(e) {
            const row = e.target.closest('tr');
            if (row) {
                // Remove highlight from all rows
                salespersonTable.querySelectorAll('tr').forEach(r => {
                    r.classList.remove('bg-blue-200');
                });
                // Add highlight to clicked row
                row.classList.add('bg-blue-200');
            }
        });

        // Double-click to select and close modal
        salespersonTable.addEventListener('dblclick', function(e) {
            const row = e.target.closest('tr');
            if (row) {
                const code = row.cells[0].textContent;
                const name = row.cells[1].textContent;
                salespersonCode.value = code;
                salespersonName.value = name;
                salespersonModal.classList.add('hidden');
            }
        });

        // Open User Modal
        userSearchBtn.addEventListener('click', function() {
            userModal.classList.remove('hidden');
        });

        // Close User Modal
        userModalExitBtn.addEventListener('click', function() {
            userModal.classList.add('hidden');
        });

        // Select User
        userModalSelectBtn.addEventListener('click', function() {
            const selectedRow = userTable.querySelector('.bg-blue-200');
            if (selectedRow) {
                const code = selectedRow.cells[0].textContent;
                userId.value = code;
                userModal.classList.add('hidden');
            }
        });

        // Handle row selection in User table
        userTable.addEventListener('click', function(e) {
            const row = e.target.closest('tr');
            if (row) {
                // Remove highlight from all rows
                userTable.querySelectorAll('tr').forEach(r => {
                    r.classList.remove('bg-blue-200');
                });
                // Add highlight to clicked row
                row.classList.add('bg-blue-200');
            }
        });

        // Double-click to select and close modal
        userTable.addEventListener('dblclick', function(e) {
            const row = e.target.closest('tr');
            if (row) {
                const code = row.cells[0].textContent;
                userId.value = code;
                userModal.classList.add('hidden');
            }
        });

        // Close modals when clicking outside
        window.addEventListener('click', function(e) {
            if (e.target === salespersonModal) {
                salespersonModal.classList.add('hidden');
            }
            if (e.target === userModal) {
                userModal.classList.add('hidden');
            }
        });
    });
</script>
@endsection
