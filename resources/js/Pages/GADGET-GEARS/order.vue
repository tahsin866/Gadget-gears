<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';
import axios from 'axios';

// Import PrimeVue components
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';
import Tag from 'primevue/tag';
import Badge from 'primevue/badge';
import Dialog from 'primevue/dialog';
import Toast from 'primevue/toast';
import { useToast } from 'primevue/usetoast';
import InputText from 'primevue/inputtext';
import Dropdown from 'primevue/dropdown';
import Card from 'primevue/card';
import Chip from 'primevue/chip';
import ProgressBar from 'primevue/progressbar';
import Skeleton from 'primevue/skeleton';
import Timeline from 'primevue/timeline';

// Reactive data
const orders = ref([]);
const loading = ref(true);
const selectedOrder = ref(null);
const showOrderDetails = ref(false);
const searchQuery = ref('');
const selectedStatus = ref('');
const toast = useToast();

// Filter options
const statusOptions = ref([
    { label: 'All Status', value: '' },
    { label: 'Pending', value: 'pending' },
    { label: 'Processing', value: 'processing' },
    { label: 'Shipped', value: 'shipped' },
    { label: 'Delivered', value: 'delivered' },
    { label: 'Cancelled', value: 'cancelled' }
]);

// Fetch orders from API
const fetchOrders = async () => {
    try {
        loading.value = true;
        const response = await axios.get('/api/orders');
        orders.value = response.data;
    } catch (error) {
        console.error('Error fetching orders:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to fetch orders',
            life: 3000
        });
    } finally {
        loading.value = false;
    }
};

// Computed properties
const filteredOrders = computed(() => {
    let filtered = orders.value;

    if (searchQuery.value) {
        filtered = filtered.filter(order =>
            order.order_number.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            (order.guest_name && order.guest_name.toLowerCase().includes(searchQuery.value.toLowerCase())) ||
            (order.guest_email && order.guest_email.toLowerCase().includes(searchQuery.value.toLowerCase()))
        );
    }

    if (selectedStatus.value) {
        filtered = filtered.filter(order => order.status === selectedStatus.value);
    }

    return filtered;
});

const stats = computed(() => {
    const totalOrders = orders.value.length;
    const pendingOrders = orders.value.filter(o => o.status === 'pending').length;
    const processingOrders = orders.value.filter(o => o.status === 'processing').length;
    const deliveredOrders = orders.value.filter(o => o.status === 'delivered').length;
    const totalRevenue = orders.value.reduce((sum, order) => sum + parseFloat(order.total || 0), 0);

    return [
        {
            title: 'Total Orders',
            value: totalOrders,
            icon: 'pi pi-shopping-cart',
            color: 'bg-blue-500'
        },
        {
            title: 'Pending Orders',
            value: pendingOrders,
            icon: 'pi pi-clock',
            color: 'bg-orange-500'
        },
        {
            title: 'Processing',
            value: processingOrders,
            icon: 'pi pi-cog',
            color: 'bg-purple-500'
        },
        {
            title: 'Delivered',
            value: deliveredOrders,
            icon: 'pi pi-check-circle',
            color: 'bg-green-500'
        }
    ];
});

// Helper functions
const getStatusSeverity = (status) => {
    const severityMap = {
        'pending': 'warning',
        'processing': 'info',
        'shipped': 'success',
        'delivered': 'success',
        'cancelled': 'danger'
    };
    return severityMap[status] || 'info';
};

const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

const formatPrice = (price) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(price);
};

// Actions
const viewOrderDetails = (order) => {
    selectedOrder.value = order;
    showOrderDetails.value = true;
};

const updateOrderStatus = async (order, newStatus) => {
    try {
        const response = await axios.patch(`/api/orders/${order.id}/status`, {
            status: newStatus
        });

        // Update local data
        const index = orders.value.findIndex(o => o.id === order.id);
        if (index !== -1) {
            orders.value[index].status = newStatus;
        }

        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Order status updated successfully',
            life: 3000
        });
    } catch (error) {
        console.error('Error updating order status:', error);
        toast.add({
            severity: 'error',
            summary: 'Error',
            detail: 'Failed to update order status',
            life: 3000
        });
    }
};

const getCustomerName = (order) => {
    return order.guest_name || order.user?.name || 'Guest Customer';
};

const getCustomerContact = (order) => {
    return order.guest_email || order.user?.email || 'N/A';
};

// Lifecycle
onMounted(() => {
    fetchOrders();
});
</script>











