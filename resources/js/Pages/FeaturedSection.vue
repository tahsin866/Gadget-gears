<script setup>
import { Head } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Navbar from '@/Pages/navbar.vue';
import axios from 'axios';
import { router } from '@inertiajs/vue3';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';

// Component imports
import HeroBanner from '@/Pages/components/HeroBanner.vue';
import FeaturedBrands from '@/Pages/components/FeaturedBrands.vue';
import ProductsSection from '@/Pages/components/ProductsSection.vue';
import ProductFilters from '@/Pages/components/ProductFilters.vue';
import CartSidebar from '@/Pages/components/CartSidebar.vue';
import FloatingCartSummary from '@/Pages/components/FloatingCartSummary.vue';

// Data
const products = ref([]);
const loading = ref(false);
const layout = ref('grid');
const options = ref(['list', 'grid']);
const toast = useToast();

// Search and Filters
const searchQuery = ref('');
const showFilters = ref(false);
const sortBy = ref('name');
const sortOrder = ref('asc');

// Pagination
const currentPage = ref(1);
const itemsPerPage = ref(12);
const totalItems = ref(0);

// Sort options
const sortOptions = ref([
    { label: 'Name (A-Z)', value: 'name_asc' },
    { label: 'Name (Z-A)', value: 'name_desc' },
    { label: 'Price (Low to High)', value: 'price_asc' },
    { label: 'Price (High to Low)', value: 'price_desc' },
    { label: 'Rating (High to Low)', value: 'rating_desc' },
    { label: 'Newest First', value: 'created_desc' }
]);

// Featured Brands Data
const featuredBrands = ref([
    { id: 1, name: 'TechSound', logo: 'https://placehold.co/120x80/4F46E5/FFFFFF/png?text=TechSound', productCount: 25, verified: true },
    { id: 2, name: 'FitTech', logo: 'https://placehold.co/120x80/059669/FFFFFF/png?text=FitTech', productCount: 18, verified: true },
    { id: 3, name: 'BrewMaster', logo: 'https://placehold.co/120x80/DC2626/FFFFFF/png?text=BrewMaster', productCount: 12, verified: false },
    { id: 4, name: 'GamePro', logo: 'https://placehold.co/120x80/7C3AED/FFFFFF/png?text=GamePro', productCount: 32, verified: true },
    { id: 5, name: 'SoundWave', logo: 'https://placehold.co/120x80/EA580C/FFFFFF/png?text=SoundWave', productCount: 21, verified: true },
    { id: 6, name: 'LightUp', logo: 'https://placehold.co/120x80/0891B2/FFFFFF/png?text=LightUp', productCount: 15, verified: false },
    { id: 7, name: 'SmartHome', logo: 'https://placehold.co/120x80/16A34A/FFFFFF/png?text=SmartHome', productCount: 28, verified: true },
    { id: 8, name: 'StyleZone', logo: 'https://placehold.co/120x80/DB2777/FFFFFF/png?text=StyleZone', productCount: 19, verified: true }
]);

// Wishlist
const wishlist = ref([]);

// Quick view
const quickViewProduct = ref(null);
const showQuickView = ref(false);

// Cart Sidebar
const showCartSidebar = ref(false);

// Fetch products with advanced filtering
const fetchProducts = async () => {
    loading.value = true;
    console.log('Starting fetchProducts...');
    
    try {
        const response = await axios.get('/products-grid');
        console.log('API Response received:', response.data);

        if (response.data.success && response.data.products) {
            const apiProducts = response.data.products;
            console.log('Raw products from API:', apiProducts);
            
            // Simple mapping to ensure data compatibility
            products.value = apiProducts.map(product => ({
                id: product.id,
                name: product.name,
                price: product.price,
                originalPrice: product.originalPrice,
                image: product.image,
                brand: product.brand,
                category: product.category,
                rating: product.rating || 4.5,
                reviews: product.reviews || 0,
                inStock: product.inStock || product.stock > 0,
                stock: product.stock,
                onSale: product.onSale || false,
                featured: product.featured || false,
                colors: product.colors || [],
                sizes: product.sizes || []
            }));
            
            totalItems.value = products.value.length;
            
            console.log('Products set to:', products.value);
            console.log('Products count:', products.value.length);
            
        } else {
            console.error('API response invalid:', response.data);
            products.value = [];
            totalItems.value = 0;
        }
    } catch (error) {
        console.error('API call failed:', error);
        products.value = [];
        totalItems.value = 0;
        
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to load products',
            life: 3000
        });
    } finally {
        loading.value = false;
        console.log('fetchProducts completed. Loading:', loading.value);
    }
};

