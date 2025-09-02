<script setup>
import { ref, computed, onMounted } from 'vue';
import { Link } from '@inertiajs/vue3';
import axios from 'axios';

// State
const showMobileMenu = ref(false);
const showUserMenu = ref(false);
const showCategoryMenu = ref(false);
const showSearchSuggestions = ref(false);
const categories = ref([]);
const searchQuery = ref('');
const cartItemCount = ref(0);
const searchSuggestions = ref([]);
const isSearchFocused = ref(false);

// Fetch categories from the API
onMounted(async () => {
  try {
    const response = await axios.get('/get-categories');
    categories.value = response.data;
  } catch (error) {
    console.error('Error fetching categories:', error);
  }
  fetchCartItemCount();
});

const fetchCartItemCount = async () => {
  try {
    const response = await axios.get('/api/cart/count');
    cartItemCount.value = response.data.count;
  } catch (error) {
    console.error('Error fetching cart item count:', error);
    cartItemCount.value = 0;
  }
};

// Search functionality
const handleSearch = async () => {
  if (searchQuery.value.length > 2) {
    try {
      const response = await axios.get(`/api/search/suggestions?q=${searchQuery.value}`);
      searchSuggestions.value = response.data;
      showSearchSuggestions.value = true;
    } catch (error) {
      console.error('Error fetching search suggestions:', error);
    }
  } else {
    showSearchSuggestions.value = false;
  }
};

const selectSuggestion = (suggestion) => {
  searchQuery.value = suggestion;
  showSearchSuggestions.value = false;
  performSearch();
};

const performSearch = () => {
  if (searchQuery.value.trim()) {
    // Navigate to search results page
    window.location.href = `/search?q=${encodeURIComponent(searchQuery.value)}`;
  }
};

// Toggles
const toggleMobileMenu = () => showMobileMenu.value = !showMobileMenu.value;
const toggleUserMenu = () => showUserMenu.value = !showUserMenu.value;
const toggleCategoryMenu = () => showCategoryMenu.value = !showCategoryMenu.value;

// Close menus when clicking outside
const closeCategoryMenu = (event) => {
  if (showCategoryMenu.value && !event.target.closest('.category-menu-button') && !event.target.closest('.category-menu-dropdown')) {
    showCategoryMenu.value = false;
  }
};

const closeUserMenu = (event) => {
  if (showUserMenu.value && !event.target.closest('.user-menu-button') && !event.target.closest('.user-menu-dropdown')) {
    showUserMenu.value = false;
  }
};

const closeSearchSuggestions = (event) => {
  if (!event.target.closest('.search-container')) {
    showSearchSuggestions.value = false;
    isSearchFocused.value = false;
  }
};

// Listen for clicks outside to close menus
onMounted(() => {
  document.addEventListener('click', closeCategoryMenu);
  document.addEventListener('click', closeUserMenu);
  document.addEventListener('click', closeSearchSuggestions);
});

// Clean up event listeners on unmount
import { onUnmounted } from 'vue';
onUnmounted(() => {
  document.removeEventListener('click', closeCategoryMenu);
  document.removeEventListener('click', closeUserMenu);
  document.removeEventListener('click', closeSearchSuggestions);
});

const user = computed(() => {
  return null;
});

const logout = () => {
  console.log('User logged out');
};

defineExpose({
  fetchCartItemCount
});
</script>

