<?php
function fetchEducationalPrograms()
{
	$datasets = require __DIR__ . '/../config/datasets.php';

	$programs = [];
	foreach ($datasets as $provider => $url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($ch);
		curl_close($ch);

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
	return $programs;
}
