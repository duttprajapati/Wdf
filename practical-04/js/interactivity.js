/* ==============================================================
   Practical 4: JavaScript DOM Manipulation & Interactivity
   StudentHub Portal Interactive Components
   ============================================================== */

// Execute after DOM is fully loaded
document.addEventListener("DOMContentLoaded", function () {

  /* -----------------------------------------------------------
     1. Notification Banner Dismissal
  ----------------------------------------------------------- */
  const banner = document.getElementById("notificationBanner");
  const closeBannerBtn = document.getElementById("closeBannerBtn");

  if (closeBannerBtn && banner) {
    closeBannerBtn.addEventListener("click", function () {
      banner.classList.add("hidden");
    });
  }

  /* -----------------------------------------------------------
     2. Light / Dark Theme Switcher with localStorage
  ----------------------------------------------------------- */
  const themeToggleBtn = document.getElementById("themeToggleBtn");
  
  // Check if user previously selected a theme in localStorage
  const savedTheme = localStorage.getItem("studenthub_theme");
  if (savedTheme === "dark") {
    document.body.classList.add("dark-theme");
    if (themeToggleBtn) themeToggleBtn.textContent = "☀️ Light Mode";
  }

  if (themeToggleBtn) {
    themeToggleBtn.addEventListener("click", function () {
      document.body.classList.toggle("dark-theme");

      if (document.body.classList.contains("dark-theme")) {
        localStorage.setItem("studenthub_theme", "dark");
        themeToggleBtn.textContent = "☀️ Light Mode";
      } else {
        localStorage.setItem("studenthub_theme", "light");
        themeToggleBtn.textContent = "🌙 Dark Mode";
      }
    });
  }

  /* -----------------------------------------------------------
     3. Hamburger Navigation Menu for Mobile
  ----------------------------------------------------------- */
  const hamburgerBtn = document.getElementById("hamburgerBtn");
  const navMenu = document.getElementById("navMenu");

  if (hamburgerBtn && navMenu) {
    hamburgerBtn.addEventListener("click", function () {
      navMenu.classList.toggle("open");
    });
  }

  /* -----------------------------------------------------------
     4. Content / Announcements Slider
  ----------------------------------------------------------- */
  const slides = document.querySelectorAll(".slide");
  const prevBtn = document.getElementById("prevSlideBtn");
  const nextBtn = document.getElementById("nextSlideBtn");
  const dotsContainer = document.getElementById("sliderDots");
  let currentSlideIndex = 0;

  // Create indicator dots dynamically
  if (dotsContainer && slides.length > 0) {
    slides.forEach((_, idx) => {
      const dot = document.createElement("span");
      dot.classList.add("dot");
      if (idx === 0) dot.classList.add("active");
      dot.addEventListener("click", () => goToSlide(idx));
      dotsContainer.appendChild(dot);
    });
  }

  function showSlide(index) {
    slides.forEach((slide, idx) => {
      slide.classList.remove("active");
      if (idx === index) {
        slide.classList.add("active");
      }
    });

    const dots = document.querySelectorAll(".dot");
    dots.forEach((dot, idx) => {
      dot.classList.toggle("active", idx === index);
    });
  }

  function nextSlide() {
    currentSlideIndex = (currentSlideIndex + 1) % slides.length;
    showSlide(currentSlideIndex);
  }

  function prevSlide() {
    currentSlideIndex = (currentSlideIndex - 1 + slides.length) % slides.length;
    showSlide(currentSlideIndex);
  }

  function goToSlide(index) {
    currentSlideIndex = index;
    showSlide(currentSlideIndex);
  }

  if (nextBtn) nextBtn.addEventListener("click", nextSlide);
  if (prevBtn) prevBtn.addEventListener("click", prevSlide);

  /* -----------------------------------------------------------
     5. Collapsible FAQ Accordion
  ----------------------------------------------------------- */
  const accordionHeaders = document.querySelectorAll(".accordion-header");

  accordionHeaders.forEach(header => {
    header.addEventListener("click", function () {
      const content = this.nextElementSibling;
      const isAlreadyOpen = content.classList.contains("show");

      // Optional: Close other open accordion items
      document.querySelectorAll(".accordion-content").forEach(item => {
        item.classList.remove("show");
      });
      document.querySelectorAll(".accordion-icon").forEach(icon => {
        icon.textContent = "+";
      });

      // Toggle current accordion item
      if (!isAlreadyOpen) {
        content.classList.add("show");
        const icon = this.querySelector(".accordion-icon");
        if (icon) icon.textContent = "−";
      }
    });
  });

  /* -----------------------------------------------------------
     6. Modal Popup Window
  ----------------------------------------------------------- */
  const openModalBtn = document.getElementById("openModalBtn");
  const closeModalBtn = document.getElementById("closeModalBtn");
  const modalOverlay = document.getElementById("modalOverlay");

  function openModal() {
    if (modalOverlay) modalOverlay.classList.add("active");
  }

  function closeModal() {
    if (modalOverlay) modalOverlay.classList.remove("active");
  }

  if (openModalBtn) openModalBtn.addEventListener("click", openModal);
  if (closeModalBtn) closeModalBtn.addEventListener("click", closeModal);

  // Close when clicking outside modal box
  if (modalOverlay) {
    modalOverlay.addEventListener("click", function (event) {
      if (event.target === modalOverlay) {
        closeModal();
      }
    });
  }

  // Close when pressing the ESC key on keyboard
  document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" && modalOverlay && modalOverlay.classList.contains("active")) {
      closeModal();
    }
  });

});
