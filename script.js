// Assuming jobOpenings is fetched from an external file named jobs-data.js
// Add this line to include the external JavaScript file containing jobOpenings data.  Place this line in the `<head>` of your HTML file.
// <script src="jobs-data.js"></script>

document.addEventListener("DOMContentLoaded", () => {
  const featuredJobsList = document.getElementById("featured-jobs-list")
  const topCompaniesList = document.getElementById("top-companies-list")

  // Render featured jobs
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
                    <a href="#" class="btn btn-outline-primary">Apply Now</a>
                </div>
            `
      featuredJobsList.appendChild(jobCard)
    })
  } else {
    console.error("Job openings data not found. Make sure jobs-data.js is loaded correctly.")
  }

  // Render top companies
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
})

