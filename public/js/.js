// ========================================
// KWITANSI CREATE - JAVASCRIPT
// File: public/js/kwitansi-create.js
// ========================================

// Toggle Sidebar
function toggleSidebar() {
    const sidebar = document.getElementById("sidebar");
    const mainContent = document.querySelector(".proyek-main");
    const header = document.querySelector(".proyek-header");

    if (sidebar) sidebar.classList.toggle("hidden");
    if (mainContent) mainContent.classList.toggle("full-width");
    if (header) header.classList.toggle("full-width");
}

// Close sidebar when clicking outside (mobile)
document.addEventListener("click", function (event) {
    const sidebar = document.getElementById("sidebar");
    const toggle = document.querySelector(".header-menu-toggle");

    if (window.innerWidth <= 992 && sidebar && toggle) {
        if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
            sidebar.classList.remove("hidden");
        }
    }
});

// ========================================
// MAIN KWITANSI LOGIC
// ========================================
document.addEventListener("DOMContentLoaded", function () {
    // Get all elements
    const sumberSelect = document.getElementById("Sumber_Tabel");
    const idSumberSelect = document.getElementById("Id_Sumber");
    const searchInput = document.getElementById("searchKeyword");
    const btnSearch = document.getElementById("btnSearch");
    const totalInput = document.getElementById("Total");
    const totalPembayaranInput = document.getElementById("Total_Pembayaran");
    const displayStatusInput = document.getElementById("displayStatus");
    const salesInput = document.getElementById("Sales");
    const paymentRow = document.getElementById("paymentRow");
    const btnSubmit = document.getElementById("btnSubmit");

    // Check if kasir (will be set from blade template)
    const isKasir =
        typeof window.isKasir !== "undefined" ? window.isKasir : false;

    // ========================================
    // ✅ UPDATE STATUS & COLOR - DIPERBAIKI
    // ========================================
    function updateStatusAndColor() {
        const total = parseFloat(totalInput.value) || 0;
        const totalPembayaran = parseFloat(totalPembayaranInput.value) || 0;

        // Reset classes
        displayStatusInput.classList.remove("lunas", "lebih-bayar");

        if (totalPembayaran <= 0 || total <= 0) {
            // Belum ada pembayaran
            paymentRow.style.borderLeft = "4px solid #6c757d";
            totalPembayaranInput.style.borderLeft = "4px solid #6c757d";
            displayStatusInput.value = "DP 0%";
            displayStatusInput.className = "status-display";
        } else if (totalPembayaran > total) {
            // Lebih bayar
            paymentRow.style.borderLeft = "4px solid #dc3545";
            totalPembayaranInput.style.borderLeft = "4px solid #dc3545";

            const persentase = Math.round((totalPembayaran / total) * 100);
            displayStatusInput.value = "⚠️ Lebih Bayar (" + persentase + "%)";
            displayStatusInput.className = "status-display lebih-bayar";
        } else if (totalPembayaran >= total) {
            // Lunas
            paymentRow.style.borderLeft = "4px solid #0d6efd";
            totalPembayaranInput.style.borderLeft = "4px solid #0d6efd";
            displayStatusInput.value = "✅ Lunas (100%)";
            displayStatusInput.className = "status-display lunas";
        } else {
            // DP (pembayaran sebagian)
            const persentase = Math.round((totalPembayaran / total) * 100);

            // Warna berdasarkan persentase
            if (persentase >= 75) {
                paymentRow.style.borderLeft = "4px solid #fd7e14"; // Orange
                totalPembayaranInput.style.borderLeft = "4px solid #fd7e14";
            } else if (persentase >= 50) {
                paymentRow.style.borderLeft = "4px solid #ffc107"; // Yellow
                totalPembayaranInput.style.borderLeft = "4px solid #ffc107";
            } else {
                paymentRow.style.borderLeft = "4px solid #6c757d"; // Gray
                totalPembayaranInput.style.borderLeft = "4px solid #6c757d";
            }

            displayStatusInput.value = "DP " + persentase + "%";
            displayStatusInput.className = "status-display";
        }
    }

    // ========================================
    // LOAD DATA BY SUMBER
    // ========================================
    async function loadDataBySumber() {
        const sumber = sumberSelect.value;
        if (!sumber) {
            idSumberSelect.innerHTML =
                '<option value="">-- Pilih Data --</option>';
            return;
        }

        idSumberSelect.innerHTML = '<option value="">⏳ Loading...</option>';
        idSumberSelect.disabled = true;

        try {
            // Get route URL from meta tag or data attribute
            const getDataRoute =
                document.querySelector('meta[name="get-data-route"]')
                    ?.content || "/kwitansi/get-data-by-sumber";

            const response = await fetch(
                getDataRoute + "?sumber_tabel=" + encodeURIComponent(sumber)
            );
            const result = await response.json();

            idSumberSelect.innerHTML =
                '<option value="">-- Pilih Data --</option>';

            if (result.success && result.data && result.data.length > 0) {
                result.data.forEach(function (item) {
                    const option = document.createElement("option");
                    option.value = item.id;
                    option.textContent = item.text;
                    option.dataset.total = item.total || 0;
                    option.dataset.sales = item.sales || "";
                    idSumberSelect.appendChild(option);
                });
            } else {
                idSumberSelect.innerHTML =
                    '<option value="">❌ Tidak ada data</option>';
            }
        } catch (error) {
            console.error("Error loading data:", error);
            alert("❌ Gagal mengambil data. Silakan coba lagi.");
            idSumberSelect.innerHTML =
                '<option value="">⚠️ Error loading data</option>';
        } finally {
            idSumberSelect.disabled = false;
        }
    }

    // ========================================
    // SEARCH DATA
    // ========================================
    async function searchData() {
        const sumber = sumberSelect.value;
        const keyword = searchInput.value.trim();

        if (!sumber) {
            alert("⚠️ Pilih sumber data terlebih dahulu!");
            return;
        }

        if (!keyword) {
            loadDataBySumber();
            return;
        }

        idSumberSelect.innerHTML = '<option value="">🔍 Searching...</option>';
        idSumberSelect.disabled = true;

        try {
            const searchRoute =
                document.querySelector('meta[name="search-data-route"]')
                    ?.content || "/kwitansi/search-data";

            const response = await fetch(
                searchRoute +
                    "?sumber_tabel=" +
                    encodeURIComponent(sumber) +
                    "&keyword=" +
                    encodeURIComponent(keyword)
            );
            const result = await response.json();

            idSumberSelect.innerHTML =
                '<option value="">-- Pilih Data --</option>';

            if (result.success && result.data && result.data.length > 0) {
                result.data.forEach(function (item) {
                    const option = document.createElement("option");
                    option.value = item.id;
                    option.textContent = item.text;
                    option.dataset.total = item.total || 0;
                    option.dataset.sales = item.sales || "";
                    idSumberSelect.appendChild(option);
                });
            } else {
                idSumberSelect.innerHTML =
                    '<option value="">❌ Tidak ditemukan</option>';
            }
        } catch (error) {
            console.error("Error searching data:", error);
            alert("❌ Gagal mencari data. Silakan coba lagi.");
            idSumberSelect.innerHTML =
                '<option value="">⚠️ Error searching</option>';
        } finally {
            idSumberSelect.disabled = false;
        }
    }

    // ========================================
    // GET TOTAL FROM SELECTED DATA
    // ========================================
    function getTotalSumber() {
        const sumber = sumberSelect.value;
        const idSumber = idSumberSelect.value;

        if (!sumber || !idSumber) {
            totalInput.value = "";
            updateStatusAndColor();
            return;
        }

        const selectedOption =
            idSumberSelect.options[idSumberSelect.selectedIndex];
        const total = selectedOption.dataset.total || 0;
        const sales = selectedOption.dataset.sales || "";

        totalInput.value = total;

        // Set Sales berdasarkan sumber
        if (sumber === "penjualan" && sales) {
            salesInput.value = sales;
        }

        updateStatusAndColor();
    }

    // ========================================
    // EVENT LISTENERS
    // ========================================
    if (sumberSelect) {
        sumberSelect.addEventListener("change", function () {
            totalInput.value = "";
            searchInput.value = "";
            loadDataBySumber();
        });
    }

    if (idSumberSelect) {
        idSumberSelect.addEventListener("change", getTotalSumber);
    }

    if (btnSearch) {
        btnSearch.addEventListener("click", searchData);
    }

    if (searchInput) {
        searchInput.addEventListener("keypress", function (e) {
            if (e.key === "Enter") {
                e.preventDefault();
                searchData();
            }
        });
    }

    if (totalPembayaranInput) {
        totalPembayaranInput.addEventListener("input", updateStatusAndColor);
    }

    // ========================================
    // INITIALIZATION
    // ========================================
    if (isKasir && sumberSelect) {
        sumberSelect.value = "penjualan";
        loadDataBySumber();
    } else if (sumberSelect) {
        const prefillSumber = sumberSelect.value;
        if (prefillSumber) {
            loadDataBySumber();
        }
    }

    // ========================================
    // ✅ FORM VALIDATION - SIMPLE & TIDAK STRICT
    // ========================================
    const formKwitansi = document.getElementById("formKwitansi");
    if (formKwitansi) {
        formKwitansi.addEventListener("submit", function (e) {
            const total = parseFloat(totalInput.value) || 0;
            const totalPembayaran = parseFloat(totalPembayaranInput.value) || 0;
            const idSumber = idSumberSelect.value;

            // Validasi 1: ID Sumber harus dipilih
            if (!idSumber) {
                e.preventDefault();
                alert(
                    "⚠️ Harap pilih data sumber (RAB/Penjualan) terlebih dahulu!"
                );
                idSumberSelect.focus();
                return false;
            }

            // Validasi 2: Total harus terisi
            if (total <= 0) {
                e.preventDefault();
                alert(
                    "⚠️ Total harus lebih dari 0. Pastikan Anda sudah memilih data sumber yang valid."
                );
                idSumberSelect.focus();
                return false;
            }

            // Validasi 3: Total Pembayaran harus terisi
            if (totalPembayaran <= 0) {
                e.preventDefault();
                alert("⚠️ Total Pembayaran harus diisi dan lebih dari 0!");
                totalPembayaranInput.focus();
                return false;
            }

            // ✅ FIXED: Konfirmasi lebih bayar (opsional, tidak blocking)
            if (totalPembayaran > total) {
                const lebihBayar = totalPembayaran - total;
                const konfirmasi = confirm(
                    "⚠️ PERINGATAN!\n\n" +
                        "Pembayaran melebihi total tagihan:\n" +
                        "• Total Tagihan: Rp " +
                        total.toLocaleString("id-ID") +
                        "\n" +
                        "• Pembayaran: Rp " +
                        totalPembayaran.toLocaleString("id-ID") +
                        "\n" +
                        "• Lebih Bayar: Rp " +
                        lebihBayar.toLocaleString("id-ID") +
                        "\n\n" +
                        "Apakah Anda yakin ingin melanjutkan?"
                );

                if (!konfirmasi) {
                    e.preventDefault();
                    totalPembayaranInput.focus();
                    return false;
                }
            }

            // Validasi 4: Konfirmasi final
            const persentase = Math.round((totalPembayaran / total) * 100);
            let statusText;

            if (totalPembayaran > total) {
                statusText = "⚠️ LEBIH BAYAR (" + persentase + "%)";
            } else if (totalPembayaran >= total) {
                statusText = "✅ LUNAS (100%)";
            } else {
                statusText = "DP " + persentase + "%";
            }

            const konfirmasiAkhir = confirm(
                "✅ KONFIRMASI SIMPAN KWITANSI\n\n" +
                    "• Total Tagihan: Rp " +
                    total.toLocaleString("id-ID") +
                    "\n" +
                    "• Pembayaran: Rp " +
                    totalPembayaran.toLocaleString("id-ID") +
                    "\n" +
                    "• Status: " +
                    statusText +
                    "\n\n" +
                    "💡 ID Kwitansi akan di-generate otomatis.\n\n" +
                    "Apakah data sudah benar?"
            );

            if (!konfirmasiAkhir) {
                e.preventDefault();
                return false;
            }

            // ✅ Show loading
            if (btnSubmit) {
                btnSubmit.innerHTML =
                    '<i class="fas fa-spinner fa-spin"></i> Menyimpan...';
                btnSubmit.disabled = true;
            }

            return true;
        });
    }
});
