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

<div class="card shadow-sm">
    <div class="card-body">
        <h2 class="card-title text-primary mb-4">All Scholarships</h2>
        <a href="add_scholarship.php" class="btn btn-primary mb-3">+ Add New</a>

        <?php if (empty($scholarships)): ?>
            <div class="alert alert-info">No scholarships added yet.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Name</th>
                            <th>Provider</th>
                            <th>Amount (₹)</th>
                            <th>Deadline</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scholarships as $s): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($s['name']); ?></td>
                            <td><?php echo htmlspecialchars($s['provider']); ?></td>
                            <td>₹<?php echo number_format($s['amount']); ?></td>
                            <td><?php echo $s['deadline']; ?></td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="view_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-primary shadow-sm"><i class="bi bi-eye"></i> View</a>
                                    <a href="edit_scholarship.php?id=<?php echo $s['id']; ?>" class="btn btn-sm btn-outline-secondary shadow-sm"><i class="bi bi-pencil"></i> Edit</a>
                                    <a href="delete_scholarship.php?id=<?php echo $s['id']; ?>"
                                       class="btn btn-sm btn-outline-danger shadow-sm"
                                       onclick="return confirm('Delete this scholarship?')"><i class="bi bi-trash"></i> Delete</a>
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

<?php require_once '../includes/footer.php'; ?>
