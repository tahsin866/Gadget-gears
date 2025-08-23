<template>
  <AuthenticatedLayout>
    <div
      style="font-family: 'SolaimanLipi', sans-serif;"
      class="min-h-screen bg-gray-50 py-8"
    >
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-gray-900">সেটিংস সিস্টেম</h1>
          <p class="text-gray-600 mt-2">ডেলিভারি চার্জ এবং ট্যাক্স রেট কনফিগার করুন</p>
        </div>

        <!-- Tab Navigation -->
        <div class="mb-8">
          <div class="border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
              <button
                v-for="tab in tabs"
                :key="tab.id"
                @click="activeTab = tab.id"
                :class="[
                  activeTab === tab.id
                    ? 'border-blue-500 text-blue-600'
                    : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
                  'whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm flex items-center'
                ]"
              >
                <component :is="tab.icon" class="w-5 h-5 mr-2" />
                {{ tab.name }}
              </button>
            </nav>
          </div>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
          <!-- Delivery & Tax Tab -->
          <div v-show="activeTab === 'delivery-tax'" class="tab-pane">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
              <!-- Delivery Charge Settings -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                  <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    ডেলিভারি চার্জ সেটিংস
                  </h2>
                </div>

                <div class="p-6">
                  <!-- Add New Area with Division/District/Thana Dropdowns -->
                  <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h3 class="font-medium text-blue-900 mb-3">নতুন এরিয়া যোগ করুন</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                      <Select
                        v-model="newArea.division"
                        :options="divisions"
                        optionLabel="Division"
                        optionValue="ID"
                        placeholder="বিভাগ সিলেক্ট করুন"
                        @change="onDivisionChange"
                        class="w-full"
                        :loading="!divisions.length"
                      />
                      <Select
                        v-model="newArea.district"
                        :options="districts"
                        optionLabel="District"
                        optionValue="DID"
                        placeholder="জেলা সিলেক্ট করুন"
                        @change="onDistrictChange"
                        class="w-full"
                        :disabled="!newArea.division"
                      />
                      <Select
                        v-model="newArea.thana"
                        :options="thanas"
                        optionLabel="Thana"
                        optionValue="Thana_ID"
                        placeholder="থানা সিলেক্ট করুন"
                        class="w-full"
                        :disabled="!newArea.district"
                        @change="onThanaChange"
                      />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">

                      <input
                        v-model="newArea.charge"
                        type="number"
                        placeholder="চার্জ (৳)"
                        class="px-3 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                      >
                    </div>
                    <button
                      @click="addDeliveryArea"
                      class="mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors flex items-center"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                      যোগ করুন
                    </button>
                  </div>

                  <!-- Delivery Areas List -->
                  <div class="space-y-3">
                    <div
                      v-for="(area, index) in deliveryAreas"
                      :key="index"
                      class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors"
                    >
                      <div class="flex items-center">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                          <h4 class="font-medium text-gray-900">{{ area.name }}</h4>
                          <p class="text-sm text-gray-600">চার্জ: ৳{{ area.charge }}</p>
                        </div>
                      </div>
                      <div class="flex items-center space-x-2">
                        <button
                          @click="editDeliveryArea(index)"
                          class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                          </svg>
                        </button>
                        <button
                          @click="removeDeliveryArea(index)"
                          class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tax Rate Settings -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200">
                <div class="p-6 border-b border-gray-200">
                  <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    ট্যাক্স রেট সেটিংস
                  </h2>
                </div>

                <div class="p-6">
                  <!-- Add New Tax Category -->
                  <div class="mb-6 p-4 bg-green-50 rounded-lg border border-green-200">
                    <h3 class="font-medium text-green-900 mb-3">নতুন ক্যাটেগরি যোগ করুন</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                      <input
                        v-model="newTaxCategory.name"
                        type="text"
                        placeholder="ক্যাটেগরির নাম"
                        class="px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                      >
                      <input
                        v-model="newTaxCategory.rate"
                        type="number"
                        step="0.01"
                        placeholder="ট্যাক্স রেট (%)"
                        class="px-3 py-2 border border-green-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent"
                      >
                    </div>
                    <button
                      @click="addTaxCategory"
                      class="mt-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                      </svg>
                      যোগ করুন
                    </button>
                  </div>

                  <!-- Tax Categories List -->
                  <div class="space-y-3">
                    <div
                      v-for="(category, index) in taxCategories"
                      :key="index"
                      class="flex items-center justify-between p-4 bg-gray-50 rounded-lg border border-gray-200 hover:bg-gray-100 transition-colors"
                    >
                      <div class="flex items-center">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <div>
                          <h4 class="font-medium text-gray-900">{{ category.name }}</h4>
                          <p class="text-sm text-gray-600">ট্যাক্স রেট: {{ category.rate }}%</p>
                        </div>
                      </div>
                      <div class="flex items-center space-x-2">
                        <button
                          @click="editTaxCategory(index)"
                          class="p-2 text-green-600 hover:bg-green-100 rounded-lg transition-colors"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                          </svg>
                        </button>
                        <button
                          @click="removeTaxCategory(index)"
                          class="p-2 text-red-600 hover:bg-red-100 rounded-lg transition-colors"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
                        </button>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- General Settings Tab -->
          <div v-show="activeTab === 'general'" class="tab-pane">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">

                  <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                  </svg>
                  সাধারণ সেটিংস
                </h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ফ্রি ডেলিভারি লিমিট</span>
                    <input
                      v-model="generalSettings.freeDeliveryLimit"
                      type="number"
                      class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-purple-500"
                    >
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ডিফল্ট ট্যাক্স রেট</span>
                    <input
                      v-model="generalSettings.defaultTaxRate"
                      type="number"
                      step="0.01"
                      class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-purple-500"
                    >
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">অটো ট্যাক্স ক্যালকুলেশন</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="generalSettings.autoTaxCalculation"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-purple-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Additional General Settings -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">অতিরিক্ত সেটিংস</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">মিনিমাম অর্ডার পরিমাণ</span>
                    <input
                      v-model="generalSettings.minimumOrderAmount"
                      type="number"
                      class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-purple-500"
                    >
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ডেলিভারি টাইম (দিন)</span>
                    <input
                      v-model="generalSettings.deliveryTime"
                      type="number"
                      class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-purple-500"
                    >
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">রিটার্ন পলিসি (দিন)</span>
                    <input
                      v-model="generalSettings.returnPolicy"
                      type="number"
                      class="w-20 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-2 focus:ring-purple-500"
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Notification Settings Tab -->
          <div v-show="activeTab === 'notifications'" class="tab-pane">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM12 17.5a6.5 6.5 0 110-13 6.5 6.5 0 010 13z"/>
                  </svg>
                  নোটিফিকেশন টাইপ
                </h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ইমেইল নোটিফিকেশন</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.email"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">SMS নোটিফিকেশন</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.sms"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">পুশ নোটিফিকেশন</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.push"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Order Notifications -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">অর্ডার নোটিফিকেশন</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">নতুন অর্ডার</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.newOrder"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">অর্ডার আপডেট</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.orderUpdate"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ডেলিভারি সম্পন্ন</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.deliveryComplete"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Marketing Notifications -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">মার্কেটিং নোটিফিকেশন</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">প্রমোশনাল অফার</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.promotional"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">নিউজলেটার</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.newsletter"
                        type="checkbox"
                        class="sr-only peer"
                      >
                                       <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">স্পেশাল ইভেন্ট</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="notificationSettings.specialEvents"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-orange-600"></div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Payment Settings Tab -->
          <div v-show="activeTab === 'payment'" class="tab-pane">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                  </svg>
                  পেমেন্ট মেথড
                </h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ক্যাশ অন ডেলিভারি</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.cod"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">অনলাইন পেমেন্ট</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.online"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">মোবাইল ব্যাংকিং</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.mobileBanking"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Payment Gateway Settings -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">পেমেন্ট গেটওয়ে</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">বিকাশ</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.bkash"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">নগদ</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.nagad"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">রকেট</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.rocket"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Payment Security -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">পেমেন্ট সিকিউরিটি</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">SSL সিকিউরিটি</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.sslSecurity"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">টু-ফ্যাক্টর অথ</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.twoFactorAuth"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">পেমেন্ট লগ</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="paymentSettings.paymentLog"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
                    </label>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- System Settings Tab -->
          <div v-show="activeTab === 'system'" class="tab-pane">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <svg class="w-5 h-5 mr-2 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                  </svg>
                  সিস্টেম কনফিগারেশন
                </h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ডিবাগ মোড</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="systemSettings.debugMode"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                 <span class="text-sm text-gray-700">মেইনটেনেন্স মোড</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="systemSettings.maintenanceMode"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">অটো ব্যাকআপ</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="systemSettings.autoBackup"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ক্যাশ সিস্টেম</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="systemSettings.cacheSystem"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- Database Settings -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">ডেটাবেস সেটিংস</h3>
                <div class="space-y-4">
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">অটো অপটিমাইজেশন</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="systemSettings.autoOptimization"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">ডেটা ক্লিনআপ</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="systemSettings.dataCleanup"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                  <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700">লগ রোটেশন</span>
                    <label class="relative inline-flex items-center cursor-pointer">
                      <input
                        v-model="systemSettings.logRotation"
                        type="checkbox"
                        class="sr-only peer"
                      >
                      <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-red-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-red-600"></div>
                    </label>
                  </div>
                </div>
              </div>

              <!-- System Information -->
              <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 md:col-span-2">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">সিস্টেম ইনফরমেশন</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">সিস্টেম ভার্সন</div>
                    <div class="text-lg font-semibold text-gray-900">{{ systemInfo.version }}</div>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">ডেটাবেস সাইজ</div>
                    <div class="text-lg font-semibold text-gray-900">{{ systemInfo.databaseSize }}</div>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-4">
                    <div class="text-sm text-gray-600">সর্বশেষ ব্যাকআপ</div>
                    <div class="text-lg font-semibold text-gray-900">{{ systemInfo.lastBackup }}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Save Button -->
        <div class="mt-8 flex justify-end">
          <button
            @click="saveSettings"
            :disabled="saving"
            class="px-6 py-3 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg shadow-lg hover:from-blue-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
          >
            <span v-if="saving" class="flex items-center">
              <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              সেভ করা হচ্ছে...
            </span>
            <span v-else>সেটিংস সেভ করুন</span>
          </button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import Select from 'primevue/select'
