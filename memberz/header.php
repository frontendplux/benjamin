<!DOCTYPE html>
<html lang="en">
<head>
<?php
if (isset($_GET['darkmode'])) {
    $_SESSION['darkmode'] =
        (($_SESSION['darkmode'] ?? 'light') === 'dark')
        ? 'light'
        : 'dark';

    header("Location: " . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}

$theme = $_SESSION['darkmode'] ?? 'light';

$closeMenuMe = [
    ['Dashboard','/member','bi-grid-1x2-fill'],
    ['Make Deposit','/make-deposit','bi-wallet2'],
    ['Apply for Loan','/apply-for-loan','bi-bank'],
    ['My Plans','/investment-plans','bi-pie-chart'],
    // ['Promo Plans','/promo-plans','bi-gift'],
    ['Wallet Connect','/wallet-connect','bi-plugin'],
    // ['Withdraw Funds','/withdraw-funds','bi-arrow-down-left-circle'],
    // ['Beneficiary Shares','/beneficiary-shares','bi-chevron-down'],
    ['Account History','/account-history','bi-chevron-down'],
    ['Referrals','/referrals','bi-people']
];
$response = [
    "success" => false,
    "data" => null
];

if (
    !empty($_SESSION['user_id']) &&
    !empty($_SESSION['token'])
) {
    $uid = $_SESSION['user_id'];
    $token = $_SESSION['token'];
    $islogin = $conn->prepare("
        SELECT
            uid,
            first_name,
            last_name,
            email,
            phone,
            country,
            token
        FROM users
        WHERE uid = ?
        AND token = ?
        LIMIT 1
    ");

    $islogin->bind_param("ss", $uid, $token);
    $islogin->execute();
    $result = $islogin->get_result();
    if ($result->num_rows > 0) {
        $response = [
            "success" => true,
            "data" => $result->fetch_assoc()
        ];
    }
}
if (!$response['success']) {
    header("Location: /login");
    exit;
}
// Logged in user
$user = $response['data'];
?>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="<?= $company_info['description']; ?>">
<meta name="keywords" content="<?= $company_info['keywords']; ?>">
<link rel="manifest" href="/manifest.json">
<link rel="icon" href="<?= $company_info['logo'] ?>" type="image/x-icon">
<title><?= $company_info['title'] ?></title>
<meta name="theme-color" content="#198754">
<link rel="apple-touch-icon" href="/icons/icon-192.png">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/main/script.js"></script>

<style>
    iframe{
    display:none;
}
/* Smooth transition */
body,
.navbar,
.offcanvas,
.dropdown-menu,
.card,
.btn,
.form-control,
.table{
    transition:all .25s ease;
}

/* LIGHT MODE */
body.light-mode{
    background:#f5f7fb;
    color:#212529;
}

body.light-mode .navbar{
    background:#0f2b1d !important;
}

body.light-mode .offcanvas{
    background:#0f2b1d !important;
}

body.light-mode .dropdown-menu{
    background:#fff;
    color:#212529;
}

/* DARK MODE */
body.dark-mode{
    background:#121212;
    color:#f8f9fa;
}

body.dark-mode .navbar{
    background:#1a1a1a !important;
    border-color:#333 !important;
}

body.dark-mode .offcanvas{
    background:#1b1b1b !important;
    border-color:#333 !important;
}

body.dark-mode .dropdown-menu{
    background:#242424;
    color:#fff;
    border-color:#444;
}

body.dark-mode .dropdown-item{
    color:#ddd;
}

body.dark-mode .dropdown-item:hover{
    background:#343434;
    color:#fff;
}

body.dark-mode .dropdown-header{
    color:#fff;
}

body.dark-mode .btn-link{
    color:#ddd;
}

body.dark-mode .bg-light{
    background:#2d2d2d !important;
}

body.dark-mode .text-dark{
    color:#fff !important;
}

body.dark-mode .card{
    background:#202020;
    color:#fff;
    border-color:#333;
}

body.dark-mode .form-control{
    background:#2a2a2a;
    color:#fff;
    border-color:#444;
}

body.dark-mode .form-control:focus{
    background:#2a2a2a;
    color:#fff;
}

body.dark-mode table{
    color:#fff;
}

body.dark-mode hr{
    border-color:#444;
}

body.dark-mode a{
    color:#8ec5ff;
}
</style>
    <title>Document</title>
</head>
<body class="<?= $theme === 'dark' ? 'dark-mode' : 'light-mode' ?>">


<header class="navbar navbar-dark sticky-top p-3 shadow-sm" style="background-color: #0f2b1d; border-bottom: 1px solid #198754;z-index:30000">
  <div class="container d-flex align-items-center justify-content-between">
    <!-- LEFT SIDE DIV: Android-style Slider Trigger, Brand, and Meta Context -->
    <div class="d-flex align-items-center gap-3">
      <!-- Android-style Slider Menu Trigger Button -->
      <button class="btn btn-link d-lg-none text-white p-0 border-0 fs-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#androidDashboardSidebar" aria-controls="androidDashboardSidebar">
        <i class="bi bi-list"></i>
      </button>
      <!-- Brand Title & Account Metadata Layout -->
      <div class="d-flex flex-column lh-1 d-none d-md-block">
        <img src="<?= $company_info['logo2'] ?>" style="width:70px" alt="<?= htmlspecialchars($company_info['keywords']) ?>" class="">
      </div>
    </div>

    <!-- RIGHT SIDE DIV: Localization, Mode Toggle, Alert Bell, and User Profile Node -->
    <div class="d-flex align-items-center gap-3">
      
      <!-- Language Selector Dropdown -->
      <div class="dropdown d-block d-sm-block">
        <button id="langauge-killer" class="btn btn-link text-white-50 text-decoration-none dropdown-toggle p-0 small fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
          English
        </button>
        <ul style="max-height: 300px; overflow: auto;" class="dropdown-menu dropdown-menu-end border-light-subtle bg-white shadow">
          <?php foreach ($languages as $language): ?>
            <li><a onclick="document.getElementById('langauge-killer').innerHTML='<?= $language[1] ?>';changeLang('<?= $language[0] ?>')" class="dropdown-menu-item dropdown-item active bg-success text-white small" href="#"><?= $language[1] ?></a></li>
          <?php endforeach ?>
        </ul>
      </div>

      <!-- UI Theme Cycle Indicator Button -->
      <a href="?darkmode"
        class="btn btn-link p-0 border-0 fs-5 d-flex align-items-center justify-content-center rounded-circle"
        style="width:36px;height:36px;">
            <i class="bi <?= $theme === 'dark' ? 'bi-sun-fill text-warning' : 'bi-moon-stars text-success' ?>"></i>
        </a>

      <!-- Alert Notification Bell Notification Hub Indicator -->
      <div class="position-relative">
        <a href="/notification" class="btn btn-link text-white-50 p-0 border-0 fs-5 d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
          <i class="bi bi-bell"></i>
        </a>
        <span class="position-absolute top-0 start-100 translate-middle p-1 bg-success border border-2 border-dark rounded-circle"></span>
      </div>

      <!-- User Session Avatar Profile Framework -->
      <div class="dropdown">
        <a href="#" class="d-flex align-items-center gap-2 link-body-emphasis text-decoration-none dropdown-toggle no-caret" data-bs-toggle="dropdown" aria-expanded="false">
          <div class="bg-light border border-2 border-success text-success rounded-circle d-flex align-items-center justify-content-center fw-bold text-uppercase shadow-sm" style="width: 38px; height: 38px;">
           <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
          </div>
        </a>
        <ul class="dropdown-menu dropdown-menu-end border-light-subtle bg-white shadow mt-2">
          <li><div class="dropdown-header text-dark fw-bold text-capitalize"><?= htmlspecialchars($user['first_name']).'&nbsp;'. htmlspecialchars($user['last_name']) ?></div></li>
          <li><a class="dropdown-item small text-muted" href="/profile"><i class="bi bi-person me-2"></i>My Profile</a></li>
          <li><a class="dropdown-item small text-muted" href="/settings"><i class="bi bi-gear me-2"></i>Security Settings</a></li>
          <li><hr class="dropdown-divider border-light-subtle"></li>
          <li><a class="dropdown-item small text-danger fw-semibold" href="/signout"><i class="bi bi-box-arrow-left me-2"></i>Sign Out</a></li>
        </ul>
      </div>

    </div>

  </div>
</header>

<div class="offcanvas offcanvas-start text-white border-end border-success" tabindex="-1" id="androidDashboardSidebar" aria-labelledby="androidDashboardSidebarLabel" style="background-color: #0f2b1d; max-width: 280px;">
    <div class="offcanvas-header border-bottom border-light border-opacity-10 py-4">
    <h5 class="offcanvas-title fw-bold text-success d-flex align-items-center" id="androidDashboardSidebarLabel">
        <i class="bi bi-shield-check me-2"></i> Terminal Menu
    </h5>
    <button type="button" class="btn-close btn-close-white text-reset" data-bs-toggle="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body p-0">
    <!-- Sidebar Navigation Links Inside Slider Container -->
    <ul class="list-unstyled my-3 pb-5">
        <?php foreach ($closeMenuMe as $menuList):?>
        <li class="px-3 mb-1">
        <a href="<?= $menuList[1]  ?>" class="d-flex <?= $dataUrl == $menuList[1] ? 'active':'' ?> align-items-center text-white-50 hover-success rounded p-3 text-decoration-none">
            <i class="bi <?= htmlspecialchars($menuList[2])  ?> me-3 fs-5"></i> <span><?= $menuList[0]  ?></span>
        </a>
        </li>
        <?php endforeach ?>
    </ul>
    </div>
</div>
<div id="google_translate_element" style="display:none;"></div>
<script src="/memberz/script.js"></script>