<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref } from 'vue';

const salesStats = ref({
    totalSales: '₹124,592',
    growth: '+14.2%',
    orders: 284,
    customers: 1842,
    avgOrderValue: '₹438'
});

const recentOrders = ref([
    { id: '#ORD-1234', customer: 'John Doe', amount: '₹459', status: 'Delivered', date: '2 min ago' },
    { id: '#ORD-1235', customer: 'Sarah Smith', amount: '₹892', status: 'Processing', date: '15 min ago' },
    { id: '#ORD-1236', customer: 'Mike Johnson', amount: '₹245', status: 'Pending', date: '1 hour ago' },
]);

const topProducts = ref([
    { name: 'Wireless Earbuds', sales: 124, revenue: '₹12,400', stock: 45 },
    { name: 'Smart Watch', sales: 98, revenue: '₹29,400', stock: 12 },
    { name: 'Phone Case', sales: 85, revenue: '₹4,250', stock: 145 },
]);
</script>



<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center bg-gradient-to-r from-[#2973B2] to-[#EB5A3C]  p-3 rounded-t-lg">
                <h2 class="text-2xl font-bold text-white">Dashboard Overview</h2>
                <div class="space-x-4">
                    <button class="inline-flex items-center px-4 py-2 bg-white/10 text-white text-sm font-medium rounded-lg hover:bg-white/20">
                        Generate Report
                    </button>
                    <button class="inline-flex items-center px-4 py-2 bg-[#EB5A3C] text-white text-sm font-medium rounded-lg hover:bg-[#d54b30]">
                        Add Product
                    </button>
                </div>
            </div>
        </template>

        <div class="py-6 bg-gradient-to-br from-gray-50 to-blue-50">
            <div class="mx-auto  sm:px-6 lg:px-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
    <!-- Total Sales Card -->
    <div class="bg-gradient-to-br from-[#2973B2]/90 to-[#1e5c8f] rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-100 mb-1">Total Sales</p>
                <div class="flex items-center">
                    <h3 class="text-2xl font-bold text-white">{{ salesStats.totalSales }}</h3>
                    <span class="ml-2 text-sm font-medium text-emerald-300">{{ salesStats.growth }}</span>
                </div>
            </div>
            <div class="p-3 bg-white/10 rounded-lg backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Benefit Card -->
    <div class="bg-gradient-to-br from-[#EB5A3C]/90 to-[#c73e23] rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-orange-100 mb-1">Total Benefit</p>
                <div class="flex items-center">
                    <h3 class="text-2xl font-bold text-white">{{ salesStats.totalSales }}</h3>
                    <span class="ml-2 text-sm font-medium text-emerald-300">{{ salesStats.growth }}</span>
                </div>
            </div>
            <div class="p-3 bg-white/10 rounded-lg backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Total Return Card -->
    <div class="bg-gradient-to-br from-[#2973B2]/90 to-[#1e5c8f] rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-blue-100 mb-1">Total Return</p>
                <div class="flex items-center">
                    <h3 class="text-2xl font-bold text-white">{{ salesStats.totalSales }}</h3>
                    <span class="ml-2 text-sm font-medium text-emerald-300">{{ salesStats.growth }}</span>
                </div>
            </div>
            <div class="p-3 bg-white/10 rounded-lg backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Fourth Card -->
    <div class="bg-gradient-to-br from-[#EB5A3C]/90 to-[#c73e23] rounded-xl p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-orange-100 mb-1">Total Orders</p>
                <div class="flex items-center">
                    <h3 class="text-2xl font-bold text-white">{{ salesStats.totalSales }}</h3>
                    <span class="ml-2 text-sm font-medium text-emerald-300">{{ salesStats.growth }}</span>
                </div>
            </div>
            <div class="p-3 bg-white/10 rounded-lg backdrop-blur-sm">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>


                <!-- Main Content Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Sales Chart -->
                    <div class="lg:col-span-2 bg-white rounded-xl shadow-sm p-6 border-t-4 border-[#2973B2]">
                        <h3 class="text-lg font-semibold mb-4 text-[#2973B2]">Sales Analytics</h3>
                        <div class="h-80 bg-gradient-to-br from-gray-50 to-blue-50 rounded-lg"></div>
                    </div>

                    <!-- Recent Orders -->
                    <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-[#EB5A3C]">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-[#EB5A3C]">Recent Orders</h3>
                            <a href="#" class="text-[#2973B2] text-sm hover:text-[#EB5A3C]">View All</a>
                        </div>
                        <div class="space-y-4">
                            <div v-for="order in recentOrders" :key="order.id"
                                 class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-blue-50 rounded-lg hover:from-blue-50 hover:to-gray-50 transition-all duration-300">
                                <!-- Your existing order content -->

                            </div>
                        </div>
                    </div>
                </div>

                <!-- Top Products Table -->
                <div class="mt-6 bg-white rounded-md shadow-sm overflow-hidden">
                    <div class="p-3 border-b border-gray-200 bg-gradient-to-r from-[#2973B2] to-[#EB5A3C]">
                        <h3 class="text-lg font-semibold text-white">Top Selling Products</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gradient-to-r from-[#2973B2]/10 to-[#EB5A3C]/10">
                                <!-- Your existing table header -->
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sales</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Revenue</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>

                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr v-for="product in topProducts" :key="product.name"
                                    class="hover:bg-gradient-to-r hover:from-blue-50 hover:to-gray-50">
                                    <!-- Your existing table content -->
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ product.name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ product.sales }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-900">{{ product.revenue }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full"
                                              :class="product.stock > 20 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'">
                                            {{ product.stock }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <button class="text-blue-600 hover:text-blue-900">Edit</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
