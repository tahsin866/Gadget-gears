<script setup>
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    currentTab: {
        type: Number,
        required: true
    },
    tabsLength: {
        type: Number,
        required: true
    },
    tabs: {
        type: Array,
        required: true
    },
    isSubmitting: {
        type: Boolean,
        required: true
    }
});

const emit = defineEmits(['prev-tab', 'next-tab', 'go-back']);

const prevTab = () => {
    emit('prev-tab');
};

const nextTab = () => {
    emit('next-tab');
};

const goBack = () => {
    emit('go-back');
};
</script>

<template>
    <div class="bg-white shadow-sm rounded-lg mt-6 p-6">
        <div class="flex justify-between items-center">
            <button
                v-if="currentTab > 0"
                @click="prevTab"
                type="button"
                class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Previous
            </button>
            <div v-else></div>

            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-500">
                    Step {{ currentTab + 1 }} of {{ tabsLength }}
                </span>
                <div class="flex space-x-1">
                    <div
                        v-for="(tab, index) in tabs"
                        :key="index"
                        class="w-2 h-2 rounded-full"
                        :class="index <= currentTab ? 'bg-indigo-500' : 'bg-gray-300'"
                    ></div>
                </div>
            </div>

            <div class="flex space-x-3">
                <SecondaryButton @click="goBack" type="button">
                    Cancel
                </SecondaryButton>

                <button
                    v-if="currentTab < tabsLength - 1"
                    @click="nextTab"
                    type="button"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                >
                    Next
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>

                <PrimaryButton
                    v-else
                    type="submit"
                    :disabled="isSubmitting"
                    class="inline-flex items-center"
                >
                    <svg v-if="isSubmitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    {{ isSubmitting ? 'Creating Product...' : 'Create Product' }}
                </PrimaryButton>
            </div>
        </div>
    </div>
</template>
