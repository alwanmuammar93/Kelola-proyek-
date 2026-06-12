// ============================================
// CMS ADMIN - ALERT NOTIFICATIONS
// File: public/js/admin/cms-alerts.js
// ============================================

/**
 * Show notification message
 * @param {string} message - Message to display
 * @param {string} type - Type of notification (success, error, warning, info)
 * @param {number} duration - Duration in milliseconds (default: 5000)
 */
function showNotification(message, type = "info", duration = 5000) {
    const iconMap = {
        success: "bi-check-circle-fill",
        error: "bi-exclamation-triangle-fill",
        warning: "bi-exclamation-circle-fill",
        info: "bi-info-circle-fill",
    };

    const alert = document.createElement("div");
    alert.className = `cms-alert cms-alert-${type} cms-alert-floating`;
    alert.innerHTML = `
        <i class="bi ${iconMap[type]}"></i>
        <div class="cms-alert-content">
            <p>${message}</p>
        </div>
        <button class="cms-alert-close" onclick="this.parentElement.remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    `;

    // Add to page
    const wrapper = document.querySelector(".cms-wrapper");
    if (wrapper) {
        wrapper.insertBefore(alert, wrapper.firstChild);
    } else {
        document.body.appendChild(alert);
    }

    // Auto remove after duration
    setTimeout(() => {
        alert.style.opacity = "0";
        alert.style.transform = "translateY(-20px)";

        setTimeout(() => {
            alert.remove();
        }, 300);
    }, duration);

    console.log("Notification shown:", type, message);
}

/**
 * Auto-hide existing alerts after delay
 */
document.addEventListener("DOMContentLoaded", function () {
    const alerts = document.querySelectorAll(
        ".cms-alert:not(.cms-alert-floating)"
    );

    alerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.opacity = "0";
            alert.style.transform = "translateY(-20px)";

            setTimeout(() => {
                alert.remove();
            }, 300);
        }, 5000);
    });

    if (alerts.length > 0) {
        console.log("Auto-hide initialized for", alerts.length, "alerts");
    }
});
