<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}
require_once '../includes/header.php';
require_once '../config/db.php';

$user_id = $_SESSION['user_id'];
$student_query = $conn->query("SELECT * FROM student_profiles WHERE user_id = $user_id");
$student = ($student_query->num_rows > 0) ? $student_query->fetch_assoc() : null;

$eligible_count = 0;
// Only calculate eligibility if student exists and has filled out their profile minimums.
if ($student && isset($student['cgpa']) && isset($student['course'])) {
    $sch_query = $conn->query("SELECT * FROM scholarships");
    while ($sch_query && $sch = $sch_query->fetch_assoc()) {
        $crit_query = $conn->query("SELECT * FROM scholarship_criteria WHERE scholarship_id = " . $sch['id']);
        $crit = ($crit_query) ? $crit_query->fetch_assoc() : null;
        
        $is_eligible = true;
        if ($crit) {
            if ($crit['minimum_cgpa'] > $student['cgpa']) $is_eligible = false;
            // Case-insensitive comparison for course and state
            if (strtolower(trim($crit['required_course'])) != 'any' && strtolower(trim($crit['required_course'])) != strtolower(trim($student['course']))) $is_eligible = false;
            if (strtolower(trim($crit['required_state'])) != 'any' && strtolower(trim($crit['required_state'])) != strtolower(trim($student['state_of_origin']))) $is_eligible = false;
            if ($crit['maximum_income'] > 0 && $student['family_income'] > $crit['maximum_income']) $is_eligible = false;
        }
        
        if ($is_eligible) {
            $eligible_count++;
        }
    }
}
?>

