-- Sample data for testing

-- Insert sample users
INSERT INTO users (username, password, email, full_name, user_type) VALUES
('john_doe', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'john@example.com', 'John Doe', 'job_seeker'),
('jane_smith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'jane@example.com', 'Jane Smith', 'job_seeker'),
('tech_corp', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hr@techcorp.com', 'Tech Corp HR', 'employer'),
('finance_inc', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'hr@financeinc.com', 'Finance Inc HR', 'employer');

-- Insert sample companies
INSERT INTO companies (name, description, website, location, logo) VALUES
('Tech Corp', 'Leading technology company specializing in software development', 'https://techcorp.com', 'San Francisco, CA', 'assets/techcorp-logo.png'),
('Finance Inc', 'Global financial services company', 'https://financeinc.com', 'New York, NY', 'assets/financeinc-logo.png'),
('Health Plus', 'Healthcare technology solutions provider', 'https://healthplus.com', 'Boston, MA', 'assets/healthplus-logo.png'),
('EduTech Solutions', 'Educational technology company', 'https://edutech.com', 'Austin, TX', 'assets/edutech-logo.png');

-- Insert sample jobs
INSERT INTO jobs (company_id, title, description, requirements, location, salary_range, job_type, posted_by) VALUES
(1, 'Senior Software Engineer', 'Looking for an experienced software engineer to join our team...', '5+ years of experience, PHP, MySQL, JavaScript', 'San Francisco, CA', '$120,000 - $150,000', 'full-time', 3),
(1, 'Frontend Developer', 'Join our frontend development team...', '3+ years of experience, React, JavaScript, CSS', 'Remote', '$90,000 - $110,000', 'full-time', 3),
(2, 'Financial Analyst', 'Seeking a financial analyst for our investment team...', 'Bachelor\'s in Finance, 2+ years experience', 'New York, NY', '$80,000 - $100,000', 'full-time', 4),
(3, 'Healthcare IT Specialist', 'Help us develop healthcare technology solutions...', 'Experience in healthcare IT, Python, SQL', 'Boston, MA', '$95,000 - $115,000', 'full-time', 3),
(4, 'Educational Software Developer', 'Create innovative educational software...', 'Experience with educational technology, JavaScript', 'Austin, TX', '$85,000 - $105,000', 'full-time', 4),
(1, 'DevOps Engineer', 'Join our DevOps team to improve our infrastructure...', 'Experience with AWS, Docker, CI/CD', 'Remote', '$110,000 - $130,000', 'full-time', 3);

-- Insert sample applications
INSERT INTO applications (job_id, user_id, status, cover_letter, resume_path) VALUES
(1, 1, 'pending', 'I am interested in the Senior Software Engineer position...', 'resumes/john-doe-resume.pdf'),
(2, 2, 'reviewed', 'I would like to apply for the Frontend Developer position...', 'resumes/jane-smith-resume.pdf');

-- Insert sample contact messages
INSERT INTO contact_messages (name, email, subject, message) VALUES
('Alice Johnson', 'alice@example.com', 'Job Application Question', 'I have a question about the application process...'),
('Bob Wilson', 'bob@example.com', 'Company Information', 'I would like to know more about your company...'); 