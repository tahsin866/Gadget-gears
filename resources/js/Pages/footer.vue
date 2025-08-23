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
          <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                                             <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                            </div>
                            <span class="text-2xl font-bold">ShopHা্িািআub</span>
                        </div>
                        <p class="text-gray-400 mb-6 leading-relaxed">
                            Your ultimate destination for premium products. We bring you the best quality items from trusted brands worldwide.
                        </p>
                        <div class="flex gap-4">
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-indigo-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-indigo-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-indigo-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.741.099.12.112.225.085.345-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146C9.57 23.812 10.763 24.009 12.017 24.009c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001.012.001z"/>
                                </svg>
                            </a>
                            <a href="#" class="w-10 h-10 bg-gray-800 rounded-lg flex items-center justify-center hover:bg-indigo-600 transition-colors duration-200">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12.007 0C5.383 0 .007 5.376.007 12s5.376 12 12 12 12-5.376 12-12S18.631 0 12.007 0zm5.603 8.745c.085 1.886-.018 3.783-.018 3.783s-.018 1.896-.226 2.262c-.226.4-.792.712-1.314.712-.565 0-1.131-.312-1.357-.712-.208-.366-.226-2.262-.226-2.262s-.103-1.897-.018-3.783c.085-1.886.452-3.783.452-3.783s.367-1.897.792-2.262c.226-.4.792-.712 1.357-.712.522 0 1.088.312 1.314.712.208.365.226 2.376.226 2.376s.103 1.897.018 3.669z"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Quick Links</h3>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">About Us</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Contact</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">FAQ</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Shipping Info</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Returns</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Size Guide</a></li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Categories</h3>
                        <ul class="space-y-4">
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Electronics</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Fashion</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Home & Garden</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Sports</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Books</a></li>
                            <li><a href="#" class="text-gray-400 hover:text-white transition-colors duration-200">Toys</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Contact Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <svg class="w-5 h-5 text-indigo-400 mt-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <p class="text-gray-400">123 Commerce Street</p>
                                    <p class="text-gray-400">New York, NY 10001</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                                <p class="text-gray-400">+1 (555) 123-4567</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-400">support@shophub.com</p>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-5 h-5 text-indigo-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <p class="text-gray-400">Mon - Fri: 9AM - 6PM</p>
                                    <p class="text-gray-400">Sat - Sun: 10AM - 4PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Bottom -->
                <div class="border-t border-gray-800 mt-12 pt-8">
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                        <div class="text-gray-400 text-sm">
                            © 2024 ShopHub. All rights reserved.
                        </div>
                        <div class="flex items-center gap-6">
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Privacy Policy</a>
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Terms of Service</a>
                            <a href="#" class="text-gray-400 hover:text-white text-sm transition-colors duration-200">Cookie Policy</a>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-400 text-sm">We accept:</span>
                            <div class="flex gap-2">
                                <div class="w-8 h-5 bg-blue-600 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">V</span>
                                </div>
                                <div class="w-8 h-5 bg-red-600 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">M</span>
                                </div>
                                <div class="w-8 h-5 bg-blue-500 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">A</span>
                                </div>
                                <div class="w-8 h-5 bg-yellow-500 rounded flex items-center justify-center">
                                    <span class="text-white text-xs font-bold">P</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </footer>

</template>
