<?php
include __DIR__."/header.php";
?>

<header class="py-5 bg-light min-vh-75 d-flex align-items-center">
  <div class="container py-4">
    <div class="row align-items-center g-5">
    

      <!-- Right Column: Text Content -->
      <div class="col-lg-6 text-center text-lg-start">
        <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold mb-3">
          <i class="bi bi-shield-check me-1"></i> Secure &amp; Verified Crypto Growth
        </span>
        
        <h1 class="display-4 fw-bold text-dark mb-3 lh-sm">
          Navigate Your Financial Future with <span class="text-success">Bright Part</span>
        </h1>
        
        <p class="lead text-muted mb-4 fs-5">
          Maximize your capital with diversified, automated crypto investment plans. Simple terms, transparent daily payouts, and controlled market exposure designed for every tier of investor.
        </p>
        
        <!-- Action Buttons -->
        <div class="d-flex flex-column flex-sm-row justify-content-center justify-content-lg-start gap-3">
          <a href="/register" class="btn btn-success btn-lg px-4 py-3 shadow">
            Get Started Today <i class="bi bi-arrow-right ms-2"></i>
          </a>
          <a href="#investment_plan" class="btn btn-white btn-lg border px-4 py-3 bg-white text-dark">
            View Investment Plans <i class="bi bi-eye ms-1"></i>
          </a>
        </div>

        <!-- Trust Mini-Banner -->
        <div class="row g-3 mt-4 pt-3 border-top border-light justify-content-center justify-content-lg-start">
          <div class="col-auto d-flex align-items-center text-muted">
            <i class="bi bi-lock-fill text-success fs-4 me-2"></i>
            <span class="small fw-medium">Bank-Grade Security</span>
          </div>
          <div class="col-auto d-flex align-items-center text-muted">
            <i class="bi bi-lightning-charge-fill text-success fs-4 me-2"></i>
            <span class="small fw-medium">Fast Payouts</span>
          </div>
        </div>
      </div>  
      <!-- Left Column: Hero Image -->
      <div class="col-lg-6 text-center">
        <!-- Replace the placeholder src with your actual crypto asset or illustration URL -->
        <img src="https://images.unsplash.com/photo-1621761191319-c6fb62004040?auto=format&fit=crop&w=600&q=80" 
             alt="Bright Path Crypto Investment Illustration" 
             class="img-fluid rounded-4 shadow-sm"
             style="max-height: 450px; object-fit: cover;">
      </div>

    </div>
  </div>
</header>


<section class="py-5 bg-white">
  <div class="container py-4">
    <div class="row align-items-center g-5">
      
      <!-- Left Column: Pseudo-Masonry Image Layout -->
      <div class="col-lg-6">
        <div class="row g-3">
          <!-- Left Column of Masonry -->
          <div class="col-6">
            <div class="mb-3">
              <img src="https://images.unsplash.com/photo-1639762681485-074b7f938ba0?auto=format&fit=crop&w=400&h=500&q=80" 
                   class="img-fluid rounded-4 shadow-sm w-100 object-cover" 
                   alt="Crypto Network Graphics">
            </div>
            <div>
              <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&w=400&h=300&q=80" 
                   class="img-fluid rounded-4 shadow-sm w-100 object-cover" 
                   alt="Financial Security Growth">
            </div>
          </div>
          
          <!-- Right Column of Masonry (Offset down using Bootstrap spacing margins) -->
          <div class="col-6 pt-4">
            <div class="mb-3">
              <img src="https://images.unsplash.com/photo-1600880292203-757bb62b4baf?auto=format&fit=crop&w=400&h=320&q=80" 
                   class="img-fluid rounded-4 shadow-sm w-100 object-cover" 
                   alt="Team Planning Strategies">
            </div>
            <div>
              <img src="https://images.unsplash.com/photo-1621416894569-0f39ed31d247?auto=format&fit=crop&w=400&h=450&q=80" 
                   class="img-fluid rounded-4 shadow-sm w-100 object-cover" 
                   alt="Trading Performance Dashboard">
            </div>
          </div>
        </div>
      </div>

      <!-- Right Column: About Us Text Content -->
      <div class="col-lg-6">
        <span class="text-success fw-bold text-uppercase tracking-wider small d-block mb-2">Who We Are</span>
        <h2 class="display-5 fw-bold text-dark mb-4">
          Pioneering a Clearer Horizon for <span class="text-success">Digital Wealth</span>
        </h2>
        
        <p class="text-muted mb-4 lead">
          At Bright Path Investment, we believe navigating the cryptocurrency landscape shouldn't be complex or intimidating. We bridge the gap between volatile digital asset markets and secure, structured capital growth.
        </p>
        
        <p class="text-muted mb-4">
          Our team combines seasoned algorithmic trading expertise, strict risk-mitigation framework management, and community-driven pooling options to deliver balanced yields. Whether you are stepping in with a starter amount or building long-term institutional wealth, your trajectory remains our focus.
        </p>

        <!-- Core Value Featurettes -->
        <div class="row g-4 mt-2">
          <!-- Value 1 -->
          <div class="col-sm-6">
            <div class="d-flex align-items-start">
              <div class="bg-light p-2 rounded text-success me-3">
                <i class="bi bi-shield-check fs-4"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1 text-dark">Risk Control</h5>
                <p class="small text-muted mb-0">Diversified trading setups ensure minimum platform exposure.</p>
              </div>
            </div>
          </div>
          
          <!-- Value 2 -->
          <div class="col-sm-6">
            <div class="d-flex align-items-start">
              <div class="bg-light p-2 rounded text-success me-3">
                <i class="bi bi-pie-chart fs-4"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1 text-dark">Pure Transparency</h5>
                <p class="small text-muted mb-0">Calculated durations and fixed yields without hidden metrics.</p>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</section>
