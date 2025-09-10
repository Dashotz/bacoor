@extends('layouts.app')

@section('title', 'Bacoor Dashboard')

@push('styles')
    @vite(['resources/css/pages/dashboard.css'])
@endpush

@push('meta')
    <!-- User data will be fetched securely via API -->
@endpush

@section('content')
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
                <a href="/dashboard" class="nav-link active">Dashboard</a>
                <a href="#" class="nav-link">Programs</a>
                <a href="#" class="nav-link">Updates</a>
            </nav>
            <div class="dash-user" style="display:flex;align-items:center;gap:10px">
                <span id="user-welcome">Welcome, <span id="user-name">Loading...</span></span>
                <form id="logout-form">
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
                <h1 id="welcome-title">Welcome, <span id="welcome-name">Loading...</span>!</h1>
                <p>Your citizen portal gives you access to Bacoor services and updates.</p>
                <div class="session-info">
                    <small>Session expires in <span id="session-timer">3:00</span> minutes of inactivity</small>
                </div>
            </section>

            <!-- User Data Table Section -->
            <section class="user-data-section">
                <h2>Your Registration Information</h2>
                <div class="table-container">
                    <table class="user-data-table" id="userDataTable">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody id="userDataBody">
                            <tr>
                                <td colspan="2" style="text-align: center; padding: 20px;">
                                    <div class="loading-spinner">Loading user data...</div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <!-- Assessor's Services Section -->
            <section class="assessor-services-section">
                <div class="section-header">
                    <h2>OFFICE OF THE <span class="highlight">ASSESSOR'S</span> SERVICES OFFERED</h2>
                    <p class="section-subtitle">Please Select One</p>
                </div>
                
                <div class="services-grid">
                    <!-- Service 1: Transfer Of Ownership -->
                    <div class="service-card highlighted">
                        <div class="service-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                            </svg>
                            <div class="tax-badge">TAX</div>
                        </div>
                        <h3 class="service-title">Transfer Of Ownership / Updating Of Tax Declaration</h3>
                        <button class="start-btn">Start Today</button>
                    </div>

                    <!-- Service 2: Subdivision / Consolidation -->
                    <div class="service-card highlighted">
                        <div class="service-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <circle cx="6" cy="6" r="2"></circle>
                                <circle cx="18" cy="6" r="2"></circle>
                                <circle cx="6" cy="18" r="2"></circle>
                                <circle cx="18" cy="18" r="2"></circle>
                                <line x1="8" y1="8" x2="10" y2="10"></line>
                                <line x1="14" y1="8" x2="16" y2="10"></line>
                                <line x1="8" y1="16" x2="10" y2="14"></line>
                                <line x1="14" y1="16" x2="16" y2="14"></line>
                            </svg>
                        </div>
                        <h3 class="service-title">Subdivision / Consolidation of Real Property</h3>
                        <button class="start-btn">Start Today</button>
                    </div>

                    <!-- Service 3: Reassessment of Real Property -->
                    <div class="service-card highlighted">
                        <div class="service-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor" stroke="currentColor" stroke-width="2">
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"></path>
                                <path d="M12 1a11 11 0 1 0 11 11A11 11 0 0 0 12 1z"></path>
                            </svg>
                        </div>
                        <h3 class="service-title">Reassessment of Real Property: Building & Other Improvement</h3>
                        <button class="start-btn">Start Today</button>
                    </div>

                    <!-- Service 4: New Assessment of Real Property -->
                    <div class="service-card highlighted">
                        <div class="service-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <path d="M12 15l-2 2 2 2"></path>
                            </svg>
                        </div>
                        <h3 class="service-title">New Assessment of Real Property: Land (FOR TITLED PROPERTY)</h3>
                        <button class="start-btn">Start Today</button>
                    </div>

                    <!-- Service 5: Appraisal and Assessment of Building -->
                    <div class="service-card highlighted">
                        <div class="service-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect>
                                <rect x="7" y="7" width="3" height="3"></rect>
                                <rect x="14" y="7" width="3" height="3"></rect>
                                <rect x="7" y="14" width="3" height="3"></rect>
                                <rect x="14" y="14" width="3" height="3"></rect>
                                <circle cx="12" cy="12" r="3"></circle>
                                <path d="M12 1v6m0 6v6m11-7h-6m-6 0H1"></path>
                            </svg>
                        </div>
                        <h3 class="service-title">Appraisal and Assessment of Building and Other Structures</h3>
                        <button class="start-btn">Start Today</button>
                    </div>

                    <!-- Service 6: Appraisal and Assessment of Machinery -->
                    <div class="service-card highlighted">
                        <div class="service-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                                <polyline points="9,22 9,12 15,12 15,22"></polyline>
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path>
                            </svg>
                        </div>
                        <h3 class="service-title">Appraisal and Assessment of Machinery</h3>
                        <button class="start-btn">Start Today</button>
                    </div>

                    <!-- Service 7: Certificates -->
                    <div class="service-card highlighted">
                        <div class="service-icon">
                            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                <polyline points="14,2 14,8 20,8"></polyline>
                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                <polyline points="10,9 9,9 8,9"></polyline>
                                <path d="M12 15l-2 2 2 2"></path>
                            </svg>
                        </div>
                        <h3 class="service-title">Certificates</h3>
                        <button class="start-btn">Start Today</button>
                    </div>
                </div>
            </section>

            <section class="cards">
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
@endsection

@push('scripts')
    @vite(['resources/js/pages/dashboard.js', 'resources/js/core/jwt-auth.js'])
    
    <script>
        // Pass session data to JavaScript
        window.sessionData = {
            jwt_token: '{{ session('jwt_token') }}',
            jwt_user: @json(session('jwt_user'))
        };
    </script>
@endpush
