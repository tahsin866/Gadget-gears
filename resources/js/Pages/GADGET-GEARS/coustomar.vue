<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const customers = ref([
    {
        id: 1,
        name: 'John Doe',
        email: 'john@example.com',
        phone: '+91 98765 43210',
        totalOrders: 12,
        totalSpent: '₹24,599',
        status: 'active',
        lastPurchase: '2 days ago',
        avatar: 'JD'
    },
    // Add more customer data
]);

const selectedCustomers = ref([]);
const searchQuery = ref('');
const filterStatus = ref('all');
const showCustomerDetails = ref(false);
const selectedCustomer = ref(null);
</script>

<template>
    <Head title="Customer Management" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto  px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-[#2973B2]">Customer Management</h2>
                        <p class="text-gray-600">Manage and analyze your customer base</p>
                    </div>
                    <button class="bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white px-6 py-2 rounded-lg hover:opacity-90">
                        Add Customer
                    </button>
                </div>

                <!-- Customer Analytics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-[#2973B2]/10 to-white p-6 rounded-xl border border-[#2973B2]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Total Customers</p>
                                <h3 class="text-2xl font-bold text-[#2973B2]">2,453</h3>
                            </div>
                            <span class="text-green-500 text-sm">+12.5%</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#EB5A3C]/10 to-white p-6 rounded-xl border border-[#EB5A3C]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Active Customers</p>
                                <h3 class="text-2xl font-bold text-[#EB5A3C]">1,879</h3>
                            </div>
                            <span class="text-green-500 text-sm">+8.2%</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#2973B2]/10 to-white p-6 rounded-xl border border-[#2973B2]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Avg. Spend</p>
                                <h3 class="text-2xl font-bold text-[#2973B2]">₹4,892</h3>
                            </div>
                            <span class="text-green-500 text-sm">+15.3%</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#EB5A3C]/10 to-white p-6 rounded-xl border border-[#EB5A3C]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Retention Rate</p>
                                <h3 class="text-2xl font-bold text-[#EB5A3C]">76%</h3>
                            </div>
                            <span class="text-green-500 text-sm">+5.7%</span>
                        </div>
                    </div>
                </div>

                <!-- Customer List Section -->
                <div class="bg-white rounded-md shadow-sm overflow-hidden">
                    <!-- Toolbar -->
                    <div class="p-3 border-b border-gray-200 bg-gradient-to-r from-[#2973B2] to-[#EB5A3C]">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Customer Database</h3>
                            <div class="flex space-x-3">
                                <button class="bg-white/20 text-white px-4 py-2 rounded-md hover:bg-white/30">
                                    Export
                                </button>
                                <button class="bg-white/20 text-white px-4 py-2 rounded-md hover:bg-white/30">
                                    Import
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="p-4 bg-gray-50 border-b grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search customers..."
                            class="px-4 py-2 rounded-lg border focus:outline-none focus:border-[#2973B2] col-span-2"
                        >
                        <select
                            v-model="filterStatus"
                            class="px-4 py-2 rounded-lg border focus:outline-none focus:border-[#2973B2]"
                        >
                            <option value="all">All Status</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <select
                            class="px-4 py-2 rounded-lg border focus:outline-none focus:border-[#2973B2]"
                        >
                            <option>Sort by</option>
                            <option>Name</option>
                            <option>Most Orders</option>
                            <option>Highest Spent</option>
                        </select>
                    </div>

                    <!-- Customer Table -->
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left">
                                    <input type="checkbox" class="rounded text-[#2973B2]">
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contact</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Orders</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total Spent</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Last Purchase</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="customer in customers"
                                :key="customer.id"
                                class="hover:bg-gray-50 cursor-pointer"
                                @click="selectedCustomer = customer; showCustomerDetails = true"
                            >
                                <td class="px-6 py-4">
                                    <input
                                        type="checkbox"
                                        v-model="selectedCustomers"
                                        :value="customer.id"
                                        class="rounded text-[#2973B2]"
                                        @click.stop
                                    >
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-[#2973B2] flex items-center justify-center text-white font-medium">
                                            {{ customer.avatar }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="font-medium text-gray-900">{{ customer.name }}</div>
                                            <div class="text-sm text-gray-500">Customer ID: #{{ customer.id }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ customer.email }}</div>
                                    <div class="text-sm text-gray-500">{{ customer.phone }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ customer.totalOrders }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    {{ customer.totalSpent }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                          :class="customer.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'">
                                        {{ customer.status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-500">
                                    {{ customer.lastPurchase }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <button class="text-[#2973B2] hover:text-[#EB5A3C] mr-3">Edit</button>
                                    <button class="text-red-600 hover:text-red-900">Delete</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing 1 to 10 of 45 results
                        </div>
                        <div class="flex space-x-2">
                            <button class="px-3 py-1 rounded border hover:bg-gray-50">Previous</button>
                            <button class="px-3 py-1 rounded border bg-[#2973B2] text-white">1</button>
                            <button class="px-3 py-1 rounded border hover:bg-gray-50">2</button>
                            <button class="px-3 py-1 rounded border hover:bg-gray-50">3</button>
                            <button class="px-3 py-1 rounded border hover:bg-gray-50">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Customer Details Slide-Over -->
        <div v-if="showCustomerDetails"
             class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            <div class="fixed inset-y-0 right-0 pl-10 max-w-full flex">
                <div class="w-screen max-w-md">
                    <div class="h-full flex flex-col bg-white shadow-xl">
                        <div class="flex-1 overflow-y-auto py-6">
                            <div class="px-4 sm:px-6">
                                <div class="flex items-start justify-between">
                                    <h2 class="text-lg font-medium text-gray-900">Customer Details</h2>
                                    <button
                                        @click="showCustomerDetails = false"
                                        class="rounded-md text-gray-400 hover:text-gray-500"
                                    >
                                        <span class="sr-only">Close panel</span>
                                        ×
                                    </button>
                                </div>
                            </div>

                            <div class="mt-6 px-4 sm:px-6">
                                <!-- Customer Profile -->
                                <div class="text-center mb-6">
                                    <div class="h-20 w-20 rounded-full bg-[#2973B2] flex items-center justify-center text-white text-2xl font-medium mx-auto">
                                        {{ selectedCustomer?.avatar }}
                                    </div>
                                    <h3 class="mt-2 text-xl font-medium text-gray-900">{{ selectedCustomer?.name }}</h3>
                                    <p class="text-sm text-gray-500">Customer since Jan 2023</p>
                                </div>

                                <!-- Customer Stats -->
                                <div class="grid grid-cols-3 gap-4 mb-6">
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-[#2973B2]">{{ selectedCustomer?.totalOrders }}</div>
                                        <div class="text-sm text-gray-500">Orders</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-[#2973B2]">{{ selectedCustomer?.totalSpent }}</div>
                                        <div class="text-sm text-gray-500">Total Spent</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-[#2973B2]">4.8</div>
                                        <div class="text-sm text-gray-500">Avg. Rating</div>
                                    </div>
                                </div>

                                <!-- Contact Information -->
                                <div class="border-t border-gray-200 py-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-4">Contact Information</h4>
                                    <div class="space-y-3">
                                        <div class="flex items-center">
                                            <span class="text-gray-500 text-sm w-24">Email:</span>
                                            <span class="text-sm">{{ selectedCustomer?.email }}</span>
                                        </
                                    </div>
                                        <div class="flex items-center">
                                            <span class="text-gray-500 text-sm w-24">Phone:</span>
                                            <span class="text-sm">{{ selectedCustomer?.phone }}</span>
                                        </div>
                                        <div class="flex items-center">
                                            <span class="text-gray-500 text-sm w-24">Address:</span>
                                            <span class="text-sm">123 Main Street, City, Country</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Recent Orders -->
                                <div class="border-t border-gray-200 py-6">
                                    <h4 class="text-sm font-medium text-gray-900 mb-4">Recent Orders</h4>
                                    <div class="space-y-4">
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-sm font-medium">#ORD-2023-1234</span>
                                                <span class="text-sm text-gray-500">2 days ago</span>
                                            </div>
                                            <div class="text-sm text-gray-500">₹4,599 • 3 items</div>
                                        </div>
                                        <div class="bg-gray-50 p-4 rounded-lg">
                                            <div class="flex justify-between items-center mb-2">
                                                <span class="text-sm font-medium">#ORD-2023-1233</span>
                                                <span class="text-sm text-gray-500">5 days ago</span>
                                            </div>
                                            <div class="text-sm text-gray-500">₹2,899 • 2 items</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="border-t border-gray-200 py-6">
                                    <div class="grid grid-cols-2 gap-4">
                                        <button class="w-full px-4 py-2 bg-[#2973B2] text-white rounded-lg hover:opacity-90">
                                            Edit Details
                                        </button>
                                        <button class="w-full px-4 py-2 border border-[#EB5A3C] text-[#EB5A3C] rounded-lg hover:bg-[#EB5A3C] hover:text-white">
                                            Send Message
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </AuthenticatedLayout>
</template>
