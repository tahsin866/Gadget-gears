<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head,router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

// Reactive data
const products = ref([]);
const categories = ref([]);
const searchQuery = ref('');
const selectedCategory = ref('');
const selectedStatus = ref('');
const showAddModal = ref(false);
const availableCategories = ref([]);
const brands = ref([]);
const errors = ref({});

// New product form data
const newProduct = ref({
    name: '',
    sku: '',
    description: '',
    price: '',
    sale_price: '',
    stock: '',
    category_id: '',
    brand_id: '',
    status: 'active',
    featured: false,
    meta_title: '',
    meta_description: ''
});

// Modal functions
const closeModal = () => {
    showAddModal.value = false;
    // Reset form
    newProduct.value = {
        name: '',
        sku: '',
        description: '',
        price: '',
        sale_price: '',
        stock: '',
        category_id: '',
        brand_id: '',
        status: 'active',
        featured: false,
        meta_title: '',
        meta_description: ''
    };
    errors.value = {};
};

// const submitForm = async () => {
//     try {
//         const response = await axios.post('/products', newProduct.value);
//         console.log('Product added successfully:', response.data);
//         closeModal();
//         fetchProducts(); // Refresh the product list
//     } catch (error) {
//         if (error.response && error.response.data.errors) {
//             errors.value = error.response.data.errors;
//         }
//         console.error('Error adding product:', error);
//     }
// };




const submitForm = () => {
// Clear previous errors
 errors.value = {};


// Basic client-side validation
 if (!newProduct.value.name) {
errors.value.name = 'Product name is required';
 return;
 }
 if (!newProduct.value.sku) {
errors.value.sku = 'SKU is required';
 return;
}
if (!newProduct.value.price) {
 errors.value.price = 'Price is required';
 return;
 }
 if (!newProduct.value.stock) {
errors.value.stock = 'Stock is required';
 return;
 }
 if (!newProduct.value.category_id) {
 errors.value.category_id = 'Category is required';
 return;
 }


 router.post(route('products.store'), newProduct.value, {
onSuccess: () => {
 closeModal();
 alert('Product created successfully!');
 },
 onError: (serverErrors) => {
errors.value = serverErrors;
 console.log('Validation errors:', serverErrors);
}
 });
};







// Fetch products from API
const fetchProducts = async () => {
    try {
        const response = await axios.get('/products-list');
        products.value = response.data;
    } catch (error) {
        console.error('Error fetching products:', error);
    }
};

// Fetch categories
const fetchCategories = async () => {
    try {
        const response = await axios.get('/show-cetagory');
        categories.value = response.data;
        availableCategories.value = response.data;
    } catch (error) {
        console.error('Error fetching categories:', error);
    }
};

