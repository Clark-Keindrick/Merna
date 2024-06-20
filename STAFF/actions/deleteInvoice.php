<?php 
    $invcnum = $_GET["invcNUM"];
     try{
        include "../../database.php";
        
        $query = "UPDATE INVOICE SET INVC_ACTIVE = 0 WHERE INVC_NUMBER = :invcnumber";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":invcnumber", $invcnum );

        $stmt->execute();

        echo '<script>setTimeout(function () { window.location.href = "../invoice.php";}, 500);</script>';

        $pdo = null;
        $stmt = null;
        die();
    }
    catch(PDOException $e){
        die("Query failed: ". $e->getMessage());
    }
?>