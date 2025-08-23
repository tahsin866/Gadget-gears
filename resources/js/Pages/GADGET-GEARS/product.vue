<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import Modal from '@/Components/Modal.vue';
import axios from 'axios';

// Import PrimeVue components for advanced UI
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Badge from 'primevue/badge';
import Image from 'primevue/image';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Card from 'primevue/card';
import Chip from 'primevue/chip';
import ProgressBar from 'primevue/progressbar';
import Skeleton from 'primevue/skeleton';

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
const loading = ref(false);
const selectedProducts = ref([]);
const showDeleteDialog = ref(false);
const productToDelete = ref(null);
const toast = useToast();

// Image upload related
const selectedImages = ref([]);
const imagePreviewUrls = ref([]);
const primaryImageIndex = ref(0);

// Advanced filters
const sortBy = ref('name');
const sortOrder = ref('asc');
const showFilters = ref(false);
const stockFilter = ref('all'); // all, instock, lowstock, outofstock


// Fetch brands
const fetchBrands = async () => {
    try {
        const response = await axios.get('/show-brand');
        brands.value = response.data;
    } catch (error) {
        console.error('Error fetching brands:', error);
    }
};


const fetchProducts = async () => {
    loading.value = true;
    try {
        console.log('Fetching products from /products-list...');
        const response = await axios.get('/products-list');

        console.log('Products API Response:', response.data);

        products.value = response.data;

        console.log('Products set to:', products.value);
        console.log('First product images:', products.value[0]?.images || 'No images');

        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: `${response.data.length} products loaded successfully`,
            life: 3000
        });
    } catch (error) {
        console.error('Error fetching products:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load products',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

// Fetch categories
const fetchCategories = async () => {
    try {
        const response = await axios.get('/show-SubCetagory');
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
            product.sku.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (product.brand?.name && product.brand.name.toLowerCase().includes(searchQuery.value.toLowerCase()))
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

    // Stock filter
    if (stockFilter.value !== 'all') {
        switch (stockFilter.value) {
            case 'instock':
                filtered = filtered.filter(product => product.stock > 10);
                break;
            case 'lowstock':
                filtered = filtered.filter(product => product.stock > 0 && product.stock <= 10);
                break;
            case 'outofstock':
                filtered = filtered.filter(product => product.stock === 0);
                break;
        }
    }

    // Sorting
    filtered.sort((a, b) => {
        let aValue = a[sortBy.value];
        let bValue = b[sortBy.value];

        if (typeof aValue === 'string') {
            aValue = aValue.toLowerCase();
            bValue = bValue.toLowerCase();
        }

        if (sortOrder.value === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });

    return filtered;
});

// Stats computed
const stats = computed(() => {
    const total = products.value.length;
    const active = products.value.filter(p => p.status === 'active').length;
    const lowStock = products.value.filter(p => p.stock > 0 && p.stock <= 10).length;
    const outOfStock = products.value.filter(p => p.stock === 0).length;
    const totalValue = products.value.reduce((sum, p) => sum + (p.price * p.stock), 0);
    const featured = products.value.filter(p => p.featured).length;

    return {
        total,
        active,
        lowStock,
        outOfStock,
        totalValue,
        featured
    };
});

// Advanced computed properties
const sortOptions = computed(() => [
    { label: 'Name', value: 'name' },
    { label: 'Price', value: 'price' },
    { label: 'Stock', value: 'stock' },
    { label: 'Created Date', value: 'created_at' },
    { label: 'SKU', value: 'sku' }
]);

const statusOptions = computed(() => [
    { label: 'All Status', value: '' },
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' },
    { label: 'Draft', value: 'draft' }
]);

const stockOptions = computed(() => [
    { label: 'All Stock Levels', value: 'all' },
    { label: 'In Stock (>10)', value: 'instock' },
    { label: 'Low Stock (1-10)', value: 'lowstock' },
    { label: 'Out of Stock (0)', value: 'outofstock' }
]);

// Helper functions
const getStatusBadgeClass = (status) => {
    switch (status) {
        case 'active':
            return 'success';
        case 'inactive':
            return 'danger';
        case 'draft':
            return 'warning';
        default:
            return 'secondary';
    }
};

const getStockSeverity = (stock) => {
    if (stock === 0) {
        return 'danger';
    } else if (stock <= 10) {
        return 'warning';
    }
    return 'success';
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
};

const getProductImage = (product) => {
    console.log('Getting image for product:', product.id, product.name);
    console.log('Product image field:', product.image);
    console.log('Product images array:', product.images);

    // Check if there's a direct image field (from the API)
    if (product.image) {
        console.log('Using direct image field:', product.image);
        return product.image;
    }

    // Check if product has images array
    if (product.images && product.images.length > 0) {
        const primaryImage = product.images.find(img => img.is_primary === 1 || img.is_primary === '1');
        if (primaryImage && primaryImage.image_path) {
            const imagePath = `/storage/${primaryImage.image_path}`;
            console.log('Using primary image:', imagePath);
            return imagePath;
        }

        // Use first image if no primary
        if (product.images[0] && product.images[0].image_path) {
            const imagePath = `/storage/${product.images[0].image_path}`;
            console.log('Using first image:', imagePath);
            return imagePath;
        }
    }

    // Return placeholder
    const placeholder = 'https://placehold.co/80x80/E5E7EB/9CA3AF/png?text=No+Image';
    console.log('Using placeholder:', placeholder);
    return placeholder;
};

// Image error handling
const handleImageError = (event) => {
    console.log('Image failed to load:', event.target.src);
    event.target.src = 'https://placehold.co/80x80/E5E7EB/9CA3AF/png?text=No+Image';
};

const handleImageLoad = (event) => {
    console.log('Image loaded successfully:', event.target.src);
};

// Actions
const editProduct = (product) => {
    router.visit(`/admin/products/${product.id}/edit`);
};

const deleteProduct = async (product) => {
    if (window.confirm(`Are you sure you want to delete "${product.name}"? This action cannot be undone.`)) {
        try {
            loading.value = true;

            const response = await axios.delete(`/admin/products/${product.id}`);

            if (response.data.success) {
                // Remove from local array
                const index = products.value.findIndex(p => p.id === product.id);
                if (index > -1) {
                    products.value.splice(index, 1);
                }

                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: response.data.message || 'Product deleted successfully',
                    life: 3000
                });
            } else {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: response.data.message || 'Failed to delete product',
                    life: 3000
                });
            }
        } catch (error) {
            console.error('Error deleting product:', error);
            let errorMessage = 'Failed to delete product. Please try again.';

            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }

            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: errorMessage,
                life: 3000
            });
        } finally {
            loading.value = false;
        }
    }
};

