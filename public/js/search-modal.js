// ==========================================
// SEARCH MODAL JAVASCRIPT - PT SURABAYA LAS
// Updated with Proyek & Kontak support
// Categories: beranda, tentang-kami, catalog, proyek, kontak
// ==========================================

let currentFilter = "all";
let searchTimeout = null;

// ========== MODAL CONTROLS ==========

/**
 * Open search modal
 */
function openSearchModal() {
    const modal = document.getElementById("searchModalOverlay");
    const input = document.getElementById("searchModalInput");
    
    modal.style.display = "flex";
    document.body.style.overflow = "hidden";
    
    // Focus input after modal animation
    setTimeout(() => {
        input.focus();
        input.select(); // Select existing text if any
    }, 150);
    
    console.log("Search modal opened");
}

/**
 * Close search modal
 */
function closeSearchModal() {
    const modal = document.getElementById("searchModalOverlay");
    const input = document.getElementById("searchModalInput");
    
    modal.style.display = "none";
    document.body.style.overflow = "auto";
    
    // Reset search
    input.value = "";
    currentFilter = "all";
    
    // Reset active tab
    document.querySelectorAll(".search-filter-tab").forEach((tab) => {
        tab.classList.toggle("active", tab.dataset.category === "all");
    });
    
    showEmptyState();
    console.log("Search modal closed");
}

// ========== FILTER CONTROLS ==========

/**
 * Filter search by category
 * @param {string} category - Category to filter (all, beranda, tentang-kami, catalog, proyek, kontak)
 */
function filterSearchCategory(category) {
    currentFilter = category;
    
    // Update active tab styling
    document.querySelectorAll(".search-filter-tab").forEach((tab) => {
        tab.classList.toggle("active", tab.dataset.category === category);
    });
    
    // Re-run search with current query
    const query = document.getElementById("searchModalInput").value;
    performSearch(query);
    
    console.log("Filter changed to:", category);
}

// ========== SEARCH LOGIC ==========

/**
 * Perform search with debouncing
 * @param {string} query - Search query
 */
function performSearch(query) {
    const resultsContainer = document.getElementById("searchModalResults");
    
    // Clear previous timeout
    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }
    
    // Show empty state if query is empty
    if (!query.trim()) {
        showEmptyState();
        return;
    }
    
    // Show loading state
    resultsContainer.innerHTML = `
        <div class="search-loading">
            <div class="search-loading-spinner"></div>
            <p>Mencari...</p>
        </div>
    `;
    
    // Debounce search (wait 300ms after user stops typing)
    searchTimeout = setTimeout(() => {
        const results = searchInData(query.toLowerCase());
        displayResults(results, query);
    }, 300);
}

/**
 * Search in data with improved matching
 * @param {string} query - Lowercase search query
 * @returns {Array} Filtered results
 */
function searchInData(query) {
    return searchData.filter((item) => {
        // Filter by category
        const matchCategory =
            currentFilter === "all" || 
            item.category === currentFilter ||
            (currentFilter === "catalog" && item.category === "catalog"); // Support subcategories
        
        // Search in title, description, and tags
        const matchTitle = item.title.toLowerCase().includes(query);
        const matchDescription = item.description.toLowerCase().includes(query);
        const matchTags = item.tags?.some(tag => tag.toLowerCase().includes(query)) || false;
        
        const matchQuery = matchTitle || matchDescription || matchTags;
        
        return matchCategory && matchQuery;
    });
}

// ========== DISPLAY RESULTS ==========

/**
 * Display search results
 * @param {Array} results - Search results
 * @param {string} query - Original search query
 */
function displayResults(results, query) {
    const resultsContainer = document.getElementById("searchModalResults");

    // No results found
    if (results.length === 0) {
        resultsContainer.innerHTML = `
            <div class="search-no-results">
                <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="11" cy="11" r="8"></circle>
                    <path d="M21 21l-4.35-4.35"></path>
                </svg>
                <h3>Tidak ada hasil untuk "${escapeHtml(query)}"</h3>
                <p>Coba kata kunci lain atau ubah kategori filter</p>
                <div class="search-suggestions">
                    <p><strong>Saran:</strong></p>
                    <ul>
                        <li>Gunakan kata kunci yang lebih umum</li>
                        <li>Periksa ejaan kata kunci</li>
                        <li>Coba pilih kategori "Semua"</li>
                    </ul>
                </div>
            </div>
        `;
        return;
    }

    // Group results by category
    const grouped = {};
    results.forEach((item) => {
        const category = item.category;
        if (!grouped[category]) {
            grouped[category] = [];
        }
        grouped[category].push(item);
    });

    // Category display names (UPDATED)
    const categoryNames = {
        "beranda": "Beranda",
        "tentang-kami": "Tentang Kami",
        "catalog": "Catalog Produk",
        "proyek": "Galeri Proyek",
        "kontak": "Informasi Kontak"
    };

    // Build HTML
    let html = `<div class="search-results-info">Ditemukan <strong>${results.length}</strong> hasil untuk "<strong>${escapeHtml(query)}</strong>"</div>`;
    
    // Sort categories (prioritize: beranda, tentang-kami, catalog, proyek, kontak)
    const categoryOrder = ["beranda", "tentang-kami", "catalog", "proyek", "kontak"];
    const sortedCategories = Object.keys(grouped).sort((a, b) => {
        return categoryOrder.indexOf(a) - categoryOrder.indexOf(b);
    });
    
    sortedCategories.forEach((category) => {
        const categoryName = categoryNames[category] || category;
        const items = grouped[category];
        
        html += `
            <div class="search-result-section">
                <div class="search-section-title">
                    <span class="category-icon">${items[0].icon || '📄'}</span>
                    ${categoryName}
                    <span class="result-count">${items.length}</span>
                </div>
        `;
        
        items.forEach((item) => {
            const isExternal = item.url.startsWith('http') || item.url.startsWith('mailto:');
            const target = isExternal ? 'target="_blank" rel="noopener"' : '';
            
            html += `
                <a href="${item.url}" class="search-result-item" ${target}>
                    <div class="search-result-icon">${item.icon || '📄'}</div>
                    <div class="search-result-content">
                        <div class="search-result-title">
                            ${highlightText(item.title, query)}
                        </div>
                        <div class="search-result-description">
                            ${highlightText(truncateText(item.description, 120), query)}
                        </div>
                        ${item.tags ? `
                            <div class="search-result-tags">
                                ${item.tags.slice(0, 3).map(tag => 
                                    `<span class="tag">${tag}</span>`
                                ).join('')}
                            </div>
                        ` : ''}
                    </div>
                    <div class="search-result-badge">${categoryName}</div>
                </a>
            `;
        });
        
        html += "</div>";
    });
    
    resultsContainer.innerHTML = html;
    
    console.log(`Displayed ${results.length} results for "${query}"`);
}

