<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhmcsWebhookController extends Controller
{
    /**
     * Receive status updates from WHMCS hooks.
     *
     * WHMCS should POST here with:
     *   - secret      (must match WHMCS_WEBHOOK_SECRET)
     *   - event       (e.g. AfterModuleCreate, AfterModuleSuspend, AfterModuleTerminate…)
     *   - service_id  (WHMCS service ID)
     *   - status      (Active, Suspended, Terminated, Cancelled…)
     */
    public function handle(Request $request)
    {
        // Verify shared secret
        $expectedSecret = config('whmcs.webhook_secret');

        if (! $expectedSecret || $request->input('secret') !== $expectedSecret) {
            Log::warning('WHMCS webhook: invalid secret', [
                'ip' => $request->ip(),
            ]);
            abort(403, 'Invalid secret');
        }

        $event     = $request->input('event', '');
        $serviceId = (int) $request->input('service_id', 0);
        $status    = $request->input('status', '');

        if (! $serviceId) {
            return response()->json(['ok' => false, 'message' => 'Missing service_id'], 422);
        }

        // Update all payments linked to this WHMCS service
        $affected = Payment::where('whmcs_service_id', $serviceId)
            ->update(['whmcs_status' => strtolower($status)]);

        Log::info('WHMCS webhook received', [
            'event'      => $event,
            'service_id' => $serviceId,
            'status'     => $status,
            'affected'   => $affected,
        ]);

        return response()->json(['ok' => true, 'affected' => $affected]);
    }
}
