<template>
   <AuthenticatedLayout>
    <div class="bg-white shadow-md rounded-lg">
      <div class="p-6 border-b border-gray-200">
        <div class="flex justify-between items-center mb-4">
          <h2 class="text-xl font-semibold text-gray-800">Inventory Items</h2>
          <button
            @click="showAddModal = true"
            class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500"
          >
            Add New Item
          </button>
        </div>

        <!-- Search and Filter -->
        <div class="flex gap-4 mb-6">
          <div class="flex-1">
            <input
              type="text"
              v-model="searchQuery"
              placeholder="Search items..."
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
            />
          </div>
          <select
            v-model="categoryFilter"
            class="px-4 py-2 border border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500"
          >
            <option value="">All Categories</option>
            <option v-for="category in categories" :key="category" :value="category">
              {{ category }}
            </option>
          </select>
        </div>

        <!-- Inventory Table -->
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
              <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Item Name
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Category
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Quantity
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Price
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                  Actions
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="item in filteredItems" :key="item.id">
                <td class="px-6 py-4 whitespace-nowrap">{{ item.name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ item.category }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    :class="[
                      'px-2 py-1 rounded-full text-xs font-medium',
                      item.quantity < 10 ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'
                    ]"
                  >
                    {{ item.quantity }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">${{ item.price }}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button
                    @click="editItem(item)"
                    class="text-indigo-600 hover:text-indigo-900 mr-2"
                  >
                    Edit
                  </button>
                  <button
                    @click="deleteItem(item.id)"
                    class="text-red-600 hover:text-red-900"
                  >
                    Delete
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Add/Edit Modal -->


    <Modal :show="showAddModal" @close="showAddModal = false">
    <div class="p-6">
        <h2 class="text-lg font-medium leading-6 text-gray-900">
            {{ editingItem ? 'Edit Item' : 'Add New Item' }}
        </h2>

        <form @submit.prevent="saveItem" class="mt-4 space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700">Name</label>
                <input
                    type="text"
                    v-model="itemForm.name"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Category</label>
                <select
                    v-model="itemForm.category"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                >
                    <option v-for="category in categories" :key="category" :value="category">
                        {{ category }}
                    </option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Quantity</label>
                <input
                    type="number"
                    v-model="itemForm.quantity"
                    required
                    min="0"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Price</label>
                <input
                    type="number"
                    v-model="itemForm.price"
                    required
                    min="0"
                    step="0.01"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                />
            </div>

            <div class="mt-4 flex justify-end space-x-2">
                <button
                    type="button"
                    @click="showAddModal = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500"
                >
                    Cancel
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    {{ editingItem ? 'Update' : 'Add' }}
                </button>
            </div>
        </form>
    </div>
</Modal>


   </AuthenticatedLayout>
  </template>

  <script setup>

import { ref, computed } from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'
import Modal from '@/Components/Modal.vue'


  const items = ref([
    { id: 1, name: 'Laptop', category: 'Electronics', quantity: 5, price: 999.99 },
    { id: 2, name: 'Desk Chair', category: 'Furniture', quantity: 12, price: 199.99 },
  ])

  const categories = ['Electronics', 'Furniture', 'Office Supplies', 'Books']
  const searchQuery = ref('')
  const categoryFilter = ref('')
  const showAddModal = ref(false)
  const editingItem = ref(null)
  const itemForm = ref({
    name: '',
    category: '',
    quantity: 0,
    price: 0
  })

  const filteredItems = computed(() => {
    return items.value
      .filter(item => {
        const matchesSearch = item.name.toLowerCase().includes(searchQuery.value.toLowerCase())
        const matchesCategory = !categoryFilter.value || item.category === categoryFilter.value
        return matchesSearch && matchesCategory
      })
  })

  function editItem(item) {
    editingItem.value = item
    itemForm.value = { ...item }
    showAddModal.value = true
  }

  function deleteItem(id) {
    if (confirm('Are you sure you want to delete this item?')) {
      items.value = items.value.filter(item => item.id !== id)
    }
  }


  </script>