import axios from 'axios'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue'

// Reactive data
const activeTab = ref('general')
const saving = ref(false)

// Tab configuration
const tabs = ref([
  { id: 'delivery-tax', name: 'ডেলিভারি ও ট্যাক্স', icon: 'svg' },
  { id: 'general', name: 'সাধারণ সেটিংস', icon: 'svg' },
  { id: 'notifications', name: 'নোটিফিকেশন', icon: 'svg' },
  { id: 'payment', name: 'পেমেন্ট', icon: 'svg' },
  { id: 'system', name: 'সিস্টেম', icon: 'svg' }
])

// Delivery Areas
const deliveryAreas = ref([
  { name: 'ঢাকা শহর', charge: 60 },
  { name: 'ঢাকার বাইরে', charge: 120 }
])

const newArea = reactive({
  division: '',
  district: '',
  thana: '',
  name: '',
  charge: 0
})

const onDistrictChange = async () => {
  // Find DesID for selected district
  const selectedDistrict = districts.value.find(d => d.DID == newArea.district);
  const desId = selectedDistrict ? selectedDistrict.Des_ID : null;
  newArea.thana = '';
  thanas.value = [];
  if (desId) {
    try {
      const response = await axios.get(`/thanas/${desId}`);
      thanas.value = response.data;
    } catch (error) {
      console.error('Error fetching thanas:', error);
    }
  }
}

