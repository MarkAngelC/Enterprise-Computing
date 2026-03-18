<?php
require_once dirname(dirname(dirname(__FILE__))) . '/config/config.php';

class Student {
    private $conn;
    private $user_id;
    
    public function __construct($userId = null) {
        $this->conn = getDBConnection();
        $this->user_id = $userId;
    }
    
    /**
     * Create a new student
     */
    public function create($studentId, $name, $email) {
        // Validate inputs
        if (empty($studentId) || empty($name) || empty($email)) {
            return array('success' => false, 'message' => 'All fields are required');
        }
        
        // Check if student ID already exists
        $checkQuery = "SELECT id FROM students WHERE student_id = ?";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bind_param("s", $studentId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return array('success' => false, 'message' => 'Student ID already exists');
        }
        
        // Check if email already exists
        $checkQuery = "SELECT id FROM students WHERE email = ?";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return array('success' => false, 'message' => 'Email already exists');
        }
        
        // Insert student
        $insertQuery = "INSERT INTO students (student_id, name, email, user_id) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($insertQuery);
        $stmt->bind_param("sssi", $studentId, $name, $email, $this->user_id);
        
        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'Student created successfully', 'id' => $this->conn->insert_id);
        } else {
            return array('success' => false, 'message' => 'Failed to create student');
        }
    }
    
    /**
     * Get all students for the logged-in user
     */
    public function getAll() {
        $query = "SELECT id, student_id, name, email FROM students WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        $students = array();
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }
        
        return $students;
    }
    
    /**
     * Get student by ID
     */
    public function getById($studentId) {
        $query = "SELECT id, student_id, name, email FROM students WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ii", $studentId, $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Update student information
     */
    public function update($studentId, $name, $email) {
        // Validate inputs
        if (empty($studentId) || empty($name) || empty($email)) {
            return array('success' => false, 'message' => 'All fields are required');
        }
        
        // Check if email is already used by another student
        $checkQuery = "SELECT id FROM students WHERE email = ? AND id != ? AND user_id = ?";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bind_param("sii", $email, $studentId, $this->user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return array('success' => false, 'message' => 'Email already exists');
        }
        
        // Update student
        $updateQuery = "UPDATE students SET name = ?, email = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($updateQuery);
        $stmt->bind_param("ssii", $name, $email, $studentId, $this->user_id);
        
        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'Student updated successfully');
        } else {
            return array('success' => false, 'message' => 'Failed to update student');
        }
    }
    
    /**
     * Delete a student
     */
    public function delete($studentId) {
        $deleteQuery = "DELETE FROM students WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($deleteQuery);
        $stmt->bind_param("ii", $studentId, $this->user_id);
        
        if ($stmt->execute()) {
            if ($stmt->affected_rows > 0) {
                return array('success' => true, 'message' => 'Student deleted successfully');
            } else {
                return array('success' => false, 'message' => 'Student not found');
            }
        } else {
            return array('success' => false, 'message' => 'Failed to delete student');
        }
    }
}
?>
