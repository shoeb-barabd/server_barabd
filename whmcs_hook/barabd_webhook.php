<?php
/**
 * WHMCS Hook — sends status updates to Laravel webhook endpoint.
 *
 * Installation:
 *   1. Copy this file to:  WHMCS_ROOT/includes/hooks/barabd_webhook.php
 *   2. Set the $webhookUrl and $webhookSecret below.
 *   3. Done! WHMCS will call this hook automatically on service events.
 */

// ── Configure these ─────────────────────────────────────────
$webhookUrl    = 'https://server.barabdonline.xyz/api/whmcs/webhook';
$webhookSecret = '';   // <-- same value as WHMCS_WEBHOOK_SECRET in .env
// ─────────────────────────────────────────────────────────────

/**
 * Send a POST to the Laravel endpoint.
 */
function barabd_send_webhook($event, $serviceId, $status) {
    global $webhookUrl, $webhookSecret;

    if (empty($webhookUrl) || empty($webhookSecret)) return;

    $postData = http_build_query([
        'secret'     => $webhookSecret,
        'event'      => $event,
        'service_id' => $serviceId,
        'status'     => $status,
    ]);

    $ch = curl_init($webhookUrl);
    curl_setopt_array($ch, [
        CURLOPT_POST           => true,
        CURLOPT_POSTFIELDS     => $postData,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT        => 10,
        CURLOPT_HTTPHEADER     => ['Content-Type: application/x-www-form-urlencoded'],
    ]);
    curl_exec($ch);
    curl_close($ch);
}

// ── Hook: After module creates an account ──
add_hook('AfterModuleCreate', 1, function($vars) {
    barabd_send_webhook('AfterModuleCreate', $vars['params']['serviceid'] ?? 0, 'Active');
});

// ── Hook: After module suspends ──
add_hook('AfterModuleSuspend', 1, function($vars) {
    barabd_send_webhook('AfterModuleSuspend', $vars['params']['serviceid'] ?? 0, 'Suspended');
});

// ── Hook: After module unsuspends ──
add_hook('AfterModuleUnsuspend', 1, function($vars) {
    barabd_send_webhook('AfterModuleUnsuspend', $vars['params']['serviceid'] ?? 0, 'Active');
});

// ── Hook: After module terminates ──
add_hook('AfterModuleTerminate', 1, function($vars) {
    barabd_send_webhook('AfterModuleTerminate', $vars['params']['serviceid'] ?? 0, 'Terminated');
});

// ── Hook: Service cancelled ──
add_hook('CancellationRequest', 1, function($vars) {
    barabd_send_webhook('CancellationRequest', $vars['relid'] ?? 0, 'Cancelled');
});
