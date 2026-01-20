<?php
session_start();

//Fully a copy of Lecutre notes

//Login
if (!isset($_SESSION['username'], $_SESSION['role'])) {
    die("Access denied. Not logged in.");
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

//teachers only
if ($role !== 'teacher') {
    die("Access denied. Only teachers can view Faculty Notes.");
}

//Location 
$baseDir = "C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/";
$classStudentsFile = __DIR__ . '/class_students.json';
$classStudents = json_decode(file_get_contents($classStudentsFile), true) ?? [];

//Name of Subfolder targeted
$type = 'FacultyNotes';
$classPath = '';
$subDir = '';

//Pick class + section to uplod to
if (!isset($_POST['open_class'])) {
    $courseFolders = array_filter(glob($baseDir . "*"), 'is_dir');

//HTML stuff but PHP version
    echo "<!DOCTYPE html>
    <html>
    <head>
        <link rel='stylesheet' href='style.css'>
    </head>
    <body>
    <div class='centered-container'>
        <h1>Select Class & Section – Faculty Notes</h1>
        <form method='POST'>
            <label>Class:</label><br>
            <select name='class' id='classSelect' required>
                <option value=''>-- Select Class --</option>";
//Let em see all class options
    foreach ($courseFolders as $folder) {
        $name = basename($folder);
        if ($name === "People") continue;
        echo "<option value='$name'>$name</option>";
    }
//Let em see the sections of the class they took. Go into subfolders of section
    echo "</select><br><br>
            <label>Section:</label><br>
            <select name='section' id='sectionSelect' required>
                <option value=''>-- Select Section --</option>
            </select><br><br>
            <button type='submit' name='open_class'>Open Class</button>
        </form>

        <script>
		//For stuff in class section 
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

//Class and selection part five thougsand woop de do
$class = $_POST['class'];
$section = $_POST['section'];


//Same as before
$classPath = $baseDir . "$class/$section/";
$assignDir = $classPath . "$type/";
$subDir = $assignDir . "Submissions/";

//  folders gotta exist
foreach (['FacultyNotes', $subDir] as $folder) {
    if (!is_dir($folder)) mkdir($folder, 0777, true);
}
// HTML + UI
echo "<!DOCTYPE html>
<html>
<head>
    <link rel='stylesheet' href='style.css'>
</head>
<body>
<div class='centered-container'>
<h1>$type – $class / $section</h1>";

//See files already uploaded
$files = array_diff(scandir($assignDir), ['.', '..', 'Submissions']);
echo "<h2>Available $type</h2>";
if (empty($files)) {
    echo "No $type uploaded yet.<br>";
} else {
    foreach ($files as $f) {
		//List the ones in the folder
        $safe = htmlspecialchars($f);
        $url  = "Classes/$class/$section/$type/" . rawurlencode($f);
		//Downloadable
        echo "<a href='$url' download='$safe'>$safe</a><br>";
    }
}

//Upload part. Like Upload file but chill
echo "
<h2>Upload New $type</h2>
<form action='upload.php' method='POST' enctype='multipart/form-data'>
    <input type='hidden' name='class' value='" . htmlspecialchars($class) . "'>
    <input type='hidden' name='section' value='" . htmlspecialchars($section) . "'>
    <input type='hidden' name='type' value='" . htmlspecialchars($type) . "'>
    <label>Select file to upload:</label><br>
    <input type='file' name='file' required><br><br>
    <input type='submit' value='Upload $type'>
</form>
<hr>";

//UI for sub. I think I doubled it. What's a girl to do.
echo "<h2>Submissions</h2>";
$submissions = array_diff(scandir($subDir), ['.', '..']);
if (empty($submissions)) {
    echo "No submissions yet.";
} else {
    foreach ($submissions as $f) {
        $safe = htmlspecialchars($f);
        $url = "Classes/$class/$section/$type/Submissions/" . rawurlencode($f);
        echo "<a href='$url' download='$safe'>$safe</a><br>";
    }
}


// -------------------------
// Change class/section
// -------------------------
echo "<form method='POST'><button type='submit'>Change Class/Section</button></form>";
echo "</div></body></html>";
?>