const onThanaChange = () => {
  const selectedThana = thanas.value.find(t => t.Thana_ID == newArea.thana);
  newArea.name = selectedThana ? selectedThana.Thana : '';
}

onMounted(() => {
  fetchDivisions();
})

// Tax Categories
const taxCategories = ref([
  { name: 'সাধারণ পণ্য', rate: 15 },
  { name: 'বিলাসবহুল পণ্য', rate: 25 }
])

const newTaxCategory = reactive({
  name: '',
  rate: 0
})

// General Settings
const generalSettings = reactive({
  freeDeliveryLimit: 500,
  defaultTaxRate: 15.00,
  autoTaxCalculation: true,
  minimumOrderAmount: 100,
  deliveryTime: 3,
  returnPolicy: 7
})

// Notification Settings
const notificationSettings = reactive({
  email: true,
  sms: true,
  push: false,
  newOrder: true,
  orderUpdate: true,
  deliveryComplete: true,
  promotional: false,
  newsletter: true,
  specialEvents: true
})

// Payment Settings
const paymentSettings = reactive({
  cod: true,
  online: true,
  mobileBanking: true,
  bkash: true,
  nagad: true,
  rocket: false,
  sslSecurity: true,
  twoFactorAuth: false,
  paymentLog: true
})

// System Settings
const systemSettings = reactive({
  debugMode: false,
  maintenanceMode: false,
  autoBackup: true,
  cacheSystem: true,
  autoOptimization: true,
  dataCleanup: true,
  logRotation: true
})

// System Information
const systemInfo = reactive({
  version: 'v2.1.0',
  databaseSize: '245 MB',
  lastBackup: '২৪ ডিসেম্বর, ২০২৩'
})

// Methods
const addDeliveryArea = () => {
  if (newArea.name && newArea.charge) {
    deliveryAreas.value.push({
      name: newArea.name,
      charge: newArea.charge
    })
    newArea.name = ''
    newArea.charge = 0
  }
}

const removeDeliveryArea = (index) => {
  deliveryAreas.value.splice(index, 1)
}

const editDeliveryArea = (index) => {
  // Edit functionality
}

const addTaxCategory = () => {
  if (newTaxCategory.name && newTaxCategory.rate) {
    taxCategories.value.push({
      name: newTaxCategory.name,
      rate: newTaxCategory.rate
    })
    newTaxCategory.name = ''
    newTaxCategory.rate = 0
  }
}

const removeTaxCategory = (index) => {
  taxCategories.value.splice(index, 1)
}

const editTaxCategory = (index) => {
  // Edit functionality
}

const saveSettings = async () => {
  saving.value = true

  try {
    const settings = {
      general: generalSettings,
      notifications: notificationSettings,
      payment: paymentSettings,
      system: systemSettings
    }

    // Simulate API call
    await new Promise(resolve => setTimeout(resolve, 2000))

    // Here you would make actual API call
    // await $fetch('/api/settings', { method: 'POST', body: settings })

    // Show success message (you might need to import a toast library)
    console.log('সেটিংস সফলভাবে সেভ হয়েছে!')

  } catch (error) {
    console.error('সেটিংস সেভ করতে সমস্যা হয়েছে!', error)
  } finally {
    saving.value = false
  }
}
</script>

