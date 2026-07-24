<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <title>Document</title>
</head>
<body>
<div class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5 px-3">
  <div class="w-100" style="max-width: 600px;">
    
    <!-- Top Identity Branding Link Element -->
    <div class="text-center mb-4">
      <a class="d-inline-flex align-items-center fw-bold text-success fs-3 text-decoration-none" href="#">
        <i class="bi bi-graph-up-arrow me-2"></i>
        <span>Bright Part</span>
      </a>
    </div>

    <!-- Password Recovery Card Wrapper -->
    <div class="card border-0 shadow-sm bg-white p-4 p-md-5 rounded-4">
      <div class="card-body p-0">
        
        <!-- Action Context Description Headers -->
        <div class="mb-4">
          <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-3" style="width: 48px; height: 48px;">
            <i class="bi bi-key-fill fs-4"></i>
          </div>
          <h2 class="fw-bold text-dark h3 mb-1">Recover Password</h2>
          <p class="text-muted small">Enter your verified registration email framework node below to initiate an authentication reset sequence.</p>
        </div>

        <!-- Recovery input submission elements -->
        <form autocomplete="off">
          
          <!-- Email Node Target Input -->
          <div class="mb-4">
            <label for="recoveryEmail" class="form-label fw-semibold text-dark small mb-1">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-light-subtle text-muted">
                <i class="bi bi-envelope"></i>
              </span>
              <input type="email" class="form-control bg-white border-light-subtle py-2 text-muted" id="recoveryEmail" placeholder="name@example.com" required>
            </div>
            <div class="form-text text-muted small mt-2" style="font-size: 11px;">
              We will transmit an execution link containing a secure token window valid for 15 minutes.
            </div>
          </div>

          <!-- Action Submission Key -->
          <button type="submit" class="btn btn-success w-100 py-2.5 fw-medium shadow-sm mb-3">
            Send Reset Link <i class="bi bi-arrow-right-short ms-1"></i>
          </button>

        </form>

        <hr class="text-muted opacity-25 my-4">

        <!-- Navigation Return Route Back To Secure Entry Dashboard -->
        <div class="text-center">
          <a href="#" class="text-success small fw-bold text-decoration-none d-inline-flex align-items-center">
            <i class="bi bi-arrow-left me-2"></i> Return to Sign In
          </a>
        </div>

      </div>
    </div>

    <!-- Help Center Footnote Node -->
    <div class="text-center mt-4">
      <p class="text-muted small mb-0">
        Experiencing hardware verification failures? 
        <a href="#" class="text-success text-decoration-none fw-medium ms-1">Contact Security Desk</a>
      </p>
    </div>

  </div>
</div>