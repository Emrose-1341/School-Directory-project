<?php
session_start();

// Protect page
if (!isset($_SESSION['username'], $_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];
$baseDir = 'C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/';
$classStudentsFile = __DIR__ . '/class_students.json';
$usersFile = __DIR__ . '/users_hashed.json';

// Load existing class-student assignments
$classStudents = json_decode(file_get_contents($classStudentsFile), true) ?? [];

// Only teachers can modify class-student assignments
if ($role !== 'teacher') {
    die("Access denied. Only teachers can add students.");
}

// Load all users
$allUsersData = json_decode(file_get_contents($usersFile), true);
$allStudents = [];
foreach ($allUsersData as $u) {
    if ($u['role'] === 'student') {
        $allStudents[] = $u['username'];
    }
}

// Detect classes and sections dynamically
$classes = [];
foreach (scandir($baseDir) as $clsDir) {
    if ($clsDir === '.' || $clsDir === '..') continue;
    $clsPath = $baseDir . $clsDir;
    if (is_dir($clsPath)) {
        $sections = [];
        foreach (scandir($clsPath) as $secDir) {
            if ($secDir === '.' || $secDir === '..') continue;
            if (is_dir($clsPath . '/' . $secDir)) $sections[] = $secDir;
        }
        $classes[$clsDir] = $sections ?: ['Default'];
    }
}

// Teacher: add/update students
$message = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST' &&
    isset($_POST['class_select'], $_POST['section_select'], $_POST['student_select'])) {

    $classKey = $_POST['class_select'] . '_' . $_POST['section_select'];
    $selectedStudent = $_POST['student_select'];

    if (!isset($classStudents[$classKey])) $classStudents[$classKey] = [];
    if (!in_array($selectedStudent, $classStudents[$classKey])) {
        $classStudents[$classKey][] = $selectedStudent;
    }

    file_put_contents($classStudentsFile, json_encode($classStudents, JSON_PRETTY_PRINT));
    $message = "Student '$selectedStudent' added to " . htmlspecialchars($_POST['class_select']) . " " . htmlspecialchars($_POST['section_select']);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
<title>Add Students to Class</title>
</head>
<body>
<div class="centered-container">
<h1>Add Students to Class</h1>
<?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>

<form method="post">
    <label>Select Class:</label>
    <select name="class_select" id="classSelect" required>
        <option value="">--Select--</option>
        <?php foreach ($classes as $cls => $secs): ?>
            <option value="<?= htmlspecialchars($cls) ?>"><?= htmlspecialchars($cls) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <label>Select Section:</label>
    <select name="section_select" id="sectionSelect" required>
        <option value="">--Select--</option>
    </select><br><br>

    <label>Select Student:</label>
    <select name="student_select" required>
        <option value="">--Select Student--</option>
        <?php foreach ($allStudents as $stu): ?>
            <option value="<?= htmlspecialchars($stu) ?>"><?= htmlspecialchars($stu) ?></option>
        <?php endforeach; ?>
    </select><br><br>

    <input type="submit" value="Add Student">
</form>

<h3>Existing Enrollments:</h3>
<?php foreach ($classStudents as $key => $students): ?>
    <strong><?= htmlspecialchars($key) ?>:</strong> <?= htmlspecialchars(implode(', ', $students)) ?><br>
<?php endforeach; ?>

<script>
    const classes = <?= json_encode($classes) ?>;
    const classSelect = document.getElementById('classSelect');
    const sectionSelect = document.getElementById('sectionSelect');

    classSelect.addEventListener('change', () => {
        const cls = classSelect.value;
        sectionSelect.innerHTML = '<option value="">--Select--</option>';
        if (cls in classes) {
            classes[cls].forEach(sec => {
                const opt = document.createElement('option');
                opt.value = sec;
                opt.textContent = sec;
                sectionSelect.appendChild(opt);
            });
        }
    });




</script>

<button onclick="logout.php">Logout</button>
<button onclick="window.history.back()">Go Back</button>
</div>
</body>
</html>
