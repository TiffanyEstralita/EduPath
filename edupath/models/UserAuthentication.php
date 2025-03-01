<?php
require_once __DIR__ . '/../config/Database.php';

class UserAuthentication
{
	private $conn;
	private $table = 'user_profiles';

	public function __construct()
	{
		$db = new Database();
		$this->conn = $db->getConnection();
	}

	public function registerUser($username, $email, $password)
	{
		// Hash the password
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// Check if the username or email already exists
		$query = "SELECT * FROM " . $this->table . " WHERE username = :username OR email = :email";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			return false;  // Username or email already exists
		}

		// Insert the new user into the database
		$query = "INSERT INTO " . $this->table . " (username, email, password, created_at) VALUES (:username, :email, :password, NOW())";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':username', $username);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $hashedPassword);

		return $stmt->execute();  // Returns true if successful
	}

	public function loginUser($usernameOrEmail, $password)
	{
		// Check if username or email exists
		$query = "SELECT * FROM " . $this->table . " WHERE username = :usernameOrEmail OR email = :usernameOrEmail";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':usernameOrEmail', $usernameOrEmail);
		$stmt->execute();

		if ($stmt->rowCount() === 0) {
			return false;  // Username/email doesn't exist
		}

		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Verify password
		if (password_verify($password, $user['password'])) {
			return $user;  // Return the user data if successful
		}

		return false;  // Incorrect password
	}
}
