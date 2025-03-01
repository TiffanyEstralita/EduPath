<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	require_once __DIR__ . '/../controllers/UserAuthenticationController.php';

	// Sanitize inputs using FILTER_SANITIZE_FULL_SPECIAL_CHARS
	$usernameOrEmail = filter_input(INPUT_POST, 'usernameOrEmail', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Ensure inputs are not empty
	if (!empty($usernameOrEmail) && !empty($password)) {
		$authController = new UserAuthenticationController();
		$user = $authController->login($usernameOrEmail, $password);

		if ($user) {
			// Storing user data in session variables
			$_SESSION['user_id'] = $user['user_id'];
			$_SESSION['username'] = $user['username'];
			header("Location: profile.php");  // Redirect to profile page after login
			exit;
		} else {
			echo "Invalid login credentials.";
		}
	} else {
		echo "Please fill in both fields.";
	}
}
?>

<form method="POST" action="login.php">
	<label for="usernameOrEmail">Username or Email:</label>
	<input type="text" name="usernameOrEmail" required>

	<label for="password">Password:</label>
	<input type="password" name="password" required>

	<button type="submit">Login</button>
</form>