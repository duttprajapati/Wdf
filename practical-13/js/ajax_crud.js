/* ==============================================================
   Practical 13: Asynchronous CRUD using Fetch API (No Page Reload)
   ============================================================== */

document.addEventListener("DOMContentLoaded", function () {
  const eventForm = document.getElementById("eventForm");
  const formTitle = document.getElementById("formTitle");
  const eventIdInput = document.getElementById("eventId");
  const titleInput = document.getElementById("title");
  const categoryInput = document.getElementById("category");
  const dateInput = document.getElementById("event_date");
  const venueInput = document.getElementById("venue");
  const seatsInput = document.getElementById("max_seats");
  const descInput = document.getElementById("description");
  const submitBtn = document.getElementById("submitBtn");
  const cancelBtn = document.getElementById("cancelBtn");
  const eventsTableBody = document.getElementById("eventsTableBody");
  const toast = document.getElementById("toast");

  // Helper: Display Toast Notification
  function showToast(message, isSuccess = true) {
    toast.textContent = message;
    toast.className = isSuccess ? "toast-success" : "toast-error";
    toast.style.display = "block";
    setTimeout(() => {
      toast.style.display = "none";
    }, 3500);
  }

  // 1. READ: Fetch all events via GET and render in table
  async function fetchEvents() {
    eventsTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 20px;">⏳ Loading events...</td></tr>';
    
    try {
      const response = await fetch("api/events.php");
      const result = await response.json();

      if (result.success && Array.isArray(result.data)) {
        if (result.data.length === 0) {
          eventsTableBody.innerHTML = '<tr><td colspan="6" style="text-align:center; padding: 20px; color:#64748b;">No events currently scheduled.</td></tr>';
          return;
        }

        eventsTableBody.innerHTML = "";
        result.data.forEach(event => {
          const row = document.createElement("tr");
          row.id = `event-row-${event.id}`;
          row.innerHTML = `
            <td>#${event.id}</td>
            <td><strong>${escapeHtml(event.title)}</strong><br><small style="color:#64748b;">${escapeHtml(event.venue)}</small></td>
            <td><span class="badge">${escapeHtml(event.category)}</span></td>
            <td>${event.event_date}</td>
            <td>${event.max_seats}</td>
            <td style="white-space: nowrap;">
              <button class="btn btn-sm-edit" onclick="startEditEvent(${event.id})">Edit</button>
              <button class="btn btn-sm-del" onclick="deleteEventRecord(${event.id})">Delete</button>
            </td>
          `;
          eventsTableBody.appendChild(row);
        });
      } else {
        showToast(result.message || "Failed to load events", false);
      }
    } catch (err) {
      showToast("Error connecting to Events API", false);
    }
  }

  // 2. CREATE / UPDATE Form Handler
  eventForm.addEventListener("submit", async function (e) {
    e.preventDefault();

    const id = eventIdInput.value;
    const payload = {
      title: titleInput.value.trim(),
      category: categoryInput.value,
      event_date: dateInput.value,
      venue: venueInput.value.trim(),
      max_seats: seatsInput.value,
      description: descInput.value.trim()
    };

    try {
      let response;
      if (id) {
        // UPDATE Existing Record
        payload.id = id;
        payload.action = "update";
        response = await fetch("api/events.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        });
      } else {
        // CREATE New Record
        response = await fetch("api/events.php", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        });
      }

      const result = await response.json();
      if (result.success) {
        showToast(result.message, true);
        resetForm();
        fetchEvents(); // Refresh data asynchronously
      } else {
        showToast(result.message, false);
      }
    } catch (err) {
      showToast("Network error submitting form", false);
    }
  });

  // 3. EDIT: Pre-populate form with selected event data
  window.startEditEvent = async function (id) {
    try {
      const response = await fetch(`api/events.php?id=${id}`);
      const result = await response.json();

      if (result.success && result.data) {
        const ev = result.data;
        eventIdInput.value = ev.id;
        titleInput.value = ev.title;
        categoryInput.value = ev.category;
        dateInput.value = ev.event_date;
        venueInput.value = ev.venue;
        seatsInput.value = ev.max_seats;
        descInput.value = ev.description;

        formTitle.textContent = `Edit Event #${ev.id}`;
        submitBtn.textContent = "Update Event";
        submitBtn.style.background = "#f59e0b";
        cancelBtn.style.display = "block";
        window.scrollTo({ top: 0, behavior: "smooth" });
      }
    } catch (err) {
      showToast("Could not load event details", false);
    }
  };

  // 4. DELETE: Asynchronously remove event without reload
  window.deleteEventRecord = async function (id) {
    if (!confirm(`Are you sure you want to delete event #${id}?`)) {
      return;
    }

    try {
      const response = await fetch("api/events.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ id: id, action: "delete" })
      });

      const result = await response.json();
      if (result.success) {
        showToast(result.message, true);
        // Remove row immediately from DOM
        const targetRow = document.getElementById(`event-row-${id}`);
        if (targetRow) targetRow.remove();
      } else {
        showToast(result.message, false);
      }
    } catch (err) {
      showToast("Error processing delete request", false);
    }
  };

  // Cancel Edit Mode
  cancelBtn.addEventListener("click", resetForm);

  function resetForm() {
    eventForm.reset();
    eventIdInput.value = "";
    formTitle.textContent = "Create New Event";
    submitBtn.textContent = "Save Event";
    submitBtn.style.background = "#0056b3";
    cancelBtn.style.display = "none";
  }

  function escapeHtml(text) {
    return text ? String(text).replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;") : "";
  }

  // Initial Load
  fetchEvents();
});
