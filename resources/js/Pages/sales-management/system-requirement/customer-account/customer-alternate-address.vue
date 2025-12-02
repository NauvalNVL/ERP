<template>
    <AppLayout :header="'Define Customer Alternate Address'">
        <!-- Header -->
        <div
            class="bg-gradient-to-r from-blue-600 via-cyan-600 to-teal-500 p-6 rounded-t-lg shadow-lg overflow-hidden relative mb-6"
        >
            <div
                class="absolute top-0 right-0 w-40 h-40 bg-white opacity-5 rounded-full -translate-y-20 translate-x-20 animate-pulse-slow"
            ></div>
            <div
                class="absolute bottom-0 left-0 w-20 h-20 bg-white opacity-5 rounded-full translate-y-10 -translate-x-10 animate-pulse-slow animation-delay-500"
            ></div>
            <div class="flex items-center">
                <div
                    class="bg-gradient-to-br from-cyan-500 to-teal-600 p-3 rounded-lg shadow-inner flex items-center justify-center mr-4"
                >
                    <i class="fas fa-address-book text-white text-2xl z-10"></i>
                </div>
                <div>
                    <h2
                        class="text-2xl md:text-3xl font-bold text-white mb-1 text-shadow"
                    >
                        Define Customer Alternate Address
                    </h2>
                    <p class="text-cyan-100">
                        Easily manage customer alternate addresses
                    </p>
                </div>
            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left: Main Container -->
            <div class="lg:col-span-2">
                <div
                    class="relative bg-white rounded-2xl shadow-xl border border-gray-200 p-8 animate-fade-in-up overflow-hidden"
                >
                    <div class="flex items-center mb-6">
                        <div
                            class="bg-gradient-to-br from-cyan-500 to-blue-600 p-3 rounded-lg mr-3"
                        >
                            <i
                                class="fas fa-map-marker-alt text-white text-2xl"
                            ></i>
                        </div>
                        <h2 class="text-xl font-bold text-gray-800">
                            Customer Alternate Address Management
                        </h2>
                    </div>
                    <form @submit.prevent="handleSave">
                        <div class="flex items-start justify-between mb-6">
                            <!-- Customer Code and Delivery Code Section -->
                            <div class="flex flex-col space-y-4 flex-grow">
                                <!-- Customer Code -->
                                <div>
                                    <label
                                        class="flex items-center text-gray-700 font-semibold mb-2"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-gradient-to-br from-cyan-400 to-blue-500 text-white rounded-lg mr-2"
                                        >
                                            <i class="fas fa-id-card"></i>
                                        </span>
                                        Customer Code
                                    </label>
                                    <div class="flex">
                                        <input
                                            type="text"
                                            class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-2 focus:ring-cyan-400"
                                            v-model="customerCode"
                                        />
                                        <button
                                            type="button"
                                            class="bg-gradient-to-br from-cyan-400 to-blue-500 text-white px-3 rounded-r-lg hover:from-cyan-500 hover:to-blue-600 transition"
                                            @click="openCustomerLookup"
                                        >
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>

                                <!-- Delivery Code -->
                                <div>
                                    <label
                                        class="flex items-center text-gray-700 font-semibold mb-2"
                                    >
                                        <span
                                            class="inline-flex items-center justify-center w-7 h-7 bg-gradient-to-br from-pink-400 to-purple-500 text-white rounded-lg mr-2"
                                        >
                                            <i class="fas fa-truck"></i>
                                        </span>
                                        Delivery Code
                                    </label>
                                    <div class="flex">
                                        <input
                                            type="text"
                                            class="flex-1 border border-gray-300 rounded-l-lg px-3 py-2 focus:ring-2 focus:ring-pink-400"
                                            v-model="deliveryCode"
                                        />
                                        <button
                                            type="button"
                                            class="bg-gradient-to-br from-pink-400 to-purple-500 text-white px-3 hover:from-pink-500 hover:to-purple-600 transition rounded-none"
                                            @click="openDeliveryLookup"
                                        >
                                            <i class="fas fa-table"></i>
                                        </button>
                                        <button
                                            type="button"
                                            class="bg-gradient-to-br from-pink-400 to-purple-500 text-white px-3 hover:from-pink-500 hover:to-purple-600 transition rounded-r-lg"
                                            @click="handlePreviewDeliveryCode"
                                        >
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- Record: Add/Select Button -->
                        </div>

                        <div v-if="showDetailsSection">
                            <div
                                class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 mb-6"
                            >
                                <!-- Country -->
                                <div>
                                    <label
                                        for="country"
                                        class="block text-gray-700 font-semibold mb-2"
                                        >Country</label
                                    >
                                    <input
                                        type="text"
                                        id="country"
                                        v-model="country"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                    />
                                </div>

                                <!-- State -->
                                <div>
                                    <label
                                        for="state"
                                        class="block text-gray-700 font-semibold mb-2"
                                        >State</label
                                    >
                                    <input
                                        type="text"
                                        id="state"
                                        v-model="state"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                    />
                                </div>

                                <!-- Town -->
                                <div>
                                    <label
                                        for="town"
                                        class="block text-gray-700 font-semibold mb-2"
                                        >Town</label
                                    >
                                    <input
                                        type="text"
                                        id="town"
                                        v-model="town"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                    />
                                </div>

                                <!-- Town Section -->
                                <div>
                                    <label
                                        for="townSection"
                                        class="block text-gray-700 font-semibold mb-2"
                                        >Town Section</label
                                    >
                                    <input
                                        type="text"
                                        id="townSection"
                                        v-model="townSection"
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                    />
                                </div>
                            </div>

                            <!-- Bill To Section -->
                            <div
                                class="bg-white p-6 rounded-lg shadow-lg mb-6 border border-blue-200"
                            >
                                <h3
                                    class="text-lg font-bold text-blue-800 mb-4 flex items-center"
                                >
                                    <i class="fas fa-receipt mr-2"></i>Bill To
                                </h3>
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"
                                >
                                    <div>
                                        <label
                                            for="billToName"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Name</label
                                        >
                                        <input
                                            type="text"
                                            id="billToName"
                                            v-model="billTo.name"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                        />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            for="billToAddress"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Address</label
                                        >
                                        <textarea
                                            id="billToAddress"
                                            v-model="billTo.address"
                                            rows="3"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-300"
                                        ></textarea>
                                    </div>
                                </div>
                            </div>

                            <!-- Ship To Section -->
                            <div
                                class="bg-white p-6 rounded-lg shadow-lg mb-6 border border-purple-200"
                            >
                                <h3
                                    class="text-lg font-bold text-purple-800 mb-4 flex items-center"
                                >
                                    <i class="fas fa-shipping-fast mr-2"></i
                                    >Ship To
                                </h3>
                                <div
                                    class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4"
                                >
                                    <div>
                                        <label
                                            for="shipToName"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Name</label
                                        >
                                        <input
                                            type="text"
                                            id="shipToName"
                                            v-model="shipTo.name"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-300"
                                        />
                                    </div>
                                    <div class="md:col-span-2">
                                        <label
                                            for="shipToAddress"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Address</label
                                        >
                                        <textarea
                                            id="shipToAddress"
                                            v-model="shipTo.address"
                                            rows="3"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-300"
                                        ></textarea>
                                    </div>
                                    <div>
                                        <label
                                            for="contactPerson"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Contact Person</label
                                        >
                                        <input
                                            type="text"
                                            id="contactPerson"
                                            v-model="shipTo.contactPerson"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-300"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="telNo"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Tel No</label
                                        >
                                        <input
                                            type="text"
                                            id="telNo"
                                            v-model="shipTo.telNo"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-300"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="faxNo"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Fax No</label
                                        >
                                        <input
                                            type="text"
                                            id="faxNo"
                                            v-model="shipTo.faxNo"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-300"
                                        />
                                    </div>
                                    <div>
                                        <label
                                            for="email"
                                            class="block text-gray-700 font-semibold mb-2"
                                            >Email</label
                                        >
                                        <input
                                            type="email"
                                            id="email"
                                            v-model="shipTo.email"
                                            class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-purple-300"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Action Buttons -->
                    <div class="mt-8 flex justify-end space-x-4">
                        <button
                            @click="
                                handleSave();
                                console.log('Final Save Check:', {
                                    customerCode: customerCode.value,
                                    deliveryCode: deliveryCode.value,
                                });
                            "
                            class="action-btn bg-gradient-to-br from-green-500 to-green-700 hover:from-green-600 hover:to-green-800"
                        >
                            <i class="fas fa-save mr-2"></i>Save
                        </button>
                        <button
                            @click="handleClear"
                            class="action-btn bg-gradient-to-br from-red-500 to-red-700 hover:from-red-600 hover:to-red-800"
                        >
                            <i class="fas fa-eraser mr-2"></i>Clear
                        </button>
                    </div>
                </div>
            </div>
            <!-- Right: Info & Quick Links -->
            <div class="flex flex-col space-y-6">
                <!-- Information Card -->
                <div
                    class="bg-white rounded-xl shadow-md border-t-4 border-blue-400 p-6"
                >
                    <div class="flex items-center mb-2">
                        <div
                            class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-green-400 to-teal-400 rounded-lg mr-3"
                        >
                            <i class="fas fa-info text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">
                            Information
                        </h3>
                    </div>
                    <hr class="my-2 border-blue-100" />
                    <div class="text-gray-700 mb-4">
                        Use this form to update customer alternate address data.
                        Make sure all information entered is accurate and
                        complete.
                    </div>
                    <div class="bg-blue-50 rounded-lg p-4">
                        <div class="font-bold text-blue-700 mb-2">
                            Instructions:
                        </div>
                        <ul
                            class="list-disc pl-5 text-blue-700 space-y-1 text-sm"
                        >
                            <li>Enter the customer code to search for data</li>
                            <li>
                                Click the table button to view the customer list
                            </li>
                            <li>Fill in all required fields</li>
                            <li>Click Save to store the changes</li>
                        </ul>
                    </div>
                </div>
                <!-- Quick Links Card -->
                <div
                    class="bg-white rounded-xl shadow-md border-t-4 border-purple-400 p-6"
                >
                    <div class="flex items-center mb-2">
                        <div
                            class="inline-flex items-center justify-center w-10 h-10 bg-gradient-to-br from-purple-400 to-pink-400 rounded-lg mr-3"
                        >
                            <i class="fas fa-link text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800">
                            Quick Links
                        </h3>
                    </div>
                    <hr class="my-2 border-purple-100" />
                    <div class="space-y-3 mt-4">
                        <a
                            href="#"
                            class="flex items-center p-3 rounded-lg bg-green-50 hover:bg-green-100 transition"
                        >
                            <span
                                class="inline-flex items-center justify-center w-9 h-9 bg-green-400 rounded-lg mr-3"
                            >
                                <i class="fas fa-print text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="font-bold text-green-800">
                                    View & Print
                                </div>
                                <div class="text-xs text-green-700">
                                    Print customer list
                                </div>
                            </div>
                        </a>
                        <a
                            href="#"
                            class="flex items-center p-3 rounded-lg bg-blue-50 hover:bg-blue-100 transition"
                        >
                            <span
                                class="inline-flex items-center justify-center w-9 h-9 bg-blue-400 rounded-lg mr-3"
                            >
                                <i class="fas fa-users text-white text-xl"></i>
                            </span>
                            <div>
                                <div class="font-bold text-blue-800">
                                    Customer Groups
                                </div>
                                <div class="text-xs text-blue-700">
                                    Manage customer groups
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Account Modal -->
        <CustomerAccountModal
            :show="showCustomerAccountModal"
            :customerAccounts="customers"
            @close="showCustomerAccountModal = false"
            @select="selectCustomerAccount"
        />

        <!-- Geo Location Modal -->
        <GeoModal
            :show="showGeoModal"
            :geos="geoData"
            @close="showGeoModal = false"
            @select="selectGeoLocation"
        />

        <!-- Delivery Code Preview Modal -->
        <DeliveryCodePreviewModal
            :show="showDeliveryCodePreviewModal"
            :deliveryCode="deliveryCode"
            :country="country"
            :state="state"
            :town="town"
            :townSection="townSection"
            :billTo="billTo"
            :shipTo="shipTo"
            @close="showDeliveryCodePreviewModal = false"
        />
    </AppLayout>
