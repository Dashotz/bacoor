<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Application Status - City Government of Bacoor</title>
    @vite(['resources/css/app.css', 'resources/css/home.css', 'resources/js/app.js', 'resources/js/home.js', 'resources/js/jwt-auth.js', 'resources/js/application-status.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#0a3b7a" />
</head>
<body>
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
    </div>

    <div class="login-container">
        <!-- Back to Home Link -->
        <div class="back-to-home">
            <a href="/" class="back-link">
                <span class="back-arrow">←</span>
                <span class="back-text">Back to home</span>
            </a>
        </div>

        <!-- Left Panel - Dark Blue -->
        <div class="login-left-panel">
            <div class="placeholder-content">
                <h2>INSERT IMAGES</h2>
                <p>(BRB)</p>
            </div>
        </div>

        <!-- Right Panel - White with Form -->
        <div class="login-right-panel">
            <div class="login-content">
                <!-- Header -->
                <div class="login-header">
                    <h3 class="welcome-text">WELCOME TO THE</h3>
                    <h1 class="city-name">CITY OF <span class="bacoor-highlight">BACOOR</span></h1>
                    
                    <!-- Bacoor Logo/Seal -->
                    <div class="bacoor-seal">
                        <img src="/images/bacoor-logo.png" alt="Bacoor Seal" class="seal-image" />
                    </div>
                </div>

                @if(isset($status))
                    <!-- Status Results -->
                    <div class="login-form-container">
                        <h2 class="signin-title">Application Status</h2>
                        
                        <div class="status-card">
                            <div class="status-header">
                                <h3>Application #{{ $status['application_id'] }}</h3>
                                <span class="status-badge status-{{ strtolower(str_replace(' ', '-', $status['status'])) }}">
                                    {{ $status['status'] }}
                                </span>
                            </div>
                            
                            <div class="status-details">
                                <div class="detail-row">
                                    <span class="detail-label">Property Address:</span>
                                    <span class="detail-value">{{ $status['property_address'] }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Owner Name:</span>
                                    <span class="detail-value">{{ $status['owner_name'] }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">TCT Number:</span>
                                    <span class="detail-value">{{ $status['tct_number'] }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Tax Declaration Number:</span>
                                    <span class="detail-value">{{ $status['tax_declaration_number'] }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Submitted Date:</span>
                                    <span class="detail-value">{{ date('F j, Y', strtotime($status['submitted_date'])) }}</span>
                                </div>
                                <div class="detail-row">
                                    <span class="detail-label">Last Updated:</span>
                                    <span class="detail-value">{{ date('F j, Y', strtotime($status['last_updated'])) }}</span>
                                </div>
                            </div>

                            <div class="status-remarks">
                                <h4>Remarks</h4>
                                <p>{{ $status['remarks'] }}</p>
                            </div>

                            @if(isset($status['next_steps']) && count($status['next_steps']) > 0)
                            <div class="next-steps">
                                <h4>Next Steps</h4>
                                <ul>
                                    @foreach($status['next_steps'] as $step)
                                    <li>{{ $step }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>

                        <div class="action-buttons">
                            <a href="/application-status" class="continue-btn">Check Another Application</a>
                            <a href="/" class="continue-btn secondary">Back to Home</a>
                        </div>
                    </div>
                @else
                    <!-- Status Form -->
                    <div class="login-form-container">
                        <h2 class="signin-title">Check Application Status</h2>
                        
                        <form id="status-form" method="POST" action="{{ route('application-status.verify') }}">
                            @csrf
                            
                            @if ($errors->has('general'))
                            <div class="form-field" role="alert">
                                <div class="error-message">{{ $errors->first('general') }}</div>
                            </div>
                            @endif

                            <div class="form-field">
                                <label for="tct_number">Transfer Certificate of Title (TCT) Number</label>
                                <input type="text" id="tct_number" name="tct_number" value="{{ old('tct_number') }}" placeholder="Enter TCT Number" />
                            </div>

                            <div class="form-field">
                                <label for="tax_declaration_number">Tax Declaration Number(s) of the Building(s)</label>
                                <input type="text" id="tax_declaration_number" name="tax_declaration_number" value="{{ old('tax_declaration_number') }}" placeholder="Enter Tax Declaration Number" />
                            </div>

                            <div class="form-field">
                                <label for="owner_number">Owner's Number</label>
                                <input type="text" id="owner_number" name="owner_number" value="{{ old('owner_number') }}" placeholder="Enter Owner's Number" />
                            </div>

                            <div class="form-actions">
                                <label class="remember">
                                    <input type="checkbox" id="terms_accepted" name="terms_accepted" value="1" {{ old('terms_accepted') ? 'checked' : '' }} required />
                                    <span>I agree to all the <a href="#" class="terms-link">Terms</a> and <a href="#" class="privacy-link">Privacy policy</a></span>
                                </label>
                            </div>

                            @if ($errors->has('terms_accepted'))
                            <div class="form-field" role="alert">
                                <div class="error-message">{{ $errors->first('terms_accepted') }}</div>
                            </div>
                            @endif

                            <button type="submit" class="continue-btn">VERIFY</button>
                        </form>

                        <!-- Additional Links -->
                        <div class="additional-links">
                            <p class="signup-link">Need to login? <a href="/login" class="link-bold">SIGN IN HERE</a></p>
                            <a href="/register" class="status-link">Create New Account</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>
</html>