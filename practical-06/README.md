# Practical 6: Rendering External JSON Data using Fetch API, Search and Filter

**Course:** Web Development Frameworks (ITUE203)  
**Institution:** Faculty of Technology & Engineering (FTE), CHARUSAT  
**Semester:** 3rd Semester, 2026-27  

---

## 1. Problem Definition
Display event lists, student profiles, notices, or FAQs from external JSON files using Fetch API. Implement dynamic rendering, search, filter, sorting, and pagination.

---

## 2. Dataset Records Created
- `data/events.json` : 15 college events (Hack-CHARUSAT, Bootcamp, Cultural Gala, Badminton, etc.)
- `data/students.json` : 15 student profiles (Name, enrollment, course, year, email, GPA)
- `data/faqs.json` : 15 campus questions and answers across Admissions, Academic, Events, Campus.

---

## 3. JavaScript Array Methods Demonstrated

1. **`fetch()` + `async/await`**:
   Asynchronously fetches `.json` files from disk/server without page reloads:
   ```javascript
   const response = await fetch('data/events.json');
   const data = await response.json();
   ```
2. **`Array.prototype.filter()`**:
   Filters records based on user search keywords and category dropdown selection:
   ```javascript
   filtered = allData.filter(item => item.title.toLowerCase().includes(query));
   ```
3. **`Array.prototype.sort()` + `localeCompare()`**:
   Alphabetically sorts titles/names ascending or descending:
   ```javascript
   filtered.sort((a, b) => a.title.localeCompare(b.title));
   ```
4. **`Array.prototype.slice()` for Pagination**:
   Extracts exactly 5 items for the active page:
   ```javascript
   const pageSlice = filtered.slice((page - 1) * itemsPerPage, page * itemsPerPage);
   ```
5. **`Set` Data Structure**:
   Extracts unique categories dynamically to populate the `<select>` dropdown.

---

## 4. Answers to Key Lab Questions

### Q1: How is JSON fetched, parsed, and rendered?
- The modern `fetch()` API issues an HTTP GET request. The promise resolves to a Response object, and `.json()` parses the JSON text into native JavaScript objects/arrays. We iterate through this array and use `document.createElement()` and `appendChild()` to build the DOM cards.

### Q2: Which array methods are used?
- `.filter()` for search and category filtering.
- `.sort()` for alphabetical ordering.
- `.slice()` for extracting paginated chunks.
- `.forEach()` for iterating and rendering cards.

### Q3: How are loading and error states handled?
- Before the fetch finishes, a temporary `<div class="loading-box">` is rendered.
- If the fetch fails (e.g. file missing or HTTP error), the `catch (error)` block catches the exception and renders a clear error notification banner (`<div class="empty-box">`).
- If no results match search criteria, a helpful "No matching records found" message appears.