</template>

<script setup>
import { ref, reactive, computed, onMounted } from "vue";
import AppLayout from "@/Layouts/AppLayout.vue";
import CustomerAccountModal from "@/Components/customer-account-modal.vue";
import GeoModal from "@/Components/geo-modal.vue";
import DeliveryCodePreviewModal from "@/Components/DeliveryCodePreviewModal.vue";
import { useToast } from "@/Composables/useToast.js";
import axios from "axios";

const customerCode = ref("");
const deliveryCode = ref("");
const country = ref("");
const state = ref("");
const town = ref("");
const townSection = ref("");
const altAddressId = ref(null); // To store the ID of the alternate address if editing

const billTo = reactive({
    name: "",
    address: "",
});

const shipTo = reactive({
    name: "",
    address: "",
    contactPerson: "",
    telNo: "",
    faxNo: "",
    email: "",
});

const showDetailsSection = computed(
    () =>
        customerCode.value !== "" ||
        deliveryCode.value !== "" ||
        altAddressId.value !== null
);

const showCustomerAccountModal = ref(false);
const showGeoModal = ref(false);
const showDeliveryCodePreviewModal = ref(false);

const { addToast } = useToast();

const customers = ref([]);
const geoData = ref([]);

onMounted(() => {
    fetchCustomerAccounts();
    fetchGeoData();
});

