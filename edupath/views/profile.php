<?php
session_start();
require_once __DIR__ . '/../models/UserProfile.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
	echo "You need to log in first.";
	exit();
}

$userProfile = new UserProfile();
$userId = $_SESSION['user_id'];  // Using the user_id stored in the session

// Fetch user profile data along with the field name from field_of_interest table
$profile = $userProfile->getProfile($userId);

// Check if the profile data was fetched successfully
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Handle the profile update
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$fieldOfInterestId = filter_input(INPUT_POST, 'field_of_interest', FILTER_VALIDATE_INT);
	$educationLevelId = filter_input(INPUT_POST, 'education_level', FILTER_VALIDATE_INT);
	$careerAspiration = filter_input(INPUT_POST, 'career_aspiration', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	if (!empty($name) && $fieldOfInterestId && $educationLevelId && !empty($careerAspiration)) {
		// Update the profile in the database
		if ($userProfile->updateProfile($userId, $name, $fieldOfInterestId, $educationLevelId, $careerAspiration)) {
			echo "Profile updated successfully!";
			// Redirect to home page after successful update
			header("Location: profile.php");  // Change to your home page URL
			exit();  // Make sure no further code is executed after the redirect
		} else {
			echo "Failed to update profile.";
		}
	} else {
		echo "Please fill in all fields correctly.";
	}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Your Profile</title>
	<link rel="stylesheet" href="styles.css">
</head>

<body>

	<h1>Your Profile</h1>

	<?php if ($_SERVER['REQUEST_METHOD'] !== 'POST'): ?>
		<!-- Display Profile Information -->
		<p><strong>Name:</strong> <?= htmlspecialchars($profile['name'] ?? '') ?></p>
		<p><strong>Email:</strong> <?= htmlspecialchars($profile['email'] ?? '') ?></p>

		<p><strong>Field of Interest:</strong> <?= htmlspecialchars($profile['field_name'] ?? 'Not set') ?></p>
		<p><strong>Education Level:</strong> <?= htmlspecialchars($profile['education_level'] ?? 'Not set') ?></p>
		<p><strong>Career Aspiration:</strong> <?= htmlspecialchars($profile['career_aspiration'] ?? 'Not set') ?></p>

		<!-- Edit Profile Button -->
		<form method="POST" action="profile.php">
			<button type="submit">Edit Profile</button>
		</form>

	<?php else: ?>
		<!-- Edit Profile Form -->
		<form method="POST" action="profile.php">
			<label for="name">Name:</label>
			<input type="text" name="name" value="<?= htmlspecialchars($profile['name'] ?? '') ?>" required>

			<label for="email">Email (cannot be changed):</label>
			<input type="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" disabled>

			<label for="field_of_interest">Field of Interest:</label>
			<select name="field_of_interest" required>
				<?php foreach ($userProfile->getFieldsOfInterest() as $field): ?>
					<option value="<?= $field['id'] ?>" <?= ($field['field_name'] == $profile['field_name']) ? 'selected' : '' ?>>
						<?= htmlspecialchars($field['field_name']) ?>
					</option>
				<?php endforeach; ?>
			</select>

			<label for="education_level">Education Level:</label>
			<select name="education_level" required>
				<?php foreach ($userProfile->getEducationLevels() as $level): ?>
					<option value="<?= $level['id'] ?>" <?= ($level['education_level'] == $profile['education_level']) ? 'selected' : '' ?>>
						<?= htmlspecialchars($level['education_level']) ?>
					</option>
				<?php endforeach; ?>
			</select>

			<label for="career_aspiration">Career Aspiration:</label>
			<input type="text" name="career_aspiration" value="<?= htmlspecialchars($profile['career_aspiration'] ?? '') ?>" required>

			<button type="submit">Update Profile</button>
		</form>
	<?php endif; ?>

	<a href="RecommendProgram.php">View Recommended Programs</a>

</body>

</html>