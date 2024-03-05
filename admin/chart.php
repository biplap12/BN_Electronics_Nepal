<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="dist/css/adminlte.min.css">
</head>

<body>


    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Pie Chart</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="pieChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->



                    <!-- AREA CHART -->
                    <div class="card card-primary" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">Area Chart</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="areaChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->

                    <div style="display: none;" class="card card-danger">
                        <div class="card-header">
                            <h3 class="card-title">Donut Chart</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <canvas id="donutChart"
                                style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                        </div>
                        <!-- /.card-body -->
                    </div>

                </div>
                <div class="col-md-6">
                    <!-- BAR CHART -->
                    <div class="card card-success" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">Bar Chart</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="barChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- LINE CHART -->
                    <div class="card card-info" style="display: none;">
                        <div class="card-header">
                            <h3 class="card-title">Line Chart</h3>

                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <canvas id="lineChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                            </div>
                        </div>
                        <!-- /.card-body -->


                    </div>
                    <!-- /.col (RIGHT) -->


                    <!-- /.row -->
                </div><!-- /.container-fluid -->
    </section>

    <!-- STACKED BAR CHART -->

    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap 4 -->
    <script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- ChartJS -->
    <script src="plugins/chart.js/Chart.min.js"></script>
    <!-- AdminLTE App -->
    <script src="dist/js/adminlte.min.js"></script>
    <!-- AdminLTE for demo purposes -->
    <script src="dist/js/demo.js"></script>
    <!-- Page specific script -->
    <?php
