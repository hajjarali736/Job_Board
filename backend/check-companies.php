<?php
require_once 'config.php';

// Direct query to fetch companies
$sql = "SELECT * FROM companies";
$result = $conn->query($sql);

if ($result) {
    echo "<h2>Companies in Database:</h2>";
    echo "<pre>";
    while ($row = $result->fetch_assoc()) {
        print_r($row);
        echo "\n";
    }
    echo "</pre>";
} else {
    echo "Error fetching companies: " . $conn->error;
}
?> 