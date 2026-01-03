<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio Projects - LAMP Stack Demo</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            line-height: 1.6;
            color: #333;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            padding: 40px;
        }
        
        h1 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 2.5em;
        }
        
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 1.1em;
        }
        
        .status {
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 30px;
            font-weight: 500;
        }
        
        .status.success {
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            color: #155724;
        }
        
        .status.error {
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
            color: #721c24;
        }
        
        .section {
            margin-bottom: 40px;
        }
        
        .section h2 {
            color: #764ba2;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 2px solid #667eea;
        }
        
        .project-card {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 5px;
            transition: transform 0.2s;
        }
        
        .project-card:hover {
            transform: translateX(5px);
        }
        
        .project-title {
            font-size: 1.3em;
            color: #333;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .project-tech {
            display: inline-block;
            background: #667eea;
            color: white;
            padding: 5px 10px;
            border-radius: 3px;
            font-size: 0.85em;
            margin: 10px 5px 10px 0;
        }
        
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5em;
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .stat-label {
            font-size: 0.9em;
            opacity: 0.9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #667eea;
            color: white;
            font-weight: 600;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 0.9em;
        }
        
        a {
            color: #667eea;
            text-decoration: none;
        }
        
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Portfolio Projects</h1>
        <p class="subtitle">LAMP Stack Demo - Local Development Environment</p>

    <div style="margin-bottom: 30px;">
        <a href="tasks.php" style="
            display: inline-block;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: 600;
            transition: transform 0.2s;
        " onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
            Go to Task Manager
        </a>
<?php
// Database configuration
require_once 'config.php';
$db_host = DB_HOST;
$db_user = DB_USER;
$db_pass = DB_PASS;
$db_name = DB_NAME;

// Create connection
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($conn->connect_error) {
    echo '<div class="status error">';
    echo '<strong>Database Connection Failed:</strong> ' . htmlspecialchars($conn->connect_error);
    echo '</div>';
    echo '<p>Please check your database configuration and ensure MySQL is running.</p>';
} else {
    echo '<div class="status success">';
    echo '<strong>Database Connected Successfully!</strong> Your LAMP stack is working correctly.';
    echo '</div>';
    
    // Get statistics
    $stats = [];
    
    $result = $conn->query("SELECT COUNT(*) as count FROM projects");
    $stats['projects'] = $result->fetch_assoc()['count'];
    
    $result = $conn->query("SELECT COUNT(*) as count FROM users");
    $stats['users'] = $result->fetch_assoc()['count'];
    
    $result = $conn->query("SELECT COUNT(*) as count FROM blog_posts");
    $stats['posts'] = $result->fetch_assoc()['count'];
    
    $result = $conn->query("SELECT SUM(views) as total FROM blog_posts");
    $stats['views'] = $result->fetch_assoc()['total'];
    
    // Display statistics
    echo '<div class="stats">';
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . $stats['projects'] . '</div>';
    echo '<div class="stat-label">Total Projects</div>';
    echo '</div>';
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . $stats['users'] . '</div>';
    echo '<div class="stat-label">Developers</div>';
    echo '</div>';
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . $stats['posts'] . '</div>';
    echo '<div class="stat-label">Blog Posts</div>';
    echo '</div>';
    echo '<div class="stat-card">';
    echo '<div class="stat-number">' . number_format($stats['views']) . '</div>';
    echo '<div class="stat-label">Total Views</div>';
    echo '</div>';
    echo '</div>';
    
    // Fetch and display projects
    echo '<div class="section">';
    echo '<h2>📂 Recent Projects</h2>';
    
    $sql = "SELECT p.*, u.username 
            FROM projects p 
            JOIN users u ON p.user_id = u.id 
            ORDER BY p.created_at DESC";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<div class="project-card">';
            echo '<div class="project-title">' . htmlspecialchars($row['title']) . '</div>';
            echo '<p>' . htmlspecialchars($row['description']) . '</p>';
            
            $techs = explode(',', $row['technology_stack']);
            foreach($techs as $tech) {
                echo '<span class="project-tech">' . trim(htmlspecialchars($tech)) . '</span>';
            }
            
            echo '<p style="margin-top: 10px; font-size: 0.9em; color: #666;">';
            echo '👤 ' . htmlspecialchars($row['username']) . ' | ';
            echo '📅 ' . date('M d, Y', strtotime($row['created_at']));
            
            if ($row['github_url']) {
                echo ' | <a href="' . htmlspecialchars($row['github_url']) . '" target="_blank">GitHub →</a>';
            }
            echo '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>No projects found.</p>';
    }
    echo '</div>';
    
    // Fetch and display blog posts
    echo '<div class="section">';
    echo '<h2>Latest Blog Posts</h2>';
    echo '<table>';
    echo '<tr><th>Title</th><th>Author</th><th>Views</th><th>Published</th></tr>';
    
    $sql = "SELECT b.*, u.username 
            FROM blog_posts b 
            JOIN users u ON b.author_id = u.id 
            ORDER BY b.published_at DESC";
    
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            echo '<tr>';
            echo '<td><strong>' . htmlspecialchars($row['title']) . '</strong><br>';
            echo '<span style="font-size: 0.9em; color: #666;">' . 
                 htmlspecialchars(substr($row['content'], 0, 100)) . '...</span></td>';
            echo '<td>' . htmlspecialchars($row['username']) . '</td>';
            echo '<td>' . number_format($row['views']) . '</td>';
            echo '<td>' . date('M d, Y', strtotime($row['published_at'])) . '</td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="4">No blog posts found.</td></tr>';
    }
    echo '</table>';
    echo '</div>';
    
    // Database info
    echo '<div class="section">';
    echo '<h2>🔧 System Information</h2>';
    echo '<table>';
    echo '<tr><th>Component</th><th>Details</th></tr>';
    echo '<tr><td>Web Server</td><td>Apache ' . apache_get_version() . '</td></tr>';
    echo '<tr><td>PHP Version</td><td>' . phpversion() . '</td></tr>';
    echo '<tr><td>MySQL Version</td><td>' . $conn->server_info . '</td></tr>';
    echo '<tr><td>Database</td><td>' . $db_name . '</td></tr>';
    echo '<tr><td>Host</td><td>' . $db_host . '</td></tr>';
    echo '</table>';
    echo '</div>';
    
    $conn->close();
}
?>

        <div class="footer">
            <p>🎓 This is a portfolio demonstration of a LAMP stack environment</p>
            <p>Simulating an AWS EC2 deployment locally for development and learning</p>
        </div>
    </div>
</body>
</html>
