<?php

namespace App\Http\Controllers;

use App\Models\SalesAgent;
use App\Http\Responses\ApiSuccessResponse;
use App\Models\SalesAgentVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SalesAgentController extends Controller
{
    /**
     * Get sales agent by referral link or ID
     *
     * @param string $identifier
     * @return ApiSuccessResponse
     */
    public function show(Request $request, $identifier)
    {
        $salesAgent = SalesAgent::where('referral_link', $identifier)
            ->orWhere('id', $identifier)
            ->firstOrFail();

        $userIp = $request->user_ip;

        // Build full URLs for stored files
        if ($salesAgent->image) {
            $salesAgent->image = asset($salesAgent->image);
        }
        if ($salesAgent->qr_code) {
            $salesAgent->qr_code = asset('storage/' . $salesAgent->qr_code);
        }


        $existingVisit = SalesAgentVisit::where('agent_id', $salesAgent->id)
            ->where('user_ip', $userIp)
            ->first();

        if (!$existingVisit) {
            SalesAgentVisit::create([
                'agent_id' => $salesAgent->id,
                'user_ip'  => $userIp
            ]);
        }

        $data = [
            'id' => $salesAgent->id,
            'first_name' => $salesAgent->first_name,
            'last_name' => $salesAgent->last_name,
            'email' => $salesAgent->email,
            'phone' => $salesAgent->phone,
            'image' => $salesAgent->image,
            'qr_code' => $salesAgent->qr_code,
            'referral_link' => $salesAgent->referral_link
        ];

        return new ApiSuccessResponse(
            $data,
            'Sales agent retrieved successfully'
        );
    }
}
