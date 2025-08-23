<template>
    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-indigo-50">
        <!-- Professional Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-4">
                        <button @click="$inertia.visit('/')" class="p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="pi pi-arrow-left text-gray-600"></i>
                        </button>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">Checkout</h1>
                            <p class="text-gray-600">Secure & Fast Ordering</p>
                        </div>
                    </div>

                    <!-- Progress Steps -->
                    <div class="hidden md:flex items-center space-x-8">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <i class="pi pi-check text-white text-sm"></i>
                            </div>
                            <span class="text-sm font-medium text-green-600">Product Selected</span>
                        </div>
                        <div class="w-12 h-0.5 bg-green-500"></div>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">2</span>
                            </div>
                            <span class="text-sm font-medium text-blue-600">Order Details</span>
                        </div>
                        <div class="w-12 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-500 text-sm font-bold">3</span>
                            </div>
                            <span class="text-sm font-medium text-gray-500">Payment</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Order Summary - Right Side -->
                <div class="lg:order-2">
                    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden sticky top-8">
                        <!-- Order Summary Header -->
                        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 px-6 py-4">
                            <h3 class="text-lg font-bold text-white flex items-center">
                                <i class="pi pi-shopping-bag mr-2"></i>
                                Order Summary
                            </h3>
                        </div>

                        <div class="p-6 space-y-6">
                            <!-- Product Details -->
                            <div v-if="product" class="space-y-4">
                                <!-- Product Image & Info -->
                                <div class="flex space-x-4">
                                    <div class="relative">
                                        <img
                                            :src="getProductImage(product)"
                                            :alt="product.name"
                                            class="w-20 h-20 object-cover rounded-xl border-2 border-gray-200"
                                        />
                                        <div class="absolute -top-2 -right-2 bg-blue-500 text-white text-xs font-bold rounded-full w-6 h-6 flex items-center justify-center">
                                            {{ quantity }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-lg font-bold text-gray-900 truncate">{{ product.name }}</h4>
                                        <p class="text-sm text-gray-600">{{ product.brand?.name }}</p>
                                        <div class="flex items-center space-x-2 mt-1">
                                            <span class="text-lg font-bold text-gray-900">
                                                ${{ product.sale_price || product.price }}
                                            </span>
                                            <span v-if="product.sale_price && product.price > product.sale_price"
                                                  class="text-sm text-gray-500 line-through">
                                                ${{ product.price }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Selected Options -->
                                <div class="bg-gray-50 rounded-xl p-4 space-y-3">
                                    <h5 class="text-sm font-bold text-gray-900 flex items-center">
                                        <i class="pi pi-cog mr-2 text-blue-500"></i>
                                        Selected Options
                                    </h5>

                                    <div class="space-y-2">
                                        <!-- Color Selection -->
                                        <div v-if="colorName" class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Color:</span>
                                            <div class="flex items-center space-x-2">
                                                <div
                                                    :style="{ backgroundColor: getColorCode(colorName) }"
                                                    class="w-4 h-4 rounded-full border border-gray-300"
                                                ></div>
                                                <span class="text-sm font-medium text-gray-900 capitalize">{{ colorName }}</span>
                                            </div>
                                        </div>

                                        <!-- Size Selection -->
                                        <div v-if="sizeName" class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Size:</span>
                                            <span class="text-sm font-medium text-gray-900 bg-gray-200 px-2 py-1 rounded">{{ sizeName }}</span>
                                        </div>

                                        <!-- Quantity -->
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-600">Quantity:</span>
                                            <span class="text-sm font-medium text-gray-900">{{ quantity }} items</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Price Breakdown -->
                                <div class="border-t border-gray-200 pt-4 space-y-3">
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Subtotal:</span>
                                        <span class="font-medium text-gray-900">${{ subtotal.toFixed(2) }}</span>
                                    </div>

                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">Shipping:</span>
                                        <span class="font-medium text-green-600">FREE</span>
                                    </div>

                                    <div v-if="discount > 0" class="flex justify-between text-sm">
                                        <span class="text-gray-600">Discount:</span>
                                        <span class="font-medium text-green-600">-${{ discount.toFixed(2) }}</span>
                                    </div>

                                    <div class="border-t border-gray-200 pt-3">
                                        <div class="flex justify-between">
                                            <span class="text-lg font-bold text-gray-900">Total:</span>
                                            <span class="text-2xl font-bold text-blue-600">${{ total.toFixed(2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Security Features -->
                                <div class="bg-green-50 rounded-xl p-4 border border-green-200">
                                    <div class="flex items-center space-x-3">
                                        <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                            <i class="pi pi-shield text-green-600"></i>
                                        </div>
                                        <div>
                                            <h6 class="text-sm font-bold text-green-800">Secure Checkout</h6>
                                            <p class="text-xs text-green-600">256-bit SSL encryption</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Form - Left Side -->
                <div class="lg:col-span-2 lg:order-1">
                    <form @submit.prevent="submitOrder" class="space-y-8">
                        <!-- Customer Information -->
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-gray-900 to-gray-700 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <i class="pi pi-user mr-2"></i>
                                    Customer Information
                                </h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            First Name *
                                        </label>
                                        <input
                                            v-model="form.firstName"
                                            type="text"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="Enter your first name"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            Last Name *
                                        </label>
                                        <input
                                            v-model="form.lastName"
                                            type="text"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="Enter your last name"
                                        />
                                    </div>
                                </div>

                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            Email Address *
                                        </label>
                                        <input
                                            v-model="form.email"
                                            type="email"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="your@email.com"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            Phone Number *
                                        </label>
                                        <input
                                            v-model="form.phone"
                                            type="tel"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="+1 (555) 000-0000"
                                        />
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <i class="pi pi-map-marker mr-2"></i>
                                    Shipping Address
                                </h3>
                            </div>

                            <div class="p-6 space-y-6">
                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">
                                        Street Address *
                                    </label>
                                    <input
                                        v-model="form.address"
                                        type="text"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        placeholder="123 Main Street"
                                    />
                                </div>

                                <div class="grid md:grid-cols-3 gap-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            City *
                                        </label>
                                        <input
                                            v-model="form.city"
                                            type="text"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="New York"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            State/Province *
                                        </label>
                                        <input
                                            v-model="form.state"
                                            type="text"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="NY"
                                        />
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            ZIP/Postal Code *
                                        </label>
                                        <input
                                            v-model="form.zipCode"
                                            type="text"
                                            required
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            placeholder="10001"
                                        />
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-bold text-gray-900 mb-2">
                                        Country *
                                    </label>
                                    <select
                                        v-model="form.country"
                                        required
                                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                    >
                                        <option value="">Select Country</option>
                                        <option value="US">United States</option>
                                        <option value="CA">Canada</option>
                                        <option value="UK">United Kingdom</option>
                                        <option value="BD">Bangladesh</option>
                                        <option value="IN">India</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <i class="pi pi-credit-card mr-2"></i>
                                    Payment Method
                                </h3>
                            </div>

                            <div class="p-6">
                                <div class="grid md:grid-cols-3 gap-4 mb-6">
                                    <label class="relative cursor-pointer">
                                        <input
                                            type="radio"
                                            name="paymentMethod"
                                            value="card"
                                            v-model="form.paymentMethod"
                                            class="sr-only"
                                        />
                                        <div class="border-2 rounded-xl p-4 text-center transition-all"
                                             :class="form.paymentMethod === 'card' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                            <i class="pi pi-credit-card text-2xl mb-2"
                                               :class="form.paymentMethod === 'card' ? 'text-blue-600' : 'text-gray-400'"></i>
                                            <div class="text-sm font-medium">Credit Card</div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer">
                                        <input
                                            type="radio"
                                            name="paymentMethod"
                                            value="paypal"
                                            v-model="form.paymentMethod"
                                            class="sr-only"
                                        />
                                        <div class="border-2 rounded-xl p-4 text-center transition-all"
                                             :class="form.paymentMethod === 'paypal' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                            <i class="pi pi-paypal text-2xl mb-2"
                                               :class="form.paymentMethod === 'paypal' ? 'text-blue-600' : 'text-gray-400'"></i>
                                            <div class="text-sm font-medium">PayPal</div>
                                        </div>
                                    </label>

                                    <label class="relative cursor-pointer">
                                        <input
                                            type="radio"
                                            name="paymentMethod"
                                            value="cod"
                                            v-model="form.paymentMethod"
                                            class="sr-only"
                                        />
                                        <div class="border-2 rounded-xl p-4 text-center transition-all"
                                             :class="form.paymentMethod === 'cod' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300'">
                                            <i class="pi pi-money-bill text-2xl mb-2"
                                               :class="form.paymentMethod === 'cod' ? 'text-blue-600' : 'text-gray-400'"></i>
                                            <div class="text-sm font-medium">Cash on Delivery</div>
                                        </div>
                                    </label>
                                </div>

                                <!-- Credit Card Form -->
                                <div v-if="form.paymentMethod === 'card'" class="space-y-4 border-t border-gray-200 pt-6">
                                    <div>
                                        <label class="block text-sm font-bold text-gray-900 mb-2">
                                            Card Number *
                                        </label>
                                        <input
                                            v-model="form.cardNumber"
                                            type="text"
                                            placeholder="1234 5678 9012 3456"
                                            class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                        />
                                    </div>

                                    <div class="grid md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                                Expiry Date *
                                            </label>
                                            <input
                                                v-model="form.expiryDate"
                                                type="text"
                                                placeholder="MM/YY"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            />
                                        </div>

                                        <div>
                                            <label class="block text-sm font-bold text-gray-900 mb-2">
                                                CVV *
                                            </label>
                                            <input
                                                v-model="form.cvv"
                                                type="text"
                                                placeholder="123"
                                                class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Order Notes -->
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                            <div class="bg-gradient-to-r from-orange-500 to-red-500 px-6 py-4">
                                <h3 class="text-lg font-bold text-white flex items-center">
                                    <i class="pi pi-comment mr-2"></i>
                                    Order Notes (Optional)
                                </h3>
                            </div>

                            <div class="p-6">
                                <textarea
                                    v-model="form.notes"
                                    rows="4"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none"
                                    placeholder="Any special instructions for your order..."
                                ></textarea>
                            </div>
                        </div>

                        <!-- Submit Section -->
                        <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <input
                                        id="terms"
                                        v-model="form.acceptTerms"
                                        type="checkbox"
                                        required
                                        class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                                    />
                                    <label for="terms" class="ml-2 text-sm text-gray-700">
                                        I agree to the
                                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Terms & Conditions</a>
                                        and
                                        <a href="#" class="text-blue-600 hover:text-blue-800 font-medium">Privacy Policy</a>
                                    </label>
                                </div>

                                <button
                                    type="submit"
                                    :disabled="!form.acceptTerms || loading"
                                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 disabled:from-gray-400 disabled:to-gray-500 text-white font-bold py-4 px-8 rounded-xl shadow-lg transform hover:scale-[1.02] disabled:transform-none transition-all duration-200 flex items-center justify-center space-x-2"
                                >
                                    <i v-if="loading" class="pi pi-spin pi-spinner"></i>
                                    <i v-else class="pi pi-check"></i>
                                    <span>{{ loading ? 'Processing...' : 'Complete Order' }}</span>
                                </button>

                                <p class="text-xs text-gray-500 text-center mt-4">
                                    <i class="pi pi-shield mr-1"></i>
                                    Your payment information is secure and encrypted
                                </p>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router, usePage } from '@inertiajs/vue3'
import { useToast } from 'primevue/usetoast'

// Props from route parameters
const page = usePage()
const toast = useToast()

// Reactive data
const product = ref(null)
const quantity = ref(1)
const colorName = ref('')
const sizeName = ref('')
const colorId = ref(null)
const sizeId = ref(null)
const loading = ref(false)

// Form data
const form = ref({
    firstName: '',
    lastName: '',
    email: '',
    phone: '',
    address: '',
    city: '',
    state: '',
    zipCode: '',
    country: '',
    paymentMethod: 'card',
    cardNumber: '',
    expiryDate: '',
    cvv: '',
    notes: '',
    acceptTerms: false
})

// Get route parameters on component mount
onMounted(() => {
    const params = new URLSearchParams(window.location.search)

    // Get product data from params
    const productData = params.get('product')
    if (productData) {
        product.value = JSON.parse(decodeURIComponent(productData))
    }

    // Get other parameters
    quantity.value = parseInt(params.get('quantity')) || 1
    colorName.value = params.get('color_name') || ''
    sizeName.value = params.get('size_name') || ''
    colorId.value = params.get('color_id')
    sizeId.value = params.get('size_id')
})

// Computed properties
const subtotal = computed(() => {
    if (!product.value) return 0
    const price = product.value.sale_price || product.value.price
    return price * quantity.value
})

const discount = computed(() => {
    // Apply discount logic if needed
    return 0
})

const total = computed(() => {
    return subtotal.value - discount.value
})

// Methods
const getProductImage = (product) => {
    if (product?.images && product.images.length > 0) {
        return `/storage/${product.images[0].image_path}`
    }
    return '/images/default-product.jpg'
}

const getColorCode = (colorName) => {
    const colorMap = {
        'red': '#ef4444',
        'blue': '#3b82f6',
        'green': '#10b981',
        'yellow': '#f59e0b',
        'purple': '#8b5cf6',
        'pink': '#ec4899',
        'black': '#1f2937',
        'white': '#f9fafb',
        'gray': '#6b7280',
        'orange': '#f97316'
    }

    const color = colorName?.toLowerCase() || 'gray'
    return colorMap[color] || '#6b7280'
}

const submitOrder = async () => {
    if (!form.value.acceptTerms) {
        toast.add({
            severity: 'warn',
            summary: 'Terms Required',
            detail: 'Please accept the terms and conditions',
            life: 3000
        })
        return
    }

    loading.value = true

    try {
        const orderData = {
            product_id: product.value?.id,
            quantity: quantity.value,
            color_id: colorId.value,
            size_id: sizeId.value,
            customer_info: {
                first_name: form.value.firstName,
                last_name: form.value.lastName,
                email: form.value.email,
                phone: form.value.phone
            },
            shipping_address: {
                address: form.value.address,
                city: form.value.city,
                state: form.value.state,
                zip_code: form.value.zipCode,
                country: form.value.country
            },
            payment_method: form.value.paymentMethod,
            payment_details: form.value.paymentMethod === 'card' ? {
                card_number: form.value.cardNumber,
                expiry_date: form.value.expiryDate,
                cvv: form.value.cvv
            } : null,
            notes: form.value.notes,
            total_amount: total.value
        }

        // Submit order (replace with actual API endpoint)
        const response = await fetch('/api/orders', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content')
            },
            body: JSON.stringify(orderData)
        })

        if (response.ok) {
            toast.add({
                severity: 'success',
                summary: 'Order Placed',
                detail: 'Your order has been successfully placed!',
                life: 3000
            })

            // Redirect to success page
            router.visit('/order-success')
        } else {
            throw new Error('Order submission failed')
        }
    } catch (error) {
        console.error('Order submission error:', error)
        toast.add({
            severity: 'error',
            summary: 'Order Failed',
            detail: 'Failed to place order. Please try again.',
            life: 3000
        })
    } finally {
        loading.value = false
    }
}
</script>

<style scoped>
/* Custom animations and transitions */
.slide-fade-enter-active {
    transition: all 0.3s ease-out;
}

.slide-fade-leave-active {
    transition: all 0.3s cubic-bezier(1.0, 0.5, 0.8, 1.0);
}

.slide-fade-enter-from,
.slide-fade-leave-to {
    transform: translateX(20px);
    opacity: 0;
}

/* Custom gradient text */
.gradient-text {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

/* Custom button hover effects */
.btn-hover {
    transition: all 0.2s ease-in-out;
}

.btn-hover:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
}

/* Custom input focus states */
input:focus, select:focus, textarea:focus {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.15);
}
</style>
