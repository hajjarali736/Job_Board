<?php
require_once 'config.php';

header('Content-Type: application/json');

try {
    // Query to get top companies based on number of active jobs
    $query = "SELECT c.company_id, c.name, c.description, c.location, c.website, COUNT(j.job_id) as job_count 
              FROM companies c 
              LEFT JOIN jobs j ON c.company_id = j.company_id AND j.status = 'active' 
              GROUP BY c.company_id 
              ORDER BY job_count DESC 
              LIMIT 6";

    $result = mysqli_query($conn, $query);

    if (!$result) {
        throw new Exception("Error fetching companies: " . mysqli_error($conn));
    }

    $companies = [];
    while ($row = mysqli_fetch_assoc($result)) {
        // Sanitize the data before sending
        $companies[] = [
            'company_id' => (int)$row['company_id'],
            'name' => htmlspecialchars($row['name']),
            'description' => htmlspecialchars($row['description']),
            'location' => htmlspecialchars($row['location']),
            'website' => htmlspecialchars($row['website']),
            'job_count' => (int)$row['job_count']
        ];
    }

    echo json_encode([
        'success' => true,
        'companies' => $companies
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conn);
?> 