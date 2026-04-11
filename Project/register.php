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

            // Auto-login the user and redirect to dashboard
            $_SESSION['user_id'] = $new_id;
            $_SESSION['name']    = $name;
            $_SESSION['role']    = 'student';
            
            header("Location: student/dashboard.php");
            exit();
        }
    }
}
?>

<?php require_once 'includes/header.php'; ?>

<div class="row g-0 min-vh-100 w-100">
    <!-- Left Side: Messaging -->
    <div class="col-md-5 d-none d-md-flex flex-column justify-content-center align-items-start p-5 login-left-panel">
        <h1 class="display-4 fw-bold mb-4 login-heading">Begin Your <br>Academic Journey.</h1>
        <p class="lead mb-5 opacity-75 login-description">Join an elite network of scholars and researchers. Secure your institutional access to global funding opportunities and editorial resources.</p>
        
        <div class="mb-4">
            <h5 class="fw-bold d-flex align-items-center"><span class="badge bg-light text-primary me-2">✓</span> Verified Status</h5>
            <p class="small opacity-75 ms-4">Institutional email verification required.</p>
        </div>
        <div>
            <h5 class="fw-bold d-flex align-items-center"><span class="badge bg-light text-primary me-2">✦</span> Curated Matches</h5>
            <p class="small opacity-75 ms-4">Personalized eligibility tracking.</p>
        </div>

        <div class="mt-auto">
            <span class="text-uppercase login-footer-text">Academic Editorial Scholarship Fund</span>
        </div>
    </div>

    <!-- Right Side: Registration Form -->
    <div class="col-md-7 d-flex align-items-center justify-content-center bg-light">
        <div class="card shadow-lg border-0 p-5 rounded-4 register-card">
            <div class="mb-5 border-bottom pb-4">
                <h2 class="fw-bold mb-2 login-title">Create Account</h2>
                <p class="text-muted small">Enter your details to register for the portal.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-3 px-4 rounded-3 mb-4 small fw-bold border-0 d-flex align-items-center alert-danger-custom">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-custom" required placeholder="Dr. Julian Reed">
                </div>

                <div class="mb-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">Institutional Email</label>
                    <input type="email" name="email" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-custom" required placeholder="reed.j@university.edu">
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-password" required placeholder="••••••••">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-password" required placeholder="••••••••">
                    </div>
                </div>

                <div class="form-check mb-5 mt-3 ms-1">
                    <input class="form-check-input" type="checkbox" id="terms" required style="width: 18px; height: 18px; margin-top: 4px;">
                    <label class="form-check-label text-muted ms-2 register-terms" for="terms">
                        I agree to the <a href="#" class="fw-bold text-decoration-none login-link">Terms of Service</a> and <a href="#" class="fw-bold text-decoration-none login-link">Privacy Policy</a>.
                    </label>
                </div>

                <button type="submit" class="btn btn-lg w-100 text-white fw-bold shadow-sm rounded-3 btn-large-primary form-submit-custom">Complete Registration</button>
            </form>

            <div class="mt-5 pt-4 border-top text-center">
                <p class="text-muted mb-0 register-footer">
                    Already registered? 
                    <a href="<?php echo $base; ?>index.php" class="fw-bold text-decoration-none login-link">Login to Portal</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
