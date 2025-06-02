<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import modal from './modal.vue';
import parentCetagiryModel from './parentCetagiryModel.vue';

// Reactive data
const showCetagoryList = ref([]);
const count = ref(0);
const loading = ref(false);
const error = ref(null);

// Computed properties for statistics
const activeCount = computed(() => {
    return showCetagoryList.value.filter(category => category.status === 'active').length;
});

const featuredCount = computed(() => {
    return showCetagoryList.value.filter(category => category.featured === 1 || category.featured === true).length;
});

const totalProducts = computed(() => {
    return showCetagoryList.value.reduce((total, category) => total + (category.products_count || 0), 0);
});

// Reactive variables
const showModal = ref(false);
const showCetagoryModal = ref(false);
const editingCategory = ref(null);
const ParentCategory = ref(null);
const searchQuery = ref('');
const statusFilter = ref('all');
const sortBy = ref('name');

const filteredCategories = computed(() => {
    let filtered = showCetagoryList.value;

    if (searchQuery.value) {
        filtered = filtered.filter(category =>
            category.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (category.description && category.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
        );
    }

    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(category => category.status === statusFilter.value);
    }

    return filtered.sort((a, b) => {
        switch (sortBy.value) {
            case 'name':
                return a.name.localeCompare(b.name);
            case 'products_count':
                return (b.products_count || 0) - (a.products_count || 0);
            case 'created_at':
                return new Date(b.created_at) - new Date(a.created_at);
            default:
                return 0;
        }
    });
});

// Fetch categories data
const fetchCategories = async () => {
    try {
        loading.value = true;
        error.value = null;

        const response = await axios.get('/show-cetagory-list');

        if (response.data.success) {
            showCetagoryList.value = response.data.showCetagoryList;
            count.value = response.data.count;
        } else {
            error.value = 'Failed to fetch categories';
        }
    } catch (err) {
        error.value = err.response?.data?.message || 'An error occurred while fetching categories';
        console.error('Error fetching categories:', err);
    } finally {
        loading.value = false;
    }
};

// Modal functions
const openEditModal = (category) => {
    editingCategory.value = category;
    showModal.value = true;
};

const openAddModal = () => {
    editingCategory.value = null;
    showModal.value = true;
};

const openCetagoryModal = () => {
    ParentCategory.value = null;
    showCetagoryModal.value = true;
};

const handleCategoryUpdated = () => {
    showModal.value = false;
    showCetagoryModal.value = false;
    editingCategory.value = null;
    ParentCategory.value = null;
    // Refresh data after update
    fetchCategories();
};

