<?php
include 'adminHeader.php';
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};
$id = filter_input(INPUT_GET, 'reply', FILTER_SANITIZE_NUMBER_INT);
if(empty($id)){
    header('location:messages.php');
}

    if(isset($_POST["submit"])){
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $attachment = $_FILES['attachment']['name'];
    $attachment = filter_var($attachment, FILTER_SANITIZE_STRING);

    // $insert_message = $conn->prepare("UPDATE `sent_msg` SET`admin_id`=?,`subject`=?,`admin_reply`=?,`admin_attch`=? WHERE id=?");
    $insert_message = $conn->prepare("INSERT INTO `sent_msg`(`admin_id`, `subject`, `admin_reply`, `admin_attch`, `user_id`) VALUES (?,?,?,?,?)");
    $insert_message->execute([$admin_id,$subject, $message, $attachment,$id]);
    if (empty($subject) || empty($message)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }else{  
        $target_dir = "../message_picture/";
        $target_file = $target_dir . basename($_FILES['attachment']['name']);
        
        if (isset($_FILES['attachment']) && is_uploaded_file($_FILES['attachment']['tmp_name']) && $_FILES['attachment']['error'] == UPLOAD_ERR_OK) {
            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $target_file)) {
                // File uploaded successfully
            } else {
                echo '<script>alert("Sorry, there was an error moving the uploaded file.");</script>';
            }
        } elseif (empty($attachment)) {
            // No file uploaded, and it's not required
        } else {
            echo '<script>alert("Sorry, there was an error uploading your file.");</script>';
        }
        
    if($insert_message->rowCount() > 0){
       echo '<script>alert("Message sent successfully!");</script>';
       $delete_draft = $conn->prepare("DELETE FROM draft WHERE user_id=?");
         $delete_draft->execute([$id]);
    }else{
       echo '<script>alert("Message not sent!");</script>';
    }
}}

if(isset($_POST["draft"])){
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $attachment = $_FILES['attachment']['name'];
    $attachment=filter_input(INPUT_POST, 'attachment', FILTER_SANITIZE_STRING);
    $select_draft = $conn->prepare("SELECT * FROM draft WHERE user_id=?");
    $select_draft->execute([$id]);
    $fetch_draft = $select_draft->fetch(PDO::FETCH_ASSOC);
    if($select_draft->rowCount() < 1){
    $insert_message = $conn->prepare("INSERT INTO `draft`( `user_id`, `subject`, `admin_reply`, `admin_id`) VALUES (?,?,?,?)");
    $insert_message->execute([$id,$subject, $message, $admin_id]);
    if (empty($subject) || empty($message)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    }else{
        if($insert_message->rowCount() > 0){
            echo '<script>alert("Draft saved successfully!");</script>';
        }else{
            echo '<script>alert("Draft not saved!");</script>';
        }}
    }else{
        $update_draft = $conn->prepare("UPDATE `draft` SET `subject`=?,`admin_reply`=?,`admin_id`=? WHERE user_id=?");
        $update_draft->execute([$subject, $message, $admin_id,$id]);
        if (empty($subject) || empty($message)) {
            echo "<script>alert('Please fill in all required fields.');</script>";
        }else{
            if($update_draft->rowCount() > 0){
                echo '<script>alert("Draft updated successfully!");</script>';
            }else{
                echo '<script>alert("Draft not updated!");</script>';
            }
        }
    }
}
if (isset($_POST["destroy"])) {
    // Clear all input data
    $_POST = array();
}
$select_draft = $conn->prepare("SELECT * FROM draft WHERE user_id=?");
$select_draft->execute([$id]);
$fetch_draft = $select_draft->fetch(PDO::FETCH_ASSOC);
if($select_draft->rowCount() > 0){
    $draft_subject = $fetch_draft['subject'];
    $draft_message = $fetch_draft['admin_reply'];
}else{  
    $draft_subject = "";
    $draft_message = "";
}
$selectAdmin = $conn->prepare("SELECT * FROM admins WHERE id=?");
$selectAdmin->execute([$_SESSION['admin_id']]);
$fetchAdmin = $selectAdmin->fetch(PDO::FETCH_ASSOC);
$adminName = $fetchAdmin['name'];

$select_messages = $conn->prepare("SELECT messages.id, users.name,users.email,users.phone,users.address,messages.date_Time FROM messages INNER JOIN users ON messages.user_id=users.id WHERE messages.id=?");
$select_messages->execute([$id]);
$fetch_message= $select_messages->fetch(PDO::FETCH_ASSOC);
$email = $fetch_message['email'];
// echo $fetch_message['id'];
    
    
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<!-- summernote -->
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Compose</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">compose</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- /.col -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <?php include 'message_header.php'; ?>

            <div class="col-md-9">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">Reply Message</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="form-group">
                                <input class="form-control" readonly name="mail_to" value="<?=$email;?>" required
                                    placeholder="To:">

                            </div>
                            <div class="form-group">
                                <input class="form-control" name="mail_from"
                                    value="<?=$adminName.'@'.$adminName.'.com';?>" required placeholder="From:">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="subject" value="<?=$draft_subject;?>"
                                    placeholder="Subject:">
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" class="form-control summernote" style=" height: 300px;"
                                    name="message"><?=$draft_message?></textarea>
                            </div>

                            <div class="form-group">
                                <div class="btn btn-default btn-file">
                                    <i class="fas fa-paperclip"></i>
                                    <input type="file" name="attachment" onchange="updateFileName(this)">
                                    <p id="file-name"></p>
                                </div>
                                <p class="help-block"></p>

                            </div>


                        </div>
                        <!-- /.card-body -->
                        <div class="card-footer">
                            <div class="float-left">
                                <button type="submit" iname="destroy" class="btn btn-default"><i
                                        class="fas fa-times"></i>
                                    Discard</button>
                            </div>
                            <div class="float-right">
                                <button type="submit" name="draft" class="btn btn-default"><i
                                        class="fas fa-pencil-alt"></i>
                                    Draft</button>
                                <button type="submit" name="submit" class="btn btn-primary"><i
                                        class="far fa-envelope"></i>
                                    Send</button>
                            </div>

                        </div>
                    </form>
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
<?php include 'footer.php'; ?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<!-- <script src="dist/js/adminlte.min.js"></script> -->
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- AdminLTE for demo purposes -->
<!-- <script src="dist/js/demo.js"></script> -->
<!-- Page specific script -->
<script>
//Add text editor
// Initialize Summernote
$('#compose-textarea').summernote({
    placeholder: 'Write your messages...',
    tabsize: 2,
    height: 120,
    toolbar: [
        ['style', ['style']],
        ['font', ['bold', 'underline', 'clear']],
        ['color', ['color']],
        ['para', ['ul', 'ol', 'paragraph']],
        ['table', ['table']],
        ['insert', ['link', 'picture', 'video']],
        ['view', ['fullscreen', 'codeview', 'help']]
    ]
});

// Destroy Summernote when needed
// $('#compose-textarea').summernote('destroy');
$('.summernote').summernote({
    airMode: true
});


function updateFileName(input) {
    var fileName = input.files[0].name;
    document.getElementById('file-name').innerHTML = fileName;
}
</script>
</body>

</html>