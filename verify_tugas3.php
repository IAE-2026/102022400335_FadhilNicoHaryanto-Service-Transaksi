<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

use GuzzleHttp\Client;

echo "1. Meminta JWT Token dari IAE SSO...\n";
$client = new Client(['verify' => false]);
try {
    $response = $client->post('https://iae-sso.virtualfri.id/api/v1/auth/token', [
        'json' => [
            'email' => 'warga09@ktp.iae.id',
            'password' => 'KtpDigital2026!'
        ]
    ]);
    $data = json_decode($response->getBody()->getContents(), true);
    $jwt = $data['token'] ?? null;
    
    if (!$jwt) {
        echo "Gagal: Token tidak ditemukan dalam response SSO.\n";
        exit(1);
    }
    
    $payload = json_decode(base64_decode(explode('.', $jwt)[1]), true);
    echo "JWT Payload:\n";
    print_r($payload);
    
    echo "Sukses: Mendapatkan JWT Token! (Token: " . substr($jwt, 0, 30) . "...)\n\n";
} catch (\Exception $e) {
    echo "Gagal login SSO: " . $e->getMessage() . "\n";
    echo "Mencoba dengan password alternatif...\n";
    try {
        $response = $client->post('https://iae-sso.virtualfri.id/api/v1/auth/token', [
            'json' => [
                'email' => 'warga09@ktp.iae.id',
                'password' => 'KtpDIgital2026!'
            ]
        ]);
        $data = json_decode($response->getBody()->getContents(), true);
        $jwt = $data['token'] ?? null;
        if (!$jwt) throw new Exception("Token tetap null");
        echo "Sukses dengan password alternatif! (Token: " . substr($jwt, 0, 30) . "...)\n\n";
    } catch (\Exception $e2) {
        echo "Gagal total login SSO: " . $e2->getMessage() . "\n";
        exit(1);
    }
}

echo "2. Mensimulasikan POST /api/v1/transactions ke layanan lokal...\n";
$request = Illuminate\Http\Request::create('/api/v1/transactions', 'POST', [
    'sender' => 'Nico',
    'receiver' => 'Sistem IAE',
    'amount' => 500000
]);
$request->headers->set('Authorization', 'Bearer ' . $jwt);
$request->headers->set('Accept', 'application/json');

$response = $kernel->handle($request);

echo "Status HTTP Response: " . $response->getStatusCode() . "\n";
echo "Response Body:\n";
echo json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT) . "\n\n";

if ($response->getStatusCode() == 201) {
    echo "✅ SUKSES! Endpoint berhasil diamankan dengan SSO dan transaksi terekam.\n";
    $json = json_decode($response->getContent(), true);
    if (!empty($json['data']['audit_receipt'])) {
        echo "✅ SUKSES! Terhubung dengan Legacy Audit (SOAP). Diterima Receipt: " . $json['data']['audit_receipt'] . "\n";
        echo "✅ SUKSES! Jika Receipt muncul, maka M2M Token dan RabbitMQ Bridge juga berhasil dieksekusi!\n";
    } else {
        echo "❌ GAGAL: audit_receipt kosong. Komunikasi SOAP / M2M Token mungkin gagal.\n";
    }
} else {
    echo "❌ GAGAL: Transaksi gagal dieksekusi.\n";
}

$kernel->terminate($request, $response);
