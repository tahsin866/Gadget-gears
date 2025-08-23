<script setup>
import Image from 'primevue/image';
import Badge from 'primevue/badge';

defineProps({
    brands: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['navigate-to-brand']);
</script>

<template>
    <section class="py-12 md:py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-10 md:mb-12">
                <div class="inline-flex items-center px-4 py-2 bg-indigo-100 text-indigo-700 rounded-full font-medium text-sm mb-4">
                    <i class="pi pi-verified mr-2"></i>
                    Trusted Partners
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">Featured Brands</h2>
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">Shop from the world's most trusted and popular brands with verified quality</p>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-8 gap-4 md:gap-6">
                <div v-for="brand in brands"
                     :key="brand.id"
                     class="cursor-pointer border border-gray-100 rounded-xl shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 bg-white p-4"
                     @click="$emit('navigate-to-brand', brand)">
                    <div class="text-center">
                        <div class="relative mb-4">
                            <div class="w-16 h-16 mx-auto bg-gray-100 rounded-lg flex items-center justify-center overflow-hidden transition-transform duration-300">
                                <Image :src="brand.logo"
                                       :alt="brand.name"
                                       width="50"
                                       height="35"
                                       class="object-contain"
                                       :imageClass="'w-full h-full object-contain'"
                                       preview />
                            </div>
                            <Badge v-if="brand.verified"
                                   value="✓"
                                   severity="success"
                                   class="absolute -top-2 -right-2 w-5 h-5 rounded-full text-xs" />
                        </div>
                        <h3 class="font-bold text-gray-900 hover:text-indigo-600 transition-colors duration-300 mb-1 text-sm">
                            {{ brand.name }}
                        </h3>
                        <div class="flex items-center justify-center gap-1 text-xs text-gray-500">
                            <Badge :value="brand.productCount" severity="info" class="text-xs" />
                            <span>products</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
