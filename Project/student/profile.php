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
                    <h4 class="fw-bold mb-0" style="color: #002855;">📋 Your Saved Profile</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($profile):  ?>
                        <div class="mb-4">
                            <small class="text-uppercase fw-bold text-secondary" style="font-size: 0.7rem; letter-spacing: 0.05em;">CGPA</small>
                            <p class="fw-bold mb-0" style="color: #002855; font-size: 1.3rem;"><?php echo (isset($profile['cgpa']) && $profile['cgpa'] !== null) ? $profile['cgpa'] : 'Not set'; ?></p>
                        </div>
                        <div class="mb-4">
                            <small class="text-uppercase fw-bold text-secondary" style="font-size: 0.7rem; letter-spacing: 0.05em;">Course / Program</small>
                            <p class="fw-bold mb-0" style="color: #002855;"><?php echo !empty($profile['course']) ? htmlspecialchars($profile['course']) : 'Not set'; ?></p>
                        </div>
                        <div class="mb-4">
                            <small class="text-uppercase fw-bold text-secondary" style="font-size: 0.7rem; letter-spacing: 0.05em;">State of Origin</small>
                            <p class="fw-bold mb-0" style="color: #002855;"><?php echo !empty($profile['state_of_origin']) ? htmlspecialchars($profile['state_of_origin']) : 'Not set'; ?></p>
                        </div>
                        <div>
                            <small class="text-uppercase fw-bold text-secondary" style="font-size: 0.7rem; letter-spacing: 0.05em;">Annual Family Income</small>
                            <p class="fw-bold mb-0" style="color: #002855;">₹<?php echo number_format($profile['family_income'] ?? 0); ?></p>
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
                    <h4 class="fw-bold mb-0" style="color: #002855;">✏️ Update Your Profile</h4>
                </div>
                <div class="card-body p-4">
                    <?php if ($error): ?>
                        <div class="alert alert-danger py-3 px-4 rounded-3 mb-4 small fw-bold border-0 d-flex align-items-center" style="background-color: #fee; color: #c33;">
                            <i class="bi bi-exclamation-triangle me-2"></i><?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($success): ?>
                        <div class="alert alert-success py-3 px-4 rounded-3 mb-4 small fw-bold border-0 d-flex align-items-center" style="background-color: #e8f5e9; color: #2e7d32;">
                            <i class="bi bi-check-circle me-2"></i><?php echo $success; ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">CGPA (0.0 – 10.0)</label>
                            <input type="number" name="cgpa" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" step="0.1" min="0" max="10"
                                   value="<?php echo $profile['cgpa'] ?? 0; ?>" required style="padding: 12px 16px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">Course / Program</label>
                            <input type="text" name="course" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" placeholder="e.g., B.Tech CSE, M.Sc Physics"
                                   value="<?php echo htmlspecialchars($profile['course'] ?? ''); ?>" required style="padding: 12px 16px;">
                        </div>

                        <div class="mb-4">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">State of Origin</label>
                            <input type="text" name="state" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" placeholder="e.g., Maharashtra, Tamil Nadu"
                                   value="<?php echo htmlspecialchars($profile['state_of_origin'] ?? ''); ?>" required style="padding: 12px 16px;">
                        </div>

                        <div class="mb-5">
                            <label class="form-label text-uppercase fw-bold text-secondary mb-3" style="font-size: 0.75rem; letter-spacing: 0.08em;">Annual Family Income (₹)</label>
                            <input type="number" name="family_income" class="form-control form-control-lg border-0 bg-white shadow-sm rounded-3" min="0"
                                   value="<?php echo $profile['family_income'] ?? 0; ?>" required style="padding: 12px 16px;">
                        </div>

                        <button type="submit" class="btn btn-lg w-100 text-white fw-bold shadow-sm rounded-3" style="background-color: #002855; font-size: 0.95rem; padding: 14px; letter-spacing: 0.5px; transition: all 0.3s;" onmouseover="this.style.backgroundColor='#001a35'; this.style.transform='translateY(-2px)';" onmouseout="this.style.backgroundColor='#002855'; this.style.transform='translateY(0)';">Save Profile Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
