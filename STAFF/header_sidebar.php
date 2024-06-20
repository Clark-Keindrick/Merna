<?php
    require_once('../functions.php');
    if(!isset($_SESSION["username"]) && !isset($_SESSION["branch"]) )
	{
    	header("location: ../login.php");
    	exit();
	}
    $session_name = $_SESSION["username"];
    $row = array();
	$row = user_data($session_name);
    $last_login = $row["LAST_LOGIN"];
    $last_login = date('jS M Y H:i', strtotime($last_login));
    $pic = $row["USER_PIC"];
    $fname = $row["USER_FIRSTNAME"];
    $lname = $row["USER_LASTNAME"];
    $total_user = user_count();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
<div class="navbar-header bg-dark">
        <a class="navbar-brand" href="dashboard.php">
            <img src="dashboard-icons/logo.png" alt="image">
        </a>
      
        <div class="user-menu">
            <img src="../ProfilePics/<?php echo $pic; ?>" alt="image" class="profilepic">
            <a href="#" class="prof-name"><?php echo $fname ." ". $lname; ?></a>
            <div class="btn-group drpdwn">
                <button class="btn btn-info btn-sm dropdown-toggle badge btn-prof" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                  BikePro Staff
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="prof-pic.php"><img src="dashboard-icons/user.png" alt="image" >Change Profile Pic</a></li>
                    <li><a class="dropdown-item" href="user-settings.php"><img src="dashboard-icons/settings.png" alt="image">Settings</a></li>
                    <li><a class="dropdown-item" href="logout.php"><img src="dashboard-icons/sign-out.png" alt="image" width="20px" style="width:20px; margin-left: 4px;">Logout</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="sidebar">
        <div class="form-group nav-search-container">
            <input type="text" class="form-control nav-search" placeholder="Search" required="">
        </div>

        <hr>

        <div class="page-links">
            <a href="dashboard.php">
                <div class="links-container">
                    <i class='bx bxs-dashboard' style='color:#36a7ff'></i>
                    <p>Dashboard</p>
                </div>
             </a>

             <a href="inventory.php">
                <div class="links-container">
                    <i class='bx bxs-coin-stack' style='color:#36a7ff'></i>
                    <p>Inventory</p>
                </div>
             </a>

             <a href="invoice.php">
                <div class="links-container">
                    <i class='bx bx-receipt' style='color:#36a7ff'></i>
                    <p>Invoice</p>
                </div>
             </a>

             <a href="trashbin.php">
                <div class="links-container">
                    <i class='bx bxs-trash' style='color:#36a7ff'></i>
                    <p>Trash</p>
                </div>
             </a>
        </div>

        <hr>

        <div class="log-status">
            <p class="last-log"><i class='bx bx-time-five'></i>last login</p>
            <p class="log-date"><?php echo $last_login; ?></p>
        </div>

        <hr>
    </div>
</body>
</html>