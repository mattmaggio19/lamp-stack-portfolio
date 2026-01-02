<?php
// Database configuration
require_once 'config.php';
$db_host = DB_HOST;
$db_user = DB_USER;
$db_pass = DB_PASS;
$db_name = DB_NAME;
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {
    die('Database connection failed: ' . htmlspecialchars($conn->connect_error));
}

// Create tasks table if it doesn't exist
$conn->query("CREATE TABLE IF NOT EXISTS tasks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    status ENUM('pending', 'in-progress', 'completed') DEFAULT 'pending',
    priority ENUM('low', 'medium', 'high') DEFAULT 'medium',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
)");

// Handle form submissions BEFORE any output
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'create':
                $title = $conn->real_escape_string($_POST['title']);
                $description = $conn->real_escape_string($_POST['description']);
                $priority = $conn->real_escape_string($_POST['priority']);
                
                $sql = "INSERT INTO tasks (title, description, priority) VALUES ('$title', '$description', '$priority')";
                
                if ($conn->query($sql)) {
                    header('Location: tasks.php?success=created');
                    exit;
                } else {
                    header('Location: tasks.php?error=create_failed');
                    exit;
                }
                break;
                
            case 'delete':
                $id = (int)$_POST['id'];
                $sql = "DELETE FROM tasks WHERE id = $id";
                
                if ($conn->query($sql)) {
                    header('Location: tasks.php?success=deleted');
                    exit;
                } else {
                    header('Location: tasks.php?error=delete_failed');
                    exit;
                }
                break;
                
            case 'update_status':
                $id = (int)$_POST['id'];
                $status = $conn->real_escape_string($_POST['status']);
                $sql = "UPDATE tasks SET status = '$status' WHERE id = $id";
                
                if ($conn->query($sql)) {
                    header('Location: tasks.php?success=updated');
                    exit;
                } else {
                    header('Location: tasks.php?error=update_failed');
                    exit;
                }
                break;
        }
    }
}

// Set messages based on URL parameters
$message = '';
$messageType = '';

if (isset($_GET['success'])) {
    $messageType = 'success';
    switch ($_GET['success']) {
        case 'created':
            $message = 'Task created successfully!';
            break;
        case 'deleted':
            $message = 'Task deleted successfully!';
            break;
        case 'updated':
            $message = 'Task status updated!';
            break;
    }
}

if (isset($_GET['error'])) {
    $messageType = 'error';
    switch ($_GET['error']) {
        case 'create_failed':
            $message = 'Error creating task. Please try again.';
            break;
        case 'delete_failed':
            $message = 'Error deleting task. Please try again.';
            break;
        case 'update_failed':
            $message = 'Error updating task. Please try again.';
            break;
    }
}

// NOW we can start outputting HTML
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager - CRUD Example</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 30px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            color: #333;
        }
        
        input[type="text"], textarea, select {
            width: 100%;
            padding: 10px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        
        input[type="text"]:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #667eea;
        }
        
        textarea {
            resize: vertical;
            min-height: 80px;
        }
        
        button {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        
        button:hover {
            transform: translateY(-2px);
        }
        
        .task-list {
            margin-top: 30px;
        }
        
        .task-item {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
            display: flex;
            justify-content: space-between;
            align-items: start;
        }
        
        .task-content {
            flex: 1;
        }
        
        .task-title {
            font-weight: 600;
            font-size: 1.1em;
            margin-bottom: 5px;
        }
        
        .task-description {
            color: #666;
            margin-bottom: 10px;
        }
        
        .task-meta {
            font-size: 0.85em;
            color: #999;
        }
        
        .task-actions {
            display: flex;
            gap: 10px;
        }
        
        .btn-small {
            padding: 6px 12px;
            font-size: 14px;
        }
        
        .btn-delete {
            background: #dc3545;
        }
        
        .btn-edit {
            background: #28a745;
        }
        
        .status-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 3px;
            font-size: 0.85em;
            font-weight: 600;
        }
        
        .status-pending {
            background: #ffc107;
            color: #000;
        }
        
        .status-in-progress {
            background: #17a2b8;
            color: white;
        }
        
        .status-completed {
            background: #28a745;
            color: white;
        }
        
        .message {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .message.success {
            background: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .message.error {
            background: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .back-link {
            display: inline-block;
            margin-bottom: 20px;
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        
        .back-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="index.php" class="back-link">← Back to Portfolio</a>
        
        <h1>📝 Task Manager - CRUD Demo</h1>

        <?php if ($message): ?>
        <div class="message <?php echo $messageType; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>
        <?php endif; ?>

        <!-- Create Task Form -->
        <form method="POST" action="">
            <input type="hidden" name="action" value="create">
            
            <div class="form-group">
                <label for="title">Task Title</label>
                <input type="text" id="title" name="title" required placeholder="Enter task title...">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" placeholder="Enter task description..."></textarea>
            </div>
            
            <div class="form-group">
                <label for="priority">Priority</label>
                <select id="priority" name="priority">
                    <option value="low">Low</option>
                    <option value="medium" selected>Medium</option>
                    <option value="high">High</option>
                </select>
            </div>
            
            <button type="submit">Add Task</button>
        </form>

        <!-- Task List -->
        <div class="task-list">
            <h2>All Tasks</h2>
            
<?php
$result = $conn->query("SELECT * FROM tasks ORDER BY 
    CASE priority 
        WHEN 'high' THEN 1 
        WHEN 'medium' THEN 2 
        WHEN 'low' THEN 3 
    END,
    created_at DESC");

if ($result->num_rows > 0) {
    while ($task = $result->fetch_assoc()) {
        echo '<div class="task-item">';
        echo '<div class="task-content">';
        echo '<div class="task-title">' . htmlspecialchars($task['title']) . '</div>';
        
        if ($task['description']) {
            echo '<div class="task-description">' . htmlspecialchars($task['description']) . '</div>';
        }
        
        echo '<div class="task-meta">';
        echo '<span class="status-badge status-' . $task['status'] . '">' . 
             ucfirst(str_replace('-', ' ', $task['status'])) . '</span> ';
        echo '<span style="margin-left: 10px;">Priority: ' . ucfirst($task['priority']) . '</span> | ';
        echo 'Created: ' . date('M d, Y', strtotime($task['created_at']));
        echo '</div>';
        echo '</div>';
        
        echo '<div class="task-actions">';
        
        // Status update form
        echo '<form method="POST" style="display: inline;">';
        echo '<input type="hidden" name="action" value="update_status">';
        echo '<input type="hidden" name="id" value="' . $task['id'] . '">';
        
        if ($task['status'] === 'pending') {
            echo '<input type="hidden" name="status" value="in-progress">';
            echo '<button type="submit" class="btn-small btn-edit">Start</button>';
        } elseif ($task['status'] === 'in-progress') {
            echo '<input type="hidden" name="status" value="completed">';
            echo '<button type="submit" class="btn-small btn-edit">Complete</button>';
        }
        echo '</form>';
        
        // Delete form
        echo '<form method="POST" style="display: inline;" onsubmit="return confirm(\'Delete this task?\')">';
        echo '<input type="hidden" name="action" value="delete">';
        echo '<input type="hidden" name="id" value="' . $task['id'] . '">';
        echo '<button type="submit" class="btn-small btn-delete">Delete</button>';
        echo '</form>';
        
        echo '</div>';
        echo '</div>';
    }
} else {
    echo '<p style="text-align: center; color: #666; padding: 40px 0;">No tasks yet. Create one above!</p>';
}

$conn->close();
?>
        </div>
    </div>
</body>
</html>