<?php
require_once 'backend/config.php';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Check if companies table exists
$result = $conn->query("SHOW TABLES LIKE 'companies'");
if ($result->num_rows == 0) {
    die("Companies table does not exist");
}

// Get all companies
$result = $conn->query("SELECT * FROM companies");
if (!$result) {
    die("Query failed: " . $conn->error);
}

echo "Number of companies: " . $result->num_rows . "<br><br>";

// Display companies
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['company_id'] . "<br>";
    echo "Name: " . $row['name'] . "<br>";
    echo "Description: " . $row['description'] . "<br>";
    echo "<hr>";
}
?> 