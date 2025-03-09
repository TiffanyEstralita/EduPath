<?php

function fetchEducationalPrograms()
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
		// Initialize cURL
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

function recommendPrograms($userField)
{
	// Fetch all programs
	$allPrograms = fetchEducationalPrograms();

	// Filter programs based on the user's field of interest
	$filteredPrograms = array_filter($allPrograms, function ($program) use ($userField) {
		return stripos($program['name'], $userField) !== false;
	});

	return array_values($filteredPrograms);
}

session_start();
require_once __DIR__ . '/../models/UserProfile.php';
require_once __DIR__ . '/../models/RecommendationModel.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
	echo "You need to log in first.";
	exit();
}

$userProfile = new UserProfile();
$userId = $_SESSION['user_id'];

// Fetch user profile data
$profile = $userProfile->getProfile($userId);

// Check if profile exists
if (!$profile) {
	echo "Profile not found.";
	exit();
}

$userField = $profile['field_name'] ?? ''; // Get the user's field of interest
$recommended = recommendPrograms($userField);

?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Recommended Educational Programs</title>
	<link rel="stylesheet" href="styles.css">
</head>

<body>
	<h2>Recommended Educational Programs</h2>
	<?php if (!empty($recommended)): ?>
		<ul>
			<?php foreach ($recommended as $program): ?>
				<li>
					<strong><?= htmlspecialchars($program['name']) ?></strong><br>
					<em><?= htmlspecialchars($program['provider']) ?></em><br>
					<?= htmlspecialchars($program['description']) ?><br>
					<strong>Cost:</strong> <?= htmlspecialchars($program['cost']) ?><br>
					<strong>Mode:</strong> <?= htmlspecialchars($program['mode']) ?>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php else: ?>
		<p>No recommended programs found.</p>
	<?php endif; ?>
</body>

</html>