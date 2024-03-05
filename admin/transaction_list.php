<?php
include 'adminHeader.php';
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Merchant Transactions</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback" />
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css" />
    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css" />
    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css" />
    <link rel="stylesheet" href="plugins/datatables-buttons/css/buttons.bootstrap4.min.css" />
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

</head>
<style>
.pagination {
    display: flex;
    justify-content: space-between;
}
</style>

<body>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Merchant Transaction</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
                        <li class="breadcrumb-item active">merchant_transaction</li>
                    </ol>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <!-- /.card -->

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Merchant Transaction</h3>
                        </div>
                        <div class="card-body">

                            <?php
    // Define $current_page or set it to a default value
    $current_page = 1;

    // Set the initial page number
    $pageNumber = $current_page;

    if (isset($_GET['page']) && is_numeric($_GET['page'])) {
        $pageNumber = intval($_GET['page']);
    }

    $url = "https://khalti.com/api/v2/merchant-transaction/?page=" . $pageNumber;

    # Make the call using API.
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    $headers = ['Authorization: Key test_secret_key_67c35d31456545dfa734f7f1ea215229'];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // Response
    $response = curl_exec($ch);
    $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    // Parse JSON response
    $data = json_decode($response, true);

    // Check if decoding was successful and if 'records' key exists
    if ($data !== null && isset($data['records']) && is_array($data['records'])) {
        // Display the data in an HTML table
        $table="";
        $table .= "<div class='table-responsive'>";
        $table.= "<table id='example1' class='table table-bordered table-striped'>";
        $table.= "<thead>";
        $table.= "<tr>";
        $table.= "<th>#</th>";
        $table.= "<th>User Name</th>";  
        $table.= "<th>User Mobile</th>";
        $table.= "<th>Transaction ID</th>";
        $table.= "<th>Status</th>";
        $table.= "<th>Date</th>";
        $table.= "<th>Amount</th>";
        $table.= "</tr>";
        $table.= "</thead>";
        $i = 1;
        foreach ($data['records'] as $record) {
            $table.="<tbody>";
            $table.='<tr>';
            $table.="<td>" . $i++ . "</td>";
            $userData = explode('(', $record['user']['name']);
            $name = $userData[0]; // Extracted name
            $phoneNumber = rtrim($userData[1], ')'); // Extracted phone number
            $table.="<td>".$name."</td>";
            $table.="<td>".$phoneNumber."</td>";
            $table.="<td>".$record['idx']."</td>";
            if($record['state']['name'] == 'Completed'){
                $table.="<td><span class='badge badge-success'>".$record['state']['name']."</span></td>";
            }elseif($record['state']['name'] == 'Confirmed'){
                $table.="<td><span class='badge badge-warning'>".$record['state']['name']."</span></td>";
            }else{
                $table.="<td><span class='badge badge-danger'>".$record['state']['name']."</span></td>";
            }
            $table.="<td>".$record['created_on']."</td>";  
            $table .= '<td>' . number_format($record['amount'] / 100, 2) . '</td>';
            $table.='</tr>';
            $table.="</tbody>";
                }
            $table .= "<tr>";
            $table .= "<td colspan='6' style='text-align:center; font-size:larger; font-weight:bolder' >Total</td>";
            $table .= "<td style='font-size:larger; font-weight:bolder'>" . number_format($data['total_amount'] / 100) . "</td>";
            $table .= "</tr>";
        $table .= "</table>";
        echo $table;
        
        echo '<br>';
        $_SESSION['totalTrans'] = $data['total_records'];
        echo '<div class="pagination">';
            echo '<div> Page ' . $pageNumber . ' of ' . $data['total_pages'] . '</div> ';
            echo '<div>Records: ' . $data['total_records'] . '</div>';
            echo '<div>Total Amount: ' . number_format($data['total_amount'] / 100) . '</div>';
            echo '<div>';
            echo '<nav aria-label="...">
                <ul class="pagination">
                    <li class="page-item">';
            echo '<button class="page-link" id="prevButton">Previous</button>';
            echo '</li>';
            
            for ($i = 1; $i <= $data['total_pages']; $i++) {
                $activeClass = ($i == $pageNumber) ? 'active' : '';
                echo '<li class="page-item ' . $activeClass . '"><a class="page-link" href="?page=' . $i . '">' . $i . '</a></li>';
            }
        
        
            echo '<li class="page-item">';
            echo '<button class="page-link" id="nextButton">Next</button>';
            echo '</li>';
            echo '</ul>';
            echo '</nav>';
            echo '</div>';
        } else {
            echo '<div>No records found.</div>';
        }
        echo '</div>';
        
        

    ?>


                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.col -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->

    <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <!-- DataTables  & Plugins -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if the current page is the first page
        var pageNumber = <?php echo $data['total_pages']; ?>;

        if (<?php echo $pageNumber; ?> === 1) {
            document.getElementById('prevButton').style.display = 'none';
        }

        // Check if the current page is the last page
        if (<?php echo $pageNumber; ?> === <?php echo $data['total_pages']; ?>) {
            document.getElementById('nextButton').style.display = 'none';
        }

        document.getElementById('nextButton').addEventListener('click', function() {
            // Check if the current page is not the last page
            if (<?php echo $pageNumber; ?> <
                <?php echo $data['total_pages']; ?>) {
                // Increment the page number and reload the page
                window.location.href = '?page=<?php echo $pageNumber + 1; ?>';
            }
        });

        document.getElementById('prevButton').addEventListener('click', function() {
            // Check if the current page is the first page
            if (<?php echo $pageNumber; ?> > 1) {
                // Decrement the page number and reload the page
                window.location.href =
                    '?page=<?php echo max($pageNumber - 1, 1); ?>';
            } else {
                // Go to the last page
                window.location.href =
                    '?page=<?php echo $data['total_pages']; ?>';
            }
        });
    });
    </script>



</body>

</html>