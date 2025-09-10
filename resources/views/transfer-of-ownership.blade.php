<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Transfer of Ownership - BACOOR CITY EGOV™</title>
    <link rel="stylesheet" href="{{ asset('css/transfer-of-ownership.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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

    <div class="transfer-container">
        <!-- Header -->
        <div class="transfer-header">
            <div class="header-left">
                <div class="logo-container">
                    <img src="/images/bacoor-logo.png" alt="Bacoor City Logo" class="city-logo">
                    <span class="city-title">BACOOR CITY EGOV™</span>
                </div>
            </div>
            <div class="header-right">
                <div class="progress-bar">
                    <div class="progress-step active">
                        <span class="step-number">01</span>
                        <span class="step-label">Applicant info</span>
                    </div>
                    <div class="progress-step">
                        <span class="step-number">02</span>
                        <span class="step-label">Required Document</span>
                    </div>
                    <div class="progress-step">
                        <span class="step-number">03</span>
                        <span class="step-label">Payment info</span>
                        <span class="step-description">Lorem Ipsum is simply</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="transfer-content">
            <form id="transfer-form" method="POST" action="{{ route('transfer-of-ownership.submit') }}">
                @csrf
                
                <!-- Basic Info Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-number">1</div>
                        <h2 class="section-title">Basic info</h2>
                    </div>
                    <p class="section-description">
                        By registering, the user confirms the accuracy of the information provided, agrees to verify their account via the email confirmation link, and is responsible for maintaining the confidentiality of their credentials. Multiple or fraudulent accounts are strictly prohibited.
                    </p>
                    <p class="required-note">*All fields required unless noted.</p>

                    <!-- Account Details -->
                    <div class="subsection">
                        <h3 class="subsection-title">Account Details</h3>
                        <div class="form-group">
                            <label for="email" class="form-label required">Verified Email Address</label>
                            <input type="email" id="email" name="email" class="form-input" value="{{ old('email', 'hsoscano@gmail.com') }}" required>
                        </div>
                        <div class="form-group">
                            <label for="password" class="form-label required">Password</label>
                            <input type="password" id="password" name="password" class="form-input" required>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation" class="form-label required">Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" required>
                        </div>
                    </div>

                    <!-- Application Info -->
                    <div class="subsection">
                        <h3 class="subsection-title">Application Info</h3>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name" class="form-label required">First Name</label>
                                <input type="text" id="first_name" name="first_name" class="form-input" value="{{ old('first_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="middle_name" class="form-label">Middle Name</label>
                                <input type="text" id="middle_name" name="middle_name" class="form-input" value="{{ old('middle_name') }}">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="last_name" class="form-label required">Last Name</label>
                                <input type="text" id="last_name" name="last_name" class="form-input" value="{{ old('last_name') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="suffix" class="form-label">Suffix</label>
                                <select id="suffix" name="suffix" class="form-select">
                                    <option value="">Select Suffix</option>
                                    <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                    <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                    <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                                    <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">What's your gender?</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }}>
                                    <span class="radio-custom"></span>
                                    Female
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }}>
                                    <span class="radio-custom"></span>
                                    Male
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="civil_status" class="form-label required">Civil Status</label>
                            <select id="civil_status" name="civil_status" class="form-select" required>
                                <option value="">Select Civil Status</option>
                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="birth_date" class="form-label required">Birth date</label>
                                <input type="date" id="birth_date" name="birth_date" class="form-input" value="{{ old('birth_date') }}" required>
                            </div>
                            <div class="form-group">
                                <label for="birthplace" class="form-label required">Birthplace</label>
                                <input type="text" id="birthplace" name="birthplace" class="form-input" value="{{ old('birthplace') }}" required>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="citizenship" class="form-label required">Citizenship</label>
                            <select id="citizenship" name="citizenship" class="form-select" required>
                                <option value="">Select Citizenship</option>
                                <option value="filipino" {{ old('citizenship') == 'filipino' ? 'selected' : '' }}>Filipino</option>
                                <option value="dual" {{ old('citizenship') == 'dual' ? 'selected' : '' }}>Dual Citizenship</option>
                                <option value="other" {{ old('citizenship') == 'other' ? 'selected' : '' }}>Other</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="contact_number" class="form-label required">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" class="form-input" value="{{ old('contact_number') }}" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Account Ownership:</label>
                            <div class="radio-group">
                                <label class="radio-label">
                                    <input type="radio" name="account_ownership" value="individual" {{ old('account_ownership') == 'individual' ? 'checked' : '' }}>
                                    <span class="radio-custom"></span>
                                    Individual
                                </label>
                                <label class="radio-label">
                                    <input type="radio" name="account_ownership" value="business" {{ old('account_ownership') == 'business' ? 'checked' : '' }}>
                                    <span class="radio-custom"></span>
                                    Business
                                </label>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="application_photo" class="form-label required">Application Photo</label>
                            <div class="file-upload-area" id="file-upload-area">
                                <div class="file-upload-content">
                                    <div class="upload-icon">↑</div>
                                    <p class="upload-text">Click here to upload or drop media here</p>
                                </div>
                                <input type="file" id="application_photo" name="application_photo" class="file-input" accept="image/*" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Terms and Regulation Section -->
                <div class="form-section">
                    <div class="section-header">
                        <div class="section-number">2</div>
                        <h2 class="section-title">Terms and Regulation</h2>
                    </div>
                    <p class="terms-description">
                        I consent to the processing, profiling, and disclosure of all Personal Data—as defined under the Data Privacy Act of 2012—including customer, account, and transaction information held by the City Government. Such data may be shared with requesting parties or used in legal proceedings, audits, investigations, or other official inquiries. This consent applies regardless of any non-disclosure agreements and may extend to jurisdictions with less stringent data privacy laws.
                    </p>
                </div>

                <!-- Navigation Buttons -->
                <div class="form-navigation">
                    <button type="button" class="btn btn-back" onclick="goBack()">
                        <span class="btn-icon">←</span>
                        Back
                    </button>
                    <button type="submit" class="btn btn-next">
                        Next
                    </button>
                </div>
            </form>
        </div>

        <!-- Footer -->
        <div class="transfer-footer">
            <p class="copyright">Copyright © 2025 All Rights Reserved</p>
        </div>
    </div>

    <script src="{{ asset('js/transfer-of-ownership.js') }}"></script>
</body>
</html>