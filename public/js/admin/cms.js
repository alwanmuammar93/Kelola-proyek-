// ============================================
// CMS ADMIN - TAB SWITCHING
// File: public/js/admin/cms.js
// ============================================

/**
 * Switch between different CMS tabs
 * @param {string} tabName - Name of the tab to activate
 */
function switchTab(tabName) {
    console.log("Switching to tab:", tabName);

    // Get all tab buttons and tab contents
    const tabButtons = document.querySelectorAll(".cms-tab-btn");
    const tabContents = document.querySelectorAll(".cms-tab-content");

    // Remove active class from all buttons and contents
    tabButtons.forEach((btn) => {
        btn.classList.remove("active");
    });

    tabContents.forEach((content) => {
        content.classList.remove("active");
    });

    // Add active class to selected tab button
    const activeButton = document.querySelector(
        `.cms-tab-btn[data-tab="${tabName}"]`
    );
    if (activeButton) {
        activeButton.classList.add("active");
        console.log("Tab button activated:", tabName);
    } else {
        console.error("Tab button not found:", tabName);
    }

    // Show selected tab content
    const activeContent = document.getElementById(`tab-${tabName}`);
    if (activeContent) {
        activeContent.classList.add("active");
        console.log("Tab content shown:", tabName);
    } else {
        console.error("Tab content not found:", tabName);
    }

    // Scroll to top of content area smoothly
    const contentContainer = document.querySelector(".cms-content-container");
    if (contentContainer) {
        contentContainer.scrollIntoView({
            behavior: "smooth",
            block: "start",
        });
    }

    // Store active tab in sessionStorage
    try {
        sessionStorage.setItem("cms_active_tab", tabName);
    } catch (e) {
        console.warn("SessionStorage not available:", e);
    }
}

/**
 * Initialize tabs on page load
 */
document.addEventListener("DOMContentLoaded", function () {
    console.log("CMS Tab System Initialized");

    // Restore last active tab from sessionStorage
    try {
        const lastActiveTab = sessionStorage.getItem("cms_active_tab");
        if (lastActiveTab) {
            switchTab(lastActiveTab);
            console.log("Restored last active tab:", lastActiveTab);
        }
    } catch (e) {
        console.warn("Could not restore tab state:", e);
    }

    // Add keyboard navigation (Alt + number keys)
    document.addEventListener("keydown", function (e) {
        if (e.altKey && e.key >= "1" && e.key <= "5") {
            e.preventDefault();
            const tabs = ["hero", "about", "services", "catalog", "proyek"];
            const tabIndex = parseInt(e.key) - 1;
            if (tabs[tabIndex]) {
                switchTab(tabs[tabIndex]);
            }
        }
    });
});
