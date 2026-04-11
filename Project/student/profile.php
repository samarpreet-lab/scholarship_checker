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
    $cgpa      = floatval($_POST['cgpa']);
    $course    = $conn->real_escape_string($_POST['course']);
    $state     = $conn->real_escape_string($_POST['state']);
    $income    = intval($_POST['family_income']);

    // Validate CGPA range
    if ($cgpa < 0 || $cgpa > 10) {
        $error = "CGPA must be between 0 and 10.";
    } elseif (empty($course) || empty($state)) {
        $error = "All fields are required.";
    } else {
        $update_query = "UPDATE student_profiles SET cgpa='$cgpa', course='$course', state_of_origin='$state', family_income='$income' WHERE user_id='$user_id'";
        if ($conn->query($update_query) === TRUE) {
            $success = "Profile updated successfully.";
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
    }
}

// Fetch existing profile
$profile_query = "SELECT * FROM student_profiles WHERE user_id='$user_id'";
$result = $conn->query($profile_query);
$profile = $result->fetch_assoc();

require_once '../includes/header.php';
?>

<div class="container mt-4 mb-5">
    <div class="row">
        <!-- Saved Profile Info -->
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm rounded-4 border-0 bg-light h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h4 class="fw-bold mb-0 profile-card-title">📋 Your Saved Profile</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($profile):  ?>
                        <div class="mb-4">
                            <small class="text-uppercase fw-bold text-secondary profile-field-label">CGPA</small>
                            <p class="fw-bold mb-0 profile-field-value"><?php echo (isset($profile['cgpa']) && $profile['cgpa'] !== null) ? $profile['cgpa'] : 'Not set'; ?></p>
                        </div>
                        <div class="mb-4">
                            <small class="text-uppercase fw-bold text-secondary profile-field-label">Course / Program</small>
                            <p class="fw-bold mb-0 profile-field-value"><?php echo !empty($profile['course']) ? htmlspecialchars($profile['course']) : 'Not set'; ?></p>
                        </div>
                        <div class="mb-4">
                            <small class="text-uppercase fw-bold text-secondary profile-field-label">State of Origin</small>
                            <p class="fw-bold mb-0 profile-field-value"><?php echo !empty($profile['state_of_origin']) ? htmlspecialchars($profile['state_of_origin']) : 'Not set'; ?></p>
                        </div>
                        <div>
                            <small class="text-uppercase fw-bold text-secondary profile-field-label">Annual Family Income</small>
                            <p class="fw-bold mb-0 profile-field-value">₹<?php echo number_format($profile['family_income'] ?? 0); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info mb-0">
                            <i class="bi bi-info-circle me-2"></i>No profile data saved yet. Fill and save the form on the right to get started!
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Edit Profile Form -->
        <div class="col-md-6">
            <div class="card shadow-sm rounded-4 border-0">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h4 class="fw-bold mb-0 profile-card-title">✏️ Update Your Profile</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger py-3 px-4 rounded-3 mb-4 small fw-bold border-0 d-flex align-items-center alert-danger-custom">
                            <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success py-3 px-4 rounded-3 mb-4 small fw-bold border-0 d-flex align-items-center alert-success-custom">
                            <i class="bi bi-check-circle me-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">CGPA (0.0 – 10.0)</label>
                            <input type="number" name="cgpa" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-custom" step="0.1" min="0" max="10"
                                   value="<?php echo $profile['cgpa'] ?? 0; ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">Course / Program</label>
                            <input type="text" name="course" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-custom" placeholder="e.g., B.Tech CSE, M.Sc Physics"
                                   value="<?php echo htmlspecialchars($profile['course'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">State of Origin</label>
                            <input type="text" name="state" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-custom" placeholder="e.g., Maharashtra, Tamil Nadu"
                                   value="<?php echo htmlspecialchars($profile['state_of_origin'] ?? ''); ?>" required>
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3 form-label-custom">Annual Family Income (₹)</label>
                            <input type="number" name="family_income" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3 form-input-custom" min="0"
                                   value="<?php echo $profile['family_income'] ?? 0; ?>" required>
                        </div>

                        <button type="submit" class="btn btn-lg w-100 text-white fw-bold shadow-sm rounded-3 btn-large-primary form-submit-custom">Save Profile Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
