<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class OpenProviderService
{
    private string $apiUrl;
    private string $username;
    private string $password;

    public function __construct()
    {
        $this->apiUrl   = rtrim(config('openprovider.api_url'), '/');
        $this->username = config('openprovider.username');
        $this->password = config('openprovider.password');
    }

    /**
     * Check if API credentials are configured.
     */
    public function isConfigured(): bool
    {
        return $this->username !== '' && $this->password !== '';
    }

    /**
     * Get or refresh the bearer token (cached for 30 min).
     */
    private function token(): string
    {
        return Cache::remember('openprovider_token', 1800, function () {
            $response = Http::post("{$this->apiUrl}/auth/login", [
                'username' => $this->username,
                'password' => $this->password,
            ]);

            if (!$response->successful()) {
                Log::error('OpenProvider auth failed', ['body' => $response->body()]);
                throw new \RuntimeException('OpenProvider authentication failed');
            }

            return $response->json('data.token');
        });
    }

    /**
     * Authenticated API call.
     */
    private function call(string $method, string $endpoint, array $data = []): array
    {
        $url = "{$this->apiUrl}/{$endpoint}";
        $token = $this->token();

        $response = Http::withToken($token)
            ->timeout(30)
            ->$method($url, $data);

        // Token expired – refresh once
        if ($response->status() === 401) {
            Cache::forget('openprovider_token');
            $token = $this->token();
            $response = Http::withToken($token)
                ->timeout(30)
                ->$method($url, $data);
        }

        $body = $response->json();

        if (!$response->successful() || ($body['code'] ?? 0) !== 0) {
            Log::error('OpenProvider API error', [
                'endpoint' => $endpoint,
                'code'     => $body['code'] ?? $response->status(),
                'desc'     => $body['desc'] ?? 'Unknown',
            ]);
        }

        return $body;
    }

    /**
     * Check domain availability.
     * Returns array of ['domain' => 'example.com', 'status' => 'free'|'active'|...]
     */
    public function checkDomain(string $sld, array $tlds): array
    {
        $domains = [];
        foreach ($tlds as $tld) {
            $domains[] = [
                'name'      => $sld,
                'extension' => ltrim($tld, '.'),
            ];
        }

        $result = $this->call('post', 'domains/check', [
            'domains' => $domains,
        ]);

        $items = [];
        foreach ($result['data']['results'] ?? [] as $r) {
            $ext = $r['domain']['extension'] ?? '';
            $items[] = [
                'domain'  => $sld . '.' . $ext,
                'tld'     => '.' . $ext,
                'status'  => $r['status'] ?? 'unknown',  // free, active
            ];
        }

        return $items;
    }

    /**
     * Register a domain.
     */
    public function registerDomain(array $params): array
    {
        $ns = config('openprovider.default_ns');

        $data = [
            'domain'      => [
                'name'      => $params['sld'],
                'extension' => ltrim($params['tld'], '.'),
            ],
            'period'      => $params['years'] ?? 1,
            'owner_handle'   => $params['owner_handle'],
            'admin_handle'   => $params['admin_handle'] ?? $params['owner_handle'],
            'tech_handle'    => $params['tech_handle'] ?? $params['owner_handle'],
            'billing_handle' => $params['billing_handle'] ?? $params['owner_handle'],
            'name_servers'   => array_map(fn($n) => ['name' => $n], $ns),
            'autorenew'      => 'off',
        ];

        return $this->call('post', 'domains', $data);
    }

    /**
     * Create a WHOIS contact handle.
     */
    public function createContact(array $details): ?string
    {
        $data = [
            'name' => [
                'first_name' => $details['first_name'],
                'last_name'  => $details['last_name'],
            ],
            'phone' => [
                'country_code' => $details['phone_cc'] ?? '+880',
                'area_code'    => '',
                'subscriber_number' => $details['phone'] ?? '',
            ],
            'address' => [
                'street'  => $details['address'] ?? '',
                'number'  => '',
                'zipcode' => $details['postal_code'] ?? '',
                'city'    => $details['city'] ?? '',
                'state'   => $details['state'] ?? '',
                'country' => $details['country_code'] ?? 'BD',
            ],
            'email' => $details['email'],
        ];

        $result = $this->call('post', 'customers', $data);

        return $result['data']['handle'] ?? null;
    }

    /**
     * Get domain details from OpenProvider.
     */
    public function getDomain(int $domainId): array
    {
        return $this->call('get', "domains/{$domainId}");
    }

    /**
     * Renew domain.
     */
    public function renewDomain(int $domainId, int $years = 1): array
    {
        return $this->call('post', "domains/{$domainId}/renew", [
            'period' => $years,
        ]);
    }

    /**
     * Transfer domain.
     */
    public function transferDomain(array $params): array
    {
        $ns = config('openprovider.default_ns');

        $data = [
            'domain'      => [
                'name'      => $params['sld'],
                'extension' => ltrim($params['tld'], '.'),
            ],
            'period'         => $params['years'] ?? 1,
            'auth_code'      => $params['auth_code'] ?? '',
            'owner_handle'   => $params['owner_handle'],
            'admin_handle'   => $params['admin_handle'] ?? $params['owner_handle'],
            'tech_handle'    => $params['tech_handle'] ?? $params['owner_handle'],
            'billing_handle' => $params['billing_handle'] ?? $params['owner_handle'],
            'name_servers'   => array_map(fn($n) => ['name' => $n], $ns),
        ];

        return $this->call('post', 'domains/transfer', $data);
    }

    /**
     * Full registration flow after payment.
     */
    public function provisionDomain(array $params): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'OpenProvider not configured'];
        }

        try {
            // Create contact handle
            $handle = $this->createContact($params['contact']);

            if (!$handle) {
                return ['success' => false, 'error' => 'Failed to create contact handle'];
            }

            // Register domain
            $result = $this->registerDomain([
                'sld'          => $params['sld'],
                'tld'          => $params['tld'],
                'years'        => $params['years'] ?? 1,
                'owner_handle' => $handle,
            ]);

            $domainId = $result['data']['id'] ?? null;

            if ($domainId) {
                return [
                    'success'   => true,
                    'domain_id' => $domainId,
                    'status'    => $result['data']['status'] ?? 'PEN',
                ];
            }

            return [
                'success' => false,
                'error'   => $result['desc'] ?? 'Unknown error',
            ];
        } catch (\Throwable $e) {
            Log::error('Domain provisioning failed', [
                'domain' => ($params['sld'] ?? '') . ($params['tld'] ?? ''),
                'error'  => $e->getMessage(),
            ]);
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
