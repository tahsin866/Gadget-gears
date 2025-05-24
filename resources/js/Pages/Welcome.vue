<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    auth: Object
});

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
const products = ref([
    {
        id: 1,
        name: "Premium Wireless Headphones",
        category: "Electronics",
        price: 299.99,
        originalPrice: 399.99,
        image: 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?w=400&h=400&fit=crop',
        rating: 4.8,
        reviewCount: 256,
        stock: 15,
        maxStock: 100,
        onSale: true,
        isInWishlist: false,
        badge: 'Best Seller'
    },
    {
        id: 2,
        name: "Smart Fitness Watch",
        category: "Electronics",
        price: 199.99,
        originalPrice: 249.99,
        image: 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&h=400&fit=crop',
        rating: 4.6,
        reviewCount: 189,
        stock: 8,
        maxStock: 50,
        onSale: true,
        isInWishlist: true,
        badge: 'Limited'
    },
    {
        id: 3,
        name: "Professional Camera Lens",
        category: "Electronics",
        price: 899.99,
        originalPrice: null,
        image: 'https://images.unsplash.com/photo-1606983340126-99ab4feaa64a?w=400&h=400&fit=crop',
        rating: 4.9,
        reviewCount: 94,
        stock: 25,
        maxStock: 30,
        onSale: false,
        isInWishlist: false,
        badge: 'New'
    },
    {
        id: 4,
        name: "Ergonomic Office Chair",
        category: "Home & Living",
        price: 449.99,
        originalPrice: 599.99,
        image: 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=400&h=400&fit=crop',
        rating: 4.7,
        reviewCount: 167,
        stock: 12,
        maxStock: 40,
        onSale: true,
        isInWishlist: false,
        badge: 'Sale'
    },
    {
        id: 5,
        name: "Minimalist Desk Lamp",
        category: "Home & Living",
        price: 89.99,
        originalPrice: 119.99,
        image: 'https://images.unsplash.com/photo-1507473885765-e6ed057f782c?w=400&h=400&fit=crop',
        rating: 4.5,
        reviewCount: 78,
        stock: 30,
        maxStock: 50,
        onSale: true,
        isInWishlist: false,
        badge: null
    },
    {
        id: 6,
        name: "Premium Coffee Maker",
        category: "Home & Living",
        price: 349.99,
        originalPrice: null,
        image: 'https://images.unsplash.com/photo-1495474472287-4d71bcdd2085?w=400&h=400&fit=crop',
        rating: 4.8,
        reviewCount: 203,
        stock: 18,
        maxStock: 35,
        onSale: false,
        isInWishlist: true,
        badge: 'Premium'
    },
    {
        id: 7,
        name: "Wireless Gaming Mouse",
        category: "Gaming",
        price: 79.99,
        originalPrice: 99.99,
        image: 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46?w=400&h=400&fit=crop',
        rating: 4.6,
        reviewCount: 145,
        stock: 22,
        maxStock: 60,
        onSale: true,
        isInWishlist: false,
        badge: 'Gaming'
    },
    {
        id: 8,
        name: "Bluetooth Speaker",
        category: "Electronics",
        price: 129.99,
        originalPrice: 159.99,
        image: 'https://images.unsplash.com/photo-1608043152269-423dbba4e7e1?w=400&h=400&fit=crop',
        rating: 4.4,
        reviewCount: 112,
        stock: 35,
        maxStock: 80,
        onSale: true,
        isInWishlist: false,
        badge: 'Popular'
    }
]);

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

const colors = ref([
    { id: 1, name: 'Black', code: '#000000' },
    { id: 2, name: 'White', code: '#FFFFFF' },
    { id: 3, name: 'Blue', code: '#3B82F6' },
    { id: 4, name: 'Red', code: '#EF4444' },
    { id: 5, name: 'Green', code: '#10B981' }
]);

const sizes = ref(['XS', 'S', 'M', 'L', 'XL', 'XXL']);

// Hero slider data
const slides = ref([
    {
        id: 1,
        title: "Discover Amazing Products",
        subtitle: "Shop the latest trends with unbeatable prices",
        description: "Explore our premium collection of electronics, fashion, and lifestyle products",
        image: "https://images.unsplash.com/photo-1441986300917-64674bd600d8?w=1200&h=600&fit=crop",
        buttonText: "Shop Now",
        buttonLink: "#products"
    },
    {
        id: 2,
        title: "New Collection Arrival",
        subtitle: "Explore our premium selection",
        description: "Get the latest gadgets and accessories with exclusive member discounts",
        image: "https://images.unsplash.com/photo-1560472354-b33ff0c44a43?w=1200&h=600&fit=crop",
        buttonText: "Explore Collection",
        buttonLink: "#categories"
    },
    {
        id: 3,
        title: "Special Offers",
        subtitle: "Up to 50% off on selected items",
        description: "Limited time deals on your favorite brands and products",
        image: "https://images.unsplash.com/photo-1607082348824-0a96f2a4b9da?w=1200&h=600&fit=crop",
        buttonText: "View Deals",
        buttonLink: "#deals"
    }
]);

