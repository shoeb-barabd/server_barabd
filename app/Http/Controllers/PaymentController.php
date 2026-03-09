<?php

namespace App\Http\Controllers;

use App\Models\BillingCycle;
use App\Models\Location;
use App\Models\Payment;
use App\Models\Product;
use App\Services\QuoteService;
use App\Services\WhmcsService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    public function __construct(
        private QuoteService $quoteService,
        private WhmcsService $whmcsService,
    ) {}

    public function checkout()
    {
        return view('ssl.checkout');
    }

    public function initiate(Request $request)
    {
        $payload = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'location_id'      => 'required|exists:locations,id',
            'billing_cycle_id' => 'required|exists:billing_cycles,id',
            'amount'           => 'nullable|numeric|min:0.01',
            'currency'         => 'nullable|string|max:3',
            'features'         => 'array',
            'features.*'       => 'nullable',
            'add_ons'          => 'array',
            'add_ons.*.key'    => 'required_with:add_ons|string',
            'add_ons.*.qty'    => 'nullable|numeric',
            'plan_title'       => 'nullable|string|max:255',
            'notes'            => 'nullable|string|max:500',
            'features_json'    => 'nullable|string',
            'add_ons_json'     => 'nullable|string',
        ]);

        // Allow JSON payloads from the customize page if array inputs are empty
        if (empty($payload['features'] ?? []) && $request->filled('features_json')) {
            $decoded = json_decode($request->string('features_json'), true);
            $payload['features'] = is_array($decoded) ? $decoded : [];
        }
        if (empty($payload['add_ons'] ?? []) && $request->filled('add_ons_json')) {
            $decoded = json_decode($request->string('add_ons_json'), true);
            $payload['add_ons'] = is_array($decoded) ? $decoded : [];
        }

        $product  = Product::with('category')->findOrFail($payload['product_id']);
        $location = Location::findOrFail($payload['location_id']);
        $cycle    = BillingCycle::findOrFail($payload['billing_cycle_id']);

        $amount = null;
        $currency = $payload['currency'] ?? $location->currency_code ?? 'BDT';

        $quote = null;
        if ($request->filled('amount')) {
            $amount = (float) $payload['amount'];
        } else {
            try {
                $quote = $this->quoteService->buildQuote([
                    'product_id'       => $payload['product_id'],
                    'location_id'      => $payload['location_id'],
                    'billing_cycle_id' => $payload['billing_cycle_id'],
                    'features'         => $payload['features'] ?? [],
                    'add_ons'          => $payload['add_ons'] ?? [],
                ]);
                $amount   = (float) ($quote['total'] ?? 0);
                $currency = $quote['currency'] ?? $currency;
            } catch (ValidationException $e) {
                return back()->withErrors($e->errors())->withInput();
            }
        }

        if ($amount <= 0) {
            return back()->with('error', 'Invalid amount calculated for this order.');
        }

        $tranId = 'BARABD_' . uniqid();

        $payment = Payment::create([
            'tran_id'          => $tranId,
            'user_id'          => $request->user()?->id,
            'category_id'      => $product->category_id,
            'product_id'       => $product->id,
            'location_id'      => $location->id,
            'billing_cycle_id' => $cycle->id,
            'product_name'     => $payload['plan_title'] ?: $product->name,
            'amount'           => $amount,
            'currency'         => $currency,
            'status'           => 'PENDING',
            'config'           => [
                'features' => $payload['features'] ?? [],
                'add_ons'  => $payload['add_ons'] ?? [],
            ],
            'line_items'       => $quote['lines'] ?? [],
            'meta'             => array_filter([
                'location'         => $quote['location'] ?? $location->name ?? null,
                'billing_cycle'    => $quote['billing_cycle'] ?? $cycle->code ?? null,
                'tax_rate_percent' => $quote['tax_rate_percent'] ?? $location->tax_rate_percent ?? null,
                'notes'            => $payload['notes'] ?? null,
            ], fn ($value) => $value !== null && $value !== ''),
        ]);

        $storeId   = env('SSLCZ_STORE_ID');
        $storePass = env('SSLCZ_STORE_PASS');
        $sandbox   = filter_var(env('SSLCZ_SANDBOX', true), FILTER_VALIDATE_BOOLEAN);

        $baseUrl = $sandbox
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';

        $postData = [
            'store_id'      => $storeId,
            'store_passwd'  => $storePass,
            'total_amount'  => $amount,
            'currency'      => $currency,
            'tran_id'       => $tranId,
            'success_url'   => route('ssl.success'),
            'fail_url'      => route('ssl.fail'),
            'cancel_url'    => route('ssl.cancel'),

            'cus_name'      => $request->user()?->name ?? 'Customer',
            'cus_email'     => $request->user()?->email ?? 'test@example.com',
            'cus_add1'      => 'Dhaka',
            'cus_city'      => 'Dhaka',
            'cus_postcode'  => '1207',
            'cus_country'   => 'Bangladesh',
            'cus_phone'     => '01700000000',

            'shipping_method'  => 'NO',
            'product_name'     => $payload['plan_title'] ?: $product->name,
            'product_category' => $product->category?->name ?? 'Hosting',
            'product_profile'  => 'general',
        ];

        $response = Http::asForm()->post($baseUrl . '/gwprocess/v4/api.php', $postData);

        if (!$response->ok()) {
            $payment->update(['status' => 'FAILED']);
            return back()->with('error', 'Could not connect to SSLCommerz');
        }

        $body = $response->json();

        if (isset($body['status']) && $body['status'] === 'SUCCESS' && !empty($body['GatewayPageURL'])) {
            $payment->update([
                'gateway_response' => json_encode($body),
            ]);

            return redirect()->away($body['GatewayPageURL']);
        }

        $payment->update([
            'status'           => 'FAILED',
            'gateway_response' => json_encode($body),
        ]);

        \Log::error('SSLCommerz init failed', [
            'tran_id'  => $tranId,
            'store_id' => $storeId,
            'sandbox'  => $sandbox,
            'amount'   => $amount,
            'currency' => $currency,
            'response' => $body,
            'success_url' => route('ssl.success'),
            'fail_url'    => route('ssl.fail'),
            'cancel_url'  => route('ssl.cancel'),
        ]);

        return back()->with('error', 'SSLCommerz init failed: ' . ($body['faildReason'] ?? $body['status'] ?? 'Unknown error'));
    }

    /**
     * AJAX endpoint – returns GatewayPageURL as JSON so the frontend can
     * display SSLCommerz inside an embedded iframe instead of redirecting.
     */
    public function initiateAjax(Request $request)
    {
        $payload = $request->validate([
            'product_id'       => 'required|exists:products,id',
            'location_id'      => 'required|exists:locations,id',
            'billing_cycle_id' => 'required|exists:billing_cycles,id',
            'amount'           => 'nullable|numeric|min:0.01',
            'currency'         => 'nullable|string|max:3',
            'features'         => 'array',
            'features.*'       => 'nullable',
            'add_ons'          => 'array',
            'add_ons.*.key'    => 'required_with:add_ons|string',
            'add_ons.*.qty'    => 'nullable|numeric',
            'plan_title'       => 'nullable|string|max:255',
            'notes'            => 'nullable|string|max:500',
            'features_json'    => 'nullable|string',
            'add_ons_json'     => 'nullable|string',
        ]);

        if (empty($payload['features'] ?? []) && $request->filled('features_json')) {
            $decoded = json_decode($request->string('features_json'), true);
            $payload['features'] = is_array($decoded) ? $decoded : [];
        }
        if (empty($payload['add_ons'] ?? []) && $request->filled('add_ons_json')) {
            $decoded = json_decode($request->string('add_ons_json'), true);
            $payload['add_ons'] = is_array($decoded) ? $decoded : [];
        }

        $product  = Product::with('category')->findOrFail($payload['product_id']);
        $location = Location::findOrFail($payload['location_id']);
        $cycle    = BillingCycle::findOrFail($payload['billing_cycle_id']);

        $amount   = null;
        $currency = $payload['currency'] ?? $location->currency_code ?? 'BDT';

        $quote = null;
        if ($request->filled('amount')) {
            $amount = (float) $payload['amount'];
        } else {
            try {
                $quote = $this->quoteService->buildQuote([
                    'product_id'       => $payload['product_id'],
                    'location_id'      => $payload['location_id'],
                    'billing_cycle_id' => $payload['billing_cycle_id'],
                    'features'         => $payload['features'] ?? [],
                    'add_ons'          => $payload['add_ons'] ?? [],
                ]);
                $amount   = (float) ($quote['total'] ?? 0);
                $currency = $quote['currency'] ?? $currency;
            } catch (ValidationException $e) {
                return response()->json(['success' => false, 'message' => 'Validation error'], 422);
            }
        }

        if ($amount <= 0) {
            return response()->json(['success' => false, 'message' => 'Invalid amount calculated.'], 422);
        }

        $tranId = 'BARABD_' . uniqid();

        $payment = Payment::create([
            'tran_id'          => $tranId,
            'user_id'          => $request->user()?->id,
            'category_id'      => $product->category_id,
            'product_id'       => $product->id,
            'location_id'      => $location->id,
            'billing_cycle_id' => $cycle->id,
            'product_name'     => $payload['plan_title'] ?: $product->name,
            'amount'           => $amount,
            'currency'         => $currency,
            'status'           => 'PENDING',
            'config'           => [
                'features' => $payload['features'] ?? [],
                'add_ons'  => $payload['add_ons'] ?? [],
            ],
            'line_items'       => $quote['lines'] ?? [],
            'meta'             => array_filter([
                'location'         => $quote['location'] ?? $location->name ?? null,
                'billing_cycle'    => $quote['billing_cycle'] ?? $cycle->code ?? null,
                'tax_rate_percent' => $quote['tax_rate_percent'] ?? $location->tax_rate_percent ?? null,
                'notes'            => $payload['notes'] ?? null,
            ], fn ($value) => $value !== null && $value !== ''),
        ]);

        $storeId   = env('SSLCZ_STORE_ID');
        $storePass = env('SSLCZ_STORE_PASS');
        $sandbox   = filter_var(env('SSLCZ_SANDBOX', true), FILTER_VALIDATE_BOOLEAN);

        $baseUrl = $sandbox
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';

        $postData = [
            'store_id'      => $storeId,
            'store_passwd'  => $storePass,
            'total_amount'  => $amount,
            'currency'      => $currency,
            'tran_id'       => $tranId,
            'success_url'   => route('ssl.success'),
            'fail_url'      => route('ssl.fail'),
            'cancel_url'    => route('ssl.cancel'),

            'cus_name'      => $request->user()?->name ?? 'Customer',
            'cus_email'     => $request->user()?->email ?? 'test@example.com',
            'cus_add1'      => 'Dhaka',
            'cus_city'      => 'Dhaka',
            'cus_postcode'  => '1207',
            'cus_country'   => 'Bangladesh',
            'cus_phone'     => '01700000000',

            'shipping_method'  => 'NO',
            'product_name'     => $payload['plan_title'] ?: $product->name,
            'product_category' => $product->category?->name ?? 'Hosting',
            'product_profile'  => 'general',
        ];

        $response = Http::asForm()->post($baseUrl . '/gwprocess/v4/api.php', $postData);

        if (!$response->ok()) {
            $payment->update(['status' => 'FAILED']);
            return response()->json(['success' => false, 'message' => 'Could not connect to SSLCommerz'], 502);
        }

        $body = $response->json();

        if (isset($body['status']) && $body['status'] === 'SUCCESS' && !empty($body['GatewayPageURL'])) {
            $payment->update(['gateway_response' => json_encode($body)]);

            return response()->json([
                'success'     => true,
                'gateway_url' => $body['GatewayPageURL'],
                'tran_id'     => $tranId,
            ]);
        }

        $payment->update([
            'status'           => 'FAILED',
            'gateway_response' => json_encode($body),
        ]);

        \Log::error('SSLCommerz AJAX init failed', [
            'tran_id'  => $tranId,
            'response' => $body,
        ]);

        return response()->json([
            'success' => false,
            'message' => $body['faildReason'] ?? $body['status'] ?? 'SSLCommerz init failed',
        ], 422);
    }

    public function success(Request $request)
    {
        $tranId = $request->input('tran_id');
        $valId  = $request->input('val_id');

        $payment = Payment::where('tran_id', $tranId)->firstOrFail();

        $sandbox   = env('SSLCZ_SANDBOX', true);
        $baseUrl   = $sandbox
            ? 'https://sandbox.sslcommerz.com'
            : 'https://securepay.sslcommerz.com';

        $storeId   = env('SSLCZ_STORE_ID');
        $storePass = env('SSLCZ_STORE_PASS');

        $validationUrl = $baseUrl . '/validator/api/validationserverAPI.php';

        $validateResponse = Http::get($validationUrl, [
            'val_id'       => $valId,
            'store_id'     => $storeId,
            'store_passwd' => $storePass,
            'format'       => 'json',
        ]);

        if ($validateResponse->ok()) {
            $data = $validateResponse->json();

            if (in_array($data['status'] ?? '', ['VALID', 'VALIDATED'])) {
                if ((float)$data['amount'] == (float)$payment->amount) {
                    $payment->update([
                        'status'           => 'SUCCESS',
                        'val_id'           => $valId,
                        'gateway_response' => json_encode($data),
                        'paid_at'          => now(),
                    ]);

                    // ── WHMCS Auto-Provisioning ──
                    $this->provisionOnWhmcs($payment);

                    return view('front.payment-success', compact('payment', 'data'));
                }
            }

            $payment->update([
                'status'           => 'FAILED',
                'gateway_response' => json_encode($data),
            ]);

            return view('front.payment-fail', ['payment' => $payment, 'reason' => 'Validation failed']);
        }

        $payment->update(['status' => 'FAILED']);
        return view('front.payment-fail', ['payment' => $payment, 'reason' => 'Validation API error']);
    }

    public function fail(Request $request)
    {
        $tranId = $request->input('tran_id');
        if ($tranId) {
            Payment::where('tran_id', $tranId)->update(['status' => 'FAILED']);
        }
        return view('front.payment-fail');
    }

    public function cancel(Request $request)
    {
        $tranId = $request->input('tran_id');
        if ($tranId) {
            Payment::where('tran_id', $tranId)->update(['status' => 'CANCELLED']);
        }
        return view('front.payment-cancel');
    }

    public function ipn(Request $request)
    {
        return response('IPN received', 200);
    }

    public function invoice(Request $request, Payment $payment)
    {
        $user = $payment->user;
        if (! $user || $user->id !== auth()->id()) {
            abort(403);
        }

        $payment->load(['product', 'category', 'location', 'billingCycle']);

        if ($request->boolean('download')) {
            $pdf = Pdf::loadView('customer.payment.invoice', compact('payment', 'user'));
            $fileName = 'invoice-' . ($payment->tran_id ?: $payment->id) . '.pdf';
            return $pdf->download($fileName);
        }

        return view('customer.payment.invoice_show', compact('payment', 'user'));
    }

    /**
     * After SSLCommerz payment succeeds, provision the order in WHMCS.
     * Runs silently — any failure is logged but does not block the user.
     */
    private function provisionOnWhmcs(Payment $payment): void
    {
        try {
            $product = $payment->product;

            // Skip if product has no WHMCS mapping
            if (! $product || ! $product->whmcs_product_id) {
                Log::info('WHMCS skip: no whmcs_product_id for payment #' . $payment->id);
                return;
            }

            // Skip if WHMCS API is not configured
            if (! config('whmcs.api_url') || ! config('whmcs.identifier')) {
                Log::info('WHMCS skip: API not configured');
                return;
            }

            $user     = $payment->user;
            $cycle    = $payment->billingCycle;
            $location = $payment->location;

            // Map billing cycle
            $whmcsCycle = $cycle?->whmcs_cycle
                ?: $this->whmcsService->mapBillingCycle($cycle?->key ?? 'annually');

            // Build customer details
            $meta = $payment->meta ?? [];
            $customerDetails = [
                'email'        => $user?->email ?? ($meta['email'] ?? ''),
                'first_name'   => $user?->first_name ?? ($meta['first_name'] ?? 'Customer'),
                'last_name'    => $user?->last_name ?? ($meta['last_name'] ?? ''),
                'phone'        => $user?->phone ?? ($meta['phone'] ?? ''),
                'address'      => $meta['street_address'] ?? 'N/A',
                'city'         => $meta['city'] ?? ($location?->name ?? 'Dhaka'),
                'state'        => $meta['state'] ?? 'Dhaka',
                'postcode'     => $meta['postal_code'] ?? '1207',
                'country_code' => $location?->country?->iso2 ?? 'BD',
            ];

            // Domain from payment meta or config
            $domain = $meta['domain_name'] ?? '';
            if (! empty($meta['tld'])) {
                $domain .= '.' . ltrim($meta['tld'], '.');
            }

            // Provision
            $whmcsIds = $this->whmcsService->provisionAfterPayment(
                $customerDetails,
                $product->whmcs_product_id,
                $whmcsCycle,
                $domain,
                $payment->tran_id,
                (float) $payment->amount,
            );

            // Save WHMCS IDs on the payment record
            $payment->update([
                'whmcs_client_id'  => $whmcsIds['whmcs_client_id'] ?? null,
                'whmcs_order_id'   => $whmcsIds['whmcs_order_id'] ?? null,
                'whmcs_invoice_id' => $whmcsIds['whmcs_invoice_id'] ?? null,
                'whmcs_service_id' => $whmcsIds['whmcs_service_id'] ?? null,
                'whmcs_status'     => 'provisioned',
            ]);

        } catch (\Throwable $e) {
            // Log the error but don't break the payment success flow
            Log::error('WHMCS provisioning failed', [
                'payment_id' => $payment->id,
                'tran_id'    => $payment->tran_id,
                'error'      => $e->getMessage(),
                'trace'      => $e->getTraceAsString(),
            ]);

            $payment->update(['whmcs_status' => 'provision_failed']);
        }
    }
}
