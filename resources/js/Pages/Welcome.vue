<script setup>
import { Head, Link } from '@inertiajs/vue3';
import { ref, computed, reactive, watch, onMounted, onUnmounted } from 'vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import GuestLayout from '@/Layouts/GuestLayout.vue';
import Navbar from '@/Pages/navbar.vue';
import HeroSection from './heroSection.vue';
import CategorySection from './categorySection.vue';
import MainContent from './mainContent.vue';
import FeaturedSection from './FeaturedSection.vue';
import Divider from 'primevue/divider';
import Button from 'primevue/button';
import Avatar from 'primevue/avatar';
import Chip from 'primevue/chip';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    auth: Object
});

// Categories data
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

const quickLinks = ref([
    { label: 'About Us', url: '#' },
    { label: 'Contact', url: '#' },
    { label: 'FAQ', url: '#' },
    { label: 'Shipping Info', url: '#' },
    { label: 'Returns', url: '#' },
    { label: 'Size Guide', url: '#' }
]);

const categories = ref([
    { label: 'Electronics', url: '#' },
    { label: 'Fashion', url: '#' },
    { label: 'Home & Garden', url: '#' },
    { label: 'Sports', url: '#' },
    { label: 'Books', url: '#' },
    { label: 'Toys', url: '#' }
]);

const socialLinks = ref([
    { icon: 'pi pi-twitter', url: '#', color: 'bg-blue-500' },
    { icon: 'pi pi-facebook', url: '#', color: 'bg-blue-600' },
    { icon: 'pi pi-instagram', url: '#', color: 'bg-pink-500' },
    { icon: 'pi pi-linkedin', url: '#', color: 'bg-blue-700' }
]);

const paymentMethods = ref([
    { name: 'Visa', color: 'bg-blue-600', initial: 'V' },
    { name: 'Mastercard', color: 'bg-red-600', initial: 'M' },
    { name: 'American Express', color: 'bg-blue-500', initial: 'A' },
    { name: 'PayPal', color: 'bg-yellow-500', initial: 'P' }
]);

const footerLinks = ref([
    { label: 'Privacy Policy', url: '#' },
    { label: 'Terms of Service', url: '#' },
    { label: 'Cookie Policy', url: '#' }
]);
</script>

<template>
    <Head title="GadgetGears - Premium Electronics & Lifestyle Products" />
    <div class="min-h-screen bg-gray-50">
        <!-- Navigation -->
        <Navbar/>

        <!-- Hero Section -->
        <HeroSection/>

        <!-- Categories Section -->
        <CategorySection/>

        <!-- Main Content -->
        <MainContent/>

        <!-- Featured Brands Section -->
        <FeaturedSection/>

        <!-- Footer -->
        <footer class="bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <!-- Company Info -->
                    <div>
                        <div class="flex items-center gap-3 mb-6">
                            <Avatar
                                icon="pi pi-shopping-bag"
                                class="bg-gradient-to-r from-indigo-600 to-purple-600"
                                shape="square"
                                size="large"
                            />
                            <span class="text-2xl font-bold">FyoHub</span>
                        </div>

                        <p class="text-gray-400 mb-6 leading-relaxed">
                            Your ultimate destination for premium products. We bring you the best quality items from trusted brands worldwide.
                        </p>

                        <div class="flex gap-4">
                            <Button
                                v-for="social in socialLinks"
                                :key="social.icon"
                                :icon="social.icon"
                                rounded
                                text
                                class="w-10 h-10 bg-gray-800 hover:bg-indigo-600 transition-colors duration-200"
                                @click="window.open(social.url, '_blank')"
                            />
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Quick Links</h3>
                        <ul class="space-y-4">
                            <li v-for="link in quickLinks" :key="link.label">
                                <Button
                                    :label="link.label"
                                    link
                                    class="text-gray-400 hover:text-white transition-colors duration-200 p-0"
                                />
                            </li>
                        </ul>
                    </div>

                    <!-- Categories -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Categories</h3>
                        <ul class="space-y-4">
                            <li v-for="category in categories" :key="category.label">
                                <Button
                                    :label="category.label"
                                    link
                                    class="text-gray-400 hover:text-white transition-colors duration-200 p-0"
                                />
                            </li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div>
                        <h3 class="text-lg font-semibold mb-6">Contact Info</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <i class="pi pi-map-marker text-indigo-400 mt-1"></i>
                                <div>
                                    <p class="text-gray-400">Hazrat shahjala Road</p>
                                    <p class="text-gray-400">Dhaka Bangladesh</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-3">
                                <i class="pi pi-phone text-indigo-400"></i>
                                <p class="text-gray-400">01632399990</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <i class="pi pi-envelope text-indigo-400"></i>
                                <p class="text-gray-400">fyohub@gmail.com</p>
                            </div>

                            <div class="flex items-center gap-3">
                                <i class="pi pi-clock text-indigo-400"></i>
                                <div>
                                    <p class="text-gray-400">Every day</p>
                                    <!-- <p class="text-gray-400">Sat - Sun: 10AM - 4PM</p> -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <Divider class="my-8" />

                <!-- Footer Bottom -->
                <div class="flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="text-gray-400 text-sm">
                        © 2024 fyohub. All rights reserved.
                    </div>

                    <div class="flex items-center gap-6">
                        <Button
                            v-for="link in footerLinks"
                            :key="link.label"
                            :label="link.label"
                            link
                            class="text-gray-400 hover:text-white text-sm transition-colors duration-200"
                        />
                    </div>

                    <div class="flex items-center gap-4">
                        <!-- <span class="text-gray-400 text-sm">We accept:</span>
                        <div class="flex gap-2">
                            <Chip
                                v-for="payment in paymentMethods"
                                :key="payment.name"
                                :label="payment.initial"
                                :class="payment.color"
                                class="text-white text-xs font-bold w-8 h-5 rounded"
                            />
                        </div> -->
                    </div>
                </div>
            </div>
        </footer>
    </div>
</template>
