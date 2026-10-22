<?php
$pageTitle = "About";
$activeNav = "about";
require_once __DIR__ . "/includes/header.php";
?>

<div class="card">
  <h2>About StudentHub &amp; FTE CHARUSAT</h2>
  <p>The <strong>StudentHub</strong> web application was designed and engineered as part of the Web Development Frameworks (ITUE203) course at the Faculty of Technology and Engineering (FTE), Charotar University of Science and Technology (CHARUSAT), Changa.</p>

  <h3 style="margin-top: 20px; color: #003366;">Project Scope &amp; Architecture:</h3>
  <p>StudentHub synthesizes all core concepts taught throughout the 3rd semester curriculum:</p>
  <ul style="padding-left: 20px; margin-top: 10px; font-size: 14px;">
    <li><strong>Frontend Engineering:</strong> Semantic HTML5 tags, WCAG accessibility standards, CSS Flexbox and Grid responsive layouts, and vanilla DOM manipulation.</li>
    <li><strong>Client-Side Validation:</strong> Regular expression parsing and real-time password strength algorithms.</li>
    <li><strong>Backend Engineering:</strong> Procedural PHP form processing, input sanitization, and session management with inactivity timeouts.</li>
    <li><strong>Relational Database:</strong> Normalized MySQL schema in 3NF with foreign keys, cascading constraints, and audit logging.</li>
    <li><strong>Asynchronous Operations:</strong> RESTful JSON API endpoints integrated via JavaScript Fetch API for seamless Single-Page Application (SPA) experiences.</li>
  </ul>
</div>

<div class="grid-2col">
  <div class="card">
    <h3>CHARUSAT Accreditation</h3>
    <p style="font-size: 14px; margin-top: 6px;">CHARUSAT is accredited with an prestigious <strong>'A+' Grade</strong> by NAAC. The university fosters innovation, entrepreneurship, and hands-on technological projects across disciplines.</p>
  </div>

  <div class="card">
    <h3>Developer Notes</h3>
    <p style="font-size: 14px; margin-top: 6px;">Developed by 3rd Semester Students under the mentorship of the Information Technology Department, Faculty of Technology &amp; Engineering.</p>
  </div>
</div>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
