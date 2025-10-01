<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Guru private channel for real-time notifications
Broadcast::channel('guru.{guruId}', function ($user, $guruId) {
    // Allow access if the authenticated user is a guru and the ID matches
    return auth('guru')->check() && auth('guru')->id() == $guruId;
});

// Siswa private channel for real-time notifications
Broadcast::channel('siswa.{siswaId}', function ($user, $siswaId) {
    // Allow access if the authenticated user is a siswa and the ID matches
    return auth('siswa')->check() && auth('siswa')->id() == $siswaId;
});

// Admin private channel for real-time notifications
Broadcast::channel('admin.{adminId}', function ($user, $adminId) {
    // Allow access if the authenticated user is an admin and the ID matches
    return auth('admin')->check() && auth('admin')->id() == $adminId;
});
