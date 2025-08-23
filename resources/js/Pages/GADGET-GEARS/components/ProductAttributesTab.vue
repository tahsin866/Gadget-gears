<script setup>
import TextInput from '@/Components/TextInput.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    form: {
        type: Object,
        required: true
    },
    availableAttributes: {
        type: Array,
        required: true
    }
});

const emit = defineEmits(['add-attribute', 'remove-attribute', 'select-predefined-attribute']);

const addAttribute = () => {
    emit('add-attribute');
};

const removeAttribute = (index) => {
    emit('remove-attribute', index);
};

const selectPredefinedAttribute = (attribute, index) => {
    emit('select-predefined-attribute', attribute, index);
};
</script>

<template>
    <div class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-lg font-medium text-gray-900">Product Attributes</h3>
            <button @click="addAttribute" type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Add Attribute
            </button>
        </div>

        <div v-if="form.attributes.length === 0" class="text-center py-8 text-gray-500">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
            </svg>
            <p class="mt-2">No attributes added yet</p>
            <p class="text-sm">Add attributes like color, size, material, etc.</p>
        </div>

        <div v-else class="space-y-4">
            <div v-for="(attribute, index) in form.attributes" :key="index" class="flex items-end space-x-4 p-4 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <InputLabel :for="`attr_name_${index}`" value="Attribute Name" />
                    <div class="mt-1 relative">
                        <TextInput
                            :id="`attr_name_${index}`"
                            v-model="attribute.name"
                            type="text"
                            class="block w-full"
                            placeholder="e.g., Color, Size"
                        />
                        <!-- Predefined attributes dropdown -->
                        <div class="absolute right-2 top-2">
                            <select @change="selectPredefinedAttribute(availableAttributes.find(a => a.name === $event.target.value), index)" class="text-xs border-0 bg-transparent">
                                <option value="">Quick Select</option>
                                <option v-for="attr in availableAttributes" :key="attr.name" :value="attr.name">{{ attr.name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="flex-1">
                    <InputLabel :for="`attr_value_${index}`" value="Values (comma separated)" />
                    <TextInput
                        :id="`attr_value_${index}`"
                        v-model="attribute.value"
                        :type="attribute.type"
                        class="mt-1 block w-full"
                        placeholder="e.g., Red, Blue, Green OR Small, Medium, Large"
                    />
                    <p class="text-xs text-gray-500 mt-1">Enter multiple values separated by commas</p>
                </div>
                <div class="w-24">
                    <InputLabel :for="`attr_type_${index}`" value="Type" />
                    <select :id="`attr_type_${index}`" v-model="attribute.type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm">
                        <option value="text">Text</option>
                        <option value="number">Number</option>
                    </select>
                </div>
                <button @click="removeAttribute(index)" type="button" class="p-2 text-red-600 hover:text-red-800">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</template>
