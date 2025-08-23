<script setup>
import { defineProps, defineEmits, watch } from 'vue';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Badge from 'primevue/badge';
import Rating from 'primevue/rating';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Chip from 'primevue/chip';
import Paginator from 'primevue/paginator';
import ProgressSpinner from 'primevue/progressspinner';

const props = defineProps({
    products: Array,
    filteredProductsCount: Number,
    totalProductsCount: Number,
    loading: Boolean,
    layout: String,
    options: Array,
    searchQuery: String,
    sortOptions: Array,
    sortBy: String,
    filters: Object,
    itemsPerPage: Number,
    currentPage: Number,
    totalItems: Number,
    formatPrice: Function,
    calculateDiscount: Function,
    getSeverity: Function,
    getColorCode: Function,
    isInWishlist: Function
});

const emit = defineEmits([
    'update:searchQuery',
    'update:layout',
    'sort',
    'show-filters',
    'page-change',
    'reset-filters',
    'add-to-cart',
    'toggle-wishlist',
    'quick-view',
    'buy-now'
]);

// Debug products prop
watch(() => props.products, (newProducts) => {
    console.log('ProductsSection received products:', newProducts);
    console.log('Products length:', newProducts?.length || 0);
}, { immediate: true, deep: true });

function formatBDT(price) {
    // Remove any existing currency symbol and format as Bangladeshi Taka
    const num = typeof price === 'string' ? parseFloat(price.replace(/[^0-9.-]+/g, '')) : price;
    return `৳${num.toLocaleString('en-BD', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}
</script>

<template>
    <section class="py-12 md:py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-8 md:mb-12">
                <div class="inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full font-medium text-sm mb-4">
                    <i class="pi pi-star-fill mr-2"></i>
                    Premium Collection
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Products</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Discover our handpicked selection of premium products with unbeatable quality</p>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow mb-6 md:mb-8">
                <div class="p-4 md:p-6">
                    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-center">
                        <!-- Search -->
                        <div class="lg:col-span-2">
                            <div class="relative">
                                <i class="pi pi-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <InputText
                                    :modelValue="searchQuery"
                                    placeholder="Search products, brands..."
                                    class="w-full pl-10 border-gray-300 rounded-lg"
                                    @input="$emit('update:searchQuery', $event.target.value)"
                                />
                            </div>
                        </div>

                        <!-- Sort -->
                        <div>
                            <Select
                                :modelValue="sortBy"
                                :options="sortOptions"
                                optionLabel="label"
                                optionValue="value"
                                placeholder="Sort by"
                                class="w-full"
                                @change="$emit('sort', $event.value)"
                            />
                        </div>

                        <!-- Filter Toggle -->
                        <div class="flex gap-2">
                            <Button
                                label="Filters"
                                icon="pi pi-filter"
                                outlined
                                class="flex-1"
                                @click="$emit('show-filters')"
                            />
                        </div>
                    </div>
                </div>
            </div>

            <!-- Debug Info -->


            <!-- Products Display -->
            <div class="bg-white rounded-xl shadow overflow-hidden">
                <!-- Loading State -->
                <div v-if="loading" class="p-12 text-center">
                    <ProgressSpinner />
                    <p class="mt-4 text-gray-600">Loading amazing products...</p>
                </div>

                <!-- Products Grid -->
                <div v-else-if="products && products.length > 0" class="p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        <div v-for="product in products" :key="product.id"
                             class="bg-white rounded-xl shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">

                            <!-- Product Image -->
                            <div class="relative aspect-square bg-gray-50 overflow-hidden">
                                <img :src="product.image"
                                     :alt="product.name"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                     @error="$event.target.src = 'https://placehold.co/300x300/E5E7EB/9CA3AF/png?text=No+Image'" />

                                <!-- Stock Badge -->
                                <div class="absolute top-3 left-3">
                                    <Tag :value="product.inStock ? 'IN STOCK' : 'OUT OF STOCK'"
                                         :severity="product.inStock ? 'success' : 'danger'"
                                         class="shadow" />
                                </div>

                                <!-- Sale Badge -->
                                <div v-if="product.onSale" class="absolute top-3 right-3">
                                    <Badge value="SALE" severity="danger" class="shadow" />
                                </div>

                                <!-- Quick Actions -->
                                <div class="absolute bottom-3 left-3 right-3 opacity-0 hover:opacity-100 transition-all duration-300">
                                    <Button :label="product.inStock ? 'Add to Cart' : 'Out of Stock'"
                                            icon="pi pi-shopping-cart"
                                            :disabled="!product.inStock"
                                            class="w-full bg-white/90 backdrop-blur-sm text-gray-900 hover:bg-white shadow"
                                            @click="$emit('add-to-cart', product)" />
                                </div>
                            </div>

                            <!-- Product Info -->
                            <div class="p-4 space-y-3">
                                <!-- Brand -->
                                <div class="flex items-center justify-between">
                                    <span class="text-sm font-medium text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                        {{ product.brand }}
                                    </span>
                                    <div class="flex items-center gap-1">
                                        <Rating :modelValue="product.rating" :readonly="true" :cancel="false" class="text-sm" />
                                        <span class="text-xs text-gray-500">({{ product.reviews }})</span>
                                    </div>
                                </div>

                                <!-- Product Name -->
                                <h3 class="text-base font-bold text-gray-900 hover:text-indigo-600 transition-colors duration-300 line-clamp-2">
                                    {{ product.name }}
                                </h3>

                                <!-- Colors -->
                                <div v-if="product.colors && product.colors.length > 0" class="flex items-center gap-2">
                                    <span class="text-sm text-gray-500">Colors:</span>
                                    <div class="flex gap-1">
                                        <div v-for="color in product.colors.slice(0, 4)"
                                             :key="color"
                                             class="w-5 h-5 rounded-full border-2 border-gray-300 shadow-sm"
                                             :style="{ backgroundColor: getColorCode(color) }"
                                             :title="color">
                                        </div>
                                        <span v-if="product.colors.length > 4" class="text-xs text-gray-400">
                                            +{{ product.colors.length - 4 }}
                                        </span>
                                    </div>
                                </div>

                                <!-- Price -->
                                <div>
                                    <div class="flex items-center justify-between">
                                        <div>
                                            <div class="flex items-center gap-2">
                                                <span class="text-lg font-bold text-gray-900">
                                                    {{ formatBDT(product.price) }}
                                                </span>
                                                <span v-if="product.originalPrice"
                                                      class="text-sm text-gray-500 line-through">
                                                    {{ formatBDT(product.originalPrice) }}
                                                </span>
                                            </div>
                                            <div v-if="product.originalPrice">
                                                <Badge :value="`Save ${formatBDT(product.originalPrice - product.price)}`"
                                                       severity="success"
                                                       class="text-xs" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="pt-3 border-t border-gray-100 space-y-2">
                                    <Button :label="product.inStock ? 'Buy Now' : 'Out of Stock'"
                                            icon="pi pi-bolt"
                                            :disabled="!product.inStock"
                                            severity="success"
                                            class="w-full font-medium"
                                            @click="$emit('buy-now', product)" />

                                    <div class="flex gap-2">
                                        <Button icon="pi pi-eye"
                                                outlined
                                                class="flex-1"
                                                @click="$emit('quick-view', product)" />
                                        <Button :icon="isInWishlist(product.id) ? 'pi pi-heart-fill' : 'pi pi-heart'"
                                                :outlined="!isInWishlist(product.id)"
                                                :severity="isInWishlist(product.id) ? 'danger' : 'secondary'"
                                                class="flex-1"
                                                @click="$emit('toggle-wishlist', product)" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Empty State -->
                <div v-else-if="!loading" class="p-12 text-center">
                    <i class="pi pi-search text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">No products found</h3>
                    <p class="text-gray-500 mb-6">Try adjusting your search or filter criteria</p>
                    <Button label="Clear Filters" outlined @click="$emit('reset-filters')" />
                </div>

                <!-- Pagination -->
                <div v-if="!loading && products && products.length > 0 && filteredProductsCount > itemsPerPage"
                     class="p-4 md:p-6 border-t border-gray-200">
                    <Paginator
                        :rows="itemsPerPage"
                        :totalRecords="filteredProductsCount"
                        :first="(currentPage - 1) * itemsPerPage"
                        @page="$emit('page-change', $event)"
                        class="justify-center"
                    />
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
