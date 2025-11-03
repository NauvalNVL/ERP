<template>
    <div class="amend-so-container">
        <div class="header-section">
            <h2 class="page-title">Amend Sales Order</h2>
            <p class="page-description">Modify existing sales orders</p>
        </div>

        <div class="search-section">
            <div class="search-form">
                <div class="form-group">
                    <label for="soNumber">SO Number:</label>
                    <input 
                        type="text" 
                        id="soNumber" 
                        v-model="searchForm.soNumber" 
                        placeholder="Enter SO Number"
                        class="form-control"
                    />
                </div>
                <div class="form-group">
                    <label for="customerCode">Customer Code:</label>
                    <input 
                        type="text" 
                        id="customerCode" 
                        v-model="searchForm.customerCode" 
                        placeholder="Enter Customer Code"
                        class="form-control"
                    />
                </div>
                <div class="form-actions">
                    <button @click="searchSalesOrders" class="btn btn-primary">Search</button>
                    <button @click="clearSearch" class="btn btn-secondary">Clear</button>
                </div>
            </div>
        </div>

        <div class="results-section" v-if="salesOrders.length > 0">
            <h3>Sales Orders Found</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>SO Number</th>
                            <th>Customer Code</th>
                            <th>Customer Name</th>
                            <th>SO Date</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="so in salesOrders" :key="so.soNumber">
                            <td>{{ so.soNumber }}</td>
                            <td>{{ so.customerCode }}</td>
                            <td>{{ so.customerName }}</td>
                            <td>{{ so.soDate }}</td>
                            <td>{{ so.status }}</td>
                            <td>
                                <button 
                                    @click="amendSalesOrder(so)" 
                                    class="btn btn-warning btn-sm"
                                    :disabled="so.status === 'Cancelled'"
                                >
                                    Amend
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="amendment-form" v-if="selectedSO">
            <h3>Amend Sales Order: {{ selectedSO.soNumber }}</h3>
            <div class="form-container">
                <div class="form-row">
                    <div class="form-group">
                        <label for="customerName">Customer Name:</label>
                        <input 
                            type="text" 
                            id="customerName" 
                            v-model="amendForm.customerName" 
                            class="form-control"
                        />
                    </div>
                    <div class="form-group">
                        <label for="soDate">SO Date:</label>
                        <input 
                            type="date" 
                            id="soDate" 
                            v-model="amendForm.soDate" 
                            class="form-control"
                        />
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="deliveryDate">Delivery Date:</label>
                        <input 
                            type="date" 
                            id="deliveryDate" 
                            v-model="amendForm.deliveryDate" 
                            class="form-control"
                        />
                    </div>
                    <div class="form-group">
                        <label for="priority">Priority:</label>
                        <select v-model="amendForm.priority" class="form-control">
                            <option value="Normal">Normal</option>
                            <option value="High">High</option>
                            <option value="Urgent">Urgent</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label for="remarks">Remarks:</label>
                    <textarea 
                        id="remarks" 
                        v-model="amendForm.remarks" 
                        class="form-control"
                        rows="3"
                    ></textarea>
                </div>

                <div class="form-actions">
                    <button @click="saveAmendment" class="btn btn-success">Save Amendment</button>
                    <button @click="cancelAmendment" class="btn btn-secondary">Cancel</button>
                </div>
            </div>
        </div>

        <div class="no-results" v-else-if="searchPerformed">
            <p>No sales orders found matching your criteria.</p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'AmendSO',
    data() {
        return {
            searchForm: {
                soNumber: '',
                customerCode: ''
            },
            salesOrders: [],
            searchPerformed: false,
            selectedSO: null,
            amendForm: {
                customerName: '',
                soDate: '',
                deliveryDate: '',
                priority: 'Normal',
                remarks: ''
            }
        }
    },
    methods: {
        async searchSalesOrders() {
            try {
                // Simulate API call - replace with actual API endpoint
                const response = await fetch('/api/sales-orders', {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });
                
                if (response.ok) {
                    const data = await response.json();
                    this.salesOrders = data.data || [];
                } else {
                    this.salesOrders = [];
                }
                this.searchPerformed = true;
            } catch (error) {
                console.error('Error searching sales orders:', error);
                this.salesOrders = [];
                this.searchPerformed = true;
            }
        },

        amendSalesOrder(so) {
            this.selectedSO = so;
            // Populate form with current SO data
            this.amendForm = {
                customerName: so.customerName || '',
                soDate: so.soDate || '',
                deliveryDate: so.deliveryDate || '',
                priority: so.priority || 'Normal',
                remarks: so.remarks || ''
            };
        },

        async saveAmendment() {
            try {
                // Simulate API call for amendment
                console.log('Amending SO:', this.selectedSO.soNumber, this.amendForm);
                
                // Update the SO in the list
                const index = this.salesOrders.findIndex(so => so.soNumber === this.selectedSO.soNumber);
                if (index !== -1) {
                    this.salesOrders[index] = {
                        ...this.salesOrders[index],
                        ...this.amendForm
                    };
                }
                
                alert(`Sales Order ${this.selectedSO.soNumber} has been amended successfully.`);
                this.cancelAmendment();
            } catch (error) {
                console.error('Error amending sales order:', error);
                alert('Error amending sales order. Please try again.');
            }
        },

        cancelAmendment() {
            this.selectedSO = null;
            this.amendForm = {
                customerName: '',
                soDate: '',
                deliveryDate: '',
                priority: 'Normal',
                remarks: ''
            };
        },

        clearSearch() {
            this.searchForm.soNumber = '';
            this.searchForm.customerCode = '';
            this.salesOrders = [];
            this.searchPerformed = false;
            this.selectedSO = null;
        }
    }
}
</script>

<style scoped>
.amend-so-container {
    padding: 20px;
    max-width: 1200px;
    margin: 0 auto;
}

.header-section {
    margin-bottom: 30px;
}

.page-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #333;
}

.page-description {
    color: #666;
    margin-bottom: 0;
}

.search-section {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    margin-bottom: 30px;
}

.search-form {
    display: flex;
    gap: 20px;
    align-items: end;
    flex-wrap: wrap;
}

.form-group {
    display: flex;
    flex-direction: column;
    min-width: 200px;
}

.form-group label {
    font-weight: 500;
    margin-bottom: 5px;
    color: #333;
}

.form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
}

.form-actions {
    display: flex;
    gap: 10px;
}

.btn {
    padding: 8px 16px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
}

.btn-primary {
    background-color: #007bff;
    color: white;
}

.btn-secondary {
    background-color: #6c757d;
    color: white;
}

.btn-warning {
    background-color: #ffc107;
    color: #212529;
}

.btn-success {
    background-color: #28a745;
    color: white;
}

.btn-sm {
    padding: 4px 8px;
    font-size: 12px;
}

.btn:hover {
    opacity: 0.9;
}

.btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.results-section h3 {
    margin-bottom: 15px;
    color: #333;
}

.table-container {
    overflow-x: auto;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.data-table th,
.data-table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #eee;
}

.data-table th {
    background-color: #f8f9fa;
    font-weight: 600;
    color: #333;
}

.data-table tbody tr:hover {
    background-color: #f8f9fa;
}

.amendment-form {
    background: white;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    margin-top: 30px;
}

.amendment-form h3 {
    margin-bottom: 20px;
    color: #333;
}

.form-container {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-row {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}

.form-row .form-group {
    flex: 1;
    min-width: 250px;
}

.no-results {
    text-align: center;
    padding: 40px;
    color: #666;
    background: #f8f9fa;
    border-radius: 8px;
}
</style>
