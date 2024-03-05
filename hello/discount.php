<?php

// Assuming you have database connection details
$hostname = 'localhost';
$username = 'root';
$password = '';
$database = 'discount';

try {
    // Create a PDO instance
    $conn = new PDO("mysql:host=$hostname;dbname=$database", $username, $password);

    // Set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sample data
    $productId = 1; // Replace with the actual ID you want to update

    // Fetch the specific row based on ID
    $select = $conn->prepare("SELECT * FROM abc WHERE id = :productId");
    $select->bindParam(':productId', $productId, PDO::PARAM_INT);
    $select->setFetchMode(PDO::FETCH_ASSOC);
    $select->execute();
    $data = $select->fetch();

    if ($data) {
        
        if (isset($_POST['discount'])) {
            $newDiscountPercentage = $_POST['discount'];
  
          $update_dis_Price = $conn->prepare("UPDATE abc SET discount=? WHERE id = ?");
        $update_dis_Price->execute([$newDiscountPercentage, $productId]);
           $select = $conn->prepare("SELECT * FROM abc WHERE id = :productId");
            $select->bindParam(':productId', $productId, PDO::PARAM_INT);
            $select->setFetchMode(PDO::FETCH_ASSOC);
            $select->execute();
            $data = $select->fetch();
            $price = $data['price'];
            $discount = $data['discount'];

            $discountafterprice=$price-(($price*$discount)/100);

            echo $discountafterprice;
            
           
       
            // $updatediscountnew = $conn->prepare("SELECT * FROM abc WHERE id = :productId");
            // $updatediscountnew->bindParam(':productId', $productId, PDO::PARAM_INT);
            // $updatediscountnew->setFetchMode(PDO::FETCH_ASSOC);
            // $updatediscountnew->execute();
            // $data = $updatediscountnew->fetch();
            // $price = $data['price'];
            // $discount = $data['discount'];
            // $discountedDiff = 100 - $discount ;
            //  $discountedX = $discountedDiff / 100;
            //  $originalPP = $price / $discountedX;
            //  if($originalPP > $adddiscount){
            //  $updatedata=$conn->prepare("UPDATE abc SET discount = ? , price = ? WHERE id = ?");
            //     $updatedata->execute([$newDiscountPercentage,$originalPP,$productId]);

        
    
    }
        
        

        }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>

<form action="" method="post">
    input discount: <input type="text" name="discount"><br>
    <input type="submit" value="submit">
</form>