<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\SalesAgentsRequest;
use App\Http\Resources\Admin\UserResource;
use App\Models\Banner;
use App\Models\SalesAgent;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\KYC;
use App\Http\Responses\ApiSuccessResponse;
use App\Http\Responses\ApiErrorResponse;

class SalesAgentsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = SalesAgent::withCount('visits')->orderby('id', 'desc')->paginate(10);

        return response()->json(['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SalesAgentsRequest $request)
    {
        $validatedData = $request->validated();
        $validatedData['user_id'] = 1;//Auth::user()->id;
        $validatedData['referral_link'] = $this->referralLink($request);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/sales_agents');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/sales_agents/' . $filename;
            $validatedData['image'] = $imagePath;
        }

        if ($request->hasFile('identification_document')) {
            $file = $request->file('identification_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/identification_documents');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/identification_documents/' . $filename;
            $validatedData['identification_document'] = $imagePath;
        }

        if ($request->hasFile('agent_agreement')) {
            $file = $request->file('agent_agreement');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/agent_agreements');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/agent_agreements/' . $filename;
            $validatedData['agent_agreement'] = $imagePath;
        }

        $salesAgent = SalesAgent::create($validatedData);
        $url = '/sales-agent/' . $salesAgent->referral_link;
        $qrCodeImage = QrCode::format('png')->size(500)->generate($url);
        $fileName = $salesAgent->referral_link . '.png';
        $path = 'qr_codes/sales_agents/' . $fileName;
        Storage::disk('public')->put($path, $qrCodeImage);
        $salesAgent->qr_code = $path;
        $salesAgent->save();

        return response()->json(['data' => $salesAgent], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $salesAgent = SalesAgent::findOrFail($id);

        if (!$salesAgent) {
            return response()->json([
                'message' => 'Sales agent not found'
            ], 404);
        }

        // Build full URLs for stored files
        if ($salesAgent->image) {
            $salesAgent->image = asset($salesAgent->image);
        }
        if ($salesAgent->identification_document) {
            $salesAgent->identification_document = asset($salesAgent->identification_document);
        }
        if ($salesAgent->agent_agreement) {
            $salesAgent->agent_agreement = asset($salesAgent->agent_agreement);
        }
        if ($salesAgent->qr_code) {
            $salesAgent->qr_code = asset('storage/' . $salesAgent->qr_code);
        }

        return response()->json([
            'data' => $salesAgent,
            'message' => 'Sales agent details retrieved successfully'
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $salesAgent = SalesAgent::findOrFail($id);

        if (!$salesAgent) {
            return response()->json([
                'message' => 'Sales agent not found'
            ], 404);
        }

        // Build full URLs for stored files
        if ($salesAgent->image) {
            $salesAgent->image = asset('storage/' . $salesAgent->image);
        }
        if ($salesAgent->identification_document) {
            $salesAgent->identification_document = asset('storage/' . $salesAgent->identification_document);
        }
        if ($salesAgent->agent_agreement) {
            $salesAgent->agent_agreement = asset('storage/' . $salesAgent->agent_agreement);
        }
        if ($salesAgent->qr_code) {
            $salesAgent->qr_code = asset('storage/' . $salesAgent->qr_code);
        }

        return response()->json([
            'data' => $salesAgent,
            'message' => 'Sales agent retrieved successfully'
        ]);
    }

    /**
     * Update the specified sales agent in storage.
     */
    public function update(SalesAgentsRequest $request, $id)
    {
        /*$validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:sales_agents,email,' . $id,
            'phone' => 'nullable|string',
            'commission_amount' => 'nullable|numeric',
            'commission_type' => 'nullable|in:fixed,percentage',
            'is_active' => 'required|boolean',
            'region' => 'nullable|string',
            'zcal_meeting_link' => 'nullable|url',
            'whatsApp_number' => 'nullable|string',
            'guild_email_address' => 'nullable|email',
            'bank_name' => 'nullable|string',
            'account_number' => 'nullable|string',
            'iban_number' => 'nullable|string'
        ]);*/
        $validatedData = $request->validated();

        $salesAgent = SalesAgent::findOrFail($id);

        // Handle image upload
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/sales_agents');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/sales_agents/' . $filename;
            $validatedData['image'] = $imagePath;
        }

        // Handle identification document upload
        if ($request->hasFile('identification_document')) {
            $file = $request->file('identification_document');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/identification_documents');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/identification_documents/' . $filename;
            $validatedData['identification_document'] = $imagePath;
        }

        // Handle agent agreement upload
        if ($request->hasFile('agent_agreement')) {
            $file = $request->file('agent_agreement');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destinationPath = public_path('uploads/agent_agreements');
            $file->move($destinationPath, $filename);
            $imagePath = 'uploads/agent_agreements/' . $filename;
            $validatedData['agent_agreement'] = $imagePath;
        }

        // Update QR code if referral link changes
        if (isset($validatedData['referral_link']) && $validatedData['referral_link'] !== $salesAgent->referral_link) {
            $url = '/sales-agent/' . $validatedData['referral_link'];
            $qrCodeImage = QrCode::format('png')->size(500)->generate($url);
            $fileName = $validatedData['referral_link'] . '.png';
            $path = 'qr_codes/sales_agents/' . $fileName;
            Storage::disk('public')->put($path, $qrCodeImage);
            $validatedData['qr_code'] = $path;
        }

        $salesAgent->update($validatedData);

        return response()->json(['data' => $salesAgent]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = SalesAgent::find($id);

        if (!$data) {
            return response()->json([
                'message' => 'resource not found'
            ], 404);
        }

        $data->delete();

        return response()->json(['message' => 'Sales Agent deleted successfully']);

    }

    public function referralLink($request): string
    {
        do {
            $code = Str::slug($request->first_name . '-' . $request->last_name) . '-' . Str::random(20);

        } while (SalesAgent::where('referral_link', $code)->exists());

        return $code;
    }

    public function analytics(string $id)
    {
        $salesAgent = SalesAgent::findOrFail($id);
        
        $clients = KYC::where('sales_agent_id', $id)
            ->with('user')
            ->get()
            ->map(function ($kyc) {
                return [
                    'name' => $kyc->person_name,
                    'email' => $kyc->email,
                    'status' => $kyc->approve_status === 'Approved' ? 'Closed' : 'Interested',
                    'investment_amount' => $kyc->invested_amount,
                    'created_at' => $kyc->created_at
                ];
            });

        $data = [
            'qr_scans' => $salesAgent->visits()->count(),
            'link_clicks' => $salesAgent->visits()->count(),
            'website_visits' => $salesAgent->visits()->count(),
            'converted_clients' => $clients->where('status', 'Closed')->count(),
            'qualified_clients' => $clients->where('status', 'Interested')->count(),
            'total_investment' => $clients->where('status', 'Closed')->sum('investment_amount'),
            'booked_meetings' => $salesAgent->count(),
            'fixed_commission' => $salesAgent->sum('fixed_commission_amount'),
            'dynamic_commission' => $this->calculateDynamicCommission($salesAgent, $clients),
            'clients' => $clients
        ];

        return new ApiSuccessResponse($data);
    }

    /**
     * Calculate dynamic commission based on investment amounts
     */
    private function calculateDynamicCommission($salesAgent, $clients) 
    {
        $totalInvestment = $clients->where('status', 'Closed')->sum('investment_amount');
        $commissionPercentage = (float) $salesAgent->commission_type; // Ensure commission type is a float
        
        return ($totalInvestment * $commissionPercentage) / 100;
    }
}
