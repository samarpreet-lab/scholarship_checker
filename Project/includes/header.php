<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper function to get the base URL relative to current location
function getBasePath() {
    $current_dir = dirname($_SERVER['SCRIPT_NAME']);
    $current_folder = basename($current_dir);

    if (in_array($current_folder, ['student', 'admin'], true)) {
        return '../';
    }

    return '';
}

$base = getBasePath();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Editorial Portal</title>
    <link rel="icon" type="image/svg+xml" href="<?php echo $base; ?>favicon.svg">
    <link rel="shortcut icon" type="image/svg+xml" href="<?php echo $base; ?>favicon.svg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <!-- Custom Stylesheet -->
    <link rel="stylesheet" href="<?php echo $base; ?>css/styles.css">
</head>
<body>

<?php 
// Navbar logic: show different UI based on role and current page
$current_page = basename($_SERVER['PHP_SELF']);
$is_auth_page = ($current_page === 'index.php' || $current_page === 'register.php');
$is_authenticated = isset($_SESSION['user_id']);
$is_student = ($is_authenticated && $_SESSION['role'] === 'student');
$is_admin = ($is_authenticated && $_SESSION['role'] === 'admin');

if ($is_authenticated && !$is_auth_page): 
?>

<!-- Top Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light top-navbar">
    <div class="container-fluid px-4 py-3 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <?php if ($is_student): ?>
                <button class="btn btn-sm d-lg-none me-3" id="sidebarToggle" class="navbar-toggle">
                    <i class="bi bi-list icon-toggle-custom"></i>
                </button>
                <div>
                    <a href="<?php echo $base; ?>student/dashboard.php" class="text-decoration-none navbar-brand-link">
                        <h5 class="mb-0 fw-bold">🏛️ Scholarship Portal</h5>
                        <small class="text-muted navbar-brand-subtitle">ACADEMIC EDITORIAL</small>
                    </a>
                </div>
            <?php else: ?>
                <a href="<?php echo $base; ?>admin/dashboard.php" class="text-decoration-none navbar-brand-link">
                    <h5 class="mb-0 fw-bold">🏛️ Admin Portal</h5>
                    <small class="text-muted navbar-brand-subtitle">ADMINISTRATION</small>
                </a>
            <?php endif; ?>
        </div>

        <div class="d-flex align-items-center gap-4">
            <?php if ($is_student): ?>
                <div class="d-none d-lg-flex gap-4">
                    <a href="<?php echo $base; ?>student/dashboard.php" class="text-decoration-none fw-semibold navbar-link">Dashboard</a>
                    <a href="<?php echo $base; ?>student/results.php" class="text-decoration-none fw-semibold navbar-link">Scholarships</a>
                </div>
            <?php else: ?>
                <div class="d-none d-lg-flex gap-4">
                    <a href="<?php echo $base; ?>admin/dashboard.php" class="text-decoration-none fw-semibold navbar-link">Dashboard</a>
                    <a href="<?php echo $base; ?>admin/scholarships.php" class="text-decoration-none fw-semibold navbar-link">Scholarships</a>
                </div>
            <?php endif; ?>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link navbar-icon-btn">
                    <i class="bi bi-bell"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm rounded-circle navbar-profile-btn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end navbar-dropdown">
                        <li><span class="dropdown-item-text small fw-bold"><?php echo htmlspecialchars($_SESSION['name']); ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <?php if ($is_student): ?>
                            <li><a class="dropdown-item" href="<?php echo $base; ?>student/profile.php">Profile Settings</a></li>
                        <?php endif; ?>
                        <li><a class="dropdown-item" href="<?php echo $base; ?>logout.php">Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>

<!-- Left Sidebar -->
<aside class="sidebar">
    <ul class="sidebar-menu">
        <?php if ($is_student): ?>
            <li><a href="<?php echo $base; ?>student/dashboard.php" class="<?php echo ($current_page === 'dashboard.php') ? 'active' : ''; ?>"><i class="bi bi-house-fill"></i>Dashboard</a></li>
            <li><a href="<?php echo $base; ?>student/results.php" class="<?php echo ($current_page === 'results.php') ? 'active' : ''; ?>"><i class="bi bi-check-circle-fill"></i>Eligibility</a></li>
            <li><a href="<?php echo $base; ?>student/profile.php" class="<?php echo ($current_page === 'profile.php') ? 'active' : ''; ?>"><i class="bi bi-person-circle"></i>Profile</a></li>
        <?php else: ?>
            <li><a href="<?php echo $base; ?>admin/dashboard.php" class="<?php echo ($current_page === 'dashboard.php') ? 'active' : ''; ?>"><i class="bi bi-house-fill"></i>Overview</a></li>
            <li><a href="<?php echo $base; ?>admin/scholarships.php" class="<?php echo ($current_page === 'scholarships.php') ? 'active' : ''; ?>"><i class="bi bi-award-fill"></i>Grants Directory</a></li>
            <li><a href="<?php echo $base; ?>admin/add_scholarship.php" class="<?php echo ($current_page === 'add_scholarship.php') ? 'active' : ''; ?>"><i class="bi bi-plus-circle-fill"></i>New Grant</a></li>
        <?php endif; ?>
    </ul>
</aside>

<!-- Main Content Wrapper -->
<div class="main-content">

<?php endif; ?>

<div class="container-fluid p-0">
