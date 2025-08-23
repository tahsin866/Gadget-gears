<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Navbar from '@/Pages/navbar.vue';
import HeroSection from './heroSection.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    auth: Object
});

// Gadget-focused Categories data
const categories = ref([
    { id: 1, name: 'Smartphones', icon: '📱', count: 245 },
    { id: 2, name: 'Laptops & Computers', icon: '💻', count: 189 },
    { id: 3, name: 'Audio & Headphones', icon: '🎧', count: 156 },
    { id: 4, name: 'Smart Home Devices', icon: '🏠', count: 132 },
    { id: 5, name: 'Gaming & Consoles', icon: '🎮', count: 123 },
    { id: 6, name: 'Cameras & Photography', icon: '📷', count: 98 },
    { id: 7, name: 'Wearable Tech', icon: '⌚', count: 87 },
    { id: 8, name: 'Accessories', icon: '🔌', count: 178 }
]);

const selectedCategory = ref(null);
const featuredCategories = computed(() => categories.value.slice(0, 4));
const viewAllMode = ref(false);

const toggleViewAll = () => {
    viewAllMode.value = !viewAllMode.value;
};

// Method to handle category selection
const selectCategory = (categoryId) => {
    selectedCategory.value = categoryId;
    // Additional logic here like filtering products
};

// Mock analytics data for enterprise view
const categoryAnalytics = {
    totalProducts: 1208,
    topCategory: 'Smartphones',
    growthRate: '+17%',
    newAdditions: 32
};
</script>

<template>
    <section id="categories" class="py-16 bg-gradient-to-b from-white to-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header Section with Analytics for Enterprise View -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-end mb-10 space-y-6 md:space-y-0">
                <div>
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-50 text-blue-700 mb-3">
                        <span class="mr-1.5 flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-blue-600"></span>
                        </span>
                        Browse Categories
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-2">Gadget Categories</h2>
                    <p class="text-lg text-gray-600 max-w-2xl">Discover our wide range of premium tech gadgets organized across specialized categories</p>
                </div>

                <div class="bg-white rounded-xl shadow-sm border border-gray-100 py-3 px-6 flex flex-wrap items-center justify-between gap-6">
                    <div class="flex items-center space-x-10">
                        <div>
                            <div class="text-sm text-gray-500 font-medium">Total Products</div>
                            <div class="text-xl font-bold text-gray-900">{{ categoryAnalytics.totalProducts }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 font-medium">Top Category</div>
                            <div class="text-xl font-bold text-blue-600">{{ categoryAnalytics.topCategory }}</div>
                        </div>
                    </div>
                    <div class="flex items-center space-x-10">
                        <div>
                            <div class="text-sm text-gray-500 font-medium">Growth Rate</div>
                            <div class="text-xl font-bold text-emerald-600">{{ categoryAnalytics.growthRate }}</div>
                        </div>
                        <div>
                            <div class="text-sm text-gray-500 font-medium">New This Week</div>
                            <div class="text-xl font-bold text-orange-600">{{ categoryAnalytics.newAdditions }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter & Sort Controls -->
            <div class="flex flex-wrap items-center justify-between mb-8 gap-4">
                <div class="inline-flex bg-white rounded-lg shadow-sm border border-gray-100">
                    <button class="px-4 py-2 text-sm font-medium text-gray-800 hover:text-blue-600 border-b-2 border-blue-600">
                        All Categories
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                        Featured
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                        Popular
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-gray-600 hover:text-blue-600 border-b-2 border-transparent">
                        New
                    </button>
                </div>

                <div class="flex items-center space-x-3">
                    <span class="text-sm text-gray-500">View:</span>
                    <div class="inline-flex bg-white rounded-lg shadow-sm border border-gray-100">
                        <button class="px-3 py-2 text-gray-400 hover:text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <button class="px-3 py-2 text-blue-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Category Grid with Enterprise-Level Design -->
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-4 gap-6">
                <div v-for="category in viewAllMode ? categories : featuredCategories"
                     :key="category.id"
                     @click="selectCategory(category.id)"
                     class="group cursor-pointer">
                    <div class="h-full bg-white rounded-xl p-6 flex flex-col transition-all duration-300 transform hover:-translate-y-1 hover:shadow-xl border border-gray-100"
                         :class="{ 'ring-2 ring-blue-500 shadow-md': selectedCategory === category.id }">

                        <!-- Category Header -->
                        <div class="flex justify-between items-start mb-4">
                            <div class="w-14 h-14 bg-blue-50 rounded-lg flex items-center justify-center text-2xl">
                                {{ category.icon }}
                            </div>
                            <span class="px-2.5 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">
                                {{ category.count }} Products
                            </span>
                        </div>

                        <!-- Category Info -->
                        <div class="mt-2">
                            <h3 class="text-lg font-bold text-gray-900 mb-1 group-hover:text-blue-600 transition-colors duration-300">
                                {{ category.name }}
                            </h3>
                            <div class="flex items-center text-gray-500 text-sm mb-4">
                                <span>{{ Math.round(category.count / categoryAnalytics.totalProducts * 100) }}% of products</span>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-100 rounded-full h-1.5 mb-3">
                                <div class="h-1.5 rounded-full transition-all duration-500"
                                     :class="selectedCategory === category.id ? 'bg-blue-600' : 'bg-blue-400'"
                                     :style="{ width: `${Math.min(Math.round(category.count / categoryAnalytics.totalProducts * 100), 100)}%` }"></div>
                            </div>
                        </div>

                        <div class="mt-auto pt-4">
                            <button class="w-full px-3 py-2 text-sm font-medium rounded-lg transition-colors duration-300 border flex items-center justify-center"
                                  :class="selectedCategory === category.id ? 'bg-blue-600 text-white border-blue-600' : 'text-blue-600 border-blue-200 hover:bg-blue-50'">
                                <span>Browse Products</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- View All/Less Toggle Button -->
            <div class="flex justify-center mt-10">
                <button
                    @click="toggleViewAll"
                    class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-300"
                >
                    <span>{{ viewAllMode ? 'View Less Categories' : 'View All Categories' }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-2"
                         :class="{ 'transform rotate-180': viewAllMode }"
                         fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>
</template>
