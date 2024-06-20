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
    $pic = $row["USER_PIC"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Profile Pic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="general.css">
    <link rel="stylesheet" href="css/prof-pic.css">
    <link rel ="icon" href ="dashboard-icons/logo.png" type ="image/x-icon">
</head>
<body>
    <?php include("header_sidebar.php"); ?>
    <main>
        <div class="row page-path">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="dashboard.php"> <i class='bx bxs-home' style='color:#36a7ff'  ></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Change Profile</li>
                </ol>
              </nav>
        </div>

        <h1>Change Profile Picture</h1>

        <div class="changepic-container">
            <div class="pic-container">
                <img src="../ProfilePics/<?php echo $pic; ?>" alt="">
                <h4><?php echo $fname ." ". $lname; ?></h4>
            </div>
            <div class="upload-container">
                <div class="panel panel-info">
					<div class="panel-heading">
						Upload New Pic
					</div>
                    <form action="" method="POST" class="form-signin" enctype="multipart/form-data">
                        <div class="panel-body">
                                <label for="file">Select image</label><br>
                                <input type="file" name="profilePhoto"><br>
                        </div>

                        <div class="panel-footer">
                            <button class="btn btn-primary" name="update_pic" type="submit">Change</button>&nbsp;&nbsp;
                            <a type="button" class="btn btn-default" href="dashboard.php">Cancel</a>
                        </div>
                    </form>
			    </div>
                <?php update_photo($session_name) ?>
            </div>
        </div>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js"
    integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r"
    crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+"
        crossorigin="anonymous"></script>
</body>
</html>