<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Helper function to get the base URL relative to current location
function getBasePath() {
    $current_dir = dirname($_SERVER['PHP_SELF']);
    // Count how many directories deep we are (remove /scholarship_checker/Project from the start)
    $depth = substr_count($current_dir, '/') - substr_count('/scholarship_checker/Project', '/');
    return str_repeat('../', max(0, $depth - 1));
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
    </style>
</head>
<body>

<?php 
// Only show the navbar if we are NOT on the login or register page
$current_page = basename($_SERVER['PHP_SELF']);
if ($current_page !== 'index.php' && $current_page !== 'register.php'):
?>
<nav class="navbar navbar-expand-lg navbar-light shadow-sm py-3" style="background-color: #002855;">
    <div class="container-fluid px-4">
        <a href="<?php echo $base; ?>index.php" class="navbar-brand mb-0 h1 fw-bold" style="letter-spacing: 0.05em; font-size: 1.2rem; color: white; text-decoration: none;">🏛️ CIVITAS ACADEMIC</a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end" id="navbarContent">
            <ul class="navbar-nav align-items-lg-center">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item d-none d-lg-block me-3"><span class="text-white-50 small">Hello, <?php echo htmlspecialchars($_SESSION['name']); ?></span></li>
                    <?php if ($_SESSION['role'] === 'admin'): ?>
                        <li class="nav-item"><a class="nav-link text-white fw-semibold me-3" href="<?php echo $base; ?>admin/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="<?php echo $base; ?>admin/scholarships.php">Scholarships</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link text-white fw-semibold me-3" href="<?php echo $base; ?>student/dashboard.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-semibold me-3" href="<?php echo $base; ?>student/profile.php">Profile</a></li>
                        <li class="nav-item"><a class="nav-link text-white fw-semibold" href="<?php echo $base; ?>student/results.php">My Results</a></li>
                    <?php endif; ?>
                    <li class="nav-item ms-lg-4 mt-3 mt-lg-0">
                        <a class="btn btn-sm btn-light fw-bold px-3 py-2 text-primary" href="<?php echo $base; ?>logout.php" style="border-radius: 8px;">Logout</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<?php endif; ?>

<div class="container-fluid p-0">
