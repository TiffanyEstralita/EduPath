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
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			curl_close($ch);

			// Check if the response is valid and decode the JSON
			$data = json_decode($response, true);
			if ($data && isset($data['result']['records'])) {
				foreach ($data['result']['records'] as $record) {
					$programs[] = [
						'name' => $record['course_name'] ?? 'Unknown',
						'description' => $record['course_description'] ?? 'No description available',
						'provider' => $provider,
						'cost' => $record['course_fees'] ?? 'N/A',
						'mode' => $record['study_mode'] ?? 'Unknown'
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
