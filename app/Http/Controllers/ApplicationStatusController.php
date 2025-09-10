<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApplicationStatusController extends Controller
{
    /**
     * Show the application status page
     */
    public function show()
    {
        return view('transfer-of-ownership');
    }

    /**
     * Verify application status
     */
    public function verify(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'tct_number' => 'nullable|string|max:255',
            'tax_declaration_number' => 'nullable|string|max:255',
            'owner_number' => 'nullable|string|max:255',
            'terms_accepted' => 'required|accepted'
        ], [
            'terms_accepted.required' => 'You must agree to the Terms and Privacy Policy.',
            'terms_accepted.accepted' => 'You must agree to the Terms and Privacy Policy.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Check if at least one field is provided
        $tctNumber = $request->input('tct_number');
        $taxDeclarationNumber = $request->input('tax_declaration_number');
        $ownerNumber = $request->input('owner_number');

        if (empty($tctNumber) && empty($taxDeclarationNumber) && empty($ownerNumber)) {
            return back()->withErrors(['general' => 'Please provide at least one of the required fields.'])->withInput();
        }

        // Here you would typically query your database for the application status
        // For now, we'll simulate a response
        $applicationStatus = $this->getApplicationStatus($tctNumber, $taxDeclarationNumber, $ownerNumber);

        if ($applicationStatus) {
            return view('transfer-of-ownership', [
                'status' => $applicationStatus,
                'search_criteria' => [
                    'tct_number' => $tctNumber,
                    'tax_declaration_number' => $taxDeclarationNumber,
                    'owner_number' => $ownerNumber
                ]
            ]);
        } else {
            return back()->withErrors(['general' => 'No application found with the provided information. Please verify your details and try again.'])->withInput();
        }
    }

    /**
     * Get application status (simulate database query)
     */
    private function getApplicationStatus($tctNumber, $taxDeclarationNumber, $ownerNumber)
    {
        // This is a simulation - replace with actual database query
        // For demo purposes, we'll return a sample status if any field is provided
        
        if (!empty($tctNumber) || !empty($taxDeclarationNumber) || !empty($ownerNumber)) {
            return [
                'application_id' => 'APP-' . rand(100000, 999999),
                'status' => 'Under Review',
                'submitted_date' => '2024-01-15',
                'last_updated' => '2024-01-20',
                'property_address' => '123 Sample Street, Bacoor City',
                'owner_name' => 'John Doe',
                'tct_number' => $tctNumber ?: 'TCT-123456',
                'tax_declaration_number' => $taxDeclarationNumber ?: 'TD-789012',
                'remarks' => 'Your application is currently being reviewed by our assessment team. We will notify you once the review is complete.',
                'next_steps' => [
                    'Document verification in progress',
                    'Property assessment scheduled',
                    'Final review pending'
                ]
            ];
        }

        return null;
    }
}