async function fetchCustomerAccounts() {
    try {
        const response = await axios.get("/api/customer-accounts");
        if (Array.isArray(response.data)) {
            customers.value = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            customers.value = response.data.data;
        } else {
            console.error(
                "Unexpected data format for customer accounts:",
                response.data
            );
            addToast(
                "Failed to fetch customer accounts due to unexpected data format.",
                "error"
            );
            customers.value = [];
        }
    } catch (error) {
        addToast(
            "Failed to fetch customer accounts: " + error.message,
            "error"
        );
    }
}

async function fetchGeoData() {
    try {
        const response = await axios.get("/api/geo");
        if (Array.isArray(response.data)) {
            geoData.value = response.data;
        } else if (response.data && Array.isArray(response.data.data)) {
            geoData.value = response.data.data;
        } else {
            console.error(
                "Unexpected data format for geo data:",
                response.data
            );
            addToast(
                "Failed to fetch geo data due to unexpected data format.",
                "error"
            );
            geoData.value = [];
        }
    } catch (error) {
        addToast("Failed to fetch geo data: " + error.message, "error");
    }
}

async function fetchAlternateAddress(code) {
    try {
        console.log(`Fetching alternate address for customer code: ${code}`);
        const response = await axios.get(
            `/api/customer-alternate-addresses/${code}`
        );
        console.log("Response from fetchAlternateAddress:", response.data);

        // Ensure response.data is an array and check if it contains any elements
        if (Array.isArray(response.data) && response.data.length > 0) {
            const data = response.data[0]; // Take the first alternate address if multiple exist
            altAddressId.value = data.id;
            // customerCode.value = data.customer_code; // customerCode is already set by selectCustomerAccount
            deliveryCode.value = data.delivery_code;
            country.value = data.country;
            state.value = data.state;
            town.value = data.town;
            townSection.value = data.town_section;
            billTo.name = data.bill_to_name;
            billTo.address = data.bill_to_address;
            shipTo.name = data.ship_to_name;
            shipTo.address = data.ship_to_address;
            shipTo.contactPerson = data.contact_person;
            shipTo.telNo = data.tel_no;
            shipTo.faxNo = data.fax_no; // Ensure faxNo is correctly assigned
            shipTo.email = data.email; // Ensure email is correctly assigned
            addToast("Alternate address loaded successfully!", "success");
        } else {
            addToast(
                "Alternate address not found for this customer. Please fill in the details to create a new one.",
                "info"
            );
            console.log(
                "No alternate address found, clearing only related fields."
            );
            altAddressId.value = null;
            deliveryCode.value = "";
            country.value = "";
            state.value = "";
            town.value = "";
            townSection.value = "";
            billTo.address = ""; // Only clear address, name is from customerCode
            shipTo.name = "";
            shipTo.address = "";
            shipTo.contactPerson = "";
            shipTo.telNo = "";
            shipTo.faxNo = "";
            shipTo.email = "";
        }
    } catch (error) {
        addToast(
            "Failed to fetch alternate address: " +
                (error.response?.data?.message || error.message),
            "error"
        );
        console.log(
            "Error fetching alternate address, clearing only related fields:",
            error
        );
        altAddressId.value = null;
        deliveryCode.value = "";
        country.value = "";
        state.value = "";
        town.value = "";
        townSection.value = "";
        billTo.address = "";
        shipTo.name = "";
        shipTo.address = "";
        shipTo.contactPerson = "";
        shipTo.telNo = "";
        shipTo.faxNo = "";
        shipTo.email = "";
    }
}

