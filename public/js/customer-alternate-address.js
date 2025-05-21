document.addEventListener('DOMContentLoaded', function() {
    const customerAccountModal = document.getElementById('customerAccountModal');
    const openModalBtn = document.getElementById('openCustomerAccountModalBtn');
    const closeModalBtn = document.getElementById('closeCustomerAccountModalBtn');
    const exitModalBtn = document.getElementById('exitCustomerModalBtn');
    const selectCustomerBtn = document.getElementById('selectCustomerBtn');
    const customerCodeInput = document.getElementById('customer_code');

    // Get references to the new form fields
    const deliveryCodeInput = document.getElementById('delivery_code');
    const countryInput = document.getElementById('country');
    const stateInput = document.getElementById('state');
    const townInput = document.getElementById('town');
    const townSectionInput = document.getElementById('town_section');
    const billToNameInput = document.getElementById('bill_to_name');
    const billToAddressInput = document.getElementById('bill_to_address');
    const shipToNameInput = document.getElementById('ship_to_name');
    const shipToAddressInput = document.getElementById('ship_to_address');
    const contactPersonInput = document.getElementById('contact_person');
    const telNoInput = document.getElementById('tel_no');
    const faxNoInput = document.getElementById('fax_no');
    const emailInput = document.getElementById('email');

    let selectedCustomerAccount = null;

    // Fungsi untuk memuat data customer account ke dalam modal
    function loadCustomerAccounts(searchTerm = '') {
        const url = searchTerm 
            ? `/customer-alternate-address/api/customer-accounts/search?search=${encodeURIComponent(searchTerm)}`
            : '/customer-alternate-address/api/customer-accounts';

        fetch(url)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                const tbody = document.getElementById('customerAccountTableBody');
                if (!tbody) {
                    console.error('Tabel body modal tidak ditemukan');
                    return;
                }
                
                tbody.innerHTML = '';
                selectedCustomerAccount = null;
                selectCustomerBtn.disabled = true;

                if (data.length === 0) {
                    const row = document.createElement('tr');
                    row.innerHTML = '<td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada data customer account</td>';
                    tbody.appendChild(row);
                    return;
                }
                
                data.forEach(account => {
                    const row = document.createElement('tr');
                    row.className = 'hover:bg-gray-50 cursor-pointer'; // Tambahkan cursor-pointer
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${account.customer_name || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${account.customer_code || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${account.salesperson || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${account.ac_type || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${account.currency || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${account.status || '-'}</td>
                    `;
                    row.addEventListener('click', () => {
                        // Hapus highlight dari semua baris
                        tbody.querySelectorAll('tr').forEach(r => r.classList.remove('bg-blue-200'));
                        // Tambahkan highlight ke baris yang dipilih
                        row.classList.add('bg-blue-200');
                        selectedCustomerAccount = account;
                        selectCustomerBtn.disabled = false;
                    });
                    tbody.appendChild(row);
                });
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan saat memuat data customer account');
                const tbody = document.getElementById('customerAccountTableBody');
                 if (tbody) {
                    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">Gagal memuat data.</td></tr>';
                 }
            });
    }

    // Event listener untuk membuka modal
    if (openModalBtn) {
        openModalBtn.addEventListener('click', function() {
            customerAccountModal.classList.remove('hidden');
            loadCustomerAccounts(); // Muat data saat modal dibuka
        });
    }

    // Event listener untuk menutup modal
    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function() {
            customerAccountModal.classList.add('hidden');
        });
    }
    
    if (exitModalBtn) {
        exitModalBtn.addEventListener('click', function() {
            customerAccountModal.classList.add('hidden');
        });
    }

    // Event listener untuk tombol Select di modal
    if (selectCustomerBtn) {
        selectCustomerBtn.addEventListener('click', function() {
            if (selectedCustomerAccount) {
                // Fill the form fields with selected customer data
                customerCodeInput.value = selectedCustomerAccount.customer_code || '';
                // Assuming these fields exist in your selectedCustomerAccount object
                deliveryCodeInput.value = selectedCustomerAccount.delivery_code || '';
                countryInput.value = selectedCustomerAccount.country || '';
                stateInput.value = selectedCustomerAccount.state || '';
                townInput.value = selectedCustomerAccount.town || '';
                townSectionInput.value = selectedCustomerAccount.town_section || '';
                billToNameInput.value = selectedCustomerAccount.bill_to_name || selectedCustomerAccount.customer_name || ''; // Use customer_name if bill_to_name is null
                billToAddressInput.value = selectedCustomerAccount.bill_to_address || selectedCustomerAccount.address || ''; // Use customer address if bill_to_address is null
                shipToNameInput.value = selectedCustomerAccount.ship_to_name || selectedCustomerAccount.customer_name || ''; // Use customer_name if ship_to_name is null
                shipToAddressInput.value = selectedCustomerAccount.ship_to_address || selectedCustomerAccount.address || ''; // Use customer address if ship_to_address is null
                contactPersonInput.value = selectedCustomerAccount.contact_person || '';
                telNoInput.value = selectedCustomerAccount.tel_no || '';
                faxNoInput.value = selectedCustomerAccount.fax_no || '';
                emailInput.value = selectedCustomerAccount.co_email || ''; // Assuming email is stored in co_email

                customerAccountModal.classList.add('hidden');
            }
        });
    }

    // Tutup modal jika klik di luar area modal
    window.addEventListener('click', function(event) {
        if (event.target === customerAccountModal) {
            customerAccountModal.classList.add('hidden');
        }
    });

    // Event listener untuk tombol search di luar modal (opsional, bisa dihapus jika search hanya di dalam modal)
    const searchButton = document.querySelector('button[title="Search"]');
    if (searchButton) {
        searchButton.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah submit form jika di dalam form
            const searchTerm = prompt('Masukkan kode atau nama customer:');
            if (searchTerm) {
                 // Jika search di luar modal memicu modal:
                 customerAccountModal.classList.remove('hidden');
                 loadCustomerAccounts(searchTerm); // Muat data dengan filter pencarian

                 // Jika search di luar modal hanya untuk filtering tabel di view (tidak relevan lagi karena tabel di view dihapus)
                 // loadCustomerAccounts(searchTerm);
            }
        });
    }
    
    // Event listener untuk tombol refresh di luar modal (opsional, bisa dihapus jika refresh hanya di dalam modal)
    const refreshButton = document.querySelector('button[title="Refresh"]');
     if (refreshButton) {
        refreshButton.addEventListener('click', function(e) {
            e.preventDefault(); // Mencegah submit form jika di dalam form
            // Jika refresh di luar modal memicu modal:
            customerAccountModal.classList.remove('hidden');
            loadCustomerAccounts(); // Muat data tanpa filter

            // Jika refresh di luar modal hanya untuk merefresh tabel di view (tidak relevan lagi karena tabel di view dihapus)
            // loadCustomerAccounts();
        });
    }

    // Inisialisasi (tidak perlu memuat data saat DOMContentLoaded jika tabel hanya muncul di modal)
    // loadCustomerAccounts(); 
}); 