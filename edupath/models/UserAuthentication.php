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

	public function registerUser($name, $email, $password)
	{
		// Hash the password
		$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

		// Check if the name or email already exists
		$query = "SELECT * FROM " . $this->table . " WHERE name = :name OR email = :email";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':email', $email);
		$stmt->execute();

		if ($stmt->rowCount() > 0) {
			return false;  // name or email already exists
		}

		// Insert the new user into the database
		$query = "INSERT INTO " . $this->table . " (name, email, password, created_at) VALUES (:username, :email, :password, NOW())";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':name', $name);
		$stmt->bindParam(':email', $email);
		$stmt->bindParam(':password', $hashedPassword);

		return $stmt->execute();  // Returns true if successful
	}

	public function loginUser($nameOrEmail, $password)
	{
		// Check if name or email exists
		$query = "SELECT * FROM " . $this->table . " WHERE name = :nameOrEmail OR email = :nameOrEmail";
		$stmt = $this->conn->prepare($query);
		$stmt->bindParam(':nameOrEmail', $nameOrEmail);
		$stmt->execute();

		if ($stmt->rowCount() === 0) {
			return false;  // name/email doesn't exist
		}

		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// Verify password
		if (password_verify($password, $user['password'])) {
			return $user;  // Return the user data if successful
		}

		return false;  // Incorrect password
	}
}
