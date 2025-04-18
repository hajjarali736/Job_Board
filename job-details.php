<?php
require_once 'backend/config.php';

// Check if job ID is provided
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: jobs.php');
    exit();
}

$job_id = (int)$_GET['id'];

// Get job details
$query = "SELECT j.*, c.name as company_name, c.description as company_description, 
          c.website as company_website, c.location as company_location, c.logo as company_logo,
          u.username as posted_by
          FROM jobs j 
          JOIN companies c ON j.company_id = c.company_id
          JOIN users u ON j.posted_by = u.user_id
          WHERE j.job_id = ? AND j.status = 'active'";

$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "i", $job_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) === 0) {
    header('Location: jobs.php');
    exit();
}

$job = mysqli_fetch_assoc($result);

// Sanitize job data
$job = array_map('htmlspecialchars', $job);

// Check if user has already applied
$has_applied = false;
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $check_query = "SELECT * FROM applications WHERE job_id = ? AND user_id = ?";
    $check_stmt = mysqli_prepare($conn, $check_query);
    mysqli_stmt_bind_param($check_stmt, "ii", $job_id, $user_id);
    mysqli_stmt_execute($check_stmt);
    $has_applied = mysqli_num_rows(mysqli_stmt_get_result($check_stmt)) > 0;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $job['title']; ?> - JobSeeker</title>
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
                        <a class="nav-link active" href="jobs.php">Jobs</a>
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

    <main class="container mt-5">
        <div class="row">
            <div class="col-lg-8">
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-4">
                            <?php if($job['company_logo']): ?>
                                <img src="<?php echo $job['company_logo']; ?>" alt="<?php echo $job['company_name']; ?>" class="me-3" style="width: 80px; height: 80px; object-fit: contain;">
                            <?php endif; ?>
                            <div>
                                <h1 class="h3 mb-1"><?php echo $job['title']; ?></h1>
                                <p class="text-muted mb-0"><?php echo $job['company_name']; ?></p>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-map-marker-alt me-2 text-primary"></i>
                                    <span><?php echo $job['location']; ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-briefcase me-2 text-primary"></i>
                                    <span><?php echo ucfirst($job['job_type']); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-money-bill-wave me-2 text-primary"></i>
                                    <span><?php echo $job['salary_range']; ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <h3 class="h5">Job Description</h3>
                            <p><?php echo nl2br($job['description']); ?></p>
                        </div>

                        <div class="mb-4">
                            <h3 class="h5">Requirements</h3>
                            <p><?php echo nl2br($job['requirements']); ?></p>
                        </div>

                        <div class="mb-4">
                            <h3 class="h5">About the Company</h3>
                            <p><?php echo nl2br($job['company_description']); ?></p>
                            <?php if($job['company_website']): ?>
                                <a href="<?php echo $job['company_website']; ?>" target="_blank" class="btn btn-outline-primary">Visit Website</a>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">Posted by <?php echo $job['posted_by']; ?> on <?php echo date('F j, Y', strtotime($job['created_at'])); ?></small>
                            <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'job_seeker'): ?>
                                <?php if($has_applied): ?>
                                    <button class="btn btn-success" disabled>Already Applied</button>
                                <?php else: ?>
                                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#applyModal">Apply Now</button>
                                <?php endif; ?>
                            <?php elseif(!isset($_SESSION['user_id'])): ?>
                                <a href="login.php" class="btn btn-primary">Login to Apply</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card">
                    <div class="card-body">
                        <h3 class="h5 mb-4">Similar Jobs</h3>
                        <?php
                        $similar_query = "SELECT j.*, c.name as company_name, c.logo as company_logo 
                                        FROM jobs j 
                                        JOIN companies c ON j.company_id = c.company_id 
                                        WHERE j.job_id != ? AND j.status = 'active' 
                                        AND (j.job_type = ? OR j.location LIKE ?)
                                        ORDER BY j.created_at DESC LIMIT 3";
                        
                        $similar_stmt = mysqli_prepare($conn, $similar_query);
                        $location_param = "%{$job['location']}%";
                        mysqli_stmt_bind_param($similar_stmt, "iss", $job_id, $job['job_type'], $location_param);
                        mysqli_stmt_execute($similar_stmt);
                        $similar_result = mysqli_stmt_get_result($similar_stmt);

                        if (mysqli_num_rows($similar_result) > 0):
                            while ($similar_job = mysqli_fetch_assoc($similar_result)):
                                $similar_job = array_map('htmlspecialchars', $similar_job);
                        ?>
                            <div class="mb-3">
                                <div class="d-flex align-items-center">
                                    <?php if($similar_job['company_logo']): ?>
                                        <img src="<?php echo $similar_job['company_logo']; ?>" alt="<?php echo $similar_job['company_name']; ?>" class="me-2" style="width: 40px; height: 40px; object-fit: contain;">
                                    <?php endif; ?>
                                    <div>
                                        <h6 class="mb-0"><a href="job-details.php?id=<?php echo $similar_job['job_id']; ?>"><?php echo $similar_job['title']; ?></a></h6>
                                        <small class="text-muted"><?php echo $similar_job['company_name']; ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php
                            endwhile;
                        else:
                        ?>
                            <p class="text-muted">No similar jobs found.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Apply Modal -->
    <div class="modal fade" id="applyModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Apply for <?php echo $job['title']; ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="backend/apply_job.php" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
                        
                        <div class="mb-3">
                            <label for="cover_letter" class="form-label">Cover Letter</label>
                            <textarea class="form-control" id="cover_letter" name="cover_letter" rows="5" required></textarea>
                        </div>
                        
                        <div class="mb-3">
                            <label for="resume" class="form-label">Resume (PDF)</label>
                            <input type="file" class="form-control" id="resume" name="resume" accept=".pdf" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Submit Application</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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