<?php
function register_user(){
    if(isset($_POST["register"])) {
        $firstname = filter_input(INPUT_POST,"fname", FILTER_SANITIZE_SPECIAL_CHARS);
        $firstname = trim($firstname);
        $firstname = ucwords($firstname);
        $lastname = filter_input(INPUT_POST,"lname", FILTER_SANITIZE_SPECIAL_CHARS);
        $lastname = trim($lastname);
        $lastname = ucwords($lastname);
        $contact = filter_input(INPUT_POST,"phnum", FILTER_SANITIZE_SPECIAL_CHARS);
        $contact = trim($contact);
        $dob = $_POST['bdate'];
        $bdate = date('Y-m-d', strtotime($dob));
        $gender = filter_input(INPUT_POST, "gender", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = trim($username);
        $branch = filter_input(INPUT_POST,"branch", FILTER_SANITIZE_SPECIAL_CHARS);
        $pwd = filter_input(INPUT_POST,"pword", FILTER_SANITIZE_SPECIAL_CHARS);
        $confirmpwd = filter_input(INPUT_POST,"confirmPword", FILTER_SANITIZE_SPECIAL_CHARS);
        $pattern = '/^\d{11}$/';
        $pattern2 = '/^[A-Za-z\s]+$/';

        if(empty($firstname) || empty($lastname) || empty($contact) || empty($bdate) || empty($gender) || empty($username) || empty($pwd) || empty($confirmpwd) || empty($branch) || empty($_FILES['profpic'])){
            echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    Something is Missing!
                 </div>';
        }
        else if(!preg_match($pattern, $contact)){
            echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    Invalid phone number !
                 </div>';
        }
        else if(!preg_match($pattern2, $firstname) || !preg_match($pattern2, $lastname)){
            echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    Invalid Firstname or Lastname !
                 </div>';
        }
        else{
            $img_name = $_FILES['profpic']['name'];
            $img_size = $_FILES['profpic']['size'];
            $tmp_name = $_FILES['profpic']['tmp_name'];
            $error = $_FILES['profpic']['error'];
            
                if ($error === 0) {
                    if ($img_size > 1525000) {
                        echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                Sorry, your file is too large.
                             </div>';
                    }else {
                        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                        $img_ex_lc = strtolower($img_ex);
                        $filename = pathinfo($img_name, PATHINFO_FILENAME);
            
                        $allowed_exs = array("jpg", "jpeg", "png"); 
            
                        if (in_array($img_ex_lc, $allowed_exs)) {
                            if($pwd == $confirmpwd){
                                $new_img_name = uniqid($filename." ", true).'.'.$img_ex_lc;
                                $img_upload_path = 'ProfilePics/'.$new_img_name;
                                move_uploaded_file($tmp_name, $img_upload_path);
                                
                                $confirmpwd = password_hash($confirmpwd, PASSWORD_DEFAULT);
                                date_default_timezone_set('Asia/Manila');
                                $currentDate = new DateTime();
                                $date = $currentDate -> format("Y-m-d g:i:s");

                                try{
                                    include "database.php";
                                    
                                    $query = "INSERT INTO user_accounts (USER_FIRSTNAME, USER_LASTNAME, USER_GENDER, USERNAME, PASSWORD, USER_PIC, 
                                              USER_PHONENUM, USER_DOB, USER_BRANCH, CURRENT_LOGIN) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);";

                                    $stmt = $pdo->prepare($query);

                                    $stmt->execute([$firstname, $lastname, $gender, $username, $confirmpwd, $new_img_name, $contact, $bdate, $branch, $date]);

                                    $pdo = null;
                                    $stmt = null;

                                    echo '<div class="alert alert-success d-flex align-items-center reg-status" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                            Registered Successfully!
                                        </div>';

                                    echo '<script>setTimeout(function () { window.location.href = "login.php";}, 2000);</script>';

                                    die();

                                }
                                catch(PDOException $e){
                                    echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                            Query failed: '. $e->getMessage(). '
                                        </div>';
                                    die();
                                }
                            }
                            else {
                                echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                          Sorry, Password did not match.
                                      </div>';
                            }

                        }else {
                            echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                      Sorry, You cant upload files of this type.
                                  </div>';
                        }
                    }
                }else {
                    echo '<div class="alert alert-danger d-flex align-items-center reg-status" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            unknown error occurred!
                         </div>';
                }
            }
        }
    else{
        echo "";
    }
}

