<?php
session_start();
require_once __DIR__ . '/../models/UserProfile.php';

if (!isset($_SESSION['user_id'])) {
	echo "You need to log in first.";
	exit();
}

$userProfile = new UserProfile();
$userId = $_SESSION['user_id'];

// Fetch user profile data
$profile = $userProfile->getProfile($userId);
$fields = $userProfile->getFieldsOfInterest();
$educationLevels = $userProfile->getEducationLevels();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$fieldOfInterestId = $_POST['field_of_interest'];
	$educationLevelId = $_POST['education_level'];
	$careerAspiration = $_POST['career_aspiration'];

	// Update user profile (don't need to update name)
	$success = $userProfile->updateProfile($userId, $profile['name'], $fieldOfInterestId, $educationLevelId, $careerAspiration);

	if ($success) {
		echo "Profile updated successfully!";
		header("Location: profile.php");  // Redirect after success
		exit();
	} else {
		echo "Failed to update profile.";
	}
}
?>

<form method="POST" action="complete_profile.php">
	<label for="name">Name:</label>
	<input type="text" value="<?= htmlspecialchars($profile['name']) ?>" disabled> <!-- Display name but make it non-editable -->

	<label for="field_of_interest">Field of Interest:</label>
	<select name="field_of_interest" required>
		<?php foreach ($fields as $field): ?>
			<option value="<?= $field['id'] ?>"><?= htmlspecialchars($field['field_name']) ?></option>
		<?php endforeach; ?>
	</select>

	<label for="education_level">Education Level:</label>
	<select name="education_level" required>
		<option value="" disabled selected>Select your education level</option>
		<?php foreach ($educationLevels as $level): ?>
			<option value="<?= $level['id'] ?>"><?= htmlspecialchars($level['education_level']) ?></option>
		<?php endforeach; ?>
	</select>

	<label for="career_aspiration">Career Aspirations:</label>
	<input type="text" name="career_aspiration" value="<?= htmlspecialchars($profile['career_aspiration'] ?? '') ?>" required>

	<button type="submit">Update Profile</button>
</form>