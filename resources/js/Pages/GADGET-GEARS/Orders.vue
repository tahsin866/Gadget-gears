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

// Reactive data
const orders = ref([]);
const loading = ref(false);
const searchQuery = ref('');
const selectedStatus = ref('');
const showOrderDetails = ref(false);
const selectedOrder = ref(null);
const toast = useToast();

// Order status options
const statusOptions = [
    { label: 'All Orders', value: '' },
    { label: 'Pending', value: 'pending' },
    { label: 'Processing', value: 'processing' },
    { label: 'Shipped', value: 'shipped' },
    { label: 'Delivered', value: 'delivered' },
    { label: 'Cancelled', value: 'cancelled' }
];

// Fetch orders from API
const fetchOrders = async () => {
    loading.value = true;
    try {
        const response = await axios.get('/orders');
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

    // Filter by search query
    if (searchQuery.value) {
        const query = searchQuery.value.toLowerCase();
        filtered = filtered.filter(order =>
            order.order_number?.toLowerCase().includes(query) ||
            order.guest_name?.toLowerCase().includes(query) ||
            order.guest_email?.toLowerCase().includes(query)
        );
    }

    // Filter by status
    if (selectedStatus.value) {
        filtered = filtered.filter(order => order.status === selectedStatus.value);
    }

    return filtered;
});

// Stats computed
const stats = computed(() => {
    const total = orders.value.length;
    const pending = orders.value.filter(o => o.status === 'pending').length;
    const processing = orders.value.filter(o => o.status === 'processing').length;
    const shipped = orders.value.filter(o => o.status === 'shipped').length;
    const delivered = orders.value.filter(o => o.status === 'delivered').length;
    const cancelled = orders.value.filter(o => o.status === 'cancelled').length;

    const totalRevenue = orders.value
        .filter(o => o.status === 'delivered')
        .reduce((sum, order) => sum + parseFloat(order.total || 0), 0);

    return {
        total,
        pending,
        processing,
        shipped,
        delivered,
        cancelled,
        totalRevenue
    };
});

// View order details
const viewOrderDetails = (order) => {
    console.log('Order Details - Full Order:', order);
    console.log('Order Details - Order Items:', order.order_items);
    if (order.order_items && order.order_items[0]) {
        console.log('Order Details - First Item:', order.order_items[0]);
        console.log('Order Details - First Item Attribute Value:', order.order_items[0].attribute_value);
    }
    selectedOrder.value = order;
    showOrderDetails.value = true;
};

// Get status badge severity
const getStatusSeverity = (status) => {
    switch (status) {
        case 'pending': return 'warning';
        case 'processing': return 'info';
        case 'shipped': return 'primary';
        case 'delivered': return 'success';
        case 'cancelled': return 'danger';
        default: return 'secondary';
    }
};

// Format currency
const formatCurrency = (amount) => {
    return new Intl.NumberFormat('en-US', {
        style: 'currency',
        currency: 'USD'
    }).format(amount || 0);
};

// Format date
const formatDate = (dateString) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
};

