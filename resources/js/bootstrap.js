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

// import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

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
        let unreadCount = parseInt(document.querySelector('.dot.text-success').textContent);
    document.querySelector('.dot.text-success').textContent = unreadCount + 1;

    // Create the new notification item dynamically
     // Assuming the event sends notification data
    // console.log(notification);

    var notificationHtml = `
        <div class="list-group list-group-flush my-n3">
            <div class="list-group-item bg-light">
                <div class="row align-items-center">
                    <div class="col-auto">
                        <span class="fe fe-box fe-24"></span>
                    </div>
                    <div class="col">
                        <small><strong>New user Registered</strong></small>
                        <div class="my-0 text-muted small">${data.message}</div>
                        <small class="badge badge-pill badge-light text-muted">Just now</small>
                    </div>
                </div>
            </div>
        </div>
    `;

    // Append the new notification to the notification list
    var notificationsList = document.querySelector('.list-group');
    notificationsList.insertAdjacentHTML('afterbegin', notificationHtml);


    });
