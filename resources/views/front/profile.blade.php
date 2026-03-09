<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Company Profile — Baktier Ahmed Rony & Associates</title>

  <!-- Fonts & Bootstrap (optional if already loaded in layout) -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <link rel="stylesheet" href="{{ asset('front/css/profile.css') }}">
  <link rel="icon" type="image/png" href="{{ asset('front/images/logo.png') }}">

</head>
<body>

  <nav class="navbar navbar-light bg-white border-bottom">
    <div class="container py-2">
      <a class="navbar-brand d-flex align-items-center gap-2" href="{{ url('/') }}">
        <img src="{{ asset('front/images/logo.png') }}" alt="Logo" height="42">
        <span class="fw-bold"><span style="color:#D81E1E;">bara</span><span style="color:#016A4D;">bd Data Center</span></span>
      </a>
      <a class="btn back-home-btn d-flex align-items-center gap-2" href="{{ url('/') }}">
        <span>←</span> <span>Back to Home</span>
      </a>
    </div>
  </nav>

  <main class="py-4 py-lg-5">
    <div class="container">

      <!-- Page title -->
      <header class="text-center mb-4">
        <h1 class="display-6 fw-bold text-ems mb-2">Company Profile</h1>
        <p class="text-muted mb-0">Browse our presentation slides and download the full deck.</p>
      </header>

      <!-- ============ CAROUSEL #1: Company Profile ============ -->
      <section class="profile-block">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h2 class="h5 fw-bold mb-0"><span style="color:#E53935">bara</span><span style="color:#016A4D">bd Data Center Business Plan</span></h2>
        </div>

        <section class="profile-carousel" aria-label="Company profile slides">
          <div class="slide-viewport">
            <div class="carousel-track">
              {{-- Slides 01 → 11 --}}
              @for($i=1;$i<=11;$i++)
                <div class="carousel-slide">
                  <div class="slide-inner">
                    <img src="{{ asset("front/images/ppt/".str_pad($i,2,'0',STR_PAD_LEFT).".jpg") }}" alt="Slide {{ $i }}">
                  </div>
                </div>
              @endfor
            </div>
          </div>

          <button class="carousel-btn prev" type="button" aria-label="Previous slide">‹</button>
          <button class="carousel-btn next" type="button" aria-label="Next slide">›</button>
          <div class="carousel-dots" aria-label="Slide navigation"></div>
        </section>

        <!-- Download button -->
        <div class="text-center mt-3">
          <a href="{{ asset('front/files/company-profile.pdf') }}" class="btn btn-gradient px-4 fw-bold" download>
            Download PPT
          </a>
        </div>
      </section>

      <!-- ============ CAROUSEL #2: Internet Connectivity ============ -->
      <section class="profile-block mt-5">
        <div class="d-flex align-items-center justify-content-between mb-2">
          <h2 class="h5 fw-bold mb-0">Internet Connectivity Plan</h2>
        </div>

        <section class="profile-carousel" aria-label="Internet Connectivity slides">
          <div class="slide-viewport">
            <div class="carousel-track">
              {{-- Slides 12 → 19 --}}
              @for($i=12;$i<=19;$i++)
                <div class="carousel-slide">
                  <div class="slide-inner">
                    <img src="{{ asset("front/images/ppt/".$i.".jpg") }}" alt="Connectivity slide {{ $i }}">
                  </div>
                </div>
              @endfor
            </div>
          </div>

          <button class="carousel-btn prev" type="button" aria-label="Previous slide">‹</button>
          <button class="carousel-btn next" type="button" aria-label="Next slide">›</button>
          <div class="carousel-dots" aria-label="Slide navigation"></div>
        </section>
      </section>

              <!-- Download button -->
        <div class="text-center mt-3">
          <a href="{{ asset('front/files/company-profile.pdf') }}" class="btn btn-gradient px-4 fw-bold" download>
            Download PPT
          </a>
        </div>
      </section>


<!-- ============ CAROUSEL #3: Data Center Infrastructure (20–34) ============ -->
<section class="profile-block mt-5">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h2 class="h5 fw-bold mb-0">Business Plan</h2>
  </div>

  <section class="profile-carousel" aria-label="Infrastructure slides">
    <div class="slide-viewport">
      <div class="carousel-track">
        {{-- Slides 20 → 34 --}}
        @for($i = 20; $i <= 34; $i++)
          <div class="carousel-slide">
            <div class="slide-inner">
              <img src="{{ asset('front/images/ppt/'.$i.'.jpg') }}" alt="Infrastructure slide {{ $i }}">
            </div>
          </div>
        @endfor
      </div>
    </div>

    <button class="carousel-btn prev" type="button" aria-label="Previous slide">‹</button>
    <button class="carousel-btn next" type="button" aria-label="Next slide">›</button>
    <div class="carousel-dots" aria-label="Slide navigation"></div>
  </section>
