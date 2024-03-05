<?php include 'adminHeader.php'; ?>

<!-- Navbar -->


<!-- Main content -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Profile</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                    <li class="breadcrumb-item active">Profile</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3 mx-auto">
                <?php
                             $select_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
                                $select_profile->execute([$admin_id]);
                                $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);

                                $profilePicture = (!empty($fetch_profile['photo']))
                                ? "../admin_picture/{$fetch_profile['photo']}"
                                : "../admin_picture/default_profile_picture.png"; 
                        
                            ?>


                <!-- Profile Image -->
                <div class="card card-primary card-outline">
                    <div class="card-body box-profile">
                        <div class="text-center">
                            <img id="myImg" class="profile-user-img img-fluid img-circle" src="<?= $profilePicture ?>"
                                alt="<?= $fetch_profile['name']; ?>">
                            <div id="myModal" class="modal">
                                <span class="close"><i class="fa-solid fa-circle-xmark"
                                        style="color: #ff0000;"></i></span>
                                <img class="modal-content" id="img01">
                                <div id="caption"></div>
                            </div>

                        </div>

                        <h3 class="profile-username text-center text-capitalize"><?= $fetch_profile['name']; ?></h3>

                        <p class="text-muted text-center">Id: <?= $fetch_profile['id']; ?></p>


                        <!--  <ul class="list-group list-group-unbordered mb-3">
                            <li class="list-group-item">
                                <b>Followers</b> <a class="float-right">1,322</a>
                            </li>
                            <li class="list-group-item">
                                <b>Following</b> <a class="float-right">543</a>
                            </li>
                            <li class="list-group-item">
                                <b>Friends</b> <a class="float-right">13,287</a>
                            </li>
                        </ul> -->

                        <!-- <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a> -->
                    </div>
                    <!-- /.card-body -->
                </div>
                <!-- /.card -->

                <!-- About Me Box -->
                <!-- <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">About Me</h3>
                    </div>
                    /.card-header -->
                <!-- <div class="card-body">
                        <strong><i class="fas fa-book mr-1"></i> Education</strong>

                        <p class="text-muted">
                            B.S. in Computer Science from the University of Tennessee at Knoxville
                        </p>

                        <hr>

                        <strong><i class="fas fa-map-marker-alt mr-1"></i> Location</strong>

                        <p class="text-muted">Malibu, California</p>

                        <hr>

                        <strong><i class="fas fa-pencil-alt mr-1"></i> Skills</strong>

                        <p class="text-muted">
                            <span class="tag tag-danger">UI Design</span>
                            <span class="tag tag-success">Coding</span>
                            <span class="tag tag-info">Javascript</span>
                            <span class="tag tag-warning">PHP</span>
                            <span class="tag tag-primary">Node.js</span>
                        </p>

                        <hr>

                        <strong><i class="far fa-file-alt mr-1"></i> Notes</strong>

                        <p class="text-muted">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam
                            fermentum enim neque.</p>
                    </div> -->
                <!-- /.card-body -->
                <!-- </div>  -->
                <!-- /.card -->
            </div>

            <!-- /.row -->
        </div><!-- /.container-fluid -->
</section>
<!-- /.content -->

<!-- /.content-wrapper -->


<!-- Control Sidebar -->

<!-- ./wrapper -->

<!-- jQuery -->
<script src="../js/profile_picture.js"></script>