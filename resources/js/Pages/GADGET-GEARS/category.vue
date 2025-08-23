<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, computed, onMounted } from 'vue';
import axios from 'axios';
import modal from './modal.vue';
import parentCetagiryModel from './parentCetagiryModel.vue';

import Card from 'primevue/card';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Tag from 'primevue/tag';
import Badge from 'primevue/badge';
import Skeleton from 'primevue/skeleton';
import Message from 'primevue/message';
import ConfirmDialog from 'primevue/confirmdialog';

import { useConfirm } from 'primevue/useconfirm';
import { useToast } from 'primevue/usetoast';

// Composables
const confirm = useConfirm();
const toast = useToast();

// Reactive data
const showCetagoryList = ref([]);
const count = ref(0);
const loading = ref(false);
const error = ref(null);

// Computed properties for statistics
const activeCount = computed(() => {
    return showCetagoryList.value.filter(category => category.status === 'active').length;
});

const featuredCount = computed(() => {
    return showCetagoryList.value.filter(category => category.featured === 1 || category.featured === true).length;
});

const totalProducts = computed(() => {
    return showCetagoryList.value.reduce((total, category) => total + (category.products_count || 0), 0);
});

// Reactive variables
const showModal = ref(false);
const showCetagoryModal = ref(false);
const editingCategory = ref(null);
const ParentCategory = ref(null);
const searchQuery = ref('');
const statusFilter = ref('all');
const sortBy = ref('name');

// Filter options
const statusOptions = [
    { label: 'All Status', value: 'all' },
    { label: 'Active', value: 'active' },
    { label: 'Inactive', value: 'inactive' },
    { label: 'Draft', value: 'draft' }
];

const sortOptions = [
    { label: 'Sort by Name', value: 'name' },
    { label: 'Sort by Products', value: 'products_count' },
    { label: 'Sort by Date', value: 'created_at' },
    { label: 'Sort by Order', value: 'order' }
];

const filteredCategories = computed(() => {
    let filtered = showCetagoryList.value;

    if (searchQuery.value) {
        filtered = filtered.filter(category =>
            category.name.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (category.description && category.description.toLowerCase().includes(searchQuery.value.toLowerCase()))
        );
    }

    if (statusFilter.value !== 'all') {
        filtered = filtered.filter(category => category.status === statusFilter.value);
    }

    return filtered.sort((a, b) => {
        switch (sortBy.value) {
            case 'name':
                return a.name.localeCompare(b.name);
            case 'products_count':
                return (b.products_count || 0) - (a.products_count || 0);
            case 'created_at':
                return new Date(b.created_at) - new Date(a.created_at);
            default:
                return 0;
        }
    });
});

// Fetch categories data
const fetchCategories = async () => {
    try {
        loading.value = true;
        error.value = null;
        const response = await axios.get('/show-cetagory-list');
        if (response.data.success) {
            showCetagoryList.value = response.data.showCetagoryList;
            count.value = response.data.count;
        } else {
            error.value = 'Failed to fetch categories';
        }
    } catch (err) {
        error.value = err.response?.data?.message || 'An error occurred while fetching categories';
        console.error('Error fetching categories:', err);
    } finally {
        loading.value = false;
    }
};

// Modal functions
const openEditModal = (category) => {
    editingCategory.value = category;
    showModal.value = true;
};

const openAddModal = () => {
    editingCategory.value = null;
    showModal.value = true;
};

const openCetagoryModal = () => {
    ParentCategory.value = null;
    showCetagoryModal.value = true;
};

const handleCategoryUpdated = () => {
    showModal.value = false;
    showCetagoryModal.value = false;
    editingCategory.value = null;
    ParentCategory.value = null;
    fetchCategories();
    toast.add({
        severity: 'success',
        summary: 'Success',
        detail: 'Category updated successfully',
        life: 3000
    });
};

