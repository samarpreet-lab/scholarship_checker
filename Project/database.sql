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
    gpa DECIMAL(3,1) DEFAULT 0.0,
    family_income INT DEFAULT 0,
    category ENUM('General', 'OBC', 'SC', 'ST') DEFAULT 'General',
    gender ENUM('Male', 'Female', 'Other') DEFAULT 'Male',
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
    min_gpa DECIMAL(3,1) DEFAULT 0.0,
    max_income INT DEFAULT 1000000,
    eligible_categories VARCHAR(100) DEFAULT 'General,OBC,SC,ST',
    gender_requirement ENUM('Any', 'Male', 'Female') DEFAULT 'Any',
    FOREIGN KEY (scholarship_id) REFERENCES scholarships(id) ON DELETE CASCADE
);

-- Insert a default admin account
-- Password is: admin123
INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'admin@scholarship.com', 'admin123', 'admin');

-- Insert some sample scholarships for testing
INSERT INTO scholarships (name, description, amount, provider, deadline) VALUES
('Merit Excellence Award', 'For students with outstanding academic performance', 50000, 'Ministry of Education', '2026-12-31'),
('SC/ST Welfare Scholarship', 'Financial assistance for SC/ST category students', 30000, 'Social Welfare Department', '2026-11-30'),
('Women Empowerment Grant', 'Supporting female students in higher education', 25000, 'Women Development Corporation', '2026-10-31'),
('Need-Based Financial Aid', 'For economically weaker students with good academics', 40000, 'State Education Board', '2026-12-15');

-- Insert criteria for sample scholarships
INSERT INTO scholarship_criteria (scholarship_id, min_gpa, max_income, eligible_categories, gender_requirement) VALUES
(1, 8.0, 1000000, 'General,OBC,SC,ST', 'Any'),
(2, 6.0, 500000, 'SC,ST', 'Any'),
(3, 7.0, 800000, 'General,OBC,SC,ST', 'Female'),
(4, 7.5, 400000, 'General,OBC,SC,ST', 'Any');
