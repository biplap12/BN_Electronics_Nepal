<?php
date_default_timezone_set('Asia/Kathmandu');
include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   header('location: user_login.php');
};

$select_user_details = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
    $select_user_details->execute([$user_id]);
    $fetch_user_details = $select_user_details->fetch(PDO::FETCH_ASSOC); // Corrected variable name
  
    // Assuming you have a database connection $conn
    
    if (isset($_POST['send'])) {
        // Validate and sanitize form inputs
        $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
        $dateAndTime = date("Y-m-d H:i:s");
        $dateAndTime = filter_var($dateAndTime, FILTER_SANITIZE_STRING);
        $orderid = filter_var($_POST['orderid'], FILTER_SANITIZE_STRING);
        $PaymentTransaction = filter_var($_POST['PaymentTransaction'], FILTER_SANITIZE_STRING);
    
        // Get user details from the database
        $select_user_details = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_user_details->execute([$user_id]);
        $fetch_user_details = $select_user_details->fetch(PDO::FETCH_ASSOC);
    
        if ($fetch_user_details) {
            // Generate a unique file name
            $username = $fetch_user_details['name']; // Replace with the actual username
            $date = date("Y-m-d_H-i-s"); // Use the current date and time
            $fileExtension = pathinfo($_FILES['contactImage']['name'], PATHINFO_EXTENSION);
            $newFileName = "BN_Electronic_Nepal_message_picture_${username}_${date}.${fileExtension}";
    
            // Check if the message already exists
            $select_message = $conn->prepare("SELECT * FROM `messages` WHERE user_message = ?");
            $select_message->execute([$msg]);
    
            if ($select_message->rowCount() > 0) {
                header('location: contact.php');
                $message[] = 'Already sent message!';
            } else {
                // Insert the message with the associated user name
                if (empty($orderid) || empty($PaymentTransaction) || empty($msg)) {
                    $message[] = 'Please fill in all required fields.';
                } else {
                    // Insert into the database
                    $insert_message = $conn->prepare("INSERT INTO `messages` (user_id, orderID, PayTran_ID, msg_pic, user_message, date_Time)
                        VALUES (?, ?, ?, ?, ?, ?)");
                    $insert_message->execute([$user_id, $orderid, $PaymentTransaction, $newFileName, $msg, $dateAndTime]);
    
                    // Upload file to storage
                    $target_dir = "./message_picture/";
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }
    
                    $target_file = $target_dir . $newFileName;
    
                    // Use move_uploaded_file correctly
                    if (move_uploaded_file($_FILES['contactImage']['tmp_name'], $target_file)) {
                        $message[] = 'Sent message successfully!';
                    } else {
                        $message[] = "Sorry, there was an error uploading your file.";
                    }
                }
            }
        } else {
            $message[] = 'User not found.';
        }
    } else {
        $message = [];
    }
    if(isset($_GET['chat'])){
        $chatid=intval(base64_decode($_GET['chat']));
    } else {
        $chatid='';
    }
    if(isset($_POST['sellermessage'])){
        $chatid=$_POST['chatid'];
        $msg = filter_var($_POST['msg'], FILTER_SANITIZE_STRING);
        $dateAndTime = date("Y-m-d H:i:s");
        $dateAndTime = filter_var($dateAndTime, FILTER_SANITIZE_STRING);
       
    
        // Get user details from the database
        $select_user_details = $conn->prepare("SELECT * FROM `users` WHERE id = ?");
        $select_user_details->execute([$user_id]);
        $fetch_user_details = $select_user_details->fetch(PDO::FETCH_ASSOC);
    
        if ($fetch_user_details) {
            // Generate a unique file name
            $username = $fetch_user_details['name']; // Replace with the actual username
            $date = date("Y-m-d_H-i-s"); // Use the current date and time
            $fileExtension = pathinfo($_FILES['contactImage']['name'], PATHINFO_EXTENSION);
            $newFileName = "BN_Electronic_Nepal_message_picture_${username}_${date}.${fileExtension}";
    
            // Check if the message already exists
            $select_message = $conn->prepare("SELECT * FROM `messages` WHERE user_message = ?");
            $select_message->execute([$msg]);
    
            if ($select_message->rowCount() > 0) {
                header('location: contact.php');
                $message[] = 'Already sent message!';
            } else {
                // Insert the message with the associated user name
                if (empty($msg)) {
                    $message[] = 'Please fill in all required fields.';
                } else {
                    // Insert into the database
                    $insert_message = $conn->prepare("INSERT INTO `messages` (user_id, msg_pic, user_message, date_Time, admin_id)
                        VALUES (?, ?, ?, ?, ?)");
                    $insert_message->execute([$user_id,  $newFileName, $msg, $dateAndTime, $chatid]);
    
                    // Upload file to storage
                    $target_dir = "./message_picture/";
                    if (!file_exists($target_dir)) {
                        mkdir($target_dir, 0755, true);
                    }
                    
                    $target_file = $target_dir . $newFileName;

                    // Use move_uploaded_file correctly
                    if (move_uploaded_file($_FILES['contactImage']['tmp_name'], $target_file)) {
                        $message[] = 'Sent message successfully!';
                    } else {
                        $message[] = "Sorry, there was an error uploading your file.";
                    }
                }
            }
        } else {
            $message[] = 'User not found.';
        }
    } else {
        $message = [];
    }
    

    
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">

</head>

<body>

    <?php include 'components/user_header.php'; ?>

    <section class="contact">

        <form action="" method="post" enctype="multipart/form-data">
            <h3>Get in touch</h3>
            <input type="text" name="name" placeholder="Name" value="<?=$fetch_user_details['name']?>"
                autocomplete="off" readonly maxlength="20" class="box">
            <input type="tel" name="number" value="<?=$fetch_user_details['phone']?>" placeholder="Phone No."
                autocomplete="off" readonly class="box">
            <input type="email" name="email" placeholder="Email" value="<?=$fetch_user_details['email']?>"
                autocomplete="off" readonly maxlength="50" class="box">
            <?php 
            if($chatid==''){
               echo' <input type="text" name="orderid" placeholder="Order Number/ID *" required maxlength="50" autocomplete="off"
                class="box">';
            echo '<input type="text" name="PaymentTransaction" placeholder="Payment Transaction ID" autocomplete="off"
                required maxlength="50" class="box">';
            }
            ?>
            <textarea name="msg" class="box" placeholder="Message" cols="30" rows="10" autocomplete="off"></textarea>
            <input type="file" name="contactImage" class="box" required autocomplete="off"
                accept=".jpg, .png, .pdf, .jpeg">
            <?php 
            if($chatid==''){
             echo '<input type="submit" value="Send Message" name="send" class="btn">';

            }
            else{
                echo '<input type="hidden" name="chatid" value="'.$chatid.'">';
                echo '<input type="submit" value="Send Message Seller" name="sellermessage" class="btn">';
            }
            ?>
        </form>

    </section>


    <?php include 'components/footer.php'; ?>

    <script src="js/script.js"></script>

</body>

</html>