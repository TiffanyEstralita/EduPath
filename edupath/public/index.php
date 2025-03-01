<?php
require_once __DIR__ . '/../controllers/UserProfileController.php';

$controller = new UserProfileController();

// Example: Viewing a profile
$userId = 1; // Replace with the actual user ID from session or request
$profile = $controller->viewProfile($userId);
echo json_encode($profile);

// Example: Updating a profile
$update = $controller->updateProfile($userId, 'john123', 'John Doe', 'Computing and Information Systems');
if ($update) {
    echo "Profile updated successfully!";
} else {
    echo "Failed to update profile.";
}
