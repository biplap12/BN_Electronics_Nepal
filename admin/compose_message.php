<?php
include 'adminHeader.php';
$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:admin_login.php');
};

if (isset($_POST["submit"])) {
    $email = filter_input(INPUT_POST, 'mail_to', FILTER_SANITIZE_STRING);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $attachment = $_FILES['attachment']['name'];
    $attachment = filter_var($attachment, FILTER_SANITIZE_STRING);

    // Check for empty fields
    if (empty($subject) || empty($message)) {
        echo "<script>alert('Please fill in all required fields.');</script>";
    } else {
        try {
            $select_user = $conn->prepare("SELECT * FROM users WHERE email=?");
            $select_user->execute([$email]);
            $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);

            $id = $fetch_user['id'];

            // Check for a similar message in sent_msg table
            $select_sent_msg = $conn->prepare("SELECT * FROM sent_msg WHERE user_id=? AND admin_reply=?");
            $select_sent_msg->execute([$id, $message]);
            $fetch_sent_msg = $select_sent_msg->fetch(PDO::FETCH_ASSOC);

            if ($fetch_sent_msg) {
                echo "<script>window.location.href='compose_message1.php';</script>";
                exit();
            }

            // Insert message into sent_msg table
            $insert_message = $conn->prepare("INSERT INTO `sent_msg`(`admin_id`, `subject`, `admin_reply`, `admin_attch`, `user_id`) VALUES (?,?,?,?,?)");
            $insert_message->execute([$admin_id, $subject, $message, $attachment, $id]);

            // Handle file upload
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

            if ($insert_message->rowCount() > 0) {
                echo '<script>alert("Message sent successfully!");</script>';
                $delete_draft = $conn->prepare("DELETE FROM draft WHERE user_id=?");
                $delete_draft->execute([$id]);
            } else {
                echo '<script>alert("Message not sent!");</script>';
            }
        } catch (PDOException $e) {
            // Handle any errors that occurred during the database operation
            echo '<script>alert("Database error: ' . $e->getMessage() . '");</script>';
        }
    }
}



if(isset($_POST["draft"])){
    $email = filter_input(INPUT_POST, 'mail_to', FILTER_SANITIZE_STRING);
    $subject = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);
    $attachment = $_FILES['attachment']['name'];
    $attachment=filter_input(INPUT_POST, 'attachment', FILTER_SANITIZE_STRING);
    $select_user = $conn->prepare("SELECT * FROM users WHERE email=?");
    $select_user->execute([$email]);
    $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);
    $id = $fetch_user['id'];
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

$select_draft = $conn->prepare("SELECT users.email,draft.subject,draft.admin_reply FROM draft INNER JOIN users ON draft.user_id=users.id WHERE draft.admin_id=?");
$select_draft->execute([$admin_id]);
$fetch_draft = $select_draft->fetch(PDO::FETCH_ASSOC);
if($select_draft->rowCount() > 0){
    $draft_subject = $fetch_draft['subject'];
    $draft_message = $fetch_draft['admin_reply'];
    $draft_email =$fetch_draft['email'];
}else{  
    $draft_subject = "";
    $draft_message = "";
    $draft_email ="";
}



$selectAdmin = $conn->prepare("SELECT * FROM admins WHERE id=?");
$selectAdmin->execute([$_SESSION['admin_id']]);
$fetchAdmin = $selectAdmin->fetch(PDO::FETCH_ASSOC);
$adminName = $fetchAdmin['name'];

// $select_messages = $conn->prepare("SELECT messages.id, users.name,users.email,users.phone,users.address,messages.date_Time FROM messages INNER JOIN users ON messages.user_id=users.id WHERE messages.id=?");
// $select_messages->execute([$id]);
// $fetch_message= $select_messages->fetch(PDO::FETCH_ASSOC);
// $email = $fetch_message['email'];
// echo $fetch_message['id'];

