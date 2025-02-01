<template>
    <TransitionRoot appear :show="showAddModal" as="template">
    <Dialog as="div" class="relative z-10" @close="showAddModal = false">
      <div class="fixed inset-0 bg-black bg-opacity-25" />
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <DialogPanel class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white p-6 text-left align-middle shadow-xl transition-all">
            <DialogTitle class="text-lg font-medium leading-6 text-gray-900">
              {{ editingItem ? 'Edit Item' : 'Add New Item' }}
            </DialogTitle>

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
          </DialogPanel>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>

</template>
<script setup>
import { Dialog, DialogPanel, DialogTitle, TransitionRoot } from '@inertiajs/vue3'

 function saveItem() {
    if (editingItem.value) {
      const index = items.value.findIndex(item => item.id === editingItem.value.id)
      items.value[index] = { ...itemForm.value, id: editingItem.value.id }
    } else {
      const newId = Math.max(...items.value.map(item => item.id)) + 1
      items.value.push({ ...itemForm.value, id: newId })
    }

    showAddModal.value = false
    editingItem.value = null
    itemForm.value = { name: '', category: '', quantity: 0, price: 0 }
  }
</script>
