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
<body style="background: linear-gradient(rgba(0, 0, 0, 0.4), rgba(0, 0, 0, 0.4)), url('{{ asset('images/back-register.png') }}'); background-size: 100% 40%; background-position: top center; background-repeat: no-repeat; background-attachment: fixed;">
    <div class="container">
        <div class="form-container">
            <!-- Back to Login Link -->
            <div class="back-to-home">
                <a href="{{ route('login.form') }}" class="back-link">← Back to log in</a>
            </div>

            <!-- Bacoor Logo -->
            <div class="logo-section">
                <img src="/images/bacoor-logo.png" alt="LUNGSOD NG BACOOR, LALAWIGAN NG CAVITE" class="bacoor-logo" />
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
                    You are responsible for the accuracy of the information you provide. Your email address will be used for verification and important communications. All information will be kept confidential and secure. Multiple or fraudulent accounts are strictly prohibited.
                </div>

                <div class="required-note">
                    <strong>*All fields required unless noted.</strong>
                </div>

                <div class="form-grid">
                    <!-- Name Fields -->
                    <div class="form-field">
                        <label for="first_name"><span class="required">*</span> First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" required />
                        @error('first_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="form-field">
                        <label for="middle_name">Middle name (as applicable)</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Santos" />
                        @error('middle_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="form-field">
                        <label for="surname"><span class="required">*</span> Last Name</label>
                        <input type="text" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Dela Cruz" required />
                        @error('surname')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Date of Birth -->
                    <div class="form-field">
                        <label>What's your date of birth?</label>
                        <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required />
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

                    <!-- Contact Details -->
                    <div class="form-field">
                        <label for="contact_number"><span class="required">*</span> Contact Number</label>
                        <input type="tel" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" placeholder="0912 345 6789" pattern="[0-9\s]*" inputmode="numeric" maxlength="13" required />
                        @error('contact_number')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label for="email"><span class="required">*</span> Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="you@example.com" required />
                        @error('email')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label for="otp"><span class="required">*</span> Verification Code</label>
                        <div class="otp-section">
                            <div class="otp-input">
                                <input type="text" id="otp" name="otp" placeholder="Enter verification code" required />
                            </div>
                            <button type="button" class="otp-button" id="send-otp-btn">Send OTP</button>
                        </div>
                        @error('otp')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Password Fields -->
                    <div class="form-field">
                        <label for="password"><span class="required">*</span> Password</label>
                        <input type="password" id="password" name="password" placeholder="Create a strong password" required />
                        <div class="password-requirements">
                            <div class="requirement" data-requirement="length">
                                <span class="requirement-icon"></span>
                                <span>At least 8 characters</span>
                            </div>
                            <div class="requirement" data-requirement="uppercase">
                                <span class="requirement-icon"></span>
                                <span>1 uppercase letter</span>
                            </div>
                            <div class="requirement" data-requirement="lowercase">
                                <span class="requirement-icon"></span>
                                <span>1 lowercase letter</span>
                            </div>
                            <div class="requirement" data-requirement="number">
                                <span class="requirement-icon"></span>
                                <span>1 number</span>
                            </div>
                            <div class="requirement" data-requirement="special">
                                <span class="requirement-icon"></span>
                                <span>1 special character</span>
                            </div>
                        </div>
                        @error('password')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label for="password_confirmation"><span class="required">*</span> Confirm Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Re-enter password" required />
                        @error('password_confirmation')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <!-- Government ID -->
                    <div class="form-field">
                        <label for="government_id_type"><span class="required">*</span> Government ID</label>
                        <select id="government_id_type" name="government_id_type" required>
                            <option value="">Select ID Type</option>
                            <option value="driver_license" {{ old('government_id_type') == 'driver_license' ? 'selected' : '' }}>Driver's License</option>
                            <option value="passport" {{ old('government_id_type') == 'passport' ? 'selected' : '' }}>Passport</option>
                            <option value="sss_id" {{ old('government_id_type') == 'sss_id' ? 'selected' : '' }}>SSS ID</option>
                            <option value="philhealth_id" {{ old('government_id_type') == 'philhealth_id' ? 'selected' : '' }}>PhilHealth ID</option>
                            <option value="postal_id" {{ old('government_id_type') == 'postal_id' ? 'selected' : '' }}>Postal ID</option>
                            <option value="voter_id" {{ old('government_id_type') == 'voter_id' ? 'selected' : '' }}>Voter's ID</option>
                            <option value="national_id" {{ old('government_id_type') == 'national_id' ? 'selected' : '' }}>National ID</option>
                        </select>
                        @error('government_id_type')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label for="government_id_number"><span class="required">*</span> ID Number</label>
                        <input type="text" id="government_id_number" name="government_id_number" value="{{ old('government_id_number') }}" placeholder="Enter ID number" required />
                        @error('government_id_number')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                        <label><span class="required">*</span> Upload ID</label>
                        <div class="file-upload">
                            <input type="file" id="government_id_file" name="government_id_file" accept=".jpg,.jpeg,.png,.pdf" required />
                            <label for="government_id_file" class="file-upload-label">
                                <span>Click to upload ID image/scan</span>
                            </label>
                        </div>
                        @error('government_id_file')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                </div>

                <!-- Section 2: Terms and Regulation -->
                <div class="section-title">
                    <div class="section-number inactive">2</div>
                    <span>Terms and Regulation</span>
                </div>

                <div class="terms-section">
                    <p>I consent to the processing, profiling, and disclosure of my Personal Data as defined under the Data Privacy Act of 2012, including customer, account, and transaction information held by the City Government of Bacoor. I understand that this information may be shared with requesting parties, used in legal proceedings, audits, investigations, or other official inquiries. This consent applies regardless of any non-disclosure agreements and may extend to jurisdictions with less stringent data privacy laws.</p>
                </div>

                <!-- Submit Button -->
                <div class="button-group">
                    <button type="submit" class="btn btn-primary">Submit</button>
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
