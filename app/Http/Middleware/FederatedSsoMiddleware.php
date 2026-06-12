<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Firebase\JWT\JWT;
use Firebase\JWT\JWK;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class FederatedSsoMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Bearer token is missing.',
            ], 401);
        }

        try {
            $jwksUri = env('IAE_SSO_URL', 'https://iae-sso.virtualfri.id') . '/api/v1/auth/jwks';
            
            // Cache JWKS for 1 hour to avoid hitting SSO server on every request
            $jwks = Cache::remember('sso_jwks', 3600, function () use ($jwksUri) {
                $client = new Client();
                $response = $client->get($jwksUri);
                return json_decode($response->getBody()->getContents(), true);
            });

            $keys = JWK::parseKeySet($jwks);
            
            // Decode the token using the parsed keys
            $decoded = JWT::decode($token, $keys);
            
            // Extract user info from payload
            $email = $decoded->profile->email ?? $decoded->sub ?? null;
            
            if (!$email) {
                 return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid token payload. Email missing.',
                ], 401);
            }

            // Memetakan user ke tabel roles lokal
            $roleName = $decoded->role ?? 'warga'; 
            $role = Role::firstOrCreate(['name' => $roleName]);

            $name = $decoded->profile->name ?? explode('@', $email)[0];

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $name,
                    'password' => bcrypt(str()->random(16)),
                    'role_id' => $role->id
                ]
            );
            
            if ($user->role_id !== $role->id) {
                $user->role_id = $role->id;
                $user->save();
            }

            // Set the authenticated user
            Auth::login($user);

            return $next($request);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. Invalid token: ' . $e->getMessage(),
            ], 401);
        }
    }
}
