<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Register - City Government of Bacoor</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="login-route" content="{{ route('login.form') }}">
    @vite(['resources/css/app.css', 'resources/css/register.css', 'resources/js/app.js', 'resources/js/jwt-auth.js', 'resources/js/register.js'])
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Background Images -->
    <div class="page-background">
        <div class="background-left">
            <img src="/images/background-swirl1.png" alt="Background Swirl 1" class="background-image">
        </div>
        <div class="background-right">
            <img src="/images/background-swirl2.png" alt="Background Swirl 2" class="background-image">
        </div>
    </div>
    
    <div class="container">
        <div class="form-container">
            <!-- Back to Home Link -->
            <div class="back-to-home">
                <a href="/" class="back-link">
                    <span class="back-arrow">←</span>
                    <span class="back-text">Back to home</span>
                </a>
            </div>
            <!-- Header with Logo and Progress Bar -->
            <div class="header">
                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="progress-step">
                    </div>
                    <div class="progress-step center">
                        <div class="logo-container">
                            <img src="/images/bacoor-logo.png" alt="LUNGSOD NG BACOOR" class="bacoor-logo" />
                        </div>
                    </div>
                    <div class="progress-step">
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

                <!-- Form Fields in 4-column layout for names -->
                <div class="form-grid-names">
                    <!-- First Name -->
                    <div class="form-field">
                        <label for="first_name"><span class="required">*</span>First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Enter first name" required />
                        @error('first_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Middle Name -->
                    <div class="form-field">
                        <label for="middle_name">Middle name</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Enter middle name" />
                        @error('middle_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Last Name -->
                    <div class="form-field">
                        <label for="surname"><span class="required">*</span>Last Name</label>
                        <input type="text" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Enter last name" required />
                        @error('surname')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <!-- Suffix -->
                    <div class="form-field">
                        <label for="suffix">Suffix</label>
                        <input type="text" id="suffix" name="suffix" value="{{ old('suffix') }}" placeholder="Jr., Sr., III, etc." />
                        @error('suffix')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Date of Birth Section -->
                <div class="form-field date-section">
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

                <!-- Gender and Account Ownership Section -->
                <div class="form-row">
                    <!-- Gender Section -->
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

                    <!-- Account Ownership Section -->
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
                </div>

                <!-- Contact, Email and OTP Verification Row -->
                <div class="form-row-four">
                    <!-- Contact Information -->
                    <div class="form-field">
                        <label for="contact_number"><span class="required">*</span>Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" placeholder="0912 345 6789" maxlength="13" pattern="[0-9]{4} [0-9]{3} [0-9]{4}" title="Please enter contact number in format: 0912 345 6789" required />
                        @error('contact_number')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Email Address -->
                    <div class="form-field">
                        <label for="email"><span class="required">*</span>Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address" required />
                        @error('email')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Verification Code Input -->
                    <div class="form-field">
                        <label for="verification_code"><span class="required">*</span>Verification Code</label>
                        <input type="text" id="verification_code" name="verification_code" placeholder="Enter verification code" maxlength="6" required />
                        @error('verification_code')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Send OTP Button -->
                    <div class="form-field">
                        <label>&nbsp;</label>
                        <button type="button" id="sendOtpBtn" class="btn btn-outline full-width-btn">Send OTP</button>
                        <div class="otp-timer" id="otpTimer"></div>
                    </div>
                </div>

                <!-- Password and Confirm Password Row -->
                <div class="form-row">
                    <!-- Password Section -->
                    <div class="form-field">
                        <label for="password"><span class="required">*</span>Password</label>
                        <input type="password" id="password" name="password" placeholder="Enter password" required />
                        @error('password')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Confirm Password Section -->
                    <div class="form-field">
                        <label for="password_confirmation"><span class="required">*</span>Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required />
                        @error('password_confirmation')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Password Requirements - Desktop -->
                <div class="password-requirements desktop-only" id="passwordRequirements">
                    <div class="requirements-grid">
                        <div class="requirement" id="req-length">
                            <span class="check"></span> At least 8 characters
                        </div>
                        <div class="requirement" id="req-uppercase">
                            <span class="check"></span> One uppercase letter
                        </div>
                        <div class="requirement" id="req-lowercase">
                            <span class="check"></span> One lowercase letter
                        </div>
                        <div class="requirement" id="req-number">
                            <span class="check"></span> One number
                        </div>
                        <div class="requirement" id="req-special">
                            <span class="check"></span> One special character
                        </div>
                    </div>
                </div>

                <!-- Password Requirements - Mobile -->
                <div class="password-requirements-mobile mobile-only" id="passwordRequirementsMobile">
                    <div class="mobile-requirements-grid">
                        <div class="mobile-requirement" id="mobile-req-length">
                            <span class="mobile-check"></span> 8+ chars
                        </div>
                        <div class="mobile-requirement" id="mobile-req-uppercase">
                            <span class="mobile-check"></span> A-Z
                        </div>
                        <div class="mobile-requirement" id="mobile-req-lowercase">
                            <span class="mobile-check"></span> a-z
                        </div>
                        <div class="mobile-requirement" id="mobile-req-number">
                            <span class="mobile-check"></span> 0-9
                        </div>
                        <div class="mobile-requirement" id="mobile-req-special">
                            <span class="mobile-check"></span> !@#
                        </div>
                    </div>
                </div>

                <!-- Government ID Section -->
                <div class="form-field government-id-section">
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
                        <div class="file-upload-container">
                            <input type="file" id="government_id_file" name="government_id_file" accept="image/*,.pdf" />
                            <label for="government_id_file" class="file-upload-label">
                                <span class="upload-text">Upload ID</span>
                            </label>
                        </div>
                    </div>
                    @error('government_id_type')<div class="error-message">{{ $message }}</div>@enderror
                    @error('government_id_number')<div class="error-message">{{ $message }}</div>@enderror
                    @error('government_id_file')<div class="error-message">{{ $message }}</div>@enderror
                </div>

                <!-- Section 2: Terms and Regulation -->
                <div class="section-title">
                    <div class="section-number">2</div>
                    <span>Terms and Regulation</span>
                </div>

                <div class="terms-section">
                    <p>I consent to the processing, profiling, and disclosure of my Personal Data as defined under the Data Privacy Act of 2012, including customer, account, and transaction information held by the City Government of Bacoor. I understand that this information may be shared with requesting parties, used in legal proceedings, audits, investigations, or other official inquiries. This consent applies regardless of any non-disclosure agreements and may extend to jurisdictions with less stringent data privacy laws.</p>
                </div>

                <!-- Submit Button -->
                <div class="submit-button-container">
                    <button type="submit" class="btn btn-submit">Submit</button>
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

    <!-- OTP Success Modal -->
    <div id="otpSuccessModal" class="modal">
        <div class="modal-content">
            <div class="success-icon">✓</div>
            <h3>OTP Sent Successfully!</h3>
            <p>OTP sent successfully to your email!</p>
            <div class="modal-buttons">
                <button type="button" class="btn btn-primary" id="closeOtpModal">OK</button>
            </div>
        </div>
    </div>
</body>
</html>