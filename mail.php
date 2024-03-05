<?php
include 'components/connect.php';


session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   header('location: user_login.php');
};


$id = filter_input(INPUT_GET, 'read', FILTER_SANITIZE_NUMBER_INT);


// $select_messages = $conn->prepare("SELECT messages.id, users.name,users.email,users.phone,users.address,messages.msg_pic,messages.subject,messages.subject,messages.user_message,messages.admin_reply,messages.date_Time FROM messages INNER JOIN users ON messages.user_id=users.id WHERE messages.id=?");
$select_messages = $conn->prepare("SELECT 
sent_msg.id,
users.name AS user_name,
users.email,
users.phone,
users.address,
sent_msg.subject,
sent_msg.admin_reply,
sent_msg.date_Time,
sent_msg.admin_attch,
admins.name AS admin_name,
sent_msg.admin_id
FROM sent_msg
INNER JOIN users ON sent_msg.user_id = users.id
LEFT JOIN admins ON sent_msg.admin_id = admins.id 
WHERE sent_msg.id = ?;
");
$select_messages->execute([$id]);
   $fetch_message= $select_messages->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email</title>
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time()?>">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
</head>

<body>
    <?php include 'components/user_header.php'; ?>


    <div class="email-container">
        <div class="email-header">
            <a href="inbox.php"><i class="fa-solid fa-circle-xmark close-icon" style="color: #005eff;"></i></a>
            <h2>Subject:<?= $fetch_message['subject'];?></h2>
            <p>From:<?= $fetch_message['admin_name'].'@'.$fetch_message['admin_name'].'.com';?></p>
            <p><?= $fetch_message['date_Time'];?></p>
        </div>

        <div class="email-body">
            <p><?= $fetch_message['admin_reply'];?></p>
        </div>
        <div class="attachments">

            <?php
$fileExtension = pathinfo($fetch_message['admin_attch'], PATHINFO_EXTENSION);
$fileExtension = strtolower($fileExtension);
$filePath = 'message_picture/' . $fetch_message['admin_attch'];
$maxDisplayLength = 20;
if (in_array($fileExtension, ['jpg', 'png', 'jpeg', 'gif', 'pdf', 'docx'])) {
    $attachmentName = $fetch_message['admin_attch'];
    $displayAttachmentName = strlen($attachmentName) > $maxDisplayLength ? substr($attachmentName, 0, $maxDisplayLength) . '...' : $attachmentName;

    echo '<div class="attachment">';
    echo '<img src="' . $filePath . '" alt="' . $fetch_message['admin_attch'] . '" class="attachment-img">';
    echo '<div class="attachment-details">';
    echo '<a target="_blank" class="attachment-name" href="' . $filePath . '">' . $displayAttachmentName . '</a>';
    echo '<a href="' . $filePath . '" download><i class="fas fa-download"></i></a>';
   
    echo '</div>';
    echo '</div>';
} else {
    echo ''; 
}
?>
        </div>

        <div class="buttons">
            <a href="#" class="button" onclick="window.print()">
                <i class="fas fa-print"></i> Print
            </a>
            <?php
            $encryptAid=base64_encode($fetch_message['admin_id']);
            echo '<a href="contact.php?chat='.$encryptAid.'" class="button">
                <i class="fas fa-reply"></i> Reply
            </a>';
            ?>
            
        </div>
    </div>





</body>

</html>