<div class="container mt-4 mb-5">
    <!-- Dashboard Header Area -->
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-1 dashboard-title">Welcome, <?php echo $_SESSION['name']; ?></h1>
            <p class="text-muted mb-0 lead dashboard-subtitle">Academic Editorial Scholarship Fund Portal Overview</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
            <a href="<?php echo $base; ?>student/profile.php" class="btn btn-outline-primary px-4 fw-bold dashboard-btn-outline"><i class="bi bi-person-fill me-2"></i>Curriculum Vitae</a>
            <a href="<?php echo $base; ?>student/results.php" class="btn px-4 fw-bold text-white shadow-sm dashboard-btn-primary"><i class="bi bi-search me-2"></i>Browse Grants</a>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card card-soft-border shadow-sm h-100 rounded-4 bg-light">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="badge p-3 rounded-circle text-white shadow-sm badge-brand">
                            <h4 class="m-0"><i class="bi bi-mortarboard-fill"></i></h4>
                        </div>
                        <span class="text-muted fw-bold small text-uppercase">Profile Status</span>
                    </div>
                    <?php if ($student) { ?>
                        <h2 class="display-5 fw-bold mb-1 dashboard-stat-title">Complete</h2>
                        <p class="text-muted small mb-0 fw-bold"><span class="text-success"><i class="bi bi-check-circle-fill"></i> Verified</span> Academic Profile</p>
                    <?php } else { ?>
                        <h2 class="display-5 fw-bold mb-1 text-danger">Pending</h2>
                        <p class="text-muted small mb-0 fw-bold"><a href="profile.php" class="text-danger">Update CV data</a> to unlock grants</p>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-soft-border-dark shadow-sm h-100 rounded-4 text-white dashboard-stat-card-dark">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <i class="bi bi-award position-absolute opacity-25 dashboard-stat-icon"></i>
                    
                    <div class="d-flex justify-content-between align-items-center mb-3 position-relative z-1">
                        <div class="badge p-3 rounded-circle bg-white text-primary shadow-sm">
                            <h4 class="m-0"><i class="bi bi-journal-check"></i></h4>
                        </div>
                        <span class="text-white fw-bold small text-uppercase opacity-75">Eligible Grants</span>
                    </div>
                    
                    <div class="position-relative z-1">
                        <h2 class="display-5 fw-bold mb-1 text-white"><?php echo $eligible_count; ?></h2>
                        <p class="text-white small mb-0 fw-bold opacity-75">Funds you qualify for based on demographics</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-soft-border shadow-sm h-100 rounded-4 bg-light">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="badge p-3 rounded-circle text-white shadow-sm badge-brand">
                            <h4 class="m-0"><i class="bi bi-clock-history"></i></h4>
                        </div>
                        <span class="text-muted fw-bold small text-uppercase">Upcoming</span>
                    </div>
                    <h2 class="display-5 fw-bold mb-1 dashboard-stat-title">0</h2>
                    <p class="text-muted small mb-0 fw-bold">Active institutional deadlines</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity / Info Section -->
    <div class="row g-4">
        <div class="col-md-8">
            <div class="card card-soft-border shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h4 class="fw-bold mb-0 dashboard-card-title">Portal Guidelines</h4>
                </div>
                <div class="card-body p-4">
                    <?php if (!$student) { ?>
                        <div class="p-4 rounded-4 text-center mb-3 bg-light border border-danger border-2 border-opacity-25">
                            <h5 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle-fill me-2"></i>Action Required</h5>
                            <p class="text-muted text-start mb-3 guidelines-description">You must complete your Academic Curriculum Vitae (Profile) before the system can match you with eligible institutional funding. Your demographic and academic parameters are required to run the matching algorithm.</p>
                            <div class="text-start">
                                <a href="profile.php" class="btn btn-danger fw-bold shadow-sm px-4 rounded-pill">Complete CV Requirements Now</a>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="d-flex align-items-start mb-4 bg-light p-3 rounded-4">
                            <div class="me-3 mt-1">
                                <h4 class="text-success"><i class="bi bi-check-circle-fill"></i></h4>
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1 dashboard-card-title">Profile Up to Date</h6>
                                <p class="text-muted small mb-0">Your demographic information, enrolled program (<?php echo !empty($student['course']) ? $student['course'] : 'Not set'; ?>), and financial parameters are configured and active.</p>
                            </div>
                        </div>
                    <?php } ?>
                    
                    <h6 class="fw-bold text-uppercase mt-4 mb-3 guidelines-heading">Next Steps</h6>
                    <ul class="list-group list-group-flush border-0 mb-0">
                        <li class="list-group-item px-0 py-3 border-bottom-0 d-flex">
                            <span class="badge rounded-circle me-3 d-flex align-items-center justify-content-center text-white badge-step">1</span>
                            <div>
                                <h6 class="fw-bold mb-1 dashboard-card-title">Populate Demographic Profile</h6>
                                <p class="text-muted small mb-0">Input CGPA, financial bracket, and localized origin under <a href="profile.php" class="text-decoration-none fw-bold dashboard-link">Profile</a>.</p>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 border-bottom-0 d-flex pb-0">
                            <span class="badge rounded-circle me-3 d-flex align-items-center justify-content-center text-white badge-step">2</span>
                            <div>
                                <h6 class="fw-bold mb-1 dashboard-card-title">Execute Eligibility Algorithm</h6>
                                <p class="text-muted small mb-0">Navigate to the <a href="results.php" class="text-decoration-none fw-bold dashboard-link">Grants</a> tab to review verified funding matches specific to your parameters.</p>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-soft-border shadow-sm rounded-4 text-center h-100 bg-light p-4 d-flex flex-column justify-content-center align-items-center">
                <div class="mb-4">
                    <img src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['name']); ?>&background=002855&color=fff&size=120" class="rounded-circle shadow-sm border border-white border-4">
                </div>
                <h5 class="fw-bold mb-1 dashboard-card-title"><?php echo $_SESSION['name']; ?></h5>
                <p class="text-muted small mb-3">Student</p>
                <div class="w-100 mt-2 px-3">
                    <a href="profile.php" class="btn btn-outline-secondary w-100 fw-bold border-2 rounded-pill"><i class="bi bi-pencil-square me-2"></i>Edit Parameters</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