const deleteCategory = async (category) => {
    if (confirm(`Are you sure you want to delete "${category.name}"?`)) {
        try {
            const response = await fetch(`${window.location.origin}/delete-category/${category.id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();

            if (data.success) {
                // Remove category from the list
                filteredCategories.value = filteredCategories.value.filter(cat => cat.id !== category.id);
                alert(data.message);
            } else {
                alert(data.message);
            }
        } catch (error) {
            console.error('Error deleting category:', error);
            alert('An error occurred while deleting the category');
        }
    }
};




const getStatusBadgeClass = (status) => {
    const classes = {
        'active': 'bg-green-100 text-green-800',
        'inactive': 'bg-red-100 text-red-800',
        'draft': 'bg-yellow-100 text-yellow-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Fetch data on component mount
onMounted(() => {
    fetchCategories();
});



</script>


<template>
    <Head title="Category Management" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Category Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Organize and manage your product categories
                    </p>
                </div>
                <div class="flex space-x-3">
                    <button
                        @click="openCetagoryModal"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add parent category
                    </button>
                    <button
                        @click="openAddModal"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Add Category
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Export
                    </button>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-indigo-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Categories</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ count }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-green-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Active Categories</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ activeCount }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-yellow-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Featured Categories</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ featuredCount }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow rounded-lg">
                        <div class="p-5">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-8 h-8 bg-purple-500 rounded-md flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Products</dt>
                                        <dd class="text-lg font-medium text-gray-900">{{ totalProducts }}</dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Categories Table -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <!-- Search and Filter Bar -->
    <div class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-3 sm:space-y-0 sm:space-x-4">
            <div class="flex-1 min-w-0">
                <div class="max-w-lg w-full lg:max-w-xs">
                    <label for="search" class="sr-only">Search categories</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input
                            v-model="searchQuery"
                            id="search"
                            class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Search categories..."
                            type="search"
                        >
                    </div>
                </div>
            </div>
            <div class="flex space-x-3">
                <select
                    v-model="statusFilter"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                    <option value="all">All Status</option>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="draft">Draft</option>
                </select>
                <select
                    v-model="sortBy"
                    class="block w-full pl-3 pr-10 py-2 text-base border border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md"
                >
                    <option value="name">Sort by Name</option>
                    <option value="products_count">Sort by Products</option>
                    <option value="created_at">Sort by Date</option>
                    <option value="order">Sort by Order</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Categories Table -->
<div class="overflow-x-auto shadow-lg rounded-lg border border-gray-200">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gradient-to-r from-gray-50 to-gray-100">
            <tr>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span>Category</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Status</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span>Products</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        <span>Type</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                        </svg>
                        <span>Meta Title</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" />
                        </svg>
                        <span>Meta Description</span>
                    </div>
                </th>
                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider border-b border-gray-200">
                    <div class="flex items-center space-x-1">
                        <svg class="h-4 w-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span>Created</span>
                    </div>
                </th>
                <th scope="col" class="relative px-6 py-4 border-b border-gray-200">
                    <span class="sr-only">Actions</span>
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">
            <tr v-for="category in filteredCategories" :key="category.id" class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 transition-all duration-200 group">
                <td class="px-6 py-5 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <div class="h-12 w-12 rounded-xl bg-gradient-to-br from-indigo-100 to-purple-100 flex items-center justify-center shadow-sm group-hover:shadow-md transition-shadow duration-200">
                                <svg class="h-6 w-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-semibold text-gray-900 group-hover:text-indigo-700 transition-colors duration-200">
                                {{ category.name }}
                            </div>
                            <div class="text-sm text-gray-500 font-mono bg-gray-50 px-2 py-1 rounded-md mt-1 inline-block">
                                {{ category.slug }}
                            </div>
                            <div v-if="category.description" class="text-sm text-gray-400 mt-2 max-w-xs truncate bg-gray-50 px-2 py-1 rounded-md">
                                {{ category.description }}
                            </div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap">
                    <span :class="getStatusBadgeClass(category.status)" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold capitalize shadow-sm">
                        <span class="w-2 h-2 rounded-full mr-2" :class="category.status === 'active' ? 'bg-green-400' : 'bg-red-400'"></span>
                        {{ category.status }}
                    </span>
                </td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-900">
                    <div class="flex items-center bg-gray-50 px-3 py-2 rounded-lg w-fit">
                        <svg class="mr-2 h-4 w-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                        <span class="font-semibold text-gray-700">{{ category.products_count || 0 }}</span>
                    </div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500">
                    <div class="flex items-center space-x-2">
                        <span v-if="category.parent_id" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-blue-100 to-blue-200 text-blue-800 shadow-sm">
                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                            </svg>
                            Subcategory
                        </span>
                        <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-gradient-to-r from-green-100 to-green-200 text-green-800 shadow-sm">
                            <svg class="mr-1 h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" />
                            </svg>
                            Parent Category
                        </span>
                        <span v-if="category.children && category.children.length > 0" class="inline-flex items-center px-2 py-1 rounded-md text-xs font-medium bg-gradient-to-r from-gray-100 to-gray-200 text-gray-700 shadow-sm">
                            {{ category.children.length }} subcategories
                        </span>
                    </div>
                </td>
                <td class="px-6 py-5 text-sm text-gray-900">
                    <div v-if="category.meta_title" class="max-w-xs truncate bg-gray-50 px-3 py-2 rounded-lg" :title="category.meta_title">
                        {{ category.meta_title }}
                    </div>
                    <span v-else class="text-gray-400 italic bg-gray-50 px-3 py-2 rounded-lg">No meta title</span>
                </td>
                <td class="px-6 py-5 text-sm text-gray-900">
                    <div v-if="category.meta_description" class="max-w-xs truncate bg-gray-50 px-3 py-2 rounded-lg" :title="category.meta_description">
                        {{ category.meta_description }}
                    </div>
                    <span v-else class="text-gray-400 italic bg-gray-50 px-3 py-2 rounded-lg">No meta description</span>
                </td>
                <td class="px-6 py-5 whitespace-nowrap text-sm text-gray-500">
                    <div class="bg-gray-50 px-3 py-2 rounded-lg font-medium">
                        {{ formatDate(category.created_at) }}
                    </div>
                </td>
                <td class="px-6 py-5 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex items-center justify-end space-x-3">
                        <button
                            @click="openEditModal(category)"
                            class="inline-flex items-center p-2 border border-transparent rounded-xl shadow-sm text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform hover:scale-105 transition-all duration-200"
                            title="Edit Category"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                            </svg>
                        </button>
                        <button
                            @click="deleteCategory(category)"
                            class="inline-flex items-center p-2 border border-transparent rounded-xl shadow-sm text-white bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transform hover:scale-105 transition-all duration-200"
                            title="Delete Category"
                        >
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                        </button>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>






    <!-- Empty State -->
    <div v-if="filteredCategories.length === 0" class="text-center py-12">
        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
        </svg>
        <h3 class="mt-2 text-sm font-medium text-gray-900">No categories found</h3>
        <p class="mt-1 text-sm text-gray-500">Get started by creating a new category.</p>
        <div class="mt-6">
            <button
                @click="openAddModal"
                class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                Add Category
            </button>
        </div>
    </div>

    <!-- Pagination -->
    <div v-if="filteredCategories.length > 0" class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between sm:hidden">
            <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </a>
            <a href="#" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Next
            </a>
        </div>
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">1</span> to <span class="font-medium">{{ filteredCategories.length }}</span> of <span class="font-medium">{{ showCetagoryList.length }}</span> results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#" class="bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">1</a>
                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">2</a>
                    <a href="#" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">3</a>
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                </nav>
            </div>
        </div>
    </div>
</div>



            </div>
        </div>

        <!-- Category Modal -->
        <modal
            v-model:show="showModal"
            :category="editingCategory"
            @category-saved="handleCategoryUpdated"
        />

        <parentCetagiryModel
            v-model:show="showCetagoryModal"
            :category="ParentCategory"
            @category-saved="handleCategoryUpdated"
        />
    </AuthenticatedLayout>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>


