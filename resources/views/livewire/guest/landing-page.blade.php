<div>
    <!-- Image Banner Section -->
    <section class="banner-section">
        <img src="{{ asset('img/landing.jpeg') }}" alt="UAS Kecerdasan Interpersonal" class="banner-img">
    </section>

    <!-- Hero Text Section -->
    <section class="hero" style="min-height: auto; padding: 4rem 0;">
        <div class="hero-bg">
            <div class="hero-shape shape-1"></div>
            <div class="hero-shape shape-2"></div>
        </div>
    </section>

    <!-- About Section -->
    <section class="section about-section" id="about">
        <div class="container">
            <div class="about-grid">
                <div class="about-image">
                    <div class="image-wrapper">
                        <i class="fas fa-university" style="font-size: 8rem; color: var(--primary-color); opacity: 0.8;"></i>
                    </div>
                </div>
                <div class="about-content">
                    <span class="section-badge">Tentang Event</span>
                    <h2 class="section-title">UAS Kecerdasan Interpersonal</h2>
                    <p class="section-description" style="margin-bottom: 1.5rem;">
                        Event KIP TALKS ini merupakan wujud evaluasi Ujian Akhir Semester (UAS) untuk mata kuliah <strong>Kecerdasan Interpersonal</strong>. Mahasiswa ditantang untuk menunjukkan kemampuan komunikasi, persuasi, dan public speaking mereka secara langsung.
                    </p>
                    <p class="section-description">
                        Mengadaptasi format presentasi populer TEDx, setiap mahasiswa akan berdiri di atas panggung untuk membagikan gagasan inspiratif dengan batas waktu yang ketat, dievaluasi secara langsung oleh dewan juri ahli.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="section cta-section">
        <div class="container">
            <div class="cta-card">
                <div class="cta-content">
                    <h2>Siap untuk Menyaksikan Ide Brilian?</h2>
                    <p>Ikuti jalannya presentasi secara langsung melalui sistem tersinkronisasi kami, atau login jika Anda adalah bagian dari acara.</p>
                    <div class="cta-actions">
                        <a href="{{ route('presentasi.public') }}" class="btn btn-white btn-lg">
                            <i class="fas fa-play"></i>
                            Tonton Sekarang
                        </a>
                    </div>
                </div>
                <div class="cta-decoration">
                    <div class="decoration-circle"></div>
                    <div class="decoration-circle small"></div>
                </div>
            </div>
        </div>
    </section>

    <x-slot:styles>
        <style>
            /* Banner Section */
            .banner-section {
                width: 100%;
                background-color: var(--bg-light);
                display: flex;
                justify-content: center;
                align-items: center;
                overflow: hidden;
            }

            .banner-img {
                width: 100%;
                height: auto;
                max-height: 100vh;
                object-fit: contain;
                display: block;
            }

            /* Hero Section */
            .hero {
                min-height: 100vh;
                display: flex;
                align-items: center;
                position: relative;
                overflow: hidden;
                padding-top: 80px;
            }

            .hero-bg {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                z-index: 0;
            }

            .hero-shape {
                position: absolute;
                border-radius: 50%;
                animation: float 20s ease-in-out infinite;
            }

            .shape-1 {
                width: 600px;
                height: 600px;
                background: linear-gradient(135deg, rgba(230, 43, 30, 0.08), rgba(184, 34, 24, 0.08));
                top: -200px;
                right: -200px;
            }

            .shape-2 {
                width: 400px;
                height: 400px;
                background: linear-gradient(135deg, rgba(230, 43, 30, 0.05), rgba(184, 34, 24, 0.05));
                bottom: -100px;
                left: -100px;
                animation-delay: -7s;
            }

            .shape-3 {
                width: 300px;
                height: 300px;
                background: linear-gradient(135deg, rgba(230, 43, 30, 0.06), rgba(184, 34, 24, 0.06));
                top: 50%;
                left: 30%;
                animation-delay: -14s;
            }

            @keyframes float {

                0%,
                100% {
                    transform: translateY(0) rotate(0deg);
                }

                33% {
                    transform: translateY(-30px) rotate(5deg);
                }

                66% {
                    transform: translateY(20px) rotate(-5deg);
                }
            }

            .hero-container {
                display: block;
                position: relative;
                z-index: 1;
            }

            .hero-badge {
                display: inline-flex;
                align-items: center;
                gap: 8px;
                background: rgba(230, 43, 30, 0.1);
                color: var(--primary-color);
                padding: 0.5rem 1.25rem;
                border-radius: 50px;
                font-size: 0.9rem;
                font-weight: 700;
                margin-bottom: 1.5rem;
                border: 1px solid rgba(230, 43, 30, 0.2);
            }

            .hero-title {
                font-size: 4rem;
                font-weight: 900;
                line-height: 1.1;
                margin-bottom: 1.5rem;
                letter-spacing: -1px;
            }

            .gradient-text {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }

            .hero-description {
                font-size: 1.2rem;
                color: var(--text-secondary);
                margin-bottom: 2.5rem;
                line-height: 1.7;
            }

            .hero-actions {
                display: flex;
                gap: 1rem;
                margin-bottom: 3rem;
            }

            .hero-stats {
                display: flex;
                align-items: center;
                gap: 2rem;
            }

            .stat-item {
                text-align: center;
            }

            .stat-value {
                display: block;
                font-size: 1.5rem;
                font-weight: 800;
                color: var(--text-primary);
            }

            .stat-label {
                font-size: 0.9rem;
                color: var(--text-muted);
                text-transform: uppercase;
                letter-spacing: 0.5px;
                font-weight: 500;
            }

            .stat-divider {
                width: 1px;
                height: 40px;
                background: var(--border-color);
            }

            /* Custom image styles if needed */

            /* Section Styles */
            .section {
                padding: 6rem 0;
            }

            .section-header {
                text-align: center;
                max-width: 700px;
                margin: 0 auto 4rem;
            }

            .section-badge {
                display: inline-block;
                background: rgba(230, 43, 30, 0.1);
                color: var(--primary-color);
                padding: 0.5rem 1.25rem;
                border-radius: 50px;
                font-size: 0.85rem;
                font-weight: 700;
                margin-bottom: 1rem;
                text-transform: uppercase;
                letter-spacing: 1px;
            }

            .section-title {
                font-size: 2.5rem;
                font-weight: 800;
                margin-bottom: 1rem;
            }

            .section-description {
                font-size: 1.15rem;
                color: var(--text-secondary);
                line-height: 1.7;
            }

            /* About Section */
            .about-section {
                background: var(--bg-white);
            }

            .about-grid {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 4rem;
                align-items: center;
            }

            .about-image {
                display: flex;
                justify-content: center;
            }

            .image-wrapper {
                width: 350px;
                height: 350px;
                background: linear-gradient(135deg, rgba(230, 43, 30, 0.1), rgba(184, 34, 24, 0.1));
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                position: relative;
            }

            .image-wrapper::before {
                content: '';
                position: absolute;
                inset: -20px;
                border: 2px dashed rgba(230, 43, 30, 0.3);
                border-radius: 50%;
                animation: spin 30s linear infinite;
            }

            @keyframes spin {
                100% { transform: rotate(360deg); }
            }

            /* Features Grid */
            .features-section {
                background: var(--bg-light);
            }

            .features-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 2rem;
            }

            .feature-card {
                background: var(--bg-white);
                padding: 2.5rem 2rem;
                border-radius: 16px;
                transition: all 0.3s ease;
                border: 1px solid var(--border-color);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
            }

            .feature-card:hover {
                transform: translateY(-5px);
                box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
                border-color: rgba(230, 43, 30, 0.3);
            }

            .feature-icon {
                width: 64px;
                height: 64px;
                border-radius: 16px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.5rem;
                box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1);
            }

            .feature-icon i {
                font-size: 1.75rem;
                color: white;
            }

            .feature-card h3 {
                font-size: 1.25rem;
                font-weight: 700;
                margin-bottom: 1rem;
                color: var(--text-primary);
            }

            .feature-card p {
                color: var(--text-secondary);
                font-size: 1rem;
                line-height: 1.6;
            }

            /* CTA Section */
            .cta-section {
                background: var(--bg-white);
            }

            .cta-card {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-dark));
                border-radius: 24px;
                padding: 5rem 4rem;
                position: relative;
                overflow: hidden;
            }

            .cta-content {
                position: relative;
                z-index: 1;
                text-align: center;
                max-width: 650px;
                margin: 0 auto;
            }

            .cta-content h2 {
                font-size: 2.75rem;
                font-weight: 800;
                color: white;
                margin-bottom: 1.5rem;
            }

            .cta-content p {
                font-size: 1.2rem;
                color: rgba(255, 255, 255, 0.9);
                margin-bottom: 2.5rem;
            }

            .cta-actions {
                display: flex;
                justify-content: center;
                gap: 1rem;
            }

            .btn-white {
                background: white;
                color: var(--primary-dark);
            }

            .btn-white:hover {
                background: #f8fafc;
                transform: translateY(-2px);
                box-shadow: 0 10px 25px rgba(0,0,0,0.2);
            }

            .btn-outline-white {
                background: transparent;
                border: 2px solid rgba(255, 255, 255, 0.5);
                color: white;
            }

            .btn-outline-white:hover {
                background: rgba(255, 255, 255, 0.1);
                border-color: white;
                color: white;
            }

            .cta-decoration {
                position: absolute;
                top: 0;
                right: 0;
                bottom: 0;
                width: 100%;
                z-index: 0;
                pointer-events: none;
            }

            .decoration-circle {
                position: absolute;
                border-radius: 50%;
                background: rgba(255, 255, 255, 0.05);
            }

            .decoration-circle:first-child {
                width: 600px;
                height: 600px;
                right: -150px;
                top: -150px;
            }

            .decoration-circle.small {
                width: 300px;
                height: 300px;
                left: -100px;
                bottom: -150px;
            }

            /* Responsive */
            @media (max-width: 1024px) {
                .about-grid {
                    grid-template-columns: 1fr;
                }

                .about-image {
                    order: -1;
                }
            }

            @media (max-width: 768px) {
                .hero-title {
                    font-size: 3rem;
                }

                .hero-actions {
                    flex-direction: column;
                }

                .features-grid {
                    grid-template-columns: 1fr;
                }

                .cta-actions {
                    flex-direction: column;
                }

                .cta-card {
                    padding: 3rem 2rem;
                }

                .cta-content h2 {
                    font-size: 2rem;
                }
            }
        </style>
    </x-slot:styles>

    <x-slot:scripts>
        <script>
            // Add custom scripts here if needed
        </script>
    </x-slot:scripts>
</div>
