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
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Manrope:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { 
            background-color: #f8f9fb; 
            font-family: 'Inter', sans-serif;
        }
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Manrope', sans-serif;
        }
        .primary-color { color: #002855; }
        .bg-primary-custom { background-color: #002855; color: white; }
        
        /* Enhanced form styling */
        .form-control:focus {
            border-color: #002855 !important;
            box-shadow: 0 0 0 0.2rem rgba(0, 40, 85, 0.15) !important;
            background-color: #ffffff !important;
        }
        
        .form-control {
            transition: all 0.3s ease;
            background-color: #ffffff;
            padding: 12px 16px !important;
            font-size: 0.95rem;
        }
        
        .form-control::placeholder {
            color: #b0b8c1;
            font-weight: 400;
        }
        
        /* Button hover effects */
        .btn:active {
            transform: translateY(0) !important;
        }

        /* Top Navbar Styling */
        .top-navbar {
            background-color: white;
            border-bottom: 1px solid #e9ecef;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        /* Left Sidebar Styling */
        .sidebar {
            background-color: #f8f9fb;
            border-right: 1px solid #e9ecef;
            position: fixed;
            left: 0;
            top: 70px;
            width: 240px;
            height: calc(100vh - 70px);
            overflow-y: auto;
            padding: 20px 0;
            z-index: 999;
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .sidebar-menu li {
            margin: 0;
        }

        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #495057;
            text-decoration: none;
            transition: all 0.3s ease;
            border-left: 3px solid transparent;
        }

        .sidebar-menu a:hover {
            background-color: #e9ecef;
            border-left-color: #002855;
            color: #002855;
        }

        .sidebar-menu a.active {
            background-color: #e8f1ff;
            border-left-color: #002855;
            color: #002855;
            font-weight: 600;
        }

        .sidebar-menu i {
            margin-right: 12px;
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        /* Main content area with sidebar offset */
        .main-content {
            margin-left: 240px;
            margin-top: 70px;
        }

        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 200px;
                z-index: 998;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
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
                <button class="btn btn-sm d-lg-none me-3" id="sidebarToggle" style="color: #002855; border: none; padding: 0;">
                    <i class="bi bi-list" style="font-size: 1.5rem;"></i>
                </button>
                <div>
                    <a href="<?php echo $base; ?>student/dashboard.php" class="text-decoration-none" style="color: #002855;">
                        <h5 class="mb-0 fw-bold">🏛️ Scholarship Portal</h5>
                        <small class="text-muted" style="font-size: 0.7rem; letter-spacing: 0.05em;">ACADEMIC EDITORIAL</small>
                    </a>
                </div>
            <?php else: ?>
                <a href="<?php echo $base; ?>admin/dashboard.php" class="text-decoration-none" style="color: #002855;">
                    <h5 class="mb-0 fw-bold">🏛️ Admin Portal</h5>
                    <small class="text-muted" style="font-size: 0.7rem; letter-spacing: 0.05em;">ADMINISTRATION</small>
                </a>
            <?php endif; ?>
        </div>

        <div class="d-flex align-items-center gap-4">
            <?php if ($is_student): ?>
                <div class="d-none d-lg-flex gap-4">
                    <a href="<?php echo $base; ?>student/dashboard.php" class="text-decoration-none fw-semibold" style="color: #495057; font-size: 0.95rem;">Dashboard</a>
                    <a href="<?php echo $base; ?>student/results.php" class="text-decoration-none fw-semibold" style="color: #495057; font-size: 0.95rem;">Scholarships</a>
                </div>
            <?php else: ?>
                <div class="d-none d-lg-flex gap-4">
                    <a href="<?php echo $base; ?>admin/dashboard.php" class="text-decoration-none fw-semibold" style="color: #495057; font-size: 0.95rem;">Dashboard</a>
                    <a href="<?php echo $base; ?>admin/scholarships.php" class="text-decoration-none fw-semibold" style="color: #495057; font-size: 0.95rem;">Scholarships</a>
                </div>
            <?php endif; ?>

            <div class="d-flex align-items-center gap-3">
                <button class="btn btn-link" style="color: #495057; font-size: 1.2rem; border: none; padding: 0;">
                    <i class="bi bi-bell"></i>
                </button>
                <div class="dropdown">
                    <button class="btn btn-sm rounded-circle" data-bs-toggle="dropdown" aria-expanded="false" style="background-color: #002855; color: white; width: 36px; height: 36px; border: none; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <i class="bi bi-person-fill"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 200px;">
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

<?php if ($is_student): ?>
<!-- Left Sidebar (Student Only) -->
<aside class="sidebar">
    <ul class="sidebar-menu">
        <li><a href="<?php echo $base; ?>student/dashboard.php" class="<?php echo ($current_page === 'dashboard.php') ? 'active' : ''; ?>"><i class="bi bi-house-fill"></i>Dashboard</a></li>
        <li><a href="<?php echo $base; ?>student/results.php" class="<?php echo ($current_page === 'results.php') ? 'active' : ''; ?>"><i class="bi bi-check-circle-fill"></i>Eligibility</a></li>
        <li><a href="<?php echo $base; ?>student/profile.php" class="<?php echo ($current_page === 'profile.php') ? 'active' : ''; ?>"><i class="bi bi-person-circle"></i>Profile</a></li>
    </ul>
</aside>

<!-- Main Content Wrapper (Student) -->
<div class="main-content">
<?php endif; ?>

<?php endif; ?>

<div class="container-fluid p-0">
