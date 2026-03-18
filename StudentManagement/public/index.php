<?php
// Main routing file
require_once dirname(dirname(__FILE__)) . '/config/config.php';
require_once dirname(dirname(__FILE__)) . '/app/Controllers/AuthController.php';
require_once dirname(dirname(__FILE__)) . '/app/Controllers/StudentController.php';
require_once dirname(dirname(__FILE__)) . '/app/Models/User.php';

// Start session
session_start();

// Get page parameter
$page = isset($_GET['page']) ? $_GET['page'] : 'login';

// Initialize controllers
$authController = new AuthController();
$studentController = null;

// Route handling
switch ($page) {
    case 'register':
        $authController->register();
        break;
        
    case 'login':
        $authController->login();
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'dashboard':
    case 'students':
        if (User::isLoggedIn()) {
            $studentController = new StudentController();
            $studentController->index();
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'create':
        if (User::isLoggedIn()) {
            $studentController = new StudentController();
            $studentController->create();
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'delete':
        if (User::isLoggedIn()) {
            $studentController = new StudentController();
            $studentController->delete();
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    default:
        // Redirect to login if already logged in, otherwise show login page
        if (User::isLoggedIn()) {
            header('Location: index.php?page=students');
            exit;
        } else {
            $authController->showLogin();
        }
        break;
}
?>
