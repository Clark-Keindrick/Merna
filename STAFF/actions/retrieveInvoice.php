<?php 
    $invcnum = $_GET["invcNUM"];
     try{
        include "../../database.php";
        
        $query = "UPDATE INVOICE SET INVC_ACTIVE = 1 WHERE INVC_NUMBER = :invcnumber";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":invcnumber", $invcnum );

        $stmt->execute();

        echo '<script>setTimeout(function () { window.location.href = "../trashbin.php";}, 500);</script>';

        $pdo = null;
        $stmt = null;
        die();
    }
    catch(PDOException $e){
        die("Query failed: ". $e->getMessage());
    }
?>