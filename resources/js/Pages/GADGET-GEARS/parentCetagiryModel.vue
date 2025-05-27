<script setup>
import { ref, watch, computed } from 'vue';
import { useForm } from '@inertiajs/vue3';

const props = defineProps({
    show: {
        type: Boolean,
        default: false
    },
    category: {
        type: Object,
        default: null
    },
    categories: {
        type: Array,
        default: () => []
    }
});

const emit = defineEmits(['update:show', 'category-saved']);

const isEditing = computed(() => props.category !== null);

const form = useForm({
    name: '',
    slug: '',
    description: '',
    parent_id: null,
    order: 0,
    meta_title: '',
    meta_description: '',
    status: 'active'
});

// Watch for category changes to populate form
watch(() => props.category, (newCategory) => {
    if (newCategory) {
        form.name = newCategory.name || '';
        form.slug = newCategory.slug || '';
        form.description = newCategory.description || '';
        form.parent_id = newCategory.parent_id || null;
        form.order = newCategory.order || 0;
        form.meta_title = newCategory.meta_title || '';
        form.meta_description = newCategory.meta_description || '';
        form.status = newCategory.status || 'active';
    } else {
        form.reset();
    }
}, { immediate: true });

// Auto-generate slug from name
watch(() => form.name, (newName) => {
    if (newName && !isEditing.value) {
        form.slug = newName
            .toLowerCase()
            .replace(/[^a-z0-9]+/g, '-')
            .replace(/(^-|-$)/g, '');
    }
});

const closeModal = () => {
    emit('update:show', false);
    form.reset();
    form.clearErrors();
};

const submitForm = () => {
    const url = '/parent-categories';

    form.post(url, {
        onSuccess: () => {
            emit('category-saved');
            closeModal();
        },
        preserveScroll: true
    });
};

// Available parent categories (exclude current category and its children)
const availableParentCategories = computed(() => {
    if (!props.categories) return [];
    return props.categories.filter(cat => {
        if (isEditing.value && props.category) {
            return cat.id !== props.category.id && cat.parent_id !== props.category.id;
        }
        return true;
    });
});
</script>

<template>
    <!-- Modal Backdrop -->
    <div v-if="show" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div
                class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"
                @click="closeModal"
            ></div>

            <!-- Modal panel -->
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <form @submit.prevent="submitForm">
                    <!-- Modal Header -->
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                {{ isEditing ? 'Edit Category' : 'Add New parent-cetagory' }}
                            </h3>
                            <button
                                type="button"
                                @click="closeModal"
                                class="bg-white rounded-md text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500"
                            >
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Form Fields -->
                        <div class="space-y-6">
                            <!-- Basic Information -->
                            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                <!-- Category Name -->
                                <div class="sm:col-span-2">
                                    <label for="name" class="block text-sm font-medium text-gray-700">
                                        Category Name *
                                    </label>
                                    <input
                                        v-model="form.name"
                                        type="text"
                                        id="name"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300': form.errors.name }"
                                        placeholder="Enter category name"
                                        required
                                    >
                                    <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
                                </div>

                                <!-- Slug -->
                                <div class="sm:col-span-2">
                                    <label for="slug" class="block text-sm font-medium text-gray-700">
                                        Slug *
                                    </label>
                                    <input
                                        v-model="form.slug"
                                        type="text"
                                        id="slug"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        :class="{ 'border-red-300': form.errors.slug }"
                                        placeholder="category-slug"
                                        required
                                    >
                                    <p v-if="form.errors.slug" class="mt-1 text-sm text-red-600">{{ form.errors.slug }}</p>
                                    <p class="mt-1 text-sm text-gray-500">URL-friendly version of the name</p>
                                </div>

                                <!-- Parent Category -->


                                <!-- Status -->
                                <div class="sm:col-span-2">
                                    <label for="status" class="block text-sm font-medium text-gray-700">
                                        Status
                                    </label>
                                    <select
                                        v-model="form.status"
                                        id="status"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    >
                                        <option value="active">Active</option>
                                        <option value="inactive">Inactive</option>
                                        <option value="draft">Draft</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Description -->
                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">
                                    Description
                                </label>
                                <textarea
                                    v-model="form.description"
                                    id="description"
                                    rows="3"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                    placeholder="Enter category description..."
                                ></textarea>
                                <p v-if="form.errors.description" class="mt-1 text-sm text-red-600">{{ form.errors.description }}</p>
                            </div>

                            <!-- SEO Section -->
                            <div class="border-t border-gray-200 pt-6">
                                <h4 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h4>

                                <!-- Meta Title -->
                                <div class="mb-4">
                                    <label for="meta_title" class="block text-sm font-medium text-gray-700">
                                        Meta Title
                                    </label>
                                    <input
                                        v-model="form.meta_title"
                                        type="text"
                                        id="meta_title"
                                        maxlength="60"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="SEO title for search engines"
                                    >
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ form.meta_title ? form.meta_title.length : 0 }}/60 characters
                                    </p>
                                    <p v-if="form.errors.meta_title" class="mt-1 text-sm text-red-600">{{ form.errors.meta_title }}</p>
                                </div>

                                <!-- Meta Description -->
                                <div>
                                    <label for="meta_description" class="block text-sm font-medium text-gray-700">
                                        Meta Description
                                    </label>
                                    <textarea
                                        v-model="form.meta_description"
                                        id="meta_description"
                                        rows="3"
                                        maxlength="160"
                                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                        placeholder="SEO description for search engines"
                                    ></textarea>
                                    <p class="mt-1 text-sm text-gray-500">
                                        {{ form.meta_description ? form.meta_description.length : 0 }}/160 characters
                                    </p>
                                    <p v-if="form.errors.meta_description" class="mt-1 text-sm text-red-600">{{ form.errors.meta_description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button
                            type="submit"
                            :disabled="form.processing"
                            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50"
                        >
                            <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>

                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            {{ form.processing ? 'Saving...' : (isEditing ? 'Update Category' : 'Create Category') }}
                        </button>
                        <button
                            type="button"
                            @click="closeModal"
                            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                        >
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
