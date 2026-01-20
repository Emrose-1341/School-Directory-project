<?php
session_start();
unset($_SESSION['class'], $_SESSION['section']);
header("Location: assignment.php");
exit();
