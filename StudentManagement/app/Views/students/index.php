<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students - Student Management</title>
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
            max-width: 1000px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .header h2 {
            color: #333;
            font-size: 28px;
        }
        
        .btn-create {
            background: #28a745;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            transition: background 0.3s;
        }
        
        .btn-create:hover {
            background: #218838;
        }
        
        .alert {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
            display: none;
        }
        
        .alert.show {
            display: block;
        }
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        th {
            background: #667eea;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #eee;
        }
        
        tr:last-child td {
            border-bottom: none;
        }
        
        tr:hover {
            background-color: #f9f9f9;
        }
        
        .btn-delete {
            background: #dc3545;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 12px;
            transition: background 0.3s;
        }
        
        .btn-delete:hover {
            background: #c82333;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state p {
            font-size: 18px;
            margin-bottom: 20px;
        }
        
        .empty-state a {
            display: inline-block;
            background: #28a745;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
            transition: background 0.3s;
        }
        
        .empty-state a:hover {
            background: #218838;
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
        <div class="header">
            <h2>Students List</h2>
            <a href="index.php?page=create" class="btn-create">+ Add New Student</a>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success show">
                <?php echo htmlspecialchars($_SESSION['success']); unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error show">
                <?php echo htmlspecialchars($_SESSION['error']); unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>
        
        <?php if (count($students) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Student ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($students as $student): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($student['student_id']); ?></td>
                            <td><?php echo htmlspecialchars($student['name']); ?></td>
                            <td><?php echo htmlspecialchars($student['email']); ?></td>
                            <td>
                                <form method="POST" action="index.php?page=delete" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this student?');">
                                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                    <button type="submit" class="btn-delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="empty-state">
                <p>No students found.</p>
                <a href="index.php?page=create">Add your first student</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
