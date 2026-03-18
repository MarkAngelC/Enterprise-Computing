<?php
require_once dirname(dirname(__FILE__)) . '/Models/Student.php';
require_once dirname(dirname(__FILE__)) . '/Models/User.php';

class StudentController {
    private $student;
    
    public function __construct() {
        // Check if user is logged in
        if (!User::isLoggedIn()) {
            header('Location: index.php?page=login');
            exit;
        }
        
        $this->student = new Student($_SESSION['user_id']);
    }
    
    /**
     * Show all students
     */
    public function index() {
        $students = $this->student->getAll();
        include dirname(dirname(__FILE__)) . '/Views/students/index.php';
    }
    
    /**
     * Show create student form
     */
    public function showCreate() {
        include dirname(dirname(__FILE__)) . '/Views/students/create.php';
    }
    
    /**
     * Handle student creation
     */
    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = isset($_POST['student_id']) ? trim($_POST['student_id']) : '';
            $name = isset($_POST['name']) ? trim($_POST['name']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            
            // Validate inputs
            if (empty($studentId) || empty($name) || empty($email)) {
                $error = 'All fields are required';
                include dirname(dirname(__FILE__)) . '/Views/students/create.php';
                return;
            }
            
            // Validate email format
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'Invalid email format';
                include dirname(dirname(__FILE__)) . '/Views/students/create.php';
                return;
            }
            
            // Create student
            $result = $this->student->create($studentId, $name, $email);
            
            if ($result['success']) {
                header('Location: index.php?page=students&success=1');
                exit;
            } else {
                $error = $result['message'];
                include dirname(dirname(__FILE__)) . '/Views/students/create.php';
            }
        } else {
            include dirname(dirname(__FILE__)) . '/Views/students/create.php';
        }
    }
    
    /**
     * Handle student deletion
     */
    public function delete() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $studentId = isset($_POST['student_id']) ? intval($_POST['student_id']) : 0;
            
            if ($studentId <= 0) {
                $_SESSION['error'] = 'Invalid student ID';
            } else {
                $result = $this->student->delete($studentId);
                if ($result['success']) {
                    $_SESSION['success'] = $result['message'];
                } else {
                    $_SESSION['error'] = $result['message'];
                }
            }
            
            header('Location: index.php?page=students');
            exit;
        }
    }
}
?>
