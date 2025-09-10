<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class TransferApplyController extends Controller
{
    /**
     * Show step 1 of the transfer application
     */
    public function showStep1()
    {
        return view('pages.transfer-apply.step1');
    }

    /**
     * Handle step 1 submission
     */
    public function submitStep1(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'first_name' => 'required|string|max:255',
            'middle_name' => 'nullable|string|max:255',
            'last_name' => 'required|string|max:255',
            'suffix' => 'nullable|string|max:10',
            'gender' => 'required|in:male,female',
            'civil_status' => 'required|in:single,married,widowed,divorced',
            'birth_date' => 'required|date|before:today',
            'birthplace' => 'required|string|max:255',
            'citizenship' => 'required|in:Filipino,Dual Citizen,Foreigner',
            'contact_number' => 'required|string|regex:/^09[0-9]{9}$/',
            'account_type' => 'required|in:individual,business',
            'application_photo' => 'required|file|mimes:jpg,jpeg,png|max:5120', // 5MB max
        ], [
            'email.unique' => 'This email address is already registered.',
            'password.confirmed' => 'Password confirmation does not match.',
            'contact_number.regex' => 'Please enter a valid 11-digit mobile number starting with 09.',
            'application_photo.required' => 'Application photo is required.',
            'application_photo.mimes' => 'Photo must be a JPG, JPEG, or PNG file.',
            'application_photo.max' => 'Photo size must be less than 5MB.',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }

        try {
            // Handle photo upload
            $photoPath = null;
            if ($request->hasFile('application_photo')) {
                $file = $request->file('application_photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $photoPath = $file->storeAs('application_photos', $fileName, 'public');
            }

            // Create user account
            $user = User::create([
                'first_name' => $request->first_name,
                'middle_name' => $request->middle_name,
                'surname' => $request->last_name,
                'suffix' => $request->suffix,
                'gender' => $request->gender,
                'civil_status' => $request->civil_status,
                'birth_date' => $request->birth_date,
                'birthplace' => $request->birthplace,
                'citizenship' => $request->citizenship,
                'contact_number' => $request->contact_number,
                'account_type' => $request->account_type,
                'application_photo_path' => $photoPath,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'is_verified' => false, // Will be verified via OTP
            ]);

            // Store application data in session for next steps
            session([
                'transfer_apply_data' => [
                    'user_id' => $user->id,
                    'step' => 1,
                    'completed_steps' => [1],
                    'data' => $request->except(['password', 'password_confirmation', 'application_photo'])
                ]
            ]);

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Step 1 completed successfully',
                    'redirect_url' => route('transfer-apply.step2')
                ]);
            }

            return redirect()->route('transfer-apply.step2')
                ->with('success', 'Step 1 completed successfully! Please proceed to step 2.');

        } catch (\Exception $e) {
            \Log::error('Transfer Apply Step 1 Error: ' . $e->getMessage());
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'An error occurred. Please try again.'
                ], 500);
            }
            
            return back()->withErrors(['general' => 'An error occurred. Please try again.'])->withInput();
        }
    }

    /**
     * Show step 2 of the transfer application
     */
    public function showStep2()
    {
        // Check if step 1 is completed
        $applyData = session('transfer_apply_data');
        if (!$applyData || !in_array(1, $applyData['completed_steps'])) {
            return redirect()->route('transfer-apply.step1')
                ->with('error', 'Please complete step 1 first.');
        }

        return view('pages.transfer-apply.step2');
    }

    /**
     * Handle step 2 submission
     */
    public function submitStep2(Request $request)
    {
        // Implementation for step 2 (Required Documents)
        // This would be similar to step 1 but for document uploads
        return response()->json(['message' => 'Step 2 not implemented yet']);
    }

    /**
     * Show step 3 of the transfer application
     */
    public function showStep3()
    {
        // Check if previous steps are completed
        $applyData = session('transfer_apply_data');
        if (!$applyData || !in_array(2, $applyData['completed_steps'])) {
            return redirect()->route('transfer-apply.step1')
                ->with('error', 'Please complete previous steps first.');
        }

        return view('pages.transfer-apply.step3');
    }

    /**
     * Handle step 3 submission
     */
    public function submitStep3(Request $request)
    {
        // Implementation for step 3 (Payment Info)
        // This would handle payment processing
        return response()->json(['message' => 'Step 3 not implemented yet']);
    }

    /**
     * Get application status
     */
    public function getApplicationStatus(Request $request)
    {
        $applyData = session('transfer_apply_data');
        
        if (!$applyData) {
            return response()->json([
                'success' => false,
                'message' => 'No application found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $applyData
        ]);
    }

    /**
     * Cancel application
     */
    public function cancelApplication(Request $request)
    {
        session()->forget('transfer_apply_data');
        
        return response()->json([
            'success' => true,
            'message' => 'Application cancelled successfully'
        ]);
    }
}