// Update order status
const updateOrderStatus = async (orderId, newStatus) => {
    try {
        await axios.patch(`/orders/${orderId}/status`, { status: newStatus });
        toast.add({
            severity: 'success',
            summary: 'Success',
            detail: 'Order status updated successfully',
            life: 3000
        });
        fetchOrders(); // Refresh orders
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

// Lifecycle
onMounted(() => {
    fetchOrders();
});
</script>

<template>
    <Head title="Order Management" />
    <Toast position="top-right" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="text-xl font-semibold leading-tight text-gray-800">
                    Order Management
                </h2>
                <Button
                    icon="pi pi-refresh"
                    label="Refresh"
                    @click="fetchOrders"
                    :loading="loading"
                    severity="secondary"
                />
            </div>
        </template>

        <div class="py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    <Card class="text-center">
                        <template #content>
                            <div class="space-y-2">
                                <div class="text-3xl font-bold text-blue-600">{{ stats.total }}</div>
                                <div class="text-sm text-gray-600">Total Orders</div>
                            </div>
                        </template>
                    </Card>

                    <Card class="text-center">
                        <template #content>
                            <div class="space-y-2">
                                <div class="text-3xl font-bold text-orange-600">{{ stats.pending }}</div>
                                <div class="text-sm text-gray-600">Pending Orders</div>
                            </div>
                        </template>
                    </Card>

                    <Card class="text-center">
                        <template #content>
                            <div class="space-y-2">
                                <div class="text-3xl font-bold text-green-600">{{ stats.delivered }}</div>
                                <div class="text-sm text-gray-600">Delivered Orders</div>
                            </div>
                        </template>
                    </Card>

                    <Card class="text-center">
                        <template #content>
                            <div class="space-y-2">
                                <div class="text-3xl font-bold text-purple-600">{{ formatCurrency(stats.totalRevenue) }}</div>
                                <div class="text-sm text-gray-600">Total Revenue</div>
                            </div>
                        </template>
                    </Card>
                </div>

                <!-- Filters and Search -->
                <Card>
                    <template #content>
                        <div class="flex flex-col md:flex-row gap-4 items-center justify-between">
                            <div class="flex flex-col md:flex-row gap-4 flex-1">
                                <div class="flex-1">
                                    <InputText
                                        v-model="searchQuery"
                                        placeholder="Search by order number, customer name or email..."
                                        class="w-full"
                                    />
                                </div>
                                <div class="w-full md:w-48">
                                    <Dropdown
                                        v-model="selectedStatus"
                                        :options="statusOptions"
                                        optionLabel="label"
                                        optionValue="value"
                                        placeholder="Filter by status"
                                        class="w-full"
                                    />
                                </div>
                            </div>
                        </div>
                    </template>
                </Card>

                <!-- Orders Table -->
                <Card>
                    <template #content>
                        <DataTable
                            :value="filteredOrders"
                            :loading="loading"
                            paginator
                            :rows="10"
                            dataKey="id"
                            :rowHover="true"
                            filterDisplay="menu"
                            :globalFilterFields="['order_number', 'guest_name', 'guest_email']"
                            showGridlines
                            class="p-datatable-sm"
                        >
                            <template #empty>
                                <div class="text-center py-8">
                                    <i class="pi pi-inbox text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500">No orders found</p>
                                </div>
                            </template>

                            <template #loading>
                                <div class="flex justify-center items-center py-8">
                                    <ProgressBar mode="indeterminate" style="height: 6px" />
                                </div>
                            </template>

                            <Column field="order_number" header="Order #" :sortable="true">
                                <template #body="{ data }">
                                    <div class="font-medium text-blue-600">
                                        #{{ data.order_number }}
                                    </div>
                                </template>
                            </Column>

                            <Column field="guest_name" header="Customer" :sortable="true">
                                <template #body="{ data }">
                                    <div>
                                        <div class="font-medium">{{ data.guest_name || 'N/A' }}</div>
                                        <div class="text-sm text-gray-500">{{ data.guest_email || 'N/A' }}</div>
                                    </div>
                                </template>
                            </Column>

                            <Column header="Products" :sortable="false">
                                <template #body="{ data }">
                                    <div class="max-w-xs">
                                        <div v-if="(data.order_items || data.orderItems) && (data.order_items?.length > 0 || data.orderItems?.length > 0)" class="space-y-1">
                                            <div
                                                v-for="(item, index) in (data.order_items || data.orderItems).slice(0, 2)"
                                                :key="item.id"
                                                class="text-xs"
                                            >
                                                <div class="font-medium text-gray-800 truncate">
                                                    {{ item.product?.name || 'Product' }}
                                                </div>
                                                <div v-if="item.attribute_value || item.attributeValue" class="text-gray-500">
                                                    {{ (item.attribute_value || item.attributeValue)?.attribute?.name }}:
                                                    <span class="text-blue-600">{{ (item.attribute_value || item.attributeValue)?.value }}</span>
                                                </div>
                                            </div>
                                            <div v-if="(data.order_items || data.orderItems).length > 2" class="text-xs text-gray-500">
                                                +{{ (data.order_items || data.orderItems).length - 2 }} more...
                                            </div>
                                        </div>
                                        <div v-else class="text-xs text-gray-500">
                                            No items
                                        </div>
                                    </div>
                                </template>
                            </Column>

                            <Column field="total" header="Total" :sortable="true">
                                <template #body="{ data }">
                                    <div class="font-bold text-green-600">
                                        {{ formatCurrency(data.total) }}
                                    </div>
                                </template>
                            </Column>

                            <Column field="status" header="Status" :sortable="true">
                                <template #body="{ data }">
                                    <Tag
                                        :value="data.status"
                                        :severity="getStatusSeverity(data.status)"
                                        class="capitalize"
                                    />
                                </template>
                            </Column>

                            <Column field="payment_method" header="Payment" :sortable="true">
                                <template #body="{ data }">
                                    <Chip
                                        :label="data.payment_method || 'N/A'"
                                        class="capitalize"
                                    />
                                </template>
                            </Column>

                            <Column field="created_at" header="Order Date" :sortable="true">
                                <template #body="{ data }">
                                    <div class="text-sm">
                                        {{ formatDate(data.created_at) }}
                                    </div>
                                </template>
                            </Column>

                            <Column header="Actions" :exportable="false">
                                <template #body="{ data }">
                                    <div class="flex gap-2">
                                        <Button
                                            icon="pi pi-eye"
                                            size="small"
                                            severity="info"
                                            @click="viewOrderDetails(data)"
                                            v-tooltip.top="'View Details'"
                                        />
                                        <Dropdown
                                            :options="statusOptions.slice(1)"
                                            optionLabel="label"
                                            optionValue="value"
                                            placeholder="Update Status"
                                            @change="(e) => updateOrderStatus(data.id, e.value)"
                                            class="w-32"
                                        >
                                            <template #value="slotProps">
                                                <Button
                                                    icon="pi pi-cog"
                                                    size="small"
                                                    severity="secondary"
                                                    v-tooltip.top="'Update Status'"
                                                />
                                            </template>
                                        </Dropdown>
                                    </div>
                                </template>
                            </Column>
                        </DataTable>
                    </template>
                </Card>
            </div>
        </div>

        <!-- Order Details Dialog -->
        <Dialog
            v-model:visible="showOrderDetails"
            :style="{ width: '800px' }"
            header="Order Details"
            :modal="true"
            class="p-fluid"
        >
            <div v-if="selectedOrder" class="space-y-6">
                <!-- Order Header -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <h4 class="text-lg font-semibold mb-2">Order Information</h4>
                        <div class="space-y-2">
                            <div><strong>Order #:</strong> #{{ selectedOrder.order_number }}</div>
                            <div><strong>Status:</strong>
                                <Tag
                                    :value="selectedOrder.status"
                                    :severity="getStatusSeverity(selectedOrder.status)"
                                    class="ml-2 capitalize"
                                />
                            </div>
                            <div><strong>Payment:</strong> {{ selectedOrder.payment_method || 'N/A' }}</div>
                            <div><strong>Date:</strong> {{ formatDate(selectedOrder.created_at) }}</div>
                        </div>
                    </div>

                    <div>
                        <h4 class="text-lg font-semibold mb-2">Customer Information</h4>
                        <div class="space-y-2">
                            <div><strong>Name:</strong> {{ selectedOrder.guest_name || 'N/A' }}</div>
                            <div><strong>Email:</strong> {{ selectedOrder.guest_email || 'N/A' }}</div>
                            <div><strong>Phone:</strong> {{ selectedOrder.guest_phone || 'N/A' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Shipping Address -->
                <div v-if="selectedOrder.shipping_address">
                    <h4 class="text-lg font-semibold mb-2">Shipping Address</h4>
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <p>{{ selectedOrder.shipping_address }}</p>
                        <div class="text-sm text-gray-600 mt-2">
                            <span v-if="selectedOrder.shipping_district">{{ selectedOrder.shipping_district }}, </span>
                            <span v-if="selectedOrder.shipping_thana">{{ selectedOrder.shipping_thana }}, </span>
                            <span v-if="selectedOrder.shipping_postoffice">{{ selectedOrder.shipping_postoffice }}</span>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div v-if="(selectedOrder.order_items || selectedOrder.orderItems) && (selectedOrder.order_items?.length > 0 || selectedOrder.orderItems?.length > 0)">
                    <h4 class="text-lg font-semibold mb-3">Order Items</h4>
                    <div class="space-y-3">
                        <div
                            v-for="item in (selectedOrder.order_items || selectedOrder.orderItems)"
                            :key="item.id"
                            class="bg-white border border-gray-200 rounded-lg p-4"
                        >
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <h5 class="font-medium text-gray-900">
                                        {{ item.product?.name || 'Product' }}
                                    </h5>

                                    <!-- Product Attributes -->
                                    <div v-if="item.attribute_value || item.attributeValue" class="mt-2">
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="pi pi-tags mr-2"></i>
                                            <span class="font-medium">{{ (item.attribute_value || item.attributeValue)?.attribute?.name }}:</span>
                                            <span class="ml-1 px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                {{ (item.attribute_value || item.attributeValue)?.value }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mt-2 text-sm text-gray-500">
                                        Quantity: {{ item.quantity }} × {{ formatCurrency(item.price) }}
                                    </div>
                                </div>

                                <div class="text-right">
                                    <div class="font-semibold text-gray-900">
                                        {{ formatCurrency(item.subtotal) }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div>
                    <h4 class="text-lg font-semibold mb-2">Order Summary</h4>
                    <div class="bg-gray-50 p-4 rounded-lg space-y-2">
                        <div class="flex justify-between">
                            <span>Subtotal:</span>
                            <span>{{ formatCurrency(selectedOrder.subtotal) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Tax:</span>
                            <span>{{ formatCurrency(selectedOrder.tax) }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span>Shipping:</span>
                            <span>{{ formatCurrency(selectedOrder.shipping) }}</span>
                        </div>
                        <hr class="my-2">
                        <div class="flex justify-between font-bold text-lg">
                            <span>Total:</span>
                            <span class="text-green-600">{{ formatCurrency(selectedOrder.total) }}</span>
                        </div>
                    </div>
                </div>

                <!-- Notes -->
                <div v-if="selectedOrder.notes">
                    <h4 class="text-lg font-semibold mb-2">Order Notes</h4>
                    <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                        <p class="text-yellow-800">{{ selectedOrder.notes }}</p>
                    </div>
                </div>
            </div>

            <template #footer>
                <Button label="Close" icon="pi pi-times" @click="showOrderDetails = false" />
            </template>
        </Dialog>
    </AuthenticatedLayout>
</template>

<style scoped>
.p-datatable .p-datatable-thead > tr > th {
    background-color: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    font-weight: 600;
    color: #374151;
}

.p-datatable .p-datatable-tbody > tr:hover {
    background-color: #f8fafc;
}

.p-card {
    border: 1px solid #e2e8f0;
    border-radius: 0.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}
</style>