<section id="investment_plan" class="py-5 bg-light">
  <div class="container">
    
    <!-- Section Header -->
    <div class="text-center mb-5">
      <h2 class="fw-bold text-success">Bright Part Investment</h2>
      <p class="text-muted mx-auto" style="max-width: 600px;">
        Choose an investment plan tailored to your financial goals. Clear horizons, stable growth, and flexible terms.
      </p>
    </div>

    <!-- Investment Plans Grid -->
    <div class="row g-4 justify-content-center">

      <!-- 1. Starter Growth Plan -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white">
          <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success bg-opacity-10 p-2 rounded text-success me-3">
                <i class="bi bi-sprout fs-3"></i>
              </div>
              <h4 class="card-title fw-bold mb-0">Starter Growth</h4>
            </div>
            <hr class="text-muted">
            <div class="my-3">
              <span class="fs-2 fw-bold text-success">10%</span>
              <span class="text-muted"> / 48 Hours</span>
            </div>
            <ul class="list-unstyled my-3 flex-grow-1">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Min: <strong>$60</strong></li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Max: <strong>$400</strong></li>
            </ul>
            <p class="card-text text-muted small bg-light p-3 rounded mb-4">
              Ideal for beginners testing the platform with short-term exposure and stable growth opportunities.
            </p>
            <!-- <button class="btn btn-success w-100 py-2 mt-auto">Invest Now</button> -->
          </div>
        </div>
      </div>

      <!-- 2. Silver Steady Plan -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white">
          <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success bg-opacity-10 p-2 rounded text-success me-3">
                <i class="bi bi-shield-shaded fs-3"></i>
              </div>
              <h4 class="card-title fw-bold mb-0">Silver Steady</h4>
            </div>
            <hr class="text-muted">
            <div class="my-3">
              <span class="fs-2 fw-bold text-success">15%</span>
              <span class="text-muted"> / 3 Days</span>
            </div>
            <ul class="list-unstyled my-3 flex-grow-1">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Min: <strong>$400</strong></li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Max: <strong>$800</strong></li>
            </ul>
            <p class="card-text text-muted small bg-light p-3 rounded mb-4">
              Balanced plan for users seeking moderate growth with controlled market exposure.
            </p>
            <!-- <button class="btn btn-success w-100 py-2 mt-auto">Invest Now</button> -->
          </div>
        </div>
      </div>

      <!-- 3. Gold Advantage Plan -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white border-top border-5 border-success">
          <div class="card-body p-4 d-flex flex-column">
            <span class="badge bg-success text-white align-self-start mb-2 rounded-pill px-3 py-1">Weekend Feature</span>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success bg-opacity-10 p-2 rounded text-success me-3">
                <i class="bi bi-lightning-charge fs-3"></i>
              </div>
              <h4 class="card-title fw-bold mb-0">Gold Advantage</h4>
            </div>
            <hr class="text-muted">
            <div class="my-3">
              <span class="fs-2 fw-bold text-success">30%</span>
              <span class="text-muted"> / 30 Hours</span>
            </div>
            <ul class="list-unstyled my-3 flex-grow-1">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Min: <strong>$800</strong></li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Max: <strong>$1,500</strong></li>
            </ul>
            <p class="card-text text-muted small bg-light p-3 rounded mb-4">
              Designed for consistent daily growth using diversified crypto investment strategies.
            </p>
            <!-- <button class="btn btn-success w-100 py-2 mt-auto">Invest Now</button> -->
          </div>
        </div>
      </div>

      <!-- 4. Weekly Yield Plan -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white">
          <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success bg-opacity-10 p-2 rounded text-success me-3">
                <i class="bi bi-calendar-range fs-3"></i>
              </div>
              <h4 class="card-title fw-bold mb-0">Weekly Yield</h4>
            </div>
            <hr class="text-muted">
            <div class="my-3">
              <span class="fs-2 fw-bold text-success">40%</span>
              <span class="text-muted"> / 7 Days</span>
            </div>
            <ul class="list-unstyled my-3 flex-grow-1">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Min: <strong>$1,500</strong></li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Max: <strong>$2,500</strong></li>
            </ul>
            <p class="card-text text-muted small bg-light p-3 rounded mb-4">
              Targets higher returns through active trading, staking, and portfolio management techniques.
            </p>
            <!-- <button class="btn btn-success w-100 py-2 mt-auto">Invest Now</button> -->
          </div>
        </div>
      </div>

      <!-- 5. Premium Investors Plan -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white">
          <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success bg-opacity-10 p-2 rounded text-success me-3">
                <i class="bi bi-gem fs-3"></i>
              </div>
              <h4 class="card-title fw-bold mb-0">Premium Investors</h4>
            </div>
            <hr class="text-muted">
            <div class="my-3">
              <span class="fs-2 fw-bold text-success">50%</span>
              <span class="text-muted"> / 2 Days</span>
            </div>
            <ul class="list-unstyled my-3 flex-grow-1">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Min: <strong>$2,500</strong></li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Max: <strong>$5,000</strong></li>
            </ul>
            <p class="card-text text-muted small bg-light p-3 rounded mb-4">
              Built for experienced investors willing to embrace higher volatility for stronger potential rewards.
            </p>
            <!-- <button class="btn btn-success w-100 py-2 mt-auto">Invest Now</button> -->
          </div>
        </div>
      </div>

      <!-- 6. Joint Investment Plans -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white">
          <div class="card-body p-4 d-flex flex-column">
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success bg-opacity-10 p-2 rounded text-success me-3">
                <i class="bi bi-people fs-3"></i>
              </div>
              <h4 class="card-title fw-bold mb-0">Joint Investment</h4>
            </div>
            <hr class="text-muted">
            <div class="my-3">
              <span class="fs-2 fw-bold text-success">65%</span>
              <span class="text-muted"> / 25 Days</span>
            </div>
            <ul class="list-unstyled my-3 flex-grow-1">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Min: <strong>$7,500</strong></li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Max: <strong>$20,000</strong></li>
            </ul>
            <p class="card-text text-muted small bg-light p-3 rounded mb-4">
              Designed for partners, investment groups, families, and associates looking to pool capital together for shared opportunities.
            </p>
            <!-- <button class="btn btn-success w-100 py-2 mt-auto">Invest Now</button> -->
          </div>
        </div>
      </div>

      <!-- 7. Legacy Wealth Plan (Platinum) -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white border-top border-5 border-success">
          <div class="card-body p-4 d-flex flex-column">
            <span class="badge bg-success text-white align-self-start mb-2 rounded-pill px-3 py-1">Platinum Tier</span>
            <div class="d-flex align-items-center mb-3">
              <div class="bg-success bg-opacity-10 p-2 rounded text-success me-3">
                <i class="bi bi-bank fs-3"></i>
              </div>
              <h4 class="card-title fw-bold mb-0">Legacy Wealth</h4>
            </div>
            <hr class="text-muted">
            <div class="my-3">
              <span class="fs-2 fw-bold text-success">60%</span>
              <span class="text-muted"> / 30 Days</span>
            </div>
            <ul class="list-unstyled my-3 flex-grow-1">
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Min: <strong>5,000 USDT</strong></li>
              <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Max: <strong>12,000 USDT</strong></li>
            </ul>
            <p class="card-text text-muted small bg-light p-3 rounded mb-4">
              Long-term aggressive growth plan aimed at maximizing capital expansion and wealth accumulation.
            </p>
            <!-- <button class="btn btn-success w-100 py-2 mt-auto">Invest Now</button> -->
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<section class="py-5 bg-white">
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-12">
        <div class="card border-0  bg-white p-4 p-md-5">
          <div class="text-center mb-4">
            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-semibold mb-2">ROI Calculator</span>
            <h2 class="fw-bold text-dark">Estimate Your Yields</h2>
            <p class="text-muted small">Select your path and adjust your capital configuration instantly.</p>
          </div>

          <div class="row g-4">
            <!-- Left Side Inputs -->
            <div class="col-md-6">
              <div class="mb-3">
                <label for="planSelect" class="form-label fw-semibold text-dark">Choose Investment Plan</label>
                <select class="form-select py-2 border-light-subtle" id="planSelect" onchange="calculateProfit()">
                  <option value="starter" data-rate="10" data-min="60" data-max="400" data-duration="48 Hours">Starter Growth (10% / 48h)</option>
                  <option value="silver" data-rate="15" data-min="400" data-max="800" data-duration="3 Days">Silver Steady (15% / 3d)</option>
                  <option value="gold" data-rate="30" data-min="800" data-max="1500" data-duration="30 Hours">Gold Advantage (30% / 30h)</option>
                  <option value="weekly" data-rate="40" data-min="1500" data-max="2500" data-duration="7 Days">Weekly Yield (40% / 7d)</option>
                  <option value="premium" data-rate="50" data-min="2500" data-max="5000" data-duration="2 Days">Premium Investors (50% / 2d)</option>
                  <option value="joint" data-rate="65" data-min="7500" data-max="20000" data-duration="25 Days">Joint Investment (65% / 25d)</option>
                  <option value="legacy" data-rate="60" data-min="5000" data-max="12000" data-duration="30 Days">Legacy Wealth (60% / 30d)</option>
                </select>
              </div>

              <div class="mb-3">
                <label for="investAmount" class="form-label fw-semibold text-dark">Investment Capital ($)</label>
                <div class="input-group">
                  <span class="input-group-text bg-light border-light-subtle">$</span>
                  <input type="number" class="form-select py-2 border-light-subtle" id="investAmount" value="100" oninput="calculateProfit()">
                </div>
                <div id="limitWarning" class="form-text text-danger d-none small mt-1"></div>
              </div>
            </div>

            <!-- Right Side Visual Output Layout -->
            <div class="col-md-6 d-flex flex-column justify-content-between bg-light p-4 rounded-4">
              <div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted small">Target Rate:</span>
                  <span id="rateLabel" class="fw-bold text-success">10%</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted small">Term Duration:</span>
                  <span id="durationLabel" class="fw-bold text-dark">48 Hours</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                  <span class="text-muted small">Net Profit:</span>
                  <span id="profitLabel" class="fw-bold text-success">+$10.00</span>
                </div>
              </div>

              <hr class="text-muted my-3">

              <div class="text-center py-2">
                <span class="text-muted small d-block mb-1">Total Payout at Maturity</span>
                <span id="totalLabel" class="display-6 fw-bold text-dark">$110.00</span>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>
  </div>
