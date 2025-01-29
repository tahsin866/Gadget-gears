<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const categories = ref([
    {
        id: 1,
        name: 'Electronics',
        slug: 'electronics',
        description: 'Latest electronic gadgets and devices',
        products: 245,
        status: 'active',
        featured: true,
        icon: '📱',
        subcategories: [
            { id: 1, name: 'Smartphones', products: 85 },
            { id: 2, name: 'Laptops', products: 65 },
            { id: 3, name: 'Accessories', products: 95 }
        ]
    },
    // Add more categories
]);

const showAddModal = ref(false);
const showSubcategoryModal = ref(false);
const selectedCategory = ref(null);
const searchQuery = ref('');
</script>

<template>
    <Head title="Category Management" />

    <AuthenticatedLayout>
        <div class="py-6">
            <div class="mx-auto  px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="flex justify-between items-center mb-8">
                    <div>
                        <h2 class="text-2xl font-bold text-[#2973B2]">Category Management</h2>
                        <p class="text-gray-600">Organize and manage your product categories</p>
                    </div>
                    <div class="flex space-x-4">
                        <button @click="showAddModal = true"
                                class="bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white px-6 py-2 rounded-lg hover:opacity-90">
                            Add Category
                        </button>
                        <button class="border border-[#2973B2] text-[#2973B2] px-6 py-2 rounded-lg hover:bg-[#2973B2] hover:text-white">
                            Bulk Actions
                        </button>
                    </div>
                </div>

                <!-- Category Analytics -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div class="bg-gradient-to-br from-[#2973B2]/10 to-white p-6 rounded-xl border border-[#2973B2]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Total Categories</p>
                                <h3 class="text-2xl font-bold text-[#2973B2]">24</h3>
                            </div>
                            <span class="text-green-500 text-sm">+3 new</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#EB5A3C]/10 to-white p-6 rounded-xl border border-[#EB5A3C]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Active Categories</p>
                                <h3 class="text-2xl font-bold text-[#EB5A3C]">18</h3>
                            </div>
                            <span class="text-green-500 text-sm">75%</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#2973B2]/10 to-white p-6 rounded-xl border border-[#2973B2]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Featured Categories</p>
                                <h3 class="text-2xl font-bold text-[#2973B2]">8</h3>
                            </div>
                            <span class="text-blue-500 text-sm">Top</span>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-[#EB5A3C]/10 to-white p-6 rounded-xl border border-[#EB5A3C]/20">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm text-gray-600">Total Products</p>
                                <h3 class="text-2xl font-bold text-[#EB5A3C]">1,245</h3>
                            </div>
                            <span class="text-green-500 text-sm">+85</span>
                        </div>
                    </div>
                </div>

                <!-- Category Management Section -->
                <div class="bg-white rounded-md shadow-sm overflow-hidden">
                    <!-- Toolbar -->
                    <div class="p-3 border-b border-gray-200 bg-gradient-to-r from-[#2973B2] to-[#EB5A3C]">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-white">Category Database</h3>
                            <div class="flex space-x-3">
                                <button class="bg-white/20 text-white px-4 py-2  rounded-md hover:bg-white/30">
                                    Export Categories
                                </button>
                                <button class="bg-white/20 text-white px-4 py-2  rounded-md hover:bg-white/30">
                                    Import Categories
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Search and Filters -->
                    <div class="p-4 bg-gray-50 border-b grid grid-cols-1 md:grid-cols-4 gap-4">
                        <input
                            v-model="searchQuery"
                            type="text"
                            placeholder="Search categories..."
                            class="px-4 py-2  rounded-md border focus:outline-none focus:border-[#2973B2] col-span-2"
                        >
                        <select class="px-4 py-2  rounded-md border focus:outline-none focus:border-[#2973B2]">
                            <option>All Status</option>
                            <option>Active</option>
                            <option>Inactive</option>
                            <option>Featured</option>
                        </select>
                        <select class="px-4 py-2  rounded-md border focus:outline-none focus:border-[#2973B2]">
                            <option>Sort by</option>
                            <option>Name</option>
                            <option>Products Count</option>
                            <option>Date Created</option>
                        </select>
                    </div>

                    <!-- Categories Grid -->
                    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="category in categories"
                             :key="category.id"
                             class="border  rounded-md p-6 hover:shadow-md transition-all">
                            <div class="flex justify-between items-start mb-4">
                                <div class="flex items-center">
                                    <span class="text-3xl mr-3">{{ category.icon }}</span>
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ category.name }}</h4>
                                        <p class="text-sm text-gray-500">{{ category.products }} products</p>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button class="p-2 text-[#2973B2] hover:bg-[#2973B2]/10 rounded-lg">
                                        <span class="sr-only">Edit</span>
                                        📝
                                    </button>
                                    <button class="p-2 text-[#EB5A3C] hover:bg-[#EB5A3C]/10 rounded-lg">
                                        <span class="sr-only">Delete</span>
                                        🗑️
                                    </button>
                                </div>
                            </div>

                            <p class="text-sm text-gray-600 mb-4">{{ category.description }}</p>

                            <!-- Subcategories -->
                            <div class="space-y-2">
                                <div v-for="sub in category.subcategories"
                                     :key="sub.id"
                                     class="flex justify-between items-center text-sm p-2 bg-gray-50 rounded-lg">
                                    <span>{{ sub.name }}</span>
                                    <span class="text-gray-500">{{ sub.products }}</span>
                                </div>
                                <button @click="showSubcategoryModal = true; selectedCategory = category"
                                        class="text-sm text-[#2973B2] hover:text-[#EB5A3C]">
                                    + Add Subcategory
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="px-6 py-4 border-t border-gray-200 flex items-center justify-between">
                        <div class="text-sm text-gray-500">
                            Showing 1 to 9 of 24 categories
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

        <!-- Add Category Modal -->
        <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
            <div class="bg-white  rounded-md max-w-md w-full p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-semibold text-[#2973B2]">Add New Category</h3>
                    <button @click="showAddModal = false" class="text-gray-500 hover:text-gray-700">×</button>
                </div>

                <form class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Name</label>
                        <input type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700">Category Icon</label>
                        <input type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" class="rounded text-[#2973B2]">
                        <label class="ml-2 text-sm text-gray-700">Featured Category</label>
                    </div>

                    <div class="flex justify-end space-x-3 mt-6">
                        <button type="button" @click="showAddModal = false"
                                class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white rounded-lg hover:opacity-90">
                            Add Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
