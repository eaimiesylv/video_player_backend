<?php

namespace App\Services;

use GuzzleHttp\Client;

class GPTService
{
    public function askAi($req)
    {


        $prompt = $this->instructions($req);
        try {
            $response = $this->sendGPTRequest($prompt);

            $responseData = json_decode($response->getBody()->getContents(), true);

            if ($this->isValidResponse($responseData)) {
                return $this->extractTextFromResponse($responseData);
            } else {
                return 1;
            }
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            return 2;
        } catch (\Exception $e) {
            return "Error: " . $e->getMessage();
        }
    }
    private function instructions($request)
    {
        $user = auth()->user(); // Fetch user data once to avoid repetitive calls

        // Begin constructing the prompt with user and request data
        $prompt = "recommend a solution to this". $request . " in a professional tone.";
        $prompt .= " The patient's sex is " . $user->sex . ".";
        $prompt .= " The patient's date of birth is " . $user->dob . ".";

        // Clarifying instructions for response handling
        $prompt .= " Note: Don't ask for clarity, make a short recommendation to the problem for not more than 200 words.";
        // Finalizing language and ensuring proper formatting
        $prompt .= " Return the response in standard English text with proper punctuation and clarity.";
        $prompt .= " Avoid the use of JSON responses and Ensure the output is conversational and easily understood by both the patient and the doctor. Also";
        $prompt .= "avoid returning the patient date of birth and sex in the response. These details are provided for diagnosis purpose";

        return $prompt;
    }


    private function sendGPTRequest($prompt)
    {
        $client = new Client(['verify' => false]);

        return $client->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyBnuTAPRe6FyPPM_gMiNoJrIPk-HwFs43U', [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'contents' => [
                    'parts' => [
                        [
                            'text' => $prompt,
                        ],
                    ],
                ],
            ],
        ]);
    }

    private function isValidResponse($responseData)
    {
        return isset($responseData['candidates']) && !empty($responseData['candidates']);
    }

    private function extractTextFromResponse($responseData)
    {
        $firstCandidate = $responseData['candidates'][0] ?? null;

        if ($firstCandidate && isset($firstCandidate['content']['parts'])) {
            return $firstCandidate['content']['parts'][0]['text'];
        } else {
            return "Error: No 'parts' found in the response.";
        }
    }
}
