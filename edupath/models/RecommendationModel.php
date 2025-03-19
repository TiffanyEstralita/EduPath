<?php

class RecommendationModel
{
    public static function recommendPrograms($userField)
    {
        // Fetch educational programs using the datasets.php URLs
        $datasets = require __DIR__ . '/../config/datasets.php';

        $programs = [];
        foreach ($datasets as $provider => $url) {
            // Initialize cURL
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);

            // Check for cURL errors
            if (curl_errno($ch)) {
                echo 'cURL Error: ' . curl_error($ch);
                curl_close($ch);
                continue; // Skip to the next dataset
            }

            curl_close($ch);

            // Decode the JSON response
            $data = json_decode($response, true);

            // Check if the expected data exists
            if (isset($data['result']['records'])) {
                foreach ($data['result']['records'] as $record) {
                    $programs[] = [
                        'name' => $record['degree'] ?? 'Unknown',
                        'description' => $record['school'] ?? 'No description available',
                        'provider' => $provider,
                        'cost' => $record['gross_monthly_mean'] ?? 'N/A',
                        'mode' => $record['employment_rate_overall'] ?? 'Unknown'
                    ];
                }
            }
        }

        // Filter programs based on the user's field of interest
        $filteredPrograms = array_filter($programs, function ($program) use ($userField) {
            return stripos($program['name'], $userField) !== false;
        });

        return array_values($filteredPrograms);
    }
}