const deleteCategory = async (category) => {
    confirm.require({
        message: `Are you sure you want to delete "${category.name}"?`,
        header: 'Delete Confirmation',
        icon: 'pi pi-exclamation-triangle',
        rejectClass: 'p-button-secondary p-button-outlined',
        acceptClass: 'p-button-danger',
        accept: async () => {
            try {
                const response = await fetch(`${window.location.origin}/delete-category/${category.id}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                if (data.success) {
                    await fetchCategories();
                    toast.add({
                        severity: 'success',
                        summary: 'Success',
                        detail: data.message,
                        life: 3000
                    });
                } else {
                    toast.add({
                        severity: 'error',
                        summary: 'Error',
                        detail: data.message,
                        life: 3000
                    });
                }
            } catch (error) {
                console.error('Error deleting category:', error);
                toast.add({
                    severity: 'error',
                    summary: 'Error',
                    detail: 'An error occurred while deleting the category',
                    life: 3000
                });
            }
        }
    });
};

const getStatusSeverity = (status) => {
    const severities = {
        'active': 'success',
        'inactive': 'danger',
        'draft': 'warning'
    };
    return severities[status] || 'info';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Fetch data on component mount
onMounted(() => {
    fetchCategories();
});
</script>

<template>
    <Head title="Category Management" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        Category Management
                    </h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Organize and manage your product categories
                    </p>
                </div>
                <div class="flex gap-3">
                    <Button
                        @click="openCetagoryModal"
                        icon="pi pi-plus"
                        label="Add Parent Category"
                        class="p-button-outlined"
                    />
                    <Button
                        @click="openAddModal"
                        icon="pi pi-plus"
                        label="Add Category"
                    />
                    <Button
                        icon="pi pi-download"
                        label="Export"
                        class="p-button-outlined"
                    />
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="sm:px-6 lg:px-8">
                <!-- Statistics Cards -->
                 <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 mb-8">
    <Card class="relative bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
      <template #content>
        <div class="flex items-center justify-between px-6 py-6">
          <div>
            <div class="text-4xl font-extrabold mb-2 animate-count">{{ count }}</div>
            <div class="text-blue-800 text-sm font-medium tracking-wide">Total Categories</div>
          </div>
          <div class="bg-white bg-opacity-20 rounded-full p-3 flex items-center justify-center">
            <i class="pi pi-th-large text-3xl text-blue-800"></i>
          </div>
        </div>
      </template>
    </Card>

    <Card class="relative bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
      <template #content>
        <div class="flex items-center justify-between px-6 py-6">
          <div>
            <div class="text-4xl font-extrabold mb-2 animate-count">{{ activeCount }}</div>
            <div class="text-green-800 text-sm font-medium tracking-wide">Active Categories</div>
          </div>
          <div class="bg-white bg-opacity-20 rounded-full p-3 flex items-center justify-center">
            <i class="pi pi-check-circle text-3xl text-green-800"></i>
          </div>
        </div>
      </template>
    </Card>

    <Card class="relative bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
      <template #content>
        <div class="flex items-center justify-between px-6 py-6">
          <div>
            <div class="text-4xl font-extrabold mb-2 animate-count">{{ featuredCount }}</div>
            <div class="text-yellow-800 text-sm font-medium tracking-wide">Featured Categories</div>
          </div>
          <div class="bg-white bg-opacity-20 rounded-full p-3 flex items-center justify-center">
            <i class="pi pi-star text-3xl text-yellow-800"></i>
          </div>
        </div>
      </template>
    </Card>

    <Card class="relative bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300">
      <template #content>
        <div class="flex items-center justify-between px-6 py-6">
          <div>
            <div class="text-4xl font-extrabold mb-2 animate-count">{{ totalProducts }}</div>
            <div class="text-purple-800 text-sm font-medium tracking-wide">Total Products</div>
          </div>
          <div class="bg-white bg-opacity-20 rounded-full p-3 flex items-center justify-center">
            <i class="pi pi-box text-3xl text-purple-800"></i>
          </div>
        </div>
      </template>
    </Card>
  </div>

                <!-- Error Message -->
                <Message v-if="error" severity="error" :closable="false" class="mb-6">
                    {{ error }}
                </Message>

                <!-- Categories Table -->
                <Card>
                    <template #header>
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-4">
                            <div class="flex-1">
                                <span class="p-input-icon-left w-full max-w-md">

                                    <InputText
                                        v-model="searchQuery"
                                        placeholder="Search categories..."
                                        class="w-full"
                                    />
                                </span>
                            </div>
                            <div class="flex gap-3">
                                <Dropdown
                                    v-model="statusFilter"
                                    :options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Filter by Status"
                                    class="w-48"
                                />
                                <Dropdown
                                    v-model="sortBy"
                                    :options="sortOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Sort by"
                                    class="w-48"
                                />
                            </div>
                        </div>
                    </template>

                    <template #content>
                        <DataTable
                            :value="filteredCategories"
                            :loading="loading"
                            stripedRows
                            responsiveLayout="scroll"
                            :paginator="true"
                            :rows="10"
                            :rowsPerPageOptions="[5, 10, 20, 50]"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} entries"
                        >
                            <template #loading>
                                <div class="grid">
                                    <div class="col-12" v-for="i in 5" :key="i">
                                        <Skeleton height="4rem" class="mb-2"></Skeleton>
                                    </div>
                                </div>
                            </template>

                            <template #empty>
                                <div class="text-center py-8">
                                    <i class="pi pi-inbox text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No categories found</h3>
                                    <p class="text-gray-500 mb-4">Get started by creating a new category.</p>
                                    <Button
                                        @click="openAddModal"
                                        icon="pi pi-plus"
                                        label="Add Category"
                                    />
                                </div>
                            </template>

                            <Column field="name" header="Category" sortable>
                                <template #body="{ data }">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-blue-100 to-purple-100 rounded-lg flex items-center justify-center">
                                            <i class="pi pi-th-large text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="font-semibold text-gray-900">{{ data.name }}</div>
                                            <div class="text-sm text-gray-500 font-mono bg-gray-100 px-2 py-1 rounded mt-1 inline-block">
                                                {{ data.slug }}
                                            </div>
                                            <div v-if="data.description" class="text-sm text-gray-400 mt-1 max-w-xs truncate">
                                                {{ data.description }}
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </Column>

                            <Column field="status" header="Status" sortable>
                                <template #body="{ data }">
                                    <Tag
                                        :value="data.status"
                                        :severity="getStatusSeverity(data.status)"
                                        class="capitalize"
                                    />
                                </template>
                            </Column>

                            <Column field="products_count" header="Products" sortable>
                                <template #body="{ data }">
                                    <div class="flex items-center gap-2 bg-gray-50 px-3 py-2 rounded-lg w-fit">
                                        <i class="pi pi-box text-blue-500"></i>
                                        <Badge :value="data.products_count || 0" />
                                    </div>
                                </template>
                            </Column>

                            <Column field="parent_id" header="Type">
                                <template #body="{ data }">
                                    <div class="flex items-center gap-2">
                                        <Tag
                                            v-if="data.parent_id"
                                            value="Subcategory"
                                            severity="info"
                                            icon="pi pi-arrow-right"
                                        />
                                        <Tag
                                            v-else
                                            value="Parent Category"
                                            severity="success"
                                            icon="pi pi-star"
                                        />
                                        <Badge
                                            v-if="data.children && data.children.length > 0"
                                            :value="`${data.children.length} subcategories`"
                                            class="ml-2"
                                        />
                                    </div>
                                </template>
                            </Column>

                            <Column field="meta_title" header="Meta Title">
                                <template #body="{ data }">
                                    <div v-if="data.meta_title" class="max-w-xs truncate bg-gray-50 px-3 py-2 rounded-lg" :title="data.meta_title">
                                        {{ data.meta_title }}
                                    </div>
                                    <span v-else class="text-gray-400 italic bg-gray-50 px-3 py-2 rounded-lg">No meta title</span>
                                </template>
                            </Column>

                            <Column field="meta_description" header="Meta Description">
                                <template #body="{ data }">
                                    <div v-if="data.meta_description" class="max-w-xs truncate bg-gray-50 px-3 py-2 rounded-lg" :title="data.meta_description">
                                        {{ data.meta_description }}
                                    </div>
                                    <span v-else class="text-gray-400 italic bg-gray-50 px-3 py-2 rounded-lg">No meta description</span>
                                </template>
                            </Column>

                            <Column field="created_at" header="Created" sortable>
                                <template #body="{ data }">
                                    <div class="bg-gray-50 px-3 py-2 rounded-lg font-medium text-sm">
                                        {{ formatDate(data.created_at) }}
                                    </div>
                                </template>
                            </Column>

                            <Column header="Actions" :exportable="false">
                                <template #body="{ data }">
                                    <div class="flex gap-2">
                                        <Button
                                            @click="openEditModal(data)"
                                            icon="pi pi-pencil"
                                            class="p-button-rounded p-button-text p-button-info"
                                            v-tooltip.top="'Edit Category'"
                                        />
                                        <Button
                                            @click="deleteCategory(data)"
                                            icon="pi pi-trash"
                                            class="p-button-rounded p-button-text p-button-danger"
                                            v-tooltip.top="'Delete Category'"
                                        />
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </template>
                </Card>
            </div>
        </div>

        <!-- Category Modal -->
        <modal
            v-model:show="showModal"
            :category="editingCategory"
            @category-saved="handleCategoryUpdated"
        />

        <!-- Parent Category Modal -->
        <parentCetagiryModel
            v-model:show="showCetagoryModal"
            :category="ParentCategory"
            @category-saved="handleCategoryUpdated"
        />

        <!-- Confirm Dialog -->
        <ConfirmDialog />
    </AuthenticatedLayout>
</template>