// Computed properties
const filteredProducts = computed(() => {
    let filtered = products.value;

    // Search filter
    if (searchQuery.value) {
        filtered = filtered.filter(product =>
            product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            product.sku.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    // Category filter
    if (selectedCategory.value) {
        filtered = filtered.filter(product =>
            product.category_id == selectedCategory.value
        );
    }

    // Status filter
    if (selectedStatus.value) {
        filtered = filtered.filter(product =>
            product.status === selectedStatus.value
        );
    }

    return filtered;
});

// Stats computed
const stats = computed(() => {
    const total = products.value.length;
    const active = products.value.filter(p => p.status === 'active').length;
    const lowStock = products.value.filter(p => p.stock > 0 && p.stock <= 10).length;
    const outOfStock = products.value.filter(p => p.stock === 0).length;

    return {
        total,
        active,
        lowStock,
        outOfStock
    };
});

// Helper functions
const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active':
            return 'bg-green-100 text-green-800';
        case 'inactive':
            return 'bg-red-100 text-red-800';
        case 'draft':
            return 'bg-gray-100 text-gray-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
};

const getStockClass = (stock) => {
    if (stock === 0) {
        return 'text-red-600 font-semibold';
    } else if (stock <= 10) {
        return 'text-yellow-600 font-semibold';
    }
    return 'text-gray-900';
};

// Actions
const editProduct = (product) => {
    console.log('Edit product:', product);
    // Add edit functionality here
};

const deleteProduct = (product) => {
    if (confirm('Are you sure you want to delete this product?')) {
        // Add delete functionality here
        console.log('Delete product:', product);
    }
};

// Lifecycle
onMounted(() => {
    fetchProducts();
    fetchCategories();
});
</script>



<template>
    <Head title="Products" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Product Management
                </h2>
                <PrimaryButton @click="showAddModal = true">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add New Product
                </PrimaryButton>
            </div>
        </template>

    <div class="py-12">
            <div class=" sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex items-center">
                                <div class="p-2 bg-blue-100 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Total Products</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ stats.total }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex items-center">
                                <div class="p-2 bg-yellow-100 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Low Stock</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ stats.lowStock }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex items-center">
                                <div class="p-2 bg-red-100 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Out of Stock</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ stats.outOfStock }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900">
                            <div class="flex items-center">
                                <div class="p-2 bg-green-100 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">Active Products</p>
                                    <p class="text-2xl font-semibold text-gray-900">{{ stats.active }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product List -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <!-- Search and Filter -->
                    <div class="p-6 border-b border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div class="md:col-span-2">
                                <TextInput
                                    v-model="searchQuery"
                                    type="text"
                                    placeholder="Search products..."
                                    class="w-full"
                                />
                            </div>
                            <div>
                                <select
                                    v-model="selectedCategory"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                >
                                    <option value="">All Categories</option>
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                            </div>
                            <div>
                                <select
                                    v-model="selectedStatus"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                >
                                    <option value="">All Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Product Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Product
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        SKU
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Sale Price
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Stock
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Featured
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="product in filteredProducts" :key="product.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-12 w-12">
                                                <div class="h-12 w-12 rounded-lg bg-gray-200 flex items-center justify-center">
                                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ product.name }}
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    {{ product.category?.name }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ product.sku }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        ₹{{ product.price }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span v-if="product.sale_price">₹{{ product.sale_price }}</span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <span :class="getStockClass(product.stock)">
                                            {{ product.stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                            getStatusBadgeClass(product.status)
                                        ]">
                                            {{ product.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span v-if="product.featured" class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Featured
                                        </span>
                                        <span v-else class="text-gray-400">-</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button
                                            @click="editProduct(product)"
                                            class="text-indigo-600 hover:text-indigo-900 mr-4"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                        </button>
                                        <button
                                            @click="deleteProduct(product)"
                                            class="text-red-600 hover:text-red-900"
                                        >
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Empty State -->
                    <div v-if="filteredProducts.length === 0" class="text-center py-12">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">No products found</h3>
                        <p class="mt-1 text-sm text-gray-500">Try adjusting your search or filter criteria.</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Add Product Modal -->
       <Modal :show="showAddModal" @close="closeModal">
    <div class="p-6 max-h-[90vh] overflow-y-auto">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-medium text-gray-900">
                Add New Product
            </h2>
            <button
                @click="closeModal"
                class="text-gray-400 hover:text-gray-600"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form @submit.prevent="submitForm" class="space-y-6">
            <!-- Basic Information -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Basic Information</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="name" value="Product Name *" />
                        <TextInput
                            id="name"
                            v-model="newProduct.name"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="sku" value="SKU *" />
                        <TextInput
                            id="sku"
                            v-model="newProduct.sku"
                            type="text"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError class="mt-2" />
                    </div>
                </div>

                <div class="mt-6">
                    <InputLabel for="description" value="Product Description" />
                    <textarea
                        id="description"
                        v-model="newProduct.description"
                        rows="4"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        placeholder="Detailed product description, features, specifications..."
                    ></textarea>
                    <InputError class="mt-2" />
                </div>
            </div>

            <!-- Pricing & Inventory -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Pricing & Inventory</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <InputLabel for="price" value="Regular Price (₹) *" />
                        <TextInput
                            id="price"
                            v-model="newProduct.price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="sale_price" value="Sale Price (₹)" />
                        <TextInput
                            id="sale_price"
                            v-model="newProduct.sale_price"
                            type="number"
                            step="0.01"
                            class="mt-1 block w-full"
                        />
                        <InputError class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="stock" value="Stock Quantity *" />
                        <TextInput
                            id="stock"
                            v-model="newProduct.stock"
                            type="number"
                            class="mt-1 block w-full"
                            required
                        />
                        <InputError class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Categories & Brand -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Categories & Brand</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                 <div>
                        <InputLabel for="category_id" value="Category" />
                        <select
                            id="category_id"
                            v-model="newProduct.category_id"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"
                            required
                        >
                            <option value="">Select Category</option>
                            <option v-for="category in availableCategories" :key="category.id" :value="category.id">
                                {{ category.name }}
                            </option>
                        </select>
                        <InputError v-if="errors.category_id" :message="errors.category_id" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="brand_id" value="Brand" />
                        <select
                            id="brand_id"
                            v-model="newProduct.brand_id"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                            <option value="">Select Brand</option>
                            <option v-for="brand in brands" :key="brand.id" :value="brand.id">
                                {{ brand.name }}
                            </option>
                        </select>
                        <InputError class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Product Settings -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Product Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="status" value="Status" />
                        <select
                            id="status"
                            v-model="newProduct.status"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                        <InputError class="mt-2" />
                    </div>
                    <div class="flex items-center mt-6">
                        <input
                            id="featured"
                            v-model="newProduct.featured"
                            type="checkbox"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                        <label for="featured" class="ml-2 block text-sm text-gray-900">
                            Featured Product
                        </label>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-800 mb-4">SEO Settings</h3>
                <div class="space-y-4">
                    <div>
                        <InputLabel for="meta_title" value="Meta Title" />
                        <TextInput
                            id="meta_title"
                            v-model="newProduct.meta_title"
                            type="text"
                            class="mt-1 block w-full"
                            maxlength="255"
                        />
                        <p class="text-xs text-gray-500 mt-1">{{ newProduct.meta_title?.length || 0 }}/255 characters</p>
                        <InputError class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="meta_description" value="Meta Description" />
                        <textarea
                            id="meta_description"
                            v-model="newProduct.meta_description"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            maxlength="160"
                            placeholder="Brief description for search engines..."
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">{{ newProduct.meta_description?.length || 0 }}/160 characters</p>
                        <InputError class="mt-2" />
                    </div>
                </div>
            </div>

            <!-- Product Images -->
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="text-md font-semibold text-gray-800 mb-4">Product Images</h3>
                <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                    <div class="space-y-1 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <div class="flex text-sm text-gray-600">
                            <label for="file-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                <span>Upload files</span>
                                <input id="file-upload" name="file-upload" type="file" class="sr-only" multiple accept="image/*">
                            </label>
                            <p class="pl-1">or drag and drop</p>
                        </div>
                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 10MB</p>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <SecondaryButton @click="closeModal">
                    Cancel
                </SecondaryButton>
                <PrimaryButton
                @click="submitForm"
                type="submit">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    Add Product
                </PrimaryButton>
            </div>
        </form>
    </div>
</Modal>

    </AuthenticatedLayout>
</template>

<style scoped>
/* Custom styles for toggle switch */
.peer:checked ~ div {
    background-color: #2563eb;
}

.peer:checked ~ div:after {
    transform: translateX(100%);
    border-color: white;
}
</style>
