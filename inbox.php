<?php
date_default_timezone_set('Asia/Kathmandu');
include 'components/connect.php';


session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
    $user_id = '';
   header('location: user_login.php');
};
include 'components/user_header.php'; 


    // $selectmail = $conn->prepare("SELECT users.name,users.user_picture, users.email, users.phone, users.address,sent_msg.id,sent_msg.admin_attch, sent_msg.subject, sent_msg.admin_reply, sent_msg.date_Time FROM sent_msg INNER JOIN users ON sent_msg.user_id=users.id WHERE sent_msg.user_id=? ");
$selectmail = $conn->prepare("SELECT admins.name,admins.photo, sent_msg.id,sent_msg.admin_attch, sent_msg.subject, sent_msg.admin_reply, sent_msg.date_Time FROM sent_msg INNER JOIN admins ON sent_msg.admin_id=admins.id WHERE sent_msg.user_id=? ");
    $selectmail->execute([$user_id]);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inbox</title>

    <!-- font awesome cdn link  -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- custom css file link  -->
    <link rel="stylesheet" href="css/style.css?v=<?php echo Time();?>">

</head>
<style>
.inbox-box {
    max-width: 800px;
    margin: 20px auto;
    border: 1px solid #ccc;
    border-radius: 10px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    background-color: #fff;
}

.inbox-header {
    background-color: #005eff;
    color: #fff;
    padding: 10px;
    text-align: center;
    border-radius: 5px 5px 0 0;
    overflow: hidden;
}

.inbox-messages {
    padding: 15px;
    margin: 0 auto;
    max-width: 700px;
}

.message {
    margin: 10px 0;
    border-bottom: 1px solid #eee;
    padding: 20px;
    border-radius: 5px;
    position: relative;
    z-index: 0;
}

.message-sender_picture {
    width: 30px;
    height: 30px;
    border-radius: 50%;
    align-items: center;
    overflow: hidden;
    position: absolute;
    left: 20px;
    top: 20px;
}

.message-sender_picture img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.message-sender {
    font-weight: bold;
    font-size: 20px;
    margin: 0 0 0 80px;
    position: absolute;
}

.message-sender a {
    color: #005eff;
}

.message-subject {
    color: #005eff;
    font-weight: bold;
    font-size: 16px;
    margin: 0 0 0 200px;
}

.message-subject a {
    color: #005eff;
}

.message-body {
    font-size: 14px;
    margin: 0 50px 0 0;
    text-overflow: ellipsis;
    overflow: hidden;
    white-space: nowrap;
    word-wrap: break-word;
}

.message-body a {
    color: #666;
}

.attchm {
    margin: 0 0 10px 50px;
    /* Adjust margins as needed */
    color: #666;
    font-size: 14px;
    position: absolute;
    right: 20px;
}


/* Add hover effect on messages */
.message:hover {
    background-color: #f9f9f9;
    cursor: pointer;
}



@media screen and (max-width: 768px) {
    .inbox-box {
        width: 100%;
        min-width: 100px;
    }

    .message-sender {
        font-size: 14px;
        margin: 0 0 0 60px;
    }

    .message-subject {
        font-size: 14px;
        margin: 0 0 0 150px;
    }

    .message-body {
        font-size: 12px;
        margin: 0 50px 0 0;
    }

    .attchm {
        margin: 0 0 10px 50px;
        /* Adjust margins as needed */
        font-size: 12px;
    }

}

@media screen and (max-width: 400px) {
    .inbox-box {
        width: 100%;
        min-width: 100px;
        overflow: hidden;
    }

    .message-sender {
        font-size: 14px;
        margin: 0 0 0 40px;
    }

    .message-subject {
        font-size: 14px;
        margin: 0 0 0 150px;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        word-wrap: break-word;
    }

    .message-body {
        font-size: 12px;
        margin: 0 50px 0 0;
    }

    .attchm {
        display: none;
    }

}
</style>

<body>


    <div class="inbox-box">
        <div class="inbox-header">
            <h1>Inbox</h1>
        </div>
        <div class="inbox-messages">
            <?php 
            
if($selectmail->rowCount() == 0){
    echo '<p class="empty">No Messages </p>';
}else{
while ($fetchmail = $selectmail->fetch(PDO::FETCH_ASSOC)) {
    $adminReply = $fetchmail['admin_reply'];
    $words = str_word_count($adminReply, 1);
    $limitedText = implode(' ', array_slice($words, 0, 7));
    $profilePicture = (!empty($fetchmail['photo']))
                                ? "../admin_picture/{$fetchmail['photo']}"
                                : "../admin_picture/default_profile_picture.png"; 

    echo '<div class="message">';
    echo '<div class="message-sender_picture"><a href="mail.php?read=' . $fetchmail['id'] . '"><img src="./admin_picture/' . $profilePicture  . '" alt="Sender Picture"></a></div>';
    echo '<div class="message-sender"><a href="mail.php?read='.$fetchmail['id'].'">'.$fetchmail['name'].'</a></div>';
    echo '<div class="message-subject"><a href="mail.php?read='.$fetchmail['id'].'">'.$fetchmail['subject'].'</a></div>';
    echo '<div class="message-body"><a href="mail.php?read='.$fetchmail['id'].'">'.$limitedText. (count($words) > 7 ? '...' : '').'</a></div>';
if($fetchmail['admin_attch'] != ''){
    echo '<div><a class="fa fa-link attchm" href="mail.php?read='.$fetchmail['id'].'"></a></div>';
}  else
{
    echo "&nbsp;";
}

    echo '</div>';
}}
?>


        </div>
    </div>