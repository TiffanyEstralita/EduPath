<?php
require_once __DIR__ . '/../config/Database.php';

class UserProfile
{
    private $conn;
    private $table = 'user_profiles';
    private $fieldsTable = 'fields_of_interest';

    public function __construct()
    {
        $db = new Database();
        $this->conn = $db->getConnection();
    }

    // Fetch user profile with field name instead of ID
    public function getProfile($userId)
    {
        $query = "SELECT u.id, u.username, u.full_name, u.email, u.created_at, f.field_name
                  FROM " . $this->table . " u
                  LEFT JOIN " . $this->fieldsTable . " f ON u.field_id = f.id
                  WHERE u.id = :user_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getFieldsOfInterest()
    {
        $query = "SELECT * FROM " . $this->fieldsTable;
        $stmt = $this->conn->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);  // Return fields of interest
    }

    public function updateProfile($userId, $username, $fullName, $fieldOfInterestId)
    {
        $query = "UPDATE " . $this->table . " 
                  SET username = :username, full_name = :full_name, field_id = :field_id 
                  WHERE id = :user_id";
        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':full_name', $fullName);
        $stmt->bindParam(':field_id', $fieldOfInterestId);  // Ensure this matches the field in your DB
        $stmt->bindParam(':user_id', $userId);

        return $stmt->execute();  // Return the result of the update query
    }
}
