<?php
session_start();

// Protect page
if (!isset($_SESSION['username'], $_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// Only students can access this page
if ($role !== 'student') {
    die("Access denied. Only students can view their classes.");
}

// Base directory for classes
$baseDir = 'C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/';
$classStudentsFile = __DIR__ . '/class_students.json';

// Load class-student assignments
$classStudents = json_decode(file_get_contents($classStudentsFile), true) ?? [];

// Find classes the student is enrolled in
$enrolled = [];
foreach ($classStudents as $classKey => $students) {
    if (in_array($username, $students)) {
        if (strpos($classKey, '_') !== false) {
            list($cls, $sec) = explode('_', $classKey, 2);
        } else {
            $cls = $classKey;
            $sec = 'Default';
        }
        $enrolled[] = ['class' => $cls, 'section' => $sec];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
    <title>My Classes</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        ul { list-style-type: none; padding-left: 0; }
        li { margin-bottom: 15px; }
        select { margin-left: 10px; }
    </style>
</head>
<body>
<h1>Classes You're Enrolled In</h1>

<?php if (empty($enrolled)): ?>
    <p>You are not enrolled in any classes yet.</p>
<?php else: ?>
    <ul>
        <?php foreach ($enrolled as $entry):
            $classDir = $baseDir . $entry['class'] . '/' . $entry['section'];
        ?>
            <li>
                <?= htmlspecialchars($entry['class']) ?> - Section <?= htmlspecialchars($entry['section']) ?>
                <select onchange="if(this.value) window.location.href=this.value">
                    <option value="">Select...</option>

                    <?php if (is_dir($classDir . '/Assignments')): ?>
                        <option value="<?= "assigment.php?class=" . urlencode($entry['class']) . "&section=" . urlencode($entry['section']) . "&type=Assignments" ?>">Assignments</option>
                    <?php endif; ?>

                    <?php if (is_dir($classDir . '/LectureNotes')): ?>
                        <option value="<?= "assigment.php?class=" . urlencode($entry['class']) . "&section=" . urlencode($entry['section']) . "&type=LectureNotes" ?>">Lecture Notes</option>
                    <?php endif; ?>

                </select>
            </li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>

<button onclick="window.history.back()">Go Back</button>
<button onclick="logout.php">Logout</a></p>
</body>
</html>
