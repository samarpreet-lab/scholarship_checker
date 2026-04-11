<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$success = '';
$error   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Scholarship fields
    $name        = trim($_POST['name']);
    $description = trim($_POST['description']);
    $amount      = intval($_POST['amount']);
    $provider    = trim($_POST['provider']);
    $deadline    = $_POST['deadline'];
    $scholarship_link = trim($_POST['scholarship_link']);

    // Criteria fields
    $min_cgpa      = floatval($_POST['minimum_cgpa']);
    $max_income    = intval($_POST['maximum_income']);
    $req_course    = trim($_POST['required_course']);
    $req_state     = trim($_POST['required_state']);

    if (empty($name) || empty($provider) || empty($deadline) || empty($scholarship_link)) {
        $error = "Please fill all required fields.";
    } else {
        // Insert scholarship using basic query
        $insert_scholarship = "INSERT INTO scholarships (name, description, amount, provider, deadline, scholarship_link) VALUES ('$name', '$description', '$amount', '$provider', '$deadline', '$scholarship_link')";
        if ($conn->query($insert_scholarship) === TRUE) {
            $scholarship_id = $conn->insert_id;

            // Insert criteria using basic query with new field names
            $insert_criteria = "INSERT INTO scholarship_criteria (scholarship_id, minimum_cgpa, maximum_income, required_course, required_state) VALUES ('$scholarship_id', '$min_cgpa', '$max_income', '$req_course', '$req_state')";
            if ($conn->query($insert_criteria) === TRUE) {
                $success = "Scholarship added successfully!";
            } else {
                $error = "Scholarship added, but failed to save criteria: " . $conn->error;
            }
        } else {
            $error = "Error adding scholarship: " . $conn->error;
        }
    }
}

require_once '../includes/header.php';
?>

<div class="card shadow-sm mx-auto" style="max-width: 800px;">
    <div class="card-body">
        <h2 class="card-title primary-color mb-4">Add New Scholarship</h2>

        <?php if ($error): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Scholarship Name *</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Merit Excellence Award">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Provider / Organization *</label>
                    <input type="text" name="provider" class="form-control" required placeholder="e.g. Ministry of Education">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3" placeholder="Brief description..."></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Application Deadline *</label>
                    <input type="date" name="deadline" class="form-control" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Scholarship Link *</label>
                    <input type="url" name="scholarship_link" class="form-control" required placeholder="e.g. https://www.scholarship.example.com/apply">
                </div>
            </div>

            <hr class="my-4">
            <h4 class="mb-3 text-secondary">Eligibility Criteria</h4>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Minimum CGPA Required</label>
                    <input type="number" name="minimum_cgpa" class="form-control" step="0.1" min="0" max="10" value="0" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Max Annual Family Income (₹)</label>
                    <input type="number" name="maximum_income" class="form-control" value="1000000" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Required Course (or "Any")</label>
                    <input type="text" name="required_course" class="form-control" placeholder="e.g. B.Tech CSE, or 'Any'" value="Any" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Required State (or "Any")</label>
                    <input type="text" name="required_state" class="form-control" placeholder="e.g. Maharashtra, or 'Any'" value="Any" required>
                </div>
            </div>

            <div class="d-flex gap-3 mt-4 pt-3 border-top">
                <a href="scholarships.php" class="btn btn-outline-secondary px-4 fw-bold shadow-sm"><i class="bi bi-x-circle me-2"></i>Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm"><i class="bi bi-check2-circle me-2"></i>Save Grant Configuration</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
