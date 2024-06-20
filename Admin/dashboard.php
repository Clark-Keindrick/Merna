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
    $last_login = $row["LAST_LOGIN"];
    $last_login = date('jS M Y H:i', strtotime($last_login));
    $total_user = user_count();
    $total_branch = branch_count();
    $totalsales = overall_sales();
    $totalsold = overall_sold_items();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <title>Admin Dashboard</title>
  <!-- Link Styles -->
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/index.css">
  <link rel="stylesheet" href="css/header.css">
  <link rel="stylesheet" href="ribbon.css">
  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel ="icon" href ="image/logo.png" type ="image/x-icon">
</head>
<body>
    <div class="header">
        <div class="text">Admin Dashboard</div>
        <div class="btn-group drpdwn">
            <button class="btn btn-info btn-md dropdown-toggle badge" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                BikePro Admin
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="prof-pic.php"><img src="image/user.png" alt="image" >Change Profile Pic</a></li>
                <li><a class="dropdown-item" href="admin-settings.php"><img src="image/settings.png" alt="image">Settings</a></li>
                <li><a class="dropdown-item" href="logout.php"><img src="image/sign-out.png" alt="image" width="20px" style="width:20px; margin-left: 4px;">Logout</a></li>
            </ul>
        </div>
    </div>

  <?php include("sidebar.php");?>

   <div class="row page-path">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="dashboard.php"> <i class='bx bxs-home' style='color:#36a7ff'></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">Dashboard</li>
            </ol>
        </nav>
    </div>
    <main class="home-section">
        <div class="panels">
            <a href="#" data-bs-toggle="modal" data-bs-target="#dailysales">
                <div class="panels-container">
                    <div class="panels-icon2">
                        <i class='bx bx-line-chart' style='color:#ffffff'></i>
                    </div>
                    <div class="panels-stats">
                        <div class="panels-stats-box">
                            <p class="stats-num">&#8369;<?php echo number_format($totalsales, 2, '.'); ?></p>
                            <p class="stats-label">Daily Sales</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="#" data-bs-toggle="modal" data-bs-target="#itemsold">
                <div class="panels-container">
                    <div class="panels-icon3">
                        <i class='bx bx-money-withdraw' style='color:#ffffff' ></i>
                    </div>
                    <div class="panels-stats">
                        <div class="panels-stats-box">
                            <p class="stats-num"><?php echo number_format($totalsold);?></p>
                            <p class="stats-label">Total Items Sold</p>
                        </div>
                    </div>
                </div>
            </a>

            <a href="users.php">
                <div class="panels-container" type="button" id="liveToastBtn">
                    <div class="panels-icon5">
                    <i class='bx bxs-user' style='color:#ffffff'  ></i>
                    </div>
                    <div class="panels-stats">
                        <div class="panels-stats-box">
                            <p class="stats-num"><?php echo $total_user; ?></p>
                            <p class="stats-label">Number of Users</p>
                        </div>
                    </div>
                </div>
            </a>

            <div class="panels-container" type="button" id="liveToastBtn">
                <div class="panels-icon6">
                <i class='bx bxs-store' style='color:#ffffff' ></i>
                </div>
                <div class="panels-stats">
                    <div class="panels-stats-box">
                        <p class="stats-num"><?php echo $total_branch; ?></p>
                        <p class="stats-label">Total Branches</p>
                    </div>
                </div>
            </div>

            <div class="panels-container" type="button" id="liveToastBtn">
                <div class="panels-icon7">
                    <i class='bx bxs-time-five' style='color:#ffffff'></i>
                </div>
                <div class="panels-stats">
                    <div class="panels-stats-box">
                        <p class="last-login"><?php echo $last_login; ?></p>
                        <p class="last-login2">Last Log-in</p>
                    </div>
                </div>
            </div>
        </div>
        
        <h1 class="ribbon">
            <strong class="ribbon-content">Pending for Approval</strong>
        </h1>
        <div class="inv_table table-responsive-md">
            <?php user_table(); ?>
        </div>
    </main>

    <div class="modal fade" id="dailysales" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="dailysalesLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning">
                    <h5 class="modal-title" id="dailysalesLabel">Choose Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="d-grid gap-4 col-8 mx-auto">
                    <button class="btn btn-outline-success" type="button" onclick="location.href='LLCsales.php'">LAPU-LAPU</button>
                    <button class="btn btn-outline-info" type="button" onclick="location.href='cordovaSales.php'">CORDOVA</button>
                </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="itemsold" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="itemsoldLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h5 class="modal-title" id="itemsoldLabel">Choose Branch</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="d-grid gap-4 col-8 mx-auto">
                    <button class="btn btn-outline-primary" type="button" onclick="location.href='LLCitemsold.php'">LAPU-LAPU</button>
                    <button class="btn btn-outline-danger" type="button" onclick="location.href='CordovaItemSold.php'">CORDOVA</button>
                </div>
                </div>
            </div>
        </div>
    </div>

  
  <!-- Scripts -->
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
    crossorigin="anonymous"></script>

    <script>
        const toastTrigger = document.getElementById('liveToastBtn')
        const toasts = document.querySelectorAll(".nice-toasts .toast");
        const basicToasts = document.querySelectorAll(".toast");
        const animateFrom = "top";
        const animateOffset = "-48px";

        if(toastTrigger){
            toastTrigger.addEventListener('click', () => {
                function getAbsoluteHeight(el) {
                const styles = window.getComputedStyle(el);
                const margin =
                    parseFloat(styles["marginTop"]) + parseFloat(styles["marginBottom"]);
                return Math.ceil(el.offsetHeight + margin);
                }
                
                basicToasts.forEach((toast) => {
                const inst = bootstrap.Toast.getOrCreateInstance(toast, {
                    autohide: false
                });
                inst.show();
                });
                toasts.forEach((toast) => {
                toast.style.position = "relative";
                toast.style[animateFrom] = animateOffset;
                
                toast.addEventListener(
                    "show.bs.toast",
                    () => {
                    console.log("showing");
                    // Additional slide animation
                    setTimeout(() => {
                        // Transition is not available until actual animation
                        const transition =
                        parseFloat(getComputedStyle(toast).transitionDuration) * 1e3;
                        toast.style.transition = `all ${
                        transition * 4
                        }ms cubic-bezier(0.165, 0.840, 0.440, 1.000), opacity ${transition}ms linear`;
                        toast.style[animateFrom] = 0;
                    }, 0);
                    },
                    {
                    once: true
                    }
                );
                
                toast.addEventListener(
                    "hide.bs.toast",
                    () => {
                    console.log("hiding");
                    toast.style.transform = `scale(0)`;
                    toast.style.marginTop = `-${getAbsoluteHeight(toast)}px`;
                    },
                    {
                    once: true
                    }
                );
                toast.addEventListener(
                    "hidden.bs.toast",
                    () => {
                    console.log("hidden");
                    },
                    {
                    once: true
                    }
                );
                
                toast.classList.add("toaster");
                const inst = bootstrap.Toast.getOrCreateInstance(toast, {
                    autohide: false
                });
                inst.show();
                });
            })
        }
        
    </script>
    <script src="script.js"></script>
</body>
</html>