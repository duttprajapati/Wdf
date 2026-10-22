# Accessibility Checklist (WCAG 2.1 Basic Level Compliance)

This checklist evaluates the static HTML5 pages created for **Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure** in the StudentHub portal.

| Area | Checkpoint | Status | Implementation Details in StudentHub |
|---|---|---|---|
| **Semantic Tags** | Are `<header>`, `<nav>`, `<main>`, `<section>`, `<article>`, `<aside>`, `<footer>` used properly? | ✅ Compliant | Every page follows the standard semantic hierarchy without relying on unsemantic `<div>` soups. |
| **Form Labels** | Do all `<input>`, `<select>`, `<textarea>` elements have associated `<label>` tags with `for="..."` and matching `id`? | ✅ Compliant | Implemented across `register.html`, `login.html`, `contact.html`, and `feedback.html`. |
| **Required Indicators** | Are mandatory fields marked clearly? | ✅ Compliant | Form labels include `<span aria-hidden="true">*</span>` and the HTML5 `required` attribute. |
| **Heading Hierarchy** | Is there a logical `<h1>` to `<h3>` heading progression without skipping levels? | ✅ Compliant | `<h1>` in header, `<h2>` per section, and `<h3>` for cards/articles. |
| **Language Attribute** | Does `<html>` contain `lang="en"`? | ✅ Compliant | Present in all 11 HTML document declarations. |
| **Navigation Landmarking** | Does `<nav>` have `aria-label="Main Navigation"`? | ✅ Compliant | Added so screen readers can distinguish primary navigation from secondary pagination. |
| **Table Structure** | Do data tables use `<caption>`, `<thead>`, `<th>` with `scope="col"`, and `<tbody>`? | ✅ Compliant | Present in `dashboard.html` and `admin.html`. |
| **Color Contrast** | Is contrast between text and background readable? | ✅ Compliant | Navy blue header (`#003366`) on white text, dark charcoal text (`#333333`) on white background. |
| **Keyboard Accessibility** | Are all links and form controls focusable using Tab key? | ✅ Compliant | Standard native HTML elements retain natural focus order. |
