<?php
// Connect to MySQL server
$conn = mysqli_connect('localhost', 'root', '');

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$sql = "CREATE DATABASE IF NOT EXISTS job_board";
if (mysqli_query($conn, $sql)) {
    echo "Database created successfully or already exists<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Select the database
mysqli_select_db($conn, "job_board");

// Create companies table
$sql = "CREATE TABLE IF NOT EXISTS companies (
    company_id INT AUTO_INCREMENT PRIMARY KEY,
    company_name VARCHAR(100) NOT NULL,
    industry VARCHAR(50),
    description TEXT,
    logo VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

if (mysqli_query($conn, $sql)) {
    echo "Companies table created successfully or already exists<br>";
} else {
    echo "Error creating companies table: " . mysqli_error($conn) . "<br>";
}

// Insert sample companies if table is empty
$check = mysqli_query($conn, "SELECT COUNT(*) as count FROM companies");
$row = mysqli_fetch_assoc($check);

if ($row['count'] == 0) {
    $sql = "INSERT INTO companies (company_name, industry, description) VALUES 
            ('Tech Solutions Inc', 'Technology', 'Leading technology company providing innovative solutions'),
            ('Finance Corp', 'Finance', 'Global financial services company'),
            ('HealthCare Plus', 'Healthcare', 'Dedicated to improving healthcare services'),
            ('EduTech Systems', 'Education', 'Revolutionizing education through technology'),
            ('Retail Masters', 'Retail', 'Premier retail company with nationwide presence')";
    
    if (mysqli_query($conn, $sql)) {
        echo "Sample companies inserted successfully<br>";
    } else {
        echo "Error inserting sample companies: " . mysqli_error($conn) . "<br>";
    }
}

mysqli_close($conn);
?> 