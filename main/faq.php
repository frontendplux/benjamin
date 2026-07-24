<?php
include __DIR__."/header.php";
?>
<section class="py-5 text-center d-flex align-items-center justify-content-center position-relative overflow-hidden" style="background-color: #0f2b1d; border-bottom: 3px solid #198754; min-height: 260px;">
  
  <!-- Subtle Background Geometric Element Accents -->
  <div class="position-absolute top-0 start-0 w-100 h-100 opacity-10 pointer-events-none">
    <div class="position-absolute top-50 start-0 translate-middle bg-success rounded-circle" style="width: 300px; height: 300px; filter: blur(80px);"></div>
    <div class="position-absolute top-0 end-0 bg-success rounded-circle" style="width: 200px; height: 200px; filter: blur(60px); margin-top: -50px; margin-right: -50px;"></div>
  </div>

  <div class="container position-relative z-1 py-3">
    
    <!-- Hero Section Primary Header Title -->
    <h1 class="display-5 fw-bold text-white mb-3 tracking-tight">Frequently Asked Questions</h1>
    
    <!-- Inline Subtext Description Segment -->
    <p class="text-success text-opacity-75 small mx-auto mb-4" style="max-width: 520px; font-size: 14px;">
      Find answers to common inquiries regarding Bright part Investment's operations, strategy, and client experience.
    </p>

    <!-- CENTERED BREADCRUMB NAVIGATION LAYOUT (Home / FAQ) -->
    <nav aria-label="breadcrumb" class="d-inline-block">
      <ol class="breadcrumb mb-0 bg-white bg-opacity-10 px-4 py-2 rounded-pill border border-success border-opacity-25 shadow-sm">
        <li class="breadcrumb-item">
          <a href="/home" class="text-white gap-2 text-opacity-75 text-decoration-none small d-inline-flex align-items-center transition-all header-breadcrumb-link">
            <i class="bi bi-house-door me-1.5"></i> Home
          </a>
        </li>
        <li class="breadcrumb-item active text-success small fw-semibold" aria-current="page">FAQ</li>
      </ol>
    </nav>

  </div>
</section>

<!-- Smooth interface transitions for standard micro-interactions -->
<style>
  .header-breadcrumb-link:hover {
    color: #198754 !important;
    opacity: 1 !important;
  }
  /* Cleans up the native Bootstrap breadcrumb forward-slash divider color to display nicely over green */
  .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.3) !important;
  }
  /* Custom overrides for smooth accordion appearance matching the platform style */
  .accordion-button:not(.collapsed) {
    background-color: rgba(25, 135, 84, 0.1) !important;
    color: #0f2b1d !important;
    box-shadow: none !important;
  }
  .accordion-button:focus {
    box-shadow: none !important;
    border-color: rgba(25, 135, 84, 0.25) !important;
  }
</style>

