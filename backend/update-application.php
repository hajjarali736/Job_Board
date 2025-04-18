<?php
require_once 'db_connection.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

if (!isset($_POST['application_id']) || !isset($_POST['status'])) {
    echo json_encode(['error' => 'Missing required parameters']);
    exit;
}

$applicationId = $_POST['application_id'];
$newStatus = $_POST['status'];
$userId = $_SESSION['user_id'];

// Verify that the user has permission to update this application
$verifyQuery = "SELECT a.*, j.company_id 
                FROM applications a 
                JOIN jobs j ON a.job_id = j.job_id 
                WHERE a.application_id = ? AND j.company_id IN 
                (SELECT company_id FROM companies WHERE user_id = ?)";

$verifyStmt = $conn->prepare($verifyQuery);
$verifyStmt->bind_param("ii", $applicationId, $userId);
$verifyStmt->execute();
$verifyResult = $verifyStmt->get_result();

if ($verifyResult->num_rows === 0) {
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Update the application status
$updateQuery = "UPDATE applications SET status = ?, updated_at = NOW() WHERE application_id = ?";
$updateStmt = $conn->prepare($updateQuery);
$updateStmt->bind_param("si", $newStatus, $applicationId);

if ($updateStmt->execute()) {
    // Create notification for the applicant
    $applicantId = $verifyResult->fetch_assoc()['user_id'];
    $notificationQuery = "INSERT INTO notifications (user_id, message, type, created_at) 
                         VALUES (?, ?, 'application_status', NOW())";
    $notificationStmt = $conn->prepare($notificationQuery);
    $message = "Your application status has been updated to: " . ucfirst($newStatus);
    $notificationStmt->bind_param("is", $applicantId, $message);
    $notificationStmt->execute();
    
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['error' => 'Failed to update status']);
}

$verifyStmt->close();
$updateStmt->close();
$notificationStmt->close();
$conn->close();
?> 