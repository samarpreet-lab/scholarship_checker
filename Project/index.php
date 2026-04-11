<?php
session_start();
require_once 'config/db.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim(strtolower($_POST['email']));
    $password = $_POST['password'];

    // Escape strings to prevent SQL injection
    $safe_email    = $conn->real_escape_string($email);
    $safe_password = $conn->real_escape_string($password);

    // Fetch user by using simple Query
    $query = "SELECT * FROM users WHERE email = '$safe_email' AND password = '$safe_password'";
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

<div class="row g-0 min-vh-100 w-100">
    <!-- Left Side: Messaging -->
    <div class="col-md-5 d-none d-md-flex flex-column justify-content-center align-items-start p-5 login-left-panel">
        <span class="badge bg-light text-primary mb-3 py-2 px-3 fw-bold text-uppercase login-badge">Excellence in Research</span>
        <h1 class="display-4 fw-bold mb-4 login-heading">Elevating the standards of global scholarship.</h1>
        <p class="lead mb-5 opacity-75 login-description">Access our secure, institutional-grade portal to manage scholarly opportunities and peer assessments.</p>
        <div class="d-flex align-items-center mt-auto">
            <span class="text-uppercase login-footer-text">Trusted by 500+ leading institutions</span>
        </div>
    </div>

    <!-- Right Side: Login Form -->
    <div class="col-md-7 d-flex align-items-center justify-content-center bg-light">
        <div class="card shadow-lg border-0 p-5 rounded-4 login-card">
            <div class="mb-5 border-bottom pb-4">
                <h2 class="fw-bold mb-2 login-title">Welcome Back.</h2>
                <p class="text-muted small">Secure authentication for faculty & students.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-3 px-4 rounded-3 mb-4 small fw-bold border-0 d-flex align-items-center alert-danger-custom">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">Institutional Email</label>
                    <input type="email" name="email" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-custom" required placeholder="email@university.edu">
                </div>

                <div class="mb-5">
                    <div class="d-flex justify-content-between align-items-end mb-3">
                        <label class="form-label text-uppercase fw-bold text-secondary mb-0 form-label-custom">Password</label>
                        <a href="#" class="text-decoration-none small text-muted forgot-password">Forgot Password?</a>
                    </div>
                    <input type="password" name="password" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-password" required placeholder="••••••••">
                </div>

                <button type="submit" class="btn btn-lg w-100 text-white fw-bold shadow-sm rounded-3 btn-large-primary form-submit-custom">Sign In to Portal</button>
            </form>

            <div class="mt-5 pt-4 border-top text-center">
                <p class="text-muted mb-0 login-footer">
                    New to Academic Editorial? 
                    <a href="<?php echo $base; ?>register.php" class="fw-bold text-decoration-none login-link">Create institutional account</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
