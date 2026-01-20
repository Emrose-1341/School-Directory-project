<?php
session_start();

// Set timeout duration (5 minutes)
$timeout_duration = 300; // 5 minutes in seconds

// Check if last activity timestamp exists
if (isset($_SESSION['LAST_ACTIVITY'])) {
    if (time() - $_SESSION['LAST_ACTIVITY'] > $timeout_duration) {
        // Session expired
        session_unset();
        session_destroy();
        header("Location: login.php?timeout=1");
        exit();
    }
}

// Update last activity timestamp
$_SESSION['LAST_ACTIVITY'] = time();

// read 'database' of usernames + passcodes. Make sure it's there
$Username_pass = json_decode(file_get_contents('users_hashed.json'), true);

//Make sure we have errors thrown when needed
$error = "";

// UN and password area
if ($_SERVER["REQUEST_METHOD"] === "POST") {
	//boxes telling them what to put
    $un = trim($_POST["username"]);
    $pass = $_POST["password"];

//Make sure they have a user name from the db
    $user = null;
    foreach ($Username_pass as $u) {
        if ($u['username'] === $un) {
			//for specific person
            $user = $u;
            break;
        }
    }
//Make sure user is in DB and if they are with no error move to passcode
    if ($user) {
        // check password hash for username
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
			
			//See if they are a teacher or student
            if ($user['role'] === 'teacher') {
				//Go to teacher portal
                header("Location: teacher.php");
            } else {
				//Go to student portal
                header("Location: student.php");
            }
            exit();
			//For wrong pass
        } else {
            $error = "Incorrect password.";
        }
		//For wrong UN
    } else {
        $error = "User not found.";
    }
}
?>

<!--simple UI from book project in database system-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Page</title>
    <link rel="stylesheet" href="style.css" type="text/css" media="screen" />
</head>
<body>
    <div class="centered-container">
        <h1>Login</h1>
		<!--Calls error message based on passcode vs. Username like belmont web does-->
        <?php if ($error) echo "<p style='color:red;'>$error</p>"; ?>
		<!--Make boxes for UN and pass-->
        <form method="post" action="">
            <label>Username:</label><br>
            <input type="text" name="username" required><br><br>

            <label>Password:</label><br>
            <input type="password" name="password" required><br><br>

            <input type="submit" value="Login">
        </form>
		<!--Created a public (no passcode or username version-->
        <p><a href="public.php">No login? Click here to see public resources</a></p>
    </div>
</body>
</html>
