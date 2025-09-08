<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register - BACOOR CITY EGOV™</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="login-route" content="{{ route('login.form') }}">
    @vite(['resources/css/app.css', 'resources/css/register.css', 'resources/js/app.js', 'resources/js/jwt-auth.js', 'resources/js/register.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Header with Sign In Button -->
    <div class="page-header">
        <button class="sign-in-btn" onclick="window.location.href='{{ route('login.form') }}'">SIGN IN</button>
    </div>

    <div class="container">
        <div class="form-container">
            <!-- Header with Logo and Progress Bar -->
            <div class="header">
                <div class="logo-section">
                    <img src="/images/bacoor-logo.png" alt="BACOOR CITY" class="bacoor-logo" />
                </div>

                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="progress-step active">
                        <div class="step-number">1</div>
                        <div class="step-title">My Account</div>
                    </div>
                    <div class="progress-line"></div>
                    <div class="progress-step">
                        <div class="step-number">3</div>
                        <div class="step-title">View Sent Email</div>
                    </div>
                </div>
            </div>

            <form id="registerForm" method="POST" action="{{ route('register.submit') }}" enctype="multipart/form-data">
                @csrf
                
                @if (session('status'))
                <div class="success-message">
                    {{ session('status') }}
                </div>
                @endif

                @if ($errors->any())
                <div class="error-message" style="background: #fef2f2; padding: 12px 16px; border-radius: 8px; border: 1px solid #fecaca; margin-bottom: 20px;">
                    @if ($errors->has('general'))
                        {{ $errors->first('general') }}
                    @else
                        Please fix the errors below.
                    @endif
                </div>
                @endif

                <!-- Section 1: Basic Info -->
                <div class="section-title">
                    <div class="section-number">1</div>
                    <span>Basic info</span>
                </div>

                <div class="intro-text">
                    By registering, the user confirms the accuracy of the information provided, agrees to verify their account via the email confirmation link, and is responsible for maintaining the confidentiality of their credentials. Multiple or fraudulent accounts are strictly prohibited.
                </div>

                <div class="required-note">
                    <strong>*All fields required unless noted.</strong>
                </div>

                <!-- Form Fields in 2-column layout -->
                <div class="form-grid">
                    <!-- First Name -->
                    <div class="form-field">
                        <label for="first_name"><span class="required">*</span>First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required />
                        @error('first_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Middle Name -->
                    <div class="form-field">
                        <label for="middle_name">Middle name (as applicable)</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name" />
                        @error('middle_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Last Name -->
                    <div class="form-field">
                        <label for="surname"><span class="required">*</span>Last Name</label>
                        <input type="text" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Enter last name" required />
                        @error('surname')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="form-field">
                        <label>What's your date of birth?</label>
                        <div class="date-inputs">
                            <select id="birth_month" name="birth_month" required>
                                <option value="">Month</option>
                                <option value="01" {{ old('birth_month') == '01' ? 'selected' : '' }}>January</option>
                                <option value="02" {{ old('birth_month') == '02' ? 'selected' : '' }}>February</option>
                                <option value="03" {{ old('birth_month') == '03' ? 'selected' : '' }}>March</option>
                                <option value="04" {{ old('birth_month') == '04' ? 'selected' : '' }}>April</option>
                                <option value="05" {{ old('birth_month') == '05' ? 'selected' : '' }}>May</option>
                                <option value="06" {{ old('birth_month') == '06' ? 'selected' : '' }}>June</option>
                                <option value="07" {{ old('birth_month') == '07' ? 'selected' : '' }}>July</option>
                                <option value="08" {{ old('birth_month') == '08' ? 'selected' : '' }}>August</option>
                                <option value="09" {{ old('birth_month') == '09' ? 'selected' : '' }}>September</option>
                                <option value="10" {{ old('birth_month') == '10' ? 'selected' : '' }}>October</option>
                                <option value="11" {{ old('birth_month') == '11' ? 'selected' : '' }}>November</option>
                                <option value="12" {{ old('birth_month') == '12' ? 'selected' : '' }}>December</option>
                            </select>
                            <select id="birth_day" name="birth_day" required>
                                <option value="">Date</option>
                                @for($i = 1; $i <= 31; $i++)
                                    <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ old('birth_day') == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                            <select id="birth_year" name="birth_year" required>
                                <option value="">Year</option>
                                @for($i = date('Y') - 18; $i >= 1900; $i--)
                                    <option value="{{ $i }}" {{ old('birth_year') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        @error('birth_date')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Gender -->
                    <div class="form-field">
                        <label>What's your gender?</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="gender_female" name="gender" value="female" {{ old('gender') == 'female' ? 'checked' : '' }} required />
                                <label for="gender_female">Female</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="gender_male" name="gender" value="male" {{ old('gender') == 'male' ? 'checked' : '' }} required />
                                <label for="gender_male">Male</label>
                            </div>
                        </div>
                        @error('gender')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Account Ownership -->
                    <div class="form-field">
                        <label>Account Ownership:</label>
                        <div class="radio-group">
                            <div class="radio-item">
                                <input type="radio" id="account_individual" name="account_type" value="individual" {{ old('account_type', 'individual') == 'individual' ? 'checked' : '' }} required />
                                <label for="account_individual">Individual</label>
                            </div>
                            <div class="radio-item">
                                <input type="radio" id="account_business" name="account_type" value="business" {{ old('account_type') == 'business' ? 'checked' : '' }} required />
                                <label for="account_business">Business</label>
                            </div>
                        </div>
                        @error('account_type')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Contact Number -->
                    <div class="form-field">
                        <label for="contact_number"><span class="required">*</span>Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" placeholder="Enter contact number" required />
                        @error('contact_number')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Password -->
                    <div class="form-field">
                        <label for="password"><span class="required">*</span>Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter password" required />
                        @error('password')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="form-field">
                        <label for="password_confirmation"><span class="required">*</span>Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required />
                        @error('password_confirmation')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Government ID -->
                    <div class="form-field">
                        <label><span class="required">*</span>Government ID</label>
                        <div class="government-id-fields">
                            <select id="government_id_type" name="government_id_type" required>
                                <option value="">ID Type</option>
                                <option value="Driver's License" {{ old('government_id_type') == "Driver's License" ? 'selected' : '' }}>Driver's License</option>
                                <option value="Passport" {{ old('government_id_type') == 'Passport' ? 'selected' : '' }}>Passport</option>
                                <option value="SSS ID" {{ old('government_id_type') == 'SSS ID' ? 'selected' : '' }}>SSS ID</option>
                                <option value="PhilHealth ID" {{ old('government_id_type') == 'PhilHealth ID' ? 'selected' : '' }}>PhilHealth ID</option>
                                <option value="TIN ID" {{ old('government_id_type') == 'TIN ID' ? 'selected' : '' }}>TIN ID</option>
                                <option value="Postal ID" {{ old('government_id_type') == 'Postal ID' ? 'selected' : '' }}>Postal ID</option>
                                <option value="Voter's ID" {{ old('government_id_type') == "Voter's ID" ? 'selected' : '' }}>Voter's ID</option>
                                <option value="National ID" {{ old('government_id_type') == 'National ID' ? 'selected' : '' }}>National ID</option>
                                <option value="Other" {{ old('government_id_type') == 'Other' ? 'selected' : '' }}>Other</option>
                            </select>
                            <input type="text" id="government_id_number" name="government_id_number" value="{{ old('government_id_number') }}" placeholder="ID number" required />
                        </div>
                        @error('government_id_type')<div class="error-message">{{ $message }}</div>@enderror
                        @error('government_id_number')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Section 2: Terms and Regulation -->
                <div class="section-title">
                    <div class="section-number">2</div>
                    <span>Terms and Regulation</span>
                </div>

                <div class="terms-section">
                    <p>I consent to the processing, profiling, and disclosure of all Personal Data—as defined under the Data Privacy Act of 2012—including customer, account, and transaction information held by the City Government. Such data may be shared with requesting parties or used in legal proceedings, audits, investigations, or other official inquiries. This consent applies regardless of any non-disclosure agreements and may extend to jurisdictions with less stringent data privacy laws.</p>
                </div>

                <!-- Navigation Buttons -->
                <div class="button-group">
                    <button type="button" class="btn btn-secondary" id="backBtn">
                        BACK
                    </button>
                    <button type="submit" class="btn btn-primary">Next</button>
                </div>

                <div class="footer">
                    Copyright © 2025 All Rights Reserved
                </div>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="confirmationModal" class="modal">
        <div class="modal-content">
            <h3>Are you sure you want to submit?</h3>
            <div class="modal-buttons">
                <button type="button" class="btn btn-primary" id="confirmSubmit">Yes</button>
                <button type="button" class="btn btn-secondary" id="cancelSubmit">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    <div id="successModal" class="modal">
        <div class="modal-content">
            <div class="success-icon">✓</div>
            <h3>Registration Successful!</h3>
            <p>You are now registered, please proceed to the log in page.</p>
            <div class="modal-buttons">
                <button type="button" class="btn btn-primary" id="goToLogin">Go to Login</button>
            </div>
        </div>
    </div>
</body>
</html>