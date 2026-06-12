@extends('layouts.frontend')

@section('title', 'Kontak Kami')

@section('meta_description', 'Hubungi PT Surabaya Las untuk konsultasi dan informasi layanan konstruksi dan pengelasan profesional. Workshop kami berlokasi di Maros, Sulawesi Selatan.')

@section('styles')
    <link rel="stylesheet" href="/css/kontak.css">
@endsection

@section('content')

<!-- Hero Section - SEDERHANA SEPERTI PROYEK -->
<section class="hero-kontak">
    <div class="hero-overlay">
        <div class="hero-content">
            <h1>HUBUNGI KAMI<br><span class="highlight">PT SURABAYA LAS</span></h1>
        </div>
    </div>
</section>

<!-- Contact Content Section -->
<section class="contact-content">
    <div class="container">
        <div class="contact-wrapper">
            <!-- Left Column - Workshop & Contact Info -->
            <div class="contact-info-section">
                <div class="contact-info-box">
                    <h3 class="section-title">Lokasi</h3>
                    <div class="info-item">
                        <div class="icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M12 0c-4.198 0-8 3.403-8 7.602 0 4.198 3.469 9.21 8 16.398 4.531-7.188 8-12.2 8-16.398 0-4.199-3.801-7.602-8-7.602zm0 11c-1.657 0-3-1.343-3-3s1.343-3 3-3 3 1.343 3 3-1.343 3-3 3z"/>
                            </svg>
                        </div>
                        <div class="info-text">
                            <p>Jl. Poros Makassar - Maros Jl. Bandara Lama,</p>
                            <p>Marumpa, Kec. Marusu, Kabupaten Maros,</p>
                            <p>Sulawesi Selatan 90552</p>
                        </div>
                    </div>

                    <h3 class="section-title mt-5">Kontak</h3>
                    
                    <div class="info-item info-item-horizontal">
                        <div class="icon-wrapper icon-whatsapp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <div class="info-text">
                            <a href="https://wa.me/6282188637771" target="_blank" class="contact-link">+62 821-8863-7771</a>
                        </div>
                    </div>

                    <div class="info-item info-item-horizontal">
                        <div class="icon-wrapper icon-whatsapp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <div class="info-text">
                            <a href="https://wa.me/6282188637778" target="_blank" class="contact-link">+62 821-8863-7778</a>
                        </div>
                    </div>

                    <div class="info-item info-item-horizontal">
                        <div class="icon-wrapper icon-whatsapp">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </div>
                        <div class="info-text">
                            <a href="https://wa.me/6285211887779" target="_blank" class="contact-link">+62 852-1188-7779</a>
                        </div>
                    </div>

                    <div class="info-item info-item-horizontal">
                        <div class="icon-wrapper">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="white">
                                <path d="M0 3v18h24v-18h-24zm6.623 7.929l-4.623 5.712v-9.458l4.623 3.746zm-4.141-5.929h19.035l-9.517 7.713-9.518-7.713zm5.694 7.188l3.824 3.099 3.83-3.104 5.612 6.817h-18.779l5.513-6.812zm9.208-1.264l4.616-3.741v9.348l-4.616-5.607z"/>
                            </svg>
                        </div>
                        <div class="info-text">
                            <a href="mailto:surabayalas55@gmail.com" class="contact-link">surabayalas55@gmail.com</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Google Maps & Store Description -->
            <div class="map-section">
                <div class="map-box">
                    <h3 class="section-title">TOKO PT Surabaya Las</h3>
                    
                    <!-- Google Maps Container -->
                    <div class="map-container">
                        <iframe 
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3974.29982761274!2d119.53733167585617!3d-5.055064551361392!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dbef965d9027b09%3A0x4478ed301adf4ca3!2sTOKO%20BESI%20CV.Surabaya%20Las!5e0!3m2!1sid!2sid!4v1765634970845!5m2!1sid!2sid" 
                            width="100%" 
                            height="100%" 
                            style="border:0;" 
                            allowfullscreen="" 
                            loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            class="google-map">
                        </iframe>
                        <a href="https://www.google.com/maps/place/TOKO+BESI+CV.Surabaya+Las/@-5.0550646,119.5373317,17z/data=!3m1!4b1!4m6!3m5!1s0x2dbef965d9027b09:0x4478ed301adf4ca3!8m2!3d-5.0550646!4d119.5399066!16s%2Fg%2F11k3xm9_pk?entry=ttu&g_ep=EgoyMDI1MDEwOC4wIKXMDSoASAFQAw%3D%3D" target="_blank" class="map-overlay">
                            <div class="overlay-content">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="white">
                                    <path d="M21 13v10h-21v-19h12v2h-10v15h17v-8h2zm3-12h-10.988l4.035 4-6.977 7.07 2.828 2.828 6.977-7.07 4.125 4.172v-11z"/>
                                </svg>
                                <span>Buka di Google Maps</span>
                            </div>
                        </a>
                    </div>

                    <!-- Store Description Section -->
                    <div class="store-description">
                        <h4>Lokasi PT Surabaya Las</h4>
                        <p>
                            PT Surabaya Las merupakan perusahaan konstruksi dan fabrikasi baja yang berlokasi di Jl. Poros Makassar–Maros, Kecamatan Marusu, Kabupaten Maros, Sulawesi Selatan. 
                            Lokasi strategis ini memudahkan kami dalam melayani kebutuhan pelanggan dari wilayah Makassar, Maros, hingga berbagai daerah di Sulawesi Selatan dan sekitarnya.
                        </p>
                        <p>
                            Dengan lokasi strategis ini, kami siap memberikan solusi konstruksi baja terbaik untuk proyek Anda.
                        </p>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
// Smooth scroll dan animasi
document.addEventListener('DOMContentLoaded', function() {
    // Animasi fade-in saat scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };

    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('fade-in');
            }
        });
    }, observerOptions);

    // Observe semua elemen yang ingin dianimasi
    document.querySelectorAll('.info-item, .map-box, .store-description').forEach(el => {
        observer.observe(el);
    });

    // Smooth scroll untuk link anchor
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});
</script>
@endsection