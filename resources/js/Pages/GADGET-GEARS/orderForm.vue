<template>
    <div class="min-h-screen bg-gray-50 py-10">
        <div class="max-w-6xl mx-auto px-4">
            <!-- Header -->
            <div class="text-center mb-10">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-600 rounded-full mb-4">
                    <i class="pi pi-shopping-cart text-white text-xl"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900">
                    Complete Your Order
                </h1>
                <p class="text-gray-600 mt-2">Secure checkout in just a few steps</p>

                <!-- Progress Steps -->
                <div class="flex justify-center mt-6">
                    <div class="flex items-center">
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <i class="pi pi-check text-white text-sm"></i>
                            </div>
                            <span class="ml-2 text-sm font-medium text-blue-700">Cart</span>
                        </div>
                        <div class="w-16 h-0.5 bg-blue-600"></div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center">
                                <span class="text-white text-sm font-bold">2</span>
                            </div>
                            <span class="ml-2 text-sm font-medium text-blue-700">Checkout</span>
                        </div>
                        <div class="w-16 h-0.5 bg-gray-300"></div>
                        <div class="flex items-center">
                            <div class="w-8 h-8 bg-gray-300 rounded-full flex items-center justify-center">
                                <span class="text-gray-500 text-sm font-bold">3</span>
                            </div>
                            <span class="ml-2 text-sm font-medium text-gray-500">Complete</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid lg:grid-cols-3 gap-8">
                <!-- Order Form -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                        <div class="p-6">
                            <!-- Section Header -->
                            <div class="flex items-center mb-6">
                                <i class="pi pi-user text-blue-600 text-lg mr-3"></i>
                                <div>
                                    <h2 class="text-xl font-bold text-gray-900">Shipping Information</h2>
                                    <p class="text-gray-500 text-sm">Please provide your delivery details</p>
                                </div>
                            </div>

                            <form @submit.prevent="confirmOrder" class="space-y-6">
                                <!-- Personal Information Section -->
                                <div class="bg-white border border-gray-200 rounded-lg p-5">
                                    <h3 class="text-base font-medium text-gray-800 mb-4">
                                        Personal Information
                                    </h3>
                                    <div class="grid md:grid-cols-2 gap-6">
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Full Name *</label>
                                            <InputText
                                                v-model="orderForm.name"
                                                required
                                                class="w-full"
                                                placeholder="Enter your full name"
                                            />
                                        </div>
                                        <div class="space-y-2">
                                            <label class="block text-sm font-medium text-gray-700">Phone Number *</label>
                                            <InputText
                                                v-model="orderForm.phone"
                                                type="tel"
                                                required
                                                class="w-full"
                                                placeholder="+880 1XXX-XXXXXX"
                                            />
                                        </div>
                                    </div>
                                    <div class="mt-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                        <InputText
                                            v-model="orderForm.email"
                                            type="email"
                                            required
                                            class="w-full"
                                            placeholder="your.email@example.com"
                                        />
                                    </div>
                                </div>

                                <!-- Address Information Section -->
                                <div class="bg-white border border-gray-200 rounded-lg p-5">
                                    <h3 class="text-base font-medium text-gray-800 mb-4">
                                        Delivery Address
                                    </h3>
                                    <div class="space-y-6">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Street Address *</label>
                                            <Textarea
                                                v-model="orderForm.address"
                                                required
                                                rows="3"
                                                class="w-full"
                                                placeholder="House/Flat no, Road, Area..."
                                            />
                                        </div>
                                        <div class="grid md:grid-cols-3 gap-4">
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">বিভাগ *</label>
                                                <Dropdown
                                                    v-model="orderForm.division"
                                                    :options="divisions"
                                                    optionLabel="Division"
                                                    optionValue="ID"
                                                    placeholder="Select Division"
                                                    @change="onDivisionChange"
                                                    class="w-full"
                                                    :loading="!divisions.length"
                                                />
                                            </div>
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">জেলা *</label>
                                                <Dropdown
                                                    v-model="orderForm.district"
                                                    :options="districts"
                                                    optionLabel="District"
                                                    optionValue="DID"
                                                    placeholder="Select District"
                                                    @change="onDistrictChange"
                                                    class="w-full"
                                                    :disabled="!orderForm.division"
                                                />
                                            </div>
                                            <div class="space-y-2">
                                                <label class="block text-sm font-medium text-gray-700">থানা *</label>
                                                <Dropdown
                                                    v-model="orderForm.thana"
                                                    :options="thanas"
                                                    optionLabel="Thana"
                                                    optionValue="Thana_ID"
                                                    placeholder="Select Thana"
                                                    class="w-full"
                                                    :disabled="!orderForm.district"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Payment Method Section -->
                                <div class="bg-white border border-gray-200 rounded-lg p-5">
                                    <h3 class="text-base font-medium text-gray-800 mb-4">
                                        Payment Method
                                    </h3>
                                    <Dropdown
                                        v-model="orderForm.payment_method"
                                        :options="[
                                            { label: '💵 Cash on Delivery', value: 'cash_on_delivery' },
                                            { label: '💳 Credit/Debit Card', value: 'card' },
                                            { label: '📱 Mobile Banking', value: 'mobile_banking' }
                                        ]"
                                        optionLabel="label"
                                        optionValue="value"
                                        placeholder="Choose payment method"
                                        class="w-full"
                                    />
                                </div>

                                <!-- Submit Button -->
                                <div class="pt-4">
                                    <Button
                                        type="submit"
                                        class="w-full bg-blue-600 hover:bg-blue-700 border-0 py-3 font-medium"
                                    >
                                        <i class="pi pi-check-circle mr-2"></i>
                                        Confirm Order
                                    </Button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-1">
                    <div class="sticky top-8">
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                            <div class="p-6">
                                <!-- Section Header -->
                                <div class="border-b border-gray-200 pb-4 mb-4">
                                    <div class="flex items-center">
                                        <i class="pi pi-shopping-bag text-blue-600 mr-2"></i>
                                        <h2 class="text-lg font-bold text-gray-900">Order Summary</h2>
                                    </div>
                                </div>

                                <!-- Product Card -->
                                <div class="border border-gray-200 rounded-lg p-4 mb-6 bg-gray-50">
                                    <!-- Product Basic Info -->
                                    <div class="flex items-start space-x-4 mb-4">
                                        <div class="relative">
                                            <img :src="product.image_path"
                                                :alt="product.name"
                                                class="w-20 h-20 object-cover rounded-md border border-gray-200">
                                            <div class="absolute -top-2 -right-2 w-6 h-6 bg-blue-600 text-white rounded-full flex items-center justify-center text-xs font-bold">
                                                {{ quantity }}
                                            </div>
                                        </div>
                                        <div class="flex-1">
                                            <h3 class="font-semibold text-gray-900">{{ product.name }}</h3>
                                            <p class="text-sm text-gray-600 mt-1">Quantity: {{ quantity }}</p>
                                            <p class="text-base font-bold text-blue-600 mt-2">৳{{ product.price }}</p>
                                        </div>
                                    </div>

                                    <!-- Color Selection Section -->
                                    <div v-if="getAvailableColors.length > 0" class="mb-4">
                                        <h4 class="font-medium text-gray-900 mb-3">
                                            Color
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <div
                                                v-for="color in getAvailableColors"
                                                :key="color.id"
                                                @click="selectColor(color)"
                                                :class="selectedColor?.id === color.id ? 'ring-2 ring-blue-500 bg-blue-50' : 'hover:bg-gray-100'"
                                                class="flex items-center space-x-2 p-2 rounded border border-gray-200 cursor-pointer transition-all"
                                            >
                                                <div
                                                    :style="{ backgroundColor: color.colorCode }"
                                                    :class="color.colorCode === '#ffffff' ? 'border border-gray-300' : ''"
                                                    class="w-6 h-6 rounded-full"
                                                ></div>
                                                <span class="text-sm text-gray-700">{{ color.name }}</span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Size Selection Section -->
                                    <div v-if="getAvailableSizes.length > 0" class="mb-4">
                                        <h4 class="font-medium text-gray-900 mb-3">
                                            Size
                                        </h4>
                                        <div class="flex flex-wrap gap-2">
                                            <button
                                                v-for="size in getAvailableSizes"
                                                :key="size.id"
                                                @click="selectSize(size)"
                                                :class="[
                                                    'px-3 py-2 border rounded text-sm',
                                                    selectedSize?.id === size.id
                                                        ? 'bg-blue-600 text-white border-blue-600'
                                                        : 'bg-white text-gray-700 border-gray-300 hover:border-gray-400'
                                                ]"
                                            >
                                                {{ size.name }}
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Selected Options Summary -->
                                    <div v-if="selectedColor || selectedSize" class="mt-4 p-3 bg-gray-100 rounded-lg text-sm">
                                        <div class="flex flex-wrap gap-3">
                                            <div v-if="selectedColor" class="flex items-center">
                                                <span class="text-gray-700">Color:</span>
                                                <span class="font-medium ml-1 text-gray-900">{{ selectedColor.name }}</span>
                                            </div>
                                            <div v-if="selectedSize" class="flex items-center">
                                                <span class="text-gray-700">Size:</span>
                                                <span class="font-medium ml-1 text-gray-900">{{ selectedSize.name }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Pricing Breakdown -->
                                <div class="space-y-4">
                                    <div class="space-y-3 text-sm">
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Subtotal:</span>
                                            <span class="font-medium text-gray-900">৳{{ subtotal }}</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Shipping:</span>
                                            <span class="font-medium text-gray-900">৳10.00</span>
                                        </div>
                                        <div class="flex justify-between items-center">
                                            <span class="text-gray-600">Tax:</span>
                                            <span class="font-medium text-gray-900">৳{{ tax }}</span>
                                        </div>
                                        <Divider />
                                        <div class="flex justify-between items-center">
                                            <span class="font-bold text-gray-900">Total:</span>
                                            <span class="font-bold text-blue-600 text-xl">৳{{ total }}</span>
                                        </div>
                                    </div>

                                    <!-- Order Benefits -->
                                    <div class="border border-gray-200 rounded-lg p-4 bg-gray-50 mt-4">
                                        <h4 class="font-medium text-gray-900 mb-3">
                                            <i class="pi pi-shield mr-1 text-blue-600"></i>
                                            Order Benefits
                                        </h4>
                                        <ul class="space-y-2 text-sm">
                                            <li class="flex items-center text-gray-600">
                                                <i class="pi pi-check-circle mr-2 text-green-600"></i>
                                                Free shipping on orders over ৳50
                                            </li>
                                            <li class="flex items-center text-gray-600">
                                                <i class="pi pi-refresh mr-2 text-blue-600"></i>
                                                30-day return policy
                                            </li>
                                            <li class="flex items-center text-gray-600">
                                                <i class="pi pi-verified mr-2 text-blue-600"></i>
                                                1-year warranty included
                                            </li>
                                        </ul>
                                    </div>

                                    <!-- Security Badge -->
                                    <div class="flex items-center justify-center py-3 border-t border-gray-200 mt-4">
                                        <i class="pi pi-lock text-green-600 mr-2"></i>
                                        <span class="text-xs text-gray-600">Secure SSL Encrypted Checkout</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import axios from 'axios'
import Card from 'primevue/card'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import Dropdown from 'primevue/dropdown'
import Button from 'primevue/button'
import Divider from 'primevue/divider'

const props = defineProps({
    product: Object,
    quantity: {
        type: Number,
        default: 1
    },
    product_attrs: {
        type: Array,
        default: () => []
    }
})

const orderForm = ref({
    product_id: Number(props.product.id),
    quantity: Number(props.quantity),
    product_attrs: props.product_attrs,
    name: '',
    email: '',
    phone: '',
    address: '',
    division: '',
    district: '',
    thana: '',
    postoffice: '',
    payment_method: ''
})

const divisions = ref([])
const districts = ref([])
const thanas = ref([])

// Color and Size selection
const selectedColor = ref(null)
const selectedSize = ref(null)

const subtotal = computed(() => {
    return (props.product.price * props.quantity).toFixed(2)
})

const tax = computed(() => {
    return (subtotal.value * 0.1).toFixed(2)
})

const total = computed(() => {
    return (parseFloat(subtotal.value) + 10 + parseFloat(tax.value)).toFixed(2)
})

// Color attribute খুঁজে বের করার computed property
const colorAttribute = computed(() => {
    if (!props.product.attributes) return null
    return props.product.attributes.find(attr =>
        attr.attribute_name?.toLowerCase().includes('color') ||
        attr.attribute_name?.toLowerCase().includes('colour') ||
        attr.name?.toLowerCase().includes('color') ||
        attr.name?.toLowerCase().includes('colour') ||
        attr.type?.toLowerCase() === 'color'
    )
})

// Available colors থেকে color list তৈরি
const getAvailableColors = computed(() => {
    if (!colorAttribute.value?.attribute_values) return []

    return colorAttribute.value.attribute_values.map(value => ({
        id: value.id,
        name: value.value || value.attribute_values || value.name,
        colorCode: getColorCode(value.value || value.attribute_values || value.name)
    }))
})

// Size attribute খুঁজে বের করার computed property
const sizeAttribute = computed(() => {
    if (!props.product.attributes) return null
    return props.product.attributes.find(attr =>
        attr.attribute_name?.toLowerCase().includes('size') ||
        attr.name?.toLowerCase().includes('size')
    )
})

// Available sizes থেকে size list তৈরি
const getAvailableSizes = computed(() => {
    if (!sizeAttribute.value?.attribute_values) return []

    return sizeAttribute.value.attribute_values.map(value => ({
        id: value.id,
        name: value.value || value.attribute_values || value.name
    }))
})

// Color code mapping function
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
    return colorMap[lowerColorName] || '#6b7280' // default gray
}

