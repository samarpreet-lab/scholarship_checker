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
        // Escape strings to prevent SQL injection
        $safe_name     = $conn->real_escape_string($name);
        $safe_email    = $conn->real_escape_string($email);
        $safe_password = $conn->real_escape_string($password);

        // Check if email already exists
        $query = "SELECT id FROM users WHERE email = '$safe_email'";
        $result = $conn->query($query);

        if ($result->num_rows > 0) {
            $error = "Email already registered.";
        } else {

            // Insert new user
            $insert_query = "INSERT INTO users (name, email, password, role) VALUES ('$safe_name', '$safe_email', '$safe_password', 'student')";
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
    <div class="col-md-5 d-none d-md-flex flex-column justify-content-center align-items-start p-5" style="background-color: #002855; color: white;">
        <h1 class="display-4 fw-bold mb-4" style="line-height: 1.2;">Begin Your <br>Academic Journey.</h1>
        <p class="lead mb-5 opacity-75" style="font-size: 1.1rem; max-width: 400px;">Join an elite network of scholars and researchers. Secure your institutional access to global funding opportunities and editorial resources.</p>
        
        <div class="mb-4">
            <h5 class="fw-bold d-flex align-items-center"><span class="badge bg-light text-primary me-2">✓</span> Verified Status</h5>
            <p class="small opacity-75 ms-4">Institutional email verification required.</p>
        </div>
        <div>
            <h5 class="fw-bold d-flex align-items-center"><span class="badge bg-light text-primary me-2">✦</span> Curated Matches</h5>
            <p class="small opacity-75 ms-4">Personalized eligibility tracking.</p>
        </div>

        <div class="mt-auto">
            <span class="text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.1em; opacity: 0.7;">Academic Editorial Scholarship Fund</span>
        </div>
    </div>

    <!-- Right Side: Registration Form -->
    <div class="col-md-7 d-flex align-items-center justify-content-center bg-light">
        <div class="card shadow-lg border-0 p-5 rounded-4" style="width: 100%; max-width: 600px; background: rgba(255, 255, 255, 0.95);">
            <div class="mb-5 border-bottom pb-4">
                <h2 class="fw-bold mb-2" style="color: #002855; font-size: 2rem;">Create Account</h2>
                <p class="text-muted small">Enter your details to register for the portal.</p>
            </div>

            <?php if ($error): ?>
                <div class="alert alert-danger py-3 px-4 rounded-3 mb-4 small fw-bold border-0 d-flex align-items-center" style="background-color: #fee; color: #c33;">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">Full Name</label>
                    <input type="text" name="name" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" required placeholder="Dr. Julian Reed" style="font-size: 0.95rem; padding: 12px 16px; transition: all 0.3s;">
                </div>

                <div class="mb-4">
                    <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">Institutional Email</label>
                    <input type="email" name="email" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" required placeholder="reed.j@university.edu" style="font-size: 0.95rem; padding: 12px 16px; transition: all 0.3s;">
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-sm-6">
                        <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" required placeholder="••••••••" style="font-size: 0.95rem; padding: 12px 16px; transition: all 0.3s;">
                    </div>
                    <div class="col-sm-6">
                        <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" required placeholder="••••••••" style="font-size: 0.95rem; padding: 12px 16px; transition: all 0.3s;">
                    </div>
                </div>

                <div class="form-check mb-5 mt-3 ms-1">
                    <input class="form-check-input" type="checkbox" id="terms" required style="width: 18px; height: 18px; margin-top: 4px;">
                    <label class="form-check-label text-muted ms-2" for="terms" style="font-size: 0.85rem;">
                        I agree to the <a href="#" class="fw-bold text-decoration-none" style="color: #002855;">Terms of Service</a> and <a href="#" class="fw-bold text-decoration-none" style="color: #002855;">Privacy Policy</a>.
                    </label>
                </div>

                <button type="submit" class="btn btn-lg w-100 text-white fw-bold shadow-sm rounded-3 btn-large-primary" style="font-size: 0.95rem; padding: 14px; letter-spacing: 0.5px;">Complete Registration</button>
            </form>

            <div class="mt-5 pt-4 border-top text-center">
                <p class="text-muted mb-0" style="font-size: 0.85rem;">
                    Already registered? 
                    <a href="<?php echo $base; ?>index.php" class="fw-bold text-decoration-none" style="color: #002855;">Login to Portal</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>
