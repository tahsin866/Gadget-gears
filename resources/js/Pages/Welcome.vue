<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted  } from 'vue';  // Import computed from vue instead of inertiajs
// import { ref } from 'vue';

const categories = ref([
    { id: 1, name: 'Electronics', icon: '📱' },
    { id: 2, name: 'Fashion', icon: '👕' },
    { id: 3, name: 'Home & Living', icon: '🏠' },
    { id: 4, name: 'Books', icon: '📚' },
    { id: 5, name: 'Sports', icon: '⚽' }
]);

const products = ref([
    {
        id: 1,
        name: 'Wireless Earbuds',
        price: 129.99,
        image: 'https://picsum.photos/400/400',
        category: 1,
        rating: 4.5
    },
    // Add more products here...
    {
        id: 4,
        name: 'Gaming Laptop',
        price: 1299.99,
        image: 'https://picsum.photos/400/403',
        category: 1,
        rating: 4.9
    },
    {
        id: 5,
        name: 'Smartphone',
        price: 899.99,
        image: 'https://picsum.photos/400/404',
        category: 1,
        rating: 4.7
    }
]);

const selectedCategory = ref(null);

// const filteredProducts = computed(() => {
//     if (!selectedCategory.value) return products.value;
//     return products.value.filter(product => product.category === selectedCategory.value);
// });


const filteredProducts = ref([
    {
        id: 1,
        name: "Product Name",
        category: "Electronics",
        price: 99.99,
        originalPrice: 129.99,
        image: 'https://picsum.photos/400/404',
        rating: 4.5,
        reviewCount: 128,
        stock: 8,
        maxStock: 100,
        onSale: true,
        isInWishlist: false
    },
    {
        id: 1,
        name: "Product Name",
        category: "Electronics",
        price: 99.99,
        originalPrice: 129.99,
        image: 'https://picsum.photos/400/404',
        rating: 4.5,
        reviewCount: 128,
        stock: 8,
        maxStock: 100,
        onSale: true,
        isInWishlist: false
    },
    {
        id: 1,
        name: "Product Name",
        category: "Electronics",
        price: 99.99,
        originalPrice: 129.99,
        image: 'https://picsum.photos/400/404',
        rating: 4.5,
        reviewCount: 128,
        stock: 8,
        maxStock: 100,
        onSale: true,
        isInWishlist: false
    },
    {
        id: 1,
        name: "Product Name",
        category: "Electronics",
        price: 99.99,
        originalPrice: 129.99,
        image: 'https://picsum.photos/400/404',
        rating: 4.5,
        reviewCount: 128,
        stock: 8,
        maxStock: 100,
        onSale: true,
        isInWishlist: false
    }

    // ... more products
])

defineProps({
    canLogin: Boolean,
    canRegister: Boolean
});

// Add these reactive variables
const showSearchResults = ref(false)
const searchQuery = ref('')
const showMobileMenu = ref(false)

// Add these functions
function handleSearch() {
    showSearchResults.value = searchQuery.value.length > 0
}

function toggleMobileMenu() {
    showMobileMenu.value = !showMobileMenu.value
}


const logoImage = ref('/Images/Gadget_gears.png')






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
})

// Sample data
const brands = ref([
    { id: 1, name: 'Brand A', count: 150 },
    { id: 2, name: 'Brand B', count: 120 },
    { id: 3, name: 'Brand C', count: 90 }
])

const colors = ref([
    { id: 1, name: 'Red', code: '#FF0000' },
    { id: 2, name: 'Blue', code: '#0000FF' },
    { id: 3, name: 'Green', code: '#00FF00' }
])

const sizes = ref(['XS', 'S', 'M', 'L', 'XL', 'XXL'])

const priceRange = reactive({
    min: 0,
    max: 1000
})

// Filter methods
const toggleColor = (colorId) => {
    const index = filters.selectedColors.indexOf(colorId)
    if (index === -1) {
        filters.selectedColors.push(colorId)
    } else {
        filters.selectedColors.splice(index, 1)
    }
}

const toggleSize = (size) => {
    const index = filters.selectedSizes.indexOf(size)
    if (index === -1) {
        filters.selectedSizes.push(size)
    } else {
        filters.selectedSizes.splice(index, 1)
    }
}

const applyFilters = () => {
    // Implement filter logic here
    console.log('Applying filters:', filters)
}

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
    })
}

// Watch for filter changes
watch(filters, (newFilters) => {
    console.log('Filters updated:', newFilters)
    // Implement filter update logic here
}, { deep: true })


const slides = ref([
    {
        id: 1,
        title: "Discover Amazing Products",
        description: "Shop the latest trends with unbeatable prices",
        image: "/images/slide1.jpg"
    },
    {
        id: 2,
        title: "New Collection Arrival",
        description: "Explore our premium selection of products",
        image: "/images/slide2.jpg"
    },
    {
        id: 3,
        title: "Special Offers",
        description: "Get up to 50% off on selected items",
        image: "/images/slide3.jpg"
    }
])