function user_table(){
    try{
        include "database.php";
        
        $query = "SELECT * FROM user_accounts WHERE USER_ACTIVE = 1 AND USER_STATUS = 'PENDING'";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            ?>
             <table class="table table-hover table-striped text-center">
                <thead class="table-dark">
                <tr>
                    <th scope="col">FIRSTNAME</th>
                    <th scope="col">LASTNAME</th>
                    <th scope="col">DOB</th>
                    <th scope="col">USERNAME</th>
                    <th scope="col">GENDER</th>
                    <th scope="col">CONTACT</th>
                    <th scope="col">BRANCH</th>
                    <th scope="col">ACTION</th>
                </tr>
                </thead>
                <tbody>
            <?php

            foreach($result as $row){
                echo '<tr >
                        <td>'.$row["USER_FIRSTNAME"].'</td>
                        <td>'.$row["USER_LASTNAME"].'</td>
                        <td>'.$row["USER_DOB"].'</td>
                        <td>'.$row["USERNAME"].'</td>
                        <td>'.$row["USER_GENDER"].'</td>
                        <td>'.$row["USER_PHONENUM"].'</td>
                        <td>'.$row["USER_BRANCH"].'</td>
                        <td>
                            <a href="actions/approve_user.php?user_id='.$row['USER_ID'].'"><i class="bx bx-check"></i></a> 
                            <a href="actions/denied_user.php?user_id='.$row['USER_ID'].'"><i class="bx bx-x"></i></a>
                        </td>
                     </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else{
            echo '<div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        No pending accounts!
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                         Query failed: '.$e->getMessage().'
                    </div>
                </div>';
    }
}

function user_count(){
    include "database.php";

    $query = "SELECT COUNT(*) AS total_count FROM user_accounts WHERE user_active = 1 AND user_status = 'APPROVED'";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $total = $result['total_count'];

    return $total;
}


function total_payment($branch){
    include "database.php";

    $query = "SELECT SUM((SALES_PRICE - (SALES_PRICE * SALES_DISCOUNT / 100)) * SALES_QTY) AS Total_Pay FROM INVOICE, SALES WHERE INVC_STATUS = 'UNUSED' AND 
              INVC_BRANCH = :branch AND INVOICE.INVC_NUMBER = SALES.INVC_NUMBER";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":branch", $branch);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $total = $result['Total_Pay'];

    return $total;
}

function total_sales($branch){
    include "database.php";

    $query = "SELECT SUM((SALES_PRICE - (SALES_PRICE * SALES_DISCOUNT / 100)) * SALES_QTY) AS Total_Sale FROM INVOICE, SALES WHERE INVC_STATUS <> 'UNUSED' AND 
              INVC_BRANCH = :branch AND SALES_DATE = CURDATE() AND INVOICE.INVC_NUMBER = SALES.INVC_NUMBER";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":branch", $branch);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $totalsales = $result['Total_Sale'];

    return $totalsales;
}

function total_sold_items($branch){
    include "database.php";

    $query = "SELECT SUM(SALES_QTY) AS Total_Sold FROM INVOICE, SALES WHERE INVC_STATUS <> 'UNUSED' AND 
              INVC_BRANCH = :branch AND SALES_DATE = CURDATE() AND INVOICE.INVC_NUMBER = SALES.INVC_NUMBER";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":branch", $branch);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $totalsold= $result['Total_Sold'];

    return $totalsold;
}

function overall_sales(){
    include "database.php";

    $query = "SELECT SUM((SALES_PRICE - (SALES_PRICE * SALES_DISCOUNT / 100)) * SALES_QTY) AS Total_Sale FROM INVOICE, SALES WHERE INVC_STATUS <> 'UNUSED'
              AND SALES_DATE = CURDATE() AND INVOICE.INVC_NUMBER = SALES.INVC_NUMBER";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $totalsales = $result['Total_Sale'];

    return $totalsales;
}

function overall_sold_items(){
    include "database.php";

    $query = "SELECT SUM(SALES_QTY) AS Total_Sold FROM INVOICE, SALES WHERE INVC_STATUS <> 'UNUSED'
               AND SALES_DATE = CURDATE() AND INVOICE.INVC_NUMBER = SALES.INVC_NUMBER";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $totalsold= $result['Total_Sold'];

    return $totalsold;
}

function branch_count(){
    include "database.php";

    $query = "SELECT DISTINCT COUNT(DISTINCT user_branch) AS total_count FROM user_accounts WHERE user_branch <> 'ADMIN'";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $total = $result['total_count'];

    return $total;
}

function items_data($itemID){
    include "../database.php";
    
    $query = "SELECT * FROM inventory WHERE INV_ID = :invid";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":invid", $itemID);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    return $result;
}

function user_data($session_name){
    include "database.php";

    $query = "SELECT * FROM user_accounts WHERE USERNAME = :uname";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":uname", $session_name);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    return $result;
}


function business_data(){
    include "database.php";

    $query = "SELECT * FROM BUSINESS_INFO";

    $stmt = $pdo->prepare($query);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    return $result;
}


function view_users(){
    try{
        include "database.php";
        
        $query = "SELECT * FROM user_accounts WHERE USER_ACTIVE = 1 AND USER_STATUS = 'APPROVED'";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            foreach($result as $row){ 
                if($row["USER_BRANCH"] != 'ADMIN'){
                    $delete = '<div><a href="actions/delete_user.php?user_id='.$row['USER_ID'].'"><i class="bx bxs-trash" style="color:#ff0003"></i></a></div>';
                }
                else{
                    $delete = '';
                }
                echo '<div class="col img-container">
                        <div class="card h-100">
                            <img src="../ProfilePics/'.$row["USER_PIC"].'" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title mb-3">'.$row["USER_FIRSTNAME"] ." ". $row["USER_LASTNAME"].'</h5>
                                <div class="description mb-2">
                                    <div class="card-container1"><p class="card-text"><b>User: </b>'.$row["USERNAME"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Pass: </b>!^%*&=-)$#(@</p></div>
                                </div>
                                <div class="description mb-2">
                                    <div class="card-container1"><p class="card-text"><b>Contact:</b>'.$row["USER_PHONENUM"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Birthdate:</b>'.$row["USER_DOB"].'</p></div>
                                </div>
                                <div class="description">
                                    <div class="card-container1"><p class="card-text"><b>Branch:</b>'.$row["USER_BRANCH"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Status:</b>'.$row["USER_STATUS"].'</p></div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">USER ID: '.$row["USER_ID"].'</small>
                                '.$delete.'
                            </div>
                        </div>
                    </div>';
            }

        }
        else{
            echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        No users available!
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        Query failed: '.$e->getMessage().' 
                    </div>
                </div>';
    }
}


function view_reg_status(){
    try{
        include "database.php";
        
        $query = "SELECT * FROM user_accounts WHERE USER_ACTIVE = 1 AND (USER_STATUS = 'DENIED' OR USER_STATUS = 'PENDING')";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            foreach($result as $row){ 
                if($row["USER_STATUS"] == "DENIED"){
                    $delBTN = '<div><a href="delete_request.php?userid='.$row['USER_ID'].'">DELETE</a></div>';
                }
                else{
                    $delBTN = '';
                }
                echo '<div class="col img-container">
                        <div class="card h-100" style="width: 24rem;">
                            <img src="/ProfilePics/'.$row["USER_PIC"].'" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title mb-3">'.$row["USER_FIRSTNAME"]. " ". $row["USER_LASTNAME"].'</h5>
                                <div class="description mb-2">
                                    <div class="card-container1"><p class="card-text"><b>User: </b>'.$row["USERNAME"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Pass: </b>!^%*&=-$#@</p></div>
                                </div>
                                <div class="description mb-2">
                                    <div class="card-container1"><p class="card-text"><b>Contact: </b>'.$row["USER_PHONENUM"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Birthdate: </b>'.$row["USER_DOB"].'</p></div>
                                </div>
                                <div class="description">
                                    <div class="card-container1"><p class="card-text"><b>Branch: </b>'.$row["USER_BRANCH"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Status: </b>'.$row["USER_STATUS"].'</p></div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">'.$row['USER_ID'].'</small>
                                '.$delBTN.'
                            </div>
                        </div>
                    </div>';
            }

        }
        else{
            echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        No users available!
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        Query failed: '.$e->getMessage().' 
                    </div>
                </div>';
    }
}

