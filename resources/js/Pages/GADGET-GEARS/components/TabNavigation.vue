<script setup>
defineProps({
    tabs: {
        type: Array,
        required: true
    },
    currentTab: {
        type: Number,
        required: true
    }
});

const emit = defineEmits(['go-to-tab']);

const goToTab = (tabId) => {
    emit('go-to-tab', tabId);
};
</script>

<template>
    <div class="bg-white shadow-sm rounded-lg mb-8">
        <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                <button
                    v-for="tab in tabs"
                    :key="tab.id"
                    @click="goToTab(tab.id)"
                    :class="[
                        currentTab === tab.id
                            ? 'border-indigo-500 text-indigo-600'
                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                        'whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm flex items-center space-x-2'
                    ]"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" :d="tab.icon"/>
                    </svg>
                    <span>{{ tab.name }}</span>
                </button>
            </nav>
        </div>
    </div>
</template>