// ========== HELPER FUNCTIONS ==========

/**
 * Highlight matching text in search results
 * @param {string} text - Text to highlight
 * @param {string} query - Search query
 * @returns {string} HTML with highlighted text
 */
function highlightText(text, query) {
    if (!query.trim()) return escapeHtml(text);
    
    // Escape special regex characters in query
    const escapedQuery = query.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
    const regex = new RegExp(`(${escapedQuery})`, 'gi');
    
    return escapeHtml(text).replace(regex, '<mark class="search-highlight">$1</mark>');
}

/**
 * Truncate text to specified length
 * @param {string} text - Text to truncate
 * @param {number} maxLength - Maximum length
 * @returns {string} Truncated text
 */
function truncateText(text, maxLength) {
    if (text.length <= maxLength) return text;
    return text.substring(0, maxLength).trim() + '...';
}

/**
 * Escape HTML to prevent XSS
 * @param {string} text - Text to escape
 * @returns {string} Escaped text
 */
function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

/**
 * Show empty state (no search query)
 */
function showEmptyState() {
    const resultsContainer = document.getElementById("searchModalResults");
    
    resultsContainer.innerHTML = `
        <div class="search-empty-state">
            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"></circle>
                <path d="M21 21l-4.35-4.35"></path>
            </svg>
            <h3>Mulai Pencarian Anda</h3>
            <p>Ketik kata kunci untuk mencari layanan, produk, atau informasi</p>
            <div class="search-examples">
                <p><strong>Contoh pencarian:</strong></p>
                <div class="example-tags">
                    <span class="example-tag" onclick="searchExample('kanopi')">kanopi</span>
                    <span class="example-tag" onclick="searchExample('las')">las</span>
                    <span class="example-tag" onclick="searchExample('cat')">cat</span>
                    <span class="example-tag" onclick="searchExample('baut')">baut</span>
                    <span class="example-tag" onclick="searchExample('kontak')">kontak</span>
                </div>
            </div>
        </div>
    `;
}

/**
 * Example search - fill input with example query
 * @param {string} query - Example query
 */
function searchExample(query) {
    const input = document.getElementById("searchModalInput");
    input.value = query;
    input.focus();
    performSearch(query);
}

// ========== EVENT LISTENERS ==========

/**
 * Close modal on ESC key
 */
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") {
        closeSearchModal();
    }
    
    // Open search modal with Ctrl+K or Cmd+K
    if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
        e.preventDefault();
        openSearchModal();
    }
});

/**
 * Close modal when clicking outside
 */
document.getElementById("searchModalOverlay")?.addEventListener("click", (e) => {
    if (e.target.id === "searchModalOverlay") {
        closeSearchModal();
    }
});

/**
 * Search as user types
 */
document.getElementById("searchModalInput")?.addEventListener("input", (e) => {
    performSearch(e.target.value);
});

/**
 * Handle Enter key in search input
 */
document.getElementById("searchModalInput")?.addEventListener("keydown", (e) => {
    if (e.key === "Enter") {
        e.preventDefault();
        
        // If there are results, click the first result
        const firstResult = document.querySelector(".search-result-item");
        if (firstResult) {
            firstResult.click();
        }
    }
});

// ========== INITIALIZATION ==========

/**
 * Initialize search modal on page load
 */
document.addEventListener("DOMContentLoaded", function() {
    console.log("Search modal initialized");
    console.log("Total search items:", searchData.length);
    console.log("Press Ctrl+K or Cmd+K to open search");
    
    // Show empty state initially
    showEmptyState();
});

// ========== EXPORTS (if needed) ==========
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        openSearchModal,
        closeSearchModal,
        filterSearchCategory,
        performSearch
    };
}