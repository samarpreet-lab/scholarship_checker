<?php


session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$success = '';
$error   = '';

if (!isset($_GET['id'])) {
    header("Location: scholarships.php");
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
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
        $update_scholarship = "UPDATE scholarships SET name='$name', description='$description', amount='$amount', provider='$provider', deadline='$deadline', scholarship_link='$scholarship_link' WHERE id=$id";
        
        if ($conn->query($update_scholarship) === TRUE) {
            $update_criteria = "UPDATE scholarship_criteria SET minimum_cgpa='$min_cgpa', maximum_income='$max_income', required_course='$req_course', required_state='$req_state' WHERE scholarship_id=$id";
            if ($conn->query($update_criteria) === TRUE) {
                $success = "Scholarship updated successfully!";
            } else {
                $error = "Scholarship updated, but failed to save criteria: " . $conn->error;
            }
        } else {
            $error = "Error updating scholarship: " . $conn->error;
        }
    }
}

// Fetch existing data
$result = $conn->query("SELECT s.*, c.minimum_cgpa, c.maximum_income, c.required_course, c.required_state 
                        FROM scholarships s 
                        LEFT JOIN scholarship_criteria c ON s.id = c.scholarship_id 
                        WHERE s.id=$id");

if ($result->num_rows === 0) {
    header("Location: scholarships.php");
    exit();
}

$data = $result->fetch_assoc();

require_once '../includes/header.php';
?>

<div class="card card-soft-border shadow-sm mx-auto" style="max-width: 800px;">
    <div class="card-body">
        <h2 class="card-title primary-color mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Scholarship</h2>

        <?php if ($error) { ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php } ?>

        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Scholarship Name *</label>
                    <input type="text" name="name" class="form-control" required value="<?php echo $data['name']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Provider / Organization *</label>
                    <input type="text" name="provider" class="form-control" required value="<?php echo $data['provider']; ?>">
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?php echo $data['description']; ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Amount (₹) *</label>
                    <input type="number" name="amount" class="form-control" min="0" required value="<?php echo $data['amount']; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Application Deadline *</label>
                    <input type="date" name="deadline" class="form-control" required value="<?php echo $data['deadline']; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label class="form-label">Scholarship Link *</label>
                    <input type="url" name="scholarship_link" class="form-control" required value="<?php echo $data['scholarship_link']; ?>" placeholder="e.g. https://www.scholarship.example.com/apply">
                </div>
            </div>

            <hr class="my-4">
            <h4 class="mb-3 text-secondary">Eligibility Criteria</h4>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Minimum CGPA Required</label>
                    <input type="number" name="minimum_cgpa" class="form-control" step="0.1" min="0" max="10" required value="<?php echo $data['minimum_cgpa'] ?? 0; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Max Annual Family Income (₹)</label>
                    <input type="number" name="maximum_income" class="form-control" required value="<?php echo $data['maximum_income'] ?? 1000000; ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Required Course (or "Any")</label>
                    <input type="text" name="required_course" class="form-control" required value="<?php echo $data['required_course'] ?? 'Any'; ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Required State (or "Any")</label>
                    <input type="text" name="required_state" class="form-control" required value="<?php echo $data['required_state'] ?? 'Any'; ?>">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
                <a href="scholarships.php" class="btn btn-outline-secondary px-4 fw-bold shadow-sm"><i class="bi bi-x-circle me-2"></i>Discard Changes</a>
                <button type="submit" class="btn btn-primary px-4 fw-bold shadow-sm"><i class="bi bi-check2-circle me-2"></i>Update Configuration</button>
            </div>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>