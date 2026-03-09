<?php

namespace App\Http\Controllers;

use App\Models\DomainOrder;
use App\Models\DomainTld;
use App\Services\OpenProviderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DomainController extends Controller
{
    public function __construct(
        private OpenProviderService $opService,
    ) {}

    /**
     * Domain search page – show TLD pricing + search bar.
     */
    public function index()
    {
        $tlds = DomainTld::active()->orderBy('sort_order')->orderBy('register_price')->get();

        return view('front.domains.index', compact('tlds'));
    }

    /**
     * AJAX domain availability check.
     */
    public function search(Request $request)
    {
        $request->validate([
            'domain' => 'required|string|max:253',
        ]);

        $raw = strtolower(trim($request->input('domain')));

        // Split SLD and TLD
        if (str_contains($raw, '.')) {
            $parts = explode('.', $raw, 2);
            $sld = $parts[0];
            $inputTld = '.' . $parts[1];
        } else {
            $sld = $raw;
            $inputTld = null;
        }

        // sanitize SLD
        $sld = preg_replace('/[^a-z0-9\-]/', '', $sld);

        if (strlen($sld) < 2) {
            return response()->json(['error' => 'Domain name too short'], 422);
        }

        $activeTlds = DomainTld::active()->orderBy('sort_order')->get();

        // If user typed specific TLD, check that first then others
        if ($inputTld) {
            $tldValues = $activeTlds->pluck('tld')->toArray();
            $primary = in_array($inputTld, $tldValues) ? [$inputTld] : [];
            $others = array_diff($tldValues, $primary);
            $checkTlds = array_merge($primary, array_values($others));
        } else {
            $checkTlds = $activeTlds->pluck('tld')->toArray();
        }

        // Check via OpenProvider API or local-only
        if ($this->opService->isConfigured()) {
            $results = $this->opService->checkDomain($sld, $checkTlds);
        } else {
            // Fallback: just show pricing without live check
            $results = array_map(fn($tld) => [
                'domain' => $sld . $tld,
                'tld'    => $tld,
                'status' => 'free', // assume free when API not configured
            ], $checkTlds);
        }

        // Merge with pricing from DB
        $pricing = $activeTlds->keyBy('tld');
        foreach ($results as &$r) {
            $tldInfo = $pricing[$r['tld']] ?? null;
            $r['register_price'] = $tldInfo?->register_price ?? null;
            $r['renew_price']    = $tldInfo?->renew_price ?? null;
            $r['currency']       = $tldInfo?->currency ?? 'BDT';
        }

        return response()->json([
            'sld'     => $sld,
            'results' => $results,
        ]);
    }

    /**
     * Domain checkout page.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'domain' => 'required|string',
            'tld'    => 'required|string',
            'action' => 'nullable|in:register,transfer',
        ]);

        $sld    = strtolower(trim($request->input('domain')));
        $tld    = $request->input('tld');
        $action = $request->input('action', 'register');

        $tldInfo = DomainTld::where('tld', $tld)->where('is_active', true)->firstOrFail();

        $price = $action === 'transfer' ? $tldInfo->transfer_price : $tldInfo->register_price;

        return view('front.domains.checkout', compact('sld', 'tld', 'tldInfo', 'action', 'price'));
    }

    /**
     * Process domain purchase (creates DomainOrder + redirects to SSLCommerz).
     */
    public function purchase(Request $request)
    {
        $data = $request->validate([
            'sld'        => 'required|string|max:63',
            'tld'        => 'required|string|max:20',
            'action'     => 'required|in:register,transfer',
            'years'      => 'required|integer|min:1|max:10',
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|max:255',
            'phone'      => 'required|string|max:20',
            'address'    => 'required|string|max:255',
            'city'       => 'required|string|max:100',
            'state'      => 'nullable|string|max:100',
            'postal_code'=> 'required|string|max:20',
            'country_code'=> 'nullable|string|max:5',
            'auth_code'  => 'nullable|string|max:255',
        ]);

        $tldInfo = DomainTld::where('tld', $data['tld'])->where('is_active', true)->firstOrFail();
        $basePrice = $data['action'] === 'transfer' ? $tldInfo->transfer_price : $tldInfo->register_price;
        $totalAmount = $basePrice * (int) $data['years'];
        $domainName = $data['sld'] . $data['tld'];

        $tranId = 'DOM' . strtoupper(uniqid());

        $order = DomainOrder::create([
            'user_id'     => Auth::id(),
            'domain_name' => $domainName,
            'sld'         => $data['sld'],
            'tld'         => $data['tld'],
            'action'      => $data['action'],
            'years'       => $data['years'],
            'amount'      => $totalAmount,
            'currency'    => $tldInfo->currency,
            'status'      => 'pending',
            'tran_id'     => $tranId,
            'registrant'  => [
                'first_name'   => $data['first_name'],
                'last_name'    => $data['last_name'],
                'email'        => $data['email'],
                'phone'        => $data['phone'],
                'address'      => $data['address'],
                'city'         => $data['city'],
                'state'        => $data['state'] ?? '',
                'postal_code'  => $data['postal_code'],
                'country_code' => $data['country_code'] ?? 'BD',
            ],
        ]);

        // SSLCommerz integration
        $postData = [
            'store_id'     => config('services.sslcommerz.store_id', env('SSLCZ_STORE_ID')),
            'store_passwd' => config('services.sslcommerz.store_password', env('SSLCZ_STORE_PASS')),
            'total_amount' => $totalAmount,
            'currency'     => $tldInfo->currency,
            'tran_id'      => $tranId,
            'success_url'  => route('domain.payment.success'),
            'fail_url'     => route('domain.payment.fail'),
            'cancel_url'   => route('domain.payment.cancel'),
            'cus_name'     => $data['first_name'] . ' ' . $data['last_name'],
            'cus_email'    => $data['email'],
            'cus_phone'    => $data['phone'],
            'cus_add1'     => $data['address'],
            'cus_city'     => $data['city'],
            'cus_state'    => $data['state'] ?? '',
            'cus_postcode' => $data['postal_code'],
            'cus_country'  => $data['country_code'] ?? 'Bangladesh',
            'product_name'    => 'Domain: ' . $domainName,
            'product_category'=> 'Domain Registration',
            'product_profile' => 'non-physical-goods',
            'shipping_method' => 'NO',
            'num_of_item'     => 1,
        ];

        $sslcommerzUrl = env('SSLCZ_SANDBOX', false)
            ? 'https://sandbox.sslcommerz.com/gwprocess/v4/api.php'
            : 'https://securepay.sslcommerz.com/gwprocess/v4/api.php';

        $response = \Illuminate\Support\Facades\Http::asForm()
            ->post($sslcommerzUrl, $postData);

        $result = $response->json();

        if (($result['status'] ?? '') === 'SUCCESS' && !empty($result['GatewayPageURL'])) {
            return redirect($result['GatewayPageURL']);
        }

        return back()->withErrors(['payment' => 'Payment gateway error. Please try again.'])->withInput();
    }

    /**
     * SSLCommerz success callback for domain payment.
     */
    public function paymentSuccess(Request $request)
    {
        $tranId = $request->input('tran_id');
        $valId  = $request->input('val_id');
        $status = $request->input('status');

        $order = DomainOrder::where('tran_id', $tranId)->first();

        if (!$order || $status !== 'VALID') {
            return redirect()->route('domains.index')
                ->with('error', 'Payment validation failed.');
        }

        $order->update(['status' => 'paid']);

        // Auto-provision via OpenProvider
        $this->provisionDomain($order);

        return view('front.domains.success', compact('order'));
    }

    /**
     * SSLCommerz fail callback.
     */
    public function paymentFail(Request $request)
    {
        $tranId = $request->input('tran_id');
        $order = DomainOrder::where('tran_id', $tranId)->first();
        $order?->update(['status' => 'failed']);

        return view('front.domains.fail', ['order' => $order]);
    }

    /**
     * SSLCommerz cancel callback.
     */
    public function paymentCancel(Request $request)
    {
        $tranId = $request->input('tran_id');
        $order = DomainOrder::where('tran_id', $tranId)->first();
        $order?->update(['status' => 'cancelled']);

        return view('front.domains.cancel', ['order' => $order]);
    }

    /**
     * Provision domain on OpenProvider after payment.
     */
    private function provisionDomain(DomainOrder $order): void
    {
        if (!$this->opService->isConfigured()) {
            return;
        }

        try {
            $registrant = $order->registrant ?? [];

            if ($order->action === 'transfer') {
                $handle = $this->opService->createContact($registrant);
                $result = $this->opService->transferDomain([
                    'sld'          => $order->sld,
                    'tld'          => $order->tld,
                    'years'        => $order->years,
                    'auth_code'    => $registrant['auth_code'] ?? '',
                    'owner_handle' => $handle,
                ]);
            } else {
                $result = $this->opService->provisionDomain([
                    'sld'     => $order->sld,
                    'tld'     => $order->tld,
                    'years'   => $order->years,
                    'contact' => $registrant,
                ]);
            }

            if ($result['success'] ?? false) {
                $order->update([
                    'status'      => 'active',
                    'op_domain_id'=> $result['domain_id'] ?? null,
                    'op_status'   => $result['status'] ?? 'ACT',
                    'registration_date' => now(),
                    'expiry_date'       => now()->addYears($order->years),
                    'nameservers'       => config('openprovider.default_ns'),
                ]);
            }
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Domain provisioning error', [
                'order_id' => $order->id,
                'error'    => $e->getMessage(),
            ]);
        }
    }
}
