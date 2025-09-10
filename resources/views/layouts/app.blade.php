<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', config('app-config.app.name'))</title>
    
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <!-- External Stylesheets -->
    @vite(['resources/css/app.css'])
    @stack('styles')
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Meta Tags -->
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#0a3b7a" />
    
    <!-- External Scripts -->
    @stack('scripts')
    
    <!-- Page-specific meta tags -->
    @stack('meta')
</head>
<body @yield('body-attributes')>
    <!-- Notification Container -->
    <div id="notification-container" class="notification-container">
        @if (session('status'))
        <div class="notification success-notification" id="success-notification">
            <div class="notification-content">
                <div class="notification-icon">✓</div>
                <div class="notification-message">{{ session('status') }}</div>
                <button class="notification-close" onclick="closeNotification()">&times;</button>
            </div>
        </div>
        @endif
        
        @if (session('error'))
        <div class="notification error-notification" id="error-notification">
            <div class="notification-content">
                <div class="notification-icon">✗</div>
                <div class="notification-message">{{ session('error') }}</div>
                <button class="notification-close" onclick="closeNotification()">&times;</button>
            </div>
        </div>
        @endif
    </div>

    <!-- Main Content -->
    @yield('content')

    <!-- External JavaScript -->
    @vite(['resources/js/core/app.js'])
    @stack('scripts-bottom')
</body>
</html>