<template>
  <!-- Main Navigation -->
  <nav class="bg-white shadow-md sticky top-0 z-50 border-b border-gray-100">
    <!-- Top Header with Contact Info and Social Links -->
    <div class="bg-gradient-to-r from-blue-700 to-indigo-800 text-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center text-sm h-10">
          <!-- Contact Information -->
          <div class="flex items-center space-x-6">
            <span class="flex items-center">
              <i class="pi pi-phone mr-2 text-blue-200"></i>
              <span class="text-gray-100">01632399990</span>
            </span>
            <span class="hidden md:flex items-center">
              <i class="pi pi-envelope mr-2 text-blue-200"></i>
              <span class="text-gray-100">fyohub@gmail.com</span>
            </span>
          </div>

          <!-- Right Side: Shipping Info & Social Icons -->
          <div class="flex items-center space-x-6">
            <span class="hidden md:inline text-gray-100 text-xs">Free shipping on orders over 450tk</span>
            <div class="flex items-center space-x-4">
              <i class="pi pi-facebook cursor-pointer text-blue-200 hover:text-white transition-colors duration-200"></i>
              <i class="pi pi-twitter cursor-pointer text-blue-200 hover:text-white transition-colors duration-200"></i>
              <i class="pi pi-instagram cursor-pointer text-blue-200 hover:text-white transition-colors duration-200"></i>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Header with Logo, Search and Actions -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex justify-between items-center">
        <!-- Logo -->
        <Link href="/" class="flex items-center space-x-3 flex-shrink-0 group">
          <div class="relative">
            <img src="/Images/Fyohub.png" alt="Logo" class="h-12 sm:h-14 transition-transform duration-300 group-hover:scale-105">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-indigo-500 opacity-0 group-hover:opacity-10 rounded-lg transition-opacity duration-300"></div>
          </div>
          <div class="hidden sm:block">
            <span class="text-2xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-600 to-indigo-600">
              FYOHUB
            </span>
            <p class="text-xs text-gray-500 -mt-1">ELEVATE YOUR STYLE</p>
          </div>
        </Link>

        <!-- Enhanced Search Bar with Category Integration -->
        <div class="hidden lg:flex flex-grow max-w-3xl mx-8">
          <div class="relative w-full search-container">
            <div class="flex rounded-lg border-2 border-gray-200 hover:border-blue-300 focus-within:border-blue-500 focus-within:ring-4 focus-within:ring-blue-100 transition-all duration-200 bg-white shadow-sm hover:shadow">
              <!-- Category Dropdown Button -->
              <div class="relative">
                <button
                  @click="toggleCategoryMenu"
                  class="category-menu-button flex items-center px-4 py-3 bg-gray-50 hover:bg-gray-100 text-gray-700 rounded-l-lg border-r border-gray-200 transition-colors duration-200"
                >
                  <i class="pi pi-th-large mr-2 text-blue-600"></i>
                  <span class="text-sm font-medium">Categories</span>
                  <i class="pi pi-angle-down ml-2 text-xs transition-transform duration-200"
                     :class="{ 'transform rotate-180': showCategoryMenu }"></i>
                </button>

                <!-- Category Dropdown -->
                <div v-if="showCategoryMenu"
                     class="category-menu-dropdown absolute left-0 top-full mt-2 w-80 bg-white rounded-xl shadow-2xl border border-gray-100 z-30 overflow-hidden">
                  <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                    <h3 class="font-bold text-gray-800 text-lg">Browse Categories</h3>
                    <p class="text-sm text-gray-600">Find exactly what you're looking for</p>
                  </div>
                  <div class="max-h-96 overflow-y-auto">
                    <template v-for="(subCategories, parentCategory) in categories" :key="parentCategory">
                      <div class="border-b border-gray-50 last:border-b-0">
                        <div class="px-4 py-3 bg-gray-50 border-b border-gray-100">
                          <h4 class="text-sm font-bold text-blue-600 uppercase tracking-wide flex items-center">
                            <i class="pi pi-tag mr-2"></i>
                            {{ parentCategory }}
                          </h4>
                        </div>
                        <div class="py-2">
                          <Link
                            v-for="category in subCategories"
                            :key="category.id"
                            :href="`/category/${category.slug}`"
                            class="block px-6 py-2 text-sm text-gray-700 hover:bg-blue-50 hover:text-blue-700 transition-colors duration-200 flex items-center group"
                            @click="showCategoryMenu = false"
                          >
                            <i class="pi pi-circle text-xs mr-3 text-gray-400 group-hover:text-blue-500"></i>
                            {{ category.name }}
                            <i class="pi pi-arrow-right text-xs ml-auto opacity-0 group-hover:opacity-100 transition-opacity duration-200"></i>
                          </Link>
                        </div>
                      </div>
                    </template>
                  </div>
                  <div class="p-4 bg-gray-50 border-t border-gray-100">
                    <Link href="/categories" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center">
                      <i class="pi pi-external-link mr-2"></i>
                      View All Categories
                    </Link>
                  </div>
                </div>
              </div>

              <!-- Search Input -->
              <div class="flex-1 relative">
                <input
                  type="text"
                  v-model="searchQuery"
                  @input="handleSearch"
                  @focus="isSearchFocused = true"
                  @keydown.enter="performSearch"
                  placeholder="Search for products, brands, and more..."
                  class="w-full py-3 px-4 border-0 focus:outline-none text-gray-700 placeholder-gray-400"
                />

                <!-- Search Suggestions -->
                <div v-if="showSearchSuggestions && searchSuggestions.length > 0"
                     class="absolute top-full left-0 right-0 bg-white border border-gray-200 rounded-b-lg shadow-xl z-20 max-h-64 overflow-y-auto animate-fadeIn">
                  <div class="p-2">
                    <div class="text-xs text-gray-500 uppercase font-semibold px-3 py-2">Suggestions</div>
                    <button
                      v-for="suggestion in searchSuggestions"
                      :key="suggestion"
                      @click="selectSuggestion(suggestion)"
                      class="block w-full text-left px-3 py-2 hover:bg-blue-50 rounded text-sm transition-colors duration-150"
                    >
                      <i class="pi pi-search text-gray-400 mr-2"></i>
                      {{ suggestion }}
                    </button>
                  </div>
                </div>
              </div>

              <!-- Search Button -->
              <button
                @click="performSearch"
                class="px-6 py-3 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white rounded-r-lg transition-all duration-200 flex items-center"
              >
                <i class="pi pi-search text-lg"></i>
                <span class="hidden xl:inline ml-2 font-medium">Search</span>
              </button>
            </div>
          </div>
        </div>

        <!-- Right Side Actions -->
        <div class="flex items-center space-x-4 sm:space-x-6">
          <!-- Wishlist -->
          <Link href="/wishlist" class="relative group hidden sm:block">
            <div class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
              <i class="pi pi-heart text-xl text-gray-600 group-hover:text-red-500 transition-colors duration-200"></i>
            </div>
            <span class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white text-xs rounded-full flex items-center justify-center font-medium shadow-md">
              0
            </span>
          </Link>

          <!-- Cart -->
          <Link href="/cart" class="relative group">
            <div class="p-2 rounded-full hover:bg-gray-100 transition-colors duration-200">
              <i class="pi pi-shopping-cart text-xl text-gray-600 group-hover:text-blue-600 transition-colors duration-200"></i>
            </div>
            <span v-if="cartItemCount > 0" class="absolute -top-1 -right-1 w-6 h-6 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center font-bold border-2 border-white animate-pulse">
              {{ cartItemCount }}
            </span>
          </Link>

          <!-- Google Login -->
          <a v-if="!user" :href="route('auth.google')" class="hidden lg:flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition-all duration-200 shadow hover:shadow-md">
            <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
              <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
              <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
              <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
              <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
            </svg>
            <span class="hidden xl:inline">Sign in with Google</span>
            <span class="xl:hidden">Google</span>
          </a>

          <!-- User Menu -->
          <div class="relative hidden lg:block">
            <button
              @click="toggleUserMenu"
              class="user-menu-button flex items-center space-x-2 p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 group"
            >
              <div class="relative">
                <img
                  v-if="user && user.avatar"
                  :src="user.avatar"
                  alt="User Avatar"
                  class="h-10 w-10 rounded-full object-cover border-2 border-gray-200 group-hover:border-blue-300 transition-all duration-200"
                >
                <div
                  v-else
                  class="h-10 w-10 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center"
                >
                  <i class="pi pi-user text-white"></i>
                </div>
                <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-green-500 border-2 border-white rounded-full"></div>
              </div>
              <div class="hidden xl:block text-left">
                <div class="text-sm font-medium text-gray-900">{{ user ? user.name.split(' ')[0] : 'Account' }}</div>
                <div class="text-xs text-gray-500">{{ user ? 'Manage Account' : 'Sign in' }}</div>
              </div>
              <i class="pi pi-angle-down text-sm text-gray-500 group-hover:text-gray-700 transition-colors duration-200"></i>
            </button>

            <!-- Enhanced User Dropdown -->
            <div
              v-if="showUserMenu"
              class="user-menu-dropdown absolute right-0 mt-2 w-64 bg-white rounded-xl shadow-2xl border border-gray-100 z-20 overflow-hidden transform origin-top-right transition-all duration-200"
            >
              <template v-if="user">
                <div class="p-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
                  <div class="flex items-center space-x-3">
                    <img :src="user.avatar" alt="User Avatar" class="h-12 w-12 rounded-full object-cover">
                    <div>
                      <div class="font-semibold text-gray-900">{{ user.name }}</div>
                      <div class="text-sm text-gray-600">{{ user.email }}</div>
                    </div>
                  </div>
                </div>
                <div class="py-2">
                  <Link href="/profile" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    <i class="pi pi-user mr-3 text-blue-500"></i>
                    My Profile
                  </Link>
                  <Link href="/orders" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    <i class="pi pi-shopping-bag mr-3 text-green-500"></i>
                    My Orders
                  </Link>
                  <Link href="/settings" class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-gray-50 transition-colors duration-200">
                    <i class="pi pi-cog mr-3 text-gray-500"></i>
                    Settings
                  </Link>
                </div>
                <div class="border-t border-gray-100">
                  <button @click="logout" class="flex items-center w-full px-4 py-3 text-sm text-red-600 hover:bg-red-50 transition-colors duration-200">
                    <i class="pi pi-sign-out mr-3"></i>
                    Logout
                  </button>
                </div>
              </template>
              <template v-else>
                <div class="p-4">
                  <div class="text-center mb-4">
                    <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-3">
                      <i class="pi pi-user text-2xl text-white"></i>
                    </div>
                    <h3 class="font-semibold text-gray-900">Welcome!</h3>
                    <p class="text-sm text-gray-600">Sign in to access your account</p>
                  </div>
              
                  <div class="mt-4 pt-4 border-t border-gray-100">
                    <a :href="route('auth.google')" class="flex items-center justify-center w-full px-4 py-2 text-sm text-red-600 border border-red-200 rounded-lg hover:bg-red-50 transition-colors duration-200">
                      <svg class="w-4 h-4 mr-2" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                      </svg>
                      Continue with Google
                    </a>
                  </div>
                </div>
              </template>
            </div>
          </div>

          <!-- Mobile Menu Button -->
          <button
            @click="toggleMobileMenu"
            class="lg:hidden p-2 rounded-full hover:bg-gray-100 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500"
          >
            <i class="pi pi-bars text-2xl text-gray-600"></i>
          </button>
        </div>
      </div>
    </div>

    <!-- Navigation Links -->
    <div class="hidden lg:block bg-gradient-to-r from-gray-50 to-gray-100 border-t border-gray-200 shadow-inner">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between py-3">
          <!-- Main Navigation Links -->
          <div class="flex items-center space-x-6">
            <Link href="/" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-lg hover:bg-white transition-all duration-200">
              <i class="pi pi-home mr-2"></i>
              Home
            </Link>
            <Link href="/new-arrivals" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-lg hover:bg-white transition-all duration-200">
              <i class="pi pi-star mr-2"></i>
              New Arrivals
            </Link>
            <Link href="/featured" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-lg hover:bg-white transition-all duration-200">
              <i class="pi pi-thumbs-up mr-2"></i>
              Featured
            </Link>
            <Link href="/brands" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-lg hover:bg-white transition-all duration-200">
              <i class="pi pi-tags mr-2"></i>
              Brands
            </Link>
          </div>

          <!-- Special Deal Link -->
          <Link href="/deals" class="flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-red-500 to-pink-500 rounded-lg hover:from-red-600 hover:to-pink-600 transition-all duration-200 shadow transform hover:-translate-y-px">
            <i class="pi pi-bolt mr-2"></i>
            Today's Deals
            <span class="ml-2 bg-white text-red-600 text-xs px-2 py-0.5 rounded-full font-bold">HOT</span>
          </Link>

          <!-- Support Link -->
          <Link href="/customer-service" class="flex items-center px-3 py-2 text-sm font-medium text-gray-700 hover:text-blue-600 rounded-lg hover:bg-white transition-all duration-200">
            <i class="pi pi-headphones mr-2"></i>
            Support
          </Link>
        </div>
      </div>
    </div>

    <!-- Mobile Menu Overlay -->
    <div v-if="showMobileMenu" class="lg:hidden fixed inset-0 bg-black bg-opacity-50 z-50 backdrop-blur-sm transition-opacity duration-300">
      <!-- Mobile Menu Slide Panel -->
      <div class="fixed inset-y-0 left-0 w-[280px] max-w-sm bg-white shadow-2xl transform transition-transform duration-300 ease-in-out overflow-y-auto">
        <!-- Mobile Header -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 p-6 text-white">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Menu</h2>
            <button
              @click="toggleMobileMenu"
              class="p-2 rounded-full hover:bg-white/20 transition-colors duration-200"
            >
              <i class="pi pi-times text-xl"></i>
            </button>
          </div>

          <!-- Mobile Search -->
          <div class="relative">
            <input
              type="text"
              v-model="searchQuery"
              @keydown.enter="performSearch"
              placeholder="Search products..."
              class="w-full py-3 pl-12 pr-4 border-0 rounded-lg text-gray-900 placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-white/50"
            />
            <div class="absolute inset-y-0 left-0 flex items-center justify-center w-12">
              <i class="pi pi-search text-gray-500"></i>
            </div>
            <button
              @click="performSearch"
              class="absolute inset-y-0 right-0 flex items-center justify-center w-12"
            >
              <i class="pi pi-arrow-right text-blue-600"></i>
            </button>
          </div>
        </div>

        <!-- Mobile Navigation -->
        <div class="p-6 space-y-6">
          <!-- User Section -->
          <div v-if="!user" class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4 space-y-3">
            <div class="text-center">
              <div class="w-16 h-16 bg-gradient-to-r from-blue-500 to-indigo-500 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="pi pi-user text-2xl text-white"></i>
              </div>
              <h3 class="font-semibold text-gray-900 mb-1">Welcome!</h3>
              <p class="text-sm text-gray-600">Sign in to your account</p>
            </div>
            <div class="space-y-2">
              <Link :href="route('login')" class="block w-full text-center py-3 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition-colors duration-200">
                Sign In
              </Link>
              <Link :href="route('register')" class="block w-full text-center py-3 border border-blue-600 text-blue-600 rounded-lg font-medium hover:bg-blue-50 transition-colors duration-200">
                Create Account
              </Link>
              <a :href="route('auth.google')" class="flex items-center justify-center w-full py-3 text-red-600 border border-red-200 rounded-lg font-medium hover:bg-red-50 transition-colors duration-200">
                <svg class="w-5 h-5 mr-2" viewBox="0 0 24 24">
                  <path fill="currentColor" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                  <path fill="currentColor" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                  <path fill="currentColor" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                  <path fill="currentColor" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Google
              </a>
            </div>
          </div>

          <!-- Logged in user section -->
          <div v-else class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-4">
            <div class="flex items-center space-x-3 mb-4">
              <img :src="user.avatar" alt="User Avatar" class="h-12 w-12 rounded-full object-cover">
              <div>
                <div class="font-semibold text-gray-900">{{ user.name }}</div>
                <div class="text-sm text-gray-600">{{ user.email }}</div>
              </div>
            </div>
            <div class="space-y-2">
              <Link href="/profile" class="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-white rounded-lg transition-colors duration-200">
                <i class="pi pi-user mr-3 text-blue-500"></i>
                My Profile
              </Link>
              <Link href="/orders" class="flex items-center w-full px-3 py-2 text-gray-700 hover:bg-white rounded-lg transition-colors duration-200">
                <i class="pi pi-shopping-bag mr-3 text-green-500"></i>
                My Orders
              </Link>
              <button @click="logout" class="flex items-center w-full px-3 py-2 text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                <i class="pi pi-sign-out mr-3"></i>
                Logout
              </button>
            </div>
          </div>

          <!-- Quick Actions -->
          <div class="grid grid-cols-2 gap-3">
            <Link href="/cart" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
              <div class="relative mb-2">
                <i class="pi pi-shopping-cart text-2xl text-blue-600"></i>
                <span v-if="cartItemCount > 0" class="absolute -top-2 -right-2 w-6 h-6 bg-blue-600 text-white text-xs rounded-full flex items-center justify-center font-bold">
                  {{ cartItemCount }}
                </span>
              </div>
              <span class="text-sm font-medium text-gray-700">Cart</span>
            </Link>
            <Link href="/wishlist" class="flex flex-col items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors duration-200">
              <i class="pi pi-heart text-2xl text-red-500 mb-2"></i>
              <span class="text-sm font-medium text-gray-700">Wishlist</span>
            </Link>
          </div>

          <!-- Main Navigation Links -->
          <div class="space-y-1">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 pl-1">Navigation</h3>
            <Link href="/" class="flex items-center px-4 py-3 text-gray-800 font-medium hover:bg-gray-50 rounded-xl transition-colors duration-200">
              <i class="pi pi-home mr-3 text-blue-500"></i>
              Home
            </Link>
            <Link href="/new-arrivals" class="flex items-center px-4 py-3 text-gray-800 font-medium hover:bg-gray-50 rounded-xl transition-colors duration-200">
              <i class="pi pi-star mr-3 text-yellow-500"></i>
              New Arrivals
            </Link>
            <Link href="/deals" class="flex items-center px-4 py-3 text-white font-medium bg-gradient-to-r from-red-500 to-pink-500 rounded-xl shadow-md">
              <i class="pi pi-bolt mr-3"></i>
              Today's Deals
              <span class="ml-auto bg-white text-red-600 text-xs px-2 py-0.5 rounded-full font-bold">HOT</span>
            </Link>
            <Link href="/brands" class="flex items-center px-4 py-3 text-gray-800 font-medium hover:bg-gray-50 rounded-xl transition-colors duration-200">
              <i class="pi pi-tags mr-3 text-green-500"></i>
              Brands
            </Link>
            <Link href="/customer-service" class="flex items-center px-4 py-3 text-gray-800 font-medium hover:bg-gray-50 rounded-xl transition-colors duration-200">
              <i class="pi pi-headphones mr-3 text-blue-500"></i>
              Support
            </Link>
          </div>

          <!-- Categories -->
          <div class="space-y-1">
            <h3 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-3 pl-1">Categories</h3>
            <template v-for="(subCategories, parentCategory) in categories" :key="parentCategory">
              <div class="mb-4">
                <div class="flex items-center px-4 py-2 bg-gray-50 rounded-lg mb-2">
                  <i class="pi pi-tag mr-2 text-blue-500"></i>
                  <h4 class="text-sm font-bold text-gray-700">{{ parentCategory }}</h4>
                </div>
                <div class="ml-4 space-y-1">
                  <Link
                    v-for="category in subCategories"
                    :key="category.id"
                    :href="`/category/${category.slug}`"
                    @click="showMobileMenu = false"
                    class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 rounded-lg transition-colors duration-200"
                  >
                    <i class="pi pi-circle text-xs mr-3 text-gray-400"></i>
                    {{ category.name }}
                  </Link>
                </div>
              </div>
            </template>
          </div>

          <!-- Contact Info -->
          <div class="bg-gray-50 rounded-xl p-4 mt-6">
            <h3 class="font-semibold text-gray-900 mb-3">Need Help?</h3>
            <div class="space-y-2 text-sm text-gray-600">
              <div class="flex items-center">
                <i class="pi pi-phone mr-2 text-blue-500"></i>
                01632399990
              </div>
              <div class="flex items-center">
                <i class="pi pi-envelope mr-2 text-blue-500"></i>
                fyohub@gmail.com
              </div>
              <div class="flex items-center space-x-3 mt-3">
                <i class="pi pi-facebook cursor-pointer hover:text-blue-600 transition-colors duration-200"></i>
                <i class="pi pi-twitter cursor-pointer hover:text-blue-600 transition-colors duration-200"></i>
                <i class="pi pi-instagram cursor-pointer hover:text-blue-600 transition-colors duration-200"></i>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </nav>
</template>
