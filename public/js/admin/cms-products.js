/**
 * ============================================================================
 * CMS PRODUCTS MANAGEMENT - JAVASCRIPT
 * ============================================================================
 * File: public/js/admin/cms-products.js
 * Fungsi: Handle modal, AJAX, dan interaksi untuk Catalog Products Management
 */

// ============================================================================
// GLOBAL VARIABLES
// ============================================================================

const ROUTES = {
    productShow: "/admin/konten/catalog/products/",
    productUpdate: "/admin/konten/catalog/products/",
};

// ============================================================================
// MODAL FUNCTIONS - ADD PRODUCT
// ============================================================================

/**
 * Open Add Product Modal
 */
function openAddProductModal() {
    const modal = document.getElementById("addProductModal");
    if (!modal) {
        console.error("Add Product Modal not found!");
        return;
    }

    modal.style.display = "flex";
    document.body.style.overflow = "hidden";

    // Focus pada input pertama
    setTimeout(() => {
        const firstInput = modal.querySelector('input[name="name"]');
        if (firstInput) firstInput.focus();
    }, 100);

    console.log("✅ Add Product Modal opened");
}

/**
 * Close Add Product Modal
 */
function closeAddProductModal() {
    const modal = document.getElementById("addProductModal");
    if (!modal) return;

    modal.style.display = "none";
    document.body.style.overflow = "";

    // Reset form
    const form = modal.querySelector("form");
    if (form) {
        form.reset();
    }

    // Reset image preview
    const preview = document.getElementById("addProductImagePreview");
    if (preview) {
        preview.style.display = "none";
        preview.innerHTML = "";
    }

    // Reset file input
    const fileInput = document.getElementById("addProductImageInput");
    if (fileInput) {
        fileInput.value = "";
    }

    console.log("✅ Add Product Modal closed");
}

// ============================================================================
// MODAL CLOSE ON OVERLAY & ESC KEY
// ============================================================================

/**
 * Close modal when clicking overlay or pressing ESC
 */
document.addEventListener("DOMContentLoaded", function () {
    const addModal = document.getElementById("addProductModal");

    if (addModal) {
        // Handle overlay click
        const overlay = addModal.querySelector(".cms-modal-overlay");
        if (overlay) {
            overlay.addEventListener("click", function (e) {
                e.stopPropagation();
                closeAddProductModal();
            });
        }

        // Handle click on modal wrapper
        addModal.addEventListener("click", function (e) {
            if (e.target === this) {
                closeAddProductModal();
            }
        });
    }
});

/**
 * Close modal on ESC key press
 */
document.addEventListener("keydown", function (event) {
    if (event.key === "Escape" || event.key === "Esc") {
        const addModal = document.getElementById("addProductModal");

        if (addModal && addModal.style.display !== "none") {
            closeAddProductModal();
        }
    }
});

// ============================================================================
// FORM VALIDATION & SUBMISSION
// ============================================================================

/**
 * Validate Add Product Form before submission
 */
document.addEventListener("DOMContentLoaded", function () {
    const addForm = document.querySelector("#addProductModal form");

    if (addForm) {
        addForm.addEventListener("submit", function (e) {
            const name = this.querySelector('input[name="name"]');
            const category = this.querySelector('select[name="category"]');
            const image = document.getElementById("addProductImageInput");

            let errors = [];

            // Validate name
            if (!name || !name.value.trim()) {
                errors.push("Nama produk wajib diisi!");
            }

            // Validate category
            if (!category || !category.value) {
                errors.push("Kategori produk wajib dipilih!");
            }

            // Validate image
            if (!image || !image.files || image.files.length === 0) {
                errors.push("Gambar produk wajib diupload!");
            } else {
                const file = image.files[0];
                const maxSize = 5 * 1024 * 1024; // 5MB

                if (file.size > maxSize) {
                    errors.push("Ukuran gambar maksimal 5MB!");
                }

                const allowedTypes = [
                    "image/jpeg",
                    "image/jpg",
                    "image/png",
                    "image/webp",
                ];
                if (!allowedTypes.includes(file.type)) {
                    errors.push(
                        "Format gambar harus: JPEG, JPG, PNG, atau WEBP!"
                    );
                }
            }

            // Show errors if any
            if (errors.length > 0) {
                e.preventDefault();
                alert("❌ Validasi gagal:\n\n" + errors.join("\n"));
                return false;
            }

            // Show loading state
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<i class="bi bi-hourglass-split"></i> Menyimpan...';
            }
        });
    }
});

// ============================================================================
// DELETE CONFIRMATION
// ============================================================================

/**
 * Confirm before deleting product
 * @param {Event} event - Form submit event
 * @param {string} productName - Product name
 * @returns {boolean}
 */
function confirmDeleteProduct(event, productName) {
    return confirm(
        `⚠️ PERINGATAN!\n\nYakin ingin menghapus produk "${productName}"?\n\nTindakan ini tidak dapat dibatalkan!`
    );
}

/**
 * Confirm before deleting product image
 * @param {Event} event - Form submit event
 * @returns {boolean}
 */
function confirmDeleteImage(event) {
    return confirm(
        "Yakin ingin menghapus gambar produk ini?\n\nGambar akan dihapus permanen, tapi data produk tetap tersimpan."
    );
}

/**
 * Confirm before deleting category
 * @param {Event} event - Form submit event
 * @param {string} categoryName - Category name
 * @returns {boolean}
 */
function confirmDeleteCategory(event, categoryName) {
    return confirm(
        `⚠️ PERINGATAN!\n\nYakin ingin menghapus kategori "${categoryName}"?\n\nSemua produk dengan kategori ini akan kehilangan kategorinya!`
    );
}

// ============================================================================
// CONSOLE INFO
// ============================================================================

console.log(
    "%c🛍️ CMS Products Management JS Loaded",
    "color: #28a745; font-size: 14px; font-weight: bold;"
);
console.log("%cVersion: 1.0.0", "color: #6c757d; font-size: 12px;");
console.log(
    "%cReady to manage catalog products!",
    "color: #17a2b8; font-size: 12px;"
);