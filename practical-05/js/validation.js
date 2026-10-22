/* ==============================================================
   Practical 5: Registration Form Validation Logic
   ============================================================== */

document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registrationForm");
  const successBanner = document.getElementById("successBanner");

  // Input Fields
  const nameInput = document.getElementById("fullName");
  const emailInput = document.getElementById("email");
  const mobileInput = document.getElementById("mobile");
  const passwordInput = document.getElementById("password");
  const confirmPassInput = document.getElementById("confirmPassword");
  const courseInput = document.getElementById("course");
  const termsInput = document.getElementById("terms");

  // Strength Bar Elements
  const strengthFill = document.getElementById("strengthFill");
  const strengthText = document.getElementById("strengthText");

  // Regular Expressions
  const nameRegex = /^[a-zA-Z\s]{3,50}$/;
  const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const mobileRegex = /^[6-9]\d{9}$/;

  /* Helper Functions to Show / Clear Errors */
  function showError(inputElement, errorElementId, message) {
    if (inputElement) {
      inputElement.classList.add("invalid");
      inputElement.classList.remove("valid");
    }
    const errorElem = document.getElementById(errorElementId);
    if (errorElem) {
      errorElem.textContent = message;
      errorElem.classList.add("visible");
    }
    return false;
  }

  function clearError(inputElement, errorElementId) {
    if (inputElement) {
      inputElement.classList.remove("invalid");
      inputElement.classList.add("valid");
    }
    const errorElem = document.getElementById(errorElementId);
    if (errorElem) {
      errorElem.textContent = "";
      errorElem.classList.remove("visible");
    }
    return true;
  }

  /* 1. Name Validation */
  function validateName() {
    const val = nameInput.value.trim();
    if (val === "") {
      return showError(nameInput, "nameError", "Full Name is required.");
    }
    if (!nameRegex.test(val)) {
      return showError(nameInput, "nameError", "Name must be at least 3 letters and contain only alphabets and spaces.");
    }
    return clearError(nameInput, "nameError");
  }

  /* 2. Email Validation */
  function validateEmail() {
    const val = emailInput.value.trim();
    if (val === "") {
      return showError(emailInput, "emailError", "Email address is required.");
    }
    if (!emailRegex.test(val)) {
      return showError(emailInput, "emailError", "Enter a valid email address (e.g., student@domain.com).");
    }
    return clearError(emailInput, "emailError");
  }

  /* 3. Mobile Number Validation */
  function validateMobile() {
    const val = mobileInput.value.trim();
    if (val === "") {
      return showError(mobileInput, "mobileError", "Mobile number is required.");
    }
    if (!mobileRegex.test(val)) {
      return showError(mobileInput, "mobileError", "Mobile number must be exactly 10 digits starting with 6, 7, 8, or 9.");
    }
    return clearError(mobileInput, "mobileError");
  }

  /* 4. Password Strength & Validation */
  function checkPasswordStrength(password) {
    let score = 0;
    if (password.length >= 8) score++;
    if (/[A-Z]/.test(password)) score++;
    if (/[a-z]/.test(password)) score++;
    if (/[0-9]/.test(password)) score++;
    if (/[^A-Za-z0-9]/.test(password)) score++;

    strengthFill.className = "strength-fill";

    if (password.length === 0) {
      strengthFill.style.width = "0%";
      strengthText.textContent = "";
      return score;
    }

    if (score <= 2) {
      strengthFill.classList.add("weak");
      strengthText.textContent = "Strength: Weak (Needs 8+ chars, letters & numbers)";
      strengthText.style.color = "#e53e3e";
    } else if (score === 3 || score === 4) {
      strengthFill.classList.add("medium");
      strengthText.textContent = "Strength: Medium (Good, try adding special symbols)";
      strengthText.style.color = "#dd6b20";
    } else {
      strengthFill.classList.add("strong");
      strengthText.textContent = "Strength: Strong (Secure password)";
      strengthText.style.color = "#38a169";
    }

    return score;
  }

  function validatePassword() {
    const val = passwordInput.value;
    if (val === "") {
      return showError(passwordInput, "passwordError", "Password is required.");
    }
    if (val.length < 8) {
      return showError(passwordInput, "passwordError", "Password must be at least 8 characters long.");
    }
    return clearError(passwordInput, "passwordError");
  }

  /* 5. Confirm Password Validation */
  function validateConfirmPassword() {
    const val = confirmPassInput.value;
    if (val === "") {
      return showError(confirmPassInput, "confirmPasswordError", "Please confirm your password.");
    }
    if (val !== passwordInput.value) {
      return showError(confirmPassInput, "confirmPasswordError", "Passwords do not match.");
    }
    return clearError(confirmPassInput, "confirmPasswordError");
  }

  /* 6. Course Selection Validation */
  function validateCourse() {
    if (courseInput.value === "") {
      return showError(courseInput, "courseError", "Please select your course.");
    }
    return clearError(courseInput, "courseError");
  }

  /* 7. Year Validation (Radio) */
  function validateYear() {
    const selectedYear = document.querySelector('input[name="year"]:checked');
    const errorElem = document.getElementById("yearError");
    if (!selectedYear) {
      errorElem.textContent = "Please select your study year.";
      errorElem.classList.add("visible");
      return false;
    }
    errorElem.textContent = "";
    errorElem.classList.remove("visible");
    return true;
  }

  /* 8. Gender Validation (Radio) */
  function validateGender() {
    const selectedGender = document.querySelector('input[name="gender"]:checked');
    const errorElem = document.getElementById("genderError");
    if (!selectedGender) {
      errorElem.textContent = "Please select your gender.";
      errorElem.classList.add("visible");
      return false;
    }
    errorElem.textContent = "";
    errorElem.classList.remove("visible");
    return true;
  }

  /* 9. Terms Acceptance */
  function validateTerms() {
    const errorElem = document.getElementById("termsError");
    if (!termsInput.checked) {
      errorElem.textContent = "You must accept the terms and conditions.";
      errorElem.classList.add("visible");
      return false;
    }
    errorElem.textContent = "";
    errorElem.classList.remove("visible");
    return true;
  }

  /* Live Real-Time Event Listeners */
  nameInput.addEventListener("input", validateName);
  emailInput.addEventListener("input", validateEmail);
  mobileInput.addEventListener("input", validateMobile);
  
  passwordInput.addEventListener("input", function () {
    checkPasswordStrength(passwordInput.value);
    validatePassword();
    if (confirmPassInput.value !== "") validateConfirmPassword();
  });

  confirmPassInput.addEventListener("input", validateConfirmPassword);
  courseInput.addEventListener("change", validateCourse);
  termsInput.addEventListener("change", validateTerms);

  /* Form Submit Event */
  form.addEventListener("submit", function (event) {
    event.preventDefault(); // Stop default submission

    const isNameValid = validateName();
    const isEmailValid = validateEmail();
    const isMobileValid = validateMobile();
    const isPassValid = validatePassword();
    const isConfirmValid = validateConfirmPassword();
    const isCourseValid = validateCourse();
    const isYearValid = validateYear();
    const isGenderValid = validateGender();
    const isTermsValid = validateTerms();

    if (
      isNameValid &&
      isEmailValid &&
      isMobileValid &&
      isPassValid &&
      isConfirmValid &&
      isCourseValid &&
      isYearValid &&
      isGenderValid &&
      isTermsValid
    ) {
      successBanner.textContent = "Registration Successful! Welcome to StudentHub, " + nameInput.value.trim() + "!";
      successBanner.style.display = "block";
      form.reset();
      strengthFill.className = "strength-fill";
      strengthText.textContent = "";

      // Remove validation green borders
      document.querySelectorAll(".valid").forEach(el => el.classList.remove("valid"));
      window.scrollTo({ top: 0, behavior: "smooth" });
    } else {
      successBanner.style.display = "none";
    }
  });
});
