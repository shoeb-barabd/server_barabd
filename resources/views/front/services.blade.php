<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Our Services | barabd Data Centers</title>
    <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">

    <!-- Fonts & Bootstrap -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Page styles -->
    <link rel="stylesheet" href="{{ asset(url('front/css/styles.css')) }}">
    <style>
        .service-card-hover {
            transition: transform 0.22s ease, box-shadow 0.22s ease;
        }
        .service-card-hover:hover {
            transform: translateY(-6px);
            box-shadow: 0 22px 55px rgba(0, 0, 0, 0.12);
        }
        .service-card-hero {
            background: radial-gradient(circle at 15% 20%, rgba(14,165,233,0.08), transparent 38%),
                        radial-gradient(circle at 85% 15%, rgba(22,163,74,0.08), transparent 32%),
                        radial-gradient(circle at 80% 80%, rgba(239,68,68,0.08), transparent 30%),
                        linear-gradient(135deg, #fdfefe 0%, #f5f8fb 100%);
            border: 1px solid #e5edf5;
            border-radius: 20px;
        }
        .service-card-hero h4 {
            color: #0b1f33;
        }
        .service-accent-line {
            display: block;
            width: 100%;
            height: 4px;
            border-radius: 999px;
            margin-bottom: 16px;
        }
        .service-icon {
            width: 54px;
            height: 54px;
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            margin-bottom: 14px;
        }
        /* Red, Green, Blue palette */
        .accent-red { background: #ef4444; }
        .accent-green { background: #16a34a; }
        .accent-blue { background: #0ea5e9; }
        .accent-line-red { background: #ffe2e5; }
        .accent-line-green { background: #d6f5e3; }
        .accent-line-blue { background: #d8f1ff; }
        .border-accent-red { border-color: #ef4444 !important; }
        .border-accent-green { border-color: #16a34a !important; }
        .border-accent-blue { border-color: #0ea5e9 !important; }
    </style>
</head>
<body class="services-page">
@php
    use Illuminate\Support\Str;

    $fmt = function ($value) {
        if ($value === null || $value === '') {
            return null;
        }
        $s = number_format((float)$value, 3, '.', '');
        return rtrim(rtrim($s, '0'), '.');
    };

    $anchorFor = function ($category) {
        $base = $category->slug ?: $category->name ?: 'service-'.$category->id;
        $slug = Str::slug($base);
        return $slug ?: 'service-'.$category->id;
    };

@endphp

<!-- Fixed Services Nav -->
<nav class="navbar navbar-expand-lg services-nav fixed-top shadow-sm bg-white">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('index') }}">
            <img src="{{ asset(url('front/images/logo.png')) }}" alt="Logo" height="38">
            <span class="fw-bold brand-mark mb-0">
                <span class="brand-red">bara</span><span style="color:#016A4D">bd Data Center</span>
            </span>
        </a>

        <div class="d-flex align-items-center ms-auto gap-2">
            <a href="{{ route('index') }}" class="btn btn-success rounded-pill back-home-btn">
                <i class="bi bi-arrow-left me-1"></i>Back to Home
            </a>
        </div>
    </div>
</nav>

<main class="services-main">
    <section class="service-hero">
        <div class="container">
            <div class="row align-items-center g-4">
                <div class="col-lg-6">
                    <p class="service-kicker fs-2 ">Our Service</p>
                    <h1 class="display-5 fw-bold mb-3">The full story of how we host, protect, and scale</h1>
                    <p class="lead text-muted mb-4">
                        Explore every layer of our data center and hosting services in detail. From resilient compute to managed security
                        and global regions, this page walks you through what your team gets.
                    </p>
                    <div class="d-flex flex-wrap gap-3">
                        <a href="#service-details" class="btn btn-success px-4 shadow-sm">
                            Explore services <i class="bi bi-arrow-down-short ms-1"></i>
                        </a>
                        <a href="{{ route('index') }}#pricing" class="btn btn-outline-success px-4">
                            Jump to pricing on Home
                        </a>
                    </div>
                    <div class="service-badges mt-4 d-flex flex-wrap gap-2">
                        <span class="service-pill"><i class="bi bi-globe2 me-1"></i>Bangladesh • UAE • EU • US • Africa</span>
                        <span class="service-pill"><i class="bi bi-shield-lock me-1"></i>Compliance-ready</span>
                        <span class="service-pill"><i class="bi bi-lightning-charge me-1"></i>Always-on monitoring</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="service-hero-card reveal">
                        <div class="d-flex align-items-center justify-content-between mb-3">
                            <div>
                                <p class="text-muted small mb-1">What you unlock</p>
                                <h5 class="fw-bold mb-0 text-ink">Managed, resilient, secure</h5>
                            </div>
                            <span class="badge rounded-pill bg-success-subtle text-success fw-semibold">
                                Live 24/7
                            </span>
                        </div>
                        <ul class="list-unstyled mb-3 service-checklist">
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Tiered infrastructure with proactive monitoring</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Flexible compute + storage bundles per region</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Security, backups, and compliance baked in</li>
                            <li><i class="bi bi-check-circle-fill text-success me-2"></i>Human support that answers in minutes</li>
                        </ul>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="mini-badge"><i class="bi bi-cpu me-1"></i>High performance</span>
                            <span class="mini-badge"><i class="bi bi-cloud-arrow-up me-1"></i>Multi-cloud ready</span>
                            <span class="mini-badge"><i class="bi bi-emoji-smile me-1"></i>Human SLA</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-overview">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4 reveal">
                    <div class="service-card h-100">
                        <div class="icon-circle bg-success-subtle text-success mb-3">
                            <i class="bi bi-diagram-3"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Elastic infrastructure</h5>
                        <p class="mb-0 text-muted">Scale compute, memory, and storage independently. Mix bare metal and virtual resources across our regions.</p>
                    </div>
                </div>
                <div class="col-md-4 reveal">
                    <div class="service-card h-100">
                        <div class="icon-circle bg-primary-subtle text-primary mb-3">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Security-first design</h5>
                        <p class="mb-0 text-muted">Layered firewalls, DDoS protection, encrypted backups, and governance aligned with your compliance needs.</p>
                    </div>
                </div>
                <div class="col-md-4 reveal">
                    <div class="service-card h-100">
                        <div class="icon-circle bg-danger-subtle text-danger mb-3">
                            <i class="bi bi-headset"></i>
                        </div>
                        <h5 class="fw-bold mb-2">Managed by experts</h5>
                        <p class="mb-0 text-muted">24/7 engineers, guided migrations, and performance tuning so you stay focused on your product.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="service-journey">
        <div class="container">
            <div class="row g-4 align-items-center">
                <div class="col-lg-5 reveal">
                    <p class="service-kicker mb-2">How we deliver</p>
                    <h3 class="fw-bold mb-3">From onboarding to day-2 operations</h3>
                    <p class="text-muted mb-4">A simple, transparent sequence keeps projects moving. Expect proactive comms, clear cutover windows, and post-launch tuning.</p>
                    <div class="d-flex gap-3 flex-wrap">
                        <span class="mini-badge"><i class="bi bi-clock-history me-1"></i>Rapid setup</span>
                        <span class="mini-badge"><i class="bi bi-shield-lock me-1"></i>Hardened baseline</span>
                        <span class="mini-badge"><i class="bi bi-graph-up me-1"></i>Performance SLOs</span>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="journey-steps">
                        <div class="journey-step reveal">
                            <div class="step-dot"></div>
                            <div>
                                <h6 class="fw-bold mb-1">Assess & architect</h6>
                                <p class="mb-0 text-muted">We map your workloads, resilience goals, and data residency to the right regions and capacity model.</p>
                            </div>
                        </div>
                        <div class="journey-step reveal">
                            <div class="step-dot"></div>
                            <div>
                                <h6 class="fw-bold mb-1">Provision & secure</h6>
                                <p class="mb-0 text-muted">Deploy infrastructure with hardened images, access controls, monitoring, and backup policies from day one.</p>
                            </div>
                        </div>
                        <div class="journey-step reveal">
                            <div class="step-dot"></div>
                            <div>
                                <h6 class="fw-bold mb-1">Migrate & optimize</h6>
                                <p class="mb-0 text-muted">Guided migrations with performance baselines, stress tests, and right-sizing recommendations.</p>
                            </div>
                        </div>
                        <div class="journey-step reveal">
                            <div class="step-dot"></div>
                            <div>
                                <h6 class="fw-bold mb-1">Operate & support</h6>
                                <p class="mb-0 text-muted">24/7 monitoring, incident response, and quarterly reviews to keep your stack healthy.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

            <section id="service-details" class="service-details">
        <div class="container">
            <div class="text-center mb-5">
                <p class="service-kicker mb-1">Service catalog</p>
                <h3 class="fw-bold mb-2">All the ways we support your workloads</h3>
                <p class="text-muted mb-0">Browse the plans by category to see how we operate, support, and scale each offering. Pricing and specs live on the Home page.</p>
            </div>

            @php
                // Static list: edit names/descriptions directly here (no DB/link needed).
                $staticServices = [
                    ['id' => 'shared-hosting', 'name' => 'Shared Hosting', 'desc' => 'Shared Hosting is built for starter sites that still need real uptime and dependable support. You get daily backups, soft resource caps with alerts before overages, CDN-friendly caching, and a clean, beginner-friendly control panel so non-technical teammates can publish safely. We harden the stack, monitor everything 24/7, and keep you informed before small issues turn into outages. It’s ideal for blogs, portfolios, small business pages, and new brands looking to launch smoothly without high costs. Automatic updates and security patches run in the background, performance stays optimized, and you can upgrade seamlessly as traffic grows—all while having reliable support ready whenever you need it.', 'icon' => 'bi-hdd-stack', 'accent' => 'green'],
                    ['id' => 'vps-hosting', 'name' => 'VPS Hosting', 'desc' => 'VPS Hosting gives you dedicated resources, full control, and higher performance compared to shared hosting—perfect for growing businesses, busy websites, or custom applications. You get your own virtual environment with guaranteed CPU, RAM, and storage, so traffic spikes from others never slow you down. With root access, you can install software, configure servers, and run advanced workloads securely. Automatic backups, real-time monitoring, and hardened security keep your data protected, while scalable plans let you upgrade instantly as your needs grow. It’s the right balance of power, flexibility, and cost-efficiency for teams that want more freedom without managing physical hardware.', 'icon' => 'bi-cpu', 'accent' => 'blue'],
                    ['id' => 'storage-service', 'name' => 'Storage Service', 'desc' => 'Storage Service is designed for safely keeping large files, media assets, backups, and business data in a secure, high-availability environment. Your data lives on redundant servers with protection against loss, corruption, and hardware failure. Fast upload and download speeds make it easy to collaborate, archive projects, or integrate storage with apps and websites. With scalable capacity, you can start small and expand anytime—without worrying about space limitations. Role-based access, encryption, and versioning ensure your files stay safe, organized, and recoverable whenever you need them.', 'icon' => 'bi-hdd-network', 'accent' => 'red'],
                    ['id' => 'email-services', 'name' => 'Email Services', 'desc' => 'Email Services provide professional, reliable communication with custom domains, spam protection, and high inbox deliverability. You get a clean, secure email environment with antivirus filtering, generous storage, and multi-device access so your team can communicate from anywhere. Business-focused features like aliases, shared mailboxes, and calendar syncing make collaboration seamless. Automatic backups and strong authentication protect sensitive messages, while a simple dashboard helps you manage users and settings without technical complexity. It’s a polished, trustworthy email solution that supports branding, security, and day-to-day productivity.', 'icon' => 'bi-envelope-check', 'accent' => 'green'],
                    ['id' => 'backup-solutions', 'name' => 'Backup Solutions', 'desc' => 'Backup Solutions protect your business data from loss, corruption, or accidental deletion by creating automatic copies stored in secure, redundant locations. Whether it’s website files, databases, emails, or application data, backups run quietly in the background and allow fast restoration whenever something goes wrong. Version history helps you roll back to earlier states, while off-site storage ensures safety even during hardware failures or disasters. It’s a worry-free layer of insurance that keeps your operations running without interruptions.', 'icon' => 'bi-cloud-arrow-down', 'accent' => 'blue'],
                    ['id' => 'server-solution', 'name' => 'Server Solution', 'desc' => 'Server Solution provides reliable, high-performance infrastructure for businesses that need more power, stability, and customization. You get dedicated resources, optimized configurations, and the flexibility to run web apps, databases, ERP systems, or enterprise workloads at scale. With professional setup, migration assistance, and ongoing maintenance, the server environment stays fast, secure, and ready for growth. It’s designed to give organizations full control and long-term performance without the cost and complexity of managing physical hardware on their own.', 'icon' => 'bi-server', 'accent' => 'red'],
                    ['id' => 'security-and-monitoring', 'name' => 'Security & Monitoring', 'desc' => 'Security & Monitoring focuses on safeguarding your systems against threats while keeping everything visible and under control. Real-time monitoring, firewalls, malware protection, and intrusion detection help identify issues before they become critical. Continuous audits, patching, and alerts ensure vulnerabilities are fixed quickly, while uptime tracking and performance analytics keep your services stable. The goal is simple: prevent attacks, detect anomalies early, and maintain a secure, healthy environment 24/7.', 'icon' => 'bi-shield-lock', 'accent' => 'green'],
                    ['id' => 'dev-services', 'name' => 'Dev Services', 'desc' => 'Dev Services support businesses that need development expertise for building, optimizing, or automating digital platforms. From deploying applications and managing CI/CD pipelines to integrating APIs, optimizing performance, or handling version control, these services streamline the entire development workflow. Teams get faster releases, cleaner code, better collaboration, and fewer deployment errors. It’s a productivity boost that helps ideas move to production smoothly and efficiently.', 'icon' => 'bi-gear-wide-connected', 'accent' => 'blue'],
                    ['id' => 'networking-and-datacenter', 'name' => 'Networking & Datacenter', 'desc' => 'Networking & Datacenter ensures fast, reliable connectivity and robust infrastructure for mission-critical systems. High-speed networks, redundant routing, and enterprise-grade hardware keep data flowing without bottlenecks or downtime. Datacenter facilities provide climate control, power backup, physical security, and scalable rack space to support servers and storage at any size. The result is a stable, always-on environment where applications, websites, and services can operate with maximum performance, resilience, and global reach.', 'icon' => 'bi-diagram-3', 'accent' => 'red'],
                ];
            @endphp

            @foreach($staticServices as $service)
                @php
                    $accent = $service['accent'] ?? 'green';
                @endphp
                <div id="{{ $service['id'] }}" class="reveal mb-4">
                    <div class="card border-0 h-100 overflow-hidden service-card-hover service-card-hero border-accent-{{ $accent }}">
                        <div class="card-body p-4 text-center">
                            <span class="service-accent-line accent-line-{{ $accent }}"></span>
                            <div class="service-icon text-white accent-{{ $accent }}">
                                <i class="bi {{ $service['icon'] ?? 'bi-star' }}"></i>
                            </div>
                            <h4 class="fw-bold mb-1">{{ $service['name'] }}</h4>
                            <p class="text-muted mb-3">{{ $service['desc'] }}</p>
                            <a class="btn btn-success rounded-pill px-4" href="{{ route('index') }}#pricing">View plans & pricing</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <footer class="about-footer text-center text-white-75 py-4 mt-5">
        <div class="container small">
            © <span id="aboutYear"></span>
            <span class="brand-red">bara</span><span class="brand-green">bd</span> Data Center
        </div>
    </footer>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                }
            });
        }, { threshold: 0.15 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        const yearEl = document.getElementById('aboutYear');
        if (yearEl) {
            yearEl.textContent = new Date().getFullYear();
        }
    });
</script>
</body>
</html>
