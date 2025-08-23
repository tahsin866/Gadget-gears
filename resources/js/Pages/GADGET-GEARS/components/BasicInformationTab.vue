<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import { ref, watch, onMounted } from 'vue';
import axios from 'axios';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    categories: {
        type: Array,
        required: true,
        default: () => []
    },
    brands: {
        type: Array,
        required: true,
        default: () => []
    },
    errors: {
        type: Object,
        required: true,
        default: () => ({})
    }
});

// Structured description feature points
const descriptionPoints = ref([
    { id: 1, text: '', type: 'feature' }
]);
const nextPointId = ref(2);

// Feature types for categorizing bullet points
const featureTypes = ref([
    { value: 'feature', label: 'Key Feature', icon: '✨' },
    { value: 'specification', label: 'Specification', icon: '📋' },
    { value: 'benefit', label: 'Benefit', icon: '👍' },
    { value: 'usage', label: 'Usage', icon: '🔧' },
    { value: 'package', label: 'Package Contents', icon: '📦' },
    { value: 'warning', label: 'Warning', icon: '⚠️' }
]);

// Fetch feature types from API
const fetchFeatureTypes = async () => {
    try {
        const response = await axios.get('/feature-types');
        if (response.data && Array.isArray(response.data)) {
            featureTypes.value = response.data.map(type => ({
                value: type.type,
                label: type.label,
                icon: type.icon
            }));
        }
    } catch (error) {
        console.error('Error fetching feature types:', error);
        // Keep default values if API fails
    }
};

// Initialize component
onMounted(() => {
    fetchFeatureTypes();
});

// Initialize from existing longDescription if present
const initializeFromLongDescription = () => {
    if (props.form.longDescription) {
        try {
            // Check if it's already in JSON format
            const parsed = JSON.parse(props.form.longDescription);
            if (Array.isArray(parsed)) {
                descriptionPoints.value = parsed;
                nextPointId.value = Math.max(...parsed.map(p => p.id), 0) + 1;
                return;
            }
        } catch (e) {
            // Not JSON, might be regular text
        }

        // If not JSON or array, split by new lines and convert
        const lines = props.form.longDescription.split('\n').filter(line => line.trim());
        if (lines.length > 0) {
            descriptionPoints.value = lines.map((line, index) => ({
                id: index + 1,
                text: line.replace(/^[-•*]\s*/, '').trim(),
                type: 'feature'
            }));
            nextPointId.value = lines.length + 1;
        }
    }
};

// Initialize on component mount
initializeFromLongDescription();

// Add new description point
const addDescriptionPoint = () => {
    descriptionPoints.value.push({
        id: nextPointId.value++,
        text: '',
        type: 'feature'
    });
};

// Remove a description point
const removeDescriptionPoint = (id) => {
    descriptionPoints.value = descriptionPoints.value.filter(point => point.id !== id);
};

// Move a point up or down
const movePoint = (id, direction) => {
    const index = descriptionPoints.value.findIndex(p => p.id === id);
    if (index === -1) return;

    if (direction === 'up' && index > 0) {
        const temp = descriptionPoints.value[index];
        descriptionPoints.value[index] = descriptionPoints.value[index - 1];
        descriptionPoints.value[index - 1] = temp;
    } else if (direction === 'down' && index < descriptionPoints.value.length - 1) {
        const temp = descriptionPoints.value[index];
        descriptionPoints.value[index] = descriptionPoints.value[index + 1];
        descriptionPoints.value[index + 1] = temp;
    }
};

// Update the form's longDescription when description points change
watch(descriptionPoints, (newPoints) => {
    props.form.longDescription = JSON.stringify(newPoints);
}, { deep: true });

// Helper to get icon for a feature type
const getFeatureTypeIcon = (type) => {
    return featureTypes.value.find(t => t.value === type)?.icon || '•';
};

// Helper to convert description points to formatted text for preview
const getFormattedDescription = () => {
    return descriptionPoints.value
        .map(point => `${getFeatureTypeIcon(point.type)} ${point.text}`)
        .join('\n');
};
</script>

