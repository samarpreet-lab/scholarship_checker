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

// Count total profiles
$result3 = $conn->query("SELECT COUNT(*) as total FROM student_profiles");
$profiles = $result3->fetch_assoc()['total'];

require_once '../includes/header.php';
?>

<div class="container mt-4 mb-5">
    <div class="row align-items-center mb-5">
        <div class="col-md-8">
            <h1 class="display-6 fw-bold mb-1 admin-title">Administrative Console</h1>
            <p class="text-muted mb-0 lead admin-subtitle">Academic Editorial Scholarship Fund Portal</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0 d-flex justify-content-md-end gap-2">
            <a href="scholarships.php" class="btn px-4 fw-bold text-white shadow-sm rounded-pill admin-btn-primary"><i class="bi bi-gear-fill me-2"></i>Configure Grants</a>
        </div>
    </div>

    <!-- Metrics Row -->
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="card border-0 shadow-sm h-100 rounded-4 text-white admin-metric-card-dark">
                <div class="card-body p-4 position-relative overflow-hidden">
                    <i class="bi bi-people-fill position-absolute opacity-25 admin-metric-icon"></i>
                    <div class="d-flex justify-content-between align-items-center mb-3 position-relative z-1">
                        <div class="badge p-3 rounded-circle bg-white text-primary shadow-sm">
                            <h4 class="m-0"><i class="bi bi-person-badge"></i></h4>
                        </div>
                        <span class="text-white fw-bold small text-uppercase opacity-75">Registered Academics</span>
                    </div>
                    <div class="position-relative z-1">
                        <h2 class="display-5 fw-bold mb-1 text-white"><?php echo $students; ?></h2>
                        <p class="text-white small mb-0 fw-bold opacity-75">Users active on the portal</p>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card card-soft-border shadow-sm h-100 rounded-4 bg-light">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="badge p-3 rounded-circle text-white shadow-sm admin-badge-primary">
                            <h4 class="m-0"><i class="bi bi-award-fill"></i></h4>
                        </div>
                        <span class="text-muted fw-bold small text-uppercase">Active Grants</span>
                    </div>
                    <h2 class="display-5 fw-bold mb-1 admin-metric-title"><?php echo $count; ?></h2>
                    <p class="text-muted small mb-0 fw-bold">Scholarship parameters defined</p>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card card-soft-border shadow-sm h-100 rounded-4 bg-light">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="badge p-3 rounded-circle text-white shadow-sm bg-success">
                            <h4 class="m-0"><i class="bi bi-file-earmark-person-fill"></i></h4>
                        </div>
                        <span class="text-muted fw-bold small text-uppercase">Completed Profiles</span>
                    </div>
                    <h2 class="display-5 fw-bold mb-1 text-success"><?php echo $profiles; ?></h2>
                    <p class="text-muted small mb-0 fw-bold">Academics with fully populated CVs</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card card-soft-border shadow-sm rounded-4 h-100 p-4 bg-light text-center flex-column justify-content-center">
                <div class="mb-3">
                    <i class="bi bi-journal-plus admin-action-icon"></i>
                </div>
                <h4 class="fw-bold admin-action-title">New Scholastic Grant</h4>
                <p class="text-muted small mb-4 px-3">Define a new funding opportunity including course, CGPA, and demographic requirements.</p>
                <div class="mt-auto">
                    <a href="add_scholarship.php" class="btn btn-outline-primary fw-bold rounded-pill mx-auto admin-link" style="border-color: var(--brand); color: var(--brand); width: fit-content;">Access Scholarship Manager</a>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-soft-border shadow-sm rounded-4 h-100 p-4 bg-light text-center flex-column justify-content-center">
                <div class="mb-3">
                    <i class="bi bi-table admin-action-icon"></i>
                </div>
                <h4 class="fw-bold admin-action-title">Dataset Overview</h4>
                <p class="text-muted small mb-4 px-3">Review the master list of all current grants available to researchers and academic students.</p>
                <div class="mt-auto">
                    <a href="scholarships.php" class="btn fw-bold rounded-pill text-white mx-auto shadow-sm admin-btn-primary" style="width: fit-content;">View Complete Dataset</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
