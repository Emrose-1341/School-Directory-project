<?php
session_start();

// -------------------------
// Ensure user is logged in
// -------------------------
if (!isset($_SESSION['username'], $_SESSION['role'])) {
    die("Access denied. Not logged in.");
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

// -------------------------
// Base directories
// -------------------------
$baseDir = "C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/";
$classStudentsFile = __DIR__ . '/class_students.json';
$classStudents = json_decode(file_get_contents($classStudentsFile), true) ?? [];

// -------------------------
// Type is always 'Assignments'
// -------------------------
$type = 'Assignments';

// -------------------------
// Handle student & teacher class selection
// -------------------------
if ($role === 'teacher' && !isset($_POST['open_class'])) {
    $courseFolders = array_filter(glob($baseDir . "*"), 'is_dir');
    echo "<!DOCTYPE html>
    <html>
    <head>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
    <div class='centered-container'>
        <h1>Select Class & Section</h1>
        <form method='POST'>
            <label>Class:</label><br>
            <select name='class' id='classSelect' required>
                <option value=''>-- Select Class --</option>";
    foreach ($courseFolders as $folder) {
        $name = basename($folder);
        if ($name === "People") continue;
        echo "<option value='$name'>$name</option>";
    }
    echo "</select><br><br>
          <label>Section:</label><br>
          <select name='section' id='sectionSelect' required>
            <option value=''>-- Select Section --</option>
          </select><br><br>
          <button type='submit' name='open_class'>Open Class</button>
        <script>
        document.getElementById('classSelect').addEventListener('change', function() {
            let course = this.value;
            let sectionMenu = document.getElementById('sectionSelect');
            sectionMenu.innerHTML = '<option>Loading...</option>';
            fetch('sections.php?course=' + course)
            .then(r => r.json())
            .then(data => {
                sectionMenu.innerHTML = '<option value=\"\">-- Select Section --</option>';
                data.forEach(sec => {
                    sectionMenu.innerHTML += `<option value=\\\"\${sec}\\\">\${sec}</option>`;
                });
            });
        });
        </script>
    </div>
    </body>
    </html>";
    exit;
}

// -------------------------
// Students: get enrolled classes
// -------------------------
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

if ($role === 'student' && empty($enrolled)) {
    die("You are not enrolled in any classes.");
}

// -------------------------
// Determine current class & section
// -------------------------
if ($role === 'teacher') {
    $class = $_POST['class'];
    $section = $_POST['section'];
} elseif ($role === 'student') {
    if (isset($_GET['class_section'])) {
        list($class, $section) = explode('||', $_GET['class_section']);
        $_SESSION['class'] = $class;
        $_SESSION['section'] = $section;
    } elseif (isset($_SESSION['class'], $_SESSION['section'])) {
        $class = $_SESSION['class'];
        $section = $_SESSION['section'];
    } else {
        $class = $enrolled[0]['class'];
        $section = $enrolled[0]['section'];
        $_SESSION['class'] = $class;
        $_SESSION['section'] = $section;
    }

    // Verify student is enrolled in this class
    $classKey = $class . '_' . $section;
    $enrolledStudents = array_map('trim', $classStudents[$classKey] ?? []);
    if (!in_array($username, $enrolledStudents, true)) {
        die("Access denied. You are not enrolled in this class.");
    }
}

// -------------------------
// Set paths for assignments
// -------------------------
$classPath = $baseDir . "$class/$section/";
$assignDir = $classPath . "Assignments/";
$subDir = $assignDir . "Submissions/";

// Ensure folders exist
foreach ([$assignDir, $subDir] as $folder) {
    if (!is_dir($folder)) mkdir($folder, 0777, true);
}

// -------------------------
// HTML output
// -------------------------
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($type) ?> – <?= htmlspecialchars($class) ?> / <?= htmlspecialchars($section) ?></title>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>
<body>
<div class="centered-container">
<h1><?= $type ?> – <?= htmlspecialchars($class) ?> / <?= htmlspecialchars($section) ?></h1>

<?php if ($role === 'student'): ?>
<form method="GET">
    <label>Switch Class:</label>
    <select name="class_section" onchange="this.form.submit()">
        <?php foreach ($enrolled as $e):
            $selected = ($e['class'] === $class && $e['section'] === $section) ? 'selected' : '';
            $val = $e['class'] . '||' . $e['section'];
        ?>
            <option value="<?= htmlspecialchars($val) ?>" <?= $selected ?>>
                <?= htmlspecialchars($e['class']) ?> - <?= htmlspecialchars($e['section']) ?>
            </option>
        <?php endforeach; ?>
    </select>
</form>
<hr>
<?php endif; ?>

<h2>Available <?= $type ?></h2>
<?php
$files = array_diff(scandir($assignDir), ['.', '..', 'Submissions']);
if (empty($files)) {
    echo "No $type uploaded yet.<br>";
} else {
    foreach ($files as $f) {
        $safe = htmlspecialchars($f);
        $url  = "Classes/$class/$section/Assignments/" . rawurlencode($f);
        echo "<a href='$url' download='$safe'>$safe</a><br>";
    }
}
?>

<?php if ($role === 'student'): ?>
<h2>Submit Your Work</h2>
<form action='student_upload.php' method='POST' enctype='multipart/form-data'>
    <input type='hidden' name='class' value='<?= htmlspecialchars($class) ?>'>
    <input type='hidden' name='section' value='<?= htmlspecialchars($section) ?>'>
    <input type='hidden' name='type' value='Assignments'>
    <label>Select file to submit:</label><br>
    <input type='file' name='submission' required><br><br>
    <input type='submit' value='Upload Submission'>
</form>
<hr>
<?php endif; ?>

<?php if ($role === 'teacher'): ?>
<h2>Upload New <?= $type ?></h2>
<form action='upload.php' method='POST' enctype='multipart/form-data'>
    <input type='hidden' name='class' value='<?= htmlspecialchars($class) ?>'>
    <input type='hidden' name='section' value='<?= htmlspecialchars($section) ?>'>
    <input type='hidden' name='type' value='Assignments'>
    <label>Select file to upload:</label><br>
    <input type='file' name='file' required><br><br>
    <input type='submit' value='Upload <?= $type ?>'>
</form>
<hr>
<?php endif; ?>


<h2>Submissions</h2>
<?php
$submissions = array_diff(scandir($subDir), ['.', '..']);
if ($role === 'teacher') {
    if (empty($submissions)) echo "No submissions yet.";
    else foreach ($submissions as $f) {
        $safe = htmlspecialchars($f);
        $url = "Classes/$class/$section/Assignments/Submissions/" . rawurlencode($f);
        echo "<a href='$url' download='$safe'>$safe</a><br>";
    }
} elseif ($role === 'student') {
    $myFiles = array_filter($submissions, fn($f) => strpos($f, $username . "_") === 0);
    if (empty($myFiles)) echo "You have not submitted anything yet.";
    else foreach ($myFiles as $f) {
        $safe = htmlspecialchars($f);
        $url  = "Classes/$class/$section/Assignments/Submissions/" . rawurlencode($f);
        echo "<a href='$url' download='$safe'>$safe</a><br>";
    }
}
?>


<?php if ($role === 'teacher'): ?>
<form method='POST'><button type='submit'>Change Class/Section</button></form>
<?php endif; ?>

<button onclick="window.history.back()">Go Back</button>
<button onclick="logout.php">Logout</a></p>


</div>
</body>
</html>
