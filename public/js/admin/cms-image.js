// ============================================
// CMS ADMIN - IMAGE PREVIEW & UPLOAD HANDLING
// File: public/js/admin/cms-image.js
// ============================================

/**
 * Preview uploaded image before form submission
 * @param {HTMLInputElement} input - File input element
 * @param {string} previewId - ID of preview container element
 */
function previewImage(input, previewId) {
    const preview = document.getElementById(previewId);

    if (!preview) {
        console.error(`Preview element with ID "${previewId}" not found`);
        return;
    }

    // Check if file is selected
    if (!input.files || !input.files[0]) {
        console.warn("No file selected");
        return;
    }

    const file = input.files[0];

    // Validate file type
    const validTypes = ["image/jpeg", "image/jpg", "image/png", "image/webp"];
    if (!validTypes.includes(file.type)) {
        showNotification(
            "Format file tidak valid! Gunakan JPG, PNG, atau WEBP",
            "error"
        );
        input.value = "";
        return;
    }

    // Validate file size (max 5MB)
    const maxSize = 5 * 1024 * 1024; // 5MB in bytes
    if (file.size > maxSize) {
        showNotification("Ukuran file terlalu besar! Maksimal 5MB", "error");
        input.value = "";
        return;
    }

    // Show loading state
    preview.innerHTML = `
        <div class="cms-image-loading">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p>Memuat preview...</p>
        </div>
    `;
    preview.style.display = "block";

    // Create FileReader to read the file
    const reader = new FileReader();

    reader.onload = function (e) {
        // Create preview HTML
        preview.innerHTML = `
            <img src="${e.target.result}" alt="Preview Image">
            <div class="cms-image-overlay">
                <button type="button" 
                        class="cms-btn-icon cms-btn-danger" 
                        onclick="removePreview('${previewId}', '${input.id}')"
                        title="Hapus gambar">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <div class="cms-image-info">
                <small>
                    <i class="bi bi-file-earmark"></i> ${file.name} 
                    (${formatFileSize(file.size)})
                </small>
            </div>
        `;

        console.log("Image preview loaded:", file.name);
    };

    reader.onerror = function () {
        showNotification("Gagal memuat preview gambar", "error");
        preview.style.display = "none";
        input.value = "";
    };

    // Read file as data URL
    reader.readAsDataURL(file);
}

/**
 * Remove image preview and clear file input
 * @param {string} previewId - ID of preview container element
 * @param {string} inputId - ID of file input element
 */
function removePreview(previewId, inputId) {
    const preview = document.getElementById(previewId);
    const input = document.getElementById(inputId);

    if (preview) {
        preview.innerHTML = "";
        preview.style.display = "none";
        console.log("Preview removed:", previewId);
    }

    if (input) {
        input.value = "";
        console.log("Input cleared:", inputId);
    }

    showNotification("Gambar dihapus", "info");
}

/**
 * Format file size to human readable format
 * @param {number} bytes - File size in bytes
 * @returns {string} Formatted file size
 */
function formatFileSize(bytes) {
    if (bytes === 0) return "0 Bytes";

    const k = 1024;
    const sizes = ["Bytes", "KB", "MB", "GB"];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return Math.round((bytes / Math.pow(k, i)) * 100) / 100 + " " + sizes[i];
}

/**
 * Initialize drag and drop for upload areas
 */
document.addEventListener("DOMContentLoaded", function () {
    const uploadAreas = document.querySelectorAll(".cms-upload-area");

    uploadAreas.forEach((area) => {
        // Prevent default drag behaviors
        ["dragenter", "dragover", "dragleave", "drop"].forEach((eventName) => {
            area.addEventListener(eventName, preventDefaults, false);
        });

        // Highlight drop area when item is dragged over it
        ["dragenter", "dragover"].forEach((eventName) => {
            area.addEventListener(
                eventName,
                function () {
                    area.classList.add("cms-upload-dragover");
                },
                false
            );
        });

        ["dragleave", "drop"].forEach((eventName) => {
            area.addEventListener(
                eventName,
                function () {
                    area.classList.remove("cms-upload-dragover");
                },
                false
            );
        });

        // Handle dropped files
        area.addEventListener(
            "drop",
            function (e) {
                const dt = e.dataTransfer;
                const files = dt.files;

                // Find the associated file input
                const inputId = area
                    .getAttribute("onclick")
                    .match(/getElementById\('(.+?)'\)/)[1];
                const input = document.getElementById(inputId);

                if (input && files.length > 0) {
                    input.files = files;

                    // Trigger preview
                    const previewId = input
                        .getAttribute("onchange")
                        .match(/previewImage\(this, '(.+?)'\)/)[1];
                    previewImage(input, previewId);
                }
            },
            false
        );
    });

    console.log(
        "Drag & Drop initialized for",
        uploadAreas.length,
        "upload areas"
    );
});

/**
 * Prevent default drag behaviors
 */
function preventDefaults(e) {
    e.preventDefault();
    e.stopPropagation();
}