const currentSlide = ref(0)
let slideInterval

const nextSlide = () => {
    currentSlide.value = (currentSlide.value + 1) % slides.value.length
}

const prevSlide = () => {
    currentSlide.value = (currentSlide.value - 1 + slides.value.length) % slides.value.length
}

onMounted(() => {
    slideInterval = setInterval(nextSlide, 5000)
})

onUnmounted(() => {
    clearInterval(slideInterval)
})






</script>

<template>
    <Head title="ShopHub - Modern Shopping Experience" />

    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-md">
    <div class=" ">
        <div class="flex justify-between items-center h-16 px-5 bg-white shadow-md">
            <!-- Logo Section -->
            <div class="flex items-center">
                <img :src="logoImage" alt="Gadget Gears Logo" class="h-20 w-auto">
            </div>

            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
                <Link href="route('home')" class="text-gray-700 hover:text-indigo-600 transition">Home</Link>

                <!-- Categories Dropdown -->
                <div class="relative group">
                    <button class="flex items-center text-gray-700 hover:text-indigo-600 transition">
                        Categories
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div class="absolute z-50 hidden group-hover:block w-48 bg-white shadow-lg rounded-lg mt-2">
                        <div class="py-2">
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Electronics</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Accessories</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Gadgets</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Smart Home</a>
                        </div>
                    </div>
                </div>

                <Link href="route('new-arrivals')" class="relative text-gray-700 hover:text-indigo-600 transition">
                    New Arrivals
                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs px-2 py-1 rounded-full">New</span>
                </Link>

                <Link href="route('deals')" class="text-gray-700 hover:text-indigo-600 transition">Hot Deals</Link>

                <Link href="route('offers')" class="text-gray-700 hover:text-indigo-600 transition">Offers</Link>

                <Link href="route('voucher')" class="text-gray-700 hover:text-indigo-600 transition">Get Voucher</Link>

                <!-- Search Box with Advanced Features -->
                <div class="relative">
                    <input
                        type="text"
                        v-model="searchQuery"
                        @input="handleSearch"
                        @focus="showSearchSuggestions = true"
                        @blur="showSearchSuggestions = false"
                        placeholder="Search products..."
                        class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-600 w-64"
                    >
                    <button class="absolute right-2 top-2 text-gray-500 hover:text-indigo-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35M15 10a5 5 0 1 0-10 0 5 5 0 0 0 10 0z"/>
                        </svg>
                    </button>
                    <!-- Search Suggestions -->
                    <div v-if="showSearchSuggestions" class="absolute z-50 w-full bg-white shadow-lg rounded-lg mt-2">
                        <div class="py-2">
                            <div class="px-4 py-2 text-sm text-gray-500">Trending Searches</div>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Latest Smartphones</a>
                            <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-indigo-50">Wireless Earbuds</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Auth and Cart Section -->
            <div class="flex items-center space-x-4">
                <div v-if="canLogin" class="flex items-center space-x-4">
                    <Link v-if="$page.props.auth.user" :href="route('dashboard')" class="text-gray-700 hover:text-indigo-600 transition">
                        Dashboard
                    </Link>
                    <template v-else>
                        <Link :href="route('login')" class="text-gray-700 hover:text-indigo-600 transition">Login</Link>
                        <Link v-if="canRegister" :href="route('register')" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                            Register
                        </Link>
                    </template>
                </div>

                <!-- Wishlist Button -->
                <button class="p-2 hover:bg-gray-100 rounded-full relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">0</span>
                </button>

                <!-- Cart Button -->
                <button class="p-2 hover:bg-gray-100 rounded-full relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                    </svg>
                    <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs w-5 h-5 flex items-center justify-center rounded-full">0</span>
                </button>
            </div>
        </div>
    </div>
