/* ==============================================================
   Practical 6: Fetch API, Search, Filter, Sort & Pagination
   ============================================================== */

let currentDatasetType = "events"; // Default active tab: 'events', 'students', or 'faqs'
let allData = [];
let filteredData = [];
let currentPage = 1;
const itemsPerPage = 5;

// DOM Elements
const contentList = document.getElementById("contentList");
const searchInput = document.getElementById("searchInput");
const filterSelect = document.getElementById("filterSelect");
const sortSelect = document.getElementById("sortSelect");
const prevPageBtn = document.getElementById("prevPageBtn");
const nextPageBtn = document.getElementById("nextPageBtn");
const pageInfo = document.getElementById("pageInfo");

// 1. Fetch JSON Data using Fetch API
async function loadData(datasetType) {
  currentDatasetType = datasetType;
  currentPage = 1;
  contentList.innerHTML = `<div class="loading-box">⏳ Loading data from ${datasetType}.json...</div>`;

  try {
    const response = await fetch(`data/${datasetType}.json`);
    if (!response.ok) {
      throw new Error(`Failed to load data: HTTP ${response.status}`);
    }
    allData = await response.json();
    populateCategoryDropdown();
    applyFilters();
  } catch (error) {
    contentList.innerHTML = `<div class="empty-box">❌ Error fetching JSON data: ${error.message}</div>`;
  }
}

// 2. Populate Dynamic Category Dropdown
function populateCategoryDropdown() {
  filterSelect.innerHTML = '<option value="ALL">All Categories</option>';
  const categories = new Set();

  allData.forEach(item => {
    if (item.category) categories.add(item.category);
    if (item.course) categories.add(item.course);
  });

  categories.forEach(cat => {
    const opt = document.createElement("option");
    opt.value = cat;
    opt.textContent = cat;
    filterSelect.appendChild(opt);
  });
}

// 3. Search, Filter & Sort Processing
function applyFilters() {
  const query = searchInput.value.toLowerCase().trim();
  const selectedCat = filterSelect.value;
  const sortMode = sortSelect.value;

  // Step A: Search filter
  filteredData = allData.filter(item => {
    const nameOrTitle = (item.title || item.name || item.question || "").toLowerCase();
    const details = (item.venue || item.email || item.answer || "").toLowerCase();
    return nameOrTitle.includes(query) || details.includes(query);
  });

  // Step B: Category dropdown filter
  if (selectedCat !== "ALL") {
    filteredData = filteredData.filter(item => {
      return (item.category === selectedCat) || (item.course === selectedCat);
    });
  }

  // Step C: Sorting
  filteredData.sort((a, b) => {
    const textA = (a.title || a.name || a.question || "").toLowerCase();
    const textB = (b.title || b.name || b.question || "").toLowerCase();

    if (sortMode === "asc") {
      return textA.localeCompare(textB);
    } else if (sortMode === "desc") {
      return textB.localeCompare(textA);
    }
    return 0; // default order
  });

  renderPage();
}

// 4. Render Current Page Items
function renderPage() {
  contentList.innerHTML = "";

  if (filteredData.length === 0) {
    contentList.innerHTML = '<div class="empty-box">🔍 No matching records found. Try modifying your search or filter.</div>';
    pageInfo.textContent = "Page 0 of 0";
    prevPageBtn.disabled = true;
    nextPageBtn.disabled = true;
    return;
  }

  const totalPages = Math.ceil(filteredData.length / itemsPerPage);
  if (currentPage > totalPages) currentPage = totalPages;

  const startIndex = (currentPage - 1) * itemsPerPage;
  const currentSlice = filteredData.slice(startIndex, startIndex + itemsPerPage);

  currentSlice.forEach(item => {
    const card = document.createElement("article");
    card.classList.add("card-item");

    if (currentDatasetType === "events") {
      card.innerHTML = `
        <span class="badge">${item.category}</span>
        <h3>${item.title}</h3>
        <p class="card-meta">📅 Date: ${item.date} | 📍 Venue: ${item.venue} | 🪑 Available Seats: ${item.seats}</p>
      `;
    } else if (currentDatasetType === "students") {
      card.innerHTML = `
        <span class="badge">${item.course}</span>
        <h3>${item.name} (${item.enrollment})</h3>
        <p class="card-meta">🎓 Year: ${item.year} | ✉️ Email: ${item.email} | 📈 GPA: ${item.gpa}</p>
      `;
    } else if (currentDatasetType === "faqs") {
      card.innerHTML = `
        <span class="badge">${item.category}</span>
        <h3>Q: ${item.question}</h3>
        <p style="margin-top: 8px; color: #475569;"><strong>A:</strong> ${item.answer}</p>
      `;
    }

    contentList.appendChild(card);
  });

  // Update Pagination Controls
  pageInfo.textContent = `Page ${currentPage} of ${totalPages} (Total: ${filteredData.length} records)`;
  prevPageBtn.disabled = currentPage === 1;
  nextPageBtn.disabled = currentPage === totalPages;
}

// 5. Event Listeners
searchInput.addEventListener("input", () => {
  currentPage = 1;
  applyFilters();
});

filterSelect.addEventListener("change", () => {
  currentPage = 1;
  applyFilters();
});

sortSelect.addEventListener("change", applyFilters);

prevPageBtn.addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    renderPage();
  }
});

nextPageBtn.addEventListener("click", () => {
  const totalPages = Math.ceil(filteredData.length / itemsPerPage);
  if (currentPage < totalPages) {
    currentPage++;
    renderPage();
  }
});

// Tab Switchers
document.querySelectorAll(".tab-btn").forEach(button => {
  button.addEventListener("click", function () {
    document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
    this.classList.add("active");
    searchInput.value = "";
    loadData(this.dataset.type);
  });
});

// Initial Load
document.addEventListener("DOMContentLoaded", () => {
  loadData("events");
});