const fetchDivisions = async () => {
    try {
        const response = await axios.get('/divisions');
        divisions.value = response.data;
    } catch (error) {
        console.error('Error fetching divisions:', error);
    }
}

const onDivisionChange = async () => {
    const divisionId = orderForm.value.division;
    orderForm.value.district = '';
    orderForm.value.thana = '';
    districts.value = [];
    thanas.value = [];
    if (divisionId) {
        try {
            const response = await axios.get(`/districts/${divisionId}`);
            districts.value = response.data;
        } catch (error) {
            console.error('Error fetching districts:', error);
        }
    }
}

const onDistrictChange = async () => {
    const districtId = orderForm.value.district;
    orderForm.value.thana = '';
    thanas.value = [];
    if (districtId) {
        try {
            const response = await axios.get(`/thanas/${districtId}`);
            thanas.value = response.data;
        } catch (error) {
            console.error('Error fetching thanas:', error);
        }
    }
}

// Color and Size selection functions
const selectColor = (color) => {
    selectedColor.value = color
    orderForm.value.color_id = color.id
    orderForm.value.color_name = color.name
    // If color has attribute_value_id, add to product_attrs array
    if (color.attribute_value_id) {
        if (!orderForm.value.product_attrs.includes(color.attribute_value_id)) {
            orderForm.value.product_attrs.push(color.attribute_value_id);
        }
    }
    console.log('Selected color:', color)
}

