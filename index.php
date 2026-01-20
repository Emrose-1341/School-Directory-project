<?php
session_start();

// Protect page: only logged-in users
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($username); ?>!</h1>

    <nav>
        <a href="dashboard.php">Home</a>

        <!-- Teacher-only links -->
        <?php if ($role === 'teacher'): ?>
            <a href="upload.php">Upload Materials</a>
            <a href="teacher_reports.php">Reports</a>
        <?php endif; ?>

        <!-- Student-only links -->
        <?php if ($role === 'student'): ?>
            <a href="view_materials.php">View Materials</a>
            <a href="submit_assignments.php">Submit Assignments</a>
        <?php endif; ?>

        <a href="logout.php">Logout</a>
    </nav>
</body>
</html>
