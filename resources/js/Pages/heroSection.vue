<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted } from 'vue';
import Carousel from 'primevue/carousel';
import Button from 'primevue/button';
import Chip from 'primevue/chip';
import Badge from 'primevue/badge';
import Image from 'primevue/image';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    auth: Object
});

// State management
const selectedCategory = ref(null);
const showSearchResults = ref(false);
const searchQuery = ref('');
const showMobileMenu = ref(false);
const currentSlide = ref(0);
const isAutoPlaying = ref(true);

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

// Enhanced hero slider data with gadget-focused content
const slides = ref([
    {
        id: 1,
        title: "Latest Smartphones & Tablets",
        subtitle: "Discover cutting-edge mobile technology",
        description: "Shop the newest flagship smartphones, tablets, and accessories with exclusive deals and free premium delivery",
        image: "https://images.unsplash.com/photo-1551355738-1321c1a0055d?w=1920&h=800&fit=crop&q=80",
        mobileImage: "https://images.unsplash.com/photo-1551355738-1321c1a0055d?w=768&h=600&fit=crop&q=80",
        buttonText: "Shop Mobile Devices",
        buttonLink: "/smartphones",
        badge: "NEW ARRIVAL",
        offer: "Up to 30% OFF",
        category: "Smartphones"
    },
    {
        id: 2,
        title: "Premium Laptops & Computers",
        subtitle: "Powerful performance for work and play",
        description: "Explore our selection of high-performance laptops, desktop computers and accessories from top brands",
        image: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=1920&h=800&fit=crop&q=80",
        mobileImage: "https://images.unsplash.com/photo-1496181133206-80ce9b88a853?w=768&h=600&fit=crop&q=80",
        buttonText: "Explore Computers",
        buttonLink: "/computers",
        badge: "TRENDING",
        offer: "Free Premium Software",
        category: "Computers"
    },
    {
        id: 3,
        title: "Smart Home Ecosystem",
        subtitle: "Transform your living space with intelligent tech",
        description: "Create the perfect smart home with our premium selection of IoT devices, speakers, and automation systems",
        image: "https://images.unsplash.com/photo-1558002038-bb6219e499bb?w=1920&h=800&fit=crop&q=80",
        mobileImage: "https://images.unsplash.com/photo-1558002038-bb6219e499bb?w=768&h=600&fit=crop&q=80",
        buttonText: "Smart Home",
        buttonLink: "/smart-home",
        badge: "LIMITED TIME",
        offer: "Save up to 40%",
        category: "Smart Home"
    },
    {
        id: 4,
        title: "Gaming & Entertainment Tech",
        subtitle: "Level up your gaming experience",
        description: "Discover premium gaming consoles, peripherals, and accessories to elevate your gaming performance",
        image: "https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=1920&h=800&fit=crop&q=80",
        mobileImage: "https://images.unsplash.com/photo-1593305841991-05c297ba4575?w=768&h=600&fit=crop&q=80",
        buttonText: "Gaming Zone",
        buttonLink: "/gaming",
        badge: "BEST SELLER",
        offer: "Free Gaming Bundle",
        category: "Gaming"
    }
]);

// Featured stats/metrics for tech gadgets
const stats = ref([
    { icon: "pi-users", value: "2M+", label: "Tech Enthusiasts" },
    { icon: "pi-star-fill", value: "4.9", label: "Customer Rating" },
    { icon: "pi-globe", value: "50+", label: "Brands Available" },
    { icon: "pi-shield", value: "100%", label: "Authentic Products" }
]);

// Trust badges for tech store
const trustBadges = ref([
    { icon: "pi-truck", title: "Fast Delivery", desc: "Free on orders over $99" },
    { icon: "pi-refresh", title: "Easy Returns", desc: "30-day return policy" },
    { icon: "pi-shield", title: "Genuine Products", desc: "100% authentic gadgets" },
    { icon: "pi-headphones", title: "Tech Support", desc: "Expert assistance 24/7" }
]);

const responsiveOptions = ref([
    {
        breakpoint: '1024px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '768px',
        numVisible: 1,
        numScroll: 1
    },
    {
        breakpoint: '560px',
        numVisible: 1,
        numScroll: 1
    }
]);

// Auto-slide functionality
onMounted(() => {
    startAutoSlide();
});

const startAutoSlide = () => {
    setInterval(() => {
        if (isAutoPlaying.value) {
            currentSlide.value = (currentSlide.value + 1) % slides.value.length;
        }
    }, 6000);
};

const pauseAutoSlide = () => {
    isAutoPlaying.value = false;
};

const resumeAutoSlide = () => {
    isAutoPlaying.value = true;
};

// Scroll to section function
const scrollToSection = (sectionId) => {
    const element = document.querySelector(sectionId);
    if (element) {
        element.scrollIntoView({ behavior: 'smooth' });
    }
};
</script>

