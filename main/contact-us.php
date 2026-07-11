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
    <h1 class="display-5 fw-bold text-white mb-3 tracking-tight">Connect With Us</h1>
    
    <!-- Inline Subtext Description Segment -->
    <p class="text-success text-opacity-75 small mx-auto mb-4" style="max-width: 520px; font-size: 14px;">
      Have questions about our investment matrices or platform nodes? Reach out to our global support framework today.
    </p>

    <!-- CENTERED BREADCRUMB NAVIGATION LAYOUT (Home / Contact Us) -->
    <nav aria-label="breadcrumb" class="d-inline-block">
      <ol class="breadcrumb mb-0 bg-white bg-opacity-10 px-4 py-2 rounded-pill border border-success border-opacity-25 shadow-sm">
        <li class="breadcrumb-item">
          <a href="/home" class="text-white text-opacity-75 text-decoration-none small d-inline-flex align-items-center transition-all header-breadcrumb-link">
            <i class="bi bi-house-door me-1"></i> Home
          </a>
        </li>
        <li class="breadcrumb-item active text-success small fw-semibold" aria-current="page">Contact Us</li>
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
  /* Custom form focus styling to match the brand identity */
  .form-control:focus, .form-select:focus {
    border-color: #198754 !important;
    box-shadow: 0 0 0 0.25rem rgba(25, 135, 84, 0.15) !important;
  }
</style>

<div class="bg-light min-vh-100 py-5">
  <div class="container py-3">

    <div class="row g-4 justify-content-center">
      
      <!-- LEFT COLUMN: Contact Information Channels -->
      <div class="col-lg-4">
        
        <!-- Header Text Block -->
        <div class="mb-4">
          <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 small fw-semibold mb-2">Get In Touch</span>
          <h2 class="fw-bold text-dark h3">Our Global Desk</h2>
          <p class="text-muted small">We look forward to addressing your inquiries transparently.</p>
        </div>

        <div class="d-flex flex-column gap-3">
          <!-- Info Card 1: Email Support -->
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-start">
              <div class="bg-success bg-opacity-10 text-success rounded-3 p-2.5 me-3">
                <i class="bi bi-envelope-fill fs-5"></i>
              </div>
              <div>
                <h3 class="h6 fw-bold text-dark mb-1">Email Matrix</h3>
                <p class="text-muted mb-2" style="font-size: 13px;">Drop us a digital note at any hour.</p>
                <a href="mailto:support@brightpath.com" class="text-success small fw-semibold text-decoration-none">support@brightpath.com</a>
              </div>
            </div>
          </div>

          <!-- Info Card 2: Operations Office -->
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-start">
              <div class="bg-success bg-opacity-10 text-success rounded-3 p-2.5 me-3">
                <i class="bi bi-geo-alt-fill fs-5"></i>
              </div>
              <div>
                <h3 class="h6 fw-bold text-dark mb-1">HQ Location</h3>
                <p class="text-muted mb-2" style="font-size: 13px;">Our central analytical command node.</p>
                <p class="text-dark small mb-0 fw-medium">100 Financial District, Suite 500<br>London, EC1A 1BB, UK</p>
              </div>
            </div>
          </div>

          <!-- Info Card 3: Live Hours -->
          <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <div class="d-flex align-items-start">
              <div class="bg-success bg-opacity-10 text-success rounded-3 p-2.5 me-3">
                <i class="bi bi-clock-fill fs-5"></i>
              </div>
              <div>
                <h3 class="h6 fw-bold text-dark mb-1">Operational Hours</h3>
                <p class="text-muted mb-2" style="font-size: 13px;">When our live desk analysts are active.</p>
                <p class="text-dark small mb-0 fw-medium">Monday — Friday: 08:00 - 18:00 GMT</p>
                <span class="badge bg-light text-muted border border-light-subtle rounded-pill px-2.5 py-1 mt-2 font-monospace" style="font-size: 11px;">Weekend support limited to urgent nodes</span>
              </div>
            </div>
          </div>
        </div>

      </div>

      <!-- RIGHT COLUMN: Interactive Messaging Form -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 p-md-5 h-100">
          
          <h3 class="h4 fw-bold text-dark mb-2">Send an Inquiry</h3>
          <p class="text-muted small mb-4">Complete the fields below, and an asset specialist will correspond within 24 operational hours.</p>
          
          <form action="" method="POST" autocomplete="off">
            <div class="row g-3">
              
              <!-- Form Field: Name -->
              <div class="col-md-6">
                <label for="contact_name" class="form-label small fw-semibold text-dark">Full Name</label>
                <input type="text" class="form-control rounded-3 p-2.5 small" id="contact_name" name="name" placeholder="John Doe" required>
              </div>

              <!-- Form Field: Email -->
              <div class="col-md-6">
                <label for="contact_email" class="form-label small fw-semibold text-dark">Email Address</label>
                <input type="email" class="form-control rounded-3 p-2.5 small" id="contact_email" name="email" placeholder="name@example.com" required>
              </div>

              <!-- Form Field: Department Selection -->
              <div class="col-12">
                <label for="contact_subject" class="form-label small fw-semibold text-dark">Inquiry Target Vector</label>
                <select class="form-select rounded-3 p-2.5 small" id="contact_subject" name="subject" required>
                  <option value="" selected disabled>Choose the sector corresponding to your question...</option>
                  <option value="general">General Platform Information</option>
                  <option value="equities">Global Equities Portfolio Configurations</option>
                  <option value="innovation">Digital Innovation &amp; Tech Infrastructure</option>
                  <option value="renewables">Renewable Energy Solutions</option>
                  <option value="support">Technical Help &amp; Account Security</option>
                </select>
              </div>

              <!-- Form Field: Message Box -->
              <div class="col-12">
                <label for="contact_message" class="form-label small fw-semibold text-dark">Your Message</label>
                <textarea class="form-control rounded-3 p-3 small" id="contact_message" name="message" rows="5" placeholder="Detail your parameters, objectives, or questions here..." required></textarea>
              </div>

              <!-- Privacy Notice Disclaimer -->
              <div class="col-12">
                <div class="p-3 bg-light rounded-3 border border-light-subtle">
                  <p class="text-muted mb-0 font-monospace" style="font-size: 11px; line-height: 1.4;">
                    <i class="bi bi-shield-lock-fill text-success me-1"></i> Data Protection: Information submitted here stays secure within our internal system frameworks and will never be shared with outside marketing vectors.
                  </p>
                </div>
              </div>

              <!-- Submit Action Button -->
              <div class="col-12 pt-2">
                <button type="submit" class="btn btn-success px-4 py-2.5 small fw-medium rounded-3 shadow-sm w-100 w-md-auto">
                  Transmit Message <i class="bi bi-send-fill ms-1.5" style="font-size: 12px;"></i>
                </button>
              </div>

            </div>
          </form>

        </div>
      </div>

    </div>

  </div>
</div>
<?php  include __DIR__."/footer.php"; ?>