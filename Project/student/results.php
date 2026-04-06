<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'student') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';
require_once '../includes/header.php';

$user_id = $_SESSION['user_id'];

// Fetch student profile
$profile_query = "SELECT * FROM student_profiles WHERE user_id='$user_id'";
$profile_result = $conn->query($profile_query);
$profile = $profile_result->fetch_assoc();

// Check if profile is complete
if (!$profile) {
    echo "<div class='container mt-5'><div class='alert alert-danger'><strong>Error:</strong> Please complete your profile first.</div></div>";
    require_once '../includes/footer.php';
    exit();
}

// Fetch all scholarships
$sch_query = "SELECT * FROM scholarships";
$sch_result = $conn->query($sch_query);

?>

<div class="container mt-4 mb-5">
    <h2 class="fw-bold mb-4" style="color: #002855;">Eligible Scholarships</h2>

    <?php
    $eligible_found = false;
    
    while ($scholarship = $sch_result->fetch_assoc()) {
        // Fetch criteria for this scholarship
        $crit_query = "SELECT * FROM scholarship_criteria WHERE scholarship_id = " . $scholarship['id'];
        $crit_result = $conn->query($crit_query);
        $criteria = $crit_result->fetch_assoc();
        
        // Check eligibility
        $is_eligible = true;
        $reasons = [];
        
        if ($criteria) {
            if ($criteria['minimum_cgpa'] > 0 && $profile['cgpa'] < $criteria['minimum_cgpa']) {
                $is_eligible = false;
                $reasons[] = "CGPA requirement: " . $criteria['minimum_cgpa'];
            }
            if (strtolower(trim($criteria['required_course'])) !== 'any' && strtolower(trim($criteria['required_course'])) !== strtolower(trim($profile['course']))) {
                $is_eligible = false;
                $reasons[] = "Program requirement: " . $criteria['required_course'];
            }
            if (strtolower(trim($criteria['required_state'])) !== 'any' && strtolower(trim($criteria['required_state'])) !== strtolower(trim($profile['state_of_origin']))) {
                $is_eligible = false;
                $reasons[] = "State requirement: " . $criteria['required_state'];
            }
            if ($criteria['maximum_income'] > 0 && $profile['family_income'] > $criteria['maximum_income']) {
                $is_eligible = false;
                $reasons[] = "Income limit: ₹" . $criteria['maximum_income'];
            }
        }
        
        if ($is_eligible) {
            $eligible_found = true;
            ?>
            <div class="card shadow-sm mb-3 border-left border-success border-4">
                <div class="card-body">
                    <h5 class="card-title fw-bold text-success">✓ <?php echo $scholarship['name']; ?></h5>
                    <p class="card-text text-muted"><?php echo $scholarship['description']; ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <small><strong>Amount:</strong> ₹<?php echo number_format($scholarship['amount']); ?></small><br>
                            <small><strong>Provider:</strong> <?php echo $scholarship['provider']; ?></small>
                        </div>
                        <div class="col-md-6">
                            <small><strong>Deadline:</strong> <?php echo $scholarship['deadline']; ?></small>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
    
    if (!$eligible_found) {
        echo "<div class='alert alert-info'>No scholarships available matching your current profile. Keep improving your CGPA!</div>";
    }
    ?>
</div>

<?php require_once '../includes/footer.php'; ?>
