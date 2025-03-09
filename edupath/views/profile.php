<?php
session_start();
require_once __DIR__ . '/../models/UserProfile.php';

// Debugging session variables
var_dump($_SESSION);  // Check if session variables are set

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
	echo "You need to log in first.";
	exit();
}

$userProfile = new UserProfile();
$userId = $_SESSION['user_id'];  // Using the user_id stored in the session

// Fetch user profile data along with the field name from field_of_interest table
$profile = $userProfile->getProfile($userId); // Use getProfile() instead of getFieldsOfInterest()

// Display user profile information
if ($profile) {
	echo "<h1>Welcome, " . htmlspecialchars($profile['name'] ?? '') . "</h1>"; // Ensure name is set
	echo "<p><strong>Email:</strong> " . htmlspecialchars($profile['email'] ?? '') . "</p>"; // Ensure email is set

	// Check if field_name exists and is not null
	$fieldOfInterest = isset($profile['field_name']) && !is_null($profile['field_name'])
		? htmlspecialchars($profile['field_name'])
		: 'Not set';

	echo "<p><strong>Field of Interest:</strong> " . $fieldOfInterest . "</p>";
} else {
	echo "Profile not found.";
}
?>
<a href="recommendations.php">View Recommended Programs</a>