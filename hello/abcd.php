<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Merchant Transactions</title>
</head>

<body>

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
        echo '<table border="1">';
        echo '<tr><th>#</th><th>Transaction ID</th><th>Status</th><th>Amount</th><th>Date</th><th>User Name</th><th>User Mobile</th></tr>';
        $i = 1;
        foreach ($data['records'] as $record) {
            echo '<tr>';
            echo '<td>' . $i++ . '</td>';
            echo '<td>' . $record['idx'] . '</td>';
            echo '<td>' . $record['state']['name'] . '</td>';
            echo '<td>' . $record['amount'] . '</td>';
            echo '<td>' . $record['created_on'] . '</td>';

            // Extract name and phone number from the 'user' field
            $userData = explode('(', $record['user']['name']);
            $name = $userData[0]; // Extracted name
            $phoneNumber = rtrim($userData[1], ')'); // Extracted phone number

            echo '<td>' . $name . '</td>';
            echo '<td>' . $phoneNumber . '</td>';

            echo '</tr>';
        }

        echo '</table>';

        // Display navigation buttons for pagination
        echo '<br>';
        echo 'Page: ';
        echo  'total: ' .$data['total_records'] . ' ';

        for ($i = 1; $i <= $data['total_pages']; $i++) {
            echo '<a href="?page=' . $i . '">' . $i . '</a> ';
        }

        // Previous Page button
        if ($pageNumber > 1) {
            echo '<a href="?page=' . ($pageNumber - 1) . '"></a> ';
        }

        // Next Page button
        if ($pageNumber < $data['total_pages']) {
            echo '<a href="?page=' . ($pageNumber + 1) . '"></a> ';
        }
    } else {
        echo "Error processing API response. HTTP Status Code: " . $status_code;
    }
    ?>
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
            if (<?php echo $pageNumber; ?> < <?php echo $data['total_pages']; ?>) {
                // Increment the page number and reload the page
                window.location.href = '?page=<?php echo $pageNumber + 1; ?>';
            }
        });

        document.getElementById('prevButton').addEventListener('click', function() {
            // Check if the current page is the first page
            if (<?php echo $pageNumber; ?> > 1) {
                // Decrement the page number and reload the page
                window.location.href = '?page=<?php echo max($pageNumber - 1, 1); ?>';
            } else {
                // Go to the last page
                window.location.href = '?page=<?php echo $data['total_pages']; ?>';
            }
        });
    });
    </script>

    <button id="prevButton">Previous Page</button>
    <button id="nextButton">Next Page</button>

</body>

</html>