</section>

<!-- Include Bootstrap 5 in your <head> if you haven't already -->
<!-- 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
-->

<section class="py-5 bg-light">
  <div class="container px-4 py-3">
    
    <!-- Optional Centered Heading -->
    <div class="text-center mb-5">
      <span class="text-success fw-bold text-uppercase tracking-wider small d-block mb-1">Visual Identity</span>
      <h2 class="fw-bold text-dark">Who We Are in Action</h2>
    </div>

    <!-- Masonry-Style Responsive Row Grid Layout -->
    <div class="row g-3">
      
      <!-- Column 1 -->
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="mb-3">
          <img src="https://images.unsplash.com/photo-1639762681485-074b7f938ba0?auto=format&fit=crop&w=500&q=80" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 400px; object-fit: cover;" 
               alt="Crypto Assets Analytics Setup">
        </div>
        <div>
          <img src="https://images.unsplash.com/photo-1579621970563-ebec7560ff3e?auto=format&fit=crop&w=500&q=80" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 250px; object-fit: cover;" 
               alt="Digital Vault Wealth Security">
        </div>
      </div>

      <!-- Column 2 (Offset heights for the masonry staggered effect) -->
      <div class="col-sm-6 col-md-4 col-lg-3">
        <div class="mb-3">
          <img src="/assest/image/six.png" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 280px; object-fit: cover;" 
               alt="Collaboration and Financial Strategy">
        </div>
        <div>
          <img src="/assest/image/five.png" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 370px; object-fit: cover;" 
               alt="Algorithmic Trading Yield Interface">
        </div>
      </div>

      <!-- Column 3 -->
      <div class="col-sm-6 col-md-4 col-lg-3 d-none d-md-block">
        <div class="mb-3">
          <img src="/assest/image/one.jpeg" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 420px; object-fit: cover;" 
               alt="Global Blockchain Operations Network">
        </div>
        <div>
          <img src="/assest/image/two.jpeg" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 230px; object-fit: cover;" 
               alt="Executive Asset Management Consultations">
        </div>
      </div>

      <!-- Column 4 -->
      <div class="col-sm-6 col-md-4 col-lg-3 d-none d-lg-block">
        <div class="mb-3">
          <img src="/assest/image/three.png" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 260px; object-fit: cover;" 
               alt="Secure Data Capital Transfers">
        </div>
        <div>
          <img src="/assest/image/four.png" 
               class="img-fluid rounded-4 shadow-sm w-100 border border-white border-2" 
               style="height: 390px; object-fit: cover;" 
               alt="Crypto Tokens Liquidity Markets">
        </div>
      </div>

    </div>
  </div>
