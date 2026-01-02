-- Portfolio Database Initialization Script
-- This script creates sample tables and inserts test data

USE portfolio_db;

-- Create a users table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create a projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    description TEXT,
    technology_stack VARCHAR(200),
    github_url VARCHAR(255),
    demo_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    user_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Create a blog posts table
CREATE TABLE IF NOT EXISTS blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    author_id INT,
    views INT DEFAULT 0,
    published_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Insert sample users
INSERT INTO users (username, email) VALUES 
('johndoe', 'john@example.com'),
('janedoe', 'jane@example.com'),
('developer', 'dev@example.com');

-- Insert sample projects
INSERT INTO projects (title, description, technology_stack, github_url, user_id) VALUES 
(
    'E-Commerce Platform',
    'A full-featured online shopping platform with cart, checkout, and payment integration.',
    'PHP, MySQL, JavaScript, Bootstrap',
    'https://github.com/username/ecommerce',
    1
),
(
    'Task Management App',
    'A collaborative task management application with real-time updates.',
    'PHP, MySQL, AJAX, CSS3',
    'https://github.com/username/taskmanager',
    2
),
(
    'Weather Dashboard',
    'Interactive weather dashboard using external APIs.',
    'PHP, MySQL, REST API, Chart.js',
    'https://github.com/username/weather',
    3
),
(
    'Blog Engine',
    'Custom CMS for blogging with markdown support.',
    'PHP, MySQL, Markdown Parser',
    'https://github.com/username/blog',
    1
);

-- Insert sample blog posts
INSERT INTO blog_posts (title, content, author_id, views) VALUES 
(
    'Getting Started with LAMP Stack',
    'The LAMP stack is a popular open-source web development platform. In this post, we explore how to set up and use it effectively for building modern web applications.',
    1,
    150
),
(
    'Database Design Best Practices',
    'Learn about normalization, indexing, and query optimization to build efficient database schemas that scale with your application.',
    2,
    230
),
(
    'Securing Your Web Application',
    'Security should be a priority from day one. This guide covers SQL injection prevention, XSS protection, and secure authentication patterns.',
    3,
    189
);

-- Create an index for better query performance
CREATE INDEX idx_projects_user ON projects(user_id);
CREATE INDEX idx_posts_author ON blog_posts(author_id);
CREATE INDEX idx_posts_published ON blog_posts(published_at);

-- Grant privileges (already done in docker-compose, but including for reference)
-- GRANT ALL PRIVILEGES ON portfolio_db.* TO 'webuser'@'%';
-- FLUSH PRIVILEGES;
