<?php
    require_once('../functions.php');
    session_start();
    if(!isset($_SESSION["username"]) && !isset($_SESSION["branch"]) )
	{
    	header("location: ../login.php");
    	exit();
	}
    $session_name = $_SESSION["username"];
    $row = array();
	$row = user_data($session_name);
    $fname = $row["USER_FIRSTNAME"];
    $lname = $row["USER_LASTNAME"];
    $contact = $row["USER_PHONENUM"];
    $verifypass = $row["PASSWORD"];
    $userID = $_SESSION["userID"];
    $user = $row["USERNAME"];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>User Settings</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
        <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
        <link rel="stylesheet" href="general.css">
        <link rel="stylesheet" href="css/settings.css">
        <link rel ="icon" href ="dashboard-icons/logo.png" type ="image/x-icon">
    </head>
    <body>
        <?php include("header_sidebar.php"); ?>
        <main>
            <div class="row page-path">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"> <i class='bx bxs-home' style='color:#36a7ff'  ></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">User Settings</li>
                    </ol>
                </nav>
            </div>

            <h1>User Settings</h1>

            <div class="setting-container">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        Profile Details
                    </div>
                    <form class="form-signin" method="post" action="">
                        <div class="panel-body">
                            <label>Firstname</label>
                            <input type="text" placeholder="Firstname" name="newfname" required class="form-control"  value="<?php echo $fname; ?>">
                            <label class="lblbox">Lastname</label>
                            <input type="text" placeholder="Lastname"  name="newlname" required class="form-control"  value="<?php echo $lname; ?>">
                            <label class="lblbox">Phone number</label>
                            <input type="number" placeholder="+63" name="newphnum" required class="form-control"  value="<?php echo $contact; ?>">
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="submit" name="changeInfo" >Change</button>&nbsp;&nbsp;
                            <a href="dashboard.php" class="btn btn-default" id="login">Cancel</a>
                        </div>
                    </form>
                </div>
                <?php change_user_info($userID); ?>
                <div class="panel panel-danger">
                    <div class="panel-heading panel-heading-security">
                        Security
                    </div>
                    <form class="form-signin" method="post" action="">
                        <div class="panel-body">
                            <label>Username</label>
                            <input type="text" placeholder="Username" value="<?php echo $user; ?>" name="username" class="form-control" required>
                            <label  class="lblbox">old password</label>
                            <input type="password" placeholder="old password" class="form-control" name="oldpass">
                            <label class="lblbox">new password</label>
                            <input type="password" placeholder="new password" class="form-control" name="newpass"><br>
                        </div>
                        <div class="panel-footer">
                            <button class="btn btn-primary" type="submit" name="changeSecurity" id="changepass">Change</button>&nbsp;&nbsp;
                            <a href="dashboard.php" class="btn btn-default" id="login">Cancel</a>
                        </div>
                    </form>
                </div>
                <?php change_security($verifypass, $userID); ?>    
            </div>
        </main>

  <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
      <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
  </svg>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous">
    </script>

    <script>
        function saveScrollPosition() {
            localStorage.setItem('scrollPosition', window.scrollY);
        }

        // Function to scroll to the previously saved position
        function scrollToSavedPosition() {
            var savedPosition = localStorage.getItem('scrollPosition');
            if (savedPosition !== null) {
                window.scrollTo(0, savedPosition);
                localStorage.removeItem('scrollPosition'); // Clear saved position after scrolling
            }
        }

        // Add event listener to the button to save scroll position
        document.getElementById('changepass').addEventListener('click', saveScrollPosition);

        window.onload = scrollToSavedPosition;
    </script>
    </body>
</html>