</section>

<!-- Include Bootstrap 5 and Bootstrap Icons in your <head> if you haven't already -->
<!-- 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
-->

<section class="py-5 bg-white">
  <div class="container py-4">
    
    <!-- How it Works Heading -->
    <div class="text-center mb-5">
      <span class="text-success fw-bold text-uppercase tracking-wider small d-block mb-2">Process Framework</span>
      <h2 class="display-6 fw-bold text-dark">How It Works</h2>
      <p class="text-muted small mx-auto" style="max-width: 500px;">Get started today with Bright Path Investment in three transparent phases.</p>
    </div>

    <!-- 3 Step Process Grid -->
    <div class="row g-4 text-center justify-content-center mb-5 pb-4">
      
      <!-- Step 1 -->
      <div class="col-md-4">
        <div class="p-4 bg-light rounded-4 h-100 border border-light shadow-sm position-relative">
          <div class="position-absolute top-0 start-50 translate-middle bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow" style="width: 32px; height: 32px;">
            1
          </div>
          <div class="text-success my-3">
            <i class="bi bi-person-plus-fill fs-1"></i>
          </div>
          <h4 class="fw-bold text-dark h5 mb-2">Create An Account</h4>
          <p class="text-muted small mb-0">
            It takes less than 3 minutes to set up and verify your verified profile on Bright Path Investment.
          </p>
        </div>
      </div>

      <!-- Step 2 -->
      <div class="col-md-4">
        <div class="p-4 bg-light rounded-4 h-100 border border-light shadow-sm position-relative">
          <div class="position-absolute top-0 start-50 translate-middle bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow" style="width: 32px; height: 32px;">
            2
          </div>
          <div class="text-success my-3">
            <i class="bi bi-wallet2 fs-1"></i>
          </div>
          <h4 class="fw-bold text-dark h5 mb-2">Deposit and Invest</h4>
          <p class="text-muted small mb-0">
            Choose your desired investment package from our tiers and proceed by executing secure asset funding.
          </p>
        </div>
      </div>

      <!-- Step 3 -->
      <div class="col-md-4">
        <div class="p-4 bg-light rounded-4 h-100 border border-light shadow-sm position-relative">
          <div class="position-absolute top-0 start-50 translate-middle bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold shadow" style="width: 32px; height: 32px;">
            3
          </div>
          <div class="text-success my-3">
            <i class="bi bi-arrow-down-left-circle-fill fs-1"></i>
          </div>
          <h4 class="fw-bold text-dark h5 mb-2">Withdraw Funds</h4>
          <p class="text-muted small mb-0">
            Once you receive your fixed Return on Investment, execute a fast withdrawal clean to your external wallet.
          </p>
        </div>
      </div>

    </div>

    <hr class="text-muted opacity-25 my-5">

    <!-- Executive Partners Subsection -->
    <div class="row align-items-center g-4 mt-2">
      <!-- Title Badge Column -->
      <div class="col-lg-4 text-center text-lg-start">
        <h3 class="fw-bold text-dark mb-1">Executive Partners</h3>
        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-semibold">
          <i class="bi bi-check-all me-1"></i> 100+ Partners &amp; Supporters
        </span>
        <p class="text-muted small mt-2 mb-0">Supported by institutional liquidity networks and structural blockchain custodians globally.</p>
      </div>
      
      <!-- Partner Logo Badges Matrix Column -->
      <div class="col-lg-8">
        <div class="row g-3 align-items-center justify-content-center text-center text-muted fw-bold">
          
          <div class="col-6 col-sm-4 col-md-3">
            <div class="bg-light p-3 rounded-3 border border-light shadow-sm d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-coin text-success"></i> <span>BINANCE</span>
            </div>
          </div>
          
          <div class="col-6 col-sm-4 col-md-3">
            <div class="bg-light p-3 rounded-3 border border-light shadow-sm d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-cpu text-success"></i> <span>COINBASE</span>
            </div>
          </div>
          
          <div class="col-6 col-sm-4 col-md-3">
            <div class="bg-light p-3 rounded-3 border border-light shadow-sm d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-hexagon-fill text-success"></i> <span>METAMASK</span>
            </div>
          </div>
          
          <div class="col-6 col-sm-4 col-md-3">
            <div class="bg-light p-3 rounded-3 border border-light shadow-sm d-flex align-items-center justify-content-center gap-2">
              <i class="bi bi-safe2 text-success"></i> <span>LEDGER</span>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</section>

