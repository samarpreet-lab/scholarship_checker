<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'scholarship_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database if it doesn't exist
$db_query = "CREATE DATABASE IF NOT EXISTS " . DB_NAME;
$conn->query($db_query);

// Select the database
$conn->select_db(DB_NAME);

// Create tables if they don't exist
$users_table = "CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($users_table);

$student_profiles_table = "CREATE TABLE IF NOT EXISTS student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cgpa DECIMAL(3,1) DEFAULT 0.0,
    course VARCHAR(100) DEFAULT '',
    state_of_origin VARCHAR(100) DEFAULT '',
    family_income INT DEFAULT 0,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
)";
$conn->query($student_profiles_table);

$scholarships_table = "CREATE TABLE IF NOT EXISTS scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    amount INT NOT NULL,
    provider VARCHAR(100),
    deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";
$conn->query($scholarships_table);

$criteria_table = "CREATE TABLE IF NOT EXISTS scholarship_criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scholarship_id INT NOT NULL,
    minimum_cgpa DECIMAL(3,1) DEFAULT 0.0,
    maximum_income INT DEFAULT 1000000,
    required_course VARCHAR(100) DEFAULT 'Any',
    required_state VARCHAR(100) DEFAULT 'Any',
    FOREIGN KEY (scholarship_id) REFERENCES scholarships(id) ON DELETE CASCADE
)";
$conn->query($criteria_table);

// Insert default admin if not exists (beginner simple string match, no hashing for simplicity)
$admin_check = $conn->query("SELECT id FROM users WHERE email='admin@scholarship.edu'");
if ($admin_check->num_rows == 0) {
    $conn->query("INSERT INTO users (name, email, password, role) VALUES ('Admin', 'admin@scholarship.edu', 'admin123', 'admin')");
}
?>