function view_deleted_users(){
    try{
        include "database.php";
        
        $query = "SELECT * FROM user_accounts WHERE USER_ACTIVE = 0 AND USER_STATUS = 'APPROVED'";

        $stmt = $pdo->prepare($query);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            foreach($result as $row){ 
                echo '<div class="col img-container">
                        <div class="card h-100" style="width: 22rem;">
                            <img src="/ProfilePics/'.$row["USER_PIC"].'" class="card-img-top" alt="...">
                            <div class="card-body">
                                <h5 class="card-title mb-3">'.$row["USER_FIRSTNAME"]. " ". $row["USER_LASTNAME"].'</h5>
                                <div class="description mb-2">
                                    <div class="card-container1"><p class="card-text"><b>User: </b>'.$row["USERNAME"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Pass: </b>!^%*&=-$#@</p></div>
                                </div>
                                <div class="description mb-2">
                                    <div class="card-container1"><p class="card-text"><b>Contact: </b>'.$row["USER_PHONENUM"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Birthdate: </b>'.$row["USER_DOB"].'</p></div>
                                </div>
                                <div class="description">
                                    <div class="card-container1"><p class="card-text"><b>Branch: </b>'.$row["USER_BRANCH"].'</p></div>
                                    <div class="card-container"><p class="card-text"><b>Status: </b>'.$row["USER_STATUS"].'</p></div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <small class="text-muted">'.$row['USER_ID'].'</small>
                                <div><a href="actions/retrieve_user.php?userid='.$row['USER_ID'].'">RETRIEVE</a></div>
                            </div>
                        </div>
                    </div>';
            }

        }
        else{
            echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        No Deleted Users !
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        Query failed: '.$e->getMessage().' 
                    </div>
                </div>';
    }
}

function update_photo($session_name){
    if (isset($_POST['update_pic']) && isset($_FILES['profilePhoto'])) {
        $img_name = $_FILES['profilePhoto']['name'];
        $img_size = $_FILES['profilePhoto']['size'];
        $tmp_name = $_FILES['profilePhoto']['tmp_name'];
        $error = $_FILES['profilePhoto']['error'];
    
        if ($error === 0) {
            if ($img_size > 1525000) {
                echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div class="alert_label">
                                Sorry, your file is too large.
                        </div>
                    </div>';
            }else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $filename = pathinfo($img_name, PATHINFO_FILENAME);
    
                $allowed_exs = array("jpg", "jpeg", "png"); 
    
                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid($filename." ", true).'.'.$img_ex_lc;
                    $img_upload_path = '../ProfilePics/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    try{
                        include "database.php";

                        $query = "UPDATE user_accounts SET USER_PIC = :newpic WHERE USER_ACTIVE = 1 AND USERNAME = :username;";
    
                        $stmt = $pdo->prepare($query);

                        $stmt->bindParam(":newpic", $new_img_name);
                        $stmt->bindParam(":username", $session_name);
                
                        $stmt->execute();

                        echo '<script>setTimeout(function () { window.location.href = "prof-pic.php";}, 500);</script>';


                    }
                    catch(PDOException $e){
                        echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div class="alert_label">
                                    Query failed: '.$e->getMessage().'
                                </div>
                            </div>';
                    }

                }else {
                    echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            <div class="alert_label">
                                You cant upload files of this type
                            </div>
                        </div>';
                }
            }
        }else {
            echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div class="alert_label">
                        Unknown error occurred!
                    </div>
                </div>';
        }
            
    }
}


function update_item_photo($itemsID){
    if (isset($_POST['update_item_pic']) && isset($_FILES['itemPhoto'])) {
        $img_name = $_FILES['itemPhoto']['name'];
        $img_size = $_FILES['itemPhoto']['size'];
        $tmp_name = $_FILES['itemPhoto']['tmp_name'];
        $error = $_FILES['itemPhoto']['error'];
    
        if ($error === 0) {
            if ($img_size > 1525000) {
                echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div class="alert_label">
                                Sorry, your file is too large.
                        </div>
                    </div>';
            }else {
                $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                $img_ex_lc = strtolower($img_ex);
                $filename = pathinfo($img_name, PATHINFO_FILENAME);
    
                $allowed_exs = array("jpg", "jpeg", "png"); 
    
                if (in_array($img_ex_lc, $allowed_exs)) {
                    $new_img_name = uniqid($filename." ", true).'.'.$img_ex_lc;
                    $img_upload_path = '../Inventory_Pic/'.$new_img_name;
                    move_uploaded_file($tmp_name, $img_upload_path);

                    try{
                        include "database.php";

                        $query = "UPDATE inventory SET INV_PIC = :newpic WHERE INV_ACTIVE = 1 AND INV_ID = :invid;";
    
                        $stmt = $pdo->prepare($query);

                        $stmt->bindParam(":newpic", $new_img_name);
                        $stmt->bindParam(":invid", $itemsID);
                
                        $stmt->execute();

                        echo '<script>setTimeout(function () { window.location.href = "LLCInventory.php";}, 500);</script>';


                    }
                    catch(PDOException $e){
                        echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div class="alert_label">
                                    Query failed: '.$e->getMessage().'
                                </div>
                            </div>';
                    }

                }else {
                    echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            <div class="alert_label">
                                You cant upload files of this type
                            </div>
                        </div>';
                }
            }
        }else {
            echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div class="alert_label">
                        Unknown error occurred!
                    </div>
                </div>';
        }
            
    }
}

function change_security($verifypass, $userID){
    if(isset($_POST["changeSecurity"])) {
        $profUname = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $profUname = trim($profUname);
        $oldpass = filter_input(INPUT_POST,"oldpass", FILTER_SANITIZE_SPECIAL_CHARS);
        $newpass = filter_input(INPUT_POST,"newpass", FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($profUname) ||  empty($oldpass) || empty($newpass)){
            echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div class="alert_label">
                        Something is missing!
                    </div>
                </div> ';
        }
        else{
            if(password_verify($oldpass,  $verifypass)){
                if(password_verify($newpass,  $verifypass))
                {
                    echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            <div class="alert_label">
                                Your new password is still the same with your old password!
                            </div>
                        </div> ';
                }

                else{
                    $newpword = password_hash($newpass, PASSWORD_DEFAULT);
                    try{
                        include "../database.php";
                        
                        $query = "UPDATE user_accounts SET USERNAME = :uname, PASSWORD = :pword WHERE USER_ID = :userID";
                
                        $stmt = $pdo->prepare($query);
                        
                        $stmt->bindParam(":uname", $profUname);
                        $stmt->bindParam(":pword", $newpword);
                        $stmt->bindParam(":userID", $userID);
                
                        $stmt->execute();

                        unset($_SESSION['username']);
                        $_SESSION["username"] = $profUname;
                
                        echo '<div class="alert alert-success d-flex align-items-center profile_alert" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                    <div class="alert_label">
                                        Password changed successfully!
                                    </div>
                                </div> ';
                
                        $pdo = null;
                        $stmt = null;
                    }
                    catch(PDOException $e){
                        echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div class="alert_label">
                                     Query failed: '.$e->getMessage().'
                                </div>
                            </div> ';
                    }
                }
            }
            else{
                echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div class="alert_label">
                            Wrong Password!
                        </div>
                    </div> ';
            }
        }
    }
}

