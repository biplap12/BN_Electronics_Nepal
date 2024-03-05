<?php
// Fetch user profile
    $user_profile = $conn->prepare("SELECT * FROM `admins` WHERE id = ?");
    $user_profile->execute([$admin_id]);
    $fetch_profile = $user_profile->fetch(PDO::FETCH_ASSOC);

    if ($fetch_profile && $fetch_profile['admin_status'] == 'deactive') {
        // User account is blocked
        ?>
<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

<!-- Include SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<!-- Include jQuery Modal -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

<!-- Script to open the modal -->
<script>
$(document).ready(function() {
    $('.content-wrapper').hide();
    // Redirect to another page using SweetAlert2
    Swal.fire({
        icon: 'error',
        title: 'Account Blocked',
        text: 'Your account is blocked by Super Admin. Please contact support.',
        confirmButtonText: 'OK',
        allowOutsideClick: false,
        customClass: {
            popup: 'custom-swal-popup', // Apply a custom class to the SweetAlert2 popup
            title: 'custom-swal-title', // Apply a custom class to the title
            content: 'custom-swal-content', // Apply a custom class to the content
            icon: 'custom-swal-icon',
            confirmButton: 'custom-swal-button', // Apply a custom class to the confirm button
        },
    }).then(() => {
        // Redirect to another page
        window.location.href = '../components/admin_logout.php';
    });
});
</script>
<?php
    }
    ?>
<!DOCTYPE html>
<html lang="en">

<head>

</head>
<style>
.custom-swal-popup {
    width: 400px;
    /* Set the width as needed */
}

.custom-swal-title {
    font-size: 24px;
    /* Set the font size for the title */
}

.custom-swal-content {
    font-size: 20px;
    /* Set the font size for the content */
}

.custom-swal-icon {
    font-size: 40px;
    /* Set the font size for the icon */
    margin-bottom: 20px;
    /* Adjust margin as needed */
}

.custom-swal-button {
    /* Set the font size for the confirm button */
    padding: 20px 40px;
    /* Adjust padding as needed */
}
</style>

<body>

</body>

</html>