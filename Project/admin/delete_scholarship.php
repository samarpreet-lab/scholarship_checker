<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

require_once '../config/db.php';

$id = intval($_GET['id']);

if ($id > 0) {
    $delete_query = "DELETE FROM scholarships WHERE id='$id'";
    $conn->query($delete_query);
}

header("Location: scholarships.php");
exit();
?>
