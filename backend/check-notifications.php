<?php
require_once 'db_connection.php';
session_start();

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Not authenticated']);
    exit;
}

$userId = $_SESSION['user_id'];

// Get unread notifications count
$countQuery = "SELECT COUNT(*) as count FROM notifications 
               WHERE user_id = ? AND is_read = 0";
$countStmt = $conn->prepare($countQuery);
$countStmt->bind_param("i", $userId);
$countStmt->execute();
$countResult = $countStmt->get_result();
$count = $countResult->fetch_assoc()['count'];

// Get recent notifications
$notificationsQuery = "SELECT * FROM notifications 
                      WHERE user_id = ? 
                      ORDER BY created_at DESC 
                      LIMIT 5";
$notificationsStmt = $conn->prepare($notificationsQuery);
$notificationsStmt->bind_param("i", $userId);
$notificationsStmt->execute();
$notificationsResult = $notificationsStmt->get_result();

$notifications = [];
while ($row = $notificationsResult->fetch_assoc()) {
    $notifications[] = [
        'message' => $row['message'],
        'type' => $row['type'],
        'timestamp' => date('M d, Y H:i', strtotime($row['created_at'])),
        'is_read' => $row['is_read']
    ];
}

// Mark notifications as read
if ($count > 0) {
    $markReadQuery = "UPDATE notifications SET is_read = 1 
                      WHERE user_id = ? AND is_read = 0";
    $markReadStmt = $conn->prepare($markReadQuery);
    $markReadStmt->bind_param("i", $userId);
    $markReadStmt->execute();
}

echo json_encode([
    'count' => $count,
    'notifications' => $notifications
]);

$countStmt->close();
$notificationsStmt->close();
$markReadStmt->close();
$conn->close();
?> 