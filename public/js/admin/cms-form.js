// ============================================
// CMS ADMIN - FORM HANDLING & VALIDATION
// File: public/js/admin/cms-form.js
// ============================================

/**
 * Track form changes to warn before leaving
 */
let formChanged = false;

document.addEventListener("DOMContentLoaded", function () {
    const forms = document.querySelectorAll(".cms-form");

    forms.forEach((form) => {
        // Track form changes
        form.addEventListener("change", function () {
            formChanged = true;
            console.log("Form changed detected");
        });

        form.addEventListener("input", function () {
            formChanged = true;
        });

        // Reset flag on form submit
        form.addEventListener("submit", function (e) {
            formChanged = false;

            // Show loading state on submit button
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2" role="status"></span>
                    Menyimpan...
                `;
            }

            console.log("Form submitted");
        });
    });

    // Warn user before leaving with unsaved changes
    window.addEventListener("beforeunload", function (e) {
        if (formChanged) {
            e.preventDefault();
            e.returnValue = "";
            return "";
        }
    });

    console.log("Form tracking initialized for", forms.length, "forms");
});

/**
 * Character counter for textarea
 */
document.addEventListener("DOMContentLoaded", function () {
    const textareas = document.querySelectorAll(".cms-textarea");

    textareas.forEach((textarea) => {
        if (textarea.hasAttribute("maxlength")) {
            const maxLength = textarea.getAttribute("maxlength");
            const counter = document.createElement("small");
            counter.className = "cms-char-counter";
            counter.style.cssText =
                "display: block; text-align: right; margin-top: 5px; color: #6c757d; font-size: 0.875rem;";

            const updateCounter = () => {
                const remaining = maxLength - textarea.value.length;
                counter.textContent = `${remaining} karakter tersisa`;

                if (remaining < 50) {
                    counter.style.color = "#dc3545";
                } else if (remaining < 100) {
                    counter.style.color = "#ffc107";
                } else {
                    counter.style.color = "#6c757d";
                }
            };

            textarea.parentNode.appendChild(counter);
            textarea.addEventListener("input", updateCounter);
            updateCounter();
        }
    });

    if (textareas.length > 0) {
        console.log(
            "Character counter initialized for",
            textareas.length,
            "textareas"
        );
    }
});

/**
 * Real-time validation for required fields
 */
document.addEventListener("DOMContentLoaded", function () {
    const requiredInputs = document.querySelectorAll("[required]");

    requiredInputs.forEach((input) => {
        input.addEventListener("blur", function () {
            if (!this.value.trim()) {
                this.classList.add("is-invalid");
            } else {
                this.classList.remove("is-invalid");
            }
        });

        input.addEventListener("input", function () {
            if (this.classList.contains("is-invalid") && this.value.trim()) {
                this.classList.remove("is-invalid");
            }
        });
    });
});

/**
 * Toggle switch helper
 */
document.addEventListener("DOMContentLoaded", function () {
    const toggles = document.querySelectorAll(".cms-toggle-input");

    toggles.forEach((toggle) => {
        toggle.addEventListener("change", function () {
            const status = this.checked ? "Aktif" : "Nonaktif";
            console.log("Toggle changed:", this.id, status);
        });
    });
});
