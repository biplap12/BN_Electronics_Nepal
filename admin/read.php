<?php
include 'adminHeader.php';
$id = filter_input(INPUT_GET, 'inbox', FILTER_SANITIZE_NUMBER_INT);


$select_messages = $conn->prepare("SELECT messages.id, users.name,users.email,users.phone,users.address,messages.msg_pic,messages.user_message,messages.date_Time FROM messages INNER JOIN users ON messages.user_id=users.id WHERE messages.id=?");
   $select_messages->execute([$id]);
   $fetch_message= $select_messages->fetch(PDO::FETCH_ASSOC);
?>

<!-- Navbar -->

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Compose</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Compose</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>

<!-- Main content -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php include 'message_header.php'; ?>
            <!-- /.col -->
            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Read Mail</h3>

                        <div class="card-tools">
                            <a href="#" class="btn btn-tool" title="Previous"><i class="fas fa-chevron-left"></i></a>
                            <a href="#" class="btn btn-tool" title="Next"><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body p-0 print">
                        <div class="mailbox-read-info">
                            <h5>Subject:</h5>
                            <h6>From: <?= $fetch_message['email']; ?>
                                <span class="mailbox-read-time float-right">
                                    <?= $fetch_message['date_Time']; ?>
                                </span>
                            </h6>
                        </div>
                        <!-- /.mailbox-read-info -->
                        <div class="mailbox-controls with-border text-center">
                            <div class="btn-group">
                                <a href="messages.php?delete=<?= $fetch_message['id']; ?>"
                                    onclick="return confirm('Delete this message ?');" class="btn btn-default btn-sm"
                                    title="Delete" data-container="body"><i class="far fa-trash-alt"></i></a>

                                <button type="button" class="btn btn-default btn-sm" data-container="body"
                                    title="Reply">
                                    <i class="fas fa-reply"></i>
                                </button>
                                <button type="button" class="btn btn-default btn-sm" data-container="body"
                                    title="Forward">
                                    <i class="fas fa-share"></i>
                                </button>
                            </div>
                            <!-- /.btn-group -->
                            <button type="button" class="btn btn-default btn-sm printBtn" title="Print">
                                <i class="fas fa-print"></i>
                            </button>
                        </div>

                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                            <p>Hello BN Electronics Nepal,</p>


                            <p>
                                <?= $fetch_message['user_message']; ?>
                                .</p>

                            <p>Thanks,<br><?= $fetch_message['name']; ?></p>
                        </div>
                        <!-- /.mailbox-read-message -->

                    </div>



                    <div class="card-footer bg-white">
                        <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                            <?php
        $fileExtension = pathinfo($fetch_message['msg_pic'], PATHINFO_EXTENSION);
        $fileExtension = strtolower($fileExtension);
        $filePath = '../message_picture/' . $fetch_message['msg_pic'];
        if (in_array($fileExtension, ['jpg', 'png', 'jpeg', 'gif'])) {
            // Check if the image file exists
        if (file_exists($filePath)) {
        $fileSize = round(filesize($filePath) / 1024); // Convert bytes to KB
        echo '
        <li>
            <span class="mailbox-attachment-icon has-img"><i class="fa-solid fa-camera"></i></span>
            <div class="mailbox-attachment-info">
                <a href="' . $filePath . '" target="_blank" class="mailbox-attachment-name"><i class="fas fa-camera"></i>
                    ' . $fetch_message['msg_pic'] . '</a>
                <span class="mailbox-attachment-size clearfix mt-1">
                    <span>' . $fileSize . ' KB</span>
                    <a href="' . $filePath . '" download class="btn btn-default btn-sm float-right"><i
                            class="fas fa-cloud-download-alt"></i></a>
                </span>
            </div>
        </li>';
    } else {
        echo '<li>Image file not found</li>';
    }
} elseif ($fileExtension == 'pdf') {
    // Check if the PDF file exists
    if (file_exists($filePath)) {
        $fileSize = round(filesize($filePath) / 1024); // Convert bytes to KB
        echo '
        <li>
            <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

            <div class="mailbox-attachment-info">
                <a href="' . $filePath . '" target="_blank" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i>
                    ' . $fetch_message['msg_pic'] . '</a>
                <span class="mailbox-attachment-size clearfix mt-1">
                    <span>' . $fileSize . ' KB</span>
                    <a href="' . $filePath . '" download class="btn btn-default btn-sm float-right"><i
                            class="fas fa-cloud-download-alt"></i></a>
                </span>
            </div>
        </li>';
    } else {
        echo '<li>PDF file not found</li>';
    }
}
?>



                            <!-- <li>
                                <span class="mailbox-attachment-icon"><i class="far fa-file-word"></i></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-paperclip"></i> App
                                        Description.docx</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                                        <span>1,245 KB</span>
                                        <a href="#" class="btn btn-default btn-sm float-right"><i
                                                class="fas fa-cloud-download-alt"></i></a>
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo1.png"
                                        alt="Attachment"></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i>
                                        photo1.png</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                                        <span>2.67 MB</span>
                                        <a href="#" class="btn btn-default btn-sm float-right"><i
                                                class="fas fa-cloud-download-alt"></i></a>
                                    </span>
                                </div>
                            </li>
                            <li>
                                <span class="mailbox-attachment-icon has-img"><img src="../../dist/img/photo2.png"
                                        alt="Attachment"></span>

                                <div class="mailbox-attachment-info">
                                    <a href="#" class="mailbox-attachment-name"><i class="fas fa-camera"></i>
                                        photo2.png</a>
                                    <span class="mailbox-attachment-size clearfix mt-1">
                                        <span>1.9 MB</span>
                                        <a href="#" class="btn btn-default btn-sm float-right"><i
                                                class="fas fa-cloud-download-alt"></i></a>
                                    </span>
                                </div>
                            </li> -->
                        </ul>
                    </div>

                    <!-- /.card-footer -->
                    <div class="card-footer">
                        <div class="float-right">
                            <a href="reply_message.php?reply=<?= $fetch_message['id']; ?>" class="btn btn-default"><i
                                    class="fas fa-reply"></i>
                                Reply</a>
                            <a href="#" class="btn btn-default"><i class="fas fa-share"></i>
                                Forward</a>
                        </div>
                        <a href="messages.php?delete=<?= $fetch_message['id']; ?>"
                            onclick="return confirm('Delete this message ?');" class="btn btn-default"><i
                                class="far fa-trash-alt"></i>Delete</a>


                        <button type="button" class="btn btn-default printBtn"><i class="fas fa-print"></i>
                            Print</button>
                    </div>

                    <!-- /.card-footer -->
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
</section>

<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/printThis/1.15.0/printThis.min.js"></script>
<script>
$(document).ready(function() {
    $('.printBtn').click(function() {
        $('.mailbox-controls').hide();
        $('.print').printThis({
            afterPrint: function() {
                $('.mailbox-controls').show();
            }
        });
    });
    var sourcePageName = 'Read Mail';
    $('title').text(sourcePageName);
});
</script>