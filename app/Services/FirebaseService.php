<?php

namespace App\Services;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    private $client;
    private $credentials;
    private $accessToken;

    public function __construct()
    {
        $this->client = new Client();
        $this->loadCredentials();
    }

    private function loadCredentials()
    {
        $path = base_path(env('FIREBASE_CREDENTIALS', 'storage/app/godevi-firebase-adminsdk.json'));
        if (file_exists($path)) {
            $this->credentials = json_decode(file_get_contents($path), true);
        } else {
            Log::error("Firebase credentials file not found at: {$path}");
        }
    }

    private function getAccessToken()
    {
        if ($this->accessToken) {
            return $this->accessToken;
        }

        if (!$this->credentials) {
             return null;
        }

        $now = time();
        $token = [
            "iss" => $this->credentials['client_email'],
            "scope" => "https://www.googleapis.com/auth/cloud-platform",
            "aud" => $this->credentials['token_uri'],
            "exp" => $now + 3600,
            "iat" => $now,
        ];

        $jwt = $this->generateJWT($token, $this->credentials['private_key']);

        try {
            $response = $this->client->post($this->credentials['token_uri'], [
                'form_params' => [
                    'grant_type' => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                    'assertion' => $jwt,
                ]
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            $this->accessToken = $data['access_token'];
            return $this->accessToken;

        } catch (\Exception $e) {
            Log::error("Failed to get Google Access Token: " . $e->getMessage());
            return null;
        }
    }

    // Manual JWT Generation using OpenSSL
    private function generateJWT($payload, $privateKey)
    {
        $header = [
            "alg" => "RS256",
            "typ" => "JWT"
        ];

        $headerEncoded = $this->base64UrlEncode(json_encode($header));
        $payloadEncoded = $this->base64UrlEncode(json_encode($payload));

        $dataToSign = "$headerEncoded.$payloadEncoded";
        $signature = '';

        openssl_sign($dataToSign, $signature, $privateKey, "SHA256");

        $signatureEncoded = $this->base64UrlEncode($signature);

        return "$dataToSign.$signatureEncoded";
    }

    private function base64UrlEncode($data)
    {
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($data));
    }


    /**
     * Save notification to Firestore using Authenticated REST API.
     */
    public function saveNotification($data)
    {
        $token = $this->getAccessToken();
        if (!$token || !isset($this->credentials['project_id'])) {
             Log::error('Firebase Access Token or Project ID missing.');
             return;
        }

        $projectId = $this->credentials['project_id'];
        $url = "https://firestore.googleapis.com/v1/projects/{$projectId}/databases/(default)/documents/notifications";
        
        $firestoreData = $this->toFirestoreFormat($data);

        try {
            $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token,
                    'Content-Type' => 'application/json',
                ],
                'json' => $firestoreData
            ]);
            Log::info("Notification saved to Firestore.");
        } catch (\Exception $e) {
            Log::error('Firestore Save Error: ' . $e->getMessage());
        }
    }

    /**
     * Send FCM message using HTTP v1 API.
     */
    public function sendFCM($topic, $title, $body, $data = [])
    {
        $token = $this->getAccessToken();
        if (!$token || !isset($this->credentials['project_id'])) {
             return;
        }
        
        $projectId = $this->credentials['project_id'];
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        // FCM V1 Payload Structure
        $payload = [
            'message' => [
                'topic' => $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => array_map('strval', $data) // Ensure all data values are strings for FCM
            ]
        ];

        try {
            $this->client->post($url, [
                'headers' => [
                    'Authorization' => 'Bearer ' . $token, // Use Bearer Token here
                    'Content-Type' => 'application/json',
                ],
                'json' => $payload
            ]);
            Log::info("FCM sent via V1 API.");
        } catch (\Exception $e) {
            Log::error('FCM Send Error: ' . $e->getMessage());
        }
    }

    // Helper to format data for Firestore REST API
    private function toFirestoreFormat($data)
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[$key] = $this->getValueType($value);
        }
        return ['fields' => (object)$fields];
    }

    private function getValueType($value)
    {
        if (is_int($value)) {
            return ['integerValue' => $value];
        } elseif (is_string($value)) {
            return ['stringValue' => $value];
        } elseif (is_bool($value)) {
            return ['booleanValue' => $value];
        } elseif (is_array($value)) {
             if ($this->isAssoc($value)) {
                return ['mapValue' => ['fields' => (object)$this->toFirestoreFormat($value)['fields']]];
            } else {
                 $values = [];
                foreach ($value as $item) {
                     $values[] = $this->getValueType($item);
                }
                return ['arrayValue' => ['values' => $values]];
            }
        } elseif ($value instanceof \DateTime) {
            return ['timestampValue' => $value->format('Y-m-d\TH:i:s\Z')];
        }
        return ['nullValue' => null];
    }

    private function isAssoc(array $arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
