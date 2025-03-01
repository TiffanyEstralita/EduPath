<?php
session_start(); // To store user session data

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once __DIR__ . '/../controllers/UserAuthenticationController.php';

	// Sanitize input to prevent XSS attacks
	$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Ensure all fields are filled out
	if (!empty($username) && !empty($email) && !empty($password)) {
		$authController = new UserAuthenticationController();
		$success = $authController->register($username, $email, $password);

		if ($success) {
			// Store user ID and username in session
			$_SESSION['username'] = $username;
			$_SESSION['user_id'] = $success; // Assuming the $success returns the user ID

			// Redirect to profile completion page
			header("Location: complete_profile.php");
			exit();
		} else {
			echo "Username or email already exists.";
		}
	} else {
		echo "Please fill in all fields.";
	}
}
?>

<form method="POST" action="register.php">
	<label for="username">Username:</label>
	<input type="text" name="username" required>

	<label for="email">Email:</label>
	<input type="email" name="email" required>

	<label for="password">Password:</label>
	<input type="password" name="password" required>

	<button type="submit">Register</button>
</form>