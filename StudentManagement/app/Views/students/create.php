<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student - Student Management</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px 40px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .navbar h1 {
            font-size: 24px;
        }
        
        .navbar-right {
            display: flex;
            gap: 20px;
            align-items: center;
        }
        
        .navbar-right a, .navbar-right form {
            color: white;
            text-decoration: none;
        }
        
        .navbar-right button {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid white;
            color: white;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background 0.3s;
        }
        
        .navbar-right button:hover {
            background: rgba(255, 255, 255, 0.3);
        }
        
        .container {
            max-width: 600px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .form-card {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }
        
        .form-card h2 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 500;
        }
        
        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }
        
        .form-group input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 5px rgba(102, 126, 234, 0.3);
        }
        
        .form-actions {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        
        .btn-submit {
            flex: 1;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-cancel {
            flex: 1;
            padding: 12px;
            background: #6c757d;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }
        
        .btn-cancel:hover {
            background: #5a6268;
        }
        
        .error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Student Management System</h1>
        <div class="navbar-right">
            <span>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <form method="POST" action="index.php?page=logout" style="display: inline;">
                <button type="submit">Logout</button>
            </form>
        </div>
    </nav>
    
    <div class="container">
        <div class="form-card">
            <h2>Add New Student</h2>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>
            
            <form method="POST" action="index.php?page=create">
                <div class="form-group">
                    <label for="student_id">Student ID *</label>
                    <input type="text" id="student_id" name="student_id" required value="<?php echo isset($_POST['student_id']) ? htmlspecialchars($_POST['student_id']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="name">Full Name *</label>
                    <input type="text" id="name" name="name" required value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input type="email" id="email" name="email" required value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Add Student</button>
                    <a href="index.php?page=students" class="btn-cancel">Cancel</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
