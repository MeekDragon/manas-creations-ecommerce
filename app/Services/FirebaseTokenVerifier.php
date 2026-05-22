<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Exception;

class FirebaseTokenVerifier
{
    /**
     * Google's public x509 certificates URL for Firebase Auth ID tokens.
     */
    protected string $certsUrl = 'https://www.googleapis.com/robot/v1/metadata/x509/securetoken-system@system.gserviceaccount.com';

    /**
     * Decodes and verifies a Firebase ID Token (JWT).
     *
     * @param string $idToken
     * @param string|null $projectId Firebase Project ID (if null, falls back to config)
     * @return array|null The decoded token payload if valid, null otherwise
     */
    public function verify(string $idToken, ?string $projectId = null): ?array
    {
        $projectId = $projectId ?: config('services.firebase.project_id', env('FIREBASE_PROJECT_ID'));
        
        if (empty($projectId)) {
            // If project ID is not set, we cannot verify claims securely, but we can return decoded claims in local/dev
            if (app()->environment('local')) {
                \Illuminate\Support\Facades\Log::warning("Firebase ID Token decoded without Project ID validation because FIREBASE_PROJECT_ID is empty.");
                return $this->decodeWithoutVerification($idToken);
            }
            return null;
        }

        try {
            $parts = explode('.', $idToken);
            if (count($parts) !== 3) {
                return null;
            }

            [$headerB64, $payloadB64, $signatureB64] = $parts;

            $header = json_decode($this->base64UrlDecode($headerB64), true);
            $payload = json_decode($this->base64UrlDecode($payloadB64), true);

            if (!$header || !$payload) {
                return null;
            }

            // 1. Verify Alg in Header is RS256
            if (($header['alg'] ?? '') !== 'RS256') {
                return null;
            }

            // 2. Verify Key ID (kid) exists in header
            $kid = $header['kid'] ?? null;
            if (!$kid) {
                return null;
            }

            // 3. Verify standard JWT claims
            $now = time();

            // Expire check (exp)
            if (!isset($payload['exp']) || $payload['exp'] < ($now - 60)) { // 1 min clock skew
                return null;
            }

            // Issued At check (iat)
            if (!isset($payload['iat']) || $payload['iat'] > ($now + 60)) {
                return null;
            }

            // Audience check (aud) matches Firebase Project ID
            if (!isset($payload['aud']) || $payload['aud'] !== $projectId) {
                return null;
            }

            // Issuer check (iss) matches securetoken.google.com/project_id
            if (!isset($payload['iss']) || $payload['iss'] !== "https://securetoken.google.com/{$projectId}") {
                return null;
            }

            // Subject (sub) must be a non-empty string (Firebase User UID)
            if (empty($payload['sub'])) {
                return null;
            }

            // 4. Verify cryptographic signature against Google public keys
            $publicKeys = $this->getGooglePublicKeys();
            if (!isset($publicKeys[$kid])) {
                return null;
            }

            $publicKeyPem = $publicKeys[$kid];
            $dataToVerify = "{$headerB64}.{$payloadB64}";
            $signature = $this->base64UrlDecode($signatureB64);

            // Verify using openssl_verify
            $result = openssl_verify($dataToVerify, $signature, $publicKeyPem, OPENSSL_ALGO_SHA256);

            if ($result === 1) {
                return $payload;
            }

            // If signature verification fails but we are on local dev, check if mock bypass is active
            if (app()->environment('local') && env('FIREBASE_MOCK_VERIFY', false)) {
                \Illuminate\Support\Facades\Log::info("Firebase ID Token signature bypass active for local development.");
                return $payload;
            }

            return null;
        } catch (Exception $e) {
            \Illuminate\Support\Facades\Log::error("Firebase Token Verification Exception: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Helper to decode token payload directly without active validation (useful for test/dev)
     */
    public function decodeWithoutVerification(string $idToken): ?array
    {
        try {
            $parts = explode('.', $idToken);
            if (count($parts) >= 2) {
                return json_decode($this->base64UrlDecode($parts[1]), true);
            }
        } catch (Exception $e) {}
        return null;
    }

    /**
     * Retrieve Google public certificates, utilizing Laravel caching to avoid external requests.
     */
    protected function getGooglePublicKeys(): array
    {
        return Cache::remember('firebase_google_public_certs', 3600 * 12, function () {
            try {
                $response = Http::get($this->certsUrl);
                if ($response->successful()) {
                    return $response->json();
                }
            } catch (Exception $e) {
                \Illuminate\Support\Facades\Log::error("Failed to fetch Google public certs for Firebase: " . $e->getMessage());
            }
            return [];
        });
    }

    /**
     * Standard Base64Url decode helper.
     */
    protected function base64UrlDecode(string $input): string
    {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }
}
