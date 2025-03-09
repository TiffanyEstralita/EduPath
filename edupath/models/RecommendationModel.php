<?php

class RecommendationModel
{
	// Function to fetch educational programs from multiple datasets
	public function fetchEducationalPrograms()
	{
		$datasets = [
			'Singapore Polytechnic' => 'https://data.gov.sg/api/action/datastore_search?resource_id=d_aa3b89617725a58af2587ccea25e6950',
			'Temasek Polytechnic' => 'https://data.gov.sg/api/action/datastore_search?resource_id=d_b438fc00ee679a15980282fe1c7ca45d',
			'Nanyang Technological University' => 'https://data.gov.sg/api/action/datastore_search?resource_id=d_82988b8b68c649ad9e39f115559380c2', // Example
			'National University of Singapore' => 'https://data.gov.sg/api/action/datastore_search?resource_id=d_354c65ac58924c0b85013c2c3549e5a9', // Example
			'Singapore University of Technology and Design' => 'https://data.gov.sg/api/action/datastore_search?resource_id=d_50e8fc2285374050a5a74383e0e2c83e', // Example
		];

		$programs = [];
		foreach ($datasets as $provider => $url) {
			// Initialize cURL to fetch the dataset
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			$response = curl_exec($ch);
			curl_close($ch);

			// Check if the response is valid and decode the JSON
			$data = json_decode($response, true);
			if ($data && isset($data['result']['records'])) {
				// Iterate through records and collect course information
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

		return $programs;
	}

	// Function to recommend programs based on the user's field of interest
	public function recommendPrograms($userField)
	{
		// Fetch all programs from the datasets
		$allPrograms = $this->fetchEducationalPrograms();

		// Filter programs based on the user's field of interest
		$filteredPrograms = array_filter($allPrograms, function ($program) use ($userField) {
			return stripos($program['name'], $userField) !== false; // Case-insensitive matching
		});

		return array_values($filteredPrograms); // Re-index the array
	}
}
