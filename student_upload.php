<?php
session_start();

if (!isset($_SESSION['username'], $_POST['class'], $_POST['section'])) {
    die("Invalid submission.");
}

$username = $_SESSION['username'];
$class = $_POST['class'];
$section = $_POST['section'];

$baseDir = "C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/";
$subDir = $baseDir . "$class/$section/Assignments/Submissions/";

if (!is_dir($subDir)) mkdir($subDir, 0777, true);

$filename = $username . "_" . basename($_FILES['submission']['name']);
$target = $subDir . $filename;

if (move_uploaded_file($_FILES['submission']['tmp_name'], $target)) {
    echo "Submission uploaded successfully.<br>";
    echo "<a href=\"assignment.php\">Back to Class</a>";
} else {
    echo "Upload failed.";
}
?>