<!-- Include Bootstrap 5 and Bootstrap Icons in your <head> if you haven't already -->
<!-- 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
-->

<section class="py-5 bg-light">
  <div class="container py-4">
    
    <!-- Section Header -->
    <div class="text-center mb-5">
      <span class="text-success fw-bold text-uppercase tracking-wider small d-block mb-2">Success Stories</span>
      <h2 class="display-6 fw-bold text-dark">Client Testimonials</h2>
      <p class="text-muted small mx-auto" style="max-width: 500px;">
        Discover how our global users are accelerating their digital capital with Bright Path Investment.
      </p>
    </div>

    <!-- Testimonials Grid Matrix -->
    <div class="row g-4 justify-content-center">
      
      <!-- Testimonial 1 -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white p-4 rounded-4">
          <div class="card-body p-0 d-flex flex-column justify-content-between">
            <div>
              <!-- Rating Stars -->
              <div class="text-warning mb-3">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <p class="card-text text-muted mb-4 small italic">
                "I started exploring the platform using the Starter Growth tier to verify the runtime cycles. The system delivered exactly 10% after 48 hours right into my dash balance. Very reliable ecosystem execution."
              </p>
            </div>
            
            <div class="d-flex align-items-center pt-3 border-top border-light">
              <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 48px; height: 48px; min-width: 48px;">
                MH
              </div>
              <div>
                <h6 class="fw-bold text-dark mb-0">Marcus H.</h6>
                <span class="text-muted small">Starter Plan Investor</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Testimonial 2 -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white p-4 rounded-4">
          <div class="card-body p-0 d-flex flex-column justify-content-between">
            <div>
              <!-- Rating Stars -->
              <div class="text-warning mb-3">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
              </div>
              <p class="card-text text-muted mb-4 small">
                "Pooling capital into the Joint Investment setup with my small trade group has yielded excellent outcomes. The 25-day timeline is perfectly structured for our portfolio cycle requirements."
              </p>
            </div>
            
            <div class="d-flex align-items-center pt-3 border-top border-light">
              <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 48px; height: 48px; min-width: 48px;">
                SR
              </div>
              <div>
                <h6 class="fw-bold text-dark mb-0">Sarah R.</h6>
                <span class="text-muted small">Syndicate Asset Principal</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Testimonial 3 -->
      <div class="col-md-6 col-lg-4">
        <div class="card h-100 border-0 shadow-sm bg-white p-4 rounded-4">
          <div class="card-body p-0 d-flex flex-column justify-content-between">
            <div>
              <!-- Rating Stars -->
              <div class="text-warning mb-3">
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-fill"></i>
                <i class="bi bi-star-half"></i>
              </div>
              <p class="card-text text-muted mb-4 small">
                "The Legacy Wealth Tier offers stable parameters for tracking fixed aggressive returns over 30 days. Payout executions handle flawlessly straight out to external cold storage profiles."
              </p>
            </div>
            
            <div class="d-flex align-items-center pt-3 border-top border-light">
              <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center fw-bold me-3" style="width: 48px; height: 48px; min-width: 48px;">
                DK
              </div>
              <div>
                <h6 class="fw-bold text-dark mb-0">David K.</h6>
                <span class="text-muted small">Platinum Tier Custodian</span>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>
