<script setup>
import InputError from '@/Components/InputError.vue';

const props = defineProps({
    imagePreviewUrls: {
        type: Array,
        required: true
    },
    primaryImageIndex: {
        type: Number,
        required: true
    },
    errors: {
        type: Object,
        required: true
    }
});

const emit = defineEmits(['handle-image-select', 'remove-image', 'set-primary-image']);

const handleImageSelect = (event) => {
    emit('handle-image-select', event);
};

const removeImage = (index) => {
    emit('remove-image', index);
};

const setPrimaryImage = (index) => {
    emit('set-primary-image', index);
};
</script>

<template>
    <div class="p-6">
        <!-- File Upload -->
        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
            <div class="space-y-1 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="flex text-sm text-gray-600">
                    <label for="images" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                        <span>Upload files</span>
                        <input
                            id="images"
                            type="file"
                            class="sr-only"
                            multiple
                            accept="image/*"
                            @change="handleImageSelect"
                        >
                    </label>
                    <p class="pl-1">or drag and drop</p>
                </div>
                <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB each</p>
            </div>
        </div>

        <!-- Image Preview -->
        <div v-if="imagePreviewUrls.length > 0" class="mt-6">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div v-for="(url, index) in imagePreviewUrls" :key="index" class="relative group">
                    <img :src="url" class="w-full h-32 object-cover rounded-lg border-2" :class="primaryImageIndex === index ? 'border-blue-500' : 'border-gray-200'">
                    <div v-if="primaryImageIndex === index" class="absolute top-2 left-2 bg-blue-500 text-white text-xs px-2 py-1 rounded">Primary</div>
                    <div class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity space-x-1">
                        <button v-if="primaryImageIndex !== index" @click="setPrimaryImage(index)" type="button" class="bg-green-500 text-white text-xs px-2 py-1 rounded">Set Primary</button>
                        <button @click="removeImage(index)" type="button" class="bg-red-500 text-white text-xs px-2 py-1 rounded">Remove</button>
                    </div>
                </div>
            </div>
        </div>
        <InputError v-if="errors.images" :message="errors.images" class="mt-2" />
    </div>
</template>