function openCustomerLookup() {
    showCustomerAccountModal.value = true;
}

function selectCustomerAccount(selected) {
    customerCode.value = selected.customer_code;
    billTo.name = selected.customer_name;
    showCustomerAccountModal.value = false;
    console.log("Selected Customer Code:", customerCode.value);
    fetchAlternateAddress(selected.customer_code); // Attempt to load existing data
}

function openDeliveryLookup() {
    showGeoModal.value = true;
}

function selectGeoLocation(selected) {
    deliveryCode.value = selected.code;
    country.value = selected.country;
    state.value = selected.state;
    town.value = selected.town;
    townSection.value = selected.town_section;
    shipTo.address = selected.town; // Using town as placeholder for address
    showGeoModal.value = false;
    console.log("Selected Delivery Code:", deliveryCode.value);
}

function handlePreviewDeliveryCode() {
    if (deliveryCode.value && !country.value && geoData.value.length > 0) {
        const selectedGeo = geoData.value.find(
            (geo) => geo.code === deliveryCode.value
        );
        if (selectedGeo) {
            country.value = selectedGeo.country;
            state.value = selectedGeo.state;
            town.value = selectedGeo.town;
            townSection.value = selectedGeo.town_section;
            shipTo.address = selectedGeo.town; // Assuming town can be a placeholder for shipTo.address
        }
    }

    showDeliveryCodePreviewModal.value = true;
}

