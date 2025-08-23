<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue'
import axios from 'axios'
import DataTable from 'primevue/datatable'
import Column from 'primevue/column'
import Button from 'primevue/button'
import Dialog from 'primevue/dialog'
import InputText from 'primevue/inputtext'
import Textarea from 'primevue/textarea'
import FileUpload from 'primevue/fileupload'
import Message from 'primevue/message'
import Image from 'primevue/image'
import Card from 'primevue/card'
import Toast from 'primevue/toast'
import ConfirmDialog from 'primevue/confirmdialog'

import { useToast } from 'primevue/usetoast'
import { useConfirm } from 'primevue/useconfirm'

const toast = useToast()
const confirm = useConfirm()




const form = ref({
  name: '',
  slug: '',
  logo: null,
  description: ''
})

const showMessage = ref(false)
const messageText = ref('')
const messageType = ref('success')
const showModal = ref(false)
const brands = ref([])
const loading = ref(false)
const editingBrand = ref(null)
const isEditing = ref(false)

const handleFileUpload = (event) => {
  form.value.logo = event.files[0]
}

const fetchBrands = async () => {
  loading.value = true
  try {
    const response = await axios.get('/brands')
    brands.value = response.data
  } catch (error) {
    console.error('Error fetching brands:', error)
    toast.add({
      severity: 'error',
      summary: 'Error',
      detail: 'Failed to fetch brands',
      life: 3000
    })
  } finally {
    loading.value = false
  }
}

