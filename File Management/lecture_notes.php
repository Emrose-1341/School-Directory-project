<?php
session_start();

//Login + Student :) for current user
if (!isset($_SESSION['username'], $_SESSION['role'])) {
    header("Location: login.php");
    exit();
}

//Username and role of person currently using
$un = $_SESSION['username'];
$role = $_SESSION['role'];

//If you arent a student you cant go
if ($role !== 'student') {
    die("Access denied. Only students can view lecture notes.");
}

//Same as always
$baseDir = "C:/Users/emros/Apache24/Apache24/htdocs/CyberSecurity_Final/Classes/";
$classStudentsFile = __DIR__ . '/class_students.json';
$classStudents = json_decode(file_get_contents($classStudentsFile), true) ?? [];


//Open array to append
$enrolled = [];

//For student file of classes, make sure to add all classes the student username has been given access to
foreach ($classStudents as $classKey => $students) {
    if (in_array($un, $students)) {
		//If they have the class and section. Give them a drop down for it. Append to enrolled
        if (strpos($classKey, '_') !== false) {
            list($cls, $sec) = explode('_', $classKey, 2);
			//If sec isnt there
        } else {
            $cls = $classKey;
            $sec = 'Default';
        }
		//add all the stuff from the class key for the student to enrolled
        $enrolled[] = ['class' => $cls, 'section' => $sec];
    }
}
//If nothing is appended to enrolled then they can access
if (empty($enrolled)) {
    die("You are not enrolled in any classes...yet");
}

//get single and break into seperate class and section
if (isset($_GET['class_section'])) {
	//list them all
    list($class, $section) = explode('||', $_GET['class_section']);
	//Break into class & session and stores in session
    $_SESSION['class'] = $class;
    $_SESSION['section'] = $section;
	//Sees if it's already been done in session before and makes sure to store again
} elseif (isset($_SESSION['class'], $_SESSION['section'])) {
    $class = $_SESSION['class'];
    $section = $_SESSION['section'];
//Default for if there isnt an added class or saved class before
} else {
    $class = $enrolled[0]['class'];
    $section = $enrolled[0]['section'];
    $_SESSION['class'] = $class;
    $_SESSION['section'] = $section;
}

//Directory for lecture. based on classes and sections we stored in session 
$lectureDir = $baseDir . "$class/$section/LectureNotes/";
if (!is_dir($lectureDir)) mkdir($lectureDir, 0777, true);

?>

<!--UI-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<!--Class name and section for te ones the student has access to-->
    <title>Lecture Notes – <?= htmlspecialchars($class) ?> / <?= htmlspecialchars($section) ?></title>
</head>
<body>
<div class="centered-container">
<!--Can see. Idk why i need two but i cant get it to work without doubling it-->
    <h1>Lecture Notes – <?= htmlspecialchars($class) ?> / <?= htmlspecialchars($section) ?></h1>

    <!-- Class switch dropdown -->
    <form method="GET">
        <label>Switch Class:</label>
        <select name="class_section" onchange="this.form.submit()">
		<!-- Go through the enrolled classes appended for the student. Allow them to choose any of them but not ones they dont have access too-->
            <?php foreach ($enrolled as $e):
                $selected = ($e['class'] === $class && $e['section'] === $section) ? 'selected' : '';
                $val = $e['class'] . '||' . $e['section'];
            ?>
				<!--Show them options from foreach of enrolled. Let them pick-->
                <option value="<?= htmlspecialchars($val) ?>" <?= $selected ?>>
                    <?= htmlspecialchars($e['class']) ?> - <?= htmlspecialchars($e['section']) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>
    <hr>

    <!-- Lecture notes list. -->
    <h2>Available Lecture Notes</h2>
    <?php
	//go through subfolder of section 'LecutureNotes. Look for anything in it (folder or file)
    $files = array_diff(scandir($lectureDir), ['.', '..']);
	//No files then tell
    if (empty($files)) {
        echo "No lectures uploaded..yet.";
	//if there is a tile go through them
    } else {
        foreach ($files as $f) {
			//All names of the files seen
            $safe = htmlspecialchars($f);
			//Find all of them and location
            $url  = "Classes/$class/$section/LectureNotes/" . rawurlencode($f);
			//Location and show and allow to download
            echo "<a href='$url' download='$safe'>$safe</a>";
        }
    }
    ?>
	<button onclick="window.history.back()">Go Back</button>
	<button onclick="logout.php">Logout</a></p>
</div>
</body>
</html>