// Cart functionality with local storage
const cart = ref(JSON.parse(localStorage.getItem('cart') || '[]'));

// Save cart to localStorage
const saveCart = () => {
    localStorage.setItem('cart', JSON.stringify(cart.value));
};

// Add to cart function with toast notification
const addToCart = (product) => {
    const existingItem = cart.value.find(item => item.id === product.id);
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.value.push({ ...product, quantity: 1 });
    }
    saveCart();
    toast.add({
        severity: 'success',
        summary: 'Added to Cart',
        detail: `${product.name} has been added to your cart`,
        life: 3000
    });
};

// Remove from cart
const removeFromCart = (productId) => {
    const index = cart.value.findIndex(item => item.id === productId);
    if (index > -1) {
        const removedItem = cart.value[index];
        cart.value.splice(index, 1);
        saveCart();
        toast.add({
            severity: 'info',
            summary: 'Removed from Cart',
            detail: `${removedItem.name} has been removed from your cart`,
            life: 3000
        });
    }
};

// Update cart item quantity
const updateCartQuantity = (productId, newQuantity) => {
    if (newQuantity <= 0) {
        removeFromCart(productId);
        return;
    }

    const item = cart.value.find(item => item.id === productId);
    if (item) {
        item.quantity = newQuantity;
        saveCart();
    }
};

// Clear entire cart
const clearCart = () => {
    cart.value = [];
    saveCart();
    toast.add({
        severity: 'info',
        summary: 'Cart Cleared',
        detail: 'All items have been removed from your cart',
        life: 3000
    });
};

// Calculate cart totals
const cartSubtotal = computed(() => {
    return cart.value.reduce((total, item) => total + (item.price * item.quantity), 0);
});

const cartTax = computed(() => {
    return cartSubtotal.value * 0.1; // 10% tax
});

const cartShipping = computed(() => {
    return cartSubtotal.value > 100 ? 0 : 10; // Free shipping over $100
});

const cartTotal = computed(() => {
    return cartSubtotal.value + cartTax.value + cartShipping.value;
});

const cartItemCount = computed(() => {
    return cart.value.reduce((total, item) => total + item.quantity, 0);
});

// View cart function
const viewCart = () => {
    showCartSidebar.value = true;
};

// Proceed to checkout
const proceedToCheckout = () => {
    if (cart.value.length === 0) {
        toast.add({
            severity: 'warn',
            summary: 'Empty Cart',
            detail: 'Please add some items to your cart before checkout',
            life: 3000
        });
        return;
    }

    // Here you can implement checkout logic
    // For now, we'll just show a message
    toast.add({
        severity: 'info',
        summary: 'Checkout',
        detail: 'Checkout functionality will be implemented soon',
        life: 3000
    });

    // You could redirect to checkout page:
    // router.visit('/checkout', {
    //     method: 'post',
    //     data: { cart: cart.value }
    // });
};

// Add to wishlist
const toggleWishlist = (product) => {
    const index = wishlist.value.findIndex(item => item.id === product.id);
    if (index > -1) {
        wishlist.value.splice(index, 1);
        toast.add({
            severity: 'info',
            summary: 'Removed from Wishlist',
            detail: `${product.name} removed from wishlist`,
            life: 3000
        });
    } else {
        wishlist.value.push(product);
        toast.add({
            severity: 'success',
            summary: 'Added to Wishlist',
            detail: `${product.name} added to wishlist`,
            life: 3000
        });
    }
};