function change_user_info($userID){
    if(isset($_POST["changeInfo"])) {
        $profileFname = filter_input(INPUT_POST,"newfname", FILTER_SANITIZE_SPECIAL_CHARS);
        $profileFname = trim($profileFname);
        $profileFname = ucwords($profileFname);
        $profileLname = filter_input(INPUT_POST,"newlname", FILTER_SANITIZE_SPECIAL_CHARS);
        $profileLname = trim($profileLname);
        $profileLname = ucwords($profileLname);
        $profphnum = filter_input(INPUT_POST,"newphnum", FILTER_SANITIZE_SPECIAL_CHARS);
        $profphnum = trim($profphnum );
        $pattern = '/^[A-Za-z]+$/';
        $pattern2 = '/^\d{11}$/';

        if(empty($profileFname) || empty($profileLname) || empty($profphnum)){
            echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div class="alert_label">
                        Something is missing!
                    </div>
                </div> ';
        }else{
            if(preg_match($pattern, $profileFname) || preg_match($pattern, $profileLname)){
                if(preg_match($pattern2, $profphnum)){
                    try{
                        include "../database.php";
                        
                        $query = "UPDATE user_accounts SET USER_FIRSTNAME = :profname, USER_LASTNAME = :proflname, USER_PHONENUM = :phnumber WHERE USER_ID = :userID";
                
                        $stmt = $pdo->prepare($query);
                        
                        $stmt->bindParam(":profname", $profileFname);
                        $stmt->bindParam(":proflname", $profileLname);
                        $stmt->bindParam(":phnumber", $profphnum);
                        $stmt->bindParam(":userID", $userID);
                
                        $stmt->execute();
                
                        echo '<script>setTimeout(function () { window.location.href = "dashboard.php";}, 500);</script>';
                
                        $pdo = null;
                        $stmt = null;
                    }
                    catch(PDOException $e){
                        echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                <div class="alert_label">
                                        Query failed: '.$e->getMessage().'
                                </div>
                            </div> ';
                    }
                }
                else{
                    echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            <div class="alert_label">
                                Invalid Phone Number
                            </div>
                        </div> ';
                }
            }
            else{
                echo '<div class="alert alert-danger d-flex align-items-center profile_alert" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div class="alert_label">
                            Invalid firstname or lastname
                        </div>
                    </div> ';
            }
        }
    }
}

function add_inventory(){
    if(isset($_POST["add_item"])) {
        $description = filter_input(INPUT_POST, "inv_desc", FILTER_SANITIZE_SPECIAL_CHARS);
        $description = trim($description);
        $quantity = filter_input(INPUT_POST,"inv_qnty", FILTER_SANITIZE_NUMBER_INT);
        $quantity = trim($quantity);
        $unit = filter_input(INPUT_POST, "inv_unit", FILTER_SANITIZE_SPECIAL_CHARS);
        $unit = trim($unit);
        $price = filter_input(INPUT_POST, "inv_price", FILTER_VALIDATE_FLOAT);
        $price = trim($price);
        $invBranch = filter_input(INPUT_POST, "inv_branch", FILTER_SANITIZE_SPECIAL_CHARS);
        $invBranch = trim($invBranch);

        if(empty($description) || empty($quantity) || empty($unit) || empty($price) || empty($invBranch) || empty($_FILES['inv_pic'])){
            echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        SOMETHING IS MISSING!
                </div>';
        }else if($price <= 0 || $quantity <= 0){
            echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        Numbers that are zero and below is not acceptable!
                </div>';
        }else{
            $img_name = $_FILES['inv_pic']['name'];
            $img_size = $_FILES['inv_pic']['size'];
            $tmp_name = $_FILES['inv_pic']['tmp_name'];
            $error = $_FILES['inv_pic']['error'];
            
                if ($error === 0) {
                    if ($img_size > 2525000) {
                        echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    File is too large!
                            </div>';
                    }else {
                        $img_ex = pathinfo($img_name, PATHINFO_EXTENSION);
                        $img_ex_lc = strtolower($img_ex);
                        $filename = pathinfo($img_name, PATHINFO_FILENAME);
            
                        $allowed_exs = array("jpg", "jpeg", "png"); 
            
                        if (in_array($img_ex_lc, $allowed_exs)) {
                            $new_img_name = uniqid($filename." ", true).'.'.$img_ex_lc;
                            $img_upload_path = '../Inventory_Pic/'.$new_img_name;
                            move_uploaded_file($tmp_name, $img_upload_path);
                            
                            date_default_timezone_set('Asia/Manila');
                            $currentDate = new DateTime();
                            $date = $currentDate -> format('Y-m-d');

                            try{
                                include "database.php";
                                
                                $query = "INSERT INTO inventory (INV_DESCRIPTION, INV_PIC, INV_INDATE, INV_QOH, INV_UNIT, INV_PRICE, INV_BRANCH)
                                          VALUES (?, ?, ?, ?, ?, ?, ?);";

                                $stmt = $pdo->prepare($query);

                                $stmt->execute([$description, $new_img_name, $date, $quantity, $unit, $price, $invBranch]);

                                $pdo = null;
                                $stmt = null;

                                echo '<div class="alert alert-success d-flex align-items-center reg-status" role="alert">
                                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                            Saved Successfully!
                                      </div>';

                                echo '<script>setTimeout(function () { window.location.href = "add_inventory.php";}, 600);</script>';

                                die();
                            }
                            catch(PDOException $e){
                                echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                               Query failed: '.$e->getMessage().'
                                        </div>';
                            }

                        }else {
                            echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                            Sorry, you cant upload this type of file
                                    </div>';
                        }
                    }
                }else {
                    echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                    unknown error occurred!
                            </div>';
                }
            }
        }
    else{
        echo "";
    }
}

function total_items($branch){
    include "database.php";

    $query = "SELECT COUNT(*) as total_count FROM INVENTORY WHERE INV_BRANCH = :branch AND INV_ACTIVE = 1";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":branch", $branch);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $total = $result['total_count'];

    return $total;
}

