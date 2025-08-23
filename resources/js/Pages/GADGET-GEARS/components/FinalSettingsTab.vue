<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';

defineProps({
    form: {
        type: Object,
        required: true
    },
    errors: {
        type: Object,
        required: true
    }
});
</script>

<template>
    <div class="p-6">
        <div class="space-y-8">
            <!-- Product Settings -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Product Settings</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <InputLabel for="status" value="Status" />
                        <select
                            id="status"
                            v-model="form.status"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                        >
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                            <option value="draft">Draft</option>
                        </select>
                        <InputError v-if="errors.status" :message="errors.status" class="mt-2" />
                    </div>
                    <div class="flex items-center pt-6">
                        <input
                            id="featured"
                            v-model="form.featured"
                            type="checkbox"
                            class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                        />
                        <label for="featured" class="ml-2 block text-sm text-gray-900">
                            Featured Product
                        </label>
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Settings</h3>
                <div class="space-y-6">
                    <div>
                        <InputLabel for="meta_title" value="Meta Title" />
                        <TextInput
                            id="meta_title"
                            v-model="form.meta_title"
                            type="text"
                            class="mt-1 block w-full"
                            maxlength="255"
                        />
                        <p class="text-xs text-gray-500 mt-1">{{ form.meta_title?.length || 0 }}/255 characters</p>
                        <InputError v-if="errors.meta_title" :message="errors.meta_title" class="mt-2" />
                    </div>
                    <div>
                        <InputLabel for="meta_description" value="Meta Description" />
                        <textarea
                            id="meta_description"
                            v-model="form.meta_description"
                            rows="3"
                            class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                            maxlength="160"
                            placeholder="Brief description for search engines..."
                        ></textarea>
                        <p class="text-xs text-gray-500 mt-1">{{ form.meta_description?.length || 0 }}/160 characters</p>
                        <InputError v-if="errors.meta_description" :message="errors.meta_description" class="mt-2" />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
