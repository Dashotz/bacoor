<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transfer of Ownership Application - City Government of Bacoor</title>
    @vite(['resources/css/pages/transfer-apply.css', 'resources/js/pages/transfer-apply.js', 'resources/js/core/jwt-auth.js'])
    
    <script>
        // Pass session data to JavaScript
        window.sessionData = {
            jwt_token: '{{ session('jwt_token') }}',
            jwt_user: @json(session('jwt_user'))
        };
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="color-scheme" content="light" />
    <meta name="theme-color" content="#0a3b7a" />
</head>
<body>
    <div class="transfer-apply-container">
        <!-- Header -->
        <header class="apply-header">
            <div class="header-content">
                <div class="logo-section">
                    <img src="/images/bacoor-logo.png" alt="Bacoor City Seal" class="city-logo" />
                    <span class="egov-text">BACOOR CITY EGOV™</span>
                </div>
                
                <!-- Progress Bar -->
                <div class="progress-container">
                    <div class="progress-bar">
                        <div class="progress-step active">
                            <span class="step-number">01</span>
                            <span class="step-label">Applicant info</span>
                        </div>
                        <div class="progress-line"></div>
                        <div class="progress-step">
                            <span class="step-number">02</span>
                            <span class="step-label">Required Document</span>
                        </div>
                        <div class="progress-line"></div>
                        <div class="progress-step">
                            <span class="step-number">03</span>
                            <span class="step-label">Payment info Lorem Ipsum is simply</span>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="apply-main">
            <div class="apply-card">
                <form id="transfer-apply-form" method="POST" action="{{ route('transfer-apply.step1.submit') }}">
                    @csrf
                    
                    <!-- Step 1: Basic Info -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-number">1</div>
                            <h2 class="section-title">Basic info</h2>
                        </div>
                        
                        <p class="section-description">
                            By registering, you confirm that the information provided is accurate and complete. 
                            You agree to verify your account via email and are responsible for maintaining the 
                            confidentiality of your credentials. Multiple or fraudulent accounts are prohibited.
                        </p>
                        
                        <p class="required-note">*All fields required unless noted.</p>

                        <!-- Account Details -->
                        <div class="form-subsection">
                            <h3 class="subsection-title">Account Details</h3>
                            
                            <div class="form-field">
                                <label for="email" class="field-label">* Verified Email Address</label>
                                <input type="email" id="email" name="email" class="form-input" value="{{ old('email', 'hsoscano@gmail.com') }}" required readonly />
                            </div>
                            
                            <div class="form-field">
                                <label for="password" class="field-label">* Password</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password" name="password" class="form-input" required />
                                    <button type="button" class="password-toggle" data-target="password">
                                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                            <line x1="1" y1="1" x2="23" y2="23"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            
                            <div class="form-field">
                                <label for="password_confirmation" class="field-label">* Confirm Password</label>
                                <div class="password-input-wrapper">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required />
                                    <button type="button" class="password-toggle" data-target="password_confirmation">
                                        <svg class="eye-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path>
                                            <circle cx="12" cy="12" r="3"></circle>
                                        </svg>
                                        <svg class="eye-slash-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" style="display: none;">
                                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path>
                                            <line x1="1" y1="1" x2="23" y2="23"></line>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Application Info -->
                        <div class="form-subsection">
                            <h3 class="subsection-title">Application Info</h3>
                            
                            <div class="form-row">
                                <div class="form-field">
                                    <label for="first_name" class="field-label">* First Name</label>
                                    <input type="text" id="first_name" name="first_name" class="form-input" value="{{ old('first_name') }}" required />
                                </div>
                                
                                <div class="form-field">
                                    <label for="middle_name" class="field-label">Middle Name</label>
                                    <input type="text" id="middle_name" name="middle_name" class="form-input" value="{{ old('middle_name') }}" />
                                </div>
                                
                                <div class="form-field">
                                    <label for="last_name" class="field-label">* Last Name</label>
                                    <input type="text" id="last_name" name="last_name" class="form-input" value="{{ old('last_name') }}" required />
                                </div>
                                
                                <div class="form-field">
                                    <label for="suffix" class="field-label">Suffix</label>
                                    <select id="suffix" name="suffix" class="form-select">
                                        <option value="">Select suffix</option>
                                        <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                        <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                        <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                                        <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                                        <option value="IV" {{ old('suffix') == 'IV' ? 'selected' : '' }}>IV</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-field">
                                <label class="field-label">What's your gender?</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} />
                                        <span class="radio-label">Female</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} />
                                        <span class="radio-label">Male</span>
                                    </label>
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="civil_status" class="field-label">* Civil Status</label>
                                <select id="civil_status" name="civil_status" class="form-select" required>
                                    <option value="">Select civil status</option>
                                    <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                    <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                    <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                    <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label for="birth_date" class="field-label">* Birth date</label>
                                <div class="date-input-wrapper">
                                    <input type="date" id="birth_date" name="birth_date" class="form-input" value="{{ old('birth_date') }}" required />
                                    <svg class="calendar-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </div>
                            </div>

                            <div class="form-field">
                                <label for="birthplace" class="field-label">* Birthplace</label>
                                <input type="text" id="birthplace" name="birthplace" class="form-input" value="{{ old('birthplace') }}" required />
                            </div>

                            <div class="form-field">
                                <label for="citizenship" class="field-label">* Citizenship</label>
                                <select id="citizenship" name="citizenship" class="form-select" required>
                                    <option value="">Select citizenship</option>
                                    <option value="Filipino" {{ old('citizenship') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                    <option value="Dual Citizen" {{ old('citizenship') == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                    <option value="Foreigner" {{ old('citizenship') == 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                                </select>
                            </div>

                            <div class="form-field">
                                <label for="contact_number" class="field-label">* Contact Number</label>
                                <input type="tel" id="contact_number" name="contact_number" class="form-input" value="{{ old('contact_number') }}" placeholder="09XX XXX XXXX" required />
                            </div>

                            <div class="form-field">
                                <label class="field-label">Account Ownership</label>
                                <div class="radio-group">
                                    <label class="radio-option">
                                        <input type="radio" name="account_type" value="individual" {{ old('account_type', 'individual') == 'individual' ? 'checked' : '' }} />
                                        <span class="radio-label">Individual</span>
                                    </label>
                                    <label class="radio-option">
                                        <input type="radio" name="account_type" value="business" {{ old('account_type') == 'business' ? 'checked' : '' }} />
                                        <span class="radio-label">Business</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Application Photo -->
                        <div class="form-subsection">
                            <h3 class="subsection-title">Application Photo</h3>
                            <div class="photo-upload-area" id="photo-upload-area">
                                <div class="upload-content">
                                    <svg class="upload-icon" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                        <polyline points="7,10 12,15 17,10"></polyline>
                                        <line x1="12" y1="15" x2="12" y2="3"></line>
                                    </svg>
                                    <p class="upload-text">Click here to upload or drop media here</p>
                                </div>
                                <input type="file" id="application_photo" name="application_photo" class="file-input" accept="image/*" />
                            </div>
                        </div>
                    </div>

                    <!-- Step 2: Terms and Regulation -->
                    <div class="form-section">
                        <div class="section-header">
                            <div class="section-number">2</div>
                            <h2 class="section-title">Terms and Regulation</h2>
                        </div>
                        
                        <div class="terms-content">
                            <p>
                                I consent to the processing, profiling, and disclosure of my personal data under the 
                                Data Privacy Act of 2012, including customer, account, and transaction information 
                                held by the City Government. Such data may be shared with requesting parties or 
                                used in legal proceedings, audits, investigations, or other official inquiries. 
                                This consent applies regardless of non-disclosure agreements and may extend to 
                                jurisdictions with less stringent data privacy laws.
                            </p>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary btn-next">Next</button>
                        <button type="button" class="btn btn-secondary btn-back">
                            <svg class="back-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M19 12H5M12 19l-7-7 7-7"></path>
                            </svg>
                            Back
                        </button>
                    </div>
                </form>
            </div>
        </main>

        <!-- Footer -->
        <footer class="apply-footer">
            <p>Copyright © 2025 All Rights Reserved</p>
        </footer>
    </div>
</body>
</html>
