# Practical 3: Responsive UI Design using CSS2.0/CSS3.0, CSS Grid and Flexbox

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Design responsive layouts for Home, About, Registration, Dashboard, and Events pages using CSS Grid and Flexbox. Ensure mobile-first responsiveness across phones, tablets, and desktops.

---

## 2. Responsive Architecture & Techniques Used

### CSS Flexbox (1-Dimensional Layouts)
- **Header & Navbar:** `.site-header` and `.navbar` use flexbox to automatically align brand logos and navigation items. On mobile devices, menu links stack vertically; on wider screens (`min-width: 768px`), they switch to horizontal alignment.
- **Form Rows:** Registration forms use flexbox rows (`.form-row-2col`) that display two input fields side-by-side on desktop while neatly stacking on mobile.
- **Content + Sidebar:** Two-column content layout (`.content-layout`) displays main text (2/3 width) and sidebar (1/3 width) on desktop, collapsing to 1 column on mobile.

### CSS Grid (2-Dimensional Layouts)
- **Events Directory & Cards:** Uses CSS Grid (`grid-template-columns: 1fr` on mobile, `repeat(2, 1fr)` on tablet, and `repeat(3, 1fr)` on desktop).
- **Dashboard Metric Cards:** Uses auto-fitting grid (`grid-template-columns: repeat(auto-fit, minmax(200px, 1fr))`) to adapt dynamically to screen size without explicit breakpoints.

---

## 3. Answers to Key Lab Questions

### Q1: How does the layout adapt for mobile, tablet, and desktop screens?
- **Mobile (< 600px):** Single column stream. Navigation links stack vertically. Cards occupy 100% width for easy touch interaction.
- **Tablet (600px - 991px):** Two columns for event cards and form input fields. Nav wraps cleanly.
- **Desktop (>= 992px):** Three columns for event cards, side-by-side main content and sidebar, horizontal header alignment.

### Q2: Which layout technique is used and why?
- **Flexbox** was chosen for linear/one-dimensional UI components (navigation bar, button groupings, header alignment, form field rows) because it excels at content distribution along a single axis.
- **CSS Grid** was chosen for two-dimensional multi-item layouts (event cards gallery and dashboard metric tiles) because it provides precise control over both rows and columns simultaneously.

### Q3: Are typography, spacing, colors, and contrast readable?
- Yes. Deep navy blue (`#1a365d`) header and text contrast against off-white background (`#f4f7f6`). Accent buttons use accessible burnt orange (`#ed8936`). Line heights are set to `1.6` for optimal readability.

### Q4: Is the CSS organized without unnecessary inline styles?
- Yes. All rules are centralized in `css/responsive.css` using meaningful class names (`.grid-cards`, `.metric-card`, `.form-row-2col`).
