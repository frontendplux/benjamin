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
    <h1 class="display-5 fw-bold text-white mb-3 tracking-tight">Investment Portfolios</h1>
    
    <!-- Inline Subtext Description Segment -->
    <p class="text-success text-opacity-75 small mx-auto mb-4" style="max-width: 520px; font-size: 14px;">
      Explore our optimized capital configurations designed to match your risk tolerance and duration targets.
    </p>

    <!-- CENTERED BREADCRUMB NAVIGATION LAYOUT (Home / Investment Plans) -->
    <nav aria-label="breadcrumb" class="d-inline-block">
      <ol class="breadcrumb mb-0 bg-white bg-opacity-10 px-4 py-2 rounded-pill border border-success border-opacity-25 shadow-sm">
        <li class="breadcrumb-item">
          <a href="/home" class="text-white text-opacity-75 text-decoration-none small d-inline-flex align-items-center transition-all header-breadcrumb-link">
            <i class="bi bi-house-door me-1"></i> Home
          </a>
        </li>
        <li class="breadcrumb-item active text-success small fw-semibold" aria-current="page">Investment Plans</li>
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
  .breadcrumb-item + .breadcrumb-item::before {
    color: rgba(255, 255, 255, 0.3) !important;
  }
  .plan-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
  }
  .plan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 .5rem 1.5rem rgba(0,0,0,0.08)!important;
  }
</style>

