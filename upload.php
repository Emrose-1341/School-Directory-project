<?php
session_start();
$baseDir = 'C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/';
$message = "";

// Check if baseDir exists
if (!is_dir($baseDir)) {
    die("Classes folder not found at $baseDir");
}

// Step 1: Detect classes and sections dynamically
$classes = [];
foreach (scandir($baseDir) as $classDir) {
    if ($classDir === '.' || $classDir === '..') continue;
    $classPath = $baseDir . $classDir;
    if (is_dir($classPath)) {
        $sections = [];
        foreach (scandir($classPath) as $sectionDir) {
            if ($sectionDir === '.' || $sectionDir === '..') continue;
            $sectionPath = $classPath . '/' . $sectionDir;
            if (is_dir($sectionPath)) {
                $sections[] = $sectionDir;
            }
        }
        $classes[$classDir] = $sections ?: ['Default'];
    }
}

// Allowed upload folders
$allowedFolders = ['Assignments','FacultyNotes','LectureNotes'];

// Handle class/section selection
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['class_select'], $_POST['section_select'])) {
    $_SESSION['class'] = $_POST['class_select'];
    $_SESSION['section'] = $_POST['section_select'];
}

// Handle file upload
if (isset($_SESSION['class'], $_SESSION['section']) && isset($_FILES['material'])) {
    $folder = $_POST['folder'] ?? '';
    if (!in_array($folder, $allowedFolders)) {
        $message = "Invalid folder.";
    } else {
        $filename = basename($_FILES['material']['name']);
        $targetDir = $baseDir . $_SESSION['class'] . '/' . $_SESSION['section'] . '/' . $folder . '/';
        if (!is_dir($targetDir)) mkdir($targetDir, 0777, true);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['material']['tmp_name'], $targetFile)) {
            $message = "File uploaded to $folder: $filename";
        } else {
            $message = "Upload failed.";
        }
    }
}

// List uploaded files
$uploadedFiles = [];
if (isset($_SESSION['class'], $_SESSION['section'])) {
    foreach ($allowedFolders as $f) {
        $folderPath = $baseDir . $_SESSION['class'] . '/' . $_SESSION['section'] . '/' . $f . '/';
        if (is_dir($folderPath)) {
            $files = array_diff(scandir($folderPath), ['.', '..']);
            foreach ($files as $file) {
                $uploadedFiles[] = ['folder'=>$f,'name'=>$file,'path'=>$folderPath.$file];
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
<head><meta charset="UTF-8"><title>Upload Material</title></head>
<body>
<h1>Upload Your Material</h1>

<?php if (!isset($_SESSION['class'], $_SESSION['section'])): ?>
<form method="post">
<label>Select Class:</label><br>
<select name="class_select" required>
<option value="">--Select--</option>
<?php foreach ($classes as $cls => $sections): ?>
<option value="<?= htmlspecialchars($cls) ?>"><?= htmlspecialchars($cls) ?></option>
<?php endforeach; ?>
</select><br><br>

<label>Select Section:</label><br>
<select name="section_select" required>
<option value="">--Select--</option>
<?php if (!empty($classes)) foreach (current($classes) as $sec): ?>
<option value="<?= htmlspecialchars($sec) ?>"><?= htmlspecialchars($sec) ?></option>
<?php endforeach; ?>
</select><br><br>

<input type="submit" value="Select Class & Section">
</form>

<?php else: ?>
<p>Class: <b><?= htmlspecialchars($_SESSION['class']) ?></b>, Section: <b><?= htmlspecialchars($_SESSION['section']) ?></b></p>
<?php if($message) echo "<p>$message</p>"; ?>

<form method="post" enctype="multipart/form-data">
<label>Select folder:</label><br>
<select name="folder" required>
<option value="">--Select--</option>
<?php foreach ($allowedFolders as $f): ?>
<option value="<?= $f ?>"><?= $f ?></option>
<?php endforeach; ?>
</select><br><br>

<label>Select file:</label><br>
<input type="file" name="material" required><br><br>
<input type="submit" value="Upload">
</form>

<h3>Uploaded Files:</h3>
<ul>
<?php foreach ($uploadedFiles as $file): ?>
<li>[<?= htmlspecialchars($file['folder']) ?>] <?= htmlspecialchars($file['name']) ?></li>
<?php endforeach; ?>
</ul>

<p><a href="reset_class.php">Change Class/Section</a></p>
<?php endif; ?>
<button onclick="window

<button onclick="logout.php">Logout</button>
<button onclick="window.history.back()">Go Back</button>
</body>
</html>