const selectSize = (size) => {
    selectedSize.value = size
    orderForm.value.size_id = size.id
    orderForm.value.size_name = size.name
    // If size has attribute_value_id, add to product_attrs array
    if (size.attribute_value_id) {
        if (!orderForm.value.product_attrs.includes(size.attribute_value_id)) {
            orderForm.value.product_attrs.push(size.attribute_value_id);
        }
    }
    console.log('Selected size:', size)
}

const confirmOrder = async () => {
    // Map frontend fields to backend required fields
    orderForm.value.shipping_did = String(orderForm.value.division ?? '');
    orderForm.value.shipping_des_id = String(orderForm.value.district ?? '');
    orderForm.value.shipping_TID = String(orderForm.value.thana ?? '');
    orderForm.value.product_id = Number(orderForm.value.product_id);
    orderForm.value.quantity = Number(orderForm.value.quantity);
    orderForm.value.division = Number(orderForm.value.division);
    orderForm.value.district = Number(orderForm.value.district);
    orderForm.value.thana = Number(orderForm.value.thana);
    try {
        console.log('Order form data:', orderForm.value);
        console.log('Product attributes:', orderForm.value.product_attrs);
        const response = await axios.post('/orders', orderForm.value);

        // Show success message with order number
        const orderNumber = response.data.order_number;
        alert(`Order Confirmed!\nOrder Number: ${orderNumber}\nThank you for your purchase!\nYou will receive a confirmation email shortly.`);

        // Redirect to home page
        router.visit('/');
    } catch (error) {
        console.error('Error placing order:', error);

        if (error.response?.data?.message) {
            alert(`Failed to place order: ${error.response.data.message}`);
        } else {
            alert('Failed to place order. Please try again.');
        }
    }
}

