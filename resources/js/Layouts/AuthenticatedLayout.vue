<script setup>
import { ref } from 'vue';

const showingNavigationDropdown = ref(false);
const unreadNotifications = ref(3);
const unreadMessages = ref(5);

const sidebarItems = ref([
    { name: 'Dashboard', icon: 'chart-bar', href: '#' },
    { name: 'Products', icon: 'shopping-bag', href: '#' },
    { name: 'Orders', icon: 'shopping-cart', href: '#' },
    { name: 'Customers', icon: 'users', href: '#' },
    { name: 'Categories', icon: 'folder', href: '#' },
    { name: 'Analytics', icon: 'chart-pie', href: '#' },
    { name: 'Marketing', icon: 'speakerphone', href: '#' },
    { name: 'Settings', icon: 'cog', href: '#' },
]);


</script>

<template>
    <div class="min-h-screen bg-gray-100">
        <!-- Top Navigation Bar -->
        <nav class="bg-white shadow-md">
            <div class="mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex h-16 justify-between">
                    <!-- Logo and Brand -->
                    <div class="flex items-center">
                        <a href="#" class="flex items-center">
                            <img src="/images/Gadget_gears.png" class="h-10 w-auto" alt="Logo">
                            <span class="ml-2 text-xl font-bold text-gray-800">Admin Panel</span>
                        </a>
                    </div>

                    <!-- Right Navigation Items -->
                    <div class="flex items-center space-x-4">
                        <!-- Search -->
                        <div class="hidden md:block">
                            <div class="relative">
                                <input type="text" class="w-64 rounded-md border border-gray-300 pl-10 pr-4 py-2 focus:border-indigo-500 focus:ring-indigo-500" placeholder="Search...">
                                <div class="absolute left-3 top-2.5">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-md h-5 w-5 flex items-center justify-center">
                                {{ unreadNotifications }}
                            </span>
                        </button>

                        <!-- Messages -->
                        <button class="relative p-2 text-gray-600 hover:bg-gray-100 rounded-md">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                            </svg>
                            <span class="absolute top-0 right-0 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center">
                                {{ unreadMessages }}
                            </span>
                        </button>

                        <!-- Profile Dropdown -->

<!-- Replace the Profile Dropdown section with this: -->
<div class="relative">
    <button
        @click="showingNavigationDropdown = !showingNavigationDropdown"
        class="flex items-center space-x-2 rounded-full bg-gray-100 p-2"
    >
        <img
            :src="`https://ui-avatars.com/api/?name=${$page.props.auth.user.name}`"
            :alt="$page.props.auth.user.name"
            class="h-8 w-8 rounded-full"
        >
        <span class="text-sm font-medium text-gray-700">{{ $page.props.auth.user.name }}</span>
        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <!-- Dropdown Menu -->
    <div v-show="showingNavigationDropdown"
         class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5">
        <div class="py-1">
            <div class="px-4 py-2 border-b">
                <div class="text-base font-medium text-gray-800">
                    {{ $page.props.auth.user.name }}
                </div>
                <div class="text-sm font-medium text-gray-500">
                    {{ $page.props.auth.user.email }}
                </div>
            </div>
            <a :href="route('profile')" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
            <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Settings</a>
            <Link :href="route('logout')"
                  method="post"
                  as="button"
                  class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                Log Out
            </Link>
        </div>
    </div>
</div>




                    </div>
                </div>
            </div>
        </nav>

        <!-- Sidebar and Main Content -->
        <div class="flex">
            <!-- Sidebar -->
            <aside class="w-64 bg-white shadow-md h-screen fixed">
                <nav class="mt-5 px-2">
                    <div class="space-y-1">
                        <a v-for="item in sidebarItems"
                           :key="item.name"
                           :href="item.href"
                           class="group flex items-center px-2 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50">
                            <svg class="mr-3 h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            {{ item.name }}
                        </a>
                    </div>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="flex-1 ml-64 p-8">
                <header class="bg-white shadow-sm rounded-lg mb-6">
                    <div class="py-4 px-6">
                        <slot name="header" />
                    </div>
                </header>

                <div class="bg-white rounded-lg shadow-sm p-6">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>
