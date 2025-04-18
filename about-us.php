<?php
require_once 'backend/config.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - JobSeeker</title>
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
                        <a class="nav-link active" href="about-us.php">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="contact.php">Contact</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="btn btn-outline-primary me-2">Profile</a>
                        <a href="logout.php" class="btn btn-outline-secondary">Logout</a>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                        <a href="register.php" class="btn btn-outline-secondary">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main class="container mt-5">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="card">
                    <div class="card-body">
                        <h1 class="card-title text-center mb-4">About JobSeeker</h1>
                        
                        <div class="mb-5">
                            <h2 class="h4 mb-3">Our Mission</h2>
                            <p>JobSeeker is dedicated to connecting talented professionals with their dream jobs and helping employers find the perfect candidates for their organizations. We strive to make the job search and hiring process more efficient, transparent, and rewarding for everyone involved.</p>
                        </div>

                        <div class="mb-5">
                            <h2 class="h4 mb-3">For Job Seekers</h2>
                            <p>We provide a comprehensive platform where you can:</p>
                            <ul>
                                <li>Search and apply for jobs across various industries</li>
                                <li>Create and manage your professional profile</li>
                                <li>Track your job applications and their status</li>
                                <li>Receive notifications about new job opportunities</li>
                                <li>Connect with potential employers</li>
                            </ul>
                        </div>

                        <div class="mb-5">
                            <h2 class="h4 mb-3">For Employers</h2>
                            <p>Our platform offers employers powerful tools to:</p>
                            <ul>
                                <li>Post job listings and reach qualified candidates</li>
                                <li>Manage applications and track candidate progress</li>
                                <li>Review resumes and cover letters</li>
                                <li>Communicate with applicants</li>
                                <li>Build a strong employer brand</li>
                            </ul>
                        </div>

                        <div class="mb-5">
                            <h2 class="h4 mb-3">Our Features</h2>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-search fa-3x mb-3 text-primary"></i>
                                        <h3 class="h5">Advanced Search</h3>
                                        <p>Find jobs that match your skills and preferences</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-bell fa-3x mb-3 text-primary"></i>
                                        <h3 class="h5">Job Alerts</h3>
                                        <p>Get notified about new job opportunities</p>
                                    </div>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <div class="text-center">
                                        <i class="fas fa-shield-alt fa-3x mb-3 text-primary"></i>
                                        <h3 class="h5">Secure Platform</h3>
                                        <p>Your data is protected with advanced security</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="text-center">
                            <a href="register.php" class="btn btn-primary btn-lg">Join Us Today</a>
                        </div>
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