<?php
session_start();
require_once __DIR__ . '/../models/UserProfile.php';

if (!isset($_SESSION['username'])) {
	echo "You need to register first.";
	exit();
}

$userProfile = new UserProfile();

// Fetch available fields of interest
$fields = $userProfile->getFieldsOfInterest();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = $_POST['name'];
	$fieldOfInterestId = $_POST['field_of_interest'];
	$userId = $_SESSION['user_id']; // Get user ID from session

	// Debug: check if the session user_id is set correctly
	echo "User ID: " . $userId;

	$success = $userProfile->updateProfile($userId, $name, $fullname,  $fieldOfInterestId);

	if ($success) {
		echo "Profile completed successfully!";
		header("Location: profile.php");  // Redirect after success
		exit();
	} else {
		echo "Failed to update profile.";
	}
}
?>

<form method="POST" action="complete_profile.php">
	<label for="name">Full Name:</label>
	<input type="text" name="name" required>

	<label for="field_of_interest">Field of Interest:</label>
	<select name="field_of_interest" required>
		<?php foreach ($fields as $field): ?>
			<option value="<?= $field['id'] ?>"><?= htmlspecialchars($field['field_name']) ?></option>
		<?php endforeach; ?>
	</select>

	<button type="submit">Complete Profile</button>
</form>