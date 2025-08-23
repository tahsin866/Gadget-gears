<script setup>
import { defineProps, defineEmits } from 'vue';
import Drawer from 'primevue/drawer';
import Slider from 'primevue/slider';
import MultiSelect from 'primevue/multiselect';
import Rating from 'primevue/rating';
import Button from 'primevue/button';

const props = defineProps({
    visible: Boolean,
    filters: Object,
    brandOptions: Array,
    colorOptions: Array,
    sizeOptions: Array
});

defineEmits(['update:visible', 'reset-filters']);
</script>

<template>
    <Drawer :visible="visible"
             @update:visible="$emit('update:visible', $event)"
             position="right"
             class="w-full md:w-96"
             :modal="true">
        <template #header>
            <div class="flex items-center gap-2">
                <i class="pi pi-filter text-indigo-600"></i>
                <span class="font-bold text-lg">Advanced Filters</span>
            </div>
        </template>

        <div class="space-y-6 p-4">
            <!-- Price Range -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Price Range</label>
                <div class="px-2">
                    <Slider v-model="filters.priceRange"
                            :max="5000"
                            :min="0"
                            :step="50"
                            range
                            class="mb-4" />
                    <div class="flex justify-between text-sm text-gray-600">
                        <span>${{ filters.priceRange[0] }}</span>
                        <span>${{ filters.priceRange[1] }}</span>
                    </div>
                </div>
            </div>

            <!-- Brands -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Brands</label>
                <MultiSelect v-model="filters.selectedBrands"
                             :options="brandOptions"
                             optionLabel="label"
                             optionValue="label"
                             placeholder="Select brands"
                             class="w-full"
                             display="chip" />
            </div>

            <!-- Rating -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Minimum Rating</label>
                <Rating v-model="filters.rating" :cancel="true" />
            </div>

            <!-- Colors -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">Colors</label>
                <div class="grid grid-cols-4 gap-2">
                    <div v-for="color in colorOptions"
                         :key="color.value"
                         class="relative">
                        <input :id="`color-${color.value}`"
                               v-model="filters.selectedColors"
                               :value="color.value"
                               type="checkbox"
                               class="sr-only" />
                        <label :for="`color-${color.value}`"
                               class="flex flex-col items-center p-2 border-2 rounded-lg cursor-pointer transition-all hover:bg-gray-50"
                               :class="filters.selectedColors.includes(color.value) ? 'border-indigo-500 bg-indigo-50' : 'border-gray-200'">
                            <div class="w-6 h-6 rounded-full border border-gray-300 mb-1"
                                 :style="{ backgroundColor: color.color }"></div>
                            <span class="text-xs">{{ color.label }}</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Stock & Sale Filters -->
            <div class="space-y-3">
                <div class="flex items-center">
                    <input id="inStock"
                           v-model="filters.inStock"
                           type="checkbox"
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                    <label for="inStock" class="ml-2 text-sm text-gray-700">In Stock Only</label>
                </div>
                <div class="flex items-center">
                    <input id="onSale"
                           v-model="filters.onSale"
                           type="checkbox"
                           class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500" />
                    <label for="onSale" class="ml-2 text-sm text-gray-700">On Sale</label>
                </div>
            </div>

            <!-- Filter Actions -->
            <div class="pt-6 border-t border-gray-200 space-y-3">
                <Button label="Apply Filters"
                        icon="pi pi-check"
                        class="w-full"
                        @click="$emit('update:visible', false)" />
                <Button label="Clear All"
                        icon="pi pi-times"
                        outlined
                        class="w-full"
                        @click="$emit('reset-filters')" />
            </div>
        </div>
    </Drawer>
</template>