// Check if product is in wishlist
const isInWishlist = (productId) => {
    return wishlist.value.some(item => item.id === productId);
};

// Navigate to product details page using Inertia router
const viewProduct = (product) => {
    router.get(route('GADGET-GEARS.buyeProduct', { id: product.id }), {
        product: product
    });
};

// Quick view functionality
const openQuickView = (product) => {
    quickViewProduct.value = product;
    showQuickView.value = true;
};

// Calculate discount percentage
const calculateDiscount = (originalPrice, currentPrice) => {
    let original, current;

    if (typeof originalPrice === 'string') {
        original = parseFloat(originalPrice.replace(/[^0-9.-]+/g, ''));
    } else {
        original = parseFloat(originalPrice);
    }

    if (typeof currentPrice === 'string') {
        current = parseFloat(currentPrice.replace(/[^0-9.-]+/g, ''));
    } else {
        current = parseFloat(currentPrice);
    }

    return Math.round(((original - current) / original) * 100);
};

// Format price
const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
};

// Buy now function
const buyNow = (product) => {
    router.get(route('GADGET-GEARS.buyeProduct', { id: product.id }), {
        product: product
    });
};

// Advanced Filters
const filters = reactive({
    priceMin: 0,
    priceMax: 5000,
    priceRange: [0, 5000],
    selectedBrands: [],
    rating: null,
    inStock: false,
    onSale: false,
    selectedColors: [],
    selectedSizes: [],
    categories: []
});

// Available filter options
const brandOptions = computed(() => {
    return featuredBrands.value.map(brand => ({
        label: brand.name,
        value: brand.id
    }));
});

const colorOptions = ref([
    { label: 'Red', value: 'red', color: '#ef4444' },
    { label: 'Blue', value: 'blue', color: '#3b82f6' },
    { label: 'Green', value: 'green', color: '#10b981' },
    { label: 'Black', value: 'black', color: '#1f2937' },
    { label: 'White', value: 'white', color: '#f9fafb' },
    { label: 'Yellow', value: 'yellow', color: '#f59e0b' },
    { label: 'Purple', value: 'purple', color: '#8b5cf6' },
    { label: 'Pink', value: 'pink', color: '#ec4899' }
]);

const sizeOptions = ref([
    { label: 'XS', value: 'xs' },
    { label: 'S', value: 's' },
    { label: 'M', value: 'm' },
    { label: 'L', value: 'l' },
    { label: 'XL', value: 'xl' },
    { label: 'XXL', value: 'xxl' }
]);

// Simplified - Direct products without complex filtering for now
// This will help us debug the issue
console.log('Products ref created:', products.value);

// Reset filters
const resetFilters = () => {
    Object.assign(filters, {
        priceMin: 0,
        priceMax: 5000,
        priceRange: [0, 5000],
        selectedBrands: [],
        rating: null,
        inStock: false,
        onSale: false,
        selectedColors: [],
        selectedSizes: [],
        categories: []
    });
    searchQuery.value = '';
    currentPage.value = 1;
};

// Sort products
const handleSort = (sortValue) => {
    const [field, order] = sortValue.split('_');
    sortBy.value = field;
    sortOrder.value = order;

    products.value.sort((a, b) => {
        let aValue, bValue;

        switch (field) {
            case 'name':
                aValue = a.name.toLowerCase();
                bValue = b.name.toLowerCase();
                break;
            case 'price':
                aValue = parseFloat(a.price);
                bValue = parseFloat(b.price);
                break;
            case 'rating':
                aValue = a.rating;
                bValue = b.rating;
                break;
            default:
                return 0;
        }

        if (order === 'asc') {
            return aValue > bValue ? 1 : -1;
        } else {
            return aValue < bValue ? 1 : -1;
        }
    });
};

// Page change handler
const onPageChange = (event) => {
    currentPage.value = event.page + 1;
};

// Navigate to brand page
const navigateToBrand = (brand) => {
    filters.selectedBrands = [brand.name];
    showFilters.value = false;
    toast.add({
        severity: 'info',
        summary: 'Filter Applied',
        detail: `Showing products from ${brand.name}`,
        life: 3000
    });
};