<div class="bg-light min-vh-100 py-5">
  <div class="container py-3">

    <!-- HERO HEADER: Core Section Title -->
    <div class="text-center mb-5">
      <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-semibold mb-2">Help Center</span>
      <h2 class="display-6 fw-bold text-dark mb-3">How Can We Help You?</h2>
      <p class="text-muted small mx-auto" style="max-width: 520px;">We have compiled clear answers to the most frequent structural, strategic, and financial questions from our global community.</p>
      <hr class="mx-auto border-success opacity-25" style="width: 80px; height: 3px;">
    </div>

    <!-- ACCORDION WRAPPER CONTAINER -->
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="accordion accordion-flush shadow-sm rounded-4 overflow-hidden border-0 bg-white" id="faqAccordion">

          <!-- FAQ Item 1 -->
          <div class="accordion-item border-bottom border-light-subtle">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold py-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseOne" aria-expanded="false" aria-controls="faq-collapseOne">
                What is Bright Part Investment's primary focus?
              </button>
            </h2>
            <div id="faq-collapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted lh-lg small py-4">
                Bright Part Investment is a global investment platform dedicated to delivering sophisticated financial solutions, fostering sustainable growth, and creating long-term value for investors. We leverage advanced technological frameworks and data-driven market insights to connect capital with high-yield opportunities across several key economic sectors.
              </div>
            </div>
          </div>

          <!-- FAQ Item 2 -->
          <div class="accordion-item border-bottom border-light-subtle">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold py-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseTwo" aria-expanded="false" aria-controls="faq-collapseTwo">
                In which core areas or sectors do you invest capital?
              </button>
            </h2>
            <div id="faq-collapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted lh-lg small py-4">
                Our active allocation strategy spans eight major economic vectors: Global Equities, Digital Innovation (including Cloud networks, Cybersecurity, and AI software), Real Estate, Essential Infrastructure, Renewable Energy transitions, Private Business Growth markets, Strategic Assets management, and emerging structural macroeconomic transitions.
              </div>
            </div>
          </div>

          <!-- FAQ Item 3 -->
          <div class="accordion-item border-bottom border-light-subtle">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold py-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseThree" aria-expanded="false" aria-controls="faq-collapseThree">
                How does your investment philosophy mitigate client risk?
              </button>
            </h2>
            <div id="faq-collapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted lh-lg small py-4">
                We operate through a rigid four-pillar mechanism: 
                <ul class="mt-2 mb-0 ps-3">
                  <li><strong>Research-Driven Logic:</strong> Every option is carefully filtered using massive data metrics prior to terminal matching.</li>
                  <li><strong>Diversified Exposure:</strong> Spreading asset placement across disconnected pipelines protects against standard node drops.</li>
                  <li><strong>Active Risk Discovery:</strong> Real-time structural auditing matrices are built into all executions.</li>
                  <li><strong>Long-Term Horizons:</strong> Prioritizing compound performance over short-term volatility thresholds.</li>
                </ul>
              </div>
            </div>
          </div>

          <!-- FAQ Item 4 -->
          <div class="accordion-item border-bottom border-light-subtle">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold py-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseFour" aria-expanded="false" aria-controls="faq-collapseFour">
                How is transparency and data security ensured on your platform?
              </button>
            </h2>
            <div id="faq-collapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted lh-lg small py-4">
                Transparency and safety are foundational rules within our organization. We continually allocate resources directly into deep technology security systems to sustain a highly resilient ecosystem. Clients receive completely clear, objective, and trackable reports outlining every single aspect of their digital investment journey.
              </div>
            </div>
          </div>

          <!-- FAQ Item 5 -->
          <div class="accordion-item border-0">
            <h2 class="accordion-header">
              <button class="accordion-button collapsed fw-semibold py-4 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#faq-collapseFive" aria-expanded="false" aria-controls="faq-collapseFive">
                How do I begin exploring active investment configurations?
              </button>
            </h2>
            <div id="faq-collapseFive" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
              <div class="accordion-body text-muted lh-lg small py-4">
                Getting started is simple. You can review our tailored options directly by navigating to our active configurations or clicking the call-to-action button down below. Our portal layout will walk you sequentially through the required prerequisites.
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <!-- CALL TO ACTION BANNER -->
    <div class="text-center bg-white border border-light-subtle rounded-4 p-4 p-md-5 shadow-sm mt-5 mx-auto" style="max-width: 850px;">
      <h3 class="h4 fw-bold text-dark mb-2">Still Have Questions?</h3>
      <p class="text-muted small mb-4 mx-auto" style="max-width: 580px;">If your inquiry wasn't handled within our core matrix rules, please connect with our operational help desk for personalized assistance.</p>
      <div class="d-flex flex-wrap gap-3 justify-content-center">
        <a href="#" class="btn btn-success px-4 py-2.5 small fw-medium rounded-3 shadow-sm">
          Explore Investment Plans <i class="bi bi-arrow-right-short ms-1"></i>
        </a>
        <a href="#" class="btn btn-outline-success px-4 py-2.5 small fw-medium rounded-3">
          Contact Support Desk
        </a>
      </div>
    </div>

  </div>
</div>
<?php  include __DIR__."/footer.php"; ?>