const submitForm = async () => {
  const formData = new FormData()
  formData.append('name', form.value.name)
  formData.append('slug', form.value.slug)
  formData.append('description', form.value.description)
  if (form.value.logo) {
    formData.append('logo', form.value.logo)
  }

  try {
    let response
    if (isEditing.value) {
      formData.append('_method', 'PUT')
      response = await axios.post(`/brand/${editingBrand.value.id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    } else {
      response = await axios.post('/brand', formData, {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      })
    }

    toast.add({
      severity: 'success',
      summary: 'Success',
      detail: response.data.message,
      life: 3000
    })

    closeModal()
    fetchBrands()

  } catch (error) {
    if (error.response && error.response.data.errors) {
      const errors = Object.values(error.response.data.errors).flat()
      toast.add({
        severity: 'error',
        summary: 'Validation Error',
        detail: errors.join(', '),
        life: 5000
      })
    } else {
      toast.add({
        severity: 'error',
        summary: 'Error',
        detail: isEditing.value ? 'Error updating brand' : 'Error creating brand',
        life: 3000
      })
    }
  }
}

const closeModal = () => {
  showModal.value = false
  form.value = { name: '', slug: '', description: '', logo: null }
  editingBrand.value = null
  isEditing.value = false
}

const editBrand = (brand) => {
  editingBrand.value = brand
  isEditing.value = true
  form.value = {
    name: brand.name,
    slug: brand.slug,
    description: brand.description || '',
    logo: null
  }
  showModal.value = true
}

const deleteBrand = (brand) => {
  confirm.require({
    message: `Are you sure you want to delete "${brand.name}"?`,
    header: 'Delete Confirmation',
    icon: 'pi pi-exclamation-triangle',
    rejectClass: 'p-button-secondary p-button-outlined',
    acceptClass: 'p-button-danger',
    accept: async () => {
      try {
        const response = await axios.delete(`/brand/${brand.id}`, {
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
          }
        })

        toast.add({
          severity: 'success',
          summary: 'Success',
          detail: response.data.message,
          life: 3000
        })

        fetchBrands()
      } catch (error) {
        toast.add({
          severity: 'error',
          summary: 'Error',
          detail: 'Failed to delete brand',
          life: 3000
        })
      }
    }
  })
}

onMounted(() => {
  fetchBrands()
})
</script>

<template>
  <AuthenticatedLayout>
    <div class="min-h-screen bg-gray-50 py-12">
      <div class="px-4 sm:px-6 lg:px-8">

        <!-- Flash Message -->
        <Message
          v-if="showMessage"
          :severity="messageType"
          :closable="true"
          @close="showMessage = false"
          class="mb-4">
          {{ messageText }}
        </Message>

        <!-- Header Card -->
        <Card class="mb-6">
          <template #content>
            <div class="flex justify-between items-center">
              <div>
                <h1 class="text-2xl font-bold text-gray-900">Brand Management</h1>
                <p class="text-gray-600 mt-1">Manage your product brands</p>
              </div>
              <Button
                @click="showModal = true"
                label="Add New Brand"
                icon="pi pi-plus"
                class="p-button-primary" />
            </div>
          </template>
        </Card>

        <!-- Brands DataTable -->
        <Card>
          <template #content>
            <DataTable
              :value="brands"
              :loading="loading"
              paginator
              :rows="10"
              :rowsPerPageOptions="[5, 10, 20, 50]"
              tableStyle="min-width: 50rem"
              paginatorTemplate="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
              currentPageReportTemplate="{first} to {last} of {totalRecords}"
              class="p-datatable-sm">

              <template #empty>
                <div class="text-center py-8">
                  <i class="pi pi-inbox text-4xl text-gray-400 mb-4"></i>
                  <p class="text-gray-500">No brands found</p>
                </div>
              </template>

              <Column field="name" header="Name" sortable>
                <template #body="{ data }">
                  <span class="font-medium">{{ data.name }}</span>
                </template>
              </Column>

              <Column field="slug" header="Slug" sortable>
                <template #body="{ data }">
                  <span class="text-gray-600 font-mono text-sm">{{ data.slug }}</span>
                </template>
              </Column>

              <Column header="Logo" style="width: 120px">
                <template #body="{ data }">
                  <div class="flex justify-center">
                    <Image
                      v-if="data.logo"
                      :src="`/storage/${data.logo}`"
                      :alt="data.name"
                      width="40"
                      height="40"
                      class="rounded-lg border"
                      preview />
                    <div v-else class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center">
                      <i class="pi pi-image text-gray-400"></i>
                    </div>
                  </div>
                </template>
              </Column>

              <Column field="description" header="Description">
                <template #body="{ data }">
                  <span class="text-gray-600 text-sm">
                    {{ data.description || 'No description' }}
                  </span>
                </template>
              </Column>

              <Column header="Actions" style="width: 120px">
                <template #body="{ data }">
                  <div class="flex gap-2">
                    <Button
                      icon="pi pi-pencil"
                      size="small"
                      text
                      rounded
                      severity="info"
                      @click="editBrand(data)" />
                    <Button
                      icon="pi pi-trash"
                      size="small"
                      text
                      rounded
                      severity="danger"
                      @click="deleteBrand(data)" />
                  </div>
                </template>
              </Column>
            </DataTable>
          </template>
        </Card>

        <!-- Create/Edit Brand Modal -->
        <Dialog
          v-model:visible="showModal"
          modal
          :header="isEditing ? 'Edit Brand' : 'Create New Brand'"
          :style="{ width: '32rem' }"
          :breakpoints="{ '1199px': '75vw', '575px': '90vw' }">

          <form @submit.prevent="submitForm" class="space-y-6">
            <div class="space-y-2">
              <label for="brandName" class="block text-sm font-medium text-gray-700">
                Brand Name *
              </label>
              <InputText
                id="brandName"
                v-model="form.name"
                placeholder="Enter brand name"
                class="w-full"
                :class="{ 'p-invalid': !form.name }" />
            </div>

            <div class="space-y-2">
              <label for="brandSlug" class="block text-sm font-medium text-gray-700">
                Slug *
              </label>
              <InputText
                id="brandSlug"
                v-model="form.slug"
                placeholder="brand-slug"
                class="w-full"
                :class="{ 'p-invalid': !form.slug }" />
              <small class="text-gray-500">URL-friendly version of the name</small>
            </div>

            <div class="space-y-2">
              <label class="block text-sm font-medium text-gray-700">
                Logo
              </label>
              <FileUpload
                mode="basic"
                accept="image/*"
                :maxFileSize="1000000"
                @select="handleFileUpload"
                :auto="false"
                chooseLabel="Choose Logo"
                class="w-full" />
              <small class="text-gray-500">Maximum file size: 1MB</small>
            </div>

            <div class="space-y-2">
              <label for="brandDescription" class="block text-sm font-medium text-gray-700">
                Description
              </label>
              <Textarea
                id="brandDescription"
                v-model="form.description"
                rows="3"
                placeholder="Enter brand description"
                class="w-full" />
            </div>
          </form>

          <template #footer>
            <div class="flex justify-end gap-3">
              <Button
                label="Cancel"
                icon="pi pi-times"
                @click="closeModal"
                text />
              <Button
                :label="isEditing ? 'Update Brand' : 'Create Brand'"
                icon="pi pi-check"
                @click="submitForm" />
            </div>
          </template>
        </Dialog>
      </div>
    </div>

    <!-- Toast and ConfirmDialog -->
    <Toast />
    <ConfirmDialog />
  </AuthenticatedLayout>
</template>

<style scoped>
:deep(.p-datatable .p-datatable-thead > tr > th) {
  background-color: #f9fafb;
  color: #374151;
  font-weight: 600;
  font-size: 0.875rem;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

:deep(.p-datatable .p-datatable-tbody > tr:hover) {
  background-color: #f9fafb;
}

:deep(.p-card .p-card-content) {
  padding: 1.5rem;
}

:deep(.p-dialog .p-dialog-header) {
  background-color: #f9fafb;
  border-bottom: 1px solid #e5e7eb;
}

:deep(.p-fileupload-basic .p-button) {
  width: 100%;
}

:deep(.p-message) {
  margin-bottom: 1rem;
}
</style>
