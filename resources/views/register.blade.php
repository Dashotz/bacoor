@extends('layouts.app')

@section('title', 'Register - ' . config('app-config.app.name'))

@push('styles')
    @vite(['resources/css/register.css'])
@endpush

@push('meta')
    <meta name="login-route" content="{{ route('login.form') }}">
@endpush

@section('body-attributes', 'style="position: relative;"')

@section('content')
<div class="swirl-left" style="position: fixed; top: 0; left: 0; width: 20%; height: 100%; background: url('{{ asset('images/background-swirl1.png') }}') left top / 75% 100% no-repeat; background-attachment: fixed; z-index: -1;"></div>
<div class="swirl-right" style="position: fixed; top: 0; right: 0; width: 20%; height: 100%; background: url('{{ asset('images/background-swirl2.png') }}') right top / 75% 100% no-repeat; background-attachment: fixed; z-index: -1;"></div>
    <div class="container">
        <div class="form-container">
            <!-- Header with Logo and Progress Bar -->
            <div class="header">
                <div class="logo-section">
                    <img src="/images/bacoor-logo.png" alt="BACOOR CITY" class="bacoor-logo" />
                    <span class="app-title">BACOOR CITY EGOV™</span>
            </div>

                <!-- Progress Bar -->
                <div class="progress-bar">
                    <div class="progress-step active">
                        <div class="step-number">01</div>
                        <div class="step-title">Applicant info</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-number">02</div>
                        <div class="step-title">Required Document</div>
                    </div>
                    <div class="progress-step">
                        <div class="step-number">03</div>
                        <div class="step-title">Payment info</div>
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

                <!-- Account Details Section -->
                <div class="form-section">
                    <div class="section-header">Account Details</div>
                    
                    <div class="account-details-row">
                        <div class="form-field">
                            <label for="email"><span class="required">*</span>Email Address</label>
                            <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="example@gmail.com" required />
                            @error('email')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-field otp-field">
                            <label for="otp"><span class="required">*</span>Enter Verification Code</label>
                            <div class="otp-input-group">
                                <input type="text" id="otp" name="otp" placeholder="Enter verification code" required />
                                <button type="button" class="otp-button" id="send-otp-btn">Send OTP</button>
                            </div>
                            @error('otp')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-field">
                            <label for="password"><span class="required">*</span>Enter Password</label>
                            <input type="password" id="password" name="password" placeholder="Create password" required />
                            @error('password')<div class="error-message">{{ $message }}</div>@enderror
                            
                            <!-- Password Requirements -->
                            <div class="password-requirements">
                                <div class="requirement" data-requirement="length">
                                    <span class="requirement-icon"></span>
                                    <span class="requirement-text">8+ chars</span>
                                </div>
                                <div class="requirement" data-requirement="uppercase">
                                    <span class="requirement-icon"></span>
                                    <span class="requirement-text">A-Z</span>
                                </div>
                                <div class="requirement" data-requirement="lowercase">
                                    <span class="requirement-icon"></span>
                                    <span class="requirement-text">a-z</span>
                                </div>
                                <div class="requirement" data-requirement="number">
                                    <span class="requirement-icon"></span>
                                    <span class="requirement-text">0-9</span>
                                </div>
                                <div class="requirement" data-requirement="special">
                                    <span class="requirement-icon"></span>
                                    <span class="requirement-text">!@#$</span>
                                </div>
                            </div>
                        </div>

                        <div class="form-field">
                            <label for="password_confirmation"><span class="required">*</span>Confirm Password</label>
                            <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required />
                            @error('password_confirmation')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
                    </div>
                </div>

                <!-- Application Info Section -->
                <div class="form-section">
                    <div class="section-header">Application Info</div>

                <div class="form-grid">
                    <!-- Name Fields -->
                    <div class="form-field">
                            <label for="first_name"><span class="required">*</span>First Name</label>
                        <input type="text" id="first_name" name="first_name" value="{{ old('first_name') }}" placeholder="Juan" required />
                        @error('first_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="form-field">
                            <label for="middle_name">Middle Name</label>
                        <input type="text" id="middle_name" name="middle_name" value="{{ old('middle_name') }}" placeholder="Santos" />
                        @error('middle_name')<div class="error-message">{{ $message }}</div>@enderror
                    </div>
                    
                    <div class="form-field">
                            <label for="surname"><span class="required">*</span>Last Name</label>
                        <input type="text" id="surname" name="surname" value="{{ old('surname') }}" placeholder="Dela Cruz" required />
                        @error('surname')<div class="error-message">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-field">
                            <label for="suffix">Suffix</label>
                            <select id="suffix" name="suffix">
                                <option value="">Select</option>
                                <option value="Jr." {{ old('suffix') == 'Jr.' ? 'selected' : '' }}>Jr.</option>
                                <option value="Sr." {{ old('suffix') == 'Sr.' ? 'selected' : '' }}>Sr.</option>
                                <option value="II" {{ old('suffix') == 'II' ? 'selected' : '' }}>II</option>
                                <option value="III" {{ old('suffix') == 'III' ? 'selected' : '' }}>III</option>
                            </select>
                        @error('suffix')<div class="error-message">{{ $message }}</div>@enderror
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

                        <!-- Civil Status -->
                        <div class="form-field">
                            <label for="civil_status"><span class="required">*</span>Civil Status</label>
                            <select id="civil_status" name="civil_status" required>
                                <option value="">Select</option>
                                <option value="single" {{ old('civil_status') == 'single' ? 'selected' : '' }}>Single</option>
                                <option value="married" {{ old('civil_status') == 'married' ? 'selected' : '' }}>Married</option>
                                <option value="widowed" {{ old('civil_status') == 'widowed' ? 'selected' : '' }}>Widowed</option>
                                <option value="divorced" {{ old('civil_status') == 'divorced' ? 'selected' : '' }}>Divorced</option>
                            </select>
                            @error('civil_status')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <!-- Date of Birth -->
                        <div class="form-field">
                            <label for="birth_date"><span class="required">*</span>Birth date</label>
                            <input type="date" id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required />
                            @error('birth_date')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <div class="form-field">
                            <label for="birthplace"><span class="required">*</span>Birthplace</label>
                            <input type="text" id="birthplace" name="birthplace" value="{{ old('birthplace') }}" placeholder="City, Province" required />
                            @error('birthplace')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <!-- Citizenship -->
                        <div class="form-field">
                            <label for="citizenship"><span class="required">*</span>Citizenship</label>
                            <select id="citizenship" name="citizenship" required>
                                <option value="">Select</option>
                                <option value="Filipino" {{ old('citizenship') == 'Filipino' ? 'selected' : '' }}>Filipino</option>
                                <option value="Dual Citizen" {{ old('citizenship') == 'Dual Citizen' ? 'selected' : '' }}>Dual Citizen</option>
                                <option value="Foreigner" {{ old('citizenship') == 'Foreigner' ? 'selected' : '' }}>Foreigner</option>
                            </select>
                            @error('citizenship')<div class="error-message">{{ $message }}</div>@enderror
                        </div>

                        <!-- Contact Number -->
                        <div class="form-field">
                            <label for="contact_number"><span class="required">*</span>Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" value="{{ old('contact_number') }}" placeholder="0912 345 6789" pattern="[0-9\s]*" inputmode="numeric" maxlength="13" required />
                            @error('contact_number')<div class="error-message">{{ $message }}</div>@enderror
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

                        <!-- Application Photo -->
                        <div class="form-field photo-upload">
                            <label><span class="required">*</span>Application Photo</label>
                            <div class="file-upload-large">
                                <input type="file" id="application_photo" name="application_photo" accept=".jpg,.jpeg,.png" required />
                                <label for="application_photo" class="file-upload-label-large">
                                    <div class="upload-icon">↑</div>
                                    <div class="upload-text">Click here to upload or drop media here</div>
                                </label>
                            </div>
                            @error('application_photo')<div class="error-message">{{ $message }}</div>@enderror
                        </div>
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
                        <span class="back-arrow">←</span> Back
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
@endsection

@push('scripts')
    @vite(['resources/js/jwt-auth.js', 'resources/js/register.js'])
@endpush
