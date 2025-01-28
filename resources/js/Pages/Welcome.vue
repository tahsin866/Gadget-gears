<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed } from 'vue';  // Import computed from vue instead of inertiajs
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
    {
        id: 2,
        name: 'Smart Watch Series 7',
        price: 299.99,
        image: 'https://picsum.photos/400/401',
        category: 1,
        rating: 4.8
    },
    {
        id: 3,
        name: 'Designer T-Shirt',
        price: 49.99,
        image: 'https://picsum.photos/400/402',
        category: 2,
        rating: 4.3
    },
    // Add more products as needed
]);

const selectedCategory = ref(null);

const filteredProducts = computed(() => {
    if (!selectedCategory.value) return products.value;
    return products.value.filter(product => product.category === selectedCategory.value);
});

defineProps({
    canLogin: Boolean,
    canRegister: Boolean
});

const logoImage = ref('/Images/Gadget_gears.png')
</script>

<template>
    <Head title="ShopHub - Modern Shopping Experience" />

    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <nav class="bg-white shadow-md">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
<!-- In your template, update the logo section -->
<div class="flex items-center">
    <img :src="logoImage" alt="Gadget Gears Logo" class="h-20 w-auto  mx-5">
</div>





                    <div class="flex items-center space-x-4">
                        <div v-if="canLogin" class="flex items-center space-x-4">
                            <Link v-if="$page.props.auth.user" :href="route('dashboard')"
                                  class="text-gray-700 hover:text-indigo-600 transition">
                                Dashboard
                            </Link>
                            <template v-else>
                                <Link :href="route('login')"
                                      class="text-gray-700 hover:text-indigo-600 transition">
                                    Login
                                </Link>
                                <Link v-if="canRegister" :href="route('register')"
                                      class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                    Register
                                </Link>
                            </template>
                        </div>

                        <button class="p-2 hover:bg-gray-100 rounded-full">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 text-white py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h1 class="text-4xl md:text-6xl font-bold mb-6">Discover Amazing Products</h1>
                <p class="text-xl mb-8">Shop the latest trends with unbeatable prices</p>
                <button class="bg-white text-indigo-600 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition">
                    Shop Now
                </button>
            </div>
        </div>

        <!-- Main Content -->
        <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex gap-8">
                <!-- Categories Sidebar -->
                <div class="w-64 flex-shrink-0">
                    <div class="bg-white rounded-xl shadow-lg p-6">
                        <h2 class="text-xl font-semibold mb-4">Categories</h2>
                        <ul class="space-y-3">
                            <li v-for="category in categories" :key="category.id"
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
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div v-for="product in filteredProducts" :key="product.id"
                             class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition">
                            <img :src="product.image" :alt="product.name" class="w-full h-64 object-cover">
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2">{{ product.name }}</h3>
                                <div class="flex items-center mb-4">
                                    <div class="flex text-yellow-400">
                                        <svg v-for="i in 5" :key="i" class="w-5 h-5" :class="{ 'text-gray-300': i > product.rating }"
                                             fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    </div>
                                    <span class="ml-2 text-gray-600">{{ product.rating }}/5</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-indigo-600">${{ product.price }}</span>
                                    <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
                                        Add to Cart
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
