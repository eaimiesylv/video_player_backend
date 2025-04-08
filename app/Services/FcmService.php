<?php

namespace App\Services;

use Google\Client;
use GuzzleHttp\Client as HttpClient;

class FcmService
{
    protected $client;
    protected $serviceAccountPath;

    public function __construct()
    {
        // Path to your service account JSON file
        $this->serviceAccountPath = base_path('gofirebase.json');

        // Initialize Google Client
        $this->client = new Client();
        $this->client->setAuthConfig($this->serviceAccountPath);
        $this->client->addScope('https://www.googleapis.com/auth/firebase.messaging');
    }

    /**
     * Get an OAuth 2.0 access token.
     *
     * @return string
     */
    protected function getAccessToken()
    {
        $accessToken = $this->client->fetchAccessTokenWithAssertion();
        return $accessToken['access_token'];
    }


    public function sendNotification($token, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();
        $projectId = env('FIREBASE_PROJECT_ID'); // Your Firebase Project ID

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $message = [
            'message' => [
                'token' => $token,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
            ],
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ];

        $httpClient = new HttpClient();
        $response = $httpClient->post($url, [
            'headers' => $headers,
            'json' => $message,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function sendMulticastNotification(array $tokens, $title, $body, $data = [])
    {

        $accessToken = $this->getAccessToken();

        $projectId = env('FIREBASE_PROJECT_ID');
        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";
        //dd($data);

        $responses = [];
        foreach ($tokens as $token) {
            try {
                $message = [
                    'message' => [
                        'notification' => [
                            'title' => $title,
                            'body' => $body,
                        ],
                        'data' => $data,
                        'token' => $token,  // Send to each token individually
                    ]
                ];

                $headers = [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ];

                $httpClient = new HttpClient();
                $response = $httpClient->post($url, [
                    'headers' => $headers,
                    'json' => $message,
                ]);
                $responseBody = json_decode($response->getBody()->getContents(), true);

                // Check for invalid token errors in the response
                if (isset($responseBody['error']) && $responseBody['error']['message'] === 'InvalidRegistration') {
                    // Delete the invalid token
                    $this->destroy($token);
                }

                $responses[] = $responseBody;
            } catch (\Exception $e) {
                // Log the exception or handle it as necessary
                \Log::error("Failed to send notification to token {$token}: " . $e->getMessage());
            }
        }

        return $responses;
    }

    public function destroy($token)
    {
        $model = \App\Models\Fcm::where("token", $token)->first();
        if ($model) {
            $model->delete();
        }
        return $model;
    }



    public function sendTopicNotification($topic, $title, $body, $data = [])
    {
        $accessToken = $this->getAccessToken();
        $projectId = env('FIREBASE_PROJECT_ID');

        $url = "https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send";

        $message = [
            'message' => [
                'topic' => $topic,
                'notification' => [
                    'title' => $title,
                    'body' => $body,
                ],
                'data' => $data,
            ],
        ];

        $headers = [
            'Authorization' => 'Bearer ' . $accessToken,
            'Content-Type' => 'application/json',
        ];

        $httpClient = new HttpClient();
        $response = $httpClient->post($url, [
            'headers' => $headers,
            'json' => $message,
        ]);

        return json_decode($response->getBody()->getContents(), true);
    }
}
