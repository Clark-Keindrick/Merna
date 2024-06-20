<?php 
    $invID = $_GET["invID"];
     try{
        include "../../database.php";
        
        $query = "UPDATE INVENTORY SET INV_ACTIVE = 1 WHERE INV_ID = :inventoryID";

        $stmt = $pdo->prepare($query);

        $stmt->bindParam(":inventoryID", $invID);

        $stmt->execute();

        echo '<script>setTimeout(function () { window.location.href = "../LLCdeleted_items.php";}, 500);</script>';

        $pdo = null;
        $stmt = null;
        die();
    }
    catch(PDOException $e){
        die("Query failed: ". $e->getMessage());
    }
?>