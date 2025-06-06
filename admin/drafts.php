<?php

include 'adminHeader.php';



$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};
// if(isset($_GET['delete'])){
//     $delete_id = $_GET['delete'];
//     $delete_message = $conn->prepare("DELETE FROM `messages` WHERE id = ?");
//     $delete_message->execute([$delete_id]);
//     echo '<script>window.location.href = "messages.php";</script>';
//     exit();
//  }

// $select_messages = $conn->prepare("SELECT users.name,users.email,draft.id,draft.admin_reply,draft.date_Time FROM draft INNER JOIN users ON draft.admin_id=users.id WHERE draft.admin_id=?");
// $select_messages->execute([$admin_id]);
// $number_of_messages_darft = $select_messages->rowCount();

  ?>



<!-- Content Wrapper. Contains page content -->

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
    <div class="row">
        <?php include 'message_header.php'; ?>

        <!-- /.col -->
        <div class="col-md-9">
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">Drafts</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <input type="text" class="form-control" placeholder="Search Mail">
                            <div class="input-group-append">
                                <div class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /.card-tools -->
                </div>
                <!-- /.card-header -->
                <div class="card-body p-0">
                    <div class="mailbox-controls">
                        <!-- Check all button -->
                        <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i
                                class="far fa-square"></i>
                        </button>
                        <div class="btn-group">
                            <button type="button" class="btn btn-default btn-sm">
                                <i class="far fa-trash-alt"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm">
                                <i class="fas fa-reply"></i>
                            </button>
                            <button type="button" class="btn btn-default btn-sm">
                                <i class="fas fa-share"></i>
                            </button>
                        </div>
                        <!-- /.btn-group -->
                        <button type="button" class="btn btn-default btn-sm">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                        <div class="float-right">
                            1-50/200
                            <div class="btn-group">
                                <button type="button" class="btn btn-default btn-sm">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <button type="button" class="btn btn-default btn-sm">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <!-- /.btn-group -->
                        </div>

                        <!-- /.float-right -->


                    </div>
                    <div class="table-responsive mailbox-messages">
                        <?php
                        $select_admin = $conn->prepare("SELECT * FROM admins WHERE id=?");
                        $select_admin->execute([$admin_id]);
                        $fetch_admin = $select_admin->fetch(PDO::FETCH_ASSOC);

                        if ($fetch_admin['admin_status'] === 'super') {
                            $select_messages = $conn->prepare("SELECT draft.id,draft.admin_reply,draft.date_Time,users.name,users.email FROM draft INNER JOIN users ON draft.user_id=users.id");
                            $select_messages->execute();
                        }else{
                            $select_messages = $conn->prepare("SELECT draft.id,draft.admin_reply,draft.date_Time,users.name,users.email FROM draft INNER JOIN users ON draft.user_id=users.id WHERE draft.admin_id=?");
                            $select_messages->execute([$admin_id]);
                        }

                        $number_of_messages_draft = $select_messages->rowCount();

                        if ($number_of_messages_draft > 0) {
                            while ($fetch_message = $select_messages->fetch(PDO::FETCH_ASSOC)) {
                               
                        ?>

                        <table class="table table-hover table-striped">
                            <tbody>
                                <tr>
                                    <td style="width: 20px;">
                                        <div class="icheck-primary">
                                            <input type="checkbox" value="" id="check1">
                                            <label for="check1"></label>
                                        </div>
                                    </td>
                                    <td cstyle="width: 20px;" class="mailbox-star"><a href="#"><i
                                                class="fas fa-star text-warning"></i></a></td>
                                    <td style="width:150px;" class="mailbox-name"><a
                                            href="drafts_mail.php?drafts=<?=$fetch_message['id'];?>"><?= $fetch_message['name']; ?></a>
                                    </td>
                                    <?php
                                        if (!function_exists('limitWords')) {
                                            function limitWords($text, $limit) {
                                                $words = explode(' ', $text);
                                                $limitedWords = implode(' ', array_slice($words, 0, $limit));

                                                if (count($words) > $limit) {
                                                    $limitedWords .= '...';
                                                     }

                                                return $limitedWords;
                                            }
                                        }
                                        
                                        if (!function_exists('formatTimeAgo')) {
                                            function formatTimeAgo($dateTimeString) {
                                                $timeZone = new DateTimeZone('Asia/Kathmandu');
                                                $dateTime = new DateTime($dateTimeString, $timeZone);
                                                $now = new DateTime(' ', $timeZone);
                                        
                                                $interval = $now->diff($dateTime);
                                        
                                                $years = $interval->y;
                                                $months = $interval->m;
                                                $days = $interval->days;
                                                $hours = $interval->h;
                                                $minutes = $interval->i;
                                        
                                                if ($years > 0) {
                                                    return $years === 1 ? '1 year ago' : $years . ' years ago';
                                                } elseif ($months > 0) {
                                                    return $months === 1 ? '1 month ago' : $months . ' months ago';
                                                } elseif ($days > 0) {
                                                    return $days === 1 ? 'yesterday' : $days . ' days ago';
                                                } elseif ($hours > 0) {
                                                    return $hours === 1 ? '1 hour ago' : $hours . ' hours ago';
                                                } elseif ($minutes > 0) {
                                                    return $minutes === 1 ? '1 min ago' : $minutes . ' mins ago';
                                                } else {
                                                    return 'just now';
                                                }
                                            }
                                        }
                                        ?>


                                    <td style="width:500px; " class="mailbox-subject "><a style=" color: black; "
                                            href="drafts_mail.php?drafts=<?=$fetch_message['id'];?>">
                                            <b><?= $fetch_message['email']; ?></b> -
                                            <?= limitWords($fetch_message['admin_reply'], 8); ?></a>
                                    </td>

                                    <td style="width: 150px;" class="mailbox-date">
                                        <?= formatTimeAgo($fetch_message['date_Time']); ?>






                                        <?php
                                           }
                                         }else{
                                            echo '<p style="text-align:center; color:red; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);font-weight:500;">You have no Draft messages.</p>';
                                        }
                                    ?>
                                    </td>
                                <tr>
                            </tbody>
                        </table>
                        <!-- /.table -->
                    </div>
                    <!-- /.mail-box-messages -->
                </div>
                <!-- /.card-body -->
            </div>
        </div>
        <!-- /.card -->
    </div>
    <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<script>
