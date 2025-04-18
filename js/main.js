$(document).ready(function () {
  // Load featured jobs and companies on page load
  loadFeaturedJobs();
  loadTopCompanies();

  // Handle search form submission
  $("#search-form").on("submit", function (e) {
    e.preventDefault();
    const searchTerm = $("#search-input").val();
    window.location.href = `jobs.php?search=${encodeURIComponent(searchTerm)}`;
  });
});

// Function to load featured jobs
function loadFeaturedJobs() {
  $.ajax({
    url: "backend/get_featured_jobs.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        displayFeaturedJobs(response.jobs);
      } else {
        $("#featured-jobs-list").html(
          '<p class="text-center">No featured jobs available at the moment.</p>'
        );
      }
    },
    error: function () {
      $("#featured-jobs-list").html(
        '<p class="text-center text-danger">Error loading featured jobs.</p>'
      );
    },
  });
}

// Function to display featured jobs
function displayFeaturedJobs(jobs) {
  const jobsContainer = $("#featured-jobs-list");
  jobsContainer.empty();

  if (jobs.length === 0) {
    jobsContainer.html('<p class="text-center">No jobs found.</p>');
    return;
  }

  jobs.forEach(function (job) {
    const jobCard = `
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title">${job.title}</h5>
                        <h6 class="card-subtitle mb-2 text-muted">${
                          job.company_name
                        }</h6>
                        <p class="card-text">${job.description.substring(
                          0,
                          150
                        )}...</p>
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="badge bg-primary">${
                              job.job_type
                            }</span>
                            <span class="text-muted">${job.location}</span>
                        </div>
                        <div class="mt-3">
                            <a href="job-details.php?id=${
                              job.job_id
                            }" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            </div>
        `;
    jobsContainer.append(jobCard);
  });
}

// Function to load top companies
function loadTopCompanies() {
  $.ajax({
    url: "backend/get_top_companies.php",
    method: "GET",
    dataType: "json",
    success: function (response) {
      if (response.success) {
        displayTopCompanies(response.companies);
      } else {
        $("#top-companies-list").html(
          '<p class="text-center">No companies available at the moment.</p>'
        );
      }
    },
    error: function () {
      $("#top-companies-list").html(
        '<p class="text-center text-danger">Error loading companies.</p>'
      );
    },
  });
}

// Function to display top companies
function displayTopCompanies(companies) {
  const companiesContainer = $("#top-companies-list");
  companiesContainer.empty();

  if (companies.length === 0) {
    companiesContainer.html('<p class="text-center">No companies found.</p>');
    return;
  }

  companies.forEach(function (company) {
    const companyCard = `
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body text-center">
                        <h5 class="card-title">${company.name}</h5>
                        <p class="card-text">${company.location}</p>
                        <a href="company-details.php?id=${company.company_id}" class="btn btn-outline-primary">View Profile</a>
                    </div>
                </div>
            </div>
        `;
    companiesContainer.append(companyCard);
  });
}
