-- Scholarship Eligibility Checker Database Setup
-- Run this SQL in phpMyAdmin to create the database

CREATE DATABASE IF NOT EXISTS scholarship_db;
USE scholarship_db;

-- Users table (both students and admins)
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('student', 'admin') DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student profiles
CREATE TABLE student_profiles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    cgpa DECIMAL(3,1) DEFAULT 0.0,
    family_income INT DEFAULT 0,
    course VARCHAR(100),
    state_of_origin VARCHAR(100),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Scholarships
CREATE TABLE scholarships (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(150) NOT NULL,
    description TEXT,
    amount INT NOT NULL,
    provider VARCHAR(100),
    deadline DATE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Scholarship criteria
CREATE TABLE scholarship_criteria (
    id INT AUTO_INCREMENT PRIMARY KEY,
    scholarship_id INT NOT NULL,
    minimum_cgpa DECIMAL(3,1) DEFAULT 0.0,
    maximum_income INT DEFAULT 1000000,
    required_course VARCHAR(100) DEFAULT 'Any',
    required_state VARCHAR(100) DEFAULT 'Any',
    FOREIGN KEY (scholarship_id) REFERENCES scholarships(id) ON DELETE CASCADE
);

-- Insert a default admin account
-- Password is: admin123
INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'admin@scholarship.edu', 'admin123', 'admin');

-- Insert some sample scholarships for testing
INSERT INTO scholarships (name, description, amount, provider, deadline) VALUES
('Merit Excellence Award', 'For students with outstanding academic performance', 50000, 'Ministry of Education', '2026-12-31'),
('B.Tech Welfare Scholarship', 'Financial assistance for B.Tech students', 30000, 'Social Welfare Department', '2026-11-30'),
('Maharashtra State Grant', 'Supporting residential students in higher education', 25000, 'State Corporation', '2026-10-31'),
('Need-Based Financial Aid', 'For economically weaker students with good academics', 40000, 'State Education Board', '2026-12-15');

-- Insert criteria for sample scholarships
INSERT INTO scholarship_criteria (scholarship_id, minimum_cgpa, maximum_income, required_course, required_state) VALUES
(1, 8.0, 1000000, 'Any', 'Any'),
(2, 6.0, 500000, 'B.Tech', 'Any'),
(3, 7.0, 800000, 'Any', 'Maharashtra'),
(4, 7.5, 400000, 'Any', 'Any');