<!-- Include Bootstrap 5 and Bootstrap Icons in your <head> if you haven't already -->
<!-- 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
-->

<!-- Include Bootstrap 5 and Bootstrap Icons in your <head> if you haven't already -->
<!-- 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
-->

<section class="py-5 bg-light min-vh-100 d-flex align-items-center" id="contact_">
  <div class="container py-4">
    
    <!-- Section Header Block -->
    <div class="text-center mb-5">
      <span class="text-success fw-bold text-uppercase tracking-wider small d-block mb-2">Connect With Us</span>
      <h2 class="display-6 fw-bold text-dark">Get in Touch</h2>
      <p class="text-muted small mx-auto" style="max-width: 500px;">
        Have a technical inquiry regarding node optimization or capital deployment? Reach our operations network desk.
      </p>
    </div>

    <div class="row g-4 justify-content-center align-items-stretch">
      
      <!-- LEFT COLUMN: Contact Information Details -->
      <div class="col-lg-4 col-md-5">
        <div class="card h-100 border-0 shadow-sm bg-white p-4 rounded-4 d-flex flex-column justify-content-between">
          <div>
            <h3 class="h5 fw-bold text-dark mb-4">Support Channels</h3>
            
            <!-- Support Item 1: Email -->
            <div class="d-flex align-items-start mb-4">
              <div class="bg-success bg-opacity-10 text-success rounded-3 p-2.5 me-3">
                <i class="bi bi-envelope-fill fs-5"></i>
              </div>
              <div>
                <h4 class="fw-bold text-dark h6 mb-1">Email Terminal</h4>
                <p class="text-muted small mb-0">support@brightpath.com</p>
                <span class="text-success" style="font-size: 11px;"><i class="bi bi-clock me-1"></i> Response within 2 hours</span>
              </div>
            </div>

            <!-- Support Item 2: Telegram Hub -->
            <div class="d-flex align-items-start mb-4">
              <div class="bg-success bg-opacity-10 text-success rounded-3 p-2.5 me-3">
                <i class="bi bi-telegram fs-5"></i>
              </div>
              <div>
                <h4 class="fw-bold text-dark h6 mb-1">Community Grid</h4>
                <p class="text-muted small mb-0">t.me/BrightPathOfficial</p>
                <span class="text-muted" style="font-size: 11px;">Join live verification chat</span>
              </div>
            </div>

            <!-- Support Item 3: Headquarters -->
            <div class="d-flex align-items-start">
              <div class="bg-success bg-opacity-10 text-success rounded-3 p-2.5 me-3">
                <i class="bi bi-geo-alt-fill fs-5"></i>
              </div>
              <div>
                <h4 class="fw-bold text-dark h6 mb-1">Corporate Node</h4>
                <p class="text-muted small mb-0">
                  Suite 408, FinTech Tower,<br>
                  Grand Cayman, KY1-1104
                </p>
              </div>
            </div>
          </div>

          <!-- Quick Operating Hours Badge -->
          <div class="mt-4 pt-3 border-top border-light text-center text-sm-start">
            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-medium small">
              <i class="bi bi-shield-fill-check me-1"></i> Network Operating: 24/7/366
            </span>
          </div>
        </div>
      </div>

      <!-- RIGHT COLUMN: Interactive Inquiry Ticket Form -->
      <div class="col-lg-6 col-md-7">
        <div class="card h-100 border-0 shadow-sm bg-white p-4 p-md-5 rounded-4">
          <h3 class="h5 fw-bold text-dark mb-4">Transmit a Message</h3>
          
          <form autocomplete="off">
            <div class="row g-3">
              
              <!-- Full Name Input Element -->
              <div class="col-sm-6">
                <label for="contactName" class="form-label fw-semibold text-dark small mb-1">Your Name</label>
                <input type="text" class="form-control bg-light border-light-subtle py-2 text-muted small" id="contactName" placeholder="Alex Mercer" required>
              </div>

              <!-- Email Input Element -->
              <div class="col-sm-6">
                <label for="contactEmail" class="form-label fw-semibold text-dark small mb-1">Email Address</label>
                <input type="email" class="form-control bg-light border-light-subtle py-2 text-muted small" id="contactEmail" placeholder="name@example.com" required>
              </div>

              <!-- Subject Category Selector Dropdown -->
              <div class="col-12">
                <label for="contactSubject" class="form-label fw-semibold text-dark small mb-1">Inquiry Parameter Category</label>
                <select class="form-select bg-light border-light-subtle py-2 text-muted small" id="contactSubject" required>
                  <option selected disabled value="">Choose Classification Type...</option>
                  <option value="deposit">Deposit / Asset Settlement Failure</option>
                  <option value="package">Investment Tier Optimization Queries</option>
                  <option value="partnership">Executive Network Affiliation</option>
                  <option value="technical">Authentication & Account Pin Bug</option>
                </select>
              </div>

              <!-- Message Textarea Body -->
              <div class="col-12">
                <label for="contactMessage" class="form-label fw-semibold text-dark small mb-1">Detailed Message Description</label>
                <textarea class="form-control bg-light border-light-subtle text-muted small p-3" id="contactMessage" rows="5" placeholder="Elaborate on operational blockages or structural portfolio specifications..." required></textarea>
              </div>

              <!-- Submit Ticket Interactive Action Mechanism Button -->
              <div class="col-12 mt-4">
                <button type="submit" class="btn btn-success w-100 py-2.5 rounded-3 fw-medium shadow-sm d-flex align-items-center justify-content-center gap-2">
                  <span>Transmit Secure Ticket</span> <i class="bi bi-send-fill small"></i>
                </button>
              </div>

            </div>
          </form>
        </div>
      </div>

    </div>

  </div>