</nav>


        <!-- Hero Section -->
        <div class="relative h-[600px] overflow-hidden">
        <!-- Background Slider -->
        <div class="absolute inset-0">
            <div v-for="(slide, index) in slides"
                 :key="slide.id"
                 class="absolute inset-0 transition-opacity duration-500 ease-in-out"
                 :class="currentSlide === index ? 'opacity-100' : 'opacity-0'">
                <img :src="slide.image"
                     :alt="slide.title"
                     class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-r from-indigo-500/80 to-purple-600/80"></div>
            </div>
        </div>

        <!-- Content -->
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-full">
            <div class="flex items-center h-full">
                <div class="w-full md:w-1/2 text-white">
                    <div v-for="(slide, index) in slides"
                         :key="slide.id"
                         class="space-y-6 transition-all duration-500 ease-in-out transform"
                         :class="currentSlide === index ? 'translate-x-0 opacity-100' : 'translate-x-8 opacity-0'">
                        <h1 class="text-4xl md:text-6xl font-bold leading-tight">
                            {{ slide.title }}
                        </h1>
                        <p class="text-xl">{{ slide.description }}</p>

                        <!-- CTA Buttons -->
                        <div class="flex flex-wrap gap-4">
                            <button class="bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition-colors duration-300 flex items-center gap-2 group">
                                <span>Shop Now</span>
                                <svg class="w-5 h-5 transform transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </button>
                            <button class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white/10 transition-colors duration-300">
                                Learn More
                            </button>
                        </div>

                        <!-- Features -->
                        <div class="grid grid-cols-2 gap-4 mt-8">
                            <div class="flex items-center gap-2 group">
                                <svg class="w-6 h-6 transform transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                <span>Free Shipping</span>
                            </div>
                            <div class="flex items-center gap-2 group">
                                <svg class="w-6 h-6 transform transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>24/7 Support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Slider Controls -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex items-center gap-4">
                <button @click="prevSlide"
                        class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-colors duration-300 transform hover:scale-105">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>

                <div class="flex gap-2">
                    <button v-for="(_, index) in slides"
                            :key="index"
                            @click="currentSlide = index"
                            class="w-3 h-3 rounded-full transition-colors duration-300"
                            :class="currentSlide === index ? 'bg-white scale-110' : 'bg-white/50 hover:bg-white/70'">
                    </button>
                </div>

                <button @click="nextSlide"
                        class="p-2 rounded-full bg-white/20 hover:bg-white/30 transition-colors duration-300 transform hover:scale-105">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

        <!-- Main Content -->
        <main class=" mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex gap-8">
                <!-- Categories Sidebar -->
                <div class="w-64 flex-shrink-0">
                <div class="bg-white rounded-md shadow-lg p-6 sticky top-4">
                    <h2 class="text-xl font-semibold mb-4">Categories</h2>
                    <ul class="space-y-3">
                        <li v-for="category in categories"
                            :key="category.id"
                            @click="selectedCategory = category.id"
                            class="flex items-center space-x-3 cursor-pointer p-2 rounded-lg hover:bg-gray-50 transition"
                            :class="{ 'bg-indigo-50 text-indigo-600': selectedCategory === category.id }">
                            <span class="text-2xl">{{ category.icon }}</span>
                            <span>{{ category.name }}</span>
                        </li>
                    </ul>
                </div>
            </div>

                <!-- Products Grid -->
                <div class="flex-1">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div v-for="product in filteredProducts"
                 :key="product.id"
                 class="bg-white rounded-md shadow-lg overflow-hidden hover:shadow-xl transition group">
                <!-- Image Container with Overlay -->
                <div class="relative">
                    <img :src="product.image"
                         :alt="product.name"
                         class="w-full h-64 object-cover group-hover:scale-105 transition-transform duration-300">

                    <!-- Quick View Overlay -->
                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                        <div class="space-x-3">
                            <!-- Quick View Button -->
                            <button @click="openQuickView(product)"
                                    class="bg-white text-gray-800 p-2 rounded-md hover:bg-indigo-600 hover:text-white transition">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                            </button>
                            <!-- Wishlist Button -->
                            <button @click="toggleWishlist(product)"
                                    class="bg-white text-gray-800 p-2 rounded-md hover:bg-indigo-600 hover:text-white transition">
                                <svg class="w-6 h-6" :class="{ 'text-red-500': product.isInWishlist }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    <!-- Sale Badge -->
                    <div v-if="product.onSale"
                         class="absolute top-4 left-4 bg-red-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        SALE
                    </div>
                    <!-- Stock Status -->
                    <div v-if="product.stock < 5"
                         class="absolute top-4 right-4 bg-yellow-500 text-white px-3 py-1 rounded-full text-sm font-semibold">
                        Low Stock
                    </div>
                </div>

                <div class="p-6">
                    <!-- Category -->
                    <div class="text-sm text-indigo-600 mb-2">{{ product.category }}</div>

                    <!-- Title -->
                    <h3 class="text-xl font-semibold mb-2 hover:text-indigo-600 cursor-pointer">
                        {{ product.name }}
                    </h3>

                    <!-- Rating -->
                    <div class="flex items-center mb-4">
                        <div class="flex text-yellow-400">
                            <svg v-for="i in 5"
                                 :key="i"
                                 class="w-5 h-5"
                                 :class="{ 'text-gray-300': i > product.rating }"
                                 fill="currentColor"
                                 viewBox="0 0 20 20">
                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                            </svg>
                        </div>
                        <span class="ml-2 text-gray-600">{{ product.rating }}/5</span>
                        <span class="ml-2 text-gray-500">({{ product.reviewCount }} reviews)</span>
                    </div>

                    <!-- Price Section -->
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span v-if="product.originalPrice"
                                  class="text-gray-500 line-through text-sm">
                                ${{ product.originalPrice }}
                            </span>
                            <span class="text-2xl font-bold text-indigo-600">${{ product.price }}</span>
                        </div>

                        <!-- Add to Cart Button -->
                        <button @click="addToCart(product)"
                                class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            Add to Cart
                        </button>
                    </div>

                    <!-- Stock Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-sm mb-1">
                            <span>Stock</span>
                            <span>{{ product.stock }} left</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-indigo-600 h-2 rounded-full"
                                 :style="{ width: `${(product.stock / product.maxStock) * 100}%` }">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





    <div class="w-80 flex-shrink-0">
                <div class="bg-white rounded-md shadow-lg p-6 sticky top-4">
                    <div class="space-y-6">
                        <!-- Price Range Filter -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Price Range</h3>
                            <div class="space-y-4">
                                <div class="flex gap-4">
                                    <div class="flex-1">
                                        <label class="text-sm text-gray-600">Min</label>
                                        <input type="number"
                                               v-model="filters.priceMin"
                                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600">
                                    </div>
                                    <div class="flex-1">
                                        <label class="text-sm text-gray-600">Max</label>
                                        <input type="number"
                                               v-model="filters.priceMax"
                                               class="w-full px-3 py-2 border rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-600">
                                    </div>
                                </div>
                                <!-- Price Range Slider -->
                                <input type="range"
                                       v-model="filters.priceRange"
                                       :min="priceRange.min"
                                       :max="priceRange.max"
                                       class="w-full">
                            </div>
                        </div>

                        <!-- Brand Filter -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Brands</h3>
                            <div class="space-y-2 max-h-48 overflow-y-auto">
                                <label v-for="brand in brands"
                                       :key="brand.id"
                                       class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox"
                                           v-model="filters.selectedBrands"
                                           :value="brand.id"
                                           class="rounded text-indigo-600 focus:ring-indigo-500">
                                    <span>{{ brand.name }}</span>
                                    <span class="text-gray-500 text-sm">({{ brand.count }})</span>
                                </label>
                            </div>
                        </div>

                        <!-- Rating Filter -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Rating</h3>
                            <div class="space-y-2">
                                <label v-for="rating in [4,3,2,1]"
                                       :key="rating"
                                       class="flex items-center space-x-3 cursor-pointer">
                                    <input type="radio"
                                           v-model="filters.rating"
                                           :value="rating"
                                           class="text-indigo-600 focus:ring-indigo-500">
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
                                        <span class="ml-2">& Up</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <!-- Availability Filter -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Availability</h3>
                            <div class="space-y-2">
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox"
                                           v-model="filters.inStock"
                                           class="rounded text-indigo-600 focus:ring-indigo-500">
                                    <span>In Stock Only</span>
                                </label>
                                <label class="flex items-center space-x-3 cursor-pointer">
                                    <input type="checkbox"
                                           v-model="filters.onSale"
                                           class="rounded text-indigo-600 focus:ring-indigo-500">
                                    <span>On Sale</span>
                                </label>
                            </div>
                        </div>

                        <!-- Color Filter -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Colors</h3>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="color in colors"
                                        :key="color.id"
                                        @click="toggleColor(color.id)"
                                        class="w-8 h-8 rounded-full border-2 focus:outline-none"
                                        :class="{ 'ring-2 ring-offset-2 ring-indigo-600': filters.selectedColors.includes(color.id) }"
                                        :style="{ backgroundColor: color.code }">
                                </button>
                            </div>
                        </div>

                        <!-- Size Filter -->
                        <div>
                            <h3 class="text-lg font-semibold mb-4">Sizes</h3>
                            <div class="flex flex-wrap gap-2">
                                <button v-for="size in sizes"
                                        :key="size"
                                        @click="toggleSize(size)"
                                        class="px-3 py-1 border rounded-md focus:outline-none"
                                        :class="{ 'bg-indigo-600 text-white': filters.selectedSizes.includes(size) }">
                                    {{ size }}
                                </button>
                            </div>
                        </div>

                        <!-- Filter Actions -->
                        <div class="flex gap-4 pt-4 border-t">
                            <button @click="applyFilters"
                                    class="flex-1 bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 transition">
                                Apply Filters
                            </button>
                            <button @click="resetFilters"
                                    class="flex-1 border border-gray-300 px-4 py-2 rounded-md hover:bg-gray-50 transition">
                                Reset
                            </button>
                        </div>
                    </div>
                </div>
            </div>



            </div>
        </main>
    </div>
</template>