function total_deleted_items($branch){
    include "database.php";

    $query = "SELECT COUNT(*) as total_count FROM INVENTORY WHERE INV_BRANCH = :branch AND INV_ACTIVE = 0";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":branch", $branch);

    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC); 

    $total = $result['total_count'];

    return $total;
}

function inventory_item($userID, $branch){
    try{
        include "../database.php";

        if(isset($_POST['searchBar'])){
            $searchKey = filter_input(INPUT_POST, "searchBar", FILTER_SANITIZE_SPECIAL_CHARS);
            $searchKey = trim($searchKey);
            $query = "SELECT * FROM inventory WHERE (INV_DESCRIPTION LIKE '%$searchKey%' OR INV_UNIT LIKE '%$searchKey%'
                      OR INV_ID LIKE '%$searchKey%' OR INV_PRICE LIKE '%$searchKey%') AND 
                      (INV_BRANCH = :branch AND INV_ACTIVE = 1) ORDER BY INV_QOH ASC";
        }
        else{
            $query = "SELECT * FROM inventory WHERE INV_BRANCH = :branch AND INV_ACTIVE = 1 ORDER BY INV_QOH ASC";
            $searchKey = "";
        }

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            foreach($result as $row){ 
                if($row["INV_QOH"] > 0  && $row["INV_QOH"] <= 8){
                    $border = 'border-warning';
                }
                else if($row["INV_QOH"] == 0){
                    $border = 'border-danger';
                }
                else{
                    $border = 'border-success';
                }

                $query = "SELECT * FROM user_accounts WHERE USER_ID = :userID";

                $stmt = $pdo->prepare($query);

                $stmt->bindParam(":userID", $userID);

                $stmt->execute();

                $actions = $stmt->fetch(PDO::FETCH_ASSOC);

                if($actions["USER_BRANCH"] == "ADMIN"){
                    $viewAction = '<a href="edit_item.php?invID='.$row['INV_ID'].'"><i class="bx bxs-edit" style="color:#06c700"></i></a>';
                    $viewAction2 = '<a href="actions/remove_item.php?invID='.$row['INV_ID'].'"><i class="bx bxs-trash bxs-trash2" style="color:#ff0003"></i></a>';
                }
                else{
                    $viewAction = '';
                    $viewAction2 = '';
                }

                echo '<div class="card '.$border.' bg-light border-3" style="width: 19rem;">
                        <div class="card-header bg-transparent border-success"> 
                            '.$viewAction.'
                            <p>'.$row["INV_DESCRIPTION"].'</p>
                            '.$viewAction2.'
                        </div>
                        <img src="../Inventory_Pic/'.$row["INV_PIC"].'" class="card-img-top" alt="...">
                        <div class="card-body">
                            <div class="first-row">
                                <p class="card-text">ID: <span>'.$row["INV_ID"].'</span></p>
                                <p class="card-text">Unit: <span>'.$row["INV_UNIT"].'</span></p>
                            </div>
                            <div class="second-row">
                                <p class="card-text">Price: <span>&#8369;'.$row["INV_PRICE"].'</span></p>
                                <p class="card-text">Stocks: <span>'.$row["INV_QOH"].'</span></p>
                            </div>
                            <div class="third-row">
                                <p class="card-text">Updated: <span>'.$row["INV_INDATE"].'</span></p>
                            </div>
                        </div>
                    </div>';
            }

        }
        else{
            echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        INVENTORY IS EMPTY!
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        Query failed: '.$e->getMessage().' 
                    </div>
                </div>';
    }
}


function deleted_inventory_item($userID, $branch){
    try{
        include "../database.php";
        
        $query = "SELECT * FROM inventory WHERE INV_BRANCH = :branch AND INV_ACTIVE = 0 ORDER BY INV_QOH ASC";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            foreach($result as $row){ 
                if($row["INV_QOH"] > 0  && $row["INV_QOH"] <= 8){
                    $border = 'border-warning';
                }
                else if($row["INV_QOH"] == 0){
                    $border = 'border-danger';
                }
                else{
                    $border = 'border-success';
                }

                $query = "SELECT * FROM user_accounts WHERE USER_ID = :userID";

                $stmt = $pdo->prepare($query);

                $stmt->bindParam(":userID", $userID);

                $stmt->execute();

                $actions = $stmt->fetch(PDO::FETCH_ASSOC);

                if($actions["USER_BRANCH"] == "ADMIN"){
                    $viewAction = '<a href="actions/retrieve_item.php?invID='.$row['INV_ID'].'"><img src="image/return-box.png" alt="" class="return"></a>';
                    $viewAction2 = '<a href="actions/delete_item.php?invID='.$row['INV_ID'].'"><i class="bx bxs-trash bxs-trash2" style="color:#ff0003"></i></a>';
                }
                else{
                    $viewAction = '';
                    $viewAction2 = '';
                }

                echo '<div class="card '.$border.' bg-light border-3" style="width: 19rem;">
                            <div class="card-header bg-transparent border-success"> 
                                '.$viewAction.'
                                <p>'.$row["INV_DESCRIPTION"].'</p>
                                '.$viewAction2.'
                            </div>
                            <img src="../Inventory_Pic/'.$row["INV_PIC"].'" class="card-img-top" alt="...">
                            <div class="card-body">
                                <div class="first-row">
                                    <p class="card-text">ID: <span>'.$row["INV_ID"].'</span></p>
                                    <p class="card-text">Unit: <span>'.$row["INV_UNIT"].'</span></p>
                                </div>
                                <div class="second-row">
                                    <p class="card-text">Price: <span>&#8369;'.$row["INV_PRICE"].'</span></p>
                                    <p class="card-text">Stocks: <span>'.$row["INV_QOH"].'</span></p>
                                </div>
                                <div class="third-row">
                                    <p class="card-text">Supplier: <span>'.$row["SUP_CODE"].'</span></p>
                                    <p class="card-text">Updated: <span>'.$row["INV_INDATE"].'</span></p>
                                </div>
                            </div>
                        </div>';
            }

        }
        else{
            echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                       There is no deleted Inventory item !
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert alert-primary d-flex align-items-center user_alert" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                        Query failed: '.$e->getMessage().' 
                    </div>
                </div>';
    }
}