</section>

<section class="py-5 bg-light" id="faq_">
  <div class="container max-width-md" style="max-width: 800px;">
    
    <!-- Section Introduction Header Block -->
    <div class="text-center mb-5">
      <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-semibold mb-2">Got Questions?</span>
      <h2 class="fw-bold text-dark h1 mb-3">Frequently Asked Questions</h2>
      <p class="text-muted mx-auto" style="max-width: 540px;"> Explore operational details, network metrics, and core platform capabilities inside our centralized parameter framework.</p>
    </div>

    <!-- Bootstrap Accordion Module Custom Styled with Brand Colors -->
    <div class="accordion accordion-flush border-0 shadow-sm rounded-4 overflow-hidden bg-white p-3 p-md-4" id="homepageFaqAccordion">
      
      <!-- FAQ Item 1 -->
      <div class="accordion-item border-bottom border-light-subtle py-2">
        <h3 class="accordion-header" id="faqHeadingOne">
          <button class="accordion-button collapsed bg-white text-dark fw-semibold py-3 px-2 small shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne" aria-expanded="false" aria-controls="faqCollapseOne">
            <i class="bi bi-question-circle text-success me-3 fs-5"></i> How does the platform architecture generate yields?
          </button>
        </h3>
        <div id="faqCollapseOne" class="accordion-collapse collapse" aria-labelledby="faqHeadingOne" data-bs-parent="#homepageFaqAccordion">
          <div class="accordion-body text-muted small px-2 pt-1 pb-3 lh-lg">
            Our infrastructure interfaces with decentralized liquidity pools and high-frequency parameter nodes across multiple verified validation networks, securing real-time risk-mitigated asset optimization.
          </div>
        </div>
      </div>

      <!-- FAQ Item 2 -->
      <div class="accordion-item border-bottom border-light-subtle py-2">
        <h3 class="accordion-header" id="faqHeadingTwo">
          <button class="accordion-button collapsed bg-white text-dark fw-semibold py-3 px-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo" aria-expanded="false" aria-controls="faqCollapseTwo">
            <i class="bi bi-shield-check text-success me-3 fs-5"></i> What safety frameworks protect my account security?
          </button>
        </h3>
        <div id="faqCollapseTwo" class="accordion-collapse collapse" aria-labelledby="faqHeadingTwo" data-bs-parent="#homepageFaqAccordion">
          <div class="accordion-body text-muted small px-2 pt-1 pb-3 lh-lg">
            All configuration data is processed using multi-layer localized encryption tunnels. We use strict hardware multi-factor verification tokens alongside deep programmatic isolation layers to guarantee your environment safety.
          </div>
        </div>
      </div>

      <!-- FAQ Item 3 -->
      <div class="accordion-item border-bottom border-light-subtle py-2">
        <h3 class="accordion-header" id="faqHeadingThree">
          <button class="accordion-button collapsed bg-white text-dark fw-semibold py-3 px-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree" aria-expanded="false" aria-controls="faqCollapseThree">
            <i class="bi bi-wallet2 text-success me-3 fs-5"></i> Are there minimum thresholds for node withdrawals?
          </button>
        </h3>
        <div id="faqCollapseThree" class="accordion-collapse collapse" aria-labelledby="faqHeadingThree" data-bs-parent="#homepageFaqAccordion">
          <div class="accordion-body text-muted small px-2 pt-1 pb-3 lh-lg">
            Withdrawal loops execute automatically based on package duration rules. Minimum verification triggers vary slightly per selected blockchain network, maintaining uniform low execution fees across the platform.
          </div>
        </div>
      </div>

      <!-- FAQ Item 4 -->
      <div class="accordion-item border-0 py-2">
        <h3 class="accordion-header" id="faqHeadingFour">
          <button class="accordion-button collapsed bg-white text-dark fw-semibold py-3 px-2 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour" aria-expanded="false" aria-controls="faqCollapseFour">
            <i class="bi bi-people text-success me-3 fs-5"></i> How does the profile referral bonus framework distribute?
          </button>
        </h3>
        <div id="faqCollapseFour" class="accordion-collapse collapse" aria-labelledby="faqHeadingFour" data-bs-parent="#homepageFaqAccordion">
          <div class="accordion-body text-muted small px-2 pt-1 pb-3 lh-lg">
            When a secondary node registers your structural referral parameter key, immediate commission tracking triggers apply to your active dashboard wallet balance following their primary funding deployment.
          </div>
        </div>
      </div>

    </div>

    <!-- Supplementary Support Footer Banner -->
    <div class="text-center mt-5 bg-white border border-light-subtle rounded-4 p-4 shadow-sm">
      <h4 class="h6 fw-bold text-dark mb-2">Still need architectural information?</h4>
      <p class="text-muted small mb-3">Our continuous verification assistance center operates 24/7 to clear structural blocks.</p>
      <a href="#" class="btn btn-success px-4 py-2 small fw-medium rounded-3 shadow-sm">
        Connect to Help Desk <i class="bi bi-chat-left-dots-fill ms-1 small"></i>
      </a>
    </div>

  </div>
