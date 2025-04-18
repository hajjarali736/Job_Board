<?php
session_start();
require_once 'config.php';

// Check if user is logged in and is a job seeker
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'job_seeker') {
    header('Location: ../login.php');
    exit();
}

// Check if required fields are present
if (!isset($_POST['job_id']) || !isset($_POST['cover_letter']) || !isset($_FILES['resume'])) {
    $_SESSION['error'] = "Please fill in all required fields.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

$job_id = (int)$_POST['job_id'];
$user_id = $_SESSION['user_id'];
$cover_letter = trim($_POST['cover_letter']);

// Validate job exists and is active
$job_query = "SELECT * FROM jobs WHERE job_id = ? AND status = 'active'";
$job_stmt = mysqli_prepare($conn, $job_query);
mysqli_stmt_bind_param($job_stmt, "i", $job_id);
mysqli_stmt_execute($job_stmt);
$job_result = mysqli_stmt_get_result($job_stmt);

if (mysqli_num_rows($job_result) === 0) {
    $_SESSION['error'] = "Job not found or no longer available.";
    header('Location: ../jobs.php');
    exit();
}

// Check if user has already applied
$check_query = "SELECT * FROM applications WHERE job_id = ? AND user_id = ?";
$check_stmt = mysqli_prepare($conn, $check_query);
mysqli_stmt_bind_param($check_stmt, "ii", $job_id, $user_id);
mysqli_stmt_execute($check_stmt);

if (mysqli_num_rows(mysqli_stmt_get_result($check_stmt)) > 0) {
    $_SESSION['error'] = "You have already applied for this job.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Handle resume upload
$resume = $_FILES['resume'];
$allowed_types = ['application/pdf'];
$max_size = 5 * 1024 * 1024; // 5MB

if (!in_array($resume['type'], $allowed_types)) {
    $_SESSION['error'] = "Only PDF files are allowed for resumes.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

if ($resume['size'] > $max_size) {
    $_SESSION['error'] = "Resume file size must be less than 5MB.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Generate unique filename
$file_extension = pathinfo($resume['name'], PATHINFO_EXTENSION);
$filename = uniqid() . '.' . $file_extension;
$upload_path = '../uploads/resumes/' . $filename;

// Create uploads directory if it doesn't exist
if (!file_exists('../uploads/resumes')) {
    mkdir('../uploads/resumes', 0777, true);
}

// Move uploaded file
if (!move_uploaded_file($resume['tmp_name'], $upload_path)) {
    $_SESSION['error'] = "Error uploading resume. Please try again.";
    header('Location: ' . $_SERVER['HTTP_REFERER']);
    exit();
}

// Insert application into database
$insert_query = "INSERT INTO applications (job_id, user_id, cover_letter, resume_path, status, created_at) 
                 VALUES (?, ?, ?, ?, 'pending', NOW())";
$insert_stmt = mysqli_prepare($conn, $insert_query);
mysqli_stmt_bind_param($insert_stmt, "iiss", $job_id, $user_id, $cover_letter, $filename);

if (mysqli_stmt_execute($insert_stmt)) {
    $_SESSION['success'] = "Your application has been submitted successfully!";
    
    // Get job details for notification
    $job = mysqli_fetch_assoc($job_result);
    $job_title = $job['title'];
    
    // Get employer details
    $employer_query = "SELECT u.email, u.username FROM users u 
                      JOIN jobs j ON u.user_id = j.posted_by 
                      WHERE j.job_id = ?";
    $employer_stmt = mysqli_prepare($conn, $employer_query);
    mysqli_stmt_bind_param($employer_stmt, "i", $job_id);
    mysqli_stmt_execute($employer_stmt);
    $employer = mysqli_fetch_assoc(mysqli_stmt_get_result($employer_stmt));
    
    // Send notification email to employer
    $to = $employer['email'];
    $subject = "New Application for: " . $job_title;
    $message = "Dear " . $employer['username'] . ",\n\n";
    $message .= "A new application has been submitted for your job posting: " . $job_title . "\n\n";
    $message .= "Please log in to your dashboard to review the application.\n\n";
    $message .= "Best regards,\nJobSeeker Team";
    $headers = "From: noreply@jobseeker.com";
    
    mail($to, $subject, $message, $headers);
} else {
    $_SESSION['error'] = "Error submitting application. Please try again.";
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit();
?> 