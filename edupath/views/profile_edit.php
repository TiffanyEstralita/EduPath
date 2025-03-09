<?php
session_start();
require_once __DIR__ . '/../controllers/UserProfile.php';

$userId = $_SESSION['user_id'];
$userProfile = new UserProfile();
$profile = $userProfile->getProfile($userId);
$fields = $userProfile->getFieldsOfInterest();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$fieldOfInterestId = filter_input(INPUT_POST, 'field_of_interest', FILTER_VALIDATE_INT);

	if (!empty($name) && $fieldOfInterestId) {
		if ($userProfile->updateProfile($userId, $name, $fieldOfInterestId, $educationLevelId, $careerAspiration)) {
			echo "Profile updated successfully!";
		} else {
			echo "Failed to update profile.";
		}
	} else {
		echo "Please fill in all fields correctly.";
	}
}
?>

<form method="POST" action="profile_edit.php">
	<label for="name">Name:</label>
	<input type="text" name="name" value="<?= htmlspecialchars($profile['name']) ?>" required>

	<label for="email">Email (cannot be changed):</label>
	<input type="email" value="<?= htmlspecialchars($profile['email']) ?>" disabled>

	<label for="field_of_interest">Field of Interest:</label>
	<select name="field_of_interest" required>
		<?php foreach ($fields as $field): ?>
			<option value="<?= $field['id'] ?>" <?= ($field['field_name'] == $profile['field_name']) ? 'selected' : '' ?>>
				<?= htmlspecialchars($field['field_name']) ?>
			</option>
		<?php endforeach; ?>
	</select>

	<button type="submit">Update Profile</button>
</form>