async function handleSave() {
    console.log("Attempting to save:", {
        customerCode: customerCode.value,
        deliveryCode: deliveryCode.value,
    });
    if (!customerCode.value || !deliveryCode.value) {
        addToast("Customer Code and Delivery Code are required.", "error");
        return;
    }

    const payload = {
        customer_code: customerCode.value,
        delivery_code: deliveryCode.value,
        country: country.value,
        state: state.value,
        town: town.value,
        town_section: townSection.value,
        bill_to_name: billTo.name,
        bill_to_address: billTo.address,
        ship_to_name: shipTo.name,
        ship_to_address: shipTo.address,
        contact_person: shipTo.contactPerson,
        tel_no: shipTo.telNo,
        fax_no: shipTo.faxNo,
        email: shipTo.email,
    };

    try {
        let response;
        if (altAddressId.value) {
            // Update existing record
            response = await axios.put(
                `/api/customer-alternate-addresses/${altAddressId.value}`,
                payload
            );
            addToast(
                "Customer Alternate Address updated successfully!",
                "success"
            );
        } else {
            // Create new record
            response = await axios.post(
                "/api/customer-alternate-addresses",
                payload
            );
            addToast(
                "Customer Alternate Address saved successfully!",
                "success"
            );
        }
        console.log(response.data);
        handleClear(); // Clear form after successful save/update
    } catch (error) {
        console.error("Error saving customer alternate address:", error);
        addToast(
            "Failed to save Customer Alternate Address: " +
                (error.response?.data?.message || error.message),
            "error"
        );
    }
}

function handleClear() {
    altAddressId.value = null;
    customerCode.value = "";
    deliveryCode.value = "";
    country.value = "";
    state.value = "";
    town.value = "";
    townSection.value = "";
    billTo.name = "";
    billTo.address = "";
    shipTo.name = "";
    shipTo.address = "";
    shipTo.contactPerson = "";
    shipTo.telNo = "";
    shipTo.faxNo = "";
    shipTo.email = "";
}
</script>

<style scoped>
.action-btn {
    @apply text-white px-4 py-2 rounded-lg shadow-md text-lg flex items-center justify-center transition-all duration-200 hover:scale-105 hover:shadow-xl;
}
.animate-fade-in-up {
    animation: fadeInUp 0.7s cubic-bezier(0.23, 1, 0.32, 1);
}
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(40px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