const bulkDelete = async () => {
    if (selectedProducts.value.length === 0) {
        toast.add({
            severity: 'warn',
            summary: 'Warning',
            detail: 'Please select products to delete',
            life: 3000
        });
        return;
    }

    if (window.confirm(`Are you sure you want to delete ${selectedProducts.value.length} selected products? This action cannot be undone.`)) {
        try {
            loading.value = true;
            const productIds = selectedProducts.value.map(p => p.id);

            const response = await axios.post('/admin/products/bulk-delete', { ids: productIds });

            if (response.data.success) {
                // Remove from local array
                products.value = products.value.filter(p => !productIds.includes(p.id));
                selectedProducts.value = [];

                toast.add({
                    severity: 'success',
                    summary: 'Success',
                    detail: response.data.message || `${productIds.length} products deleted successfully`,
                    life: 3000
                });
            } else {
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: response.data.message || 'Failed to delete selected products',
                    life: 3000
                });
            }
        } catch (error) {
            console.error('Error bulk deleting products:', error);
            let errorMessage = 'Failed to delete selected products. Please try again.';

            if (error.response && error.response.data && error.response.data.message) {
                errorMessage = error.response.data.message;
            }

            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: errorMessage,
                life: 3000
            });
        } finally {
            loading.value = false;
        }
    }
};

const duplicateProduct = async (product) => {
    try {
        const response = await axios.post(`/admin/products/${product.id}/duplicate`);
        products.value.unshift(response.data);

        toast.add({
            severity: 'success',
            summary: 'Duplicated',
            detail: `${product.name} has been duplicated successfully`,
            life: 3000
        });
    } catch (error) {
        console.error('Error duplicating product:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to duplicate product. Please try again.',
            life: 3000
        });
    }
};

const toggleFeatured = async (product) => {
    try {
        const response = await axios.patch(`/admin/products/${product.id}/toggle-featured`);
        product.featured = response.data.featured;

        toast.add({
            severity: 'success',
            summary: 'Updated',
            detail: `${product.name} featured status updated`,
            life: 3000
        });
    } catch (error) {
        console.error('Error toggling featured:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update featured status',
            life: 3000
        });
    }
};

const refreshData = () => {
    fetchProducts();
    fetchCategories();
    fetchBrands();

    toast.add({
        severity: 'info',
        summary: 'Refreshed',
        detail: 'Data refreshed successfully',
        life: 3000
    });
};

