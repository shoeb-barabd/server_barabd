<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>About | barabd Data Centers</title>
    <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">

    <!-- Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Page styles -->
    <link rel="stylesheet" href="{{ asset(url('front/css/styles.css')) }}">
</head>
<body class="about-page">

    <!-- Fixed About Nav -->
    <nav class="navbar navbar-expand-lg about-nav fixed-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('index') }}">
                <img src="{{ asset(url('front/images/logo.png')) }}" alt="Logo" height="38">
                <span class="fw-bold brand-mark mb-0">
                    <span class="brand-red">bara</span><span style="color:#016A4D">bd Data Center </span>
                </span>
            </a>

            <div class="d-flex align-items-center ms-auto gap-3">
                <a href="{{ route('index') }}" class="btn back-home-btn fw-semibold">
                    <i class="bi bi-arrow-left me-2"></i>Back to Home
                </a>
            </div>
        </div>
    </nav>

    <main class="about-main">
        <section class="about-hero text-center text-white">
            <div class="container">
                <h1 class="display-5 fw-bold mb-3">Built for reliable & secure hosting</h1>
                <p class="lead text-white-75 mb-4">
                    We design and operate modern data centers across Bangladesh, UAE, EU, US, and Africa with an obsessive focus on uptime,
                    efficiency, and customer support.
                </p>
                <div class="d-inline-flex align-items-center gap-2 px-4 py-2 rounded-4 about-pill">
                    <i class="bi bi-shield-lock-fill"></i>
                    <span>Tiered security • Sustainable power • 24/7 NOC</span>
                </div>
            </div>
        </section>

        <section class="py-5 about-body">
            <div class="container">
                <div class="text-center about-heading-wrap mb-4">
                    <h2 class="about-heading">About <span class="accent-green">US</span></h2>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-10">
                        <div class="about-intro-card">
                            <p class="about-body-text">
                                Baktier Ahmed Rony &amp; Associates is a government-approved software provider. Alongside delivering software and IT services for many years, the company is now also offering advanced data center services.
                                With revenue consultancy and a wide range of professional services, the organization has successfully completed over 12 years of operations.
                                The founder of the firm, Mr. Baktier Ahmed Rony, has been working in the field of revenue and technology for a long time, and is now gaining global recognition through data center services.
                                <span class="about-quote">"Not in words, but in actions we believe"—we are committed to delivering the highest level of service.</span>
                            </p>
                            <div class="about-highlight-grid">
                                <div class="about-highlight">
                                    <span class="about-highlight-number">12+</span>
                                    <span class="about-highlight-label">Years in operations</span>
                                </div>
                                <div class="about-highlight">
                                    <span class="about-highlight-number">Govt. Approved</span>
                                    <span class="about-highlight-label">Software provider</span>
                                </div>
                                <div class="about-highlight">
                                    <span class="about-highlight-number">5 Regions</span>
                                    <span class="about-highlight-label">Bangladesh • UAE • EU • US • Africa</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 about-panels">
            <div class="container">
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="about-panel h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="dot dot-blue"></span>
                                <h5 class="fw-bold mb-0">Our Mission</h5>
                            </div>
                            <p class="text-white-75 mb-0">
                                To make premium hosting simple, secure, and sustainable—delivering performance and transparent service without compromise.
                            </p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="about-panel h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="dot dot-green"></span>
                                <h5 class="fw-bold mb-0">Our Focus</h5>
                            </div>
                            <ul class="mb-0 text-white-75 list-unstyled about-list">
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>High-availability cloud &amp; bare metal</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Managed services with proactive monitoring</li>
                                <li><i class="bi bi-check-circle-fill text-success me-2"></i>Compliance-friendly infrastructure &amp; backups</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="about-panel h-100">
                            <div class="d-flex align-items-center gap-2 mb-2">
                                <span class="dot dot-amber"></span>
                                <h5 class="fw-bold mb-0">How we support you</h5>
                            </div>
                            <p class="text-white-75 mb-3">
                                Real people solving real problems—fast onboarding, consultative guidance, and rapid incident response.
                            </p>
                            <div class="d-flex align-items-center gap-3">
                                <div class="mini-stat">
                                    <span class="mini-number">24/7</span>
                                    <span class="mini-label">NOC coverage</span>
                                </div>
                                <div class="mini-stat">
                                    <span class="mini-number">99.9%</span>
                                    <span class="mini-label">Uptime target</span>
                                </div>
                                <div class="mini-stat">
                                    <span class="mini-number">15m</span>
                                    <span class="mini-label">Avg. response</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section class="py-5 about-gallery">
            <div class="container">
                <div class="text-center mb-3">
                    <h2 class="fw-bold about-gallery-title">Our <span class="accent-green">Gallery</span></h2>
                    <p class="about-gallery-sub">
                        A quick peek at our data centers, racks, maintenance, and team at work.
                    </p>
                </div>

                <div id="aboutGalleryCarousel" class="carousel slide about-gallery-slider" data-bs-ride="false" data-bs-interval="false">
                    <div class="carousel-indicators about-gallery-indicators">
                        <button type="button" data-bs-target="#aboutGalleryCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#aboutGalleryCarousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row g-3 g-md-4">
                                @foreach([1,9,3,10] as $i)
                                    <div class="col-6 col-md-3">
                                        <div class="about-gallery-card">
                                            <img src="{{ asset(url('front/images/gallery/' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg')) }}" alt="Gallery {{ $i }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="carousel-item">
                            <div class="row g-3 g-md-4">
                                @foreach([5,6,7,8] as $i)
                                    <div class="col-6 col-md-3">
                                        <div class="about-gallery-card">
                                            <img src="{{ asset(url('front/images/gallery/' . str_pad($i, 2, '0', STR_PAD_LEFT) . '.jpg')) }}" alt="Gallery {{ $i }}">
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button class="carousel-control-prev about-gallery-arrow" type="button" data-bs-target="#aboutGalleryCarousel" data-bs-slide="prev">
                        <span class="about-gallery-icon">&#8249;</span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next about-gallery-arrow" type="button" data-bs-target="#aboutGalleryCarousel" data-bs-slide="next">
                        <span class="about-gallery-icon">&#8250;</span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </section>
    </main>

    <footer class="about-footer text-center text-white-75 py-4">
        <div class="container small">
            © <span id="aboutYear"></span>
            <span class="brand-red">bara</span><span style="color:#016A4D">bd</span> Data Center
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('aboutYear').textContent = new Date().getFullYear();
    </script>
</body>
</html>
