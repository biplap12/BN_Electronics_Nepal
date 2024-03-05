<?php

if(!isset($admin_id)){
   header('location:admin_login.php');
};
$admin_id = $_SESSION['admin_id'];

$select_admin = $conn->prepare("SELECT * FROM admins WHERE id=?");
$select_admin->execute([$admin_id]);
$fetch_admin = $select_admin->fetch();

if ($fetch_admin['admin_status'] === 'super') {
    $select_messages = $conn->prepare("SELECT * FROM messages");
    $select_messages->execute();
    $select_messages_sent_mail = $conn->prepare("SELECT * FROM sent_msg");
    $select_messages_sent_mail->execute();
    $select_messages_darft = $conn->prepare("SELECT * FROM draft");
    $select_messages_darft->execute();
} else {
    $select_messages = $conn->prepare("SELECT * FROM messages WHERE admin_id=?");
    $select_messages->execute([$admin_id]);
$select_messages_sent_mail = $conn->prepare("SELECT * FROM sent_msg WHERE admin_id=?");
$select_messages_sent_mail->execute([$admin_id]);
$select_messages_darft = $conn->prepare("SELECT * FROM draft WHERE admin_id=?");
$select_messages_darft->execute([$admin_id]);
}
$number_of_messages = $select_messages->rowCount();
$number_of_messages_sent_mail = $select_messages_sent_mail->rowCount();
$number_of_messages_darft = $select_messages_darft->rowCount();

?>
<div class="col-md-3">
    <a href="compose_message.php" class="btn btn-primary btn-block btn-lg mb-3"><i class="fa-solid fa-pen"></i>
        Compose</a>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Folders</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <ul class="nav nav-pills flex-column">
                <li class="nav-item active">
                    <a href="messages.php" class="nav-link">
                        <i class="fas fa-inbox"></i> Inbox
                        <span class="badge bg-primary float-right"><?= $number_of_messages; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="sent.php" class="nav-link">
                        <i class="far fa-envelope"></i> Sent
                        <span class="badge bg-success float-right"><?=$number_of_messages_sent_mail; ?></span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="drafts.php" class="nav-link">
                        <i class="far fa-file-alt"></i> Drafts
                        <span class="badge bg-warning float-right"><?=$number_of_messages_darft; ?></span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->

    <!-- /.card -->
</div>