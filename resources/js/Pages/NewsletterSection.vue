<script setup>

import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted } from 'vue';


// Categories data
const categories = ref([
    { id: 1, name: 'Electronics', icon: '📱', count: 245 },
    { id: 2, name: 'Fashion', icon: '👕', count: 189 },
    { id: 3, name: 'Home & Living', icon: '🏠', count: 156 },
    { id: 4, name: 'Books', icon: '📚', count: 98 },
    { id: 5, name: 'Sports', icon: '⚽', count: 134 },
    { id: 6, name: 'Beauty', icon: '💄', count: 87 },
    { id: 7, name: 'Automotive', icon: '🚗', count: 76 },
    { id: 8, name: 'Gaming', icon: '🎮', count: 123 }
]);

// Products data


// State management
const selectedCategory = ref(null);
const showSearchResults = ref(false);
const searchQuery = ref('');
const showMobileMenu = ref(false);
const showSearchSuggestions = ref(false);

// Filter state
const filters = reactive({
    priceMin: 0,
    priceMax: 1000,
    priceRange: [0, 1000],
    selectedBrands: [],
    rating: null,
    inStock: false,
    onSale: false,
    selectedColors: [],
    selectedSizes: []
});

// Sample filter data
const brands = ref([
    { id: 1, name: 'Apple', count: 45 },
    { id: 2, name: 'Samsung', count: 38 },
    { id: 3, name: 'Sony', count: 29 },
    { id: 4, name: 'LG', count: 22 },
    { id: 5, name: 'Nike', count: 67 }
]);










const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value;
};












// Watch for filter changes
watch(filters, (newFilters) => {
    console.log('Filters updated:', newFilters);
}, { deep: true });


</script>
<template>
       <section class="py-16 bg-gradient-to-r from-indigo-600 to-purple-600">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Stay Updated</h2>
                    <p class="text-xl text-indigo-100 mb-8 max-w-2xl mx-auto">
                        Subscribe to our newsletter and get exclusive deals, new product announcements, and more!
                    </p>

                    <div class="max-w-md mx-auto">
                        <div class="flex gap-4">
                            <input type="email"
                                   v-model="newsletterEmail"
                                   placeholder="Enter your email address"
                                   class="flex-1 px-6 py-4 rounded-xl border-0 focus:outline-none focus:ring-2 focus:ring-white/50 text-gray-900">
                            <button @click="subscribeNewsletter"
                                    class="bg-white text-indigo-600 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-colors duration-200 flex items-center gap-2">
                                <span>Subscribe</span>
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                </svg>
                            </button>
                        </div>
                        <p class="text-indigo-100 text-sm mt-4">
                            We respect your privacy. Unsubscribe at any time.
                        </p>
                    </div>
                </div>
            </div>
        </section>

</template>