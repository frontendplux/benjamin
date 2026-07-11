<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="keywords" content="<?= htmlspecialchars($company_info['keywords']) ?>">
    <meta name="description" content="<?= htmlspecialchars($company_info['description']) ?>">
    <title><?= htmlspecialchars($company_info['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
  <div class="container p-3 py-1">
    
    <!-- Brand / Logo -->
    <a class="navbar-brand d-flex align-items-center fw-bold text-success fs-4" href="/home">
      <img src="<?= htmlspecialchars($company_info['logo']) ?>" style="width: 60px;" alt="<?= htmlspecialchars($company_info['keywords']) ?>" class="">
    </a>

    <!-- Mobile Toggler Button (Triggers the slide down) -->
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#brightPathNavbar" aria-controls="brightPathNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible Menu Wrapper -->
    <div class="collapse navbar-collapse" id="brightPathNavbar">
      
      <!-- Primary Navigation Links (Left/Center) -->
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium">
        <li class="nav-item">
          <a class="nav-link <?= $dataUrl == ('/home' ?? '/') ? 'active text-success' : '' ?>"  href="/home">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $dataUrl == '/investment' ? 'active text-success' : '' ?>" href="/investment">Investment Plans</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $dataUrl == '/about-us' ? 'active text-success' : '' ?>" href="/about-us">About Us</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $dataUrl == '/faq' ? 'active text-success' : '' ?>" href="/faq">FAQ</a>
        </li>
        <li class="nav-item">
          <a class="nav-link <?= $dataUrl == '/contact-us' ? 'active text-success' : '' ?>" href="/contact-us">Contact</a>
        </li>
      </ul>

      <!-- Action Buttons (Right Side) -->
      <div class="d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center gap-2 pt-2 pt-lg-0 border-top border-light border-lg-0">
        <!-- Sign In Button -->
        <a href="/login" class="btn btn-link text-decoration-none text-dark fw-medium px-3 py-2 text-center">
          <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
        </a>
        <!-- Register Button -->
        <a href="/register" class="btn btn-success px-4 py-2 rounded shadow-sm text-center">
          <i class="bi bi-person-plus me-1"></i> Register
        </a>
      </div>

    </div>

  </div>
</nav>