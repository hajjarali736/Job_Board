<?php
require_once 'backend/config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$error = '';
$success = '';

// Fetch user information
$user_query = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = mysqli_prepare($conn, $user_query);

if ($user_stmt) {
    mysqli_stmt_bind_param($user_stmt, "i", $user_id);
    mysqli_stmt_execute($user_stmt);
    $user_result = mysqli_stmt_get_result($user_stmt);
    $user = mysqli_fetch_assoc($user_result);
    mysqli_stmt_close($user_stmt);
} else {
    $error = "Error preparing user query: " . mysqli_error($conn);
}

// If user is an employer, fetch company information
$company = null;
if ($user['user_type'] === 'employer') {
    $company_query = "SELECT c.* FROM companies c 
                     JOIN jobs j ON c.company_id = j.company_id 
                     WHERE j.posted_by = ? 
                     LIMIT 1";
    $company_stmt = mysqli_prepare($conn, $company_query);
    
    if ($company_stmt) {
        mysqli_stmt_bind_param($company_stmt, "i", $user_id);
        mysqli_stmt_execute($company_stmt);
        $company_result = mysqli_stmt_get_result($company_stmt);
        $company = mysqli_fetch_assoc($company_result);
        mysqli_stmt_close($company_stmt);
    } else {
        $error = "Error preparing company query: " . mysqli_error($conn);
    }
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = trim($_POST['full_name']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Validate input
    if (empty($full_name) || empty($email)) {
        $error = "Please fill in all required fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if email is already taken by another user
        $check_query = "SELECT user_id FROM users WHERE email = ? AND user_id != ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        
        if ($check_stmt) {
            mysqli_stmt_bind_param($check_stmt, "si", $email, $user_id);
            mysqli_stmt_execute($check_stmt);
            mysqli_stmt_store_result($check_stmt);
            
            if (mysqli_stmt_num_rows($check_stmt) > 0) {
                $error = "This email is already registered by another user.";
            } else {
                // Update user information
                $update_query = "UPDATE users SET full_name = ?, email = ?";
                $params = array($full_name, $email);
                $types = "ss";
                
                // If password is being changed
                if (!empty($current_password) && !empty($new_password) && !empty($confirm_password)) {
                    // Verify current password
                    if (password_verify($current_password, $user['password'])) {
                        if ($new_password === $confirm_password) {
                            if (strlen($new_password) >= 6) {
                                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                                $update_query .= ", password = ?";
                                $params[] = $hashed_password;
                                $types .= "s";
                            } else {
                                $error = "New password must be at least 6 characters long.";
                            }
                        } else {
                            $error = "New passwords do not match.";
                        }
                    } else {
                        $error = "Current password is incorrect.";
                    }
                }
                
                if (empty($error)) {
                    $update_query .= " WHERE user_id = ?";
                    $params[] = $user_id;
                    $types .= "i";
                    
                    $update_stmt = mysqli_prepare($conn, $update_query);
                    
                    if ($update_stmt) {
                        mysqli_stmt_bind_param($update_stmt, $types, ...$params);
                        
                        if (mysqli_stmt_execute($update_stmt)) {
                            $success = "Profile updated successfully!";
                            // Refresh user data
                            $user['full_name'] = $full_name;
                            $user['email'] = $email;
                        } else {
                            $error = "Error updating profile: " . mysqli_error($conn);
                        }
                        
                        mysqli_stmt_close($update_stmt);
                    } else {
                        $error = "Error preparing update query: " . mysqli_error($conn);
                    }
                }
            }
            
            mysqli_stmt_close($check_stmt);
        } else {
            $error = "Error preparing check query: " . mysqli_error($conn);
        }
    }
}

// Get user's applications if job seeker
$applications = [];
if ($user['user_type'] == 'job_seeker') {
    $query = "SELECT a.*, j.title as job_title, j.company_name, j.location, j.job_type, j.salary_range 
              FROM applications a 
              JOIN jobs j ON a.job_id = j.job_id 
              WHERE a.user_id = ? 
              ORDER BY a.created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $applications[] = $row;
    }
}

// Get user's posted jobs if employer
$posted_jobs = [];
if ($user['user_type'] == 'employer') {
    $query = "SELECT * FROM jobs WHERE posted_by = ? ORDER BY created_at DESC";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $posted_jobs[] = $row;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile - JobSeeker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <header class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand" href="index.php">JobSeeker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="jobs.php">Jobs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="companies.php">Companies</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="about-us.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <a href="profile.php" class="btn btn-outline-primary me-2">Profile</a>
                    <a href="logout.php" class="btn btn-outline-secondary">Logout</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Profile</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" readonly>
                            </div>
                            
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="full_name" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="user_type" class="form-label">Account Type</label>
                                <input type="text" class="form-control" id="user_type" value="<?php echo ucfirst(str_replace('_', ' ', $user['user_type'])); ?>" readonly>
                            </div>
                            
                            <hr>
                            
                            <h4 class="mb-3">Change Password</h4>
                            <div class="mb-3">
                                <label for="current_password" class="form-label">Current Password</label>
                                <input type="password" class="form-control" id="current_password" name="current_password">
                            </div>
                            
                            <div class="mb-3">
                                <label for="new_password" class="form-label">New Password</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                            
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Confirm New Password</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5>Contact Us</h5>
                    <p>Email: info@jobseeker.com</p>
                    <p>Phone: +961 76 010 956</p>
                </div>
                <div class="col-md-6">
                    <h5>Quick Links</h5>
                    <ul class="list-unstyled">
                        <li><a href="about-us.php" class="text-light">About us</a></li>
                        <li><a href="contact.php" class="text-light">Contact us</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4">
            <p class="text-center mb-0">&copy; 2025 JobSeeker. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 