<template>
    <div v-if="showAddModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4">
        <div class="bg-white rounded-md max-w-md w-full p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xl font-semibold text-[#2973B2]">Add New Category</h3>
                <button @click="closeModal" class="text-gray-500 hover:text-gray-700">×</button>
            </div>

            <form @submit.prevent="submitForm" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Category Name</label>
                    <input v-model="form.name" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea v-model="form.description" rows="3" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Meta Title</label>
                    <input v-model="form.meta_title" type="text" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Meta Description</label>
                    <textarea v-model="form.meta_description" rows="2" class="mt-1 block w-full rounded-lg border border-gray-300 px-3 py-2"></textarea>
                </div>

                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" @click="closeModal" class="px-4 py-2 border rounded-lg text-gray-700 hover:bg-gray-50">
                        Cancel
                    </button>
                    <button type="submit" class="px-4 py-2 bg-gradient-to-r from-[#2973B2] to-[#EB5A3C] text-white rounded-lg hover:opacity-90">
                        Add Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import axios from 'axios'
import { useForm } from '@inertiajs/vue3'



defineProps({
    categories: Array,
    showAddModal: {
        type: Boolean,
       default: false
   }

})




const emit = defineEmits(['update:showAddModal'])
// const props = defineProps({
//
// })

// Inertia form
const form = useForm({
    name: '',
    description: '',
    parent_id: null,
    order: null,
    meta_title: '',
    meta_description: ''
})

// Close modal function
const closeModal = () => {
    emit('update:showAddModal', false)
}

// Submit form function (using Inertia)
const submitForm = () => {
    form.post(route('GADGET-GEARS.categoryModel'), {
        onSuccess: () => {
            closeModal()
            form.reset()
        }
    })
}


</script>
