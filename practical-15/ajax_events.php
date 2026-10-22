<?php
$pageTitle = "Live AJAX Events Explorer";
$activeNav = "ajax";
require_once __DIR__ . "/includes/header.php";
?>

<div class="card">
  <h2>Live Asynchronous Event Explorer (Fetch API)</h2>
  <p style="color: #64748b; font-size: 13px; margin-bottom: 15px;">
    Demonstrates asynchronous REST data consumption via PHP JSON API without full page reloads.
  </p>

  <div style="display: flex; gap: 10px; margin-bottom: 20px;">
    <input type="text" id="ajaxSearch" placeholder="🔍 Instant search by keyword..." style="flex: 2;">
    <button type="button" id="refreshBtn" class="btn btn-primary">🔄 Reload Data</button>
  </div>

  <div id="ajaxStatus" style="font-size: 13px; color: #64748b; margin-bottom: 10px;">Loading data via Fetch API...</div>

  <table>
    <thead>
      <tr>
        <th style="width: 50px;">ID</th>
        <th>Event Name</th>
        <th>Category</th>
        <th>Event Date</th>
        <th>Venue</th>
        <th>Capacity</th>
      </tr>
    </thead>
    <tbody id="ajaxTableBody">
      <!-- Injected via JavaScript below -->
    </tbody>
  </table>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
  const tbody = document.getElementById("ajaxTableBody");
  const status = document.getElementById("ajaxStatus");
  const searchInput = document.getElementById("ajaxSearch");
  const refreshBtn = document.getElementById("refreshBtn");
  let cachedEvents = [];

  async function loadEvents() {
    status.textContent = "Fetching events from api_events.php...";
    try {
      const response = await fetch("api_events.php");
      const result = await response.json();
      if (result.status === "success") {
        cachedEvents = result.data;
        renderEvents(cachedEvents);
        status.textContent = `Showing ${cachedEvents.length} live events from database.`;
      } else {
        status.textContent = "Error: Failed to parse events data.";
      }
    } catch (err) {
      status.textContent = "Network error loading events API.";
    }
  }

  function renderEvents(items) {
    tbody.innerHTML = "";
    if (items.length === 0) {
      tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; color: #64748b;">No matching events found.</td></tr>';
      return;
    }

    items.forEach(ev => {
      const tr = document.createElement("tr");
      tr.innerHTML = `
        <td>#${ev.id}</td>
        <td><strong>${escapeStr(ev.title)}</strong></td>
        <td><span style="background: #e0f2fe; color: #0369a1; padding: 2px 6px; border-radius: 4px; font-size: 11px; font-weight: bold;">${escapeStr(ev.category)}</span></td>
        <td>${ev.event_date}</td>
        <td>${escapeStr(ev.venue)}</td>
        <td>${ev.max_seats} seats</td>
      `;
      tbody.appendChild(tr);
    });
  }

  function escapeStr(text) {
    return text ? String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : "";
  }

  searchInput.addEventListener("input", function () {
    const q = this.value.toLowerCase().trim();
    const filtered = cachedEvents.filter(e => 
      e.title.toLowerCase().includes(q) || 
      e.venue.toLowerCase().includes(q) || 
      e.category.toLowerCase().includes(q)
    );
    renderEvents(filtered);
    status.textContent = `Filtered to ${filtered.length} of ${cachedEvents.length} events.`;
  });

  refreshBtn.addEventListener("click", loadEvents);

  loadEvents();
});
</script>

<?php require_once __DIR__ . "/includes/footer.php"; ?>
