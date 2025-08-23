<template>
    <Toast position="top-right" />
    <navbar ref="navbarRef" />

    <!-- Professional Product Page -->
    <div class="min-h-screen bg-gray-50">
        <div v-if="loading" class="min-h-screen flex items-center justify-center">
            <div class="text-center">
                <div class="w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full animate-spin mx-auto"></div>
                <p class="mt-4 text-gray-600">Loading product details...</p>
            </div>
        </div>

        <div v-else-if="product && Object.keys(product).length" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Breadcrumb Navigation -->
            <nav class="flex items-center space-x-2 text-sm text-gray-500 mb-6">
                <a href="/" class="hover:text-blue-600 transition-colors">Home</a>
                <i class="pi pi-angle-right text-xs"></i>
                <a href="/products" class="hover:text-blue-600 transition-colors">Products</a>
                <i class="pi pi-angle-right text-xs"></i>
                <span class="text-gray-900">{{ product.name }}</span>
            </nav>

            <!-- Main Product Section -->
            <div class="grid lg:grid-cols-2 gap-10 mb-12">
                <!-- Product Images Section -->
                <div class="space-y-4">
                    <!-- Main Product Image -->
                    <div class="bg-white rounded-lg overflow-hidden border border-gray-200 shadow-sm">
                        <Image
                            :src="currentDisplayImage"
                            :alt="product.name || 'Product'"
                            class="w-full h-auto object-contain"
                            preview
                        />

                        <!-- Stock Badge -->
                        <div class="absolute top-4 left-4">
                            <Badge
                                :value="product.stock > 0 ? `${product.stock} in stock` : 'Out of Stock'"
                                :severity="product.stock > 0 ? 'success' : 'danger'"
                            />
                        </div>
                    </div>

                    <!-- Thumbnail Gallery -->
                    <div class="grid grid-cols-4 gap-2">
                        <div
                            v-for="(image, index) in product.images?.slice(0, 4) || []"
                            :key="index"
                            @click="selectedImage = image.image_path"
                            :class="currentDisplayImage === `/storage/${image.image_path}` ? 'ring-2 ring-blue-500' : 'hover:ring-1 hover:ring-blue-300'"
                            class="bg-white border border-gray-200 rounded-md cursor-pointer transition-all duration-200"
                        >
                            <Image
                                :src="image.image_path ? `/storage/${image.image_path}` : 'https://via.placeholder.com/100x100'"
                                :alt="product.name || 'Product'"
                                class="w-full h-24 object-cover"
                            />
                        </div>
                    </div>
                </div>

                <!-- Product Information -->
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center gap-3 mb-2">
                            <Chip
                                :label="product.brand?.name || 'Brand'"
                                class="bg-blue-100 text-blue-800 px-3 py-1"
                            />

                            <div class="flex items-center text-yellow-400">
                                <i class="pi pi-star-fill text-sm"></i>
                                <i class="pi pi-star-fill text-sm"></i>
                                <i class="pi pi-star-fill text-sm"></i>
                                <i class="pi pi-star-fill text-sm"></i>
                                <i class="pi pi-star-fill text-sm"></i>
                                <span class="ml-1 text-sm text-gray-600">(4.9)</span>
                            </div>
                        </div>

                        <h1 class="text-3xl font-bold text-gray-900 mb-3">
                            {{ product.name || 'Product Name' }}
                        </h1>

                        <p class="text-gray-600 mb-4">
                            {{ product.short_description || product.description || 'Premium quality product with excellent features and modern design.' }}
                        </p>
                    </div>

                    <!-- Pricing Section -->
                    <div class="bg-white p-4 border border-gray-200 rounded-lg">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-2">
                                <span class="text-3xl font-bold text-gray-900">
                                    ${{ product.sale_price || product.price || 0 }}
                                </span>
                                <div v-if="product.sale_price && product.price > product.sale_price">
                                    <span class="text-lg text-gray-500 line-through">${{ product.price }}</span>
                                    <Tag
                                        :value="`${Math.round(((product.price - product.sale_price) / product.price) * 100)}% OFF`"
                                        severity="danger"
                                        class="ml-2"
                                    />
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-4 text-sm">
                            <div class="flex items-center text-green-600">
                                <i class="pi pi-check-circle mr-1"></i>
                                <span>Free shipping over $99</span>
                            </div>
                            <div class="flex items-center text-blue-600">
                                <i class="pi pi-clock mr-1"></i>
                                <span>2-day delivery</span>
                            </div>
                        </div>
                    </div>

                    <!-- Color Selection -->
                    <div v-if="getAvailableColors.length > 0" class="border border-gray-200 rounded-lg p-4 bg-white">
                        <h3 class="font-medium text-gray-900 mb-3">
                            Select Color
                        </h3>
                        <div class="flex flex-wrap gap-3">
                            <div
                                v-for="color in getAvailableColors"
                                :key="color.id"
                                @click="selectColor(color)"
                                :class="selectedColor?.id === color.id ? 'ring-2 ring-offset-2 ring-blue-500' : ''"
                                class="cursor-pointer transition-all duration-200"
                            >
                                <div
                                    :style="{ backgroundColor: color.colorCode }"
                                    :class="color.colorCode === '#ffffff' ? 'border border-gray-300' : ''"
                                    class="w-10 h-10 rounded-full shadow-sm"
                                    :title="color.name"
                                ></div>
                            </div>
                        </div>
                        <div v-if="selectedColor" class="mt-2 text-sm text-gray-600">
                            Selected: {{ selectedColor.name }}
                        </div>
                    </div>

                    <!-- Size Selection -->
                    <div v-if="getAvailableSizes.length > 0" class="border border-gray-200 rounded-lg p-4 bg-white">
                        <h3 class="font-medium text-gray-900 mb-3">
                            Select Size
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            <button
                                v-for="size in getAvailableSizes"
                                :key="size.id"
                                @click="selectSize(size)"
                                :class="[
                                    'px-4 py-2 border rounded-md text-sm font-medium transition-colors',
                                    selectedSize?.id === size.id
                                        ? 'bg-blue-600 text-white border-blue-600'
                                        : 'bg-white text-gray-700 border-gray-300 hover:border-blue-400'
                                ]"
                            >
                                {{ size.name }}
                            </button>
                        </div>
                        <div class="mt-3 text-xs text-blue-600">
                            <a href="#" class="hover:underline">Size Guide</a>
                        </div>
                    </div>

                    <!-- Quantity & Actions -->
                    <div class="border border-gray-200 rounded-lg p-4 bg-white">
                        <h3 class="font-medium text-gray-900 mb-3">
                            Quantity
                        </h3>
                        <div class="flex items-center mb-4">
                            <InputNumber
                                v-model="quantity"
                                :min="1"
                                :max="product.stock"
                                showButtons
                                buttonLayout="horizontal"
                                :class="quantity > product.stock ? 'border-red-500' : ''"
                            />
                            <div class="ml-4 text-sm text-gray-600">
                                {{ product.stock }} items available
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <Button
                                @click="addToCart"
                                :disabled="product.stock <= 0 || quantity > product.stock"
                                :label="product.stock <= 0 ? 'Out of Stock' : 'Add to Cart'"
                                icon="pi pi-shopping-cart"
                                class="w-full"
                            />

                            <Button
                                @click="buyNow"
                                :disabled="product.stock <= 0 || quantity > product.stock"
                                :label="product.stock <= 0 ? 'Out of Stock' : 'Buy Now'"
                                icon="pi pi-bolt"
                                severity="success"
                                class="w-full"
                            />
                        </div>
                    </div>

                    <!-- Trust Indicators -->
                    <div class="flex items-center justify-between text-sm">
                        <div class="flex items-center">
                            <i class="pi pi-shield mr-2 text-gray-700"></i>
                            <span class="text-gray-600">Secure Payment</span>
                        </div>
                        <div class="flex items-center">
                            <i class="pi pi-refresh mr-2 text-gray-700"></i>
                            <span class="text-gray-600">30-day Returns</span>
                        </div>
                        <div class="flex items-center">
                            <i class="pi pi-truck mr-2 text-gray-700"></i>
                            <span class="text-gray-600">Fast Shipping</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Details Tabs -->
            <div class="bg-white rounded-lg border border-gray-200 shadow-sm overflow-hidden mb-10">
                <TabView class="professional-tabs">
                    <!-- Description Tab -->
                    <TabPanel header="Description">
                        <div class="p-6">
                            <div class="prose max-w-none">
                                <h3 class="text-xl font-bold text-gray-900 mb-4">
                                    Product Overview
                                </h3>
                                <p class="text-gray-700 mb-6">
                                    {{ getCleanDescription(product.description) || 'This premium product offers exceptional quality and modern design, crafted with attention to detail and built to last. Experience superior performance and style.' }}
                                </p>

                                <div class="grid md:grid-cols-2 gap-6 mt-8">
                                    <!-- Dynamic Feature Types -->
                                    <div v-for="(points, type) in product.descriptionPoints" :key="type" class="mb-6">
                                        <h4 class="font-bold text-gray-900 mb-3 flex items-center">
                                            <span v-if="product.featureTypes && product.featureTypes[type]" class="mr-2">
                                                {{ product.featureTypes[type].icon }}
                                            </span>
                                            {{ product.featureTypes && product.featureTypes[type] ? product.featureTypes[type].label : type.charAt(0).toUpperCase() + type.slice(1) }}
                                        </h4>
                                        <ul class="list-disc pl-5 text-gray-700 space-y-2">
                                            <li v-for="point in points" :key="point.id">
                                                {{ point.text }}
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Fallback for products without structured points -->
                                    <div v-if="!product.descriptionPoints || Object.keys(product.descriptionPoints).length === 0">
                                        <div>
                                            <h4 class="font-bold text-gray-900 mb-3">
                                                Key Features
                                            </h4>
                                            <ul class="list-disc pl-5 text-gray-700 space-y-2">
                                                <li>Premium quality materials and construction</li>
                                                <li>Modern design with attention to detail</li>
                                                <li>Excellent performance and durability</li>
                                                <li>Backed by manufacturer warranty</li>
                                            </ul>
                                        </div>

                                        <div>
                                            <h4 class="font-bold text-gray-900 mb-3">
                                                What's Included
                                            </h4>
                                            <ul class="list-disc pl-5 text-gray-700 space-y-2">
                                                <li>{{ product.name }} (1x)</li>
                                                <li>User manual and documentation</li>
                                                <li>Manufacturer warranty card</li>
                                                <li>Premium packaging</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TabPanel>

                    <!-- Specifications Tab -->
                    <TabPanel header="Specifications">
                        <div class="p-6">
                            <div class="grid lg:grid-cols-2 gap-8">
                                <!-- Basic Information -->
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-4">
                                        Basic Information
                                    </h4>

                                    <table class="w-full text-left border-collapse">
                                        <tbody>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-3 font-medium text-gray-900 w-1/3">Brand</td>
                                                <td class="py-3 text-gray-700">{{ product.brand?.name || 'N/A' }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-3 font-medium text-gray-900">SKU</td>
                                                <td class="py-3 text-gray-700">{{ product.sku || product.value || 'N/A' }}</td>
                                            </tr>
                                            <tr class="border-b border-gray-200">
                                                <td class="py-3 font-medium text-gray-900">Stock</td>
                                                <td class="py-3 text-gray-700">{{ product.stock || 0 }} units</td>
                                            </tr>
                                            <tr>
                                                <td class="py-3 font-medium text-gray-900">Availability</td>
                                                <td class="py-3">
                                                    <Badge
                                                        :value="product.stock > 0 ? 'In Stock' : 'Out of Stock'"
                                                        :severity="product.stock > 0 ? 'success' : 'danger'"
                                                    />
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Product Attributes -->
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-4">
                                        Product Specifications
                                    </h4>

                                    <!-- Dynamic Specification Points -->
                                    <div v-if="product.descriptionPoints && product.descriptionPoints.specification" class="mb-6">
                                        <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                                            <h5 class="font-semibold text-blue-900 mb-3 flex items-center">
                                                <span v-if="product.featureTypes && product.featureTypes.specification" class="mr-2">
                                                    {{ product.featureTypes.specification.icon }}
                                                </span>
                                                {{ product.featureTypes && product.featureTypes.specification ? product.featureTypes.specification.label : 'Specifications' }}
                                            </h5>
                                            <ul class="text-blue-800 space-y-2">
                                                <li v-for="point in product.descriptionPoints.specification" :key="point.id" class="flex items-start">
                                                    <span class="text-blue-600 mr-2">•</span>
                                                    {{ point.text }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <!-- Attribute-based Specifications -->
                                    <table v-if="product.attributes && product.attributes.length > 0" class="w-full text-left border-collapse">
                                        <tbody>
                                            <tr v-for="attr in product.attributes" :key="attr.id" class="border-b border-gray-200">
                                                <td class="py-3 font-medium text-gray-900 w-1/3">
                                                    {{ attr.attribute_name || attr.name || 'N/A' }}
                                                </td>
                                                <td class="py-3">
                                                    <div v-if="attr.attribute_values && attr.attribute_values.length > 0" class="flex flex-wrap gap-2">
                                                        <Badge
                                                            v-for="value in attr.attribute_values"
                                                            :key="value.id"
                                                            :value="value.value"
                                                            severity="info"
                                                        />
                                                    </div>
                                                    <span v-else class="text-gray-500">Not specified</span>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>

                                    <div v-else-if="!product.descriptionPoints || !product.descriptionPoints.specification" class="text-center py-6 bg-gray-50 rounded-lg border border-gray-200">
                                        <p class="text-gray-500">No specifications available for this product.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </TabPanel>

                    <!-- Reviews Tab -->
                    <TabPanel header="Reviews">
                        <div class="p-6 text-center">
                            <div class="py-8">
                                <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <i class="pi pi-star text-yellow-500 text-xl"></i>
                                </div>
                                <h5 class="text-xl font-bold text-gray-900 mb-2">Customer Reviews Coming Soon</h5>
                                <p class="text-gray-600 max-w-md mx-auto mb-6">Be the first to review this product and help other customers make informed decisions.</p>
                                <Button
                                    label="Write a Review"
                                    icon="pi pi-pencil"
                                    outlined
                                />
                            </div>
                        </div>
                    </TabPanel>

                    <!-- Shipping & Returns Tab -->
                    <TabPanel header="Shipping & Returns">
                        <div class="p-6">
                            <div class="grid md:grid-cols-2 gap-8">
                                <!-- Shipping Information -->
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-4">
                                        Shipping Information
                                    </h4>
                                    <ul class="space-y-4">
                                        <li class="flex items-start">
                                            <i class="pi pi-check-circle text-green-600 mt-0.5 mr-2"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Free Shipping</h5>
                                                <p class="text-gray-600 text-sm">On orders over $99. Standard delivery 3-5 business days.</p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="pi pi-bolt text-blue-600 mt-0.5 mr-2"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">Express Shipping</h5>
                                                <p class="text-gray-600 text-sm">$15.99 - Next day delivery for orders placed before 2 PM.</p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="pi pi-globe text-purple-600 mt-0.5 mr-2"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">International Shipping</h5>
                                                <p class="text-gray-600 text-sm">Available to 50+ countries. 7-14 business days.</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <!-- Returns & Warranty -->
                                <div>
                                    <h4 class="text-lg font-bold text-gray-900 mb-4">
                                        Returns & Warranty
                                    </h4>
                                    <ul class="space-y-4">
                                        <li class="flex items-start">
                                            <i class="pi pi-refresh text-orange-600 mt-0.5 mr-2"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">30-Day Returns</h5>
                                                <p class="text-gray-600 text-sm">Free returns within 30 days. Items must be in original condition.</p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="pi pi-shield text-blue-600 mt-0.5 mr-2"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">2-Year Warranty</h5>
                                                <p class="text-gray-600 text-sm">Comprehensive warranty covering manufacturing defects.</p>
                                            </div>
                                        </li>
                                        <li class="flex items-start">
                                            <i class="pi pi-headphones text-gray-600 mt-0.5 mr-2"></i>
                                            <div>
                                                <h5 class="font-medium text-gray-900">24/7 Support</h5>
                                                <p class="text-gray-600 text-sm">Round-the-clock customer support for any issues.</p>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </TabPanel>
                </TabView>
            </div>
        </div>

        <!-- Error State -->
        <div v-else class="min-h-screen flex items-center justify-center">
            <Card class="text-center max-w-md mx-auto shadow border-0">
                <template #content>
                    <div class="space-y-4">
                        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto">
                            <i class="pi pi-exclamation-triangle text-2xl text-red-500"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Product Not Found</h2>
                        <p class="text-gray-600">The product you're looking for doesn't exist or has been removed from our catalog.</p>
                        <Button
                            label="Browse Products"
                            icon="pi pi-arrow-left"
                            @click="$inertia.visit('/')"
                        />
                    </div>
                </template>
            </Card>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'
import { router } from '@inertiajs/vue3'
import navbar from '@/Pages/navbar.vue'
import Image from 'primevue/image'
import Button from 'primevue/button'
import Chip from 'primevue/chip'
import Tag from 'primevue/tag'
import InputNumber from 'primevue/inputnumber'
import TabView from 'primevue/tabview'
import TabPanel from 'primevue/tabpanel'
import Card from 'primevue/card'
import Badge from 'primevue/badge'
import Toast from 'primevue/toast'
import { useToast } from 'primevue/usetoast'
import RadioButton from 'primevue/radiobutton'

const navbarRef = ref(null);

// Props
const props = defineProps({
    productId: Number
})

const product = ref({})
const quantity = ref(1)
const activeTabIndex = ref(0)
const selectedImage = ref(null)
const loading = ref(true)
const selectedColor = ref(null)
const availableColors = ref([])
const selectedSize = ref(null)
const toast = useToast();

const fetchProduct = async () => {
    try {
        const response = await axios.get(`/buyeProduct/${props.productId}`)
        product.value = response.data
    } catch (error) {
        console.error('Failed to fetch product:', error)
    } finally {
        loading.value = false
    }
}

// Computed properties
const currentDisplayImage = computed(() => {
    if (selectedImage.value) {
        return `/storage/${selectedImage.value}`
    }
    const primaryImage = product.value.images?.find(img => img.is_primary === 1)
    if (primaryImage) {
        return `/storage/${primaryImage.image_path}`
    }
    if (product.value.images?.length > 0) {
        return `/storage/${product.value.images[0].image_path}`
    }
    return 'https://via.placeholder.com/400x400'
})

// Color attribute
const colorAttribute = computed(() => {
    if (!product.value.attributes) return null
    return product.value.attributes.find(attr =>
        attr.attribute_name?.toLowerCase().includes('color') ||
        attr.attribute_name?.toLowerCase().includes('colour') ||
        attr.name?.toLowerCase().includes('color') ||
        attr.name?.toLowerCase().includes('colour') ||
        attr.type?.toLowerCase() === 'color'
    )
})

// Available colors
const getAvailableColors = computed(() => {
    if (!colorAttribute.value?.attribute_values) return []

    return colorAttribute.value.attribute_values.map(value => ({
        id: value.id,
        name: value.value || value.attribute_values || value.name,
        colorCode: getColorCode(value.value || value.attribute_values || value.name)
    }))
})

// Size attribute
const sizeAttribute = computed(() => {
    if (!product.value.attributes) return null
    return product.value.attributes.find(attr =>
        attr.attribute_name?.toLowerCase().includes('size') ||
        attr.name?.toLowerCase().includes('size')
    )
})

// Available sizes
const getAvailableSizes = computed(() => {
    if (!sizeAttribute.value?.attribute_values) return []

    return sizeAttribute.value.attribute_values.map(value => ({
        id: value.id,
        name: value.value || value.attribute_values || value.name
    }))
})

// Methods
const getColorCode = (colorName) => {
    if (!colorName) return '#6b7280'

    const colorMap = {
        'red': '#ef4444',
        'blue': '#3b82f6',
        'green': '#10b981',
        'black': '#000000',
        'white': '#ffffff',
        'yellow': '#eab308',
        'purple': '#8b5cf6',
        'pink': '#ec4899',
        'gray': '#6b7280',
        'grey': '#6b7280',
        'orange': '#f97316',
        'brown': '#a3a3a3',
        'navy': '#1e40af',
        'silver': '#c0c0c0',
        'gold': '#ffd700',
        'maroon': '#800000',
        'lime': '#00ff00',
        'olive': '#808000',
        'aqua': '#00ffff',
        'teal': '#008080',
        'fuchsia': '#ff00ff',
        'cyan': '#00ffff'
    }

    const lowerColorName = colorName.toLowerCase().trim()
    return colorMap[lowerColorName] || '#6b7280'
}

const selectColor = (color) => {
    selectedColor.value = color
}

const selectSize = (size) => {
    selectedSize.value = size
}

const addToCart = async () => {
    try {
        // Validate stock
        if (product.value.stock <= 0) {
            toast.add({
                severity: 'warn',
                summary: 'Out of Stock',
                detail: 'This product is currently out of stock',
                life: 3000
            });
            return;
        }

        if (quantity.value > product.value.stock) {
            toast.add({
                severity: 'warn',
                summary: 'Insufficient Stock',
                detail: `Only ${product.value.stock} items available in stock`,
                life: 3000
            });
            return;
        }

        // Validate required attributes
        if (getAvailableColors.value.length > 0 && !selectedColor.value) {
            toast.add({
                severity: 'warn',
                summary: 'Color Required',
                detail: 'Please select a color for this product',
                life: 3000
            });
            return;
        }

        if (getAvailableSizes.value.length > 0 && !selectedSize.value) {
            toast.add({
                severity: 'warn',
                summary: 'Size Required',
                detail: 'Please select a size for this product',
                life: 3000
            });
            return;
        }

        // Prepare cart data
        const cartData = {
            product_id: product.value.id,
            quantity: quantity.value
        };

        // Add selected color if available
        if (selectedColor.value) {
            cartData.color_id = selectedColor.value.id;
            cartData.color_name = selectedColor.value.name;
        }

        // Add selected size if available
        if (selectedSize.value) {
            cartData.size_id = selectedSize.value.id;
            cartData.size_name = selectedSize.value.name;
        }

        const response = await axios.post('/api/cart/add', cartData);

        toast.add({
            severity: 'success',
            summary: 'Added to Cart',
            detail: `${product.value.name} has been added to your cart${selectedColor.value ? ` in ${selectedColor.value.name}` : ''}`,
            life: 3000
        });

        // Update navbar cart count in real-time
        if (navbarRef.value) {
            navbarRef.value.fetchCartItemCount();
        }

    } catch (error) {
        if (error.response?.status === 422) {
            toast.add({
                severity: 'error',
                summary: 'Validation Error',
                detail: error.response.data.message || 'Invalid product data',
                life: 3000
            });
        } else {
            toast.add({
                severity: 'error',
                summary: 'Error',
                detail: 'Failed to add product to cart. Please try again.',
                life: 3000
            });
        }
    }
};

const buyNow = () => {
    if (product.value) {
        // Validate stock
        if (product.value.stock <= 0) {
            toast.add({
                severity: 'warn',
                summary: 'Out of Stock',
                detail: 'This product is currently out of stock',
                life: 3000
            });
            return;
        }

        if (quantity.value > product.value.stock) {
            toast.add({
                severity: 'warn',
                summary: 'Insufficient Stock',
                detail: `Only ${product.value.stock} items available in stock`,
                life: 3000
            });
            return;
        }

        // Validate required attributes
        if (getAvailableColors.value.length > 0 && !selectedColor.value) {
            toast.add({
                severity: 'warn',
                summary: 'Color Required',
                detail: 'Please select a color for this product',
                life: 3000
            });
            return;
        }

        if (getAvailableSizes.value.length > 0 && !selectedSize.value) {
            toast.add({
                severity: 'warn',
                summary: 'Size Required',
                detail: 'Please select a size for this product',
                life: 3000
            });
            return;
        }

        const params = {
            product: product.value,
            quantity: quantity.value
        };

        // Collect all selected attribute values
        const productAttrs = [];

        // Add selected color attribute value ID
        if (selectedColor.value) {
            productAttrs.push(selectedColor.value.id);
        }

        // Add selected size attribute value ID
        if (selectedSize.value) {
            productAttrs.push(selectedSize.value.id);
        }

        // Add collected attributes to params
        if (productAttrs.length > 0) {
            params.product_attrs = productAttrs;
        }

        console.log('Buy Now - Selected attributes:', productAttrs);
        console.log('Buy Now - All params:', params);

        router.get(route('GADGET-GEARS.orderForm'), params);
    }
}

// Helper function to clean description from JSON data
const getCleanDescription = (description) => {
    if (!description) return '';

    // Check if description contains JSON data (starts with [ or {)
    if (description.trim().startsWith('[') || description.trim().startsWith('{')) {
        return ''; // Return empty string to use fallback description
    }

    return description;
}

// Component lifecycle
onMounted(() => {
    fetchProduct().then(() => {
        // First color কে default হিসেবে select করা
        if (getAvailableColors.value.length > 0) {
            selectedColor.value = getAvailableColors.value[0]
        }
        // First size কে default হিসেবে select করা
        if (getAvailableSizes.value.length > 0) {
            selectedSize.value = getAvailableSizes.value[0]
        }
    })
})
</script>

<style scoped>
/* Minimal styles for tabs */
:deep(.p-tabview-nav) {
    border-bottom: 1px solid #e5e7eb;
}

:deep(.p-tabview-nav-link) {
    color: #4b5563;
    padding: 1rem;
    font-weight: 500;
    transition: all 0.2s;
}

:deep(.p-tabview-nav-link:hover) {
    color: #1f2937;
    background-color: #f9fafb;
}

:deep(.p-tabview .p-tabview-nav li.p-highlight .p-tabview-nav-link) {
    color: #2563eb;
    border-bottom-color: #2563eb;
    border-width: 2px;
}

:deep(.p-inputnumber-buttons-horizontal .p-inputnumber-input) {
    width: 4rem;
    text-align: center;
}
</style>
