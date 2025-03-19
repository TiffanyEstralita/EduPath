<?php
session_start();
require_once __DIR__ . '/../models/UserProfile.php';
require_once __DIR__ . '/../models/RecommendationModel.php';

if (!isset($_SESSION['user_id'])) {
	echo "You need to log in first.";
	exit();
}

$userProfile = new UserProfile();
$userId = $_SESSION['user_id'];
$profile = $userProfile->getProfile($userId);

if (!$profile) {
	echo "Profile not found.";
	exit();
}

$userField = $profile['field_name'] ?? '';
$recommended = RecommendationModel::recommendPrograms($userField);
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