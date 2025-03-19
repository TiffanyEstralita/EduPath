<?php
require '../config/database.php';  // Import database connection
$datasets = require '../config/dataset.php';  // Load dataset URLs

foreach ($datasets as $institution => $url) {
	echo "Fetching data for: $institution\n";  // For debugging

	$response = file_get_contents($url);
	$data = json_decode($response, true);

	if (isset($data['result']['records'])) {
		foreach ($data['result']['records'] as $record) {
			$courseName = $record['course_name'] ?? 'Unknown';
			$cost = isset($record['cost']) ? (float) $record['cost'] : 0.00;
			$mode = strtolower($record['mode_of_study'] ?? 'unknown');

			// Normalize mode of study
			if (in_array($mode, ['ft', 'full-time'])) {
				$mode = 'Full-time';
			} elseif (in_array($mode, ['pt', 'part-time'])) {
				$mode = 'Part-time';
			} else {
				$mode = 'Unknown';
			}

			// Insert into database
			$stmt = $pdo->prepare("INSERT INTO courses (course_name, institution, cost, mode_of_study, source_data) 
                                   VALUES (:course_name, :institution, :cost, :mode_of_study, :source_data)");
			$stmt->execute([
				':course_name' => $courseName,
				':institution' => $institution,
				':cost' => $cost,
				':mode_of_study' => $mode,
				':source_data' => json_encode($record)
			]);
		}
		echo "Data for $institution imported successfully!\n";
	} else {
		echo "No records found for $institution.\n";
	}
}

echo "All data imported successfully!";
