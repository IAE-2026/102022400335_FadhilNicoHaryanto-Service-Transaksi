<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class IaeIntegrationService
{
    protected $client;
    protected $ssoUrl;
    protected $apiKey;
    protected $teamId;

    public function __construct()
    {
        $this->client = new Client();
        $this->ssoUrl = env('IAE_SSO_URL', 'https://iae-sso.virtualfri.id');
        $this->apiKey = env('IAE_API_KEY', 'KEY-MHS-432');
        $this->teamId = env('IAE_TEAM_ID', 'TEAM-04');
    }

    public function getM2MToken()
    {
        try {
            $response = $this->client->post("{$this->ssoUrl}/api/v1/auth/token", [
                'json' => [
                    'api_key' => $this->apiKey
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $data['token'] ?? null;
        } catch (\Exception $e) {
            Log::error("Failed to get M2M token: " . $e->getMessage());
            return null;
        }
    }

    public function sendAudit($transaction)
    {
        $token = $this->getM2MToken();
        if (!$token) return null;

        $logContent = json_encode([
            'sender' => $transaction->sender,
            'receiver' => $transaction->receiver,
            'amount' => $transaction->amount,
            'status' => $transaction->status,
            'transaction_date' => $transaction->transaction_date
        ]);

        $xml = <<<XML
<?xml version="1.0" encoding="utf-8"?>
<soap:Envelope xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/" xmlns:iae="http://iae.central.audit">
  <soap:Body>
    <iae:AuditRequest>
      <iae:TeamID>{$this->teamId}</iae:TeamID>
      <iae:ActivityName>TransactionCreated</iae:ActivityName>
      <iae:LogContent><![CDATA[{$logContent}]]></iae:LogContent>
    </iae:AuditRequest>
  </soap:Body>
</soap:Envelope>
XML;

        try {
            $response = $this->client->post("{$this->ssoUrl}/soap/v1/audit", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Content-Type' => 'text/xml'
                ],
                'body' => $xml
            ]);

            $xmlResponse = $response->getBody()->getContents();
            
            $receiptNumber = null;
            if (preg_match('/<iae:ReceiptNumber>(.*?)<\/iae:ReceiptNumber>/s', $xmlResponse, $matches)) {
                $receiptNumber = $matches[1];
            }
            
            return $receiptNumber;

        } catch (\Exception $e) {
            Log::error("Failed to send SOAP Audit: " . $e->getMessage());
            return null;
        }
    }

    public function publishMessage($transaction)
    {
        $token = $this->getM2MToken();
        if (!$token) return false;

        $user = auth()->user();
        $roleName = $user ? \App\Models\Role::find($user->role_id)->name ?? 'warga' : 'warga';

        $payload = [
            'message' => [
                'event' => 'TransactionCreated',
                'team_id' => $this->teamId,
                'data' => [
                    'id' => $transaction->id,
                    'sender' => $transaction->sender,
                    'receiver' => $transaction->receiver,
                    'amount' => $transaction->amount,
                    'status' => $transaction->status,
                    'legacy_receipt_number' => $transaction->audit_receipt,
                    'approved_by' => $user ? [
                        'sso_subject' => $user->email,
                        'roles' => [ $roleName ]
                    ] : null
                ]
            ]
        ];

        try {
            $response = $this->client->post("{$this->ssoUrl}/api/v1/messages/publish", [
                'headers' => [
                    'Authorization' => "Bearer {$token}",
                    'Accept' => 'application/json'
                ],
                'json' => $payload
            ]);

            return $response->getStatusCode() === 200 || $response->getStatusCode() === 201;
        } catch (\Exception $e) {
            Log::error("Failed to publish AMQP message: " . $e->getMessage());
            return false;
        }
    }
}
