<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$result = $conn->query("SELECT * FROM scholarships ORDER BY created_at DESC");

// Store results in array
$scholarships = [];
while ($row = $result->fetch_assoc()) {
    $scholarships[] = $row;
}

require_once '../includes/header.php';
?>

<div class="container-fluid px-4 pt-1">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0 scholarships-title">Scholarships</h2>
            <a href="add_scholarship.php" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm scholarships-btn-add" style="background-color: var(--brand);">
                <i class="bi bi-plus-circle me-2"></i>+ Add New
            </a>
        </div>
    </div>

    <!-- Scholarships Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <?php if (empty($scholarships)) { ?>
                        <div class="alert alert-info border-0 rounded-4 p-4">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3 scholarships-icon"></i>
                                <div>
                                    <h5 class="mb-1">No Scholarships Yet</h5>
                                    <p class="mb-0 text-muted">No scholarships have been added to the system. <a href="add_scholarship.php" class="text-decoration-none fw-bold">Add your first scholarship</a> to get started.</p>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0 scholarships-table">
                                <thead>
                                    <tr>
                                        <th class="scholarships-th">Scholarships Name</th>
                                        <th class="scholarships-th">Provider</th>
                                        <th class="scholarships-th">Amount (₹)</th>
                                        <th class="scholarships-th">Deadline</th>
                                        <th class="scholarships-th">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scholarships as $s) { 
                                        $isUrgent = strtotime($s['deadline']) <= strtotime('+30 days');
                                    ?>
                                    <tr class="scholarships-row">
                                        <td>
                                            <div class="scholarships-name">
                                                <?php echo $s['name']; ?>
                                            </div>
                                            <small class="text-muted d-block mt-1">ID: #<?php echo $s['id']; ?></small>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo $s['provider']; ?></span>
                                        </td>
                                        <td>
                                            <span class="scholarships-amount">₹<?php echo number_format($s['amount']); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($isUrgent) { ?>
                                                    <span class="badge bg-danger small">Urgent</span>
                                                <?php } ?>
                                                <span class="<?php echo $isUrgent ? 'fw-bold text-danger' : 'text-muted'; ?>">
                                                    <?php echo date('d M Y', strtotime($s['deadline'])); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="view_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-light border border-secondary text-dark shadow-sm fw-bold small rounded-pill">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-light border border-secondary text-dark shadow-sm fw-bold small rounded-pill">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-light border border-danger text-danger shadow-sm fw-bold small rounded-pill" onclick="return confirm('Are you sure you want to delete this scholarship?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
