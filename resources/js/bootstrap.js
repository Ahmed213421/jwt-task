/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;
// Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;

    var pusher = new Pusher('400e613895e0965e5d41', {
      cluster: 'eu'
    });

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
    wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});

window.Echo.channel('channel')
    .listen('NewPostEvent', (event) => {
        console.log(event.message);

        // تحديث العداد
        let dotElement = document.querySelector('.dot.text-succes');
        if (dotElement) {
            let unreadCount = parseInt(dotElement.textContent) || 0;
            dotElement.textContent = unreadCount + 1;
        }

        // إنشاء إشعار جديد
        var notificationHtml = `
            <div class="list-group-item bg-light">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="fe fe-box fe-24"></span>
                    </div>
                    <div class="col">
                        <small><strong>New Post Added</strong></small>
                        <div class="my-0 text-muted small">${event.message}</div>
                        <small class="badge badge-pill badge-light text-muted">Just now</small>
                    </div>
                </div>
            </div>
        `;

        let notificationsList = document.querySelector('.list-group');
        if (notificationsList) {
            notificationsList.insertAdjacentHTML('afterbegin', notificationHtml);
        }
    });

