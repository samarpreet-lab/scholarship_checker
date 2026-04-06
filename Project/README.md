# Scholarship Eligibility Checker - Project Documentation

## 🎓 About This Project
A web-based scholarship eligibility checker built with PHP, MySQL, Bootstrap 5, and Object-Oriented Programming principles.

**Course:** CAP512 — Open Source Web Application Development  
**Tech Stack:** PHP, MySQLi, OOP, HTML5, CSS3, Bootstrap 5

---

## 📋 Features

### Student Features:
- ✅ Register and create account
- ✅ Login with email/password
- ✅ Update academic profile (GPA, family income, category, gender)
- ✅ View eligible scholarships based on profile
- ✅ Scholarships sorted by amount (highest first)

### Admin Features:
- ✅ Secure admin login
- ✅ Dashboard with statistics
- ✅ Add new scholarships with eligibility criteria
- ✅ View all scholarships
- ✅ Delete scholarships

---

## 🚀 Installation Steps

### 1. Prerequisites
- XAMPP or WAMP server installed
- PHP 7.4 or higher
- MySQL/MariaDB

### 2. Setup Database
1. Start Apache and MySQL from XAMPP Control Panel
2. Open phpMyAdmin: `http://localhost/phpmyadmin`
3. Click "Import" tab
4. Choose the `database.sql` file from the Project folder
5. Click "Go" to execute the SQL script

This will create:
- Database: `scholarship_db`
- Tables: `users`, `student_profiles`, `scholarships`, `scholarship_criteria`
- Sample data: 1 admin account + 4 sample scholarships

### 3. Access the Application
Open your browser and navigate to:
```
http://localhost/scholarship_checker/Project/
```

---

## 🔐 Login Credentials

### Admin Account:
- **Email:** admin@scholarship.com
- **Password:** admin123

### Student Account:
Register a new account using the "Register" link on the login page.

---

## 🔒 Security Features Implemented

### ✅ Fixed Issues (As of Latest Version):

1. **Email Case-Sensitivity Fix**
   - Login and registration now both use `trim(strtolower($email))`
   - Students can login regardless of email capitalization

2. **SQL Injection Prevention**
   - All user inputs are escaped using `$conn->real_escape_string()`
   - Prevents malicious SQL code injection
   - Safe handling of apostrophes and special characters

3. **Input Validation**
   - Email format validation
   - Password minimum length (6 characters)
   - Password confirmation matching
   - GPA range validation (0.0 - 10.0)

---

## 📁 Project Structure

```
Project/
├── config/
│   └── db.php                 # Database connection configuration
│
├── classes/
│   ├── User.php               # Base user class
│   ├── Student.php            # Student class (extends User)
│   ├── Admin.php              # Admin class (extends User)
│   ├── Scholarship.php        # Scholarship data class
│   ├── Criteria.php           # Eligibility criteria class
│   └── EligibilityChecker.php # Core matching algorithm
│
├── includes/
│   ├── header.php             # Common navigation header
│   └── footer.php             # Common footer with scripts
│
├── student/
│   ├── dashboard.php          # Student home page
│   ├── profile.php            # Edit profile page
│   └── results.php            # View eligible scholarships
│
├── admin/
│   ├── dashboard.php          # Admin dashboard with stats
│   ├── scholarships.php       # View all scholarships
│   ├── add_scholarship.php    # Add new scholarship form
│   └── delete_scholarship.php # Delete scholarship handler
│
├── index.php                  # Login page (students & admins)
├── register.php               # Student registration
├── logout.php                 # Logout handler
└── database.sql               # Database setup script
```

---

## 🧠 OOP Concepts Used

### Inheritance:
- `Student` and `Admin` classes extend the `User` base class
- Demonstrates code reusability

### Encapsulation:
- Private, protected, and public access modifiers
- Getters for accessing private properties

### Constructors & Destructors:
- `__construct()` initializes objects
- `__destruct()` in User class (demonstrates usage)

### Class Methods:
- `EligibilityChecker::check()` - Core business logic
- Custom sorting algorithm using nested loops

---

## 💡 How the Matching Algorithm Works

The `EligibilityChecker` class compares student profiles against scholarship criteria:

```php
// Checks performed for each scholarship:
1. GPA >= Minimum Required GPA
2. Family Income <= Maximum Allowed Income
3. Category in Eligible Categories List
4. Gender matches requirement (or requirement is "Any")

// All conditions must be TRUE for a match
```

After matching, scholarships are sorted by amount (highest first) using bubble sort.

---

## ⚠️ Important Notes

### Security Warnings:
1. **Password Storage:** Passwords are currently stored as plain text
   - ⚠️ In production, use `password_hash()` and `password_verify()`
   
2. **SQL Queries:** Currently using `real_escape_string()`
   - ✅ Provides basic protection against SQL injection
   - 💡 For better security, consider using prepared statements

3. **Session Security:** Basic session handling implemented
   - Consider adding CSRF tokens for production use

---

## 🐛 Troubleshooting

### Problem: Can't login after registration
**Solution:** Make sure you're using the same email format. The system converts all emails to lowercase.

### Problem: Database connection failed
**Solution:** 
1. Check if MySQL is running in XAMPP
2. Verify database credentials in `config/db.php`
3. Ensure database `scholarship_db` exists

### Problem: Page shows blank screen
**Solution:**
1. Enable error reporting: Add to top of `index.php`:
   ```php
   ini_set('display_errors', 1);
   error_reporting(E_ALL);
   ```
2. Check PHP error logs in XAMPP

---

## 📚 Syllabus Coverage

| Unit | Topics Covered | Implementation |
|------|---------------|----------------|
| **Unit I** | PHP basics, sessions, control structures | All PHP files use sessions, if/else, foreach loops |
| **Unit II** | Built-in functions, user-defined functions | String functions, array functions, custom methods |
| **Unit III** | String manipulation, arrays | explode(), implode(), trim(), in_array() |
| **Unit IV** | OOP: Classes, inheritance, constructors | 7 classes with inheritance hierarchy |
| **Unit V** | MySQL, MySQLi queries | CRUD operations, JOIN queries |

---

## 🎯 Future Enhancements

- [ ] Password hashing with bcrypt
- [ ] Email verification system
- [ ] Forgot password functionality
- [ ] Edit scholarship feature
- [ ] Student application tracking
- [ ] Admin approval workflow
- [ ] Export results to PDF
- [ ] Advanced search/filtering

---

## 📄 License

Educational project for CAP512 course.

---

## 👨‍💻 Developer Notes

### Key Learning Points:
1. **Email Normalization:** Always normalize user inputs (trim, lowercase) consistently
2. **SQL Injection:** Never trust user input - always escape or use prepared statements
3. **Session Management:** Check user authentication on protected pages
4. **OOP Design:** Use inheritance to avoid code duplication

---

**Created:** 2026  
**Last Updated:** April 6, 2026  
**Version:** 1.1 (Security Fixes Applied)