// $select_user = $conn->prepare("SELECT * FROM users WHERE fetchSuggestions(this.value)=?");
// $select_user->execute([$email]);
// $fetch_user = $select_user->fetch(PDO::FETCH_ASSOC);   

    
?>
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
<!-- Font Awesome -->
<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<!-- Theme style -->
<link rel="stylesheet" href="dist/css/adminlte.min.css">
<!-- summernote -->
<link rel="stylesheet" href="plugins/summernote/summernote-bs4.min.css">
<style>
body {
    scroll-behavior: smooth !important;

}

::-webkit-scrollbar {
    width: 0px;
}

#suggestion-list {
    list-style: none;
    padding: 0;
    margin: 0;
    max-height: 150px;
    overflow-y: hidden;
    /* border: 1px solid #ccc; */
}

#suggestion-list li {
    padding: 8px;
    cursor: pointer;
    border: 1px solid #ccc;
}

#suggestion-list li:hover {
    background-color: #f4f4f4;
}

#recivername {
    position: absolute;
    top: 55px;
    right: 15px;
    left: 50%;
    padding: 5px;
    text-align: center;
    color: #000;
    border-radius: 5px;
}

@media (max-width: 767px) {
    #recivername {
        position: absolute;
        top: 55px;
        right: 15px;
        left: 50%;
        padding: 5px;
        text-align: center;
        color: #000;
        border-radius: 5px;
        font-size: 12px;
    }
}
</style>
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
                        <h3 class="card-title">Compose New Message</h3>
                    </div>
                    <!-- /.card-header -->
                    <form action="" method="POST" enctype="multipart/form-data">
                        <div class="card-body">
                            <div class="form-group">
                                <!-- Input for email -->
                                <input class="form-control" name="mail_to" id="userInput"
                                    oninput="fetchSuggestions(this.value)" value="<?=$draft_email?>" required
                                    placeholder="To:">

                                <ul id="suggestion-list"></ul>
                                <span id="recivername"></span>

                            </div>

                            <div class="form-group">
                                <input class="form-control" name="mail_from"
                                    value="<?=$adminName.'@'.$adminName.'.com';?>" required placeholder="From:">
                            </div>
                            <div class="form-group">
                                <input class="form-control" type="text" name="subject" value="<?=$draft_subject;?>"
                                    placeholder="Subject:">
                                <!-- " -->
                            </div>
                            <div class="form-group">
                                <textarea id="compose-textarea" class="form-control summernote" style=" height: 300px;"
                                    name="message"><?=$draft_message?></textarea>
                                <!--  -->
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
<?php
include 'footer.php';
?>

<!-- Control Sidebar -->
<aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
</aside>
<!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

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


function fetchSuggestions(inputValue) {
    const suggestionList = $('#suggestion-list');
    const receiverNameSpan = $('#recivername');

    // Hide the suggestion list if the input is empty
    if (!inputValue.trim()) {
        suggestionList.empty();
        return;
    }


    $.ajax({
        url: 'update_qty.php', // Update with the correct path to your PHP script
        method: 'POST',
        data: {
            input: inputValue
        },
        dataType: 'json',
        success: function(data) {
            const suggestions = data.suggestions;
            let user = data.user;
            // Check if suggestions is an array before using forEach
            if (Array.isArray(suggestions) && suggestions.length > 0 || user) {
                // Display the suggestion list
                suggestionList.empty();
                suggestions.forEach(suggestion => {
                    const li = $('<li>').text(suggestion
                        .email);
                    $('#recivername').text("User Name: " + suggestion
                        .name
                    );
                    li.on('click', function() {
                        $('#userInput').val(suggestion
                            .email
                        );
                        $('#recivername').text("User Name: " + suggestion
                            .name
                        );
                        suggestionList.empty();
                    });
                    suggestionList.append(li);
                });


                if (user) {
                    receiverNameSpan.text("User Name : " + user.name);
                }
            }
        },
        error: function(error) {}
    });

}
</script>
</body>

</html>