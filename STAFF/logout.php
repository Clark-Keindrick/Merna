<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['userID']);
unset($_SESSION['branch']);
session_destroy();

if(isset($_SESSION['username']))
    echo 'error in logout';
else
{
	echo '<script>setTimeout(function () { window.location.href = "../login.php";}, 10);</script>';
    exit();
}