// Generate star rating
const generateStars = (rating) => {
    const fullStars = Math.floor(rating);
    const hasHalfStar = rating % 1 !== 0;
    const emptyStars = 5 - fullStars - (hasHalfStar ? 1 : 0);
    return {
        full: fullStars,
        half: hasHalfStar,
        empty: emptyStars
    };
};

// Get color code for color name
const getColorCode = (colorName) => {
    const colorMap = {
        'red': '#ef4444',
        'blue': '#3b82f6',
        'green': '#10b981',
        'black': '#1f2937',
        'white': '#f9fafb',
        'yellow': '#f59e0b',
        'purple': '#8b5cf6',
        'pink': '#ec4899',
        'gray': '#6b7280',
        'orange': '#f97316'
    };
    return colorMap[colorName.toLowerCase()] || '#6b7280';
};

const getSeverity = (product) => {
    if (product.inStock) {
        return 'success';
    } else {
        return 'danger';
    }
};

// Watch for filter changes
watch([filters, searchQuery], () => {
    currentPage.value = 1;
}, { deep: true });

// Lifecycle
onMounted(() => {
    fetchProducts();
});

onUnmounted(() => {
    saveCart();
});
</script>

<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        <!-- Toast for notifications -->
        <Toast />

        <!-- Hero Banner -->
        <HeroBanner />

        <!-- Featured Brands Section -->
        <FeaturedBrands
            :brands="featuredBrands"
            @navigate-to-brand="navigateToBrand"
        />

        <!-- Products Section -->
        <ProductsSection
            :products="products"
            :loading="loading"
            :layout="layout"
            :options="options"
            :searchQuery="searchQuery"
            :sortOptions="sortOptions"
            :sortBy="sortBy"
            :itemsPerPage="itemsPerPage"
            :currentPage="currentPage"
            :totalItems="totalItems"
            :filteredProductsCount="products.length"
            :totalProductsCount="products.length"
            @update:searchQuery="searchQuery = $event"
            @update:layout="layout = $event"
            @sort="handleSort"
            @show-filters="showFilters = true"
            @page-change="onPageChange"
            @reset-filters="resetFilters"
            @add-to-cart="addToCart"
            @toggle-wishlist="toggleWishlist"
            @quick-view="openQuickView"
            @buy-now="buyNow"
            :formatPrice="formatPrice"
            :calculateDiscount="calculateDiscount"
            :getSeverity="getSeverity"
            :getColorCode="getColorCode"
            :isInWishlist="isInWishlist"
        />

        <!-- Filter Sidebar -->
        <ProductFilters
            :visible="showFilters"
            @update:visible="showFilters = $event"
            :filters="filters"
            :brandOptions="brandOptions"
            :colorOptions="colorOptions"
            :sizeOptions="sizeOptions"
            @reset-filters="resetFilters"
        />

        <!-- Cart Sidebar -->
        <CartSidebar
            :visible="showCartSidebar"
            @update:visible="showCartSidebar = $event"
            :cart="cart"
            :cartItemCount="cartItemCount"
            :cartSubtotal="cartSubtotal"
            :cartTax="cartTax"
            :cartShipping="cartShipping"
            :cartTotal="cartTotal"
            :formatPrice="formatPrice"
            @update-quantity="updateCartQuantity"
            @remove-from-cart="removeFromCart"
            @clear-cart="clearCart"
            @checkout="proceedToCheckout"
        />

        <!-- Floating Cart Summary -->
        <FloatingCartSummary
            v-if="cart.length > 0"
            :cart="cart"
            :formatPrice="formatPrice"
            @view-cart="viewCart"
        />

        <!-- Floating Action Button for Filters (Mobile) -->
        <Button v-if="!showFilters"
                icon="pi pi-filter"
                rounded
                class="fixed bottom-6 left-6 w-12 h-12 bg-indigo-600 hover:bg-indigo-700 shadow-lg z-40 md:hidden"
                @click="showFilters = true" />
    </div>
</template>
