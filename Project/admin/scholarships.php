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
            <h2 class="fw-bold mb-0" style="color: #002855; font-size: 1.8rem;">Scholarships</h2>
            <a href="add_scholarship.php" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm" style="background-color: #002855;">
                <i class="bi bi-plus-circle me-2"></i>+ Add New
            </a>
        </div>
    </div>

    <!-- Scholarships Table -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <?php if (empty($scholarships)): ?>
                        <div class="alert alert-info border-0 rounded-4 p-4" role="alert">
                            <div class="d-flex align-items-center">
                                <i class="bi bi-info-circle me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h5 class="mb-1">No Scholarships Yet</h5>
                                    <p class="mb-0 text-muted">No scholarships have been added to the system. <a href="add_scholarship.php" class="text-decoration-none fw-bold">Add your first scholarship</a> to get started.</p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead style="background-color: #f8f9fb; border-bottom: 2px solid #e9ecef;">
                                    <tr>
                                        <th class="fw-bold text-muted small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #495057 !important;">Scholarships Name</th>
                                        <th class="fw-bold text-muted small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #495057 !important;">Provider</th>
                                        <th class="fw-bold text-muted small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #495057 !important;">Amount (₹)</th>
                                        <th class="fw-bold text-muted small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #495057 !important;">Deadline</th>
                                        <th class="fw-bold text-muted small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.05em; color: #495057 !important;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($scholarships as $s): 
                                        $isUrgent = strtotime($s['deadline']) <= strtotime('+30 days');
                                    ?>
                                    <tr style="border-bottom: 1px solid #e9ecef; vertical-align: middle;">
                                        <td>
                                            <div class="fw-bold" style="color: #002855; font-size: 0.95rem;">
                                                <?php echo htmlspecialchars($s['name']); ?>
                                            </div>
                                            <small class="text-muted d-block mt-1">ID: #<?php echo $s['id']; ?></small>
                                        </td>
                                        <td>
                                            <span class="text-muted"><?php echo htmlspecialchars($s['provider']); ?></span>
                                        </td>
                                        <td>
                                            <span class="fw-bold" style="color: #28a745; font-size: 0.95rem;">₹<?php echo number_format($s['amount']); ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <?php if ($isUrgent): ?>
                                                    <span class="badge bg-danger small">Urgent</span>
                                                <?php endif; ?>
                                                <span class="<?php echo $isUrgent ? 'fw-bold text-danger' : 'text-muted'; ?>">
                                                    <?php echo date('d M Y', strtotime($s['deadline'])); ?>
                                                </span>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2">
                                                <a href="view_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-light border border-secondary text-dark shadow-sm fw-bold small rounded-pill" title="View">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                                <a href="edit_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-light border border-secondary text-dark shadow-sm fw-bold small rounded-pill" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                <a href="delete_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-light border border-danger text-danger shadow-sm fw-bold small rounded-pill" title="Delete" onclick="return confirm('Are you sure you want to delete this scholarship?')">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .table tbody tr:hover {
        background-color: #f8f9fb !important;
        transition: background-color 0.2s ease;
    }
</style>

<?php require_once '../includes/footer.php'; ?>
