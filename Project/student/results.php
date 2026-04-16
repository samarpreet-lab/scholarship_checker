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
    <div class="mb-5">
        <h1 class="fw-bold mb-3" style="font-size: 2.5rem; color: #002855;">Eligible Scholarships</h1>
        <p class="text-muted lead">Based on your academic profile and socioeconomic background, a list of curated opportunities tailored to your eligibility</p>
    </div>

    <?php
    // Count eligible scholarships first
    $sch_query_count = "SELECT * FROM scholarships";
    $sch_result_count = $conn->query($sch_query_count);
    $eligible_count = 0;
    $eligible_scholarships = [];
    
    while ($scholarship = $sch_result_count->fetch_assoc()) {
        $crit_query = "SELECT * FROM scholarship_criteria WHERE scholarship_id = " . $scholarship['id'];
        $crit_result = $conn->query($crit_query);
        $criteria = $crit_result->fetch_assoc();
        
        $is_eligible = true;
        
        if ($criteria) {
            if ($criteria['minimum_cgpa'] > 0 && $profile['cgpa'] < $criteria['minimum_cgpa']) {
                $is_eligible = false;
            }
            if (strtolower(trim($criteria['required_course'])) !== 'any' && strtolower(trim($criteria['required_course'])) !== strtolower(trim($profile['course']))) {
                $is_eligible = false;
            }
            if (strtolower(trim($criteria['required_state'])) !== 'any' && strtolower(trim($criteria['required_state'])) !== strtolower(trim($profile['state_of_origin']))) {
                $is_eligible = false;
            }
            if ($criteria['maximum_income'] > 0 && $profile['family_income'] > $criteria['maximum_income']) {
                $is_eligible = false;
            }
        }
        
        if ($is_eligible) {
            $eligible_count++;
            $eligible_scholarships[] = $scholarship;
        }
    }
    
    // Display badge
    if ($eligible_count > 0) {
        echo '<div class="mb-4">';
        echo '<span class="badge bg-primary text-white px-4 py-2" style="font-size: 0.95rem; background-color: #002855 !important;">';
        echo '✓ You qualify for ' . $eligible_count . ' scholarship' . ($eligible_count > 1 ? 's' : '');
        echo '</span>';
        echo '</div>';
    }
    ?>

    <!-- Scholarship Cards Grid -->
    <div class="row g-4">
        <?php
        if ($eligible_count > 0) {
            foreach ($eligible_scholarships as $scholarship) {
                // Format date
                $deadline = new DateTime($scholarship['deadline']);
                $deadline_formatted = $deadline->format('d M Y');
                
                ?>
                <div class="col-lg-6">
                    <div class="card h-100 scholarship-card shadow-sm border-0" style="transition: transform 0.3s, box-shadow 0.3s; border-radius: 0.75rem;">
                        <div class="card-body p-4" style="display: flex; flex-direction: column;">
                            <!-- Header: Provider and Amount -->
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div>
                                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                                        <i class="bi bi-file-earmark me-1"></i><?php echo $scholarship['provider']; ?>
                                    </small>
                                </div>
                                <div class="text-end">
                                    <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">AMOUNT</small>
                                    <h4 class="fw-bold" style="color: #002855; margin: 0; font-size: 1.8rem;">
                                        ₹<?php echo number_format($scholarship['amount']); ?>
                                    </h4>
                                </div>
                            </div>
                            
                            <!-- Title -->
                            <h5 class="card-title fw-bold mb-3" style="color: #002855; font-size: 1.1rem; line-height: 1.4;">
                                <?php echo $scholarship['name']; ?>
                            </h5>
                            
                            <!-- Description -->
                            <p class="text-muted mb-4 flex-grow-1" style="font-size: 0.9rem; line-height: 1.6;">
                                <?php echo $scholarship['description']; ?>
                            </p>
                            
                            <!-- Footer: Deadline, Status, Button -->
                            <div class="d-flex justify-content-between align-items-center pt-3" style="border-top: 1px solid #e5e7eb;">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Deadline -->
                                    <div>
                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">DEADLINE</small>
                                        <p class="mb-0" style="font-size: 0.9rem; color: #374151;">
                                            <i class="bi bi-calendar3 me-1" style="color: #6b7280;"></i><?php echo $deadline_formatted; ?>
                                        </p>
                                    </div>
                                    
                                    <!-- Status Badge -->
                                    <div>
                                        <small class="text-muted d-block mb-1" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">STATUS</small>
                                        <span class="badge" style="background-color: #10b981; font-size: 0.85rem; padding: 0.4rem 0.8rem;">
                                            <span style="display: inline-block; width: 6px; height: 6px; background-color: #fff; border-radius: 50%; margin-right: 0.4rem; vertical-align: middle;"></span>Open
                                        </span>
                                    </div>
                                </div>
                                
                                <!-- Apply Button -->
                                <a href="<?php echo $scholarship['scholarship_link']; ?>" target="_blank" class="btn text-white fw-bold" 
                                   style="background-color: #002855; padding: 0.6rem 1.2rem; border-radius: 0.4rem; font-size: 0.9rem; transition: all 0.3s; white-space: nowrap;">
                                    Apply Now →
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo '<div class="col-12">';
            echo '<div class="alert alert-info alert-dismissible fade show">';
            echo '<strong>No scholarships available</strong> matching your current profile. Keep improving your CGPA and profile to unlock more opportunities!';
            echo '</div>';
            echo '</div>';
        }
        ?>
    </div>
</div>

<style>
.scholarship-card {
    cursor: pointer;
    border-radius: 0.75rem;
}

.scholarship-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1) !important;
}

.scholarship-card .card-body {
    display: flex;
    flex-direction: column;
}

.scholarship-card .btn:hover {
    transform: translateX(4px);
}
</style>

<?php require_once '../includes/footer.php'; ?>