try {
    $user_orders = $conn->prepare("SELECT * FROM orders WHERE order_status='Completed'");
    $user_orders->execute();
    $user_orders = $user_orders->fetchAll(PDO::FETCH_ASSOC);

    // Count occurrences of each product and calculate total products
    $productCounts = [];
    $dateLabels = [];
    $ordersPerMonth = [];

    foreach ($user_orders as $order) {
        $totalProducts = $order['total_products'];
        $placedOn = $order['placed_on'];

        // Extract individual products from the total_products string
        preg_match_all('/\s*([a-zA-Z\s]+)\s+\((\d+) x (\d+)\)/', $totalProducts, $matches, PREG_SET_ORDER);
        // Process each product in the order
        foreach ($matches as $match) {
            $productName = $match[1];
            $productQuantity = $match[3];

            // Initialize count for the product if not already set
            if (!isset($productCounts[$productName])) {
                $productCounts[$productName] = 0;
            }

            // Increment the count for the product
            $productCounts[$productName] += $productQuantity;
        }

        // Extract month and year from the placed_on date
        $monthYear = date('M Y', strtotime($placedOn));

        // Add month and year to labels if not already present
        if (!in_array($monthYear, $dateLabels)) {
            $dateLabels[] = $monthYear;
        }

        // Count orders per month
        if (!isset($ordersPerMonth[$monthYear])) {
            $ordersPerMonth[$monthYear] = 0;
        }
        $ordersPerMonth[$monthYear]++;
    }

    // Create an array for the labels and values
    $labels = [];
    $values = [];

    foreach ($productCounts as $productName => $productCount) {
        $labels[] = $productName;
        $values[] = $productCount;
    }

    $dlabels = [];
    $dvalues = [];

    foreach ($ordersPerMonth as $monthYear => $orderCount) {
        $dlabels[] = $monthYear;
        $dvalues[] = $orderCount;
    }

    $areaChartData = [
        'labels' => $dlabels,
        'datasets' => [
            [
                'data' => $dvalues,
            ],
        ],
    ];

    // Create a PHP associative array for the total product data
    function getRandomColor() {
        $letters = '0123456789ABCDEF';
        $color = '#';
        for ($i = 0; $i < 6; $i++) {
            $color .= $letters[random_int(0, 15)];
        }
        return $color;
    }

    $backgroundColor = [];
    for ($i = 0; $i < count($values); $i++) {
        $backgroundColor[] = [
            'color' => getRandomColor(),
            'size' => $values[$i] // You can adjust the sizing logic based on your requirements
        ];
    }

    $donutData = [
        'labels' => $labels,
        'datasets' => [
            [
                'data' => $values,
                'backgroundColor' => array_map(function($item) {
                    return $item['color'];
                }, $backgroundColor),
                'sizes' => array_map(function($item) {
                    return $item['size'];
                }, $backgroundColor),
            ],
        ],
    ];

    // Convert the PHP array to JSON
    // echo json_encode($donutData);

    // Print the count of orders per month
    // echo json_encode($areaChartData);
} catch (PDOException $e) {
    // Handle database connection or query errors
    echo "Error: " . $e->getMessage();
}
?>







    <script>
    $(function() {
        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        //--------------
        //- AREA CHART -
        //--------------

        // Get context with jQuery - using jQuery's .get() method.
        var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

        function getRandomColor() {
            var letters = '0123456789ABCDEF';
            var color = '#';
            for (var i = 0; i < 6; i++) {
                color += letters[Math.floor(Math.random() * 16)];
            }
            return color;
        }


        // Sample datasets
        var areaChartData = {
            labels: <?php echo json_encode($dlabels); ?>,
            datasets: [
                <?php foreach($donutData['labels'] as $index => $label): ?> {
                    label: '<?php echo substr(json_encode($label, JSON_UNESCAPED_SLASHES), 1, -1); ?>',
                    backgroundColor: getRandomColor(),
                    borderColor: getRandomColor(),
                    pointRadius: false,
                    pointColor: getRandomColor(),
                    pointStrokeColor: getRandomColor(),
                    pointHighlightFill: getRandomColor(),
                    pointHighlightStroke: getRandomColor(),
                    data: <?php echo json_encode($dvalues); ?> // Sample data  
                },
                <?php endforeach; ?>
            ]
        }



        var areaChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: false
            },
            scales: {
                xAxes: [{
                    gridLines: {
                        display: false,
                    }
                }],
                yAxes: [{
                    gridLines: {
                        display: false,
                    }
                }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        new Chart(areaChartCanvas, {
            type: 'line',
            data: areaChartData,
            options: areaChartOptions
        })

        //-------------
        //- LINE CHART -
        //--------------
        var lineChartCanvas = $('#lineChart').get(0).getContext('2d')
        var lineChartOptions = $.extend(true, {}, areaChartOptions)
        var lineChartData = $.extend(true, {}, areaChartData)
        lineChartData.datasets[0].fill = false;
        lineChartData.datasets[1].fill = false;
        lineChartOptions.datasetFill = false

        var lineChart = new Chart(lineChartCanvas, {
            type: 'line',
            data: lineChartData,
            options: lineChartOptions
        })

        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var donutChartCanvas = $('#donutChart').get(0).getContext('2d')

        var donutData = <?php echo json_encode($donutData); ?>;
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        });

        //-------------
        //- PIE CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.
        var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
        var pieData = donutData;
        var pieOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        new Chart(pieChartCanvas, {
            type: 'pie',
            data: pieData,
            options: pieOptions
        })

        //-------------
        //- BAR CHART -
        //-------------
        var barChartCanvas = $('#barChart').get(0).getContext('2d')
        var barChartData = $.extend(true, {}, areaChartData)
        var temp0 = areaChartData.datasets[0]
        var temp1 = areaChartData.datasets[1]
        barChartData.datasets[0] = temp1
        barChartData.datasets[1] = temp0

        var barChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            datasetFill: false
        }

        new Chart(barChartCanvas, {
            type: 'bar',
            data: barChartData,
            options: barChartOptions
        })

        //---------------------
        //- STACKED BAR CHART -
        //---------------------
        var stackedBarChartCanvas = $('#stackedBarChart').get(0).getContext('2d')
        var stackedBarChartData = $.extend(true, {}, barChartData)

        var stackedBarChartOptions = {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                xAxes: [{
                    stacked: true,
                }],
                yAxes: [{
                    stacked: true
                }]
            }
        }

        new Chart(stackedBarChartCanvas, {
            type: 'bar',
            data: stackedBarChartData,
            options: stackedBarChartOptions
        })
    });
    </script>