<?php
require_once 'db_connection.php';

header('Content-Type: application/json');

// Get parameters
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$industry = isset($_GET['industry']) ? trim($_GET['industry']) : '';

// Items per page
$itemsPerPage = 9;

// Calculate offset
$offset = ($page - 1) * $itemsPerPage;

// Build the query
$query = "SELECT SQL_CALC_FOUND_ROWS c.*, 
          COUNT(j.job_id) as job_count 
          FROM companies c 
          LEFT JOIN jobs j ON c.company_id = j.company_id 
          WHERE 1=1";

$params = [];
$types = "";

if (!empty($search)) {
    $query .= " AND (c.company_name LIKE ? OR c.description LIKE ?)";
    $searchTerm = "%$search%";
    $params[] = $searchTerm;
    $params[] = $searchTerm;
    $types .= "ss";
}

if (!empty($industry)) {
    $query .= " AND c.industry = ?";
    $params[] = $industry;
    $types .= "s";
}

$query .= " GROUP BY c.company_id 
            ORDER BY job_count DESC, c.company_name ASC 
            LIMIT ? OFFSET ?";

$params[] = $itemsPerPage;
$params[] = $offset;
$types .= "ii";

// Prepare and execute the query
$stmt = $conn->prepare($query);
if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$result = $stmt->get_result();

// Get companies
$companies = [];
while ($row = $result->fetch_assoc()) {
    $companies[] = [
        'company_id' => $row['company_id'],
        'company_name' => $row['company_name'],
        'industry' => $row['industry'],
        'description' => $row['description'],
        'logo' => $row['logo'],
        'job_count' => $row['job_count']
    ];
}

// Get total number of companies
$totalQuery = "SELECT FOUND_ROWS() as total";
$totalResult = $conn->query($totalQuery);
$total = $totalResult->fetch_assoc()['total'];

// Calculate total pages
$totalPages = ceil($total / $itemsPerPage);

// Return the response
echo json_encode([
    'companies' => $companies,
    'totalPages' => $totalPages,
    'currentPage' => $page,
    'total' => $total
]);

$stmt->close();
$conn->close();
?> 