</section>

<!-- Inline CSS to clean up Bootstrap Accordion arrow active/focus highlighting natively -->
<style>
  .accordion-button:not(.collapsed) {
    background-color: transparent !important;
    color: #198754 !important;
  }
  .accordion-button::after {
    background-size: 1.1rem;
  }
  .accordion-button:not(.collapsed)::after {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16' fill='%23198754'%3e%3cpath fill-rule='evenodd' d='M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z'/%3e%3c/svg%3e") !important;
  }
</style>

<?php include __DIR__."/footer.php"; ?>
<!-- Floating WhatsApp Link Framework -->
<a href="https://wa.me/+552135004410" 
   target="_blank" 
   rel="noopener noreferrer" 
   class="position-fixed bottom-0 end-0 m-4 bg-white text-success rounded-circle d-flex align-items-center justify-content-center shadow-lg border border-success border-opacity-25 hover-scale-effect" 
   style="width: 70px; height: 70px; z-index: 1050; text-decoration: none; transition: transform 0.2s ease-in-out;"
   title="Chat with Support on WhatsApp">
  <i class="bi bi-whatsapp display-5"></i>
</a>

<!-- Tiny native CSS patch to give it a modern hover bounce effect -->
<style>
  .hover-scale-effect:hover {
    transform: scale(1.12);
  }
</style>
<script>
function calculateProfit() {
  const select = document.getElementById('planSelect');
  const option = select.options[select.selectedIndex];
  
  const rate = parseFloat(option.getAttribute('data-rate'));
  const min = parseFloat(option.getAttribute('data-min'));
  const max = parseFloat(option.getAttribute('data-max'));
  const duration = option.getAttribute('data-duration');
  
  let amountInput = document.getElementById('investAmount');
  let amount = parseFloat(amountInput.value) || 0;
  
  const warning = document.getElementById('limitWarning');
  
  if (amount < min || amount > max) {
    warning.innerHTML = `<i class="bi bi-exclamation-circle-fill me-1"></i> Bound error: Target must be between $${min} and $${max}`;
    warning.classList.remove('d-none');
  } else {
    warning.classList.add('d-none');
  }
  
  const profit = amount * (rate / 100);
  const total = amount + profit;
  
  document.getElementById('rateLabel').innerText = rate + '%';
  document.getElementById('durationLabel').innerText = duration;
  document.getElementById('profitLabel').innerText = '+$' + profit.toFixed(2);
  document.getElementById('totalLabel').innerText = '$' + total.toFixed(2);
}

// Fire calculation once on DOM initialization
document.addEventListener("DOMContentLoaded", calculateProfit);
</script>
</body>
</html>