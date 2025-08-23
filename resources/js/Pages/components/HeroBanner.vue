<script setup>
import Button from 'primevue/button';
</script>

<template>
    <section class="relative bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 overflow-hidden">
        <div class="absolute inset-0 bg-black/20"></div>
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-900/50 to-transparent"></div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="text-white">
                    <div class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full backdrop-blur-sm mb-6">
                        <i class="pi pi-star-fill text-yellow-400 mr-2"></i>
                        <span class="text-sm font-medium">Premium Quality Products</span>
                    </div>
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold mb-6 leading-tight">
                        Discover Amazing
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-yellow-400 to-orange-400">
                            Products
                        </span>
                    </h1>
                    <p class="text-lg md:text-xl text-gray-200 mb-8">
                        Shop from thousands of premium products with fast delivery and unbeatable prices.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <Button
                            label="Shop Now"
                            icon="pi pi-shopping-bag"
                            class="bg-white text-indigo-600 hover:bg-gray-100"
                            @click="$parent.$refs.productsSection.scrollIntoView({ behavior: 'smooth' })"
                        />
                        <Button
                            label="View Categories"
                            icon="pi pi-th-large"
                            outlined
                            class="border-white text-white hover:bg-white hover:text-indigo-600"
                        />
                    </div>
                </div>
                <div class="relative hidden md:block">
                    <div class="relative bg-white/10 backdrop-blur-lg rounded-3xl p-8 border border-white/20">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <i class="pi pi-truck text-3xl text-yellow-400 mb-2"></i>
                                <div class="text-sm font-medium text-white">Free Delivery</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <i class="pi pi-shield text-3xl text-green-400 mb-2"></i>
                                <div class="text-sm font-medium text-white">Secure Payment</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <i class="pi pi-refresh text-3xl text-blue-400 mb-2"></i>
                                <div class="text-sm font-medium text-white">Easy Returns</div>
                            </div>
                            <div class="bg-white/20 rounded-xl p-4 text-center">
                                <i class="pi pi-heart text-3xl text-red-400 mb-2"></i>
                                <div class="text-sm font-medium text-white">24/7 Support</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>