<template>
    <!-- Modern Gadget E-commerce Hero Section -->
    <section class="relative bg-gradient-to-br from-gray-900 to-gray-800 overflow-hidden">
        <!-- Hero Carousel Container - Reduced height -->
        <div class="mt-10 relative h-[70vh] min-h-[450px] max-h-[600px]">
            <Carousel
                :value="slides"
                :numVisible="1"
                :numScroll="1"
                :responsiveOptions="responsiveOptions"
                :circular="true"
                :autoplayInterval="6000"
                :showNavigators="true"
                :showIndicators="false"
                @mouseenter="pauseAutoSlide"
                @mouseleave="resumeAutoSlide"
                class="h-full"
            >
                <template #item="slotProps">
                    <div class="relative h-full group overflow-hidden">
                        <!-- Slide Background with Overlay -->
                        <div class="absolute inset-0">
                            <!-- Desktop Image with adjusted height to reduce gap -->
                            <img :src="slotProps.data.image"
                                 :alt="slotProps.data.title"
                                 class="hidden md:block w-full h-full object-cover transform transition-transform duration-1000 group-hover:scale-105">

                            <!-- Mobile Image with adjusted height to reduce gap -->
                            <img :src="slotProps.data.mobileImage"
                                 :alt="slotProps.data.title"
                                 class="md:hidden w-full h-full object-cover transform transition-transform duration-1000 group-hover:scale-105">

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/60 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
                        </div>

                        <!-- Slide Content - Better balanced layout -->
                        <div class="relative h-full flex items-center">
                            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
                                    <!-- Text Content - Adjusted spacing -->
                                    <div class="text-white space-y-4 transform transition-all duration-700 translate-y-0 opacity-100 pt-0">
                                        <!-- Badges and Tags -->
                                        <div class="flex flex-wrap items-center gap-3">
                                            <span class="bg-red-600 text-white px-4 py-1 rounded-full text-sm font-bold uppercase tracking-wider animate-pulse">
                                                {{ slotProps.data.badge }}
                                            </span>
                                            <span class="bg-gradient-to-r from-yellow-400 to-orange-500 text-black px-4 py-1 rounded-full text-sm font-bold">
                                                {{ slotProps.data.offer }}
                                            </span>
                                        </div>

                                        <!-- Main Title -->
                                        <div class="space-y-1">
                                            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold leading-tight text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-100 to-gray-300">
                                                {{ slotProps.data.title }}
                                            </h1>
                                            <h2 class="text-lg sm:text-xl text-gray-200 font-light leading-relaxed">
                                                {{ slotProps.data.subtitle }}
                                            </h2>
                                        </div>

                                        <!-- Description - Reduced padding -->
                                        <p class="text-sm lg:text-base text-gray-300 max-w-xl">
                                            {{ slotProps.data.description }}
                                        </p>

                                        <!-- CTA Buttons -->
                                        <div class="flex flex-col sm:flex-row gap-3 pt-1">
                                            <Button
                                                :label="slotProps.data.buttonText"
                                                icon="pi pi-arrow-right"
                                                iconPos="right"
                                                class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 text-base font-semibold rounded-lg shadow-lg transform transition-transform hover:scale-105"
                                                @click="scrollToSection(slotProps.data.buttonLink)"
                                            />
                                            <Button
                                                label="View Collection"
                                                icon="pi pi-eye"
                                                outlined
                                                class="border-2 border-white text-white hover:bg-white hover:text-gray-900 px-5 py-2 text-base font-semibold rounded-lg transform transition-transform hover:scale-105"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Scroll Indicator - Repositioned closer to bottom -->
                        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 hidden lg:flex flex-col items-center">
                            <span class="text-white/60 text-sm mb-1">Scroll to discover</span>
                            <div class="w-8 h-8 rounded-full border border-white/30 flex items-center justify-center animate-bounce">
                                <i class="pi pi-angle-down text-white/60"></i>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Custom Navigation - Previous -->
                <template #previousicon>
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/20 transition duration-300 shadow-lg">
                        <i class="pi pi-chevron-left text-white text-sm"></i>
                    </div>
                </template>

                <!-- Custom Navigation - Next -->
                <template #nexticon>
                    <div class="w-10 h-10 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center hover:bg-white/20 transition duration-300 shadow-lg">
                        <i class="pi pi-chevron-right text-white text-sm"></i>
                    </div>
                </template>
            </Carousel>

            <!-- Custom Slide Indicators -->
            <div class="absolute bottom-4 right-8 flex space-x-2">
                <button v-for="(slide, index) in slides"
                        :key="slide.id"
                        @click="currentSlide = index"
                        class="group">
                    <div class="w-2 h-2 rounded-full transition duration-300"
                         :class="index === currentSlide ? 'w-6 bg-white' : 'bg-white/40 hover:bg-white/60'">
                    </div>
                </button>
            </div>
        </div>

        <!-- Trust Badges Section - Reduced padding -->
        <div class="bg-black/40 backdrop-blur-md border-t border-gray-700/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div v-for="badge in trustBadges" :key="badge.title"
                         class="text-center group">
                        <div class="flex flex-col md:flex-row items-center justify-center md:justify-start space-y-1 md:space-y-0 md:space-x-3 p-2 rounded-lg hover:bg-white/5 transition duration-300">
                            <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center group-hover:scale-110 transition duration-300">
                                <i :class="`pi ${badge.icon} text-white text-sm`"></i>
                            </div>
                            <div>
                                <div class="text-white font-semibold text-sm">{{ badge.title }}</div>
                                <div class="text-gray-400 text-xs">{{ badge.desc }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Stats Section - Reduced padding -->
        <div class="bg-gradient-to-r from-indigo-900/50 to-purple-900/50 border-t border-gray-700/50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div v-for="stat in stats" :key="stat.label"
                         class="text-center group">
                        <div class="flex flex-col items-center space-y-1">
                            <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-full flex items-center justify-center mb-1 group-hover:scale-110 transition duration-300">
                                <i :class="`pi ${stat.icon} text-white text-lg`"></i>
                            </div>
                            <div class="text-xl font-bold text-white">{{ stat.value }}</div>
                            <div class="text-sm text-gray-300">{{ stat.label }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Category Shortcuts - More compact design -->
        <div class="bg-gradient-to-b from-gray-900 to-black py-6 border-t border-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <h2 class="text-lg font-bold text-white text-center mb-6">Shop By Category</h2>

                <div class="grid grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    <!-- Tech categories -->
                    <div class="group cursor-pointer">
                        <div class="bg-gray-800/50 hover:bg-gray-800 rounded-xl p-3 text-center transition duration-300 transform hover:-translate-y-1 border border-gray-700/50 hover:border-indigo-500/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-indigo-600 group-hover:to-purple-600 transition duration-300">
                                <i class="pi pi-mobile text-gray-400 group-hover:text-white transition duration-300"></i>
                            </div>
                            <h3 class="text-white text-sm font-medium">Smartphones</h3>
                            <p class="text-gray-400 text-xs mt-1">Shop Now</p>
                        </div>
                    </div>

                    <div class="group cursor-pointer">
                        <div class="bg-gray-800/50 hover:bg-gray-800 rounded-xl p-3 text-center transition duration-300 transform hover:-translate-y-1 border border-gray-700/50 hover:border-indigo-500/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-indigo-600 group-hover:to-purple-600 transition duration-300">
                                <i class="pi pi-desktop text-gray-400 group-hover:text-white transition duration-300"></i>
                            </div>
                            <h3 class="text-white text-sm font-medium">Computers</h3>
                            <p class="text-gray-400 text-xs mt-1">Shop Now</p>
                        </div>
                    </div>

                    <div class="group cursor-pointer">
                        <div class="bg-gray-800/50 hover:bg-gray-800 rounded-xl p-3 text-center transition duration-300 transform hover:-translate-y-1 border border-gray-700/50 hover:border-indigo-500/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-indigo-600 group-hover:to-purple-600 transition duration-300">
                                <i class="pi pi-volume-up text-gray-400 group-hover:text-white transition duration-300"></i>
                            </div>
                            <h3 class="text-white text-sm font-medium">Audio</h3>
                            <p class="text-gray-400 text-xs mt-1">Shop Now</p>
                        </div>
                    </div>

                    <div class="group cursor-pointer">
                        <div class="bg-gray-800/50 hover:bg-gray-800 rounded-xl p-3 text-center transition duration-300 transform hover:-translate-y-1 border border-gray-700/50 hover:border-indigo-500/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-indigo-600 group-hover:to-purple-600 transition duration-300">
                                <i class="pi pi-home text-gray-400 group-hover:text-white transition duration-300"></i>
                            </div>
                            <h3 class="text-white text-sm font-medium">Smart Home</h3>
                            <p class="text-gray-400 text-xs mt-1">Shop Now</p>
                        </div>
                    </div>

                    <div class="group cursor-pointer">
                        <div class="bg-gray-800/50 hover:bg-gray-800 rounded-xl p-3 text-center transition duration-300 transform hover:-translate-y-1 border border-gray-700/50 hover:border-indigo-500/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-indigo-600 group-hover:to-purple-600 transition duration-300">
                                <i class="pi pi-tablet text-gray-400 group-hover:text-white transition duration-300"></i>
                            </div>
                            <h3 class="text-white text-sm font-medium">Gaming</h3>
                            <p class="text-gray-400 text-xs mt-1">Shop Now</p>
                        </div>
                    </div>

                    <div class="group cursor-pointer">
                        <div class="bg-gray-800/50 hover:bg-gray-800 rounded-xl p-3 text-center transition duration-300 transform hover:-translate-y-1 border border-gray-700/50 hover:border-indigo-500/50">
                            <div class="w-10 h-10 bg-gradient-to-br from-gray-700 to-gray-800 rounded-full flex items-center justify-center mx-auto mb-2 group-hover:bg-gradient-to-br group-hover:from-indigo-600 group-hover:to-purple-600 transition duration-300">
                                <i class="pi pi-list text-gray-400 group-hover:text-white transition duration-300"></i>
                            </div>
                            <h3 class="text-white text-sm font-medium">View All</h3>
                            <p class="text-gray-400 text-xs mt-1">Categories</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
