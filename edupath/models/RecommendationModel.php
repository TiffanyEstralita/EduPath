<?php

class RecommendationModel
{
    public static function recommendPrograms($userField)
    {
        // Load dataset URLs from datasets.php
        $datasets = require __DIR__ . '/../config/datasets.php';

        $programs = [];

        foreach ($datasets as $provider => $url) {
            // Initialize cURL to fetch data from the API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            curl_close($ch);

            // Decode the JSON response
            $data = json_decode($response, true);

            // Check if the expected records are present
            if ($data && isset($data['result']['records'])) {
                foreach ($data['result']['records'] as $record) {
                    
                    // Standardized data structure
                    $programs[] = [
                        'name' => $record['course_name'] ?? $record['degree'] ?? 'Unknown Course', // Try different field names
                        'description' => $record['course_description'] ?? $record['school'] ?? 'No description available',
                        'provider' => $provider, // Use the key from datasets.php as the provider name
                        'cost' => $record['course_fees'] ?? $record['gross_monthly_mean'] ?? 'N/A',
                        'mode' => $record['study_mode'] ?? $record['employment_rate_overall'] ?? 'Unknown'
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

