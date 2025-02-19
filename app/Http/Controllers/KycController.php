<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\KycResource;
use App\Models\KYC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\ApiErrorResponse;

class KycController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kycs = KYC::where('user_id', Auth::id())
                   ->orderBy('created_at', 'desc')
                   ->paginate(10);

        return new ApiSuccessResponse($kycs);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nationality'  => 'nullable|string',
            'dob'  => 'nullable|string',
            'email'  => 'required|string|email',
            'phone_no' => 'required|string',
            'source_type'  => 'required|string',
            'sales_agent_id'  => 'nullable|integer',
            'person_name' => 'required|string',
            /*'kyc_type' => 'required|in:NIC,Passport,Other',
            'kyc_document' => 'required|file|mimes:jpeg,png,jpg|max:5000',
            'kyc_document2' => 'nullable|file|mimes:jpeg,png,jpg|max:5000',*/
        ]);

        // Handle primary document upload
        /*if ($request->hasFile('kyc_document')) {
            $file = $request->file('kyc_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/kyc');
            $file->move($destinationPath, $filename);
            $validatedData['kyc_document'] = 'uploads/kyc/' . $filename;
        }*/

        // Handle secondary document upload
        /*if ($request->hasFile('kyc_document2')) {
            $file = $request->file('kyc_document2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/kyc');
            $file->move($destinationPath, $filename);
            $validatedData['kyc_document2'] = 'uploads/kyc/' . $filename;
        }*/

        $validatedData['user_id'] = 1;//Auth::id();
        $validatedData['approve_status'] = 'Pending';

        $kyc = KYC::create($validatedData);

        return new ApiSuccessResponse($kyc, 'Details submitted successfully', [], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $kyc = KYC::where('user_id', Auth::id())
                  ->where('kyc_id', $id)
                  ->firstOrFail();

        return new ApiSuccessResponse($kyc);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $kyc = KYC::where('user_id', Auth::id())
                  ->where('kyc_id', $id)
                  ->firstOrFail();

        if ($kyc->approve_status !== 'Pending') {
            return new ApiErrorResponse('Cannot update KYC after approval/rejection', '', 403);
        }

        $validatedData = $request->validate([
            'person_name' => 'required|string',
            'relation' => 'required|string',
            'kyc_type' => 'required|in:NIC,Passport,Other',
            'kyc_document' => 'nullable|file|mimes:jpeg,png,jpg|max:5000',
            'kyc_document2' => 'nullable|file|mimes:jpeg,png,jpg|max:5000',
        ]);

        // Handle primary document upload
        if ($request->hasFile('kyc_document')) {
            $file = $request->file('kyc_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/kyc');
            $file->move($destinationPath, $filename);
            $validatedData['kyc_document'] = 'uploads/kyc/' . $filename;
        }

        // Handle secondary document upload
        if ($request->hasFile('kyc_document2')) {
            $file = $request->file('kyc_document2');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/kyc');
            $file->move($destinationPath, $filename);
            $validatedData['kyc_document2'] = 'uploads/kyc/' . $filename;
        }

        $kyc->update($validatedData);

        return new ApiSuccessResponse($kyc, 'KYC updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kyc = KYC::where('user_id', Auth::id())
                  ->where('kyc_id', $id)
                  ->firstOrFail();

        if ($kyc->approve_status !== 'Pending') {
            return new ApiErrorResponse('Cannot delete KYC after approval/rejection', '', 403);
        }

        $kyc->delete();

        return new ApiSuccessResponse(null, 'KYC deleted successfully');
    }
}
