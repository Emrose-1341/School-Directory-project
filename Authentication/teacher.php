<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
	<title>Page Title</title>	
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="header">
		<h1>Teacher Portal</h1>
	</div>
	
	<!-- Clicker tabs from index example-->
	<div id="navigation">
		<ul>
			<!-- For teacher to add students to classes-->
			<li><a href="Classes.php">Classes</a></li>
			<!--Teacher can upload to all classes in Assigment, Lecture Notes and Faculty Notes-->
			<li><a href="upload.php">Uploads</a></li>
			<!-- Teacher can see assigments students submit and upload them-->
			<li><a href="assigment.php">Assignments</a></li>
			<!-- Like assigments but for facullty notes-->
			<li><a href="faculty_notes.php">Faculty Notes</a></li>
		</ul>
	</div>
	<!-- Just talks about what teacher can do with all the tabs-->
	<div id="content">
		<h2>What you can do</h2>
			
			<p>Classes:Add students to Classes </p>
			<p>Uploads: assigments, notes or lecture notes for each class + section</p>
			<p>Assignments: Upload and look at assigments for each class </p>
			 <p>Faculty notes: look at and make Faculty notes.</p>
	</div>
	<script>
// 5-minute countdown for inactivation [I just found this I did not come up with it on my own..im not that talented. It's pretty similar to something I did in prog one though]
let countdown = 5 * 60; // seconds
const timerEl = document.createElement('div');
timerEl.style.position = 'fixed';
timerEl.style.bottom = '10px';
timerEl.style.right = '10px';
timerEl.style.padding = '5px 10px';
timerEl.style.background = '#f0f0f0';
timerEl.style.border = '1px solid #ccc';
timerEl.style.borderRadius = '5px';
document.body.appendChild(timerEl);

function updateTimer() {
    let minutes = Math.floor(countdown / 60);
    let seconds = countdown % 60;
    timerEl.textContent = `Auto logout in: ${minutes}:${seconds.toString().padStart(2,'0')}`;
    countdown--;

    if (countdown < 0) {
        alert("Session expired. You will be logged out.");
        window.location.href = "logout.php";
    }
}

setInterval(updateTimer, 1000);

// Reset timer on any activity
document.addEventListener('mousemove', () => countdown = 5*60);
document.addEventListener('keypress', () => countdown = 5*60);
</script>

</body>
</html>
