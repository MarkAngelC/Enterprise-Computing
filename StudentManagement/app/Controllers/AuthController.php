<?php
require_once dirname(dirname(__FILE__)) . '/Models/User.php';

class AuthController {
    private $user;
    
    public function __construct() {
        $this->user = new User();
    }
    
    /**
     * Show registration form
     */
    public function showRegister() {
        include dirname(dirname(__FILE__)) . '/Views/auth/register.php';
    }
    
    /**
     * Handle user registration
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = isset($_POST['username']) ? trim($_POST['username']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
            
            // Validate passwords match
            if ($password !== $confirmPassword) {
                $error = 'Passwords do not match';
                include dirname(dirname(__FILE__)) . '/Views/auth/register.php';
                return;
            }
            
            // Validate password length
            if (strlen($password) < 6) {
                $error = 'Password must be at least 6 characters';
                include dirname(dirname(__FILE__)) . '/Views/auth/register.php';
                return;
            }
            
            // Register user
            $result = $this->user->register($username, $email, $password);
            
            if ($result['success']) {
                header('Location: index.php?page=login&success=1');
                exit;
            } else {
                $error = $result['message'];
                include dirname(dirname(__FILE__)) . '/Views/auth/register.php';
            }
        } else {
            include dirname(dirname(__FILE__)) . '/Views/auth/register.php';
        }
    }
    
    /**
     * Show login form
     */
    public function showLogin() {
        include dirname(dirname(__FILE__)) . '/Views/auth/login.php';
    }
    
    /**
     * Handle user login
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            // Login user
            $result = $this->user->login($email, $password);
            
            if ($result['success']) {
                header('Location: index.php?page=dashboard');
                exit;
            } else {
                $error = $result['message'];
                include dirname(dirname(__FILE__)) . '/Views/auth/login.php';
            }
        } else {
            include dirname(dirname(__FILE__)) . '/Views/auth/login.php';
        }
    }
    
    /**
     * Handle user logout
     */
    public function logout() {
        User::logout();
        header('Location: index.php?page=login');
        exit;
    }
}
?>
