# Scholarship Eligibility Checker
### CAP512 — Open Source Web Application Development
**Project Type:** Continuous Assessment (CA) Project  
**Tech Stack:** PHP, MySQLi, OOP, HTML, CSS, Bootstrap 5  
**Units Covered:** Unit I, II, III, IV, V

---

## Table of Contents

1. [Project Overview](#1-project-overview)
2. [Features](#2-features)
3. [Folder Structure](#3-folder-structure)
4. [Database Schema](#4-database-schema)
5. [Class Design (OOP)](#5-class-design-oop)
6. [File-by-File Code](#6-file-by-file-code)
   - [config/db.php](#61-configdbphp)
   - [classes/User.php](#62-classesuserphp)
   - [classes/Student.php](#63-classesstudentphp)
   - [classes/Admin.php](#64-classesadminphp)
   - [classes/Scholarship.php](#65-classesscholarshipphp)
   - [classes/Criteria.php](#66-classescriteriaophp)
   - [classes/EligibilityChecker.php](#67-classeseligibilitycheckerphp)
   - [includes/header.php](#68-includesheaderphp)
   - [includes/footer.php](#69-includesfooterphp)
   - [index.php (Login Page)](#610-indexphp--login-page)
   - [register.php](#611-registerphp)
   - [logout.php](#612-logoutphp)
   - [student/dashboard.php](#613-studentdashboardphp)
   - [student/profile.php](#614-studentprofilephp)
   - [student/results.php](#615-studentresultsphp)
   - [admin/dashboard.php](#616-admindashboardphp)
   - [admin/scholarships.php](#617-adminscholarshipsphp)
   - [admin/add_scholarship.php](#618-adminadd_scholarshipphp)
   - [admin/delete_scholarship.php](#619-admindelete_scholarshipphp)
7. [Syllabus Coverage Map](#7-syllabus-coverage-map)
8. [How to Run the Project](#8-how-to-run-the-project)

---

## 1. Project Overview

The **Scholarship Eligibility Checker** is a web application built in PHP where students can register, fill in their academic and personal profile, and instantly see which scholarships they are eligible for. Admins can log in separately to manage scholarships and set eligibility criteria.

The system compares each student's profile — GPA, family income, category (General/OBC/SC/ST), and gender — against scholarship criteria stored in the MySQL database and displays only the matched scholarships.

---

## 2. Features

**Student Side**
- Register and log in
- Fill and update personal profile (GPA, income, category, gender)
- View all scholarships they qualify for
- Scholarships sorted by amount (highest first)

**Admin Side**
- Separate admin login
- Add new scholarships with criteria
- View all scholarships
- Delete scholarships

**Both**
- Secure login (simple string matching to keep it beginner-friendly)
- Session-based authentication
- Role-based redirection (student vs admin)

---

## 3. Folder Structure

```
scholarship-checker/
│
├── index.php                  ← Login page (both student and admin)
├── register.php               ← Student registration
├── logout.php                 ← Logout
│
├── config/
│   └── db.php                 ← Database connection
│
├── classes/
│   ├── User.php               ← Base class
│   ├── Student.php            ← Extends User
│   ├── Admin.php              ← Extends User
│   ├── Scholarship.php        ← Scholarship data class
│   ├── Criteria.php           ← Criteria data class
│   └── EligibilityChecker.php ← Core matching logic
│
├── student/
│   ├── dashboard.php          ← Student home
│   ├── profile.php            ← Edit profile
│   └── results.php            ← View matched scholarships
│
├── admin/
│   ├── dashboard.php          ← Admin home
│   ├── scholarships.php       ← View all scholarships
│   ├── add_scholarship.php    ← Add new scholarship
│   └── delete_scholarship.php ← Delete scholarship
│
└── includes/
    ├── header.php             ← Common HTML header
    └── footer.php             ← Common HTML footer
```

---

## 4. Database Schema

Run this SQL in your MySQL/phpMyAdmin to create the database:

```sql
CREATE DATABASE scholarship_db;
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
```

> **Note:** The default admin password is just `admin123` to keep it very simple for beginners.

---

## 5. Class Design (OOP)

```
User  (base class)
├── properties: id, name, email, password
├── methods: getId(), getName(), getEmail()
│
├── Student  extends User
│   ├── properties: gpa, income, category, gender
│   └── methods: getGPA(), getIncome(), getCategory(), getGender()
│
└── Admin  extends User
    └── (inherits all from User, no extra properties needed)

Scholarship
├── properties: id, name, description, amount, provider, deadline
└── methods: all getters

Criteria
├── properties: scholarship_id, min_gpa, max_income, eligible_categories, gender_requirement
└── methods: all getters

EligibilityChecker
├── properties: student, scholarships
└── methods: check() → returns array of matched Scholarship objects
```

---

## 6. File-by-File Code

---

### 6.1 config/db.php

Handles database connection. All other files include this.

```php
<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'scholarship_db');

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
```

---

### 6.2 classes/User.php

Base class for both Student and Admin.

```php
<?php
class User {
    protected $id;
    protected $name;
    protected $email;
    protected $password;

    public function __construct($id, $name, $email, $password) {
        $this->id       = $id;
        $this->name     = $name;
        $this->email    = $email;
        $this->password = $password;
    }

    public function getId()    { return $this->id; }
    public function getName()  { return $this->name; }
    public function getEmail() { return $this->email; }

    public function __destruct() {
        // destructor - no cleanup needed here
    }
}
?>
```

---

### 6.3 classes/Student.php

Extends User, adds academic profile properties.

```php
<?php
require_once 'User.php';

class Student extends User {
    private $gpa;
    private $income;
    private $category;
    private $gender;

    public function __construct($id, $name, $email, $password, $gpa, $income, $category, $gender) {
        parent::__construct($id, $name, $email, $password);
        $this->gpa      = $gpa;
        $this->income   = $income;
        $this->category = $category;
        $this->gender   = $gender;
    }

    public function getGPA()      { return $this->gpa; }
    public function getIncome()   { return $this->income; }
    public function getCategory() { return $this->category; }
    public function getGender()   { return $this->gender; }
}
?>
```

---

### 6.4 classes/Admin.php

Extends User. Admin has no extra properties, just a different role.

```php
<?php
require_once 'User.php';

class Admin extends User {
    public function __construct($id, $name, $email, $password) {
        parent::__construct($id, $name, $email, $password);
    }
}
?>
```

---

### 6.5 classes/Scholarship.php

Holds scholarship data fetched from DB.

```php
<?php
class Scholarship {
    private $id;
    private $name;
    private $description;
    private $amount;
    private $provider;
    private $deadline;

    public function __construct($id, $name, $description, $amount, $provider, $deadline) {
        $this->id          = $id;
        $this->name        = $name;
        $this->description = $description;
        $this->amount      = $amount;
        $this->provider    = $provider;
        $this->deadline    = $deadline;
    }

    public function getId()          { return $this->id; }
    public function getName()        { return $this->name; }
    public function getDescription() { return $this->description; }
    public function getAmount()      { return $this->amount; }
    public function getProvider()    { return $this->provider; }
    public function getDeadline()    { return $this->deadline; }
}
?>
```

---

### 6.6 classes/Criteria.php

Holds criteria for one scholarship.

```php
<?php
class Criteria {
    private $scholarship_id;
    private $min_gpa;
    private $max_income;
    private $eligible_categories;
    private $gender_requirement;

    public function __construct($scholarship_id, $min_gpa, $max_income, $eligible_categories, $gender_requirement) {
        $this->scholarship_id      = $scholarship_id;
        $this->min_gpa             = $min_gpa;
        $this->max_income          = $max_income;
        $this->eligible_categories = $eligible_categories;
        $this->gender_requirement  = $gender_requirement;
    }

    public function getMinGpa()             { return $this->min_gpa; }
    public function getMaxIncome()          { return $this->max_income; }
    public function getEligibleCategories() { return $this->eligible_categories; }
    public function getGenderRequirement()  { return $this->gender_requirement; }
}
?>
```

---

### 6.7 classes/EligibilityChecker.php

The core logic of the project. Takes a Student and list of Scholarships+Criteria, returns only matched ones.

```php
<?php
class EligibilityChecker {
    private $student;
    private $scholarships;
    private $criteriaList;

    public function __construct($student, $scholarships, $criteriaList) {
        $this->student      = $student;
        $this->scholarships = $scholarships;
        $this->criteriaList = $criteriaList;
    }

    public function check() {
        $matched = [];

        foreach ($this->scholarships as $scholarship) {
            $criteria = $this->criteriaList[$scholarship->getId()] ?? null;

            if (!$criteria) continue;

            // Check GPA
            $gpa_ok = $this->student->getGPA() >= $criteria->getMinGpa();

            // Check income
            $income_ok = $this->student->getIncome() <= $criteria->getMaxIncome();

            // Check category — stored as "SC,ST,OBC" — explode into array
            $categories = explode(',', $criteria->getEligibleCategories());
            $category_ok = in_array($this->student->getCategory(), $categories);

            // Check gender
            $gender_req = $criteria->getGenderRequirement();
            $gender_ok  = ($gender_req === 'Any') || ($gender_req === $this->student->getGender());

            // All conditions must pass
            if ($gpa_ok && $income_ok && $category_ok && $gender_ok) {
                $matched[] = $scholarship;
            }
        }

        // Sort matched scholarships by amount — highest first
        for ($i = 0; $i < count($matched); $i++) {
            for ($j = 0; $j < count($matched) - 1 - $i; $j++) {
                if ($matched[$j]->getAmount() < $matched[$j + 1]->getAmount()) {
                    $temp = $matched[$j];
                    $matched[$j] = $matched[$j + 1];
                    $matched[$j + 1] = $temp;
                }
            }
        }

        return $matched;
    }
}
?>
```

---

### 6.8 includes/header.php

Common HTML header included on every page.

```php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Eligibility Checker</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-4">
    <div class="container-fluid">
        <span class="navbar-brand mb-0 h1">🎓 Scholarship Checker</span>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <ul class="navbar-nav mb-2 mb-lg-0">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><span class="nav-link text-white me-3">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="/admin/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/admin/scholarships.php">Scholarships</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="/student/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="/student/profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link" href="/student/results.php">My Results</a></li>
                    <?php endif; ?>
                    <li class="nav-item"><a class="nav-link btn btn-danger text-white ms-2 px-3" href="/logout.php">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="/index.php">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="/register.php">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
```

---

### 6.9 includes/footer.php

```php
</div> <!-- End container -->
<!-- Bootstrap JS Code -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

### 6.10 index.php — Login Page

Single login page for both students and admins. Redirects based on role.

```php
<?php
session_start();
require_once 'config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user by using simple Query
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        // Set session
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name']    = $user['name'];
        $_SESSION['role']    = $user['role'];

        // Redirect based on role
        if ($user['role'] === 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: student/dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="card shadow-sm mx-auto" style="max-width:400px; margin-top: 50px;">
    <div class="card-body">
        <h2 class="card-title text-center mb-4 text-primary">Login</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="Enter your email">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Enter your password">
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>

        <p class="mt-3 text-center" style="font-size:14px;">
            Don't have an account? <a href="register.php" class="text-decoration-none">Register here</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
```

---

### 6.11 register.php

Student registration only. Admins are created directly in the database.

```php
<?php
session_start();
require_once 'config/db.php';

$error   = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name     = trim($_POST['name']);
    $email    = trim(strtolower($_POST['email']));
    $password = $_POST['password'];
    $confirm  = $_POST['confirm_password'];

    // Basic validation using string and built-in functions
    if (empty($name) || empty($email) || empty($password)) {
        $error = "All fields are required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email format.";
    } elseif (strlen($password) < 6) {
        $error = "Password must be at least 6 characters.";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $query = "SELECT id FROM users WHERE email = '$email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            // Insert new user
            $insert_query = "INSERT INTO users (name, email, password, role) VALUES ('$name', '$email', '$password', 'student')";
            $conn->query($insert_query);
            $new_id = $conn->insert_id;

            // Create empty student profile
            $profile_query = "INSERT INTO student_profiles (user_id) VALUES ($new_id)";
            $conn->query($profile_query);

            $success = "Registration successful! You can now login.";
        }
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="card shadow-sm mx-auto" style="max-width:450px; margin-top: 40px;">
    <div class="card-body">
        <h2 class="card-title text-center mb-4 text-primary">Student Registration</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" required placeholder="Enter your full name">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required placeholder="Enter your email">
            </div>

            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" required placeholder="Min 6 characters">
            </div>

            <div class="mb-3">
                <label class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" required placeholder="Repeat password">
            </div>

            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>

        <p class="mt-3 text-center" style="font-size:14px;">
            Already have an account? <a href="index.php" class="text-decoration-none">Login here</a>
        </p>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
```

---

### 6.12 logout.php

```php
<?php
session_start();
session_destroy();
header("Location: ../index.php");
exit();
?>
```

---

### 6.13 student/dashboard.php

Student's home page after login.

```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}
require_once '../includes/header.php';
?>

<div class="card shadow-sm">
    <div class="card-body text-center py-5">
        <h2 class="card-title text-primary mb-3">Welcome, <?php echo htmlspecialchars($_SESSION['name']); ?>!</h2>
        <p class="card-text text-muted mb-4">Use the links below to manage your profile and check scholarships.</p>

        <a href="profile.php" class="btn btn-outline-primary me-2">📝 Update Profile</a>
        <a href="results.php" class="btn btn-primary">🎓 Check My Scholarships</a>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
```

---

### 6.14 student/profile.php

Student fills in their academic details.

```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$success = '';
$error   = '';

// Save profile
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $gpa      = floatval($_POST['gpa']);
    $income   = intval($_POST['family_income']);
    $category = $_POST['category'];
    $gender   = $_POST['gender'];

    // Validate GPA range
    if ($gpa < 0 || $gpa > 10) {
        $error = "GPA must be between 0 and 10.";
    } else {
        $update_query = "UPDATE student_profiles SET gpa='$gpa', family_income='$income', category='$category', gender='$gender' WHERE user_id='$user_id'";
        $conn->query($update_query);
        $success = "Profile updated successfully.";
    }
}

// Fetch existing profile
$profile_query = "SELECT * FROM student_profiles WHERE user_id='$user_id'";
$result = $conn->query($profile_query);
$profile = $result->fetch_assoc();

require_once '../includes/header.php';
?>

<div class="card shadow-sm mx-auto" style="max-width: 600px; margin-top: 30px;">
    <div class="card-body">
        <h2 class="card-title text-primary mb-4">My Profile</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label class="form-label">GPA (0.0 – 10.0)</label>
                <input type="number" name="gpa" class="form-control" step="0.1" min="0" max="10"
                       value="<?php echo $profile['gpa'] ?? 0; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Annual Family Income (₹)</label>
                <input type="number" name="family_income" class="form-control" min="0"
                       value="<?php echo $profile['family_income'] ?? 0; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Category</label>
                <select name="category" class="form-select">
                    <?php
                    $cats = ['General', 'OBC', 'SC', 'ST'];
                    foreach ($cats as $cat) {
                        $sel = ($profile['category'] ?? '') === $cat ? 'selected' : '';
                        echo "<option value='$cat' $sel>$cat</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Gender</label>
                <select name="gender" class="form-select">
                    <?php
                    $genders = ['Male', 'Female', 'Other'];
                    foreach ($genders as $g) {
                        $sel = ($profile['gender'] ?? '') === $g ? 'selected' : '';
                        echo "<option value='$g' $sel>$g</option>";
                    }
                    ?>
                </select>
            </div>

            <button type="submit" class="btn btn-primary w-100">Save Profile</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
```

---

### 6.15 student/results.php

The main page — runs EligibilityChecker and displays matched scholarships.

```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';
require_once '../classes/User.php';
require_once '../classes/Student.php';
require_once '../classes/Scholarship.php';
require_once '../classes/Criteria.php';
require_once '../classes/EligibilityChecker.php';

$user_id = $_SESSION['user_id'];

// Fetch student user info
$user_query = "SELECT * FROM users WHERE id='$user_id'";
$user_result = $conn->query($user_query);
$user = $user_result->fetch_assoc();

// Fetch student profile
$profile_query = "SELECT * FROM student_profiles WHERE user_id='$user_id'";
$profile_result = $conn->query($profile_query);
$profile = $profile_result->fetch_assoc();

// Build Student object
$student = new Student(
    $user['id'],
    $user['name'],
    $user['email'],
    $user['password'],
    $profile['gpa'] ?? 0,
    $profile['family_income'] ?? 0,
    $profile['category'] ?? 'General',
    $profile['gender'] ?? 'Male'
);

// Fetch all scholarships with their criteria using simple query
$query = "
    SELECT s.id, s.name, s.description, s.amount, s.provider, s.deadline,
           c.min_gpa, c.max_income, c.eligible_categories, c.gender_requirement
    FROM scholarships s, scholarship_criteria c 
    WHERE s.id = c.scholarship_id 
    AND s.deadline >= CURDATE()
";
$result = $conn->query($query);

$scholarships = [];
$criteriaList = [];

while ($row = $result->fetch_assoc()) {
    $scholarship = new Scholarship(
        $row['id'],
        $row['name'],
        $row['description'],
        $row['amount'],
        $row['provider'],
        $row['deadline']
    );
    $criteria = new Criteria(
        $row['id'],
        $row['min_gpa'],
        $row['max_income'],
        $row['eligible_categories'],
        $row['gender_requirement']
    );
    $scholarships[]              = $scholarship;
    $criteriaList[$row['id']]    = $criteria;
}

// Run eligibility check
$checker = new EligibilityChecker($student, $scholarships, $criteriaList);
$matched = $checker->check();

require_once '../includes/header.php';
?>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="card-title text-primary mb-4">My Eligible Scholarships</h2>

        <?php if (empty($matched)): ?>
            <div class="alert alert-warning">No scholarships match your current profile. Try updating your profile with accurate information.</div>
        <?php else: ?>
            <p class="mb-3">
                You qualify for <strong><?php echo count($matched); ?></strong> scholarship(s).
            </p>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-primary">
                        <tr>
                            <th>Scholarship Name</th>
                            <th>Provider</th>
                            <th>Amount (₹)</th>
                            <th>Deadline</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($matched as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s->getName()); ?></td>
                            <td><?php echo htmlspecialchars($s->getProvider()); ?></td>
                            <td>₹<?php echo number_format($s->getAmount()); ?></td>
                            <td><?php echo $s->getDeadline(); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
```

---

### 6.16 admin/dashboard.php

Admin home page with a summary.

```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

// Count total scholarships
$result = $conn->query("SELECT COUNT(*) as total FROM scholarships");
$count  = $result->fetch_assoc()['total'];

// Count total students
$result2 = $conn->query("SELECT COUNT(*) as total FROM users WHERE role='student'");
$students = $result2->fetch_assoc()['total'];

require_once '../includes/header.php';
?>

<div class="card shadow-sm">
    <div class="card-body py-4">
        <h2 class="card-title text-primary mb-3">Admin Dashboard</h2>
        <p class="card-text text-muted mb-4">Manage scholarships from here.</p>

        <div class="row g-4 mb-4">
            <div class="col-md-6">
                <div class="card shadow-sm border-0 bg-light text-center h-100">
                    <div class="card-body">
                        <h3 class="display-4 text-primary fw-bold"><?php echo $count; ?></h3>
                        <p class="text-muted fw-semibold">Total Scholarships</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 bg-light text-center h-100">
                    <div class="card-body">
                        <h3 class="display-4 text-primary fw-bold"><?php echo $students; ?></h3>
                        <p class="text-muted fw-semibold">Registered Students</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <a href="scholarships.php" class="btn btn-outline-primary">View Scholarships</a>
            <a href="add_scholarship.php" class="btn btn-primary">+ Add Scholarship</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
```

---

### 6.17 admin/scholarships.php

View all scholarships.

```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$result = $conn->query("SELECT * FROM scholarships ORDER BY created_at DESC");

// Store results in array
$scholarships = [];
while ($row = $result->fetch_assoc()) {
    $scholarships[] = $row;
}

require_once '../includes/header.php';
?>

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="card-title text-primary mb-4">All Scholarships</h2>
        <a href="add_scholarship.php" class="btn btn-primary mb-3">+ Add New</a>

        <?php if (empty($scholarships)): ?>
            <div class="alert alert-info">No scholarships added yet.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Provider</th>
                            <th>Amount (₹)</th>
                            <th>Deadline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scholarships as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['name']); ?></td>
                            <td><?php echo htmlspecialchars($s['provider']); ?></td>
                            <td>₹<?php echo number_format($s['amount']); ?></td>
                            <td><?php echo $s['deadline']; ?></td>
                            <td>
                                <a href="delete_scholarship.php?id=<?php echo $s['id']; ?>"
                                   class="btn btn-sm btn-danger"
                                   onclick="return confirm('Delete this scholarship?')">Delete</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
```

---

### 6.18 admin/add_scholarship.php

Form to add a new scholarship with criteria.

```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Scholarship fields
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $amount      = intval($_POST['amount']);
    $provider    = trim($_POST['provider']);
    $deadline    = $_POST['deadline'];

    // Criteria fields
    $min_gpa     = floatval($_POST['min_gpa']);
    $max_income  = intval($_POST['max_income']);
    $gender_req  = $_POST['gender_requirement'];

    // Build eligible_categories string from checkbox array
    $cats_selected = $_POST['categories'] ?? [];
    $eligible_cats = implode(',', $cats_selected);

    if (empty($name) || empty($provider) || empty($deadline) || empty($eligible_cats)) {
        $error = "Please fill all required fields and select at least one category.";
    } else {
        // Insert scholarship using basic query
        $insert_scholarship = "INSERT INTO scholarships (name, description, amount, provider, deadline) VALUES ('$name', '$description', '$amount', '$provider', '$deadline')";
        $conn->query($insert_scholarship);
        $scholarship_id = $conn->insert_id;

        // Insert criteria using basic query
        $insert_criteria = "INSERT INTO scholarship_criteria (scholarship_id, min_gpa, max_income, eligible_categories, gender_requirement) VALUES ('$scholarship_id', '$min_gpa', '$max_income', '$eligible_cats', '$gender_req')";
        $conn->query($insert_criteria);

        $success = "Scholarship added successfully!";
    }
}

require_once '../includes/header.php';
?>

<div class="card shadow-sm mx-auto" style="max-width: 800px;">
    <div class="card-body">
        <h2 class="card-title text-primary mb-4">Add New Scholarship</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Scholarship Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Merit Excellence Award">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Provider / Organization *</label>
                    <input type="text" name="provider" class="form-control" required placeholder="e.g. Ministry of Education">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief description..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Amount (₹) *</label>
                    <input type="number" name="amount" class="form-control" min="0" required placeholder="e.g. 50000">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Application Deadline *</label>
                    <input type="date" name="deadline" class="form-control" required>
                </div>
            </div>

            <hr class="my-4">
            <h4 class="mb-3 text-secondary">Eligibility Criteria</h4>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Minimum GPA Required</label>
                    <input type="number" name="min_gpa" class="form-control" step="0.1" min="0" max="10" value="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Max Annual Family Income (₹)</label>
                    <input type="number" name="max_income" class="form-control" value="1000000" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label d-block">Eligible Categories (select all that apply) *</label>
                <?php foreach (['General', 'OBC', 'SC', 'ST'] as $cat): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" name="categories[]" value="<?php echo $cat; ?>" id="cat_<?php echo $cat; ?>">
                        <label class="form-check-label" for="cat_<?php echo $cat; ?>"><?php echo $cat; ?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mb-4">
                <label class="form-label">Gender Requirement</label>
                <select name="gender_requirement" class="form-select">
                    <option value="Any">Any</option>
                    <option value="Male">Male Only</option>
                    <option value="Female">Female Only</option>
                </select>
            </div>

            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">Add Scholarship</button>
                <a href="scholarships.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
```

---

### 6.19 admin/delete_scholarship.php

Deletes a scholarship and its criteria (cascades via foreign key).

```php
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$id = intval($_GET['id']);

if ($id > 0) {
    $delete_query = "DELETE FROM scholarships WHERE id='$id'";
    $conn->query($delete_query);
}

header("Location: scholarships.php");
exit();
?>
```

---

## 7. Syllabus Coverage Map

| Unit | Topic | Where Used in Project |
|------|-------|-----------------------|
| Unit I | PHP basics, embedding in HTML, sessions | `index.php`, `register.php`, all pages use `<?php ?>` embedding |
| Unit I | Control flow (`if`, `foreach`) | `results.php`, `profile.php`, `header.php` |
| Unit II | Built-in functions | `trim()`, `strtolower()`, `filter_var()`, `strlen()`, `number_format()`, `intval()`, `floatval()` |
| Unit II | User-defined functions | `EligibilityChecker::check()` |
| Unit II | Loops | nested `for` loop used for sorting array by amount manually |
| Unit III | String functions | `explode()`, `implode()`, `trim()`, `strtolower()`, `htmlspecialchars()` |
| Unit III | Arrays | `$matched[]`, `$scholarships[]`, `$criteriaList[]`, `in_array()` |
| Unit IV | Classes and objects | `User`, `Student`, `Admin`, `Scholarship`, `Criteria`, `EligibilityChecker` |
| Unit IV | Inheritance | `Student extends User`, `Admin extends User` |
| Unit IV | Constructors & Destructors | `__construct()` in all classes, `__destruct()` in `User` |
| Unit IV | Access modifiers | `private`, `protected`, `public` used throughout |
| Unit V | MySQLi connection | `config/db.php` |
| Unit V | Basic queries | Basic INSERT, SELECT, UPDATE, DELETE queries used avoiding complex prepared statements |
| Unit V | Relational data | `results.php` matches scholarships and criteria via simple matching `s.id = c.scholarship_id` |
| Unit V | Advanced DB — aggregate | Admin dashboard uses `COUNT()` |

---

## 8. How to Run the Project

**Requirements**
- XAMPP or WAMP installed
- PHP 7.4 or higher
- MySQL

**Steps**

1. Copy the project folder into `htdocs` (XAMPP) or `www` (WAMP)  
   Example: `C:/xampp/htdocs/scholarship-checker/`

2. Start Apache and MySQL from XAMPP Control Panel

3. Open `phpMyAdmin` at `http://localhost/phpmyadmin`

4. Create a new database called `scholarship_db`

5. Paste and run the SQL from [Section 4](#4-database-schema)

6. Open your browser and go to:  
   `http://localhost/scholarship-checker/`

7. **Admin Login:**  
   Email: `admin@scholarship.com`  
   Password: `admin123`

8. **Student:** Register a new account at `/register.php`, fill your profile, then check results.

---

*Project by: [Your Name] | Course: CAP512 | Session: 2025-26*