<div class="bg-light min-vh-100 py-5">
  <div class="container py-3">

    <!-- HERO HEADER: Core Branding Title -->
    <div class="text-center mb-5">
      <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-semibold mb-2">Our Framework</span>
      <h2 class="display-6 fw-bold text-dark mb-3">Select Your Strategy</h2>
      <p class="text-muted small mx-auto" style="max-width: 540px;">Deploy your capital across structured nodes optimized for consistent compound performance thresholds.</p>
      <hr class="mx-auto border-success opacity-25" style="width: 80px; height: 3px;">
    </div>

    <!-- MAIN PLANS GRID LAYOUT -->
    <div class="row g-4 justify-content-center">

      <!-- Plan 1: Starter Growth -->
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 plan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1.5 fw-semibold font-monospace" style="font-size: 11px;">Tier 01</span>
            <span class="text-success fw-bold fs-5">+10%</span>
          </div>
          <h3 class="fw-bold text-dark h5 mb-2">Starter Growth Plan</h3>
          <p class="text-muted mb-4 small" style="line-height: 1.5; min-height: 60px;">Ideal for beginners testing the platform with short-term exposure and stable growth opportunities.</p>
          
          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Min Capital:</span><span class="fw-semibold text-dark">$60</span></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Max Capital:</span><span class="fw-semibold text-dark">$400</span></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Duration:</span><span class="fw-medium text-success">48 Hours</span></div>
          </div>
          <a href="#" class="btn btn-success w-100 py-2.5 small fw-medium rounded-3 mt-auto">Initialize Plan</a>
        </div>
      </div>

      <!-- Plan 2: Silver Steady -->
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 plan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1.5 fw-semibold font-monospace" style="font-size: 11px;">Tier 02</span>
            <span class="text-success fw-bold fs-5">+15%</span>
          </div>
          <h3 class="fw-bold text-dark h5 mb-2">Silver Steady Plan</h3>
          <p class="text-muted mb-4 small" style="line-height: 1.5; min-height: 60px;">Balanced plan for users seeking moderate growth with controlled market exposure.</p>
          
          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Min Capital:</span><span class="fw-semibold text-dark">$400</span></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Max Capital:</span><span class="fw-semibold text-dark">$800</span></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Duration:</span><span class="fw-medium text-success">3 Days</span></div>
          </div>
          <a href="#" class="btn btn-success w-100 py-2.5 small fw-medium rounded-3 mt-auto">Initialize Plan</a>
        </div>
      </div>

      <!-- Plan 3: Gold Advantage (Highlighted/Promo) -->
      <div class="col-md-6 col-lg-4">
        <div class="card border border-2 border-success shadow-sm rounded-4 bg-white p-4 h-100 plan-card position-relative">
          <span class="position-absolute top-0 start-50 translate-middle badge bg-success text-white rounded-pill px-3 py-1.5 fw-medium small shadow-sm" style="font-size: 11px;">Weekend Active</span>
          <div class="d-flex justify-content-between align-items-center mb-3 mt-1">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1.5 fw-semibold font-monospace" style="font-size: 11px;">Tier 03</span>
            <span class="text-success fw-bold fs-5">+30%</span>
          </div>
          <h3 class="fw-bold text-dark h5 mb-2">Gold Advantage Plan</h3>
          <p class="text-muted mb-4 small" style="line-height: 1.5; min-height: 60px;">Designed for consistent daily growth using diversified crypto investment strategies.</p>
          
          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Min Capital:</span><span class="fw-semibold text-dark">$800</span></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Max Capital:</span><span class="fw-semibold text-dark">$1,500</span></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Duration:</span><span class="fw-medium text-success">30 Hours</span></div>
          </div>
          <a href="#" class="btn btn-success w-100 py-2.5 small fw-medium rounded-3 mt-auto shadow-sm">Initialize Plan</a>
        </div>
      </div>

      <!-- Plan 4: Weekly Yield -->
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 plan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1.5 fw-semibold font-monospace" style="font-size: 11px;">Tier 04</span>
            <span class="text-success fw-bold fs-5">+40%</span>
          </div>
          <h3 class="fw-bold text-dark h5 mb-2">Weekly Yield Plan</h3>
          <p class="text-muted mb-4 small" style="line-height: 1.5; min-height: 60px;">Targets higher returns through active trading, staking, and portfolio management techniques.</p>
          
          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Min Capital:</span><span class="fw-semibold text-dark">$1,500</span></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Max Capital:</span><span class="fw-semibold text-dark">$2,500</span></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Duration:</span><span class="fw-medium text-success">7 Days</span></div>
          </div>
          <a href="#" class="btn btn-success w-100 py-2.5 small fw-medium rounded-3 mt-auto">Initialize Plan</a>
        </div>
      </div>

      <!-- Plan 5: Premium Investors -->
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 plan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1.5 fw-semibold font-monospace" style="font-size: 11px;">Tier 05</span>
            <span class="text-success fw-bold fs-5">+50%</span>
          </div>
          <h3 class="fw-bold text-dark h5 mb-2">Premium Investors Plan</h3>
          <p class="text-muted mb-4 small" style="line-height: 1.5; min-height: 60px;">Built for experienced investors willing to embrace higher volatility for stronger potential rewards.</p>
          
          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Min Capital:</span><span class="fw-semibold text-dark">$2,500</span></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Max Capital:</span><span class="fw-semibold text-dark">$5,000</span></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Duration:</span><span class="fw-medium text-success">2 Days</span></div>
          </div>
          <a href="#" class="btn btn-success w-100 py-2.5 small fw-medium rounded-3 mt-auto">Initialize Plan</a>
        </div>
      </div>

      <!-- Plan 6: Legacy Wealth (Platinum) -->
      <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 h-100 plan-card">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2.5 py-1.5 fw-semibold font-monospace" style="font-size: 11px;">Tier 06</span>
            <span class="text-success fw-bold fs-5">+60%</span>
          </div>
          <h3 class="fw-bold text-dark h5 mb-2">Legacy Wealth Plan</h3>
          <p class="text-muted mb-4 small" style="line-height: 1.5; min-height: 60px;">Long-term aggressive growth plan aimed at maximizing capital expansion and wealth accumulation.</p>
          
          <div class="bg-light rounded-3 p-3 mb-4">
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Min Capital:</span><span class="fw-semibold text-dark">$5,000 <span class="text-success font-monospace" style="font-size: 11px;">USDT</span></span></div>
            <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Max Capital:</span><span class="fw-semibold text-dark">$12,000 <span class="text-success font-monospace" style="font-size: 11px;">USDT</span></span></div>
            <div class="d-flex justify-content-between small"><span class="text-muted">Duration:</span><span class="fw-medium text-success">30 Days</span></div>
          </div>
          <a href="#" class="btn btn-success w-100 py-2.5 small fw-medium rounded-3 mt-auto">Initialize Plan</a>
        </div>
      </div>

      <!-- Plan 7: Joint Investment (Full width variant below for distinction) -->
      <div class="col-lg-12 mt-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 plan-card">
          <div class="row align-items-center g-4">
            <div class="col-md-7 col-lg-8">
              <div class="d-flex align-items-center gap-2 mb-2">
                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2.5 py-1.5 fw-semibold font-monospace" style="font-size: 11px;">Tier 07</span>
                <span class="badge bg-dark text-white rounded-pill px-2.5 py-1.5 fw-medium small" style="font-size: 11px;">Capital Pooling Node</span>
              </div>
              <h3 class="fw-bold text-dark h4 mb-2">Joint Investment Plan</h3>
              <p class="text-muted small lh-lg mb-0">The Platinum Joint Investment Plan is designed for partners, investment groups, families, and business associates looking to pool capital together for stronger portfolio performance and shared profit opportunities.</p>
            </div>
            <div class="col-md-5 col-lg-4 border-start-md border-light-subtle">
              <div class="bg-light rounded-4 p-4 text-center text-md-start h-100 d-flex flex-column justify-content-center">
                <div class="text-success fw-bold display-6 mb-3">+65% <span class="fs-6 fw-normal text-muted">yield</span></div>
                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Min Pool:</span><span class="fw-semibold text-dark">$7,500</span></div>
                <div class="d-flex justify-content-between mb-2 small"><span class="text-muted">Max Pool:</span><span class="fw-semibold text-dark">$20,000</span></div>
                <div class="d-flex justify-content-between mb-4 small"><span class="text-muted">Lock-In Cycle:</span><span class="fw-medium text-success">25 Days</span></div>
                <a href="#" class="btn btn-success w-100 py-2.5 small fw-medium rounded-3">Establish Pool Node</a>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

    <!-- INFRASTRUCTURE LAW BLOCKQUOTE DISCLAIMER -->
    <div class="mt-5 mx-auto text-center" style="max-width: 780px;">
      <div class="p-4 bg-white border border-light-subtle rounded-4 shadow-sm">
        <p class="text-muted mb-0 lh-base" style="font-size: 12.5px;">
          <i class="bi bi-info-circle-fill text-success me-1.5"></i> <strong>Risk Compliance Matrix Node:</strong> Yield metrics listed above map projected goals calculated via real-time algorithm iterations. Active account configurations are subject to systemic market variance thresholds. Ensure chosen configurations line up with your liquidation framework criteria.
        </p>
      </div>
    </div>

  </div>
</div>
<?php  include __DIR__."/footer.php"; ?>