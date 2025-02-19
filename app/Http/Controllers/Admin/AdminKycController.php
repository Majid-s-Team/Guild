<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\KycResource;
use App\Models\KYC;
use App\Mail\KycApproved;
use App\Mail\KycDeclined;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Responses\ApiSuccessResponse;

class AdminKycController extends Controller
{
    /**
     * Display a listing of documents by type
     */
    public function index(Request $request)
    {
        $query = KYC::query();

        // Filter by document type if specified
        if ($request->has('type')) {
            $query->where('kyc_type', $request->type);
        }

        // Filter by status if specified
        if ($request->has('status')) {
            $query->where('approve_status', $request->status);
        }

        $documents = $query->with('user')
                         ->latest()
                         ->paginate(10);

        return KycResource::collection($documents);
    }

    /**
     * Display specific document details
     */
    public function show(KYC $kyc)
    {
        return new KycResource($kyc->load('user'));
    }

    /**
     * Approve a KYC document
     */
    public function approve(KYC $kyc,Request $request)
    {
        if ($request->hasFile('kyc_document')) {
            $file = $request->file('kyc_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/kyc');
            $file->move($destinationPath, $filename);
            $kyc_document = 'uploads/kyc/' . $filename;
        }

        // Update status
        $kyc->update([
            'person_name' => $request->person_name,
            'email' => $request->email,
            'phone_no' => $request->phone_no,
            'dob' => $request->dob,
            'nationality' => $request->nationality,
            'approve_status' => 'Approved',
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id(),
            'invested_amount' => $request->invested_amount,
            'investment_period' => $request->investment_period,
            'kyc_type' => $request->kyc_type,
            'kyc_document' => $kyc_document
        ]);

        /*$data['name'] = $request->person_name;
        $data['email'] = $request->email;
        $data['dob'] = $request->dob;
        $data['nationality'] = $request->nationality;
        $data['referral_source'] = $kyc->sales_agent_id;
        $data['is_active'] = 1;
        $data['password'] = bcrypt(\Str::random(6));
        $data['user_type'] = 'user';

        User::create($data);*/

        // Send approval email

        try {
            Mail::to($request->email)->send(new KycApproved($kyc));
        } catch (\Exception $exception) {
            //
        }

        return new ApiSuccessResponse(
            $kyc,
            'KYC document has been approved successfully'
        );
    }

    /**
     * Decline a KYC document
     */
    public function decline(Request $request, KYC $kyc)
    {
        $request->validate([
            'decline_reason' => 'required|string|max:500'
        ]);

        // Update status with decline reason
        $kyc->update([
            'approve_status' => 'Rejected',
            'decline_reason' => $request->decline_reason,
            'reviewed_at' => now(),
            'reviewed_by' => auth()->id()
        ]);

        // Send decline email with reason
        try {
            Mail::to($kyc->email)->send(new KycDeclined($kyc));
        } catch (\Exception $exception) {
            //
        }
        return new ApiSuccessResponse(
            $kyc,
            'KYC document has been declined'
        );
    }

    /**
     * Get documents statistics
     */
    public function stats()
    {
        $stats = [
            'total' => KYC::count(),
            'pending' => KYC::where('approve_status', 'Pending')->count(),
            'approved' => KYC::where('approve_status', 'Approved')->count(),
            'declined' => KYC::where('approve_status', 'Declined')->count(),
            'by_type' => KYC::selectRaw('kyc_type, count(*) as count')
                           ->groupBy('kyc_type')
                           ->get()
        ];

        return new ApiSuccessResponse($stats);
    }
}
