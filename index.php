<?php
require_once 'backend/config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JobSeeker - Find Your Dream Job</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
    <link href="css/styles.css" rel="stylesheet">
    <link href="css/notifications.css" rel="stylesheet">
</head>
<body>
    <header class="bg-light">
        <nav class="navbar navbar-expand-lg navbar-light container">
            <a class="navbar-brand" href="index.php">JobSeeker</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="btn btn-outline-primary me-2">Profile</a>
                        <a href="logout.php" class="btn btn-outline-secondary me-2">Logout</a>
                        <?php if($_SESSION['user_type'] == 'employer'): ?>
                            <a href="post-job.php" class="btn btn-primary">Post a Job</a>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-outline-primary me-2">Login</a>
                        <a href="register.php" class="btn btn-outline-secondary me-2">Register</a>
                    <?php endif; ?>
                </div>
            </div>
        </nav>
    </header>

    <main>
        <section class="hero position-relative text-white text-center d-flex align-items-center justify-content-center">
            <video autoplay muted loop class="position-absolute w-100 h-100 object-fit-cover">
                <source src="assets/video.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
            <div class="overlay position-absolute w-100 h-100 bg-dark opacity-50"></div>
            <div class="container position-relative">
                <h1 class="display-4 fw-bold text-white">Find Your Dream Job Today</h1>
                <p class="lead mb-4">Explore thousands of opportunities from top companies worldwide.</p>
                <form id="search-form" class="d-flex justify-content-center">
                    <input type="text" id="search-input" class="form-control w-50 me-2" placeholder="Job title, keywords, or company">
                    <button type="submit" class="btn btn-primary">Search</button>
                </form>
            </div>
        </section>

        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-3">
                    <aside class="job-filters">
                        <h3>Job Categories</h3>
                        <ul class="list-unstyled">
                            <li><a href="#" class="text-decoration-none">Technology</a></li>
                            <li><a href="#" class="text-decoration-none">Marketing</a></li>
                            <li><a href="#" class="text-decoration-none">Finance</a></li>
                            <li><a href="#" class="text-decoration-none">Healthcare</a></li>
                            <li><a href="#" class="text-decoration-none">Education</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-lg-9">
                    <section class="featured-jobs mb-5">
                        <h2 class="mb-4">Featured Jobs</h2>
                        <div class="row" id="featured-jobs-list">
                            <!-- Featured jobs will be loaded via AJAX -->
                        </div>
                    </section>

                    <section class="top-companies">
                        <h2 class="mb-4">Top Companies</h2>
                        <div class="row" id="top-companies-list">
                            <!-- Top companies will be loaded via AJAX -->
                        </div>
                    </section>
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
                        <li><a href="post-job.php" class="text-light">Post a job</a></li>
                    </ul>
                </div>
            </div>
            <hr class="mt-4">
            <p class="text-center mb-0">&copy; 2025 JobSeeker. All rights reserved.</p>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/ajax-functions.js"></script>
    
    <!-- Notifications Container -->
    <div class="notifications-container position-fixed top-0 end-0 p-3" style="z-index: 1050;">
        <div class="notification-list"></div>
    </div>
    
    <!-- Notification Badge -->
    <?php if(isset($_SESSION['user_id'])): ?>
    <div class="notification-badge position-fixed top-0 end-0 bg-danger text-white rounded-circle p-2" style="display: none; z-index: 1051;">
        <span>0</span>
    </div>
    <?php endif; ?>
</body>
</html> 