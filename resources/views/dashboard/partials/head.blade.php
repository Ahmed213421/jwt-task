<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">
    <title>@yield('title')</title>

        <script>
        // Check theme mode before loading CSS to prevent flash
        (function() {
            var currentTheme = localStorage.getItem('mode') || 'light';
            var isDarkMode = currentTheme === 'dark';

            // Store theme info for use in CSS loading
            window.initialThemeMode = currentTheme;
            window.isDarkMode = isDarkMode;

            // Set theme classes immediately to prevent flash
            document.documentElement.classList.add(isDarkMode ? 'dark' : 'light');

            // Also set body class for immediate styling
            if (document.body) {
                document.body.classList.add(isDarkMode ? 'dark' : 'light');
            }

            // Force immediate background color application
            if (isDarkMode) {
                document.documentElement.style.backgroundColor = '#495057';
                document.documentElement.style.color = '#e9ecef';
                if (document.body) {
                    document.body.style.backgroundColor = '#495057';
                    document.body.style.color = '#e9ecef';
                }
            }

            // Show body immediately after theme is applied
            if (document.body) {
                document.body.classList.add('theme-ready');
            }

            // Also apply to document element
            document.documentElement.style.backgroundColor = isDarkMode ? '#495057' : '#f8f9fa';
            document.documentElement.style.color = isDarkMode ? '#e9ecef' : '#495057';

            // Hide loading screen and show content after a short delay
            setTimeout(function() {
                var loadingScreen = document.querySelector('.loading-screen');
                if (loadingScreen) {
                    loadingScreen.classList.add('hidden');
                }
                if (document.body) {
                    document.body.classList.add('theme-ready');
                }
            }, 500);
        })();
    </script>

                    <style>
                /* Prevent flash of unstyled content */
        html, body {
            background-color: #f8f9fa !important;
            color: #495057 !important;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        /* Dark theme immediate application */
        html.dark, html.dark body {
            background-color: #495057 !important;
            color: #e9ecef !important;
        }

        /* Ensure wrapper and main content follow theme */
        .wrapper {
            background-color: inherit !important;
            color: inherit !important;
        }
        .main-content {
            background-color: inherit !important;
            color: inherit !important;
        }

        /* Prevent black squares by ensuring all containers have proper background */
        .container-fluid,
        .row,
        .col-12 {
            background-color: inherit !important;
        }

        /* Navigation elements */
        .topnav,
        .sidebar,
        .navbar {
            background-color: inherit !important;
            color: inherit !important;
        }

                /* Completely hide everything until theme is ready */
        .theme-loading {
            display: none !important;
        }
        .theme-loaded {
            display: block !important;
        }

        /* Hide all content initially */
        body {
            display: none !important;
        }

        /* Show body when theme is ready */
        body.theme-ready {
            display: block !important;
        }

        /* Loading screen with correct theme */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 9999;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Overpass', sans-serif;
            font-size: 16px;
        }

        html.dark .loading-screen {
            background-color: #495057;
            color: #e9ecef;
        }

        html.light .loading-screen {
            background-color: #f8f9fa;
            color: #495057;
        }

        .loading-screen.hidden {
            display: none;
        }
    </style>

    @if (App::getLocale() == 'ar')
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/simplebar.css') }}">
        <!-- Fonts CSS -->
        <link
            href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <!-- Icons CSS -->
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/feather.css') }}">
        <!-- Date Range Picker CSS -->
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/daterangepicker.css') }}">
        <!-- App CSS -->
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/app-light.css') }}" id="lightTheme">
        <link rel="stylesheet" href="{{ asset('admin/rtl/css/app-dark.css') }}" id="darkTheme">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

    @else
        <!-- Simple bar CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/simplebar.css') }}">
        <!-- Fonts CSS -->
        <link
            href="https://fonts.googleapis.com/css2?family=Overpass:ital,wght@0,100;0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
            rel="stylesheet">
        <!-- Icons CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/feather.css') }}">
        <!-- Date Range Picker CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/daterangepicker.css') }}">
        <!-- App CSS -->
        <link rel="stylesheet" href="{{ asset('admin/css/app-light.css') }}" id="lightTheme">
        <link rel="stylesheet" href="{{ asset('admin/css/app-dark.css') }}" id="darkTheme">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
            integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg=="
            crossorigin="anonymous" referrerpolicy="no-referrer" />

    @endif

    <script>
        // Enable the correct theme CSS after page load
        (function() {
            var lightTheme = document.getElementById('lightTheme');
            var darkTheme = document.getElementById('darkTheme');
            var body = document.body;
            var modeSwitcher = document.getElementById('modeSwitcher');
            var modeIcon = modeSwitcher ? modeSwitcher.querySelector('i') : null;

            if (window.isDarkMode) {
                darkTheme.disabled = false;
                lightTheme.disabled = true;
                body.classList.remove('light');
                body.classList.add('dark');

                // Update mode switcher
                if (modeSwitcher) {
                    modeSwitcher.dataset.mode = 'dark';
                    if (modeIcon) {
                        modeIcon.className = 'fe fe-moon fe-16';
                    }
                }
            } else {
                lightTheme.disabled = false;
                darkTheme.disabled = true;
                body.classList.remove('dark');
                body.classList.add('light');

                // Update mode switcher
                if (modeSwitcher) {
                    modeSwitcher.dataset.mode = 'light';
                    if (modeIcon) {
                        modeIcon.className = 'fe fe-sun fe-16';
                    }
                }
            }
        })();
    </script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('css')

</head>

<body class="vertical theme-loading {{ app()->getLocale() == 'ar' ? 'rtl' : '' }} ">

<!-- Loading screen with correct theme -->
<div class="loading-screen">
    <div>Loading...</div>
</div>
