// Foreign Currency Management Functions

// Variables for modal management
let selectedRow = null;

// Function to fetch currencies from server
async function fetchCurrencies() {
    try {
        const response = await fetch('/system-manager/system-maintenance/foreign-currency', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Fetched currencies:', result);
        
        if (result.success) {
            return result.data;
        } else {
            throw new Error('Failed to fetch currencies');
        }
    } catch (error) {
        console.error('Error fetching currencies:', error);
        return [];
    }
}

// Function to populate currency table
async function populateCurrencyTable() {
    console.log('Populating currency table');
    
    const tbody = document.querySelector('#currencyModalTable tbody');
    if (!tbody) {
        console.error('Table body not found');
        return;
    }
    
    // Show loading state
    tbody.innerHTML = '<tr><td colspan="3" class="px-4 py-4 text-center text-gray-500">Loading currencies...</td></tr>';
    
    try {
        // Fetch currencies from server
        const currencies = await fetchCurrencies();
        console.log('Received currencies:', currencies);
        
        // Clear loading state
        tbody.innerHTML = '';
        
        if (!currencies || currencies.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-4 py-4 text-center">
                        <div class="text-gray-500">No currencies found.</div>
                        <div class="text-sm text-gray-400 mt-1">The seeder might need to be run manually using:</div>
                        <div class="text-sm font-mono bg-gray-100 p-2 mt-1 rounded">php artisan db:seed --class=ForeignCurrencySeeder</div>
                    </td>
                </tr>`;
            return;
        }
        
        // Add rows from fetched data
        currencies.forEach(currency => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-blue-50 cursor-pointer modal-row';
            row.setAttribute('data-currency-id', currency.id);
            row.setAttribute('data-currency-code', currency.currency_code);
            row.setAttribute('data-currency-name', currency.currency_name);
            row.setAttribute('data-country', currency.country);
            row.setAttribute('data-exchange-rate', currency.exchange_rate);
            row.setAttribute('data-exchange-method', currency.exchange_method);
            row.setAttribute('data-variance-control', currency.variance_control);
            row.setAttribute('data-max-tax-adj', currency.max_tax_adj);
            
            // Set click event to select row
            row.onclick = function(e) {
                selectRow(this);
                e.stopPropagation();
            };
            
            // Set double-click event to select and edit
            row.ondblclick = function() {
                selectRow(this);
                editSelectedCurrency();
            };
            
            // Create cells
            const codeCell = document.createElement('td');
            codeCell.className = 'px-4 py-3 whitespace-nowrap font-medium text-gray-900';
            codeCell.textContent = currency.currency_code;
            
            const nameCell = document.createElement('td');
            nameCell.className = 'px-4 py-3 whitespace-nowrap text-gray-700';
            nameCell.textContent = currency.currency_name;
            
            const countryCell = document.createElement('td');
            countryCell.className = 'px-4 py-3 whitespace-nowrap text-gray-500';
            countryCell.textContent = currency.country;
            
            // Add cells to row
            row.appendChild(codeCell);
            row.appendChild(nameCell);
            row.appendChild(countryCell);
            
            // Add row to table
            tbody.appendChild(row);
        });
        
        console.log('Currency table populated with data from seeder');
    } catch (error) {
        console.error('Error populating currency table:', error);
        tbody.innerHTML = `
            <tr>
                <td colspan="3" class="px-4 py-4 text-center">
                    <div class="text-red-500">Error loading currencies.</div>
                    <div class="text-sm text-gray-500 mt-1">${error.message}</div>
                    <button onclick="populateCurrencyTable()" class="mt-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                        Try Again
                    </button>
                </td>
            </tr>`;
    }
}

// Function to open currency modal
async function openCurrencyModal() {
    const modal = document.getElementById('currencyModal');
    modal.classList.remove('hidden');
    setTimeout(() => { 
        modal.querySelector('.scale-95').classList.remove('scale-95'); 
    }, 10); // Animation
    document.getElementById('currencySearchInput').focus();
    clearSelection();
    
    // Populate table with data from seeder
    await populateCurrencyTable();
}

// Function to close currency modal
function closeCurrencyModal() {
    const modal = document.getElementById('currencyModal');
    modal.querySelector('.scale-95').classList.add('scale-95');
    setTimeout(() => { 
        modal.classList.add('hidden'); 
    }, 150); // Animation
}

// Function to select currency and navigate to edit page
function selectCurrencyAndEdit(url) {
    if (url) {
        window.location.href = url;
    }
}

// Function to clear row selection
function clearSelection() {
    const rows = document.querySelectorAll('#currencyModalTable tbody tr.modal-row');
    rows.forEach(r => r.classList.remove('bg-blue-200'));
    selectedRow = null;
}

// Function to select a row
function selectRow(row) {
    const rows = document.querySelectorAll('#currencyModalTable tbody tr.modal-row');
    rows.forEach(r => r.classList.remove('bg-blue-200')); // Deselect others
    row.classList.add('bg-blue-200'); // Select this
    selectedRow = row;
}

// Function to edit selected currency
function editSelectedCurrency() {
    if (selectedRow) {
        const editUrl = `${window.location.origin}/system-manager/system-maintenance/foreign-currency/${selectedRow.dataset.currencyId}/edit`;
        selectCurrencyAndEdit(editUrl);
    }
}

// Initialize event handlers when document loads
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing foreign currency functionality');
    
    const modal = document.getElementById('currencyModal');
    const searchInput = document.getElementById('currencySearchInput');

    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            const rows = document.querySelectorAll('#currencyModalTable tbody tr.modal-row');
            
            rows.forEach(row => {
                const code = row.getAttribute('data-currency-code').toLowerCase();
                const name = row.getAttribute('data-currency-name').toLowerCase();
                const country = row.getAttribute('data-country').toLowerCase();
                
                if (code.includes(searchTerm) || 
                    name.includes(searchTerm) || 
                    country.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    }

    // Close modal on escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape" && modal && !modal.classList.contains('hidden')) {
            closeCurrencyModal();
        }
    });

    // Close modal when clicking outside
    if (modal) {
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeCurrencyModal();
            }
        });
    }
}); 