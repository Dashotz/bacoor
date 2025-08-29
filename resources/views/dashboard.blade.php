<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Bacoor Dashboard</title>
    @vite(['resources/css/app.css', 'resources/css/dashboard.css', 'resources/js/app.js', 'resources/js/dashboard.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <meta name="theme-color" content="#0a3b7a" />
    <style>
        body{margin:0;font-family:Inter,system-ui,-apple-system,Segoe UI,Roboto,Arial,sans-serif;background:#f5f8fc}
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>window.user = @json(Auth::user());</script>
    <script>
        // Global function for extending session
        function extendSession() {
            if (window.dashboardApp && window.dashboardApp.extendSession) {
                window.dashboardApp.extendSession();
            }
        }
    </script>
</head>
<body>
    <div class="bacoor-dashboard">
        <header class="dash-header">
            <div class="dash-brand">
                <img src="/favicon.ico" alt="Bacoor Seal" class="brand-logo" />
                <div class="brand-text">
                    <span class="brand-top">City Government of</span>
                    <span class="brand-name">Bacoor</span>
                </div>
            </div>
            <nav class="dash-nav">
                <a href="#" class="nav-link active">Dashboard</a>
                <a href="#" class="nav-link">Services</a>
                <a href="#" class="nav-link">Programs</a>
                <a href="#" class="nav-link">Updates</a>
            </nav>
            <div class="dash-user" style="display:flex;align-items:center;gap:10px">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}" id="logout-form">
                    @csrf
                    <button type="submit" class="logout" id="logout-btn">Logout</button>
                </form>
            </div>
        </header>

        <main class="dash-main">
            @if(session('message'))
                <div class="alert alert-info" style="background: #dbeafe; color: #1e40af; padding: 12px 16px; border-radius: 10px; margin-bottom: 16px; border: 1px solid #93c5fd;">
                    {{ session('message') }}
                </div>
            @endif
            
            <section class="welcome">
                <h1>Welcome, {{ Auth::user()->name }}!</h1>
                <p>Your citizen portal gives you access to Bacoor services and updates.</p>
                <div class="session-info">
                    <small>Session expires in <span id="session-timer">3:00</span> minutes of inactivity</small>
                </div>
            </section>

            <section class="cards">
                <article class="card">
                    <h3>Citizen Services</h3>
                    <p>Request certificates, permits, and more online.</p>
                    <a href="#" class="btn">Explore Services</a>
                </article>
                <article class="card">
                    <h3>Programs</h3>
                    <p>Learn about health, education, and livelihood programs.</p>
                    <a href="#" class="btn">View Programs</a>
                </article>
                <article class="card">
                    <h3>City Updates</h3>
                    <p>Read the latest advisories and announcements.</p>
                    <a href="#" class="btn">See Updates</a>
                </article>
            </section>
        </main>

        <footer class="dash-footer">
            <p>© <span id="year"></span> City Government of Bacoor</p>
        </footer>
    </div>
</body>
</html>

