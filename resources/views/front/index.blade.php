<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>barabd Data Centers — Simple Hosting</title>
    <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">

    <!-- Fonts & Bootstrap   jhf-->
    <!-- Black Ops One -->
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Page styles -->
    <link rel="stylesheet" href="{{ asset(url('front/css/styles.css')) }}" />
    {{-- ======================= STYLES ======================= --}}

</head>

<body>

    <!-- NAVBAR Starts here vh -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="#">
                <img src="{{ asset(url('front/images/logo.png')) }}" alt="Logo" height="40">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNav"
                aria-controls="topNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse justify-content-end" id="topNav">
                <ul class="navbar-nav align-items-lg-center gap-lg-3 mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('about') }}">About</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link fw-semibold dropdown-toggle" href="#pricing" id="servicesDropdown"
                            role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Prices
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="servicesDropdown">
                            @forelse($categories as $category)
                                <li>
                                    <a class="dropdown-item service-category-link" href="#pricing"
                                        data-service-category="{{ $category->id }}">
                                        {{ $category->name }}
                                    </a>
                                </li>
                            @empty
                                <li>
                                    <span class="dropdown-item-text text-muted small">services</span>
                                </li>
                            @endforelse
                        </ul>
                    </li>

                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('services') }}">Our Service</a>
                    </li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('domains.index') }}">Domains</a></li>


                    <li class="nav-item"><a class="nav-link fw-semibold" href="{{ route('profile') }}">Profile</a></li>
                    <li class="nav-item"><a class="nav-link fw-semibold" href="#contact">Contact</a></li>

                    @auth
                        @if (auth()->user()->role === 'user')
                            <li class="nav-item">
                                <a class="nav-link fw-semibold" href="{{ route('user.dashboard') }}">Dashboard</a>
                            </li>
                        @endif

                        <li class="nav-item">
                            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn btn-danger rounded-pill ms-lg-3 px-4 fw-bold">Logout</button>
                            </form>
                        </li>
                    @endauth
                </ul>

                @guest
                    <a href="{{ route('login') }}"
                        class="btn btn-success rounded-pill ms-lg-3 px-4 fw-bold get-started">Login</a>
                @endguest


            </div>
        </div>
    </nav>

    <!-- HERO -->
    <header id="home" class="hero position-relative overflow-hidden">
        <div class="container text-start position-relative"> <!-- 👈 changed text-center → text-start -->
            <h1 class="display-4 display-md-3 fw-bold text-white mb-2 hero-title">
                <span style="color:#D81E1E">bara</span><span style="color:#016A4D">bd</span>
                <span class="accent"><span style="color:white">Data</span> Centers</span><br>
                Simple <span class="accent">Hosting</span>
            </h1>

            <p class="lead text-white-75 mx-auto mb-4 hero-subcopy text-start"> <!-- 👈 ensure left aligned -->
                Power your business with sustainable, future-ready data centers.<br class="d-none d-md-inline">
                Experience premium performance, security, and simplicity — all in one.
            </p>

            <a href="#features" class="btn btn-primary rounded-pill px-4 py-2 fw-bold shadow hero-cta">
                View Our Special Offers
            </a>

            <!-- Features row -->
            <div id="features" class="row g-3 justify-content-start features-row mx-auto mt-5">
                <!-- 👈 changed justify-content-center → start -->
                <div class="col-12 col-md-auto">
                    <div class="feature-card glass p-4 rounded-4 text-center">
                        <img src="{{ asset(url('front/images/sheild.png')) }}" alt="Uptime"
                            class="feature-icon mb-3">
                        <h5 class="fw-bold text-white mb-2">99.9% Uptime</h5>
                        <p class="mb-0 small" style="color:white">
                            Guaranteed reliability with enterprise-grade infrastructure
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-auto">
                    <div class="feature-card glass p-4 rounded-4 text-center">
                        <img src="{{ asset(url('front/images/diagram.png')) }}" alt="High Performance"
                            class="feature-icon mb-3">
                        <h5 class="fw-bold text-white mb-2">High Performance</h5>
                        <p class="mb-0 small" style="color:white">
                            Lightning-fast processing for your workloads
                        </p>
                    </div>
                </div>

                <div class="col-12 col-md-auto">
                    <div class="feature-card glass p-4 rounded-4 text-center">
                        <img src="{{ asset(url('front/images/24.png')) }}" alt="24/7 Support"
                            class="feature-icon mb-3">
                        <h5 class="fw-bold text-white mb-2">24/7 Support</h5>
                        <p class="mb-0 small" style="color:white">
                            Expert technical support whenever you need it
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </header>
    <!-- PRICING TOP -->

    @php
        // সুন্দর সংখ্যা ফরম্যাটার
        $fmt = function ($v, $dec = 3) {
            if ($v === null || $v === '') {
                return null;
            }
            $s = number_format((float) $v, $dec, '.', '');
            return rtrim(rtrim($s, '0'), '.');
        };

        // ফিচার টেক্সট বানানোর হেল্পার (short summary)
        $buildFeatureLines = function ($product) use ($fmt) {
            $featureTexts = $product->features
                ->map(function ($f) use ($fmt) {
                    $name = $f->label ?: \Illuminate\Support\Str::title(str_replace('_', ' ', $f->key));

                    $raw = $f->min ?? ($f->value ?? null);
                    $val = $fmt($raw);
                    $unit = $f->unit ? ' ' . $f->unit : '';

                    return trim($name . ($val !== null ? ' ' . $val : '') . $unit);
                })
                ->filter()
                ->values();

            $line1 = '';
            $line2 = '';

            if ($featureTexts->count() >= 2) {
                $line1 = $featureTexts->slice(0, -1)->implode(', ');
                $line2 = $featureTexts->last();
            } elseif ($featureTexts->count() === 1) {
                $line2 = $featureTexts->first();
            }

            return [$line1, $line2];
        };

        // যদি controller থেকে $locations না পাঠাও, তাহলে products থেকে বের করে নাও
        if (!isset($locations)) {
            $locations = $categories
                ->flatMap(function ($cat) {
                    return $cat->products;
                })
                ->flatMap(function ($p) {
                    return $p->basePrices->pluck('location');
                })
                ->unique('id')
                ->values();
        }

        // JS এর জন্য price map বানানো: [product_id][location_id] = {symbol, amount}
        $priceMap = [];
        foreach ($categories as $category) {
            foreach ($category->products as $product) {
                foreach ($product->basePrices as $bp) {
                    $priceMap[$product->id][$bp->location_id] = [
                        'symbol' => $bp->location->currency_symbol ?? '',
                        'amount' => rtrim(rtrim(number_format((float) $bp->amount, 3), '0'), '.'),
                    ];
                }
            }
        }

        $defaultLocationId = $locations[0]->id ?? null;
    @endphp


    <!--  offer section -->

    <section class="offer-section">
        <div class="container">
            <div class="row align-items-center g-4 gx-lg-5">

                <div class="col-lg-6">
                    <h1 class="offer-title">SPECIAL<br>OFFER</h1>
                       @php
                            $offerLabel = $offer?->package_name ?? 'SPECIAL';
                        @endphp
                    <p class="text-white fw-semibold mt-2 fs-4">{{ $offerLabel }}</p>
                    <a class="btn btn-success offer-btn" href="{{ $offerUrl ?? '#' }}">Claim Offer</a>
                </div>

                <!-- RIGHT SIDE (MATRIX EFFECT) -->
                <div class="col-lg-6">
                    <div class="matrix-container">
                        @php
                            $offerPercent = isset($offer?->discount_percent)
                                ? rtrim(rtrim(number_format($offer->discount_percent, 2), '0'), '.')
                                : '50';
                        @endphp
                        <div class="text-center">
                            <h1 class="matrix-text" data-text="{{ $offerPercent }}%">{{ $offerPercent }}%</h1>

                        </div>
                    </div>
                    <div class="text-end mt-3 mt-lg-5">
                        <div class="terms-design d-inline-flex align-items-end fs-6">
                            <span class="mr-2">*Terms &amp; Conditions Apply</span>
                        </div>
                    </div>
                </div>
            </div>
    </section>
    <!--  offer section end -->

    <section id="pricing" class="pricing-section py-5 py-md-5">
        <div class="container">
            <div class="pricing-hero text-center mb-4 mb-md-5">
                <h2 class="pricing-title fw-bold mb-2" data-text="Choose Your Hosting Plan">Choose Your Hosting Plan
                </h2>
                <p class="pricing-sub lead mb-0">
                    Pick the best plan for your website. Optimized pricing across Bangladesh, UAE, EU, US, and Africa.
                </p>
                <div class="mt-3">
                    <a href="{{ route('services') }}" class="service-detail-link">
                        See the full service overview <i class="bi bi-arrow-right-short"></i>
                    </a>
                </div>
            </div>

            @if ($categories->count())
                <div class="pricing-pill-row category-pill-row mb-5">
                    @foreach ($categories as $category)
                        <button type="button" class="pricing-pill category-pill {{ $loop->first ? 'active' : '' }}"
                            data-category-id="{{ $category->id }}" data-category-name="{{ $category->name }}">
                            {{ $category->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="pricing-heading text-center mb-3">
                <h3 class="fw-bold mb-1" id="selectedCategoryTitle">
                    {{ $categories->first()->name ?? 'your category name' }}</h3>
                <p class="text-muted small mb-0">Browse plans by region to see the right currency and pricing.</p>
            </div>

            @if ($locations->count())
                <div class="pricing-pill-row location-pill-row mb-4 justify-content-center">
                    @foreach ($locations as $location)
                        <button type="button" class="pricing-pill location-pill {{ $loop->first ? 'active' : '' }}"
                            data-location-id="{{ $location->id }}">
                            {{ $location->code ?? $location->name }}
                        </button>
                    @endforeach
                </div>
            @endif

            <div class="row g-4 justify-content-center" id="pricingCards">
                @foreach ($categories as $catLoop => $category)
                            @foreach ($category->products as $product)
                                @php
                                    [$line1, $line2] = $buildFeatureLines($product);
                                    $defaultPrice = $priceMap[$product->id][$defaultLocationId]
                                        ?? collect($priceMap[$product->id] ?? [])->first();

                                    if (! $defaultPrice && $product->basePrices->first()) {
                                        $bp = $product->basePrices->first();
                                        $defaultPrice = [
                                            'symbol' => $bp->location?->currency_symbol ?? '',
                                            'amount' => rtrim(rtrim(number_format((float) $bp->amount, 3), '0'), '.'),
                                        ];
                                    }

                                    $isPopular = $loop->iteration === 2;
                                    $featureList = $product->features;
                                @endphp

                        <div class="col-12 col-md-6 col-lg-4 pricing-card-wrapper"
                            data-category-id="{{ $category->id }}" data-product-id="{{ $product->id }}">
                            <div
                                class="plan-card-modern h-100 d-flex flex-column {{ $isPopular ? 'is-popular' : '' }}">
                                @if ($isPopular)
                                    <span class="plan-chip">Popular</span>
                                @endif
                                <div class="plan-head">
                                    <div class="plan-title text-center">
                                        <h5 class="fw-bold mb-1">{{ $product->name }}</h5>
                                        <p class="text-success small mb-0">
                                            {{ isset($product->save_text) ? $product->save_text . '% off' : '0% off' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="plan-price-block text-center mb-3">
                                    <span class="price-symbol">{{ $defaultPrice['symbol'] ?? '' }}</span>
                                    <span class="price-amount">{{ $defaultPrice['amount'] ?? '0' }}</span>
                                    <span class="plan-price-period text-muted">/mo</span>
                                </div>

                                @if ($featureList->count())
                                    @php
                                        $visibleFeatures = $featureList->take(10);
                                        $hiddenFeatures = $featureList->slice(10);
                                    @endphp
                                    <ul class="plan-feature-list">
                                        @foreach ([$visibleFeatures, $hiddenFeatures] as $collectionIndex => $features)
                                            @foreach ($features as $f)
                                                @php
                                                    $name =
                                                        $f->label ?:
                                                        \Illuminate\Support\Str::title(str_replace('_', ' ', $f->key));
                                                    $raw = $f->min ?? ($f->value ?? null);
                                                    $val = $fmt($raw);
                                                    $unit = $f->unit ? ' ' . $f->unit : '';
                                                    $text = trim($name . ($val !== null ? ' ' . $val : '') . $unit);
                                                @endphp
                                                @if ($text)
                                                    <li
                                                        class="{{ $collectionIndex === 1 ? 'extra-feature d-none' : '' }}">
                                                        <span class="check-dot"></span>
                                                        <span>{{ $text }}</span>
                                                    </li>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </ul>
                                    @if ($hiddenFeatures->count())
                                        <button type="button" class="btn btn-link p-0 feature-toggle m-3"
                                            data-expanded="false">
                                            <span class="feature-toggle-text">See all features</span>
                                            <i class="bi bi-chevron-down feature-toggle-icon ms-1"></i>
                                        </button>
                                    @endif
                                @endif

                                <div class="mt-auto">
                                    <a href="{{ route('checkout', ['product' => $product->id, 'location' => $defaultLocationId]) }}"
                                        class="btn plan-cta w-100 js-checkout-link"
                                        data-base-url="{{ route('checkout', ['product' => $product->id]) }}">
                                        Choose Plan
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>


    <!-- ========== PRICING: BOTTOM (Slides 6–9) ========== -->

    <!-- CUSTOMIZE  -->
    <div class="text-center customize-btn-wrap">
        <a href="{{ route('customize') }}" class="btn btn-gradient mt-2 px-4 mb-4">CUSTOMIZE PLAN</a>
    </div>
    </div>

    <!-- CONTACT -->
    <section id="contact" class="contact-section text-white">
        <div class="container py-5 py-lg-6">

            <h2 class="text-center fw-bold mb-5">Contact US</h2>

            <div class="row g-4">
                <!-- Head Office -->
                <div class="col-12 col-lg-6">
                    <h5 class="fw-bold fst-italic text-uppercase mb-3">Head Office</h5>
                    <p class="mb-1"><span class="fw-semibold">Tel:</span> +8801975363707, +8801313714331,
                        +02-7542195</p>
                    <p class="mb-1"><span class="fw-semibold">Email:</span> barvtcinfo@gmail.com |
                        barabdinfo@gmail.com</p>
                    <p class="mb-1"><span class="fw-semibold">Working Hours:</span> Saturday – Thursday (8:00am to
                        06:00pm)</p>
                    <p class="mb-0"><span class="fw-semibold">Address:</span> Rasulpur Tower, 49 Rasulpur, Dania,
                        Jatrabari, Dhaka 1236</p>
                </div>

                <!-- Corporate Office -->
                <div class="col-12 col-lg-6">
                    <h5 class="fw-bold fst-italic text-uppercase mb-3">Corporate Office</h5>
                    <p class="mb-1"><span class="fw-semibold">Tel:</span> +8801975363707 +8801313714331, +02-7542195
                    </p>
                    <p class="mb-1"><span class="fw-semibold">Email:</span> barvtcinfo@gmail.com |
                        barabdinfo@gmail.com</p>
                    <p class="mb-1"><span class="fw-semibold">Working Hours:</span> Saturday – Thursday (8:00am to
                        06:00pm)</p>
                    <p class="mb-0"><span class="fw-semibold">Address:</span> 175, Shahid Syed Nazrul Islam Sarani,
                        Skylark Point, 6th Floor, Dhaka-1000</p>
                </div>

                <!-- Dubai Office -->
                <div class="col-12 col-lg-6">
                    <h5 class="fw-bold fst-italic text-uppercase mt-3 mt-lg-4 mb-3">Dubai Office</h5>
                    <p class="mb-1"><span class="fw-semibold">Tel:</span> +971547674079 +8801975363707</p>
                    <p class="mb-1"><span class="fw-semibold">Email:</span> info@barabdonline.xyz |
                        barabdinfo@gmail.com</p>
                    <p class="mb-1"><span class="fw-semibold">Working Hours:</span> Monday – Saturday (8:00am to
                        06:00pm)</p>
                    <p class="mb-0"><span class="fw-semibold">Address:</span> Humaid Sultan Building, Office No.
                        301, Al Khaleej Road, Deira PO Box 925, Dubai, UAE</p>
                </div>

                <!-- Africa Office -->
                <div class="col-12 col-lg-6">
                    <h5 class="fw-bold fst-italic text-uppercase mt-3 mt-lg-4 mb-3">Africa Office</h5>
                    <p class="mb-1"><span class="fw-semibold">Tel:</span> +23233582524</p>
                    <p class="mb-1"><span class="fw-semibold">Email:</span> barabdinfo@gmail.com</p>
                    <p class="mb-1"><span class="fw-semibold">Working Hours:</span> Monday – Saturday (8:00am to
                        06:00pm)</p>
                    <p class="mb-0"><span class="fw-semibold">Address:</span> Africa Office 28 Charlotte Street, 1st
                        Floor, Freetown, Sierra Leone</p>
                </div>
            </div>
        </div>



        <div class="contact-bottom">
            <div class="footer-divider">
                <span class="footer-divider__text small">
                    Copyright © <span id="year"></span> | All Rights Reserved
                    <span class="fw-semibold">
                        <span class="logo-red">bara</span><span style="color:#016A4D">bd</span>
                    </span>
                </span>
            </div>
        </div>

        <!-- === Scroll to Top Button === -->
        <button id="scrollTopBtn" class="scroll-top" aria-label="Scroll to top">
            <svg viewBox="0 0 24 24" width="22" height="22" aria-hidden="true">
                <path fill="currentColor" d="M12 8l6 6H6z" />
            </svg>
        </button>

        <!-- === WhatsApp Floating Button === -->
        <button id="cbLaunch" class="whatsapp-launcher" aria-label="Chat on WhatsApp" onclick="openWhatsApp()">
            <img src="{{ asset(url('front/images/whats_app5.png')) }}" alt="Get in Touch 24/7" class="whatsapp-img">
        </button>


        <!-- Bootstrap bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
        <!-- Page JS -->
        <script src="{{ asset(url('front/js/app.js')) }}"></script>
        {{-- ======================= SCRIPT ======================= --}}

        <script>
            function openWhatsApp() {
                // Replace with your WhatsApp number (country code + number)
                const number = "8801975363707";
                const message = encodeURIComponent("Hello, I want to chat!");
                const url = `https://wa.me/${number}?text=${message}`;

                window.open(url, "_blank"); // open in new tab
            }

            // JS price data
            window.BARABD_PRICES = @json($priceMap);
            const defaultLocationId = @json($defaultLocationId);
            const initialCategoryId = @json($selectedCategoryId ? (string) $selectedCategoryId : null);

            // CATEGORY FILTER
            (function() {
                const categoryPills = document.querySelectorAll('.category-pill');
                const cards = document.querySelectorAll('.pricing-card-wrapper');
                const categoryTitle = document.getElementById('selectedCategoryTitle');
                const serviceLinks = document.querySelectorAll('.service-category-link');

                const updateTitle = (catId) => {
                    if (!categoryTitle) {
                        return;
                    }
                    const btn = Array.from(categoryPills).find(item => item.dataset.categoryId === String(catId));
                    if (btn) {
                        categoryTitle.textContent = btn.dataset.categoryName || btn.textContent.trim();
                    }
                };

                function setActiveCategory(catId) {
                    categoryPills.forEach(btn => {
                        btn.classList.toggle('active', btn.dataset.categoryId === String(catId));
                    });

                    cards.forEach(card => {
                        card.style.display = (card.dataset.categoryId === String(catId)) ? '' : 'none';
                    });

                    updateTitle(catId);
                }

                if (categoryPills.length) {
                    const hasInitial = initialCategoryId &&
                        Array.from(categoryPills).some(btn => btn.dataset.categoryId === String(initialCategoryId));
                    const firstId = categoryPills[0].dataset.categoryId;
                    setActiveCategory(hasInitial ? initialCategoryId : firstId);
                }

                categoryPills.forEach(btn => {
                    btn.addEventListener('click', () => {
                        setActiveCategory(btn.dataset.categoryId);
                    });
                });

                serviceLinks.forEach(link => {
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        const targetId = link.dataset.serviceCategory;
                        setActiveCategory(targetId);

                        const pricingSection = document.getElementById('pricing');
                        pricingSection?.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });

                        if (typeof bootstrap !== 'undefined') {
                            const dropdownToggle = document.getElementById('servicesDropdown');
                            if (dropdownToggle) {
                                const dropdown = bootstrap.Dropdown.getOrCreateInstance(dropdownToggle);
                                dropdown.hide();
                            }
                            const navCollapse = document.getElementById('topNav');
                            if (navCollapse && navCollapse.classList.contains('show')) {
                                const collapseInstance = bootstrap.Collapse.getOrCreateInstance(
                                    navCollapse);
                                collapseInstance.hide();
                            }
                        }
                    });
                });
            })();

            // LOCATION PRICE SWITCHER
            (function() {
                const locationPills = document.querySelectorAll('.location-pill');

                function setActiveLocation(locId) {
                    locationPills.forEach(btn => {
                        btn.classList.toggle('active', btn.dataset.locationId === String(locId));
                    });

                    document.querySelectorAll('.pricing-card-wrapper').forEach(card => {
                        const productId = card.dataset.productId;
                        const priceInfo = (window.BARABD_PRICES[productId] || {})[locId];

                        const amountEl = card.querySelector('.price-amount');
                        const symbolEl = card.querySelector('.price-symbol');
                        const checkoutLink = card.querySelector('.js-checkout-link');

                        if (priceInfo) {
                            symbolEl.textContent = priceInfo.symbol || '';
                            amountEl.textContent = priceInfo.amount || '0';
                        } else {
                            symbolEl.textContent = '';
                            amountEl.textContent = 'N/A';
                        }

                        if (checkoutLink) {
                            const baseUrl = checkoutLink.dataset.baseUrl || checkoutLink.href;
                            const separator = baseUrl.includes('?') ? '&' : '?';
                            checkoutLink.href = `${baseUrl}${separator}location=${locId}`;
                        }
                    });
                }

                if (locationPills.length) {
                    setActiveLocation(locationPills[0].dataset.locationId || defaultLocationId);
                }

                locationPills.forEach(btn => {
                    btn.addEventListener('click', () => {
                        setActiveLocation(btn.dataset.locationId);
                    });
                });
            })();

            // FEATURE LIST TOGGLER
            (function() {
                document.querySelectorAll('.feature-toggle').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const card = btn.closest('.plan-card-modern');
                        const hiddenItems = card ? card.querySelectorAll('.extra-feature') : [];
                        const expanded = btn.getAttribute('data-expanded') === 'true';
                        const newState = !expanded;

                        hiddenItems.forEach(item => item.classList.toggle('d-none', !newState));
                        btn.setAttribute('data-expanded', newState ? 'true' : 'false');

                        const textEl = btn.querySelector('.feature-toggle-text');
                        if (textEl) {
                            textEl.textContent = newState ? 'Show fewer features' : 'See all features';
                        }

                        const iconEl = btn.querySelector('.feature-toggle-icon');
                        if (iconEl) {
                            iconEl.classList.toggle('bi-chevron-down', !newState);
                            iconEl.classList.toggle('bi-chevron-up', newState);
                        }
                    });
                });
            })();

        </script>
</body>

</html>
