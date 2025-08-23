<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Navbar from '@/Pages/navbar.vue';
import HeroSection from './heroSection.vue';
import CategorySection from './categorySection.vue';

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


</template>
