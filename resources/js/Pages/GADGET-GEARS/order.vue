<template>
    <AuthenticatedLayout>
      <div class="bg-gray-100">
        <header class="bg-gradient-to-r rounded-t from-[#2973B2] to-[#EB5A3C] shadow">
          <div class="px-4 py-3">
            <h1 class="text-3xl font-bold text-white">Order Management System</h1>
          </div>
        </header>

        <main class="p-6">
          <!-- Stats Section -->
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div v-for="stat in stats" :key="stat.title"
              class="bg-white rounded-lg shadow p-6">
              <div class="flex items-center">
                <div :class="stat.iconBg" class="p-3 rounded-full">
                  <i :class="stat.icon" class="text-2xl"></i>
                </div>
                <div class="ml-4">
                  <h3 class="text-gray-500 text-sm">{{ stat.title }}</h3>
                  <p class="text-2xl font-semibold">{{ stat.value }}</p>
                </div>
              </div>
            </div>
          </div>

          <!-- Orders Table Section -->
          <div class="bg-white rounded-lg shadow">
            <div class="p-4 border-b">
              <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <h2 class="text-xl font-semibold">Orders</h2>
                <div class="flex flex-col md:flex-row gap-2">
                  <input
                    v-model="search"
                    type="text"
                    placeholder="Search orders..."
                    class="rounded-md border-gray-300 shadow-sm focus:border-[#2973B2] focus:ring-[#2973B2]"
                  />
                  <select
                    v-model="statusFilter"
                    class="rounded-md border-gray-300 shadow-sm focus:border-[#2973B2] focus:ring-[#2973B2]"
                  >
                    <option value="">All Statuses</option>
                    <option v-for="status in orderStatuses" :key="status" :value="status">
                      {{ status }}
                    </option>
                  </select>
                </div>
              </div>
            </div>

            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                  <tr>
                    <th v-for="header in tableHeaders" :key="header"
                      class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                    >
                      {{ header }}
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr v-for="order in filteredOrders" :key="order.id">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm font-medium text-gray-900">{{ order.orderNumber }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-900">{{ order.customerName }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span class="text-sm text-gray-900">${{ order.total.toFixed(2) }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span :class="getStatusClass(order.status)"
                        class="px-2 py-1 text-xs font-semibold rounded-full"
                      >
                        {{ order.status }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                      {{ formatDate(order.createdAt) }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                      <button @click="viewOrder(order)"
                        class="text-[#2973B2] hover:text-[#EB5A3C] mr-2">
                        View
                      </button>
                      <button @click="updateOrderStatus(order)"
                        class="text-[#2973B2] hover:text-[#EB5A3C]">
                        Update
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </main>

        <!-- Order Details Modal -->
        <div v-if="selectedOrder" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center">
          <div class="bg-white rounded-lg p-6 max-w-2xl w-full mx-4">
            <div class="flex justify-between items-center mb-4">
              <h3 class="text-lg font-semibold">Order Details</h3>
              <button @click="selectedOrder = null" class="text-gray-500 hover:text-gray-700">
                ✕
              </button>
            </div>
            <div class="space-y-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <p class="text-sm font-medium text-gray-500">Order Number</p>
                  <p class="mt-1">{{ selectedOrder.orderNumber }}</p>
                </div>
                <div>
                  <p class="text-sm font-medium text-gray-500">Customer</p>
                  <p class="mt-1">{{ selectedOrder.customerName }}</p>
                </div>
              </div>
              <div>
                <p class="text-sm font-medium text-gray-500">Items</p>
                <div class="mt-1 space-y-2">
                  <div v-for="item in selectedOrder.items" :key="item.id"
                    class="flex justify-between">
                    <span>{{ item.productName }} (x{{ item.quantity }})</span>
                    <span>${{ item.subtotal.toFixed(2) }}</span>
                  </div>
                </div>
              </div>
              <div class="border-t pt-4">
                <div class="flex justify-between font-semibold">
                  <span>Total</span>
                  <span>${{ selectedOrder.total.toFixed(2) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </AuthenticatedLayout>
  </template>

  <script setup>
  import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
  import { ref, computed } from 'vue'

  const orderStatuses = ['Pending', 'Processing', 'Shipped', 'Delivered', 'Completed', 'Cancelled']
  const tableHeaders = ['Order #', 'Customer', 'Total', 'Status', 'Date', 'Actions']

  const orders = ref([
    {
      id: 1,
      orderNumber: 'ORD-001',
      customerName: 'John Doe',
      total: 299.99,
      status: 'Pending',
      createdAt: '2024-01-15T10:00:00',
      items: [
        { id: 1, productName: 'Product 1', quantity: 2, subtotal: 199.99 },
        { id: 2, productName: 'Product 2', quantity: 1, subtotal: 100.00 }
      ]
    },
    {
      id: 2,
      orderNumber: 'ORD-002',
      customerName: 'Jane Smith',
      total: 499.99,
      status: 'Shipped',
      createdAt: '2024-01-14T15:30:00',
      items: [
        { id: 3, productName: 'Product 3', quantity: 1, subtotal: 499.99 }
      ]
    }
  ])

  const search = ref('')
  const statusFilter = ref('')
  const selectedOrder = ref(null)

  const filteredOrders = computed(() => {
    return orders.value.filter(order => {
      const matchesSearch = order.orderNumber.toLowerCase().includes(search.value.toLowerCase()) ||
        order.customerName.toLowerCase().includes(search.value.toLowerCase())
      const matchesStatus = !statusFilter.value || order.status === statusFilter.value
      return matchesSearch && matchesStatus
    })
  })

  const stats = computed(() => [
    {
      title: 'Total Orders',
      value: orders.value.length,
      icon: '📦',
      iconBg: 'bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white'
    },
    {
      title: 'Pending Orders',
      value: orders.value.filter(o => o.status === 'Pending').length,
      icon: '⏳',
      iconBg: 'bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white'
    },
    {
      title: 'Completed Orders',
      value: orders.value.filter(o => o.status === 'Completed').length,
      icon: '✅',
      iconBg: 'bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white'
    },
    {
      title: 'Total Revenue',
      value: `$${orders.value.reduce((sum, order) => sum + order.total, 0).toFixed(2)}`,
      icon: '💰',
      iconBg: 'bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white'
    }
  ])

  const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
      year: 'numeric',
      month: 'short',
      day: 'numeric',
      hour: '2-digit',
      minute: '2-digit'
    })
  }

  const getStatusClass = (status) => {
    const classes = {
      'Pending': 'bg-[#2973B2] text-white',
      'Processing': 'bg-[#2973B2] text-white',
      'Shipped': 'bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white',
      'Delivered': 'bg-[#EB5A3C] text-white',
      'Completed': 'bg-[#EB5A3C] text-white',
      'Cancelled': 'bg-gray-500 text-white'
    }
    return classes[status] || 'bg-gray-100 text-gray-800'
  }

  const viewOrder = (order) => {
    selectedOrder.value = order
  }

  const updateOrderStatus = (order) => {
    console.log('Update status for order:', order.id)
  }
  </script>

  <style>
  /* Add any custom styles here if needed */
  </style>
