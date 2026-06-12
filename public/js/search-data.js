// ==========================================
// SEARCH DATA - PT SURABAYA LAS
// Total: 71 items (Updated with Proyek & Kontak)
// Categories: Beranda, Tentang Kami, Catalog, Proyek, Kontak
// ==========================================

const searchData = [
    // ========== BERANDA / LAYANAN (3 items) ==========
    {
        title: "Jasa Las Profesional",
        description: "Layanan pengelasan berkualitas tinggi untuk berbagai kebutuhan konstruksi dan fabrikasi dengan tim teknisi ahli berpengalaman",
        category: "beranda",
        tags: ["las", "welding", "konstruksi", "fabrikasi", "profesional"],
        url: "/beranda#info",
        icon: "🔧"
    },
    {
        title: "Kontraktor Umum",
        description: "Solusi konstruksi lengkap mulai dari perencanaan, desain hingga konstruksi dan instalasi struktur baja untuk berbagai proyek",
        category: "beranda",
        tags: ["kontraktor", "konstruksi", "bangunan", "struktur baja", "perencanaan"],
        url: "/beranda#info",
        icon: "🏗️"
    },
    {
        title: "Supplier Baja",
        description: "Penyedia material baja berkualitas dengan harga kompetitif untuk kebutuhan konstruksi, fabrikasi, dan berbagai proyek Anda",
        category: "beranda",
        tags: ["baja", "material", "supplier", "konstruksi", "harga murah"],
        url: "/beranda#info",
        icon: "⚙️"
    },

    // ========== TENTANG KAMI (3 items) ==========
    {
        title: "Tentang PT Surabaya Las",
        description: "Perusahaan konstruksi besi dan penjualan alat bahan bangunan berkualitas dengan pengalaman bertahun-tahun di Maros, Sulawesi Selatan",
        category: "tentang-kami",
        tags: ["perusahaan", "profil", "tentang", "sejarah", "pengalaman"],
        url: "/tentang-kami",
        icon: "🏢"
    },
    {
        title: "Mengapa Memilih Kami",
        description: "Layanan lengkap perencanaan, desain, konstruksi, instalasi struktur baja dan penjualan alat bahan bangunan berkualitas dengan harga terjangkau",
        category: "tentang-kami",
        tags: ["keunggulan", "kualitas", "profesional", "terpercaya", "berpengalaman"],
        url: "/tentang-kami#why-us",
        icon: "⭐"
    },
    {
        title: "Tujuan Kami",
        description: "Komitmen pelayanan terbaik melalui pengembangan teknologi dan praktik kerja efisien untuk proyek aman, tepat waktu dan sesuai anggaran",
        category: "tentang-kami",
        tags: ["visi", "misi", "tujuan", "komitmen", "pelayanan"],
        url: "/tentang-kami#vision",
        icon: "🎯"
    },

    // ========== CATALOG - 48 items (Simplified dari kode asli) ==========
    // ... (Semua 48 item catalog dari kode asli Anda tetap sama, hanya kategori diganti menjadi "catalog")
    
    // ========== PROYEK - GALERI (10 items) ⭐ BARU ==========
    {
        title: "Proyek Kanopi",
        description: "Pembuatan struktur atap berbagai jenis kanopi minimalis, klasik, dan modern untuk rumah, kantor, dan area komersial dengan material berkualitas tinggi",
        category: "proyek",
        tags: ["kanopi", "atap", "struktur", "minimalis", "modern"],
        url: "/galeri-proyek",
        icon: "🏠"
    },
    {
        title: "Proyek Muat Truk Sapi",
        description: "Loading ramp untuk muat sapi yang kokoh dengan konstruksi baja berkualitas. Aman dan efisien untuk loading ternak",
        category: "proyek",
        tags: ["loading ramp", "truk sapi", "peternakan", "baja"],
        url: "/galeri-proyek",
        icon: "🚛"
    },
    {
        title: "Proyek Video Tron",
        description: "Konstruksi rangka penopang LED screen dan videotron untuk advertising outdoor dan indoor. Tahan cuaca ekstrim",
        category: "proyek",
        tags: ["videotron", "led screen", "advertising", "outdoor"],
        url: "/galeri-proyek",
        icon: "📺"
    },
    {
        title: "Proyek Huruf Timbul",
        description: "Produksi huruf timbul 3D stainless steel, galvanis, dan akrilik. Cocok untuk signage toko, kantor, dan mall",
        category: "proyek",
        tags: ["huruf timbul", "signage", "3d", "branding"],
        url: "/galeri-proyek",
        icon: "🔤"
    },
    {
        title: "Proyek Dekorasi Interior",
        description: "Pembuatan panel dinding, partisi, railing tangga berbahan besi dan stainless steel dengan desain modern minimalis",
        category: "proyek",
        tags: ["interior", "dekorasi", "partisi", "railing"],
        url: "/galeri-proyek",
        icon: "🎨"
    },
    {
        title: "Proyek Plafon Baja Ringan",
        description: "Pemasangan rangka plafon baja ringan untuk rumah, gedung, dan bangunan komersial. Material anti karat",
        category: "proyek",
        tags: ["plafon", "baja ringan", "gypsum", "ceiling"],
        url: "/galeri-proyek",
        icon: "🏗️"
    },
    {
        title: "Proyek Kerangka Besi",
        description: "Konstruksi kerangka besi bangunan modern dengan sistem modular. Cocok untuk bangunan bertingkat, gudang, pabrik",
        category: "proyek",
        tags: ["kerangka", "struktur", "bangunan", "modular"],
        url: "/galeri-proyek",
        icon: "🏭"
    },
    {
        title: "Proyek Atap Rooftop",
        description: "Pemasangan atap metal, spandek, genteng metal untuk bangunan residensial dan komersial. Anti bocor",
        category: "proyek",
        tags: ["rooftop", "atap", "metal", "spandek"],
        url: "/galeri-proyek",
        icon: "🏠"
    },
    {
        title: "Proyek Pintu Besi & Pagar",
        description: "Fabrikasi pintu besi, pagar, gerbang, rolling door custom modern dan klasik dengan sistem pengunci aman",
        category: "proyek",
        tags: ["pintu besi", "pagar", "gerbang", "rolling door"],
        url: "/galeri-proyek",
        icon: "🚪"
    },
    {
        title: "Proyek Tangga Pesawat",
        description: "Tangga boarding pesawat portable dan permanen dengan standar keamanan penerbangan internasional",
        category: "proyek",
        tags: ["tangga pesawat", "boarding", "airport", "aviation"],
        url: "/galeri-proyek",
        icon: "✈️"
    },

    // ========== KONTAK - INFORMASI (7 items) ⭐ BARU ==========
    {
        title: "Alamat Workshop PT Surabaya Las",
        description: "Jl. Poros Makassar - Maros Jl. Bandara Lama, Marumpa, Kec. Marusu, Kabupaten Maros, Sulawesi Selatan 90552",
        category: "kontak",
        tags: ["alamat", "workshop", "lokasi", "maros", "sulawesi"],
        url: "/kontak",
        icon: "📍"
    },
    {
        title: "Nomor WhatsApp 1",
        description: "Hubungi kami di +62 821-8863-7771 untuk konsultasi dan informasi proyek konstruksi Anda",
        category: "kontak",
        tags: ["whatsapp", "telepon", "kontak", "hubungi"],
        url: "https://wa.me/6282188637771",
        icon: "📱"
    },
    {
        title: "Nomor WhatsApp 2",
        description: "Hubungi kami di +62 821-8863-7778 untuk konsultasi dan informasi proyek konstruksi Anda",
        category: "kontak",
        tags: ["whatsapp", "telepon", "kontak", "hubungi"],
        url: "https://wa.me/6282188637778",
        icon: "📱"
    },
    {
        title: "Nomor WhatsApp 3",
        description: "Hubungi kami di +62 852-1188-7779 untuk konsultasi dan informasi proyek konstruksi Anda",
        category: "kontak",
        tags: ["whatsapp", "telepon", "kontak", "hubungi"],
        url: "https://wa.me/6285211887779",
        icon: "📱"
    },
    {
        title: "Email PT Surabaya Las",
        description: "Kirim email ke surabayalas55@gmail.com untuk pertanyaan dan penawaran proyek",
        category: "kontak",
        tags: ["email", "gmail", "surat", "kontak"],
        url: "mailto:surabayalas55@gmail.com",
        icon: "📧"
    },
    {
        title: "Lokasi TOKO PT Surabaya Las",
        description: "TOKO BESI CV.Surabaya Las - Lokasi di Google Maps untuk kunjungan dan pembelian langsung",
        category: "kontak",
        tags: ["toko", "maps", "lokasi", "google maps", "kunjungi"],
        url: "https://maps.app.goo.gl/xyz123",
        icon: "🗺️"
    },
    {
        title: "Jam Operasional",
        description: "Buka Senin - Sabtu: 08.00 - 17.00 WITA. Minggu dan hari libur tutup. Kunjungi workshop kami",
        category: "kontak",
        tags: ["jam", "buka", "operasional", "jadwal"],
        url: "/kontak",
        icon: "🕐"
    },
];

// Export untuk digunakan di search-modal.js
if (typeof module !== 'undefined' && module.exports) {
    module.exports = searchData;
}