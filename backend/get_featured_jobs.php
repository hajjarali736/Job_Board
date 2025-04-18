<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Query to get featured jobs with company information
    $query = "SELECT j.*, c.name as company_name 
              FROM jobs j 
              JOIN companies c ON j.company_id = c.company_id 
              WHERE j.status = 'active' 
              ORDER BY j.created_at DESC 
              LIMIT 6";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Error fetching jobs: " . mysqli_error($conn));
    }

    $jobs = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Sanitize the data before sending
        $jobs[] = [
            'job_id' => (int)$row['job_id'],
            'title' => htmlspecialchars($row['title']),
            'description' => htmlspecialchars($row['description']),
            'location' => htmlspecialchars($row['location']),
            'salary_range' => htmlspecialchars($row['salary_range']),
            'job_type' => htmlspecialchars($row['job_type']),
            'company_name' => htmlspecialchars($row['company_name']),
            'created_at' => $row['created_at']
        ];
    }

    echo json_encode([
        'success' => true,
        'jobs' => $jobs
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?> 