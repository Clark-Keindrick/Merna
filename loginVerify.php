<?php
    session_start();
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $username =  filter_input(INPUT_POST,"uname", FILTER_SANITIZE_SPECIAL_CHARS);
        $username = trim($username);
        $pass =  filter_input(INPUT_POST,"password", FILTER_SANITIZE_SPECIAL_CHARS);
        $stat;

        date_default_timezone_set('Asia/Manila');
        $currentDate = new DateTime();
        $date = $currentDate -> format("Y-m-d g:i:s");

        if(!empty($username) && !empty($pass)){
            try{
                require_once("database.php");
                
                $query = "SELECT * FROM user_accounts where USER_ACTIVE = 1 AND USERNAME = :username;";
    
                $stmt = $pdo->prepare($query);

                $stmt->bindParam(":username", $username);
    
                $stmt->execute();

                $row = $stmt->fetch(PDO::FETCH_ASSOC); 

                if($username == $row["USERNAME"] && password_verify($pass,  $row["PASSWORD"]) && $row["USER_STATUS"] == "APPROVED"){
                    $last_login = $row["CURRENT_LOGIN"];
                    $query = "UPDATE user_accounts SET LAST_LOGIN = :lastLog, CURRENT_LOGIN= :curLog WHERE USERNAME = :username;";
                    $stmt = $pdo->prepare($query);

                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":lastLog", $last_login);
                    $stmt->bindParam(":curLog", $date);
        
                    $stmt->execute();

                    if($row["USER_BRANCH"] == "ADMIN"){
                        $_SESSION["branch"] = "ADMIN";
                        $_SESSION["username"] = $row["USERNAME"];
                        $_SESSION["userID"] = $row["USER_ID"];

                        echo '<script>setTimeout(function () { window.location.href = "Admin/dashboard.php";}, 100);</script>';
                    }
                    else if ($row["USER_BRANCH"] == "LLC"){
                        $_SESSION["branch"] = "LLC";
                        $_SESSION["username"] = $row["USERNAME"];
                        $_SESSION["userID"] = $row["USER_ID"];

                        echo '<script>setTimeout(function () { window.location.href = "STAFF/dashboard.php";}, 100);</script>';
                    }
                    else if ($row["USER_BRANCH"] == "CORDOVA"){
                        $_SESSION["branch"] = "CORDOVA";
                        $_SESSION["username"] = $row["USERNAME"];
                        $_SESSION["userID"] = $row["USER_ID"];

                        echo '<script>setTimeout(function () { window.location.href = "STAFF/dashboard.php";}, 100);</script>';
                    }
                    else{
                        echo "something went wrong!";
                    }
                }
                else{
                    $stat = "is-invalid";
                    header("Location: login.php?error=$stat");
                }
    
                $pdo = null;
                $stmt = null;
    
                die();
    
            }
            catch(PDOException $e){
                die("Query failed: ". $e->getMessage());
            }
        }
        else{
            $stat = "is-invalid";
            header("Location: login.php?error=$stat");
        }


    }
    else{
        header("Location: login.php");
    }

?>