<template>
    <div class="p-6">
        <div class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel for="name" value="Product Name *" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError v-if="errors.name" :message="errors.name" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="sku" value="SKU *" />
                    <TextInput
                        id="sku"
                        name="sku"
                        v-model="form.sku"
                        type="text"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError v-if="errors.sku" :message="errors.sku" class="mt-2" />
                </div>
            </div>

            <div>
                <InputLabel for="shortDescription" value="Short Description (Summary)" />
                <textarea
                    id="shortDescription"
                    v-model="form.shortDescription"
                    rows="2"
                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    placeholder="Brief product summary that appears in search results and listings..."
                ></textarea>
                <InputError v-if="errors.shortDescription" :message="errors.shortDescription" class="mt-2" />
            </div>

            <!-- Feature-oriented Product Description -->
            <div>
                <div class="flex justify-between items-center mb-2">
                    <InputLabel value="Product Features & Details" />
                    <button
                        type="button"
                        @click="addDescriptionPoint"
                        class="text-sm px-3 py-1 bg-indigo-50 text-indigo-600 rounded-md flex items-center hover:bg-indigo-100"
                    >
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Add Feature
                    </button>
                </div>

                <!-- Description Points Builder -->
                <div class="bg-gray-50 rounded-lg p-4 mb-2">
                    <div v-for="point in descriptionPoints" :key="point.id" class="mb-3 bg-white p-3 rounded-md shadow-sm border border-gray-200">
                        <div class="flex items-start space-x-2">
                            <!-- Feature Type Selector -->
                            <div class="w-36">
                                <select
                                    v-model="point.type"
                                    class="w-full text-sm rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500"
                                >
                                    <option v-for="type in featureTypes" :key="type.value" :value="type.value">
                                        {{ type.icon }} {{ type.label }}
                                    </option>
                                </select>
                            </div>

                            <!-- Feature Text -->
                            <div class="flex-1">
                                <TextInput
                                    v-model="point.text"
                                    type="text"
                                    class="block w-full text-sm"
                                    placeholder="Enter product feature or detail..."
                                />
                            </div>

                            <!-- Point Actions -->
                            <div class="flex items-center space-x-1 text-gray-400">
                                <button
                                    type="button"
                                    @click="movePoint(point.id, 'up')"
                                    class="p-1 hover:text-gray-700"
                                    title="Move Up"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    @click="movePoint(point.id, 'down')"
                                    class="p-1 hover:text-gray-700"
                                    title="Move Down"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                <button
                                    type="button"
                                    @click="removeDescriptionPoint(point.id)"
                                    class="p-1 hover:text-red-500"
                                    title="Remove"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Empty State -->
                    <div v-if="descriptionPoints.length === 0" class="text-center py-4 text-gray-500">
                        <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="mt-1">No product features added</p>
                        <button
                            type="button"
                            @click="addDescriptionPoint"
                            class="mt-2 text-sm px-3 py-1 bg-indigo-50 text-indigo-600 rounded-md hover:bg-indigo-100"
                        >
                            Add your first feature
                        </button>
                    </div>
                </div>

                <!-- Description Preview -->
                <div v-if="descriptionPoints.length > 0" class="mt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-2">Preview:</h4>
                    <div class="bg-white p-4 rounded-md border border-gray-200">
                        <p v-for="(point, index) in descriptionPoints" :key="index" class="mb-1">
                            <span class="mr-2">{{ getFeatureTypeIcon(point.type) }}</span>
                            <span>{{ point.text }}</span>
                        </p>
                    </div>
                </div>

                <InputError v-if="errors.longDescription" :message="errors.longDescription" class="mt-2" />
            </div>

            <!-- Pricing & Inventory -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <InputLabel for="price" value="Regular Price (₹) *" />
                    <TextInput
                        id="price"
                        v-model="form.price"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError v-if="errors.price" :message="errors.price" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="sale_price" value="Sale Price (₹)" />
                    <TextInput
                        id="sale_price"
                        v-model="form.sale_price"
                        type="number"
                        step="0.01"
                        class="mt-1 block w-full"
                    />
                    <InputError v-if="errors.sale_price" :message="errors.sale_price" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="stock" value="Stock Quantity *" />
                    <TextInput
                        id="stock"
                        v-model="form.stock"
                        type="number"
                        class="mt-1 block w-full"
                        required
                    />
                    <InputError v-if="errors.stock" :message="errors.stock" class="mt-2" />
                </div>
            </div>

            <!-- Categories & Brand -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <InputLabel for="category_id" value="Category *" />
                    <select
                        id="category_id"
                        v-model="form.category_id"
                        class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        required
                    >
                        <option value="">Select Category</option>
                        <option v-for="category in (categories || [])" :key="category?.id || Math.random()" :value="category?.id">
                            {{ category?.name || 'Unknown Category' }}
                        </option>
                    </select>
                    <InputError v-if="errors.category_id" :message="errors.category_id" class="mt-2" />
                </div>
                <div>
                    <InputLabel for="brand_id" value="Brand" />
                    <select
                        id="brand_id"
                        v-model="form.brand_id"
                        class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                    >
                        <option value="">Select Brand</option>
                        <option v-for="brand in (brands || [])" :key="brand?.id || Math.random()" :value="brand?.id">
                            {{ brand?.name || 'Unknown Brand' }}
                        </option>
                    </select>
                    <InputError v-if="errors.brand_id" :message="errors.brand_id" class="mt-2" />
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
/* Optional: Add some custom styling for the feature bullets */
.feature-bullet {
    display: flex;
    align-items: flex-start;
    margin-bottom: 0.5rem;
}
</style>
