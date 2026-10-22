# Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  
**Project Name:** StudentHub Portal  

---

## 1. Project Overview & Scope
**StudentHub** is an all-in-one web portal designed for college students and faculty/administrators. It acts as a central hub for campus event registrations, student profile management, announcements, FAQs, and feedback collection.

### Problem Definition:
Students often face challenges in tracking college events, updates, and academic notices across fragmented channels (social media, notice boards, chat groups). StudentHub solves this by providing:
- A unified platform to discover and register for technical, cultural, and sports events.
- Easy access to student profiles, directories, and FAQs.
- Simple administrative controls for managing event postings and student records.

---

## 2. User Roles and Permissions
1. **Visitor / Guest User:**
   - Can view the Home page, About page, public Event listings, FAQs, and Contact page.
   - Can access the Student Registration and Login pages.
2. **Registered Student:**
   - Can log into their personalized Student Dashboard.
   - Can view and edit their student profile.
   - Can register for upcoming college events.
   - Can submit inquiries and feedback.
3. **Administrator:**
   - Secure login with admin credentials.
   - Access to Admin Dashboard with overall statistics (total students, events, registrations).
   - CRUD management for Student records and Event announcements (with poster upload).
   - View system audit logs and submitted feedbacks.

---

## 3. Key Modules & Identified Pages (Minimum 10 Pages)
StudentHub consists of the following 11 core pages:

| No. | Page File | Page Title | Description & Module |
|---|---|---|---|
| 1 | `index.html` | Home Page | Welcome hero section, highlights, quick links, and upcoming events. |
| 2 | `about.html` | About Us | Information about CHARUSAT, the StudentHub initiative, and mission. |
| 3 | `register.html` | Student Registration | Form for new students to register with client-side validation. |
| 4 | `login.html` | Login Page | Authentication page with role selection and "Remember Me" option. |
| 5 | `dashboard.html` | Student Dashboard | Logged-in student landing page with registered events and quick actions. |
| 6 | `events.html` | Events Directory | List of upcoming events with category filter, search, and details. |
| 7 | `profile.html` | Student Profile | Profile view and editable contact information for the student. |
| 8 | `contact.html` | Contact Us | Campus address, map placeholder, phone numbers, and contact form. |
| 9 | `admin.html` | Admin Dashboard | Administrative panel with statistics, CRUD links, and audit logs. |
| 10 | `faq.html` | Frequently Asked Questions | Searchable and expandable accordion of common campus queries. |
| 11 | `feedback.html` | Feedback Form | User feedback submission for campus services and events. |

---

## 4. Navigation Flow
```
                      +-------------------+
                      |     Home Page     |
                      |   (index.html)    |
                      +---------+---------+
                                |
       +------------+-----------+-----------+------------+
       |            |           |           |            |
       v            v           v           v            v
  +----------+ +----------+ +--------+ +---------+ +----------+
  |  About   | |  Events  | |  FAQ   | | Contact | | Feedback |
  +----------+ +----+-----+ +--------+ +----+----+ +----------+
                    |                       |
                    v                       v
               Event Details           Submit Query
                    |
     +--------------+--------------+
     |                             |
     v                             v
+----------+                 +-----------+
|  Login   |<--------------->|  Register |
+----+-----+                 +-----------+
     |
     +--------------------------+
     |                          |
     v                          v
[Student Role]            [Admin Role]
     |                          |
     v                          v
+-------------------+      +-------------------+
| Student Dashboard |      |  Admin Dashboard  |
| - View Profile    |      | - Manage Students |
| - My Events       |      | - Manage Events   |
| - Notifications   |      | - Audit Logs      |
+-------------------+      +-------------------+
```

---

## 5. Answers to Key Lab Questions

### Q1: What is a URL and what are the parts of a URL?
A **URL (Uniform Resource Locator)** is the global web address used to locate and access a resource on the internet.
**Parts of a URL:**
Example: `https://www.studenthub.edu:8080/events/view.php?category=tech#schedule`
1. **Scheme / Protocol (`https://`):** Specifies the protocol used to communicate (e.g., HTTP, HTTPS).
2. **Subdomain (`www.`):** Sub-division of the main domain.
3. **Domain Name (`studenthub.edu`):** Human-readable name pointing to the web server IP address.
4. **Port (`:8080`):** Communication port on the server (default is 80 for HTTP, 443 for HTTPS).
5. **Path (`/events/view.php`):** The exact path to the file or resource on the server.
6. **Query String (`?category=tech`):** Key-value parameters passed to the server for dynamic processing.
7. **Fragment / Hash (`#schedule`):** An internal bookmark pointing to a specific section within the HTML page.

### Q2: How is an HTML file processed in a web browser?
When a browser receives an HTML document, it follows these steps:
1. **Parsing HTML:** The HTML parser reads the raw byte stream, converts it into characters, tokens, and builds the **DOM (Document Object Model)** tree.
2. **Parsing CSS:** External and internal CSS rules are parsed into the **CSSOM (CSS Object Model)** tree.
3. **Render Tree Construction:** The DOM and CSSOM are combined into a Render Tree, which contains only elements visible on the screen.
4. **Layout (Reflow):** The browser calculates the exact size and position of each node on the viewport.
5. **Painting (Rasterization):** Pixels are drawn on the screen (colors, borders, text, images).
6. **JavaScript Execution:** JS scripts run to manipulate the DOM/CSSOM dynamically.

### Q3: How will page navigation flow be managed among all HTML pages?
- Consistent `<header>` and `<nav>` bars are included on every page with hyperlinks (`href="page.html"`).
- Relative paths are used (e.g., `href="events.html"` or `href="../dashboard.html"`).
- Active navigation links are highlighted using a CSS class (`class="active"`).
- Breadcrumbs are provided on secondary pages (e.g., `Home > Events > TechFest 2026`).

### Q4: How will GitHub commits be maintained after each practical?
- Maintain a clear branching and commit message standard:
  ```bash
  git commit -m "feat(practical-01): project scope, sitemap, wireframes, and readme"
  git commit -m "feat(practical-02): semantic html5 structure for 11 pages"
  ```
- Each practical is stored in its own folder (`practical-01` to `practical-15`).
- After completing and testing each lab, stage changed files, commit with descriptive message, and push to GitHub repository.

---

## 6. Git & GitHub Setup Steps for Student
```bash
# 1. Initialize local repository in wdf folder
cd /d/wdf
git init

# 2. Add files to staging
git add practical-01/

# 3. Create initial commit
git commit -m "Initial commit: Practical 1 requirements and setup"

# 4. Link to GitHub repository
git branch -M main
git remote add origin https://github.com/your-username/wdf-studenthub.git

# 5. Push to GitHub
git push -u origin main
```