const addNewProduct = () => {
    router.visit(route('GADGET-GEARS.createProduct'));
};

// Lifecycle
onMounted(() => {
    fetchProducts();
    fetchCategories();
    fetchBrands();
});
</script>

<template>
    <Head title="Products Management" />
    <Toast />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight flex items-center">
                        <i class="pi pi-box text-blue-600 dark:text-blue-400 mr-3 text-3xl"></i>
                        Product Management
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your product inventory with advanced controls</p>
                </div>
                <div class="flex space-x-3">
                    <Button
                        @click="refreshData"
                        icon="pi pi-refresh"
                        severity="secondary"
                        outlined
                        v-tooltip="'Refresh Data'"
                        class="shadow-sm hover:shadow transition-all duration-200"
                    />
                    <Button
                        @click="bulkDelete"
                        icon="pi pi-trash"
                        severity="danger"
                        outlined
                        :disabled="selectedProducts.length === 0"
                        v-tooltip="'Delete Selected'"
                        class="shadow-sm hover:shadow transition-all duration-200"
                    />
                    <Button
                        @click="addNewProduct"
                        icon="pi pi-plus"
                        label="Add Product"
                        class="bg-gradient-to-r from-blue-600 to-blue-800 border-0 shadow-sm hover:shadow-md transition-all duration-200"
                    />
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="mx-auto sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-6 gap-4 mb-8">
                    <!-- Total Products Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-5 text-center space-y-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl mx-auto flex items-center justify-center">
                                <i class="pi pi-box text-white text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.total }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Products</div>
                            </div>
                        </div>
                    </div>

                    <!-- Active Products Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-5 text-center space-y-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 rounded-2xl mx-auto flex items-center justify-center">
                                <i class="pi pi-check-circle text-white text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.active }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Active Products</div>
                            </div>
                        </div>
                    </div>

                    <!-- Low Stock Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-5 text-center space-y-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-yellow-600 rounded-2xl mx-auto flex items-center justify-center">
                                <i class="pi pi-exclamation-triangle text-white text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.lowStock }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Low Stock</div>
                            </div>
                        </div>
                    </div>

                    <!-- Out of Stock Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-5 text-center space-y-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-red-600 rounded-2xl mx-auto flex items-center justify-center">
                                <i class="pi pi-times-circle text-white text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.outOfStock }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Out of Stock</div>
                            </div>
                        </div>
                    </div>

                    <!-- Featured Products Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-5 text-center space-y-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 rounded-2xl mx-auto flex items-center justify-center">
                                <i class="pi pi-star text-white text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ stats.featured }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Featured</div>
                            </div>
                        </div>
                    </div>

                    <!-- Total Value Card -->
                    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden border border-gray-100 dark:border-gray-700">
                        <div class="p-5 text-center space-y-3">
                            <div class="w-16 h-16 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-2xl mx-auto flex items-center justify-center">
                                <i class="pi pi-dollar text-white text-2xl"></i>
                            </div>
                            <div>
                                <div class="text-xl font-bold text-gray-900 dark:text-white">{{ formatPrice(stats.totalValue) }}</div>
                                <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Value</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Main Product DataTable Card -->
                <div class="bg-white dark:bg-gray-800 shadow-xl rounded-2xl overflow-hidden border border-gray-100 dark:border-gray-700">
                    <!-- Search and Filter Section -->
                    <div class="p-6 border-b border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                        <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-end">
                            <!-- Search Input -->
                            <div class="lg:col-span-4">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Search Products</label>
                                <div class="relative">
                                    <i class="pi pi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 dark:text-gray-500"></i>
                                    <InputText
                                        v-model="searchQuery"
                                        placeholder="Search by name, SKU, or brand..."
                                        class="w-full pl-10 pr-4 py-2.5 rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-800 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                                    />
                                </div>
                            </div>

                            <!-- Category Filter -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Category</label>
                                <Dropdown
                                    v-model="selectedCategory"
                                    :options="[{ name: 'All Categories', id: '' }, ...categories]"
                                    option-label="name"
                                    option-value="id"
                                    placeholder="Select Category"
                                    class="w-full rounded-lg"
                                />
                            </div>

                            <!-- Status Filter -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                                <Dropdown
                                    v-model="selectedStatus"
                                    :options="statusOptions"
                                    option-label="label"
                                    option-value="value"
                                    placeholder="Select Status"
                                    class="w-full rounded-lg"
                                />
                            </div>

                            <!-- Stock Filter -->
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Stock Level</label>
                                <Dropdown
                                    v-model="stockFilter"
                                    :options="stockOptions"
                                    option-label="label"
                                    option-value="value"
                                    placeholder="Stock Level"
                                    class="w-full rounded-lg"
                                />
                            </div>

                            <!-- Sort Options -->
                            <div class="lg:col-span-2 flex space-x-2">
                                <div class="flex-1">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Sort By</label>
                                    <Dropdown
                                        v-model="sortBy"
                                        :options="sortOptions"
                                        option-label="label"
                                        option-value="value"
                                        class="w-full rounded-lg"
                                    />
                                </div>
                                <div class="flex items-end">
                                    <Button
                                        @click="sortOrder = sortOrder === 'asc' ? 'desc' : 'asc'"
                                        :icon="sortOrder === 'asc' ? 'pi pi-sort-amount-up' : 'pi pi-sort-amount-down'"
                                        :severity="sortOrder === 'asc' ? 'secondary' : 'info'"
                                        outlined
                                        class="rounded-lg h-[42px] transition-all duration-200"
                                        v-tooltip="sortOrder === 'asc' ? 'Ascending' : 'Descending'"
                                    />
                                </div>
                            </div>
                        </div>

                        <!-- Results Summary -->
                        <div class="mt-4 flex flex-col md:flex-row justify-between items-start md:items-center space-y-2 md:space-y-0">
                            <div class="text-sm text-gray-600 dark:text-gray-400">
                                Showing {{ filteredProducts.length }} of {{ products.length }} products
                                <span v-if="selectedProducts.length > 0" class="ml-2 font-medium text-blue-600 dark:text-blue-400">
                                    ({{ selectedProducts.length }} selected)
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <Chip
                                    v-if="searchQuery"
                                    :label="`Search: ${searchQuery}`"
                                    removable
                                    @remove="searchQuery = ''"
                                    class="bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200"
                                />
                                <Chip
                                    v-if="selectedCategory"
                                    :label="`Category: ${categories.find(c => c.id === selectedCategory)?.name}`"
                                    removable
                                    @remove="selectedCategory = ''"
                                    class="bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200"
                                />
                                <Chip
                                    v-if="selectedStatus"
                                    :label="`Status: ${selectedStatus}`"
                                    removable
                                    @remove="selectedStatus = ''"
                                    class="bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- DataTable -->
                    <div class="overflow-hidden">
                        <DataTable
                            v-model:selection="selectedProducts"
                            :value="filteredProducts"
                            :loading="loading"
                            dataKey="id"
                            :paginator="true"
                            :rows="20"
                            :rowsPerPageOptions="[10, 20, 50, 100]"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} products"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            selectionMode="multiple"
                            stripedRows
                            removableSort
                            :globalFilterFields="['name', 'sku', 'brand.name']"
                            class="p-datatable-lg"
                        >
                            <!-- Selection Column -->
                            <Column selectionMode="multiple" headerStyle="width: 3rem" class="text-center"></Column>

                            <!-- Product Column with Image -->
                            <Column field="name" header="Product" sortable class="min-w-[300px]">
                                <template #body="{ data }">
                                    <div class="flex items-center space-x-4 p-2">
                                        <div class="flex-shrink-0">
                                            <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden border border-gray-200">
                                                <img
                                                    :src="getProductImage(data)"
                                                    :alt="data.name"
                                                    class="w-full h-full object-cover"
                                                    @error="handleImageError"
                                                    @load="handleImageLoad"
                                                />
                                            </div>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-lg font-bold text-gray-900 dark:text-white truncate">{{ data.name }}</div>
                                            <div class="flex items-center flex-wrap gap-2 mt-1">
                                                <Tag v-if="data.brand" :value="data.brand.name" severity="info" class="text-xs" />
                                                <Tag v-if="data.category" :value="data.category.name" severity="secondary" class="text-xs" />
                                            </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 mt-1">SKU: {{ data.sku }}</div>
                                        </div>
                                    </div>
                                </template>
                            </Column>

                            <!-- Price Column -->
                            <Column field="price" header="Price" sortable class="text-center">
                                <template #body="{ data }">
                                    <div class="space-y-1 p-2">
                                        <div class="text-lg font-bold text-gray-900 dark:text-white">{{ formatPrice(data.price) }}</div>
                                        <div v-if="data.sale_price && data.sale_price < data.price" class="text-sm">
                                            <span class="text-green-600 dark:text-green-400 font-semibold">{{ formatPrice(data.sale_price) }}</span>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ Math.round(((data.price - data.sale_price) / data.price) * 100) }}% OFF
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </Column>

                            <!-- Stock Column -->
                            <Column field="stock" header="Stock" sortable class="text-center">
                                <template #body="{ data }">
                                    <div class="space-y-2 p-2">
                                        <Badge
                                            :value="data.stock"
                                            :severity="getStockSeverity(data.stock)"
                                            class="text-lg font-bold px-3 py-1.5"
                                        />
                                        <ProgressBar
                                            :value="Math.min((data.stock / 100) * 100, 100)"
                                            :class="`h-2 ${data.stock === 0 ? 'opacity-30' : ''}`"
                                            :showValue="false"
                                        />
                                    </div>
                                </template>
                            </Column>

                            <!-- Status Column -->
                            <Column field="status" header="Status" sortable class="text-center">
                                <template #body="{ data }">
                                    <Tag
                                        :value="data.status.toUpperCase()"
                                        :severity="getStatusBadgeClass(data.status)"
                                        class="font-bold px-4 py-1.5"
                                    />
                                </template>
                            </Column>

                            <!-- Featured Column -->
                            <Column field="featured" header="Featured" class="text-center">
                                <template #body="{ data }">
                                    <Button
                                        @click="toggleFeatured(data)"
                                        :icon="data.featured ? 'pi pi-star-fill' : 'pi pi-star'"
                                        :severity="data.featured ? 'warning' : 'secondary'"
                                        :outlined="!data.featured"
                                        size="small"
                                        rounded
                                        class="transition-all duration-200"
                                        v-tooltip="data.featured ? 'Remove from Featured' : 'Add to Featured'"
                                    />
                                </template>
                            </Column>

                            <!-- Actions Column -->
                            <Column header="Actions" class="text-center min-w-[200px]">
                                <template #body="{ data }">
                                    <div class="flex justify-center space-x-2">
                                        <Button
                                            @click="editProduct(data)"
                                            icon="pi pi-pencil"
                                            severity="info"
                                            size="small"
                                            rounded
                                            outlined
                                            class="transition-all duration-200 hover:shadow-md"
                                            v-tooltip="'Edit Product'"
                                        />
                                        <Button
                                            @click="duplicateProduct(data)"
                                            icon="pi pi-copy"
                                            severity="secondary"
                                            size="small"
                                            rounded
                                            outlined
                                            class="transition-all duration-200 hover:shadow-md"
                                            v-tooltip="'Duplicate Product'"
                                        />
                                        <Button
                                            @click="router.visit(`/buyeProduct/${data.id}`)"
                                            icon="pi pi-eye"
                                            severity="help"
                                            size="small"
                                            rounded
                                            outlined
                                            class="transition-all duration-200 hover:shadow-md"
                                            v-tooltip="'View Product'"
                                        />
                                        <Button
                                            @click="deleteProduct(data)"
                                            icon="pi pi-trash"
                                            severity="danger"
                                            size="small"
                                            rounded
                                            outlined
                                            class="transition-all duration-200 hover:shadow-md"
                                            v-tooltip="'Delete Product'"
                                        />
                                    </div>
                                </template>
                            </Column>

                            <!-- Loading Template -->
                            <template #loading>
                                <div class="flex justify-center items-center py-12">
                                    <div class="space-y-4 text-center">
                                        <i class="pi pi-spin pi-spinner text-4xl text-blue-500 dark:text-blue-400"></i>
                                        <div class="text-lg font-medium text-gray-600 dark:text-gray-400">Loading products...</div>
                                    </div>
                                </div>
                            </template>

                            <!-- Empty Template -->
                            <template #empty>
                                <div class="text-center py-12">
                                    <div class="space-y-4">
                                        <div class="w-20 h-20 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mx-auto">
                                            <i class="pi pi-box text-3xl text-gray-400 dark:text-gray-500"></i>
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-white">No products found</h3>
                                        <p class="text-gray-600 dark:text-gray-400 max-w-md mx-auto">
                                            {{ searchQuery || selectedCategory || selectedStatus ?
                                               'Try adjusting your search or filter criteria.' :
                                               'Start by adding your first product to the inventory.'
                                            }}
                                        </p>
                                        <Button
                                            v-if="!searchQuery && !selectedCategory && !selectedStatus"
                                            @click="addNewProduct"
                                            label="Add First Product"
                                            icon="pi pi-plus"
                                            class="bg-gradient-to-r from-blue-600 to-blue-800 border-0 shadow-md hover:shadow-lg transition-all duration-200"
                                        />
                                    </div>
                                </div>
                            </template>
                        </DataTable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
