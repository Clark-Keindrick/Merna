<?php
    require_once('../functions.php');
    $session_name = $_SESSION["username"];
    $row = array();
	  $row = user_data($session_name);
    $pic = $row["USER_PIC"];
    $fname = $row["USER_FIRSTNAME"];
    $lname = $row["USER_LASTNAME"];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/sidebar.css">
</head>
<body>
  <!-- Sidebar -->
  <div class="sidebar">
    <ul class="nav-list">
      <li class="logo">
        <div class="logo_details">
          <img src="image/BikePro Pic.png" alt="profile image">
          <div class="logo_name">BikePro</div>
          <i class="bx bx-menu" id="btn"></i>
        </div>
      </li>
      <li>
        <i class="bx bx-search"></i>
        <input type="text" placeholder="Search">  
      </li> 
      <li>
        <a href="dashboard.php">
          <i class="bx bx-grid-alt"></i>
          <span class="link_name">Dashboard</span>
        </a>
        <span class="tooltip">Dashboard</span>
      </li>
      <li>
        <a href="LLCInventory.php">
          <i class="bx bxs-coin-stack"></i>
          <span class="link_name">Inventory</span>
        </a>
        <span class="tooltip">Inventory</span>
      </li>
      <li>
        <a href="trash.php">
          <i class="bx bxs-trash"></i>
          <span class="link_name">Trash</span>
        </a>
        <span class="tooltip">Trash</span>
      </li>
      <li class="profile">
          <div class="profile_content">
             <img src="../ProfilePics/<?php echo $pic; ?>" alt="profile image">
             <div class="profile-details">
                <div class="name"><?php echo $fname ." ". $lname?></div>
                <div class="designation">Admin</div>
             </div>
          </div>
      </li>     
    </ul>
  </div>
</body>
</html>