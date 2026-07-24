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
    <!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '4e7f83c13584d8a5fbc3b3b250cb690d90b0a20b';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o..push(arguments)};o.=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript></nav>
    <style>
      iframe {
        display:none;
      }
    </style>
    
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>
    <script src="/memberz/script.js"></script>
</head>

<nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm sticky-top">
  <div class="container p-3 py-1">
    
    <!-- Brand / Logo -->
    <a class="navbar-brand d-flex align-items-center fw-bold text-success fs-4 me-3" href="/home">
      <img src="<?= htmlspecialchars($company_info['logo']) ?>" style="width: 60px;" alt="<?= htmlspecialchars($company_info['keywords']) ?>">
    </a>

    <!-- Always Visible Actions (Language & Sign In) -->
    <div class="d-flex align-items-center gap-2 ms-auto me-2 me-lg-0 order-lg-last">
      
      <!-- Language Selector Dropdown -->
      <div class="dropdown">
        <button id="langauge-killer" class="btn btn-link text-dark text-decoration-none dropdown-toggle p-1 small fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          English
        </button>
        <ul style="max-height: 300px; overflow: auto;" class="dropdown-menu dropdown-menu-end border-light-subtle bg-white shadow">
          <?php foreach ($languages as $language): ?>
            <li>
              <a onclick="document.getElementById('langauge-killer').innerHTML='<?= $language[1] ?>';changeLang('<?= $language[0] ?>')" class="dropdown-menu-item dropdown-item small" href="#">
                <?= $language[1] ?>
              </a>
            </li>
          <?php endforeach ?>
        </ul>
      </div>

      <!-- Sign In Button -->
      <a href="/login" class="btn btn-outline-secondary btn-sm px-2 py-1 px-md-3 py-md-2 text-decoration-none fw-medium">
        <i class="bi bi-box-arrow-in-right me-1"></i> Sign In
      </a>
      
    </div>

    <!-- Mobile Toggler Button -->
    <button class="navbar-toggler border-0 p-1" type="button" data-bs-toggle="collapse" data-bs-target="#brightPartNavbar" aria-controls="brightPartNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Collapsible Menu Wrapper (Nav Links & Register Button) -->
    <div class="collapse navbar-collapse" id="brightPartNavbar">
      
      <!-- Primary Navigation Links -->
      <ul class="navbar-nav mx-auto mb-2 mb-lg-0 fw-medium text-center text-lg-start">
        <li class="nav-item">
          <a class="nav-link <?= $dataUrl == ('/home' ?? '/') ? 'active text-success' : '' ?>" href="/home">Home</a>
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
      <!-- Primary Action (Register Button) Inside Mobile Menu -->
      <div class="pt-2 pt-lg-0 border-top d-lg-none border-light border-lg-0 text-center">
        <a href="/register" class="btn btn-success px-4 py-2 rounded shadow-sm w-100 w-lg-auto">
          <i class="bi bi-person-plus me-1"></i> Register
        </a>
      </div>
    </div>

  </div>
</nav>
<div id="google_translate_element" style="display:none;"></div>

<style>
    #goog-gt-vt{display: none;}
</style>