# Practical 4: JavaScript DOM Manipulation, Event Handling, and UI Interactivity

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Add dynamic UI components such as collapsible FAQ, modal popup, image/content slider, notification banner, hamburger menu, and light/dark theme switcher using JavaScript.

---

## 2. Dynamic Components Implemented
1. **Notification Banner:** Top alert bar with a dismiss `&times;` button that hides the banner via `banner.classList.add('hidden')`.
2. **Light / Dark Theme Switcher:** Toggles `.dark-theme` on `document.body` and persists user preference using `localStorage.setItem('studenthub_theme', 'dark')`. When the page reloads, the theme is automatically restored.
3. **Hamburger Navigation Menu:** Allows toggling the mobile menu on small screens via `navMenu.classList.toggle('open')`.
4. **Campus Highlights Slider:** Content slider with Previous/Next controls, circular dot indicators, and automatic slide wrapping using modulo arithmetic (`(index + 1) % slides.length`).
5. **Accessible Modal Popup:** Opens event rules overlay; can be closed via Close button, clicking outside the modal box on the backdrop, or pressing the `Escape` key on keyboard.
6. **Collapsible FAQ Accordion:** Click on questions to expand/collapse answers, toggles `+` and `−` state indicators, and smoothly closes adjacent accordion cards.

---

## 3. Answers to Key Lab Questions

### Q1: How are DOM elements selected and modified?
- Selected using `document.getElementById('id')`, `document.querySelector('.selector')`, and `document.querySelectorAll('.class')`.
- Modified using `element.classList.toggle()`, `element.classList.add()`, `element.classList.remove()`, and `element.textContent = '...'`.

### Q2: Are event listeners attached correctly?
- Event listeners are attached using `addEventListener('click', function(e) { ... })` and `addEventListener('keydown', function(e) { ... })` inside a `DOMContentLoaded` wrapper to ensure elements exist before binding.

### Q3: Is localStorage used for remembering theme choice?
- Yes. When the theme is toggled:
  ```javascript
  localStorage.setItem("studenthub_theme", "dark");
  ```
  On page load, the saved value is retrieved:
  ```javascript
  const savedTheme = localStorage.getItem("studenthub_theme");
  if (savedTheme === "dark") {
    document.body.classList.add("dark-theme");
  }
  ```

### Q4: Does interactivity improve usability without breaking accessibility?
- Yes. Modals include `role="dialog"` and `aria-modal="true"`. Buttons include clear labels or `aria-label` attributes, and keyboard listeners (`Escape` key) allow keyboard navigation.
