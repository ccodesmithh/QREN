@extends('layouts.dashboard.index')
@section('content')


<nav x-data="{ open: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto text-gray-800" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Right Side -->
            <div class="hidden sm:flex sm:items-center sm:ml-6">
                @auth
                    <!-- Notification Bell -->
                    <div class="relative mr-4" x-data="{ open: false, notifications: [], unreadCount: 0 }" x-init="
                        fetch('/guru/notifications/unread')
                            .then(res => res.json())
                            .then(data => {
                                notifications = data.notifications;
                                unreadCount = data.unreadCount;
                            });

                        window.Echo.private('guru.{{ auth()->id() }}')
                            .listen('NewAttendanceNotification', e => {
                                notifications.unshift({
                                    id: Date.now(),
                                    message: `New attendance recorded for student: ${e.student_name} at ${e.scanned_at}`,
                                    read: false
                                });
                                unreadCount++;
                            })
                            .listen('GeolocationUpdateNotification', e => {
                                notifications.unshift({
                                    id: Date.now(),
                                    message: `Geolocation updated for subject: ${e.ajar_name} at ${e.updated_at}`,
                                    read: false
                                });
                                unreadCount++;
                            });
                    ">
                        <button @click="open = !open" class="relative focus:outline-none">
                            <svg class="h-6 w-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                            <span x-show="unreadCount > 0" x-text="unreadCount"
                                class="absolute top-0 right-0 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white bg-red-600 rounded-full transform translate-x-1/2 -translate-y-1/2"></span>
                        </button>

                        <div x-show="open" @click.away="open = false"
                            class="origin-top-right absolute right-0 mt-2 w-80 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50 overflow-auto max-h-96">
                            <div class="py-1">
                                <template x-for="notification in notifications" :key="notification.id">
                                    <div
                                        :class="{'bg-gray-100': !notification.read, 'bg-white': notification.read}"
                                        class="block px-4 py-2 text-sm text-gray-700 border-b border-gray-200 cursor-pointer hover:bg-gray-200"
                                        @click="notification.read = true; unreadCount = notifications.filter(n => !n.read).length;">
                                        <span x-text="notification.message"></span>
                                    </div>
                                </template>
                                <template x-if="notifications.length === 0">
                                    <div class="block px-4 py-2 text-sm text-gray-700">
                                        No notifications
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    <!-- Dropdown untuk user login -->
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 focus:outline-none">
                                <div>{{ Auth::user()->name ?? 'User' }}</div>
                                <div class="ml-1">
                                    <svg class="fill-current h-4 w-4" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 
                                            1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 
                                            0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            <!-- Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                @else
                    
                    <a href="{{ route('guru.login') }}" class="text-gray-600 hover:text-gray-800 px-3">
                        Login Guru
                    </a>
                    <a href="{{ route('siswa.login') }}" class="text-gray-600 hover:text-gray-800 px-3">
                        Login Siswa
                    </a>
                @endauth
            </div>
        </div>
    </div>
@endsection
</nav>
