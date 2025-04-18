<?php
require_once 'backend/config.php';

// Check if user is logged in and is an employer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'employer') {
    header('Location: login.php');
    exit();
}

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $website = trim($_POST['website']);
    $location = trim($_POST['location']);
    $user_id = $_SESSION['user_id'];
    
    if (empty($name) || empty($description) || empty($location)) {
        $error = "Please fill in all required fields.";
    } else {
        // Check if company already exists for this user
        $check_query = "SELECT * FROM companies c 
                       JOIN jobs j ON c.company_id = j.company_id 
                       WHERE j.posted_by = ?";
        $check_stmt = mysqli_prepare($conn, $check_query);
        mysqli_stmt_bind_param($check_stmt, "i", $user_id);
        mysqli_stmt_execute($check_stmt);
        $check_result = mysqli_stmt_get_result($check_stmt);
        
        if (mysqli_num_rows($check_result) > 0) {
            $error = "You already have a company profile.";
        } else {
            // Insert new company
            $query = "INSERT INTO companies (name, description, website, location) 
                     VALUES (?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $query);
            
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ssss", $name, $description, $website, $location);
                
                if (mysqli_stmt_execute($stmt)) {
                    $company_id = mysqli_insert_id($conn);
                    
                    // Create a default job posting to associate the company with the user
                    $job_query = "INSERT INTO jobs (company_id, title, description, location, 
                                 job_type, salary_range, requirements, posted_by, status) 
                                 VALUES (?, 'Company Profile', 'Default job for company profile', 
                                 ?, 'full-time', 'Not specified', 'Not specified', ?, 'active')";
                    $job_stmt = mysqli_prepare($conn, $job_query);
                    mysqli_stmt_bind_param($job_stmt, "isi", $company_id, $location, $user_id);
                    mysqli_stmt_execute($job_stmt);
                    
                    $success = "Company profile created successfully!";
                } else {
                    $error = "Error creating company profile. Please try again.";
                }
            } else {
                $error = "Error preparing statement: " . mysqli_error($conn);
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Company Profile - JobSeeker</title>
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
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title mb-4">Create Company Profile</h2>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo $success; ?></div>
                        <?php endif; ?>
                        
                        <form method="post" action="">
                            <div class="mb-3">
                                <label for="name" class="form-label">Company Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Company Description *</label>
                                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
                            </div>
                            
                            <div class="mb-3">
                                <label for="website" class="form-label">Website</label>
                                <input type="url" class="form-control" id="website" name="website">
                                <small class="text-muted">Optional</small>
                            </div>
                            
                            <div class="mb-3">
                                <label for="location" class="form-label">Location *</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">Create Profile</button>
                                <a href="profile.php" class="btn btn-outline-secondary">Cancel</a>
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