<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

if (!isset($_GET['id'])) {
    header("Location: scholarships.php");
    exit();
}

$id = intval($_GET['id']);
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

<div class="card card-soft-border shadow-sm mx-auto mb-5" style="max-width: 800px;">
    <div class="card-body p-5">
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h2 class="card-title primary-color mb-0"><i class="bi bi-file-earmark-text me-2"></i>Scholarship Details</h2>
            <div class="badge bg-primary-custom px-3 py-2 rounded-pill fs-6 border border-white">
                ₹<?php echo number_format($data['amount']); ?>
            </div>
        </div>

        <div class="row g-4 mb-4">
            <div class="col-md-12">
                <h3 class="fw-bold mb-1" style="color: #002855;"><?php echo htmlspecialchars($data['name']); ?></h3>
                <p class="text-muted"><i class="bi bi-building"></i> Provided by <strong><?php echo htmlspecialchars($data['provider']); ?></strong></p>
                <div class="p-3 bg-light rounded-3 mt-2">
                    <p class="mb-0 text-secondary"><?php echo nl2br(htmlspecialchars($data['description'] ?: 'No description available.')); ?></p>
                </div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-md-6">
                <h5 class="primary-color mb-3 border-bottom pb-2">Status overview</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                        <span class="text-muted"><i class="bi bi-calendar-event me-2"></i>Deadline</span>
                        <span class="fw-bold text-danger"><?php echo htmlspecialchars($data['deadline']); ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                        <span class="text-muted"><i class="bi bi-clock-history me-2"></i>Created at</span>
                        <span class="fw-bold"><?php echo htmlspecialchars($data['created_at']); ?></span>
                    </li>
                </ul>
            </div>
            
            <div class="col-md-6">
                <h5 class="primary-color mb-3 border-bottom pb-2">Eligibility Criteria</h5>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                        <span class="text-muted">Min CGPA</span>
                        <span class="badge bg-success rounded-pill px-3"><?php echo htmlspecialchars($data['minimum_cgpa'] ?? 'N/A'); ?> +</span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                        <span class="text-muted">Max Family Income</span>
                        <span class="fw-bold">₹<?php echo number_format($data['maximum_income'] ?? 0); ?></span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                        <span class="text-muted">Course Requirement</span>
                        <span class="fw-bold">
                            <?php echo htmlspecialchars($data['required_course'] === 'Any' || !$data['required_course'] ? 'Open to all' : $data['required_course']); ?>
                        </span>
                    </li>
                    <li class="list-group-item px-0 d-flex justify-content-between align-items-center bg-transparent">
                        <span class="text-muted">State Requirement</span>
                        <span class="fw-bold">
                            <?php echo htmlspecialchars($data['required_state'] === 'Any' || !$data['required_state'] ? 'All States' : $data['required_state']); ?>
                        </span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 mt-4 pt-4 border-top">
            <a href="scholarships.php" class="btn btn-outline-secondary px-4 fw-bold shadow-sm"><i class="bi bi-arrow-left me-2"></i>Back to Directory</a>
            <a href="edit_scholarship.php?id=<?php echo $id; ?>" class="btn btn-primary px-4 fw-bold shadow-sm"><i class="bi bi-pencil-square me-2"></i>Edit Grant Parameters</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>