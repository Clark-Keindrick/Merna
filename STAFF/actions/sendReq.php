<?php 
session_start();
$userID = $_SESSION["userID"];
    try{
    include "../../database.php";
    
    $query = "UPDATE REQUISITION SET REQ_STATUS = 'PENDING' WHERE REQ_STATUS = 'PROCESSING' AND USER_ID = :userID";

    $stmt = $pdo->prepare($query);

    $stmt->bindParam(":userID", $userID);

    $stmt->execute();

    echo '<script>setTimeout(function () { window.location.href = "../requisition.php";}, 500);</script>';

    $pdo = null;
    $stmt = null;
    die();
}
catch(PDOException $e){
    die("Query failed: ". $e->getMessage());
}
?>