<template>
    <Toast position="top-right" />
    <AuthenticatedLayout>
        <Head title="Order Management" />

        <div class="min-h-screen bg-gray-50">
            <!-- Header Section -->
            <div class="bg-white shadow-sm border-b">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold text-gray-900">Order Management</h1>
                            <p class="text-gray-600 mt-1">Manage customer orders and track shipments</p>
                        </div>
                        <Button
                            icon="pi pi-refresh"
                            label="Refresh"
                            @click="fetchOrders"
                            :loading="loading"
                        />
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <Card v-for="stat in stats" :key="stat.title" class="shadow-lg">
                        <template #content>
                            <div class="flex items-center p-4">
                                <div :class="[stat.color, 'w-12 h-12 rounded-lg flex items-center justify-center text-white mr-4']">
                                    <i :class="stat.icon" class="text-xl"></i>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ stat.value }}</h3>
                                    <p class="text-gray-600 text-sm">{{ stat.title }}</p>
                                </div>
                            </div>
                        </template>
                    </Card>
                </div>

                <!-- Filters Section -->
                <Card class="mb-6 shadow-lg">
                    <template #content>
                        <div class="flex flex-col md:flex-row gap-4 items-center justify-between p-4">
                            <div class="flex gap-4 items-center">
                                <div class="p-input-icon-left">
                                    <i class="pi pi-search" />
                                    <InputText
                                        v-model="searchQuery"
                                        placeholder="Search by order number, customer name, or email..."
                                        class="w-80"
                                    />
                                </div>
                                <Dropdown
                                    v-model="selectedStatus"
                                    :options="statusOptions"
                                    optionLabel="label"
                                    optionValue="value"
                                    placeholder="Filter by Status"
                                    class="w-48"
                                />
                            </div>
                            <div class="text-sm text-gray-600">
                                Showing {{ filteredOrders.length }} of {{ orders.length }} orders
                            </div>
                        </div>
                    </template>
                </Card>

                <!-- Orders DataTable -->
                <Card class="shadow-lg">
                    <template #content>
                        <DataTable
                            :value="filteredOrders"
                            :loading="loading"
                            paginator
                            :rows="10"
                            :rowsPerPageOptions="[5, 10, 20, 50]"
                            paginatorTemplate="FirstPageLink PrevPageLink PageLinks NextPageLink LastPageLink CurrentPageReport RowsPerPageDropdown"
                            currentPageReportTemplate="Showing {first} to {last} of {totalRecords} orders"
                            selectionMode="single"
                            dataKey="id"
                            class="p-datatable-customers"
                        >
                            <template #loading>
                                <div class="flex items-center justify-center p-8">
                                    <ProgressBar mode="indeterminate" style="height: 6px" />
                                </div>
                            </template>

                            <template #empty>
                                <div class="text-center p-8">
                                    <i class="pi pi-shopping-cart text-4xl text-gray-400 mb-4"></i>
                                    <h3 class="text-lg font-semibold text-gray-600 mb-2">No Orders Found</h3>
                                    <p class="text-gray-500">No orders match your current filters.</p>
                                </div>
                            </template>

                            <Column field="order_number" header="Order Number" sortable>
                                <template #body="{ data }">
                                    <div class="font-semibold text-blue-600">#{{ data.order_number }}</div>
                                </template>
                            </Column>

                            <Column field="customer" header="Customer" sortable>
                                <template #body="{ data }">
                                    <div>
                                        <div class="font-medium text-gray-900">{{ getCustomerName(data) }}</div>
                                        <div class="text-sm text-gray-500">{{ getCustomerContact(data) }}</div>
                                    </div>
                                </template>
                            </Column>

                            <Column field="items_count" header="Items" sortable>
                                <template #body="{ data }">
                                    <Badge
                                        :value="data.order_items?.length || 0"
                                        severity="info"
                                    />
                                </template>
                            </Column>

                            <Column field="total" header="Total" sortable>
                                <template #body="{ data }">
                                    <div class="font-semibold text-green-600">{{ formatPrice(data.total) }}</div>
                                </template>
                            </Column>

                            <Column field="payment_method" header="Payment" sortable>
                                <template #body="{ data }">
                                    <Chip
                                        :label="data.payment_method || 'N/A'"
                                        class="text-xs"
                                    />
                                </template>
                            </Column>

                            <Column field="status" header="Status" sortable>
                                <template #body="{ data }">
                                    <Tag
                                        :value="data.status"
                                        :severity="getStatusSeverity(data.status)"
                                        class="text-xs font-semibold uppercase"
                                    />
                                </template>
                            </Column>

                            <Column field="created_at" header="Order Date" sortable>
                                <template #body="{ data }">
                                    <div class="text-sm text-gray-600">{{ formatDate(data.created_at) }}</div>
                                </template>
                            </Column>

                            <Column header="Actions" :exportable="false">
                                <template #body="{ data }">
                                    <div class="flex gap-2">
                                        <Button
                                            icon="pi pi-eye"
                                            class="p-button-text p-button-sm"
                                            @click="viewOrderDetails(data)"
                                            v-tooltip.top="'View Details'"
                                        />
                                        <Dropdown
                                            v-if="data.status !== 'delivered' && data.status !== 'cancelled'"
                                            v-model="data.status"
                                            :options="statusOptions.filter(s => s.value)"
                                            optionLabel="label"
                                            optionValue="value"
                                            @change="updateOrderStatus(data, $event.value)"
                                            class="p-dropdown-sm"
                                            placeholder="Update Status"
                                        />
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </template>
                </Card>

                <!-- Order Details Dialog -->
                <Dialog
                    v-model:visible="showOrderDetails"
                    :modal="true"
                    header="Order Details"
                    :style="{ width: '60vw' }"
                    :maximizable="true"
                >
                    <div v-if="selectedOrder" class="space-y-6">
                        <!-- Order Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Order Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Order Number:</span>
                                        <span class="font-medium">#{{ selectedOrder.order_number }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Status:</span>
                                        <Tag
                                            :value="selectedOrder.status"
                                            :severity="getStatusSeverity(selectedOrder.status)"
                                        />
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Payment Method:</span>
                                        <span class="font-medium">{{ selectedOrder.payment_method || 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Order Date:</span>
                                        <span class="font-medium">{{ formatDate(selectedOrder.created_at) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <h4 class="font-semibold text-gray-900 mb-3">Customer Information</h4>
                                <div class="space-y-2 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Name:</span>
                                        <span class="font-medium">{{ getCustomerName(selectedOrder) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Email:</span>
                                        <span class="font-medium">{{ getCustomerContact(selectedOrder) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Phone:</span>
                                        <span class="font-medium">{{ selectedOrder.guest_phone || 'N/A' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Shipping Address -->
                        <div v-if="selectedOrder.shipping_address">
                            <h4 class="font-semibold text-gray-900 mb-3">Shipping Address</h4>
                            <div class="bg-gray-50 p-4 rounded-lg text-sm">
                                {{ selectedOrder.shipping_address }}
                                <div v-if="selectedOrder.shipping_district" class="mt-2">
                                    <span class="text-gray-600">District:</span> {{ selectedOrder.shipping_district }}
                                    <span v-if="selectedOrder.shipping_thana" class="ml-4 text-gray-600">Thana:</span> {{ selectedOrder.shipping_thana }}
                                </div>
                            </div>
                        </div>

                        <!-- Order Items -->
                        <div>
                            <h4 class="font-semibold text-gray-900 mb-3">Order Items</h4>
                            <DataTable :value="selectedOrder.order_items || []" class="p-datatable-sm">
                                <Column field="product.name" header="Product">
                                    <template #body="{ data }">
                                        <div>
                                            <div class="font-medium">{{ data.product?.name || 'Product Name' }}</div>
                                            <div v-if="data.color_name" class="text-xs text-gray-500">
                                                Color: {{ data.color_name }}
                                            </div>
                                        </div>
                                    </template>
                                </Column>
                                <Column field="quantity" header="Quantity">
                                    <template #body="{ data }">
                                        <Badge :value="data.quantity" severity="info" />
                                    </template>
                                </Column>
                                <Column field="price" header="Unit Price">
                                    <template #body="{ data }">
                                        {{ formatPrice(data.price) }}
                                    </template>
                                </Column>
                                <Column field="subtotal" header="Subtotal">
                                    <template #body="{ data }">
                                        <span class="font-semibold">{{ formatPrice(data.subtotal) }}</span>
                                    </template>
                                </Column>
                            </DataTable>
                        </div>

                        <!-- Order Summary -->
                        <div class="border-t pt-4">
                            <div class="space-y-2">
                                <div class="flex justify-between">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span>{{ formatPrice(selectedOrder.subtotal || selectedOrder.total) }}</span>
                                </div>
                                <div v-if="selectedOrder.tax" class="flex justify-between">
                                    <span class="text-gray-600">Tax:</span>
                                    <span>{{ formatPrice(selectedOrder.tax) }}</span>
                                </div>
                                <div v-if="selectedOrder.shipping" class="flex justify-between">
                                    <span class="text-gray-600">Shipping:</span>
                                    <span>{{ formatPrice(selectedOrder.shipping) }}</span>
                                </div>
                                <div class="flex justify-between font-semibold text-lg border-t pt-2">
                                    <span>Total:</span>
                                    <span class="text-green-600">{{ formatPrice(selectedOrder.total) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div v-if="selectedOrder.notes">
                            <h4 class="font-semibold text-gray-900 mb-3">Order Notes</h4>
                            <div class="bg-yellow-50 p-4 rounded-lg text-sm border border-yellow-200">
                                {{ selectedOrder.notes }}
                            </div>
                        </div>
                    </div>
                </Dialog>
            </div>
        </div>
    </AuthenticatedLayout>
</template>



  <style>
  /* Add any custom styles here if needed */
  </style>
