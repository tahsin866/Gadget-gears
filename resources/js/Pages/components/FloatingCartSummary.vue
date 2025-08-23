<script setup>
import { defineProps, defineEmits } from 'vue';
import Badge from 'primevue/badge';
import Button from 'primevue/button';

const props = defineProps({
    cart: {
        type: Array,
        required: true
    },
    formatPrice: {
        type: Function,
        required: true
    }
});

const emit = defineEmits(['view-cart']);
</script>

<template>
    <div class="fixed bottom-6 right-6 bg-white rounded-xl shadow-lg border border-gray-200 p-4 max-w-sm z-50">
        <div class="flex items-center justify-between mb-3">
            <h4 class="font-bold text-lg text-gray-900 flex items-center gap-2">
                <i class="pi pi-shopping-cart text-indigo-600"></i>
                Cart
            </h4>
            <Badge :value="cart.reduce((total, item) => total + item.quantity, 0)"
                   severity="info"
                   class="text-base" />
        </div>

        <div class="space-y-2 max-h-60 overflow-y-auto">
            <div v-for="item in cart.slice(0, 3)"
                 :key="item.id"
                 class="flex items-center gap-3 p-2 bg-gray-50 rounded">
                <img :src="item.image"
                     :alt="item.name"
                     class="w-12 h-12 object-cover rounded" />
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{ item.name }}</p>
                    <p class="text-xs text-gray-500">Qty: {{ item.quantity }}</p>
                </div>
                <span class="text-sm font-bold text-gray-900">
                    {{ formatPrice(item.price * item.quantity) }}
                </span>
            </div>

            <div v-if="cart.length > 3" class="text-center py-1">
                <span class="text-sm text-gray-500">+{{ cart.length - 3 }} more items</span>
            </div>
        </div>

        <div class="border-t border-gray-200 pt-3 mt-3">
            <div class="flex justify-between items-center mb-3">
                <span class="font-medium text-gray-900">Total:</span>
                <span class="text-xl font-bold text-indigo-600">
                    {{ formatPrice(cart.reduce((total, item) => total + (item.price * item.quantity), 0)) }}
                </span>
            </div>
            <Button label="View Cart"
                    icon="pi pi-arrow-right"
                    class="w-full bg-indigo-600 hover:bg-indigo-700"
                    @click="$emit('view-cart')" />
        </div>
    </div>
</template>
