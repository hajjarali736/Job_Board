<?php
require_once 'backend/config.php';

// Check if database exists
$result = $conn->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = 'job_board'");
if ($result->num_rows == 0) {
    die("Database 'job_board' does not exist");
}

// Check if companies table exists
$result = $conn->query("SHOW TABLES LIKE 'companies'");
if ($result->num_rows == 0) {
    die("Table 'companies' does not exist");
}

// Show table structure
$result = $conn->query("DESCRIBE companies");
echo "Companies table structure:\n";
while ($row = $result->fetch_assoc()) {
    echo $row['Field'] . " | " . $row['Type'] . " | " . $row['Null'] . " | " . $row['Key'] . " | " . $row['Default'] . " | " . $row['Extra'] . "\n";
}

// Count records
$result = $conn->query("SELECT COUNT(*) as count FROM companies");
$count = $result->fetch_assoc()['count'];
echo "\nTotal companies: " . $count;
?> 