<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhmcsService
{
    private string $apiUrl;
    private string $identifier;
    private string $secret;
    private string $accessKey;

    public function __construct()
    {
        $this->apiUrl     = config('whmcs.api_url');
        $this->identifier = config('whmcs.identifier');
        $this->secret     = config('whmcs.secret');
        $this->accessKey  = config('whmcs.access_key');
    }

    /**
     * Send a request to the WHMCS API.
     */
    private function call(string $action, array $params = []): array
    {
        $params = array_merge($params, [
            'action'     => $action,
            'identifier' => $this->identifier,
            'secret'     => $this->secret,
            'accesskey'  => $this->accessKey,
            'responsetype' => 'json',
        ]);

        $response = Http::asForm()
            ->timeout(30)
            ->post($this->apiUrl, $params);

        if (! $response->ok()) {
            Log::error('WHMCS API HTTP error', [
                'action' => $action,
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);

            return ['result' => 'error', 'message' => 'HTTP ' . $response->status()];
        }

        $data = $response->json();

        if (($data['result'] ?? '') !== 'success') {
            Log::warning('WHMCS API action failed', [
                'action'  => $action,
                'message' => $data['message'] ?? 'Unknown error',
            ]);
        }

        return $data;
    }

    // ────────────────────────────────────────────────
    //  CLIENT MANAGEMENT
    // ────────────────────────────────────────────────

    /**
     * Find an existing WHMCS client by email.
     * Returns client data array or null if not found.
     */
    public function findClientByEmail(string $email): ?array
    {
        $data = $this->call('GetClients', [
            'search'   => $email,
            'limitnum' => 1,
        ]);

        if (($data['result'] ?? '') !== 'success') {
            return null;
        }

        $clients = $data['clients']['client'] ?? [];

        foreach ($clients as $client) {
            if (mb_strtolower($client['email']) === mb_strtolower($email)) {
                return $client;
            }
        }

        return null;
    }

    /**
     * Create a new WHMCS client. Returns the full API response.
     */
    public function addClient(array $details): array
    {
        return $this->call('AddClient', [
            'firstname'   => $details['first_name'] ?? 'Customer',
            'lastname'    => $details['last_name'] ?? '',
            'email'       => $details['email'],
            'address1'    => $details['address'] ?? 'N/A',
            'city'        => $details['city'] ?? 'Dhaka',
            'state'       => $details['state'] ?? 'Dhaka',
            'postcode'    => $details['postcode'] ?? '1207',
            'country'     => $details['country_code'] ?? 'BD',
            'phonenumber' => $details['phone'] ?? '',
            'password2'   => $details['password'] ?? bin2hex(random_bytes(8)),
            'noemail'     => true, // don't send WHMCS welcome email – our app handles it
        ]);
    }

    /**
     * Find an existing client by email, or create a new one.
     * Returns ['client_id' => int, 'created' => bool].
     */
    public function findOrCreateClient(array $details): array
    {
        $existing = $this->findClientByEmail($details['email']);

        if ($existing) {
            return [
                'client_id' => (int) $existing['id'],
                'created'   => false,
            ];
        }

        $result = $this->addClient($details);

        if (($result['result'] ?? '') !== 'success') {
            Log::error('WHMCS: AddClient failed', $result);
            throw new \RuntimeException('WHMCS client creation failed: ' . ($result['message'] ?? 'Unknown'));
        }

        return [
            'client_id' => (int) $result['clientid'],
            'created'   => true,
        ];
    }

    // ────────────────────────────────────────────────
    //  ORDER MANAGEMENT
    // ────────────────────────────────────────────────

    /**
     * Create a new order in WHMCS.
     *
     * @param  int    $clientId       WHMCS client ID
     * @param  int    $whmcsProductId WHMCS product/service ID
     * @param  string $billingCycle   WHMCS cycle name (monthly, annually…)
     * @param  string $domain         Domain or hostname
     * @param  array  $extra          Extra data (promo code, custom fields, etc.)
     * @return array  WHMCS API response with orderid, productids, invoiceid
     */
    public function createOrder(int $clientId, int $whmcsProductId, string $billingCycle, string $domain = '', array $extra = []): array
    {
        $params = [
            'clientid'      => $clientId,
            'pid'           => $whmcsProductId,
            'billingcycle'  => $billingCycle,
            'domain'        => $domain,
            'paymentmethod' => config('whmcs.payment_method', 'sslcommerz'),
            'noinvoice'     => false,
            'noemail'       => true,
        ];

        if (! empty($extra['promo_code'])) {
            $params['promocode'] = $extra['promo_code'];
        }

        // Custom field values (pipe-separated)
        if (! empty($extra['custom_fields'])) {
            $params['customfields'] = base64_encode(json_encode($extra['custom_fields']));
        }

        // Config options (pipe-separated)
        if (! empty($extra['config_options'])) {
            $params['configoptions'] = base64_encode(json_encode($extra['config_options']));
        }

        $result = $this->call('AddOrder', $params);

        if (($result['result'] ?? '') !== 'success') {
            Log::error('WHMCS: AddOrder failed', $result);
            throw new \RuntimeException('WHMCS order creation failed: ' . ($result['message'] ?? 'Unknown'));
        }

        return $result;
    }

    /**
     * Accept (activate) an order → triggers WHM auto-provisioning.
     */
    public function acceptOrder(int $orderId): array
    {
        $result = $this->call('AcceptOrder', [
            'orderid' => $orderId,
        ]);

        if (($result['result'] ?? '') !== 'success') {
            Log::error('WHMCS: AcceptOrder failed', $result);
        }

        return $result;
    }

    /**
     * Add a manual payment entry against a WHMCS invoice
     * (marks SSLCommerz payment as received).
     */
    public function addInvoicePayment(int $invoiceId, string $transId, float $amount, string $gateway = ''): array
    {
        return $this->call('AddInvoicePayment', [
            'invoiceid'  => $invoiceId,
            'transid'    => $transId,
            'amount'     => $amount,
            'gateway'    => $gateway ?: config('whmcs.payment_method', 'sslcommerz'),
            'date'       => now()->format('Y-m-d H:i:s'),
        ]);
    }

    // ────────────────────────────────────────────────
    //  QUERY HELPERS
    // ────────────────────────────────────────────────

    /**
     * Get a client's active products/services from WHMCS.
     */
    public function getClientProducts(int $clientId): array
    {
        $data = $this->call('GetClientsProducts', [
            'clientid' => $clientId,
            'stats'    => true,
        ]);

        return $data['products']['product'] ?? [];
    }

    /**
     * Get details of a single WHMCS service/product by service ID.
     */
    public function getServiceDetails(int $serviceId): ?array
    {
        $data = $this->call('GetClientsProducts', [
            'serviceid' => $serviceId,
            'stats'     => true,
        ]);

        $products = $data['products']['product'] ?? [];

        return $products[0] ?? null;
    }

    /**
     * Map a Laravel billing cycle key to WHMCS cycle string.
     */
    public function mapBillingCycle(string $key): string
    {
        $map = config('whmcs.cycle_map', []);

        return $map[strtolower($key)] ?? 'annually';
    }

    /**
     * Full provisioning pipeline – called after SSLCommerz payment succeeds.
     *
     * Steps:
     *  1. Find or create WHMCS client
     *  2. Create order
     *  3. Mark invoice as paid
     *  4. Accept order (triggers WHM provisioning)
     *
     * Returns an array of WHMCS IDs for storage on the Payment model.
     */
    public function provisionAfterPayment(array $customerDetails, int $whmcsProductId, string $billingCycle, string $domain, string $tranId, float $amount): array
    {
        // 1. Client
        $clientData = $this->findOrCreateClient($customerDetails);
        $clientId   = $clientData['client_id'];

        // 2. Order
        $orderResult = $this->createOrder($clientId, $whmcsProductId, $billingCycle, $domain);
        $orderId     = (int) ($orderResult['orderid'] ?? 0);
        $invoiceId   = (int) ($orderResult['invoiceid'] ?? 0);

        // Service ID is returned as comma-separated string
        $serviceIds  = $orderResult['productids'] ?? '';
        $serviceId   = (int) explode(',', $serviceIds)[0];

        // 3. Mark invoice paid
        if ($invoiceId) {
            $this->addInvoicePayment($invoiceId, $tranId, $amount);
        }

        // 4. Accept order → WHM provisioning
        if ($orderId) {
            $this->acceptOrder($orderId);
        }

        Log::info('WHMCS provisioning complete', [
            'tran_id'    => $tranId,
            'client_id'  => $clientId,
            'order_id'   => $orderId,
            'invoice_id' => $invoiceId,
            'service_id' => $serviceId,
        ]);

        return [
            'whmcs_client_id'  => $clientId,
            'whmcs_order_id'   => $orderId,
            'whmcs_invoice_id' => $invoiceId,
            'whmcs_service_id' => $serviceId,
        ];
    }
}
