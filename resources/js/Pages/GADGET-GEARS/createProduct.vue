<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { ref, onMounted } from 'vue';
import axios from 'axios';

// Child components
import TabNavigation from '@/Pages/GADGET-GEARS/components/TabNavigation.vue';
import BasicInformationTab from '@/Pages/GADGET-GEARS/components/BasicInformationTab.vue';
import ProductImagesTab from '@/Pages/GADGET-GEARS/components/ProductImagesTab.vue';
import ProductAttributesTab from '@/Pages/GADGET-GEARS/components/ProductAttributesTab.vue';
import FinalSettingsTab from '@/Pages/GADGET-GEARS/components/FinalSettingsTab.vue';
import FormNavButtons from '@/Pages/GADGET-GEARS/components/FormNavButtons.vue';

// Tab system
const currentTab = ref(0);
const tabs = [
    { id: 0, name: 'Basic Information', icon: 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z' },
    { id: 1, name: 'Product Images', icon: 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z' },
    { id: 2, name: 'Product Attributes', icon: 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z' },
    { id: 3, name: 'Final Settings', icon: 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z' }
];

// Form data
const form = ref({
    name: '',
    sku: '',
    shortDescription: '',
    longDescription: '',
    price: '',
    sale_price: '',
    stock: '0',
    category_id: '',
    brand_id: '',
    status: 'active',
    featured: false,
    meta_title: '',
    meta_description: '',
    images: [],
    primary_image: 0,
    attributes: []
});

// Data
const categories = ref([]);
const brands = ref([]);
const errors = ref({});
const isSubmitting = ref(false);

// Image handling
const selectedImages = ref([]);
const imagePreviewUrls = ref([]);
const primaryImageIndex = ref(0);

// Available attributes for dropdown
const availableAttributes = ref([
    { name: 'Color', type: 'text' },
    { name: 'Size', type: 'text' },
    { name: 'Material', type: 'text' },
    { name: 'Weight', type: 'number' },
    { name: 'Dimensions', type: 'text' },
    { name: 'Brand Model', type: 'text' },
    { name: 'Battery Capacity', type: 'text' },
    { name: 'Warranty Period', type: 'text' },
    { name: 'Expiry Date', type: 'date' },
    { name: 'Ingredients', type: 'text' },
    { name: 'Volume', type: 'text' },
    { name: 'Power Consumption', type: 'text' },
    { name: 'Storage Capacity', type: 'text' },
    { name: 'Screen Size', type: 'text' },
    { name: 'Operating System', type: 'text' },
    { name: 'Processor', type: 'text' },
    { name: 'RAM', type: 'text' },
    { name: 'Gender', type: 'text' },
    { name: 'Age Group', type: 'text' },
    { name: 'Pattern', type: 'text' },
    { name: 'Fit Type', type: 'text' },
    { name: 'Fabric Type', type: 'text' },
    { name: 'Usage Type', type: 'text' },
    { name: 'Publication Year', type: 'number' },
    { name: 'Author', type: 'text' },
    { name: 'Publisher', type: 'text' },
    { name: 'Language', type: 'text' },
    { name: 'Fragrance', type: 'text' },
    { name: 'Coverage Area', type: 'text' },
    { name: 'Mounting Type', type: 'text' },
    { name: 'Wheel Type', type: 'text' },
    { name: 'Fuel Type', type: 'text' },
    { name: 'Engine Capacity', type: 'text' },
    { name: 'Speed Rating', type: 'text' },
    { name: 'Number of Pieces', type: 'number' },
    { name: 'Compatibility', type: 'text' },
    { name: 'Connectivity Type', type: 'text' },
    { name: 'Waterproof Level', type: 'text' },
    { name: 'Barcode', type: 'text' },
    { name: 'SKU', type: 'text' }
]);

// Tab navigation functions
const nextTab = () => {
    if (currentTab.value < tabs.length - 1) {
        currentTab.value++;
    }
};

const prevTab = () => {
    if (currentTab.value > 0) {
        currentTab.value--;
    }
};

const goToTab = (tabIndex) => {
    currentTab.value = tabIndex;
};

// Image functions
const handleImageSelect = (event) => {
    const files = Array.from(event.target.files);

    files.forEach((file, index) => {
        if (file.size > 2 * 1024 * 1024) {
            alert(`File ${file.name} is too large. Maximum size is 2MB.`);
            return;
        }

        selectedImages.value.push(file);
        form.value.images.push(file);

        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreviewUrls.value.push(e.target.result);
        };
        reader.readAsDataURL(file);
    });
};

const removeImage = (index) => {
    selectedImages.value.splice(index, 1);
    imagePreviewUrls.value.splice(index, 1);
    form.value.images.splice(index, 1);

    if (primaryImageIndex.value >= selectedImages.value.length && selectedImages.value.length > 0) {
        primaryImageIndex.value = 0;
        form.value.primary_image = 0;
    } else if (selectedImages.value.length === 0) {
        primaryImageIndex.value = 0;
        form.value.primary_image = 0;
    }
};

const setPrimaryImage = (index) => {
    primaryImageIndex.value = index;
    form.value.primary_image = index;
};

// Attribute functions
const addAttribute = () => {
    form.value.attributes.push({
        name: '',
        value: '',
        type: 'text'
    });
};

const removeAttribute = (index) => {
    form.value.attributes.splice(index, 1);
};

const selectPredefinedAttribute = (attribute, index) => {
    form.value.attributes[index].name = attribute.name;
    form.value.attributes[index].type = attribute.type;
};

// Data fetching
const fetchCategories = async () => {
    try {
        const response = await axios.get('/show-SubCetagory');
        categories.value = Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        console.error('Error fetching categories:', error);
        categories.value = [];
    }
};

const fetchBrands = async () => {
    try {
        const response = await axios.get('/show-brand');
        brands.value = Array.isArray(response.data) ? response.data : [];
    } catch (error) {
        console.error('Error fetching brands:', error);
        brands.value = [];
    }
};

// Form submission
const submitForm = () => {
    isSubmitting.value = true;
    errors.value = {};

    console.log('Form data:', form.value);
    console.log('Selected images:', selectedImages.value.length);

    const formData = new FormData();

    // Append all form fields
    Object.keys(form.value).forEach(key => {
        if (key !== 'images' && key !== 'attributes' && form.value[key] !== null && form.value[key] !== undefined) {
            // Convert boolean to 0/1 for 'featured'
            if (key === 'featured') {
                formData.append(key, form.value[key] ? 1 : 0);
                console.log(`Appending ${key}: ${form.value[key] ? 1 : 0}`);
            } else {
                formData.append(key, form.value[key]);
                console.log(`Appending ${key}: ${form.value[key]}`);
            }
        }
    });

    // Append images properly
    selectedImages.value.forEach((image, index) => {
        formData.append(`images[${index}]`, image);
        console.log(`Appending image ${index}`);
    });

    // Make sure primary_image is included
    formData.append('primary_image', primaryImageIndex.value);
    console.log(`Primary image index: ${primaryImageIndex.value}`);

    // Make sure attributes are properly formatted
    console.log('Processing attributes:', form.value.attributes);
    form.value.attributes.forEach((attr, index) => {
        if (attr.name && attr.value) {
            // Trim values and remove empty entries
            const trimmedName = attr.name.trim();
            const trimmedValue = attr.value.trim();
            const trimmedType = (attr.type || 'text').trim();
            
            if (trimmedName && trimmedValue) {
                formData.append(`attributes[${index}][name]`, trimmedName);
                formData.append(`attributes[${index}][value]`, trimmedValue);
                formData.append(`attributes[${index}][type]`, trimmedType);
                console.log(`Appending attribute ${index}: ${trimmedName}=${trimmedValue} (${trimmedType})`);
            }
        }
    });

    // Debug: Check what's actually in formData
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }

    // Use axios instead of router for API call
    axios.post(route('products.store'), formData, {
        headers: {
            'Content-Type': 'multipart/form-data',
        }
    })
    .then(response => {
        console.log('Success response:', response.data);
        if (response.data.success) {
            alert('Product created successfully!');
            window.history.back();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        if (error.response && error.response.data) {
            if (error.response.status === 422) {
                // Validation errors
                errors.value = error.response.data.errors || {};
                console.error('Validation errors:', errors.value);
                alert('Please check the form for validation errors. Check console for details.');
            } else {
                alert('Error creating product: ' + (error.response.data.message || 'Unknown error'));
            }
        } else {
            alert('Network error occurred');
        }
    })
    .finally(() => {
        isSubmitting.value = false;
    });
};

const goBack = () => {
    window.history.back();
};

// Lifecycle
onMounted(() => {
    fetchCategories();
    fetchBrands();
    addAttribute(); // Add one attribute by default
});
</script>

<template>
    <Head title="Add New Product" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-4">
                    <button @click="goBack" class="text-gray-500 hover:text-gray-700">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                    </button>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Add New Product
                    </h2>
                </div>
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Tab Navigation Component -->
                <TabNavigation
                    :tabs="tabs"
                    :current-tab="currentTab"
                    @go-to-tab="goToTab"
                />

                <form @submit.prevent="submitForm">
                    <!-- Tab Content -->
                    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
                        <!-- Basic Information Tab -->
                        <BasicInformationTab
                            v-show="currentTab === 0"
                            :form="form"
                            :categories="categories"
                            :brands="brands"
                        />

                        <!-- Product Images Tab -->
                        <ProductImagesTab
                            v-show="currentTab === 1"
                            :image-preview-urls="imagePreviewUrls"
                            :primary-image-index="primaryImageIndex"
                            :errors="errors"
                            @handle-image-select="handleImageSelect"
                            @remove-image="removeImage"
                            @set-primary-image="setPrimaryImage"
                        />

                        <!-- Product Attributes Tab -->
                        <ProductAttributesTab
                            v-show="currentTab === 2"
                            :form="form"
                            :available-attributes="availableAttributes"
                            @add-attribute="addAttribute"
                            @remove-attribute="removeAttribute"
                            @select-predefined-attribute="selectPredefinedAttribute"
                        />

                        <!-- Final Settings Tab -->
                        <FinalSettingsTab
                            v-show="currentTab === 3"
                            :form="form"
                            :errors="errors"
                        />
                    </div>

                    <!-- Form Navigation Buttons -->
                    <FormNavButtons
                        :current-tab="currentTab"
                        :tabs-length="tabs.length"
                        :tabs="tabs"
                        :is-submitting="isSubmitting"
                        @prev-tab="prevTab"
                        @next-tab="nextTab"
                        @go-back="goBack"
                    />
                </form>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped>
.animate-spin {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from {
        transform: rotate(0deg);
    }
    to {
        transform: rotate(360deg);
    }
}
</style>
