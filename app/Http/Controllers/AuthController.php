<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'webRegister']]);
    }

    /**
     * Register a new user.
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_date' => 'required|date|before:today',
            'gender' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,widowed,divorced',
            'birthplace' => 'required|string|max:255',
            'citizenship' => 'required|in:Filipino,Dual Citizen,Foreigner',
            'account_type' => 'required|in:individual,business',
            'contact_number' => 'required|string|regex:/^[0-9]{4} [0-9]{3} [0-9]{4}$/',
            'application_photo' => 'required|file|mimes:jpg,jpeg,png|max:2048',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Handle application photo upload
        $applicationPhotoPath = null;
        if ($request->hasFile('application_photo')) {
            $file = $request->file('application_photo');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $applicationPhotoPath = $file->storeAs('application_photos', $fileName, 'public');
        }

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'surname' => $request->surname,
            'suffix' => $request->suffix,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'civil_status' => $request->civil_status,
            'birthplace' => $request->birthplace,
            'citizenship' => $request->citizenship,
            'account_type' => $request->account_type,
            'contact_number' => $request->contact_number,
            'application_photo_path' => $applicationPhotoPath,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Register a new user via web form with OTP verification.
     */
    public function webRegister(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'surname' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:255',
            'birth_month' => 'required|string|max:2',
            'birth_day' => 'required|string|max:2',
            'birth_year' => 'required|string|max:4',
            'gender' => 'required|in:male,female',
            'account_type' => 'required|in:individual,business',
            'contact_number' => 'required|string|regex:/^[0-9]{4} [0-9]{3} [0-9]{4}$/',
            'email' => 'required|string|email|max:255|unique:users',
            'verification_code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
            'government_id_type' => 'required|string|max:255',
            'government_id_number' => 'required|string|max:255',
            'government_id_file' => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Verify OTP
        $verificationCode = $request->verification_code;
        $email = $request->email;
        
        if (!session()->has('registration_otp') || 
            !session()->has('registration_email') || 
            !session()->has('registration_otp_expires')) {
            return redirect()->back()
                ->withErrors(['verification_code' => 'Please request an OTP first.'])
                ->withInput();
        }

        if (session('registration_email') !== $email) {
            return redirect()->back()
                ->withErrors(['verification_code' => 'OTP was sent to a different email address.'])
                ->withInput();
        }

        if (session('registration_otp') !== $verificationCode) {
            return redirect()->back()
                ->withErrors(['verification_code' => 'Invalid verification code.'])
                ->withInput();
        }

        if (session('registration_otp_expires') < time()) {
            return redirect()->back()
                ->withErrors(['verification_code' => 'Verification code has expired. Please request a new one.'])
                ->withInput();
        }

        // Handle government ID file upload
        $governmentIdFilePath = null;
        if ($request->hasFile('government_id_file')) {
            $file = $request->file('government_id_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $governmentIdFilePath = $file->storeAs('government_ids', $fileName, 'public');
        }

        // Create birth date from separate fields
        $birthDate = $request->birth_year . '-' . $request->birth_month . '-' . $request->birth_day;

        $user = User::create([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'surname' => $request->surname,
            'suffix' => $request->suffix,
            'birth_date' => $birthDate,
            'gender' => $request->gender,
            'account_type' => $request->account_type,
            'contact_number' => $request->contact_number,
            'government_id_type' => $request->government_id_type,
            'government_id_number' => $request->government_id_number,
            'government_id_file_path' => $governmentIdFilePath,
            'email' => $request->email,
            'is_verified' => true, // Mark as verified since OTP was validated
            'password' => Hash::make($request->password),
        ]);

        // Clear OTP session data
        session()->forget(['registration_otp', 'registration_email', 'registration_otp_expires']);

        return redirect()->route('login.form')
            ->with('status', 'Registration successful! You can now log in with your email and password.');
    }

    /**
     * Web login for form submission.
     */
    public function webLogin(Request $request)
    {
        \Log::info('Web login attempt', ['email' => $request->email]);
        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            \Log::info('Validation failed', $validator->errors()->toArray());
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                \Log::info('Invalid credentials for email: ' . $request->email);
                return redirect()->back()
                    ->withErrors(['email' => 'Invalid credentials'])
                    ->withInput();
            }
        } catch (JWTException $e) {
            \Log::error('JWT Exception: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['email' => 'Could not create token'])
                ->withInput();
        }

        // Store JWT token and user data in session for web access
        $user = Auth::user();
        session([
            'jwt_token' => $token,
            'jwt_user' => $user->toArray()
        ]);

        \Log::info('Login successful', ['user_id' => $user->id, 'token' => substr($token, 0, 20) . '...']);

        return redirect()->route('dashboard')
            ->with('success', 'Login successful!');
    }

    /**
     * Get a JWT via given credentials.
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'g-recaptcha-response' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // Verify reCAPTCHA
        $recaptchaResponse = $request->input('g-recaptcha-response');
        if (!$this->verifyRecaptcha($recaptchaResponse)) {
            return response()->json([
                'message' => 'reCAPTCHA verification failed. Please try again.',
            ], 422);
        }

        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Invalid credentials',
                ], 401);
            }
        } catch (JWTException $e) {
            return response()->json([
                'message' => 'Could not create token',
            ], 500);
        }

        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => Auth::user(),
        ]);
    }

    /**
     * Get the authenticated User.
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Get user data for dashboard display.
     */
    public function getUserData()
    {
        try {
            $user = auth()->user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            return response()->json([
                'success' => true,
                'user' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch user data'
            ], 500);
        }
    }

    /**
     * Log the user out (Invalidate the token).
     */
    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Error logging out'], 500);
        }
    }

    /**
     * Refresh a token.
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::refresh();
            return response()->json([
                'message' => 'Token refreshed successfully',
                'token' => $token,
            ]);
        } catch (JWTException $e) {
            return response()->json(['message' => 'Error refreshing token'], 500);
        }
    }

    /**
     * Verify reCAPTCHA response
     */
    private function verifyRecaptcha($recaptchaResponse)
    {
        $secretKey = config('services.recaptcha.secret_key');
        
        if (empty($recaptchaResponse)) {
            return false;
        }

        $url = 'https://www.google.com/recaptcha/api/siteverify';
        $data = [
            'secret' => $secretKey,
            'response' => $recaptchaResponse,
            'remoteip' => request()->ip()
        ];

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($url, false, $context);
        $response = json_decode($result, true);

        return isset($response['success']) && $response['success'] === true;
    }
}
