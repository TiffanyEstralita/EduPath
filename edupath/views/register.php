<?php
session_start();

// Database connection
require_once __DIR__ . '/../config/Database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	// Sanitize inputs
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
	$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

	// Ensure all fields are filled out
	if (!empty($name) && !empty($email) && !empty($password)) {
		// Hash password before storing it
		$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

		// Default values for the optional fields (NULL)
		$fieldId = NULL;            // Nullable field
		$educationId = NULL;   // Nullable field
		$careerAspiration = NULL;   // Nullable field

		$db = new Database();
		$conn = $db->getConnection();

		// Check if the email already exists
		$query = "SELECT * FROM user_profiles WHERE email = :email";
		$stmt = $conn->prepare($query);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			echo "Email already exists.";
		} else {
			// Insert the new user into the database
			$query = "INSERT INTO user_profiles (name, email, password, field_id, education_id, career_aspiration, created_at) 
                      VALUES (:name, :email, :password, :field_id, :education_id, :career_aspiration, NOW())";
			$stmt = $conn->prepare($query);
			$stmt->bindParam(':name', $name);
			$stmt->bindParam(':email', $email);
			$stmt->bindParam(':password', $hashedPassword);
			$stmt->bindParam(':field_id', $fieldId, PDO::PARAM_INT);  // Set as NULL
			$stmt->bindParam(':education_id', $educationId, PDO::PARAM_INT);  // Set as NULL
			$stmt->bindParam(':career_aspiration', $careerAspiration, PDO::PARAM_STR);  // Set as NULL

			if ($stmt->execute()) {
				// Store user session data
				$_SESSION['name'] = $name;
				$_SESSION['user_id'] = $conn->lastInsertId();  // Assuming the user ID is the last inserted ID

				// Redirect to profile completion page
				header("Location: complete_profile.php");
				exit();
			} else {
				echo "Failed to register user.";
			}
		}
	} else {
		echo "Please fill in all fields.";
	}
}
?>

<form method="POST" action="register.php">
	<label for="name">Name:</label>
	<input type="text" name="name" required>

	<label for="email">Email:</label>
	<input type="email" name="email" required>

	<label for="password">Password:</label>
	<input type="password" name="password" required>

	<button type="submit">Register</button>
</form>