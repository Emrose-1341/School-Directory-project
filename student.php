<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>Page Title</title>	
	<link rel="stylesheet" href="style.css" type="text/css" media="screen" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="header">
		<h1>Student Portal</h1>
	</div>
	<div id="navigation">
		<ul>
			<li><a href="see_classes.php">Classes</a></li>
			<li><a href="assigment.php">Assignments</a></li>
			<li><a href="lecture_notes.php">Lecture Notes</a></li>
		</ul>
	</div>
	<div id="content"><!-- Start of the page-specific content. -->
		<h2> What you can do</h2>
			
			<p>Classes: Student can look at the classes they've been added to by the teacher </p>

			<p>Assignments: Students can look at assigments and upload assigments for classes they've been added to</p>
			<p> Lecture Notes: Students can see the lecture notes for their classes</p>
				
	<!-- End of the page-specific content. --></div>
		<script>
// 5-minute countdown
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