$(function() {
    //Enable check and uncheck all functionality
    $('.checkbox-toggle').click(function() {
        var clicks = $(this).data('clicks')
        if (clicks) {
            //Uncheck all checkboxes
            $('.mailbox-messages input[type=\'checkbox\']').prop('checked', false)
            $('.checkbox-toggle .far.fa-check-square').removeClass('fa-check-square').addClass(
                'fa-square')
        } else {
            //Check all checkboxes
            $('.mailbox-messages input[type=\'checkbox\']').prop('checked', true)
            $('.checkbox-toggle .far.fa-square').removeClass('fa-square').addClass(
                'fa-check-square')
        }
        $(this).data('clicks', !clicks)
    })

    //Handle starring for font awesome
    $('.mailbox-star').click(function(e) {
        e.preventDefault()
        //detect type
        var $this = $(this).find('a > i')
        var fa = $this.hasClass('fa')

        //Switch states
        if (fa) {
            $this.toggleClass('fa-star')
            $this.toggleClass('fa-star-o')
        }
    })
});
</script>

<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
<script src="plugins/jszip/jszip.min.js"></script>
<script src="plugins/pdfmake/pdfmake.min.js"></script>
<script src="plugins/pdfmake/vfs_fonts.js"></script>
<script src="plugins/datatables-buttons/js/buttons.html5.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.print.min.js"></script>
<script src="plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>