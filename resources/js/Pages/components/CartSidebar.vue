<script setup>
import { defineProps, defineEmits } from 'vue';
import Drawer from 'primevue/drawer';
import Button from 'primevue/button';
import Badge from 'primevue/badge';

const props = defineProps({
    visible: Boolean,
    cart: Array,
    cartItemCount: Number,
    cartSubtotal: Number,
    cartTax: Number,
    cartShipping: Number,
    cartTotal: Number,
    formatPrice: Function
});

defineEmits(['update:visible', 'update-quantity', 'remove-from-cart', 'clear-cart', 'checkout']);
</script>

<template>
    <Drawer :visible="visible"
             @update:visible="$emit('update:visible', $event)"
             position="right"
             class="w-full md:w-96 lg:w-[420px]"
             :modal="true">
        <template #header>
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-2">
                    <i class="pi pi-shopping-cart text-indigo-600"></i>
                    <span class="font-bold text-lg">Shopping Cart</span>
                    <Badge v-if="cartItemCount > 0" :value="cartItemCount" severity="info" />
                </div>
                <Button v-if="cart.length > 0"
                        icon="pi pi-trash"
                        text
                        rounded
                        severity="danger"
                        v-tooltip.left="'Clear Cart'"
                        @click="$emit('clear-cart')" />
            </div>
        </template>

        <!-- Cart Content -->
        <div class="h-full flex flex-col">
            <!-- Empty Cart State -->
            <div v-if="cart.length === 0" class="flex-1 flex flex-col items-center justify-center p-8 text-center">
                <i class="pi pi-shopping-cart text-6xl text-gray-300 mb-4"></i>
                <h3 class="text-xl font-semibold text-gray-600 mb-2">Your cart is empty</h3>
                <p class="text-gray-500 mb-6">Add some amazing products to get started!</p>
                <Button label="Continue Shopping"
                        icon="pi pi-arrow-left"
                        outlined
                        @click="$emit('update:visible', false)" />
            </div>

            <!-- Cart Items -->
            <div v-else class="flex-1 overflow-y-auto">
                <div class="space-y-4 p-4">
                    <div v-for="item in cart"
                         :key="item.id"
                         class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                        <div class="flex gap-4">
                            <!-- Product Image -->
                            <div class="flex-shrink-0">
                                <img :src="item.image"
                                     :alt="item.name"
                                     class="w-16 h-16 object-cover rounded-lg" />
                            </div>

                            <!-- Product Info -->
                            <div class="flex-1 min-w-0">
                                <h4 class="font-medium text-gray-900 text-sm truncate mb-1">
                                    {{ item.name }}
                                </h4>
                                <p class="text-xs text-indigo-600 mb-2">{{ item.brand }}</p>

                                <!-- Price -->
                                <div class="flex items-center gap-2 mb-3">
                                    <span class="font-bold text-gray-900">{{ formatPrice(item.price) }}</span>
                                    <span v-if="item.originalPrice" class="text-sm text-gray-500 line-through">
                                        {{ formatPrice(item.originalPrice) }}
                                    </span>
                                </div>

                                <!-- Quantity Controls -->
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-2">
                                        <Button icon="pi pi-minus"
                                                text
                                                rounded
                                                size="small"
                                                severity="secondary"
                                                @click="$emit('update-quantity', item.id, item.quantity - 1)" />
                                        <span class="min-w-[2rem] text-center font-medium">{{ item.quantity }}</span>
                                        <Button icon="pi pi-plus"
                                                text
                                                rounded
                                                size="small"
                                                severity="secondary"
                                                @click="$emit('update-quantity', item.id, item.quantity + 1)" />
                                    </div>

                                    <Button icon="pi pi-trash"
                                            text
                                            rounded
                                            size="small"
                                            severity="danger"
                                            v-tooltip.left="'Remove Item'"
                                            @click="$emit('remove-from-cart', item.id)" />
                                </div>
                            </div>

                            <!-- Item Total -->
                            <div class="flex-shrink-0 text-right">
                                <div class="font-bold text-gray-900">
                                    {{ formatPrice(item.price * item.quantity) }}
                                </div>
                                <div v-if="item.originalPrice" class="text-xs text-green-600">
                                    Save {{ formatPrice((item.originalPrice - item.price) * item.quantity) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cart Summary -->
                <div class="border-t border-gray-200 p-4 bg-gray-50">
                    <div class="space-y-2 mb-4">
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Subtotal ({{ cartItemCount }} items):</span>
                            <span class="font-medium">{{ formatPrice(cartSubtotal) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Tax:</span>
                            <span class="font-medium">{{ formatPrice(cartTax) }}</span>
                        </div>
                        <div class="flex justify-between text-sm">
                            <span class="text-gray-600">Shipping:</span>
                            <span class="font-medium">
                                <span v-if="cartShipping === 0" class="text-green-600">Free</span>
                                <span v-else>{{ formatPrice(cartShipping) }}</span>
                            </span>
                        </div>
                        <div v-if="cartSubtotal < 100 && cartSubtotal > 0" class="text-xs text-gray-500">
                            Add {{ formatPrice(100 - cartSubtotal) }} more for free shipping
                        </div>
                    </div>

                    <div class="border-t border-gray-300 pt-2 mb-4">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-bold text-gray-900">Total:</span>
                            <span class="text-xl font-bold text-indigo-600">{{ formatPrice(cartTotal) }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-2">
                        <Button label="Proceed to Checkout"
                                icon="pi pi-credit-card"
                                class="w-full bg-indigo-600 hover:bg-indigo-700"
                                @click="$emit('checkout')" />
                        <Button label="Continue Shopping"
                                icon="pi pi-arrow-left"
                                outlined
                                class="w-full"
                                @click="$emit('update:visible', false)" />
                    </div>
                </div>
            </div>
        </div>
    </Drawer>
    </template>
