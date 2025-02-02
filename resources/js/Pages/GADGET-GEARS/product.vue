<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const products = ref([
    { id: 1, name: 'Wireless Earbuds', sku: 'WE-001', price: '₹2499', stock: 45, status: 'active' },
    { id: 2, name: 'Smart Watch Pro', sku: 'SW-002', price: '₹3999', stock: 12, status: 'low-stock' },
]);

const showAddModal = ref(false);
const newProduct = ref({
    name: '',
    sku: '',
    price: '',
    stock: '',
    description: '',
    category: '',
    images: []
});
</script>

<template>
    <Head title="Manage Products" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-bold text-[#2973B2]">Product Management</h2>
                    <button @click="showAddModal = true"
                            class="bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white px-6 py-2 rounded-md hover:opacity-90 transition-all">
                        Add New Product
                    </button>
                </div>

                <!-- Quick Stats -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-[#2973B2]/10 to-white p-6 rounded-md border border-[#2973B2]/20">
                        <h3 class="text-[#2973B2] font-semibold">Total Products</h3>
                        <p class="text-2xl font-bold">156</p>
                    </div>
                    <div class="bg-gradient-to-br from-[#EB5A3C]/10 to-white p-6 rounded-md border border-[#EB5A3C]/20">
                        <h3 class="text-[#EB5A3C] font-semibold">Low Stock Items</h3>
                        <p class="text-2xl font-bold">8</p>
                    </div>
                    <div class="bg-gradient-to-br from-[#2973B2]/10 to-white p-6 rounded-md border border-[#2973B2]/20">
                        <h3 class="text-[#2973B2] font-semibold">Out of Stock</h3>
                        <p class="text-2xl font-bold">3</p>
                    </div>
                </div>

                <!-- Product List -->
                <div class="bg-white rounded-md shadow-sm overflow-hidden">
                    <div class="p-3 border-b border-gray-200 bg-gradient-to-r from-[#2973B2] to-[#EB5A3C]">
                        <h3 class="text-lg font-semibold text-white">Product Inventory</h3>
                    </div>

                    <!-- Search and Filter -->
                    <div class="p-4 bg-gray-50 border-b flex gap-4">
    <input type="text"
           placeholder="Search products..."
           class="px-4 py-2 rounded-md border focus:outline-none focus:border-[#2973B2] flex-1">

    <select class="px-8 py-2 rounded-md border focus:outline-none focus:border-[#2973B2]">
        <option>All Categories</option>
        <option>Electronics</option>
        <option>Accessories</option>
    </select>

    <select class="px-4 py-2 rounded-md border focus:outline-none focus:border-[#2973B2]">
        <option>All Status</option>
        <option>In Stock</option>
        <option>Out of Stock</option>
        <option>Low Stock</option>
    </select>
</div>


                    <!-- Product Table -->
                    <table class="w-full">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">cetagory</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Price</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Active</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
        </tr>
    </thead>
    <tbody class="divide-y divide-gray-200">
        <tr v-for="product in products" :key="product.id" class="hover:bg-gray-50">
            <td class="px-6 py-4">
                <div class="flex items-center">
                    <div class="h-10 w-10 rounded-lg bg-gray-200"></div>
                    <div class="ml-4">
                        <div class="font-medium text-gray-900">{{ product.name }}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ product.sku }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ product.sku }}</td>
            <td class="px-6 py-4 text-sm text-gray-900">{{ product.price }}</td>
            <td class="px-6 py-4 text-sm text-gray-500">{{ product.stock }}</td>
            <td class="px-6 py-4">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                      :class="product.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                    {{ product.status }}
                </span>
            </td>
            <td class="px-6 py-3">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" v-model="product.isActive" class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer peer-focus:ring-4 peer-focus:ring-blue-300 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-blue-600"></div>
                </label>
            </td>
            <td class="px-6 py-4 text-sm">
                <button class="text-[#2973B2] hover:text-[#EB5A3C] mr-3">Edit</button>
                <button class="text-red-600 hover:text-red-900">Delete</button>
            </td>
        </tr>
    </tbody>
</table>

                </div>
            </div>
        </div>

        <!-- Add Product Modal -->
        <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-xl max-w-2xl w-full p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-[#2973B2]">Add New Product</h3>
                    <button @click="showAddModal = false" class="text-gray-500 hover:text-gray-700">×</button>
                </div>

                <form class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Product Name</label>
                            <input type="text" v-model="newProduct.name"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">SKU</label>
                            <input type="text" v-model="newProduct.sku"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" v-model="newProduct.price"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Stock</label>
                            <input type="number" v-model="newProduct.stock"
                                   class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">short Description</label>
                        <textarea v-model="newProduct.description" rows="3"
                                 class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700"> long Description</label>
                        <textarea v-model="newProduct.description" rows="3"
                                 class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category</label>
                        <select v-model="newProduct.category"
                                class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                            <option>Electronics</option>
                            <option>Accessories</option>
                            <option>Gaming</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Product Images</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02"
                                          stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label class="relative cursor-pointer bg-white rounded-md font-medium text-[#2973B2] hover:text-[#EB5A3C]">
                                        Upload files
                                        <input type="file" class="sr-only" multiple>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="showAddModal = false"
                                class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white rounded-lg hover:opacity-90">
                            Add Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
