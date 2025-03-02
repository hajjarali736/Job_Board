// worked on by jad and ali
document.addEventListener("DOMContentLoaded", () => {
  const featuredJobsList = document.getElementById("featured-jobs-list")
  const topCompaniesList = document.getElementById("top-companies-list")

  // render featured jobs
  if (typeof jobOpenings !== "undefined") {
    jobOpenings.forEach((job) => {
      const jobCard = document.createElement("div")
      jobCard.className = "col-md-6 mb-4"
      jobCard.innerHTML = `
                <div class="job-card">
                    <h3 class="job-title">${job.title}</h3>
                    <p class="job-company">${job.company}</p>
                    <p class="job-location">${job.location}</p>
                    <p>${job.description}</p>
                    <a href="apply.html" class="btn btn-outline-primary">Apply Now</a>
                </div>
            `
      featuredJobsList.appendChild(jobCard)
    })
  } else {
    console.error("Job openings data not found. Make sure jobs-data.js is loaded correctly.")
  }

  // render top companies
  const topCompanies = [
    { name: "TechCorp", industry: "Technology", employees: "1000+", logo: "/placeholder.svg?height=50&width=50" },
    { name: "FinanceHub", industry: "Finance", employees: "500+", logo: "/placeholder.svg?height=50&width=50" },
    {
      name: "HealthCare Plus",
      industry: "Healthcare",
      employees: "2000+",
      logo: "/placeholder.svg?height=50&width=50",
    },
    { name: "EduTech", industry: "Education", employees: "300+", logo: "/placeholder.svg?height=50&width=50" },
  ]

  topCompanies.forEach((company) => {
    const companyCard = document.createElement("div")
    companyCard.className = "col-md-6 mb-4"
    companyCard.innerHTML = `
            <div class="company-card d-flex align-items-center">
<!--                <img src="${company.logo}" alt="${company.name} logo" class="me-3" width="50" height="50">-->
                <div>
                    <h3 class="company-name">${company.name}</h3>
                    <p class="company-industry">${company.industry}</p>
                    <p class="company-employees">${company.employees} employees</p>
                </div>
            </div>
        `
    topCompaniesList.appendChild(companyCard)
  })
});

        document.addEventListener("DOMContentLoaded", () => {
            const companyList = document.getElementById("company-list");
            const searchForm = document.getElementById("company-search-form");

            function displayCompanies(companiesToShow) {
                companyList.innerHTML = "";
                companiesToShow.forEach((company) => {
                    const companyCard = document.createElement("article");
                    companyCard.className = "col-md-6 col-lg-4";
                    companyCard.innerHTML = `
                        <div class="card h-100 shadow-sm">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-3">
                                 
                                    <h2 class="card-title h5 mb-0">${company.name}</h2>
                                </div>
                                <p class="card-text"><i class="bi bi-building me-2"></i>${company.industry}</p>
                                <p class="card-text"><i class="bi bi-geo-alt me-2"></i>${company.location}</p>
                                <p class="card-text"><i class="bi bi-briefcase me-2"></i>${company.openJobs} open jobs</p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <a href="company-details.html?id=${company.id}" class="btn btn-primary w-100">View Profile</a>
                            </div>
                        </div>
                    `;
                    companyList.appendChild(companyCard);
                });
            }

            searchForm.addEventListener("submit", (e) => {
                e.preventDefault();
                displayCompanies(companies);
            });

            displayCompanies(companies);
        });


        document.addEventListener("DOMContentLoaded", () => {
  const savedJobsLink = document.getElementById("saved-jobs-link")
  const appliedJobsLink = document.getElementById("applied-jobs-link")
  const postedJobsLink = document.getElementById("posted-jobs-link")
  const profileSettingsLink = document.getElementById("profile-settings-link")

  const savedJobs = document.getElementById("saved-jobs")
  const appliedJobs = document.getElementById("applied-jobs")
  const postedJobs = document.getElementById("posted-jobs")
  const profileSettings = document.getElementById("profile-settings")

  function showSection(section) {
    ;[savedJobs, appliedJobs, postedJobs, profileSettings].forEach((s) => s.classList.add("d-none"))
    section.classList.remove("d-none")
  }

  savedJobsLink.addEventListener("click", (e) => {
    e.preventDefault()
    showSection(savedJobs)
  })

  appliedJobsLink.addEventListener("click", (e) => {
    e.preventDefault()
    showSection(appliedJobs)
  })

  postedJobsLink.addEventListener("click", (e) => {
    e.preventDefault()
    showSection(postedJobs)
  })

  profileSettingsLink.addEventListener("click", (e) => {
    e.preventDefault()
    showSection(profileSettings)
  })

  // Simulating user type detection (job seeker or employer)
  const userType = "job_seeker" // or 'employer'

  if (userType === "job_seeker") {
    postedJobsLink.style.display = "none"
  } else {
    savedJobsLink.style.display = "none"
    appliedJobsLink.style.display = "none"
  }
});


                document.addEventListener('DOMContentLoaded', () => {
            const urlParams = new URLSearchParams(window.location.search);
            const jobIndex = urlParams.get('jobIndex');

            if (jobIndex !== null && jobOpenings && jobOpenings[jobIndex]) {
                const job = jobOpenings[jobIndex];

                document.getElementById('jobTitle').textContent = job.title;
                document.getElementById('jobCompany').textContent = job.company;
                document.getElementById('jobLocation').querySelector('span').textContent = job.location;
                document.getElementById('jobDescription').textContent = job.description;
            } else {
                document.getElementById('jobDetails').innerHTML = `
                    <div class="alert alert-danger">Job not found or invalid index.</div>
                `;
            }
        });

                document.addEventListener('DOMContentLoaded', () => {
    const jobListings = document.getElementById('jobListings');

    jobOpenings.forEach((job, index) => {
        const jobHTML = `
            <article class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h2 class="h5 mb-1">${job.title}</h2>
                            <p class="mb-1 text-secondary">${job.company}</p>
                            <p class="mb-1"><i class="fas fa-map-marker-alt"></i> ${job.location}</p>
                            ${job.description ? `<p class="mt-2">${job.description}</p>` : ''}
                        </div>
                        <button class="btn btn-primary btn-sm apply-btn" data-index="${index}">Job details</button>
                    </div>
                    <div class="mt-2 text-muted small">
                        <time datetime="2023-08-01">Posted 2 days ago</time>
                    </div>
                </div>
            </article>
        `;
        jobListings.insertAdjacentHTML('beforeend', jobHTML);
    });

    // Add event listener to dynamically generated "Apply Now" buttons
    document.querySelectorAll('.apply-btn').forEach(button => {
        button.addEventListener('click', (event) => {
            const jobIndex = event.target.getAttribute('data-index');
            window.location.href = `job-details.html?jobIndex=${jobIndex}`;
        });
    });
});





