<?php
include 'adminHeader.php';
$id = filter_input(INPUT_GET, 'drafts', FILTER_SANITIZE_NUMBER_INT);


$select_messages = $conn->prepare("SELECT draft.user_id, draft.id, users.name,users.email,users.phone,users.address,draft.admin_reply,draft.subject,draft.date_Time FROM draft INNER JOIN users ON draft.user_id=users.id WHERE draft.id=?");
   $select_messages->execute([$id]);
   $fetch_message= $select_messages->fetch(PDO::FETCH_ASSOC);
  ?>

<!-- Navbar -->

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Drafts</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">drafts</li>
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
                        <h3 class="card-title">Drafts Mail</h3>

                        <div class="card-tools">
                            <a href="#" class="btn btn-tool" title="Previous"><i class="fas fa-chevron-left"></i></a>
                            <a href="#" class="btn btn-tool" title="Next"><i class="fas fa-chevron-right"></i></a>
                        </div>
                    </div>
                    <!-- /.card-header -->

                    <div class="card-body p-0 print">
                        <div class="mailbox-read-info">
                            <h5>Subject:<?=$fetch_message['subject']?></h5>
                            <h6>To: <?= $fetch_message['email']; ?>
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
                                <?= $fetch_message['admin_reply']; ?>
                                .</p>

                            <p>Thanks,<br><?= $fetch_message['name']; ?></p>
                        </div>
                        <!-- /.mailbox-read-message -->

                    </div>
                    <!-- /.card-footer -->
                    <div class="card-footer">
                        <div class="float-right">
                            <a href="reply_message.php?reply=<?= $fetch_message['user_id']; ?>"
                                class="btn btn-default"><i class="fas fa-reply"></i>
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