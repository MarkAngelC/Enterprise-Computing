<?php
require_once dirname(dirname(dirname(__FILE__))) . '/config/config.php';

class User {
    private $conn;
    
    public function __construct() {
        $this->conn = getDBConnection();
    }
    
    /**
     * Register a new user
     */
    public function register($username, $email, $password) {
        // Validate inputs
        if (empty($username) || empty($email) || empty($password)) {
            return array('success' => false, 'message' => 'All fields are required');
        }
        
        // Check if user already exists
        $checkQuery = "SELECT id FROM users WHERE email = ? OR username = ?";
        $stmt = $this->conn->prepare($checkQuery);
        $stmt->bind_param("ss", $email, $username);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return array('success' => false, 'message' => 'Email or username already exists');
        }
        
        // Hash password
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        // Insert new user
        $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($insertQuery);
        $stmt->bind_param("sss", $username, $email, $hashedPassword);
        
        if ($stmt->execute()) {
            return array('success' => true, 'message' => 'Registration successful');
        } else {
            return array('success' => false, 'message' => 'Registration failed');
        }
    }
    
    /**
     * Login user with email and password
     */
    public function login($email, $password) {
        // Validate inputs
        if (empty($email) || empty($password)) {
            return array('success' => false, 'message' => 'Email and password are required');
        }
        
        // Get user by email
        $query = "SELECT id, username, email, password FROM users WHERE email = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return array('success' => false, 'message' => 'Invalid email or password');
        }
        
        $user = $result->fetch_assoc();
        
        // Verify password
        if (!password_verify($password, $user['password'])) {
            return array('success' => false, 'message' => 'Invalid email or password');
        }
        
        // Start session and set user data
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['logged_in'] = true;
        
        return array('success' => true, 'message' => 'Login successful', 'user_id' => $user['id']);
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($userId) {
        $query = "SELECT id, username, email FROM users WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }
    
    /**
     * Check if user is logged in
     */
    public static function isLoggedIn() {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
    
    /**
     * Logout user
     */
    public static function logout() {
        session_unset();
        session_destroy();
        return true;
    }
}
?>
