<?php
require_once 'backend/config.php';

// Simple query to get all companies
$query = "SELECT * FROM companies";
$result = $conn->query($query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Companies - JobSeeker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/styles.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container my-5">
        <h1 class="mb-4">Companies</h1>
        
        <div class="row">
            <?php
            if ($result && $result->num_rows > 0) {
                while ($company = $result->fetch_assoc()) {
                    echo '<div class="col-md-4 mb-4">';
                    echo '<div class="card">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($company['name']) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars($company['description']) . '</p>';
                    echo '</div></div></div>';
                }
            } else {
                echo '<div class="col-12"><p>No companies found in database.</p></div>';
            }
            ?>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html> 