const currentSlide = ref(0);
let slideInterval;

// Computed properties
const filteredProducts = computed(() => {
    let filtered = products.value;

    if (selectedCategory.value) {
        const categoryName = categories.value.find(c => c.id === selectedCategory.value)?.name;
        filtered = filtered.filter(product => product.category === categoryName);
    }

    if (searchQuery.value) {
        filtered = filtered.filter(product =>
            product.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            product.category.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }

    return filtered;
});

// Methods
const handleSearch = () => {
    showSearchResults.value = searchQuery.value.length > 0;
};

const toggleMobileMenu = () => {
    showMobileMenu.value = !showMobileMenu.value;
};

const toggleColor = (colorId) => {
    const index = filters.selectedColors.indexOf(colorId);
    if (index === -1) {
        filters.selectedColors.push(colorId);
    } else {
        filters.selectedColors.splice(index, 1);
    }
};

const toggleSize = (size) => {
    const index = filters.selectedSizes.indexOf(size);
    if (index === -1) {
        filters.selectedSizes.push(size);
    } else {
        filters.selectedSizes.splice(index, 1);
    }
};

const applyFilters = () => {
    console.log('Applying filters:', filters);
};

const resetFilters = () => {
    Object.assign(filters, {
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
};

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length;
};

const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length;
};

const addToCart = (product) => {
    console.log('Adding to cart:', product);
    // Implement cart functionality
};

const toggleWishlist = (product) => {
    product.isInWishlist = !product.isInWishlist;
    console.log('Wishlist toggled:', product);
};

const openQuickView = (product) => {
    console.log('Quick view:', product);
    // Implement quick view modal
};

// Lifecycle
onMounted(() => {
    slideInterval = setInterval(nextSlide, 5000);
});

onUnmounted(() => {
    clearInterval(slideInterval);
});

// Watch for filter changes
watch(filters, (newFilters) => {
    console.log('Filters updated:', newFilters);
}, { deep: true });
</script>

<template>
    <Head title="GadgetGears - Premium Electronics & Lifestyle Products" />

    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class=" px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">
                    <!-- Logo Section -->
                    <div class="flex items-center">
                        <Link href="/" class="flex items-center space-x-3">
                            <div class="w-12 h-12 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center">
                                <span class="text-white font-bold text-xl">GG</span>
                            </div>
                            <div class="hidden sm:block">
                                <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                                    GadgetGears
                                </h1>
                                <p class="text-xs text-gray-500">Premium Electronics</p>
                            </div>
                        </Link>
                    </div>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-8">
                        <Link href="/" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                            Home
                        </Link>

                        <!-- Categories Dropdown -->
                        <div class="relative group">
                            <button class="flex items-center text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                                Categories
                                <svg class="w-4 h-4 ml-1 transform group-hover:rotate-180 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>
                            <div class="absolute z-50 invisible group-hover:visible opacity-0 group-hover:opacity-100 transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 w-64 bg-white shadow-xl rounded-xl mt-2 border border-gray-100">
                                <div class="p-4">
                                    <div class="grid grid-cols-2 gap-2">
                                        <a v-for="category in categories.slice(0, 8)"
                                           :key="category.id"
                                           href="#"
                                           class="flex items-center space-x-2 p-2 rounded-lg hover:bg-indigo-50 transition-colors duration-200">
                                            <span class="text-lg">{{ category.icon }}</span>
                                            <div>
                                                                                         <div class="text-sm font-medium text-gray-700">{{ category.name }}</div>
                                                <div class="text-xs text-gray-500">{{ category.count }} items</div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <Link href="/new-arrivals" class="relative text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                            New Arrivals
                            <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full animate-pulse">New</span>
                        </Link>

                        <Link href="/deals" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                            Hot Deals
                        </Link>

                        <Link href="/offers" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                            Offers
                        </Link>
                    </div>

                    <!-- Search Box -->
                    <div class="hidden md:block relative">
                        <div class="relative">
                            <input
                                type="text"
                                v-model="searchQuery"
                                @input="handleSearch"
                                @focus="showSearchSuggestions = true"
                                @blur="setTimeout(() => showSearchSuggestions = false, 200)"
                                placeholder="Search products, brands..."
                                class="w-80 px-4 py-3 pl-12 pr-4 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-gray-50 transition-all duration-200"
                            >
                            <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M15 10a5 5 0 1 0-10 0 5 5 0 0 0 10 0z"/>
                                </svg>
                            </div>
                        </div>

                        <!-- Search Suggestions -->
                        <div v-if="showSearchSuggestions && searchQuery" class="absolute z-50 w-full bg-white shadow-xl rounded-xl mt-2 border border-gray-100 max-h-80 overflow-y-auto">
                            <div class="p-4">
                                <div class="text-sm text-gray-500 mb-3">Trending Searches</div>
                                <div class="space-y-2">
                                    <a href="#" class="block p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M15 10a5 5 0 1 0-10 0 5 5 0 0 0 10 0z"/>
                                            </svg>
                                            <span class="text-gray-700">Wireless Headphones</span>
                                        </div>
                                    </a>
                                    <a href="#" class="block p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M15 10a5 5 0 1 0-10 0 5 5 0 0 0 10 0z"/>
                                            </svg>
                                            <span class="text-gray-700">Smart Watch</span>
                                        </div>
                                    </a>
                                    <a href="#" class="block p-2 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M15 10a5 5 0 1 0-10 0 5 5 0 0 0 10 0z"/>
                                            </svg>
                                            <span class="text-gray-700">Gaming Mouse</span>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Auth and Cart Section -->
                    <div class="flex items-center space-x-4">
                        <!-- Auth Links -->
                        <div v-if="canLogin" class="hidden lg:flex items-center space-x-4">
                            <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link :href="route('login')" class="text-gray-700 hover:text-indigo-600 font-medium transition-colors duration-200">
                                    Login
                                </Link>
                                <Link v-if="canRegister" :href="route('register')" class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-2 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-medium shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                    Register
                                </Link>
                            </template>
                        </div>

                        <!-- Wishlist Button -->
                        <button class="relative p-3 hover:bg-gray-100 rounded-xl transition-colors duration-200 group">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-red-500 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">2</span>
                        </button>

                        <!-- Cart Button -->
                        <button class="relative p-3 hover:bg-gray-100 rounded-xl transition-colors duration-200 group">
                            <svg class="w-6 h-6 text-gray-600 group-hover:text-indigo-600 transition-colors duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            <span class="absolute -top-1 -right-1 bg-indigo-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">3</span>
                        </button>

                        <!-- Mobile Menu Button -->
                        <button @click="toggleMobileMenu" class="lg:hidden p-2 rounded-xl hover:bg-gray-100 transition-colors duration-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Mobile Menu -->
            <div v-if="showMobileMenu" class="lg:hidden bg-white border-t border-gray-200">
                <div class="px-4 py-4 space-y-4">
                    <div class="relative">
                        <input
                            type="text"
                            v-model="searchQuery"
                            placeholder="Search products..."
                            class="w-full px-4 py-3 pl-12 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500"
                        >
                        <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M15 10a5 5 0 1 0-10 0 5 5 0 0 0 10 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <Link href="/" class="block py-2 text-gray-700 hover:text-indigo-600 font-medium">Home</Link>
                        <Link href="/categories" class="block py-2 text-gray-700 hover:text-indigo-600 font-medium">Categories</Link>
                        <Link href="/new-arrivals" class="block py-2 text-gray-700 hover:text-indigo-600 font-medium">New Arrivals</Link>
                        <Link href="/deals" class="block py-2 text-gray-700 hover:text-indigo-600 font-medium">Hot Deals</Link>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="relative h-[70vh] overflow-hidden">
            <!-- Background Slider -->
            <div class="absolute inset-0">
                <div v-for="(slide, index) in slides"
                     :key="slide.id"
                     class="absolute inset-0 transition-all duration-1000 ease-in-out"
                     :class="currentSlide === index ? 'opacity-100 scale-100' : 'opacity-0 scale-105'">
                    <img :src="slide.image"
                         :alt="slide.title"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-r from-black/60 via-black/40 to-transparent"></div>
                </div>
            </div>

            <!-- Content -->
            <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
                <div class="flex items-center h-full">
                    <div class="w-full md:w-2/3 lg:w-1/2 text-white">
                        <div v-for="(slide, index) in slides"
                             :key="slide.id"
                             class="space-y-6 transition-all duration-700 ease-out"
                             :class="currentSlide === index ? 'translate-x-0 opacity-100' : 'translate-x-8 opacity-0'">
                            <div v-if="currentSlide === index">
                                <div class="inline-block px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full text-sm font-medium mb-4">
                                    ✨ Featured Collection
                                </div>
                                <h1 class="text-4xl md:text-6xl lg:text-7xl font-bold leading-tight mb-4">
                                    {{ slide.title }}
                                </h1>
                                <p class="text-xl md:text-2xl mb-2 text-gray-200">{{ slide.subtitle }}</p>
                                <p class="text-lg mb-8 text-gray-300 max-w-lg">{{ slide.description }}</p>

                                <!-- CTA Buttons -->
                                <div class="flex flex-wrap gap-4 mb-8">
                                    <button class="bg-white text-gray-900 px-8 py-4 rounded-xl font-semibold hover:bg-gray-100 transition-all duration-300 flex items-center gap-3 group shadow-xl">
                                        <span>{{ slide.buttonText }}</span>
                                        <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                        </svg>
                                    </button>
                                    <button class="border-2 border-white text-white px-8 py-4 rounded-xl font-semibold hover:bg-white hover:text-gray-900 transition-all duration-300 backdrop-blur-sm">
                                        Learn More
                                    </button>
                                </div>

                                <!-- Features -->
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                    <div class="flex items-center gap-3 group">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:bg-white/30 transition-colors duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold">Free Shipping</div>
                                            <div class="text-sm text-gray-300">On orders over $99</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 group">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:bg-white/30 transition-colors duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold">24/7 Support</div>
                                            <div class="text-sm text-gray-300">Expert assistance</div>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-3 group">
                                        <div class="w-12 h-12 bg-white/20 backdrop-blur-sm rounded-xl flex items-center justify-center group-hover:bg-white/30 transition-colors duration-300">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="font-semibold">Warranty</div>
                                            <div class="text-sm text-gray-300">2 year guarantee</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Slider Controls -->
                <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-4">
                    <button @click="prevSlide"
                            class="p-3 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <div class="flex gap-3">
                        <button v-for="(_, index) in slides"
                                :key="index"
                                @click="currentSlide = index"
                                class="w-3 h-3 rounded-full transition-all duration-300 transform hover:scale-125"
                                :class="currentSlide === index ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/70'">
                        </button>
                    </div>
                    <button @click="nextSlide"
                            class="p-3 rounded-full bg-white/20 backdrop-blur-sm hover:bg-white/30 transition-all duration-300 transform hover:scale-110">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </section>

        <!-- Categories Section -->
        <section id="categories" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Shop by Category</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover our wide range of premium products across different categories</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4">
                    <div v-for="category in categories"
                         :key="category.id"
                         @click="selectedCategory = category.id"
                         class="group cursor-pointer">
                        <div class="bg-gray-50 rounded-2xl p-6 text-center hover:bg-gradient-to-br hover:from-indigo-50 hover:to-purple-50 transition-all duration-300 transform hover:-translate-y-2 hover:shadow-xl"
                             :class="{ 'bg-gradient-to-br from-indigo-50 to-purple-50 shadow-lg': selectedCategory === category.id }">
                            <div class="text-4xl mb-3 transform group-hover:scale-110 transition-transform duration-300">
                                {{ category.icon }}
                            </div>
                            <h3 class="font-semibold text-gray-900 mb-1 group-hover:text-indigo-600 transition-colors duration-300">
                                {{ category.name }}
                            </h3>
                            <p class="text-sm text-gray-500">{{ category.count }} items</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <main id="products" class="py-16 bg-gray-50">
            <div class=" px-4 sm:px-6 lg:px-8">
                <div class="flex gap-8">
                    <!-- Filters Sidebar -->
                    <div class="hidden lg:block w-80 flex-shrink-0">
                        <div class="bg-white rounded-2xl shadow-lg p-6 sticky top-24">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-xl font-bold text-gray-900">Filters</h3>
                                <button @click="resetFilters" class="text-sm text-indigo-600 hover:text-indigo-700 font-medium">
                                    Reset All
                                </button>
                            </div>

                            <div class="space-y-8">
                                <!-- Price Range Filter -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Price Range</h4>
                                    <div class="space-y-4">
                                        <div class="flex gap-4">
                                            <div class="flex-1">
                                                <label class="text-sm text-gray-600 mb-1 block">Min Price</label>
                                                <input type="number"
                                                       v-model="filters.priceMin"
                                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            </div>
                                            <div class="flex-1">
                                                <label class="text-sm text-gray-600 mb-1 block">Max Price</label>
                                                <input type="number"
                                                       v-model="filters.priceMax"
                                                       class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Brand Filter -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Brands</h4>
                                    <div class="space-y-3 max-h-48 overflow-y-auto">
                                        <label v-for="brand in brands"
                                               :key="brand.id"
                                               class="flex items-center space-x-3 cursor-pointer group">
                                            <input type="checkbox"
                                                   v-model="filters.selectedBrands"
                                                   :value="brand.id"
                                                   class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="flex-1 text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">{{ brand.name }}</span>
                                            <span class="text-gray-500 text-sm">({{ brand.count }})</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Rating Filter -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Rating</h4>
                                    <div class="space-y-3">
                                        <label v-for="rating in [5,4,3,2,1]"
                                               :key="rating"
                                               class="flex items-center space-x-3 cursor-pointer group">
                                            <input type="radio"
                                                   v-model="filters.rating"
                                                   :value="rating"
                                                   class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <div class="flex items-center">
                                                <div class="flex text-yellow-400">
                                                    <svg v-for="i in 5"
                                                         :key="i"
                                                         class="w-4 h-4"
                                                         :class="{ 'text-gray-300': i > rating }"
                                                         fill="currentColor"
                                                         viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                </div>
                                                <span class="ml-2 text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">& Up</span>
                                            </div>
                                        </label>
                                    </div>
                                </div>

                                <!-- Availability Filter -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Availability</h4>
                                    <div class="space-y-3">
                                        <label class="flex items-center space-x-3 cursor-pointer group">
                                            <input type="checkbox"
                                                   v-model="filters.inStock"
                                                   class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">In Stock Only</span>
                                        </label>
                                        <label class="flex items-center space-x-3 cursor-pointer group">
                                            <input type="checkbox"
                                                   v-model="filters.onSale"
                                                   class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                            <span class="text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">On Sale</span>
                                        </label>
                                    </div>
                                </div>

                                <!-- Color Filter -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Colors</h4>
                                    <div class="flex flex-wrap gap-3">
                                        <button v-for="color in colors"
                                                :key="color.id"
                                                @click="toggleColor(color.id)"
                                                class="w-10 h-10 rounded-full border-2 focus:outline-none transition-all duration-200 transform hover:scale-110"
                                                :class="{ 'ring-2 ring-offset-2 ring-indigo-600 scale-110': filters.selectedColors.includes(color.id) }"
                                                :style="{ backgroundColor: color.code, borderColor: color.code === '#FFFFFF' ? '#e5e7eb' : color.code }"
                                                :title="color.name">
                                        </button>
                                    </div>
                                </div>

                                <!-- Size Filter -->
                                <div>
                                    <h4 class="text-lg font-semibold mb-4 text-gray-900">Sizes</h4>
                                    <div class="flex flex-wrap gap-2">
                                        <button v-for="size in sizes"
                                                :key="size"
                                                @click="toggleSize(size)"
                                                class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none transition-all duration-200 hover:border-indigo-500"
                                                :class="{ 'bg-indigo-600 text-white border-indigo-600': filters.selectedSizes.includes(size) }">
                                            {{ size }}
                                        </button>
                                    </div>
                                </div>

                                <!-- Filter Actions -->
                                <div class="flex gap-3 pt-6 border-t border-gray-200">
                                    <button @click="applyFilters"
                                                                                    class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-2 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-medium">
                                        Apply Filters
                                    </button>
                                    <button @click="resetFilters"
                                            class="px-4 py-2 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                                        Clear
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Products Grid -->
                    <div class="flex-1">
                        <!-- Products Header -->
                        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
                            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                                <div>
                                    <h2 class="text-2xl font-bold text-gray-900 mb-2">Featured Products</h2>
                                    <p class="text-gray-600">Showing {{ filteredProducts.length }} of {{ products.length }} products</p>
                                </div>

                                <div class="flex items-center gap-4">
                                    <!-- View Toggle -->
                                    <div class="flex bg-gray-100 rounded-lg p-1">
                                        <button @click="viewMode = 'grid'"
                                                class="p-2 rounded-md transition-colors duration-200"
                                                :class="{ 'bg-white shadow-sm': viewMode === 'grid' }">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                            </svg>
                                        </button>
                                        <button @click="viewMode = 'list'"
                                                class="p-2 rounded-md transition-colors duration-200"
                                                :class="{ 'bg-white shadow-sm': viewMode === 'list' }">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Sort Dropdown -->
                                    <div class="relative">
                                        <select v-model="sortBy"
                                                class="appearance-none bg-white border border-gray-200 rounded-lg px-4 py-2 pr-8 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                            <option value="featured">Featured</option>
                                            <option value="price-low">Price: Low to High</option>
                                            <option value="price-high">Price: High to Low</option>
                                            <option value="rating">Highest Rated</option>
                                            <option value="newest">Newest First</option>
                                            <option value="name">Name A-Z</option>
                                        </select>
                                        <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none">
                                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Mobile Filter Button -->
                                    <button @click="showMobileFilters = true"
                                            class="lg:hidden bg-gray-100 p-2 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.414A1 1 0 013 6.707V4z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Products Grid/List -->
                        <div v-if="viewMode === 'grid'"
                             class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                            <div v-for="product in paginatedProducts"
                                 :key="product.id"
                                 class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 group">

                                <!-- Product Image -->
                                <div class="relative overflow-hidden">
                                    <img :src="product.image"
                                         :alt="product.name"
                                         class="w-full h-64 object-cover group-hover:scale-110 transition-transform duration-500">

                                    <!-- Product Badges -->
                                    <div class="absolute top-4 left-4 flex flex-col gap-2">
                                        <span v-if="product.isNew"
                                              class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                            New
                                        </span>
                                        <span v-if="product.discount"
                                              class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                            -{{ product.discount }}%
                                        </span>
                                        <span v-if="product.isBestseller"
                                              class="bg-yellow-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                            Bestseller
                                        </span>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                        <button @click="toggleWishlist(product.id)"
                                                class="p-2 bg-white rounded-full shadow-lg hover:bg-red-50 transition-colors duration-200"
                                                :class="{ 'text-red-500': product.isWishlisted, 'text-gray-600': !product.isWishlisted }">
                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                            </svg>
                                        </button>
                                        <button class="p-2 bg-white rounded-full shadow-lg hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </button>
                                        <button class="p-2 bg-white rounded-full shadow-lg hover:bg-green-50 text-gray-600 hover:text-green-600 transition-colors duration-200">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2h4a1 1 0 110 2h-1v12a2 2 0 01-2 2H6a2 2 0 01-2-2V6H3a1 1 0 110-2h4zM9 6v10h2V6H9zm4 0v10h2V6h-2z"/>
                                            </svg>
                                        </button>
                                    </div>

                                    <!-- Quick Add to Cart -->
                                    <div class="absolute bottom-4 left-4 right-4 opacity-0 group-hover:opacity-100 transform translate-y-4 group-hover:translate-y-0 transition-all duration-300">
                                        <button @click="addToCart(product)"
                                                class="w-full bg-white/90 backdrop-blur-sm text-gray-900 py-3 rounded-xl font-semibold hover:bg-white transition-all duration-200 flex items-center justify-center gap-2">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                            </svg>
                                            Add to Cart
                                        </button>
                                    </div>
                                </div>

                                <!-- Product Info -->
                                <div class="p-6">
                                    <div class="mb-2">
                                        <span class="text-sm text-gray-500 font-medium">{{ product.brand }}</span>
                                    </div>
                                    <h3 class="text-lg font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors duration-200">
                                        {{ product.name }}
                                    </h3>

                                    <!-- Rating -->
                                    <div class="flex items-center gap-2 mb-3">
                                        <div class="flex text-yellow-400">
                                            <svg v-for="i in 5"
                                                 :key="i"
                                                 class="w-4 h-4"
                                                 :class="{ 'text-gray-300': i > product.rating }"
                                                 fill="currentColor"
                                                 viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                        <span class="text-sm text-gray-600">({{ product.reviews }})</span>
                                    </div>

                                    <!-- Price -->
                                    <div class="flex items-center gap-2 mb-4">
                                        <span class="text-2xl font-bold text-gray-900">${{ product.price }}</span>
                                        <span v-if="product.originalPrice"
                                              class="text-lg text-gray-500 line-through">${{ product.originalPrice }}</span>
                                    </div>

                                    <!-- Stock Status -->
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-2">
                                            <div class="w-2 h-2 rounded-full"
                                                 :class="product.stock > 0 ? 'bg-green-500' : 'bg-red-500'"></div>
                                            <span class="text-sm"
                                                  :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                                                {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
                                            </span>
                                        </div>

                                        <!-- Color Options -->
                                        <div v-if="product.colors" class="flex gap-1">
                                                                                <div v-for="color in product.colors.slice(0, 3)"
                                                 :key="color"
                                                 class="w-4 h-4 rounded-full border border-gray-200"
                                                 :style="{ backgroundColor: color }">
                                            </div>
                                            <span v-if="product.colors.length > 3"
                                                  class="text-xs text-gray-500 ml-1">+{{ product.colors.length - 3 }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- List View -->
                        <div v-else class="space-y-6">
                            <div v-for="product in paginatedProducts"
                                 :key="product.id"
                                 class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-all duration-300 group">
                                <div class="flex">
                                    <!-- Product Image -->
                                    <div class="relative w-48 h-48 flex-shrink-0 overflow-hidden">
                                        <img :src="product.image"
                                             :alt="product.name"
                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                                        <!-- Product Badges -->
                                        <div class="absolute top-4 left-4 flex flex-col gap-2">
                                            <span v-if="product.isNew"
                                                  class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                                New
                                            </span>
                                            <span v-if="product.discount"
                                                  class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-medium">
                                                -{{ product.discount }}%
                                            </span>
                                        </div>
                                    </div>

                                    <!-- Product Info -->
                                    <div class="flex-1 p-6">
                                        <div class="flex justify-between h-full">
                                            <div class="flex-1">
                                                <div class="mb-2">
                                                    <span class="text-sm text-gray-500 font-medium">{{ product.brand }}</span>
                                                </div>
                                                <h3 class="text-xl font-semibold text-gray-900 mb-3 group-hover:text-indigo-600 transition-colors duration-200">
                                                    {{ product.name }}
                                                </h3>

                                                <!-- Rating -->
                                                <div class="flex items-center gap-2 mb-3">
                                                    <div class="flex text-yellow-400">
                                                        <svg v-for="i in 5"
                                                             :key="i"
                                                             class="w-4 h-4"
                                                             :class="{ 'text-gray-300': i > product.rating }"
                                                             fill="currentColor"
                                                             viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                        </svg>
                                                    </div>
                                                    <span class="text-sm text-gray-600">({{ product.reviews }} reviews)</span>
                                                </div>

                                                <p class="text-gray-600 mb-4 line-clamp-2">{{ product.description }}</p>

                                                <!-- Stock Status -->
                                                <div class="flex items-center gap-2">
                                                    <div class="w-2 h-2 rounded-full"
                                                         :class="product.stock > 0 ? 'bg-green-500' : 'bg-red-500'"></div>
                                                    <span class="text-sm"
                                                          :class="product.stock > 0 ? 'text-green-600' : 'text-red-600'">
                                                        {{ product.stock > 0 ? `${product.stock} in stock` : 'Out of stock' }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- Price and Actions -->
                                            <div class="flex flex-col items-end justify-between ml-6">
                                                <div class="text-right mb-4">
                                                    <div class="text-2xl font-bold text-gray-900">${{ product.price }}</div>
                                                    <div v-if="product.originalPrice"
                                                         class="text-lg text-gray-500 line-through">${{ product.originalPrice }}</div>
                                                </div>

                                                <div class="flex flex-col gap-3">
                                                    <button @click="addToCart(product)"
                                                            class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-6 py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-medium flex items-center gap-2">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                                        </svg>
                                                        Add to Cart
                                                    </button>

                                                    <div class="flex gap-2">
                                                        <button @click="toggleWishlist(product.id)"
                                                                class="p-2 border border-gray-200 rounded-lg hover:bg-red-50 transition-colors duration-200"
                                                                :class="{ 'text-red-500 border-red-200': product.isWishlisted, 'text-gray-600': !product.isWishlisted }">
                                                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                                            </svg>
                                                        </button>
                                                        <button class="p-2 border border-gray-200 rounded-lg hover:bg-blue-50 text-gray-600 hover:text-blue-600 transition-colors duration-200">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <div v-if="totalPages > 1" class="mt-12 flex justify-center">
                            <div class="flex items-center gap-2">
                                <button @click="currentPage = Math.max(1, currentPage - 1)"
                                        :disabled="currentPage === 1"
                                        class="p-2 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                    </svg>
                                </button>

                                <button v-for="page in visiblePages"
                                        :key="page"
                                        @click="currentPage = page"
                                        class="px-4 py-2 rounded-lg border transition-colors duration-200"
                                        :class="currentPage === page
                                            ? 'bg-indigo-600 text-white border-indigo-600'
                                            : 'border-gray-200 hover:bg-gray-50'">
                                    {{ page }}
                                </button>

                                <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                                        :disabled="currentPage === totalPages"
                                        class="p-2 rounded-lg border border-gray-200 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <!-- Featured Brands Section -->
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Brands</h2>
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">Shop from the world's most trusted and popular brands</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-8">
                    <div v-for="brand in featuredBrands"
                         :key="brand.id"
                         class="group cursor-pointer">
                        <div class="bg-gray-50 rounded-2xl p-8 text-center hover:bg-white hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2">
                            <img :src="brand.logo"
                                 :alt="brand.name"
                                 class="w-16 h-16 mx-auto mb-4 object-contain group-hover:scale-110 transition-transform duration-300">
                            <h3 class="font-semibold text-gray-900 group-hover:text-indigo-600 transition-colors duration-300">
                                {{ brand.name }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1">{{ brand.productCount }} products</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Newsletter Section -->
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

        <!-- Footer -->
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
                            <span class="text-2xl font-bold">ShopHub</span>
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

        <!-- Mobile Filter Modal -->
        <div v-if="showMobileFilters"
             class="fixed inset-0 z-50 lg:hidden">
            <div class="fixed inset-0 bg-black bg-opacity-50"
                 @click="showMobileFilters = false"></div>
            <div class="fixed inset-y-0 right-0 w-full max-w-sm bg-white shadow-xl transform transition-transform duration-300">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Filters</h3>
                    <button @click="showMobileFilters = false"
                            class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="p-6 overflow-y-auto h-full pb-20">
                    <!-- Mobile filter content (same as desktop) -->
                    <div class="space-y-8">
                        <!-- Price Range Filter -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-900">Price Range</h4>
                            <div class="space-y-4">
                                <div class="flex gap-4">
                                    <div class="flex-1">
                                        <label class="text-sm text-gray-600 mb-1 block">Min Price</label>
                                        <input type="number"
                                               v-model="filters.priceMin"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-sm text-gray-600 mb-1 block">Max Price</label>
                                        <input type="number"
                                               v-model="filters.priceMax"
                                               class="w-full px-3 py-2 border border-gray-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-900">Brands</h4>
                            <div class="space-y-3 max-h-48 overflow-y-auto">
                                <label v-for="brand in brands"
                                       :key="brand.id"
                                       class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox"
                                           v-model="filters.selectedBrands"
                                           :value="brand.id"
                                           class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="flex-1 text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">{{ brand.name }}</span>
                                    <span class="text-gray-500 text-sm">({{ brand.count }})</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-900">Rating</h4>
                            <div class="space-y-3">
                                <label v-for="rating in [5,4,3,2,1]"
                                       :key="rating"
                                       class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="radio"
                                           v-model="filters.rating"
                                           :value="rating"
                                           class="text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            <svg v-for="i in 5"
                                                 :key="i"
                                                 class="w-4 h-4"
                                                 :class="{ 'text-gray-300': i > rating }"
                                                 fill="currentColor"
                                                 viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                            </svg>
                                        </div>
                                        <span class="ml-2 text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">& Up</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Availability Filter -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-900">Availability</h4>
                            <div class="space-y-3">
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox"
                                           v-model="filters.inStock"
                                           class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">In Stock Only</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer group">
                                    <input type="checkbox"
                                           v-model="filters.onSale"
                                           class="rounded text-indigo-600 focus:ring-indigo-500 border-gray-300">
                                    <span class="text-gray-700 group-hover:text-indigo-600 transition-colors duration-200">On Sale</span>
                                </label>
                            </div>
                        </div>

                        <!-- Color Filter -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-900">Colors</h4>
                            <div class="flex flex-wrap gap-3">
                                <button v-for="color in colors"
                                        :key="color.id"
                                        @click="toggleColor(color.id)"
                                        class="w-10 h-10 rounded-full border-2 focus:outline-none transition-all duration-200 transform hover:scale-110"
                                        :class="{ 'ring-2 ring-offset-2 ring-indigo-600 scale-110': filters.selectedColors.includes(color.id) }"
                                        :style="{ backgroundColor: color.code, borderColor: color.code === '#FFFFFF' ? '#e5e7eb' : color.code }"
                                        :title="color.name">
                                </button>
                            </div>
                        </div>

                        <!-- Size Filter -->
                        <div>
                            <h4 class="text-lg font-semibold mb-4 text-gray-900">Sizes</h4>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="size in sizes"
                                        :key="size"
                                        @click="toggleSize(size)"
                                        class="px-4 py-2 border border-gray-200 rounded-lg focus:outline-none transition-all duration-200 hover:border-indigo-500"
                                        :class="{ 'bg-indigo-600 text-white border-indigo-600': filters.selectedSizes.includes(size) }">
                                    {{ size }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Filter Actions -->
                <div class="absolute bottom-0 left-0 right-0 bg-white border-t border-gray-200 p-6">
                    <div class="flex gap-3">
                        <button @click="resetFilters"
                                class="flex-1 px-4 py-3 border border-gray-200 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200 font-medium">
                            Clear All
                        </button>
                        <button @click="applyFilters"
                                class="flex-1 bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-4 py-3 rounded-lg hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-medium">
                            Apply Filters
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Cart Sidebar -->
        <div v-if="showCart"
             class="fixed inset-0 z-50">
            <div class="fixed inset-0 bg-black bg-opacity-50"
                 @click="showCart = false"></div>
            <div class="fixed inset-y-0 right-0 w-full max-w-md bg-white shadow-xl transform transition-transform duration-300">
                <div class="flex items-center justify-between p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Shopping Cart ({{ cartItems.length }})</h3>
                    <button @click="showCart = false"
                            class="p-2 text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div v-if="cartItems.length === 0" class="flex flex-col items-center justify-center h-64">
                    <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <p class="text-gray-500 text-lg mb-2">Your cart is empty</p>
                    <p class="text-gray-400 text-sm">Add some products to get started</p>
                </div>

                <div v-else class="flex flex-col h-full">
                    <div class="flex-1 overflow-y-auto p-6">
                        <div class="space-y-4">
                            <div v-for="item in cartItems"
                                 :key="item.id"
                                 class="flex gap-4 bg-gray-50 rounded-xl p-4">
                                <img :src="item.image"
                                     :alt="item.name"
                                     class="w-16 h-16 object-cover rounded-lg">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-900 mb-1">{{ item.name }}</h4>
                                    <p class="text-sm text-gray-600 mb-2">${{ item.price }}</p>
                                    <div class="flex items-center gap-3">
                                        <div class="flex items-center border border-gray-200 rounded-lg">
                                            <button @click="updateCartQuantity(item.id, item.quantity - 1)"
                                                    class="p-1 hover:bg-gray-100 rounded-l-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                                                </svg>
                                            </button>
                                            <span class="px-3 py-1 text-sm">{{ item.quantity }}</span>
                                            <button @click="updateCartQuantity(item.id, item.quantity + 1)"
                                                    class="p-1 hover:bg-gray-100 rounded-r-lg">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                     <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <button @click="removeFromCart(item.id)"
                                                class="text-red-500 hover:text-red-700 p-1">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Footer -->
                    <div class="border-t border-gray-200 p-6">
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-lg font-semibold text-gray-900">Total:</span>
                            <span class="text-2xl font-bold text-gray-900">${{ cartTotal }}</span>
                        </div>
                        <div class="space-y-3">
                            <button class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-3 rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all duration-200 font-semibold">
                                Checkout
                            </button>
                            <button @click="showCart = false"
                                    class="w-full border border-gray-200 text-gray-700 py-3 rounded-xl hover:bg-gray-50 transition-colors duration-200 font-medium">
                                Continue Shopping
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification Toast -->
        <div v-if="showNotification"
             class="fixed top-4 right-4 z-50 bg-white rounded-xl shadow-lg border border-gray-200 p-4 max-w-sm transform transition-all duration-300"
             :class="{ 'translate-x-full opacity-0': !showNotification }">
            <div class="flex items-start gap-3">
                <div class="flex-shrink-0">
                    <svg v-if="notification.type === 'success'"
                         class="w-6 h-6 text-green-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg v-else-if="notification.type === 'error'"
                         class="w-6 h-6 text-red-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <svg v-else
                         class="w-6 h-6 text-blue-500"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <h4 class="font-semibold text-gray-900 mb-1">{{ notification.title }}</h4>
                    <p class="text-sm text-gray-600">{{ notification.message }}</p>
                </div>
                <button @click="showNotification = false"
                        class="flex-shrink-0 text-gray-400 hover:text-gray-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Loading Overlay -->
        <div v-if="loading"
             class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white rounded-2xl p-8 flex flex-col items-center">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600 mb-4"></div>
                <p class="text-gray-600 font-medium">Loading...</p>
            </div>
        </div>
    </div>
</template>





