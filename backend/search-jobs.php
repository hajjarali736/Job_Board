<?php
require_once 'db_connection.php';
session_start();

header('Content-Type: application/json');

if (!isset($_POST['search'])) {
    echo json_encode(['error' => 'No search term provided']);
    exit;
}

$searchTerm = '%' . $_POST['search'] . '%';

$query = "SELECT j.*, c.company_name 
          FROM jobs j 
          JOIN companies c ON j.company_id = c.company_id 
          WHERE j.title LIKE ? OR j.description LIKE ? OR j.location LIKE ?
          AND j.status = 'active'
          ORDER BY j.posted_date DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("sss", $searchTerm, $searchTerm, $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

$jobs = [];
while ($row = $result->fetch_assoc()) {
    $jobs[] = [
        'job_id' => $row['job_id'],
        'title' => $row['title'],
        'company_name' => $row['company_name'],
        'location' => $row['location'],
        'job_type' => $row['job_type'],
        'posted_date' => date('M d, Y', strtotime($row['posted_date']))
    ];
}

echo json_encode(['jobs' => $jobs]);
$stmt->close();
$conn->close();
?> 