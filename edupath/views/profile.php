<?php
session_start();
require_once __DIR__ . '/../models/UserProfile.php';

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
	echo "You need to log in first.";
	exit();
}

$userProfile = new UserProfile();
$userId = $_SESSION['user_id'];

// Fetch user profile data
$profile = $userProfile->getProfile($userId);

// Display user profile information
if ($profile) {
	echo "<h1>Welcome, " . htmlspecialchars($profile['name']) . "</h1>";
	//echo "<p><strong>Bio:</strong> " . htmlspecialchars($profile['bio']) . "</p>";
	echo "<p><strong>Field of Interest:</strong> " . htmlspecialchars($profile['field_of_interest']) . "</p>";
} else {
	echo "Profile not found.";
}
?>

<a href="edit_profile.php">Edit Profile</a> <!-- If you want to give users the ability to edit their profile -->