onMounted(() => {
    fetchDivisions();

    // Set default color and size based on props or first available option
    if (props.color_id && props.color_name) {
        // Find the color from available colors
        const foundColor = getAvailableColors.value.find(color => color.id === props.color_id)
        if (foundColor) {
            selectedColor.value = foundColor
        }
    } else if (getAvailableColors.value.length > 0) {
        // Select first available color
        selectedColor.value = getAvailableColors.value[0]
        orderForm.value.color_id = selectedColor.value.id
        orderForm.value.color_name = selectedColor.value.name
    }

    if (props.size_id && props.size_name) {
        // Find the size from available sizes
        const foundSize = getAvailableSizes.value.find(size => size.id === props.size_id)
        if (foundSize) {
            selectedSize.value = foundSize
        }
    } else if (getAvailableSizes.value.length > 0) {
        // Select first available size
        selectedSize.value = getAvailableSizes.value[0]
        orderForm.value.size_id = selectedSize.value.id
        orderForm.value.size_name = selectedSize.value.name
    }
})

// Debug log on component mount
onMounted(() => {
    console.log('OrderForm mounted - Props:', props);
    console.log('OrderForm mounted - product_attrs:', props.product_attrs);
    console.log('OrderForm mounted - orderForm data:', orderForm.value);
})
</script>