</section>

<!-- (optional) a download button under the 3rd carousel -->
<div class="text-center mt-3">
  <a href="{{ asset('front/files/infrastructure.pdf') }}" class="btn btn-gradient px-4 fw-bold" download>
    Download PPT
  </a>
</div>


<!-- ============ CAROUSEL #4: Layout Plan (35–44) ============ -->
<section class="profile-block mt-5">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h2 class="h5 fw-bold mb-0">Layout Plan</h2>
  </div>

  <section class="profile-carousel" aria-label="Infrastructure slides">
    <div class="slide-viewport">
      <div class="carousel-track">
        {{-- Slides 35 → 44 --}}
        @for($i = 35; $i <= 44; $i++)
          <div class="carousel-slide">
            <div class="slide-inner">
              <img src="{{ asset('front/images/ppt/'.$i.'.jpg') }}" alt="Infrastructure slide {{ $i }}">
            </div>
          </div>
        @endfor
      </div>
    </div>

    <button class="carousel-btn prev" type="button" aria-label="Previous slide">‹</button>
    <button class="carousel-btn next" type="button" aria-label="Next slide">›</button>
    <div class="carousel-dots" aria-label="Slide navigation"></div>
  </section>
</section>

<!-- (optional) a download button under the 3rd carousel -->
<div class="text-center mt-3">
  <a href="{{ asset('front/files/infrastructure.pdf') }}" class="btn btn-gradient px-4 fw-bold" download>
    Download PPT
  </a>
</div>


<!-- ============ CAROUSEL #5: Layout Plan (45–56) ============ -->
<section class="profile-block mt-5">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h2 class="h5 fw-bold mb-0">Security Poclicy</h2>
  </div>

  <section class="profile-carousel" aria-label="Infrastructure slides">
    <div class="slide-viewport">
      <div class="carousel-track">
        {{-- Slides 45 → 56 --}}
        @for($i = 45; $i <= 56; $i++)
          <div class="carousel-slide">
            <div class="slide-inner">
              <img src="{{ asset('front/images/ppt/'.$i.'.jpg') }}" alt="Infrastructure slide {{ $i }}">
            </div>
          </div>
        @endfor
      </div>
    </div>

    <button class="carousel-btn prev" type="button" aria-label="Previous slide">‹</button>
    <button class="carousel-btn next" type="button" aria-label="Next slide">›</button>
    <div class="carousel-dots" aria-label="Slide navigation"></div>
  </section>
</section>

<!-- (optional) a download button under the 3rd carousel -->
<div class="text-center mt-3">
  <a href="{{ asset('front/files/infrastructure.pdf') }}" class="btn btn-gradient px-4 fw-bold" download>
    Download PPT
  </a>
</div>



<!-- ============ CAROUSEL #6: Technical Proposal (57–67) ============ -->
<section class="profile-block mt-5">
  <div class="d-flex align-items-center justify-content-between mb-2">
    <h2 class="h5 fw-bold mb-0">Technical Proposal</h2>
  </div>

  <section class="profile-carousel" aria-label="Infrastructure slides">
    <div class="slide-viewport">
      <div class="carousel-track">
        {{-- Slides 57 → 67 --}}
        @for($i = 57; $i <= 67; $i++)
          <div class="carousel-slide">
            <div class="slide-inner">
              <img src="{{ asset('front/images/ppt/'.$i.'.jpg') }}" alt="Infrastructure slide {{ $i }}">
            </div>
          </div>
        @endfor
      </div>
    </div>

    <button class="carousel-btn prev" type="button" aria-label="Previous slide">‹</button>
    <button class="carousel-btn next" type="button" aria-label="Next slide">›</button>
    <div class="carousel-dots" aria-label="Slide navigation"></div>
  </section>
</section>

<!-- (optional) a download button under the 3rd carousel -->
<div class="text-center mt-3">
  <a href="{{ asset('front/files/infrastructure.pdf') }}" class="btn btn-gradient px-4 fw-bold" download>
    Download PPT
  </a>
</div>



    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script src="{{ asset('front/js/profile.js') }}"></script>
</body>
</html>

