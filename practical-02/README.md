# Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Develop static HTML5 skeletons for at least 10 StudentHub pages such as Home, About, Register, Login, Dashboard, Events, Profile, Contact, Admin, FAQ, and Feedback. Use semantic tags and accessibility-friendly structure.

---

## 2. Implemented Pages (11 Total)
1. `index.html` - Home page with hero intro, upcoming events, and notice board aside.
2. `about.html` - Overview of FTE CHARUSAT and StudentHub portal objectives.
3. `register.html` - Accessible student registration form with accessible labels.
4. `login.html` - Login form with role selection (Student/Admin).
5. `dashboard.html` - Student dashboard with semantic table for registered events.
6. `events.html` - Campus event directory with search form and article listings.
7. `profile.html` - Student profile view and edit form.
8. `contact.html` - Campus office address (`<address>`) and inquiry submission form.
9. `admin.html` - Admin statistics dashboard with semantic tables.
10. `faq.html` - Structured campus FAQ list with questions and answers.
11. `feedback.html` - Student feedback form with rating selection and category options.

---

## 3. Key Concepts Demonstrated
- **Semantic Structure:** Proper usage of `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, and `<footer>`.
- **Accessibility:** Form `<label for="...">` associations, `aria-label` navigation landmarks, `caption` and `scope="col"` on tables.
- **Interlinking:** Seamless cross-navigation between all 11 pages using relative links.
- **Clean Stylesheet:** Standard beginner CSS in `css/style.css`.

---

## 4. Key Questions & Answers
1. **Are semantic tags used properly?**  
   Yes. `<header>` contains branding, `<nav>` holds site menus, `<main>` encapsulates primary page content, `<section>` divides logical topics, `<article>` represents self-contained cards, `<aside>` displays notices, and `<footer>` contains copyright and contact info.
2. **Are form labels, headings, image alt text, and navigation accessible?**  
   Yes. All forms include explicit `<label for>` bindings, `required` indicators, `<nav>` carries `aria-label`, and tables feature proper headers.
3. **Is the page structure consistent?**  
   Yes, each page follows an identical top navigation and bottom footer layout.
