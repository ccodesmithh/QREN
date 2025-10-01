// Import dependencies
import './bootstrap';
import 'bootstrap';
import Alpine from 'alpinejs';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Alpine = Alpine;

// Setup Laravel Echo with Pusher (only if Pusher key is available)
window.Pusher = Pusher;

if (import.meta.env.VITE_PUSHER_APP_KEY && import.meta.env.VITE_PUSHER_APP_CLUSTER) {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: import.meta.env.VITE_PUSHER_APP_KEY,
        cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
        forceTLS: true,
        encrypted: true,
        authEndpoint: '/broadcasting/auth',
        auth: {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
        },
    });
} else {
    console.warn('Pusher configuration not found. Real-time features will be disabled.');
    window.Echo = null;
}

Alpine.start();

// Function to periodically update teacher location and refresh QR code
function startPeriodicLocationUpdate(ajarId, intervalMinutes) {
    if (!navigator.geolocation) {
        console.warn('Geolocation is not supported by this browser.');
        return;
    }

    // Create countdown display element
    let countdownElement = document.getElementById('location-countdown');
    if (!countdownElement) {
        countdownElement = document.createElement('div');
        countdownElement.id = 'location-countdown';
        countdownElement.style.cssText = 'position: fixed; top: 20px; left: 20px; background: rgba(0,0,0,0.9); color: white; padding: 15px 20px; border-radius: 8px; z-index: 1000; font-family: monospace; font-size: 16px; font-weight: bold; box-shadow: 0 4px 8px rgba(0,0,0,0.3); border: 2px solid #007bff; display: none;';
        document.body.appendChild(countdownElement);
    }

    const updateLocation = () => {
        // Start countdown before updating
        startCountdown(10, () => { // 10 second countdown for debugging
            navigator.geolocation.getCurrentPosition(
                (position) => {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;

                    fetch('/guru/qrcode/update-location', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({
                            ajar_id: ajarId,
                            teacher_lat: lat,
                            teacher_lng: lng,
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            console.log('Location updated successfully');
                            // Show notification for successful update
                            showLocationUpdateNotification('Geolocation updated successfully.');
                            // Trigger QR code refresh in Livewire component
                            if (window.Livewire) {
                                // Find the Livewire component and call refreshQrCodes method
                                const livewireComponents = window.Livewire.components;
                                for (const [id, component] of Object.entries(livewireComponents)) {
                                    if (component.fingerprint.name === 'guru-dashboard') {
                                        component.call('refreshQrCodes');
                                        break;
                                    }
                                }
                            }
                        } else {
                            console.error('Failed to update location:', data.error);
                            showLocationUpdateNotification('Failed to update geolocation: ' + data.error, true);
                        }
                    })
                    .catch(error => {
                        console.error('Error updating location:', error);
                        showLocationUpdateNotification('Error updating geolocation: ' + error.message, true);
                    });
                },
                (error) => {
                    console.error('Error getting location:', error);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0,
                }
            );
        });
    };

    // Initial update
    updateLocation();

    // Set interval for periodic updates
    setInterval(updateLocation, intervalMinutes * 60 * 1000);
}

// Function to start countdown before location update
// function startCountdown(seconds, callback) {
//     console.log(`Starting geolocation countdown: ${seconds} seconds`);

//     const countdownElement = document.getElementById('location-countdown');
//     if (!countdownElement) return;

//     countdownElement.style.display = 'block';
//     countdownElement.textContent = `📍 Updating location in ${seconds} seconds...`;
//     console.log(`Countdown display: Updating location in ${seconds} seconds...`);

//     let remaining = seconds;
//     const interval = setInterval(() => {
//         remaining--;
//         countdownElement.textContent = `📍 Updating location in ${remaining} seconds...`;
//         console.log(`Countdown: ${remaining} seconds remaining`);

//         if (remaining <= 0) {
//             clearInterval(interval);
//             countdownElement.style.display = 'none';
//             console.log('Countdown finished, updating location now');
//             callback();
//         }
//     }, 1000);
// }

// Function to show location update notifications
function showLocationUpdateNotification(message, isError = false) {
    const notification = document.createElement('div');
    notification.className = `alert alert-${isError ? 'danger' : 'success'} alert-dismissible fade show position-fixed`;
    notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
    notification.innerHTML = `
        <strong>${isError ? 'Error' : 'Success'}:</strong> ${message}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    `;

    document.body.appendChild(notification);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            $(notification).alert('close');
        }
    }, 5000);
}

// Notification center state and functions
window.notificationCenter = {
    notifications: [],
    unreadCount: 0,
    addNotification(notification) {
        this.notifications.unshift(notification);
        this.unreadCount++;
        this.updateBadge();
    },
    markAsRead(index) {
        if (!this.notifications[index].read) {
            this.notifications[index].read = true;
            this.unreadCount--;
            this.updateBadge();
        }
    },
    updateBadge() {
        const badge = document.getElementById('notification-badge');
        if (badge) {
            badge.textContent = this.unreadCount > 0 ? this.unreadCount : '';
            badge.style.display = this.unreadCount > 0 ? 'inline-block' : 'none';
        }
    }
};

if (window.Echo && window.Laravel && window.Laravel.userId) {
    try {
        const channel = window.Echo.private(`guru.${window.Laravel.userId}`);

        channel.listen('NewAttendanceNotification', (e) => {
            window.notificationCenter.addNotification({
                id: Date.now(),
                message: `New attendance recorded for student: ${e.student_name} at ${e.scanned_at}`,
                read: false
            });
        });

        channel.listen('GeolocationUpdateNotification', (e) => {
            window.notificationCenter.addNotification({
                id: Date.now(),
                message: `Geolocation updated for subject: ${e.ajar_name} at ${e.updated_at}`,
                read: false
            });
            // Refresh the QR code UI here if needed
            refreshQrCode(e.ajar_id);
        });
    } catch (error) {
        console.warn('Failed to subscribe to real-time notifications:', error);
    }
}

function refreshQrCode(ajarId) {
    fetch(`/guru/qrcode/svg?ajar_id=${ajarId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const qrContainer = document.getElementById(`qr-code-svg-${ajarId}`);
                if (qrContainer) {
                    qrContainer.innerHTML = data.qr_svg;
                }
            } else {
                console.warn('Failed to refresh QR code:', data.error);
            }
        })
        .catch(error => {
            console.error('Error refreshing QR code:', error);
        });
}

function startPeriodicLocationUpdateForAll(ajarIds, intervalMinutes) {
    if (!navigator.geolocation) {
        console.warn('Geolocation is not supported by this browser.');
        return;
    }

    ajarIds.forEach(ajarId => {
        startPeriodicLocationUpdate(ajarId, intervalMinutes);
    });
}

// Expose functions globally for usage in blade views
window.startPeriodicLocationUpdate = startPeriodicLocationUpdate;
window.startPeriodicLocationUpdateForAll = startPeriodicLocationUpdateForAll;
window.showLocationUpdateNotification = showLocationUpdateNotification;
