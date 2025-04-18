<?php
require_once 'backend/config.php';

// Get search parameters
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$location = isset($_GET['location']) ? trim($_GET['location']) : '';
$job_type = isset($_GET['job_type']) ? trim($_GET['job_type']) : '';

// Build the query
$query = "SELECT j.*, c.name as company_name
          FROM jobs j 
          JOIN companies c ON j.company_id = c.company_id 
          WHERE j.status = 'active'";

$params = [];
$types = "";

if (!empty($search)) {
    $query .= " AND (j.title LIKE ? OR j.description LIKE ? OR c.name LIKE ?)";
    $search_param = "%$search%";
    $params[] = $search_param;
    $params[] = $search_param;
    $params[] = $search_param;
    $types .= "sss";
}

if (!empty($location)) {
    $query .= " AND j.location LIKE ?";
    $location_param = "%$location%";
    $params[] = $location_param;
    $types .= "s";
}

if (!empty($job_type)) {
    $query .= " AND j.job_type = ?";
    $params[] = $job_type;
    $types .= "s";
}

$query .= " ORDER BY j.created_at DESC";

// Prepare and execute the query
$stmt = mysqli_prepare($conn, $query);
if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$jobs = [];
while ($row = mysqli_fetch_assoc($result)) {
    $jobs[] = [
        'job_id' => $row['job_id'],
        'title' => htmlspecialchars($row['title']),
        'description' => htmlspecialchars($row['description']),
        'location' => htmlspecialchars($row['location']),
        'salary_range' => htmlspecialchars($row['salary_range']),
        'job_type' => htmlspecialchars($row['job_type']),
        'company_name' => htmlspecialchars($row['company_name']),
        'created_at' => $row['created_at']
    ];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jobs - JobSeeker</title>
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
            <div class="col-lg-3">
                <aside class="job-filters">
                    <div class="card">
                        <div class="card-body">
                            <h3>Filters</h3>
                            <form method="get" action="jobs.php">
                                <div class="mb-3">
                                    <label for="search" class="form-label">Keywords</label>
                                    <input type="text" name="search" id="search" class="form-control" value="<?php echo htmlspecialchars($search); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="location" class="form-label">Location</label>
                                    <input type="text" name="location" id="location" class="form-control" value="<?php echo htmlspecialchars($location); ?>">
                                </div>
                                
                                <div class="mb-3">
                                    <label for="job_type" class="form-label">Job Type</label>
                                    <select name="job_type" id="job_type" class="form-select">
                                        <option value="">All Types</option>
                                        <option value="full-time" <?php echo $job_type == 'full-time' ? 'selected' : ''; ?>>Full Time</option>
                                        <option value="part-time" <?php echo $job_type == 'part-time' ? 'selected' : ''; ?>>Part Time</option>
                                        <option value="contract" <?php echo $job_type == 'contract' ? 'selected' : ''; ?>>Contract</option>
                                        <option value="internship" <?php echo $job_type == 'internship' ? 'selected' : ''; ?>>Internship</option>
                                    </select>
                                </div>
                                
                                <button type="submit" class="btn btn-primary w-100">Apply Filters</button>
                            </form>
                        </div>
                    </div>
                </aside>
            </div>
            
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2>Available Jobs</h2>
                    <?php if(isset($_SESSION['user_id']) && $_SESSION['user_type'] == 'employer'): ?>
                        <a href="post-job.php" class="btn btn-primary">Post a Job</a>
                    <?php endif; ?>
                </div>
                
                <?php if(empty($jobs)): ?>
                    <div class="alert alert-info">
                        No jobs found matching your criteria.
                    </div>
                <?php else: ?>
                    <div class="row">
                        <?php foreach($jobs as $job): ?>
                            <div class="col-md-6 mb-4">
                                <div class="card h-100">
                                    <div class="card-body">
                                        <div class="mb-3">
                                            <h5 class="card-title mb-0"><?php echo $job['title']; ?></h5>
                                            <p class="text-muted mb-0"><?php echo $job['company_name']; ?></p>
                                        </div>
                                        
                                        <p class="card-text"><?php echo substr($job['description'], 0, 150) . '...'; ?></p>
                                        
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="badge bg-primary"><?php echo $job['job_type']; ?></span>
                                            <span class="text-muted"><?php echo $job['location']; ?></span>
                                        </div>
                                        
                                        <div class="mt-3">
                                            <a href="job-details.php?id=<?php echo $job['job_id']; ?>" class="btn btn-primary">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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