function update_inventory($itemsID){
    if(isset($_POST["updateItem"])) {
        $description = filter_input(INPUT_POST, "updateName", FILTER_SANITIZE_SPECIAL_CHARS);
        $description = trim($description);
        $quantity = filter_input(INPUT_POST,"updateQOH", FILTER_SANITIZE_NUMBER_INT);
        $quantity = trim($quantity);
        $unit = filter_input(INPUT_POST, "updateUnit", FILTER_SANITIZE_SPECIAL_CHARS);
        $unit = trim($unit);
        $price = filter_input(INPUT_POST, "updatePrice", FILTER_VALIDATE_FLOAT);
        $price = trim($price);

        if(empty($description) || empty($quantity) || empty($unit) || empty($price)){
            echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        SOMETHING IS MISSING!
                </div>';
        }else if($price <= 0 || $quantity <= 0){
            echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        Numbers that are zero and below is not acceptable!
                </div>';
        }else{              
            date_default_timezone_set('Asia/Manila');
            $currentDate = new DateTime();
            $date = $currentDate -> format('Y-m-d');

            try{
                include "database.php";
                
                $query = "UPDATE INVENTORY SET INV_DESCRIPTION = :invdesc, INV_QOH = :invqoh, INV_UNIT = :invunit, INV_PRICE = :invprice, INV_INDATE = :indate WHERE INV_ID = :invid";

                $stmt = $pdo->prepare($query);
                
                $stmt->bindParam(":invdesc", $description);
                $stmt->bindParam(":invqoh", $quantity);
                $stmt->bindParam(":invunit", $unit);
                $stmt->bindParam(":invprice", $price, PDO::PARAM_STR);
                $stmt->bindParam(":indate", $date);
                $stmt->bindParam(":invid", $itemsID);

                $stmt->execute();

                echo '<div class="alert alert-success d-flex align-items-center reg-status" role="alert">
                <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    Updated Successfully!
                </div>';

                echo '<script>setTimeout(function () { window.location.href = "LLCInventory.php";}, 600);</script>';
        
                $pdo = null;
                $stmt = null;

                die();
            }
            catch(PDOException $e){
                echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                                Query failed: '.$e->getMessage().'
                        </div>';
            }

          }
        }
    else{
        echo "";
    }
}

function add_invoice($branch, $cashier){
    if(isset($_POST["addInvc"])) {
        $cusType = filter_input(INPUT_POST, "cus_type", FILTER_SANITIZE_SPECIAL_CHARS);
        $cusType = trim($cusType);
        $mop = filter_input(INPUT_POST, "mop_type", FILTER_SANITIZE_SPECIAL_CHARS);
        $mop = trim($mop);

        if(empty($cusType) || empty($mop)){
            echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                     Please fill all the fields!
                  </div> ';
        }else{
            date_default_timezone_set('Asia/Manila');
            $currentDate = new DateTime();
            $date = $currentDate -> format('Y-m-d');
            $time = date('h:i:s A');
            try{
                include "../database.php";
                
                $query = "INSERT INTO INVOICE (INVC_DATE, INVC_BRANCH, INVC_CASHIER, INVC_CUSTOMER, INVC_MOP, INVC_TIME) VALUES (?, ?, ?, ?, ?, ?);";

                $stmt = $pdo->prepare($query);

                $stmt->execute([$date, $branch, $cashier, $cusType, $mop, $time]);
                
                echo '<script>setTimeout(function () { window.location.href = "dashboard.php";});</script>';
        
            }
            catch(PDOException $e){
                echo '<div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            '.$e->getMessage().'
                    </div> ';
            }
        }
    }
}

function save_order_list(){
    if(isset($_POST["order_save"])) {
        include "../database.php";
        global $unit, $price, $inv_qty;
        $quantity = filter_input(INPUT_POST,"item_qty", FILTER_SANITIZE_NUMBER_INT);
        $quantity = trim($quantity);
        $inventoryID = filter_input(INPUT_POST,"invID", FILTER_SANITIZE_NUMBER_INT);
        $inventoryID = trim($inventoryID);
        $invoiceID = filter_input(INPUT_POST,"invc_id", FILTER_SANITIZE_NUMBER_INT);
        $invoiceID= trim( $invoiceID);
        $discount = filter_input(INPUT_POST, "discount", FILTER_VALIDATE_FLOAT);
        $discount= trim($discount);

        $query2 = "SELECT * FROM inventory WHERE INV_ID = :invID";

        $stmt2 = $pdo->prepare($query2);

        $stmt2->bindParam(":invID",  $inventoryID);

        $stmt2->execute();

        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        if(empty($result2)){
            echo '<div class="alert-box">
                    <div class="alert alert-danger d-flex align-items-center" role="alert" style="font-weight: 700; font-size: 20px;">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            Invalid Inventory ID !
                    </div>
                </div>';
                die();
        }else{
            $unit = $result2["INV_UNIT"];
            $price = $result2["INV_PRICE"];
            $inv_qty = $result2["INV_QOH"];
        }

        if(empty($quantity) || empty($inventoryID) || empty($invoiceID)){
            echo '<div class="alert-box">
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            Something is missing !
                    </div>
                </div>';
        }else if($quantity <= 0){
            echo '<div class="alert-box">
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            Numbers that are zero and below is not acceptable!
                    </div>
                </div>';
        }else if($quantity > $inv_qty){
            echo '<div class="alert-box">
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            Insufficient stocks in inventory!
                    </div>
                </div>';
        }else{
            date_default_timezone_set('Asia/Manila');
            $currentDate = new DateTime();
            $date = $currentDate -> format('Y-m-d');
            try{
                include "database.php";
                
                $query = "INSERT INTO SALES(SALES_DATE, SALES_QTY, SALES_UNIT, SALES_PRICE, SALES_DISCOUNT, INV_ID, INVC_NUMBER)
                            VALUES (?, ?, ?, ?, ?, ?, ?);";

                $stmt = $pdo->prepare($query);

                $stmt->execute([$date, $quantity, $unit, $price, $discount, $inventoryID, $invoiceID]);

                $query3 = "UPDATE INVENTORY SET INV_QOH = INV_QOH - :invqty WHERE INV_ID = :invID";

                $stmt3 = $pdo->prepare($query3);

                $stmt3->bindParam(":invqty", $quantity);
                $stmt3->bindParam(":invID", $inventoryID);
        
                $stmt3->execute();

                echo '<script>setTimeout(function () { window.location.href = "dashboard.php";});</script>';

                echo '<div class="alert-box">
                        <div class="alert alert-success d-flex align-items-center reg-status" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                                Saved Successfully!
                        </div>
                    </div>';

            }
            catch(PDOException $e){
                echo '<div class="alert-box">
                        <div class="alert alert-danger d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                             '.$e->getMessage().'
                        </div>
                    </div>';
            }
        }

    }else{
        echo "";
    }
}


function invoice_table($branch){
    try{
        include "database.php";
        
        $query = "SELECT * FROM INVOICE WHERE INVC_STATUS != 'UNUSED' AND INVC_ACTIVE = 1 AND INVC_BRANCH = :branch";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            ?>
                <table class="table table-hover table-striped text-center">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">INVOICE ID</th>
                            <th scope="col">BRANCH</th>
                            <th scope="col">CASHIER</th>
                            <th scope="col">CUSTOMER</th>
                            <th scope="col">MOP</th>
                            <th scope="col">DATE</th>
                            <th scope="col">TIME</th>
                            <th scope="col">ACTION</th>
                        </tr>
                    </thead>
                <tbody>
            <?php

            foreach($result as $row){
                echo '<tr >
                        <td>'.$row["INVC_NUMBER"].'</td>
                        <td>'.$row["INVC_BRANCH"].'</td>
                        <td>'.$row["INVC_CASHIER"].'</td>
                        <td>'.$row["INVC_CUSTOMER"].'</td>
                        <td>'.$row["INVC_MOP"].'</td>
                        <td>'.$row["INVC_DATE"].'</td>
                        <td>'.$row["INVC_TIME"].'</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#invcModal"><i class="bx bx-money" ></i></a>
                            <a href="actions/deleteInvoice.php?invcNUM='.$row['INVC_NUMBER'].'"><i class="bx bxs-trash" ></i></a>
                        </td>
                     </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else{
            echo '<div class="alert-box">
                    <div class="alert alert-primary d-flex align-items-center" role="alert" style="font-weight: 700; font-size: 20px;">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                        <div class="alert_label">
                            No Invoice Available !
                        </div>
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert-box">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                         '.$e->getMessage().'
                    </div>
                </div>
            </div>';
    }
}


function deleted_invoice_table($branch){
    try{
        include "database.php";
        
        $query = "SELECT * FROM INVOICE WHERE INVC_STATUS != 'UNUSED' AND INVC_ACTIVE = 0 AND INVC_BRANCH = :branch";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            ?>
                <table class="table text-center table-bordered border-dark">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col">INVOICE ID</th>
                            <th scope="col">BRANCH</th>
                            <th scope="col">CASHIER</th>
                            <th scope="col">CUSTOMER</th>
                            <th scope="col">MOP</th>
                            <th scope="col">DATE</th>
                            <th scope="col">TIME</th>
                            <th scope="col">ACTION</th>
                        </tr>
                    </thead>
                <tbody>
            <?php

            foreach($result as $row){
                echo '<tr >
                        <td>'.$row["INVC_NUMBER"].'</td>
                        <td>'.$row["INVC_BRANCH"].'</td>
                        <td>'.$row["INVC_CASHIER"].'</td>
                        <td>'.$row["INVC_CUSTOMER"].'</td>
                        <td>'.$row["INVC_MOP"].'</td>
                        <td>'.$row["INVC_DATE"].'</td>
                        <td>'.$row["INVC_TIME"].'</td>
                        <td>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#invcModal"><i class="bx bx-money" ></i></a>
                            <a href="actions/retrieveInvoice.php?invcNUM='.$row["INVC_NUMBER"].'" class="retrieve">RETRIEVE</a>
                        </td>
                     </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else{
            echo '<div class="alert-box">
                    <div class="alert alert-info d-flex align-items-center" role="alert" style="font-weight: 700; font-size: 20px;">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                        <div class="alert_label">
                            No Deleted Invoice Available !
                        </div>
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert-box">
                <div class="alert alert-infod-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                         '.$e->getMessage().'
                    </div>
                </div>
            </div>';
    }
}



function sales_table($branch){
    try{
        include "database.php";
        
        $query = "SELECT * FROM SALES, PAYMENTS, INVOICE WHERE INVC_BRANCH = :branch AND SALES_DATE = CURDATE() AND INVOICE.INVC_NUMBER = SALES.INVC_NUMBER AND INVOICE.INVC_NUMBER = PAYMENTS.INVC_NUMBER";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            ?>
                <table class="table table-hover table-light table-striped text-center">
                    <thead class="table-secondary">
                    <tr>
                        <th scope="col">SALES ID</th>
                        <th scope="col">DATE</th>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">UNIT</th>
                        <th scope="col">PRICE</th>
                        <th scope="col">DISCOUNT</th>
                        <th scope="col">PAYABLE</th>
                        <th scope="col">PAID</th>
                        <th scope="col">CHANGE</th>
                        <th scope="col">MOP</th>
                        <th scope="col">INVENTORY ID</th>
                    </tr>
                    </thead>
                <tbody>
            <?php

            foreach($result as $row){
                echo '<tr >
                        <td>'.$row["SALES_ID"].'</td>
                        <td>'.$row["SALES_DATE"].'</td>
                        <td>'.$row["SALES_QTY"].'</td>
                        <td>'.$row["SALES_UNIT"].'</td>
                        <td>'.$row["SALES_PRICE"].'</td>
                        <td>'.$row["SALES_DISCOUNT"].'</td>
                        <td>'.$row["PAYABLE"].'</td>
                        <td>'.$row["PAID"].'</td>
                        <td>'.$row["PAY_CHANGE"].'</td>
                        <td>'.$row["INVC_MOP"].'</td>
                        <td>'.$row["INV_ID"].'</td>
                     </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else{
            echo '<div class="alert-box">
                    <div class="alert alert-primary d-flex align-items-center" role="alert" style="font-weight: 700; font-size: 20px;">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                        <div class="alert_label">
                            No Sales Available !
                        </div>
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert-box">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                         '.$e->getMessage().'
                    </div>
                </div>
            </div>';
    }
}



function sold_item_table($branch){
    try{
        include "database.php";
        
        $query = "SELECT * FROM SALES, INVENTORY, INVOICE WHERE INVC_BRANCH = :branch AND SALES_DATE = CURDATE() AND INVOICE.INVC_NUMBER = SALES.INVC_NUMBER AND SALES.INV_ID = INVENTORY.INV_ID";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            ?>
                <table class="table table-hover table-light table-striped text-center">
                    <thead class="table-secondary">
                    <tr>
                        <th scope="col">INVENTORY ID</th>
                        <th scope="col">ITEM NAME</th>
                        <th scope="col">QUANTITY</th>
                        <th scope="col">UNIT</th>
                        <th scope="col">PRICE</th>
                    </tr>
                    </thead>
                <tbody>
            <?php

            foreach($result as $row){
                echo '<tr >
                        <td>'.$row["INV_ID"].'</td>
                        <td>'.$row["INV_DESCRIPTION"].'</td>
                        <td>'.$row["SALES_QTY"].'</td>
                        <td>'.$row["SALES_UNIT"].'</td>
                        <td>'.$row["SALES_PRICE"].'</td>
                     </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else{
            echo '<div class="alert-box">
                    <div class="alert alert-primary d-flex align-items-center" role="alert" style="font-weight: 700; font-size: 20px;">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                        <div class="alert_label">
                            No Invoice Available !
                        </div>
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert-box">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                         '.$e->getMessage().'
                    </div>
                </div>
            </div>';
    }
}



function staff_sales_table($branch){
    try{
        include "database.php";
        
        $query = "SELECT * FROM SALES, INVENTORY WHERE INV_BRANCH = :branch AND SALES_DATE = CURDATE() AND SALES.INV_ID = INVENTORY.INV_ID";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            ?>
                <table class="table table-hover table-striped text-center">
                    <thead class="table-warning">
                    <tr>
                            <th scope="col">SALES ID</th>
                            <th scope="col">ITEM</th>
                            <th scope="col">QTY</th>
                            <th scope="col">UNIT</th>
                            <th scope="col">PRICE</th>
                            <th scope="col">DISCOUNT</th>
                            <th scope="col">TOTAL</th>
                            <th scope="col">INVENTORY ID</th>
                        </tr>
                    </thead>
                <tbody>
            <?php

            foreach($result as $row){
                echo '<tr >
                        <td>'.$row["SALES_ID"].'</td>
                        <td>'.$row["INV_DESCRIPTION"].'</td>
                        <td>'.$row["SALES_QTY"].'</td>
                        <td>'.$row["SALES_UNIT"].'</td>
                        <td>'.$row["SALES_PRICE"].'</td>
                        <td>'.$row["SALES_DISCOUNT"].'</td>
                        <td>'.'&#8369;'.number_format($row["SALES_QTY"] * $row["SALES_PRICE"], 2, '.').'</td>
                        <td>'.$row["INV_ID"].'</td>
                     </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else{
            echo '<div class="alert-box">
                    <div class="alert alert-primary d-flex align-items-center" role="alert" style="font-weight: 700; font-size: 20px;">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                        <div class="alert_label">
                            No Invoice Available !
                        </div>
                    </div>
                </div>';
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert-box">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                         '.$e->getMessage().'
                    </div>
                </div>
            </div>';
    }
}


function order_item_list($branch){
    try{
        include "database.php";
        
        $query = "SELECT * FROM INVENTORY, SALES, INVOICE WHERE INVC_BRANCH = :branch AND INVC_STATUS = 'UNUSED' AND 
                  INVOICE.INVC_NUMBER = SALES.INVC_NUMBER AND SALES.INV_ID = INVENTORY.INV_ID";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":branch", $branch);

        $stmt->execute();

        $result = $stmt->fetchAll(PDO::FETCH_ASSOC); 

        if(!empty($result)){
            ?>
             <table class="table table-bordered table-hover table-striped">
                <thead class="table-secondary">
                    <tr>
                    <th scope="col">Invoice #</th>
                    <th scope="col">Description</th>
                    <th scope="col">Price</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Unit</th>
                    <th scope="col">Discount</th>
                    <th scope="col">TOTAL</th>
                    </tr>
                </thead>
                <tbody>
            <?php

            foreach($result as $row){
                echo '<tr >
                        <td>'.$row["INVC_NUMBER"].'</td>
                        <td>'.$row["INV_DESCRIPTION"].'</td>
                        <td>'.$row["SALES_PRICE"].'</td>
                        <td>'.$row["SALES_QTY"].'</td>
                        <td>'.$row["SALES_UNIT"].'</td>
                        <td>'.$row["SALES_DISCOUNT"].' &#37'.'</td>
                        <td>'.'&#8369;'.number_format(($row["SALES_PRICE"] - ($row["SALES_PRICE"] * $row["SALES_DISCOUNT"] / 100)) * $row["SALES_QTY"], 2, '.').'</td>
                     </tr>';
            }
            echo '</tbody>';
            echo '</table>';
        }
        else{
            echo "";
        }
        $pdo = null;
        $stmt = null;
    }
    catch(PDOException $e){
        echo '<div class="alert-box">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div class="alert_label">
                         '.$e->getMessage().'
                    </div>
                </div>
            </div>';
    }
}


function payments($totPrice, $branch){
    if(isset($_POST["getPaid"])) {
        include "../database.php";
        $pay = filter_input(INPUT_POST, "cash", FILTER_VALIDATE_FLOAT);
        $pay = trim($pay);

        $change = $pay - $totPrice;

        $query2 = "SELECT INVC_NUMBER FROM invoice WHERE INVC_STATUS = 'UNUSED' AND INVC_BRANCH = :branch";

        $stmt2 = $pdo->prepare($query2);

        $stmt2->bindParam(":branch", $branch);

        $stmt2->execute();

        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        if(empty($result2)){
            $invoiceID = '';
        }
        else{
            $invoiceID = $result2["INVC_NUMBER"];
        }

        if(empty($pay) || empty($totPrice)){
            echo '<div class="alert-box2">
                    <div class="alert2 alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            Something is missing !
                    </div>
                </div>';
        }else if($change < 0){
            echo '<div class="alert-box2">
                    <div class="alert2 alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                            Insufficient amount!
                    </div>
                </div>';
        }else{
            try{
                include "database.php";
                
                $query = "INSERT INTO PAYMENTS(PAID, PAYABLE, PAY_CHANGE, INVC_NUMBER)
                            VALUES (?, ?, ?, ?);";

                $stmt = $pdo->prepare($query);

                $stmt->execute([$pay, $totPrice, $change, $invoiceID]);

                echo '<script>setTimeout(function () { window.location.href = "invoice.php";});</script>';

                $query3 = "UPDATE invoice SET INVC_STATUS = 'PRINTABLE' WHERE INVC_STATUS = 'UNUSED' AND INVC_BRANCH = :branch2";

                $stmt3 = $pdo->prepare($query3);
        
                $stmt3->bindParam(":branch2", $branch);
        
                $stmt3->execute();

            }
            catch(PDOException $e){
                echo '<div class="alert-box2">
                        <div class="alert2 alert alert-danger d-flex align-items-center" role="alert">
                            <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                             '.$e->getMessage().'
                        </div>
                    </div>';
            }
        }

    }else{
        echo "";
    }
}
