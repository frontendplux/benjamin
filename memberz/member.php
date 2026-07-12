<?php 
include __DIR__."/header.php";
$kyc_exists_check = $conn->prepare("
    SELECT
        id,
        identification_type,
        doc_image,
        doc_number,
        expired_date,
        is_verified,
        verified_at,
        created_at
    FROM kyc
    WHERE user_uid = ?
    LIMIT 1
");
$kyc_exists_check->bind_param("s", $user['uid']);
$kyc_exists_check->execute();
$result = $kyc_exists_check->get_result();

$user['kyc'] = [
    "success" => false,
    "data" => null
];

if ($result->num_rows > 0) {
    $user['kyc'] = [
        "success" => true,
        "data" => $result->fetch_assoc()
    ];
}
?>

<!-- ANDROID-STYLE SLIDER DRAWER PANEL (OFFCANVAS) -->
    <div class="container bg-light p-0 h-100 mt-2">
      <div class="row g-0 h-100">
        <!-- LEFT SIDEBAR: Structural Match to Sidebar navigation from image_0c6460.png -->
       <?php include __DIR__."/aside.php"; ?>
        <!-- MAIN DASHBOARD WINDOW AREA -->
        <div class="col-md-9 col-xl-10 p-3 p-md-4">
          <!-- Top Breadcrumb Section -->
          <div class="d-flex align-items-center gap-2  small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?></div>">
            <i class="bi bi-house-door"></i> <span class="">Dashboard</span>  <i class="bi bi-chevron-right style='font-size: 10px;'"></i> 
            <span class="text-success fw-bold">Hello <?= $user['first_name'] ?></span>
          </div>
    
          <!-- KYC Alert Warning Block -->
     <?php if (!$user['kyc']['success']): ?>
        <a href="/kyc" class="alert text-decoration-none mb-3 bg-success bg-opacity-10 border border-success border-opacity-25 rounded-4 p-3 d-flex align-items-center justify-content-between shadow-sm">
            <span class="text-success small fw-medium">
                <i class="bi bi-shield-exclamation me-2"></i>
                KYC: Click here to verify your account
            </span>
            <i class="bi bi-arrow-right text-success small"></i>
        </a>
    <?php endif; ?>


          <!-- ROW METRICS MATRIX FROM THE LOWER GRID -->
          <div class="row g-4">
            
            <!-- Left Side Primary Balance Highlight Card -->
           <div class="col-lg-5">
  <div class="card border-0 text-white rounded-4 h-100 shadow p-4 d-flex flex-column justify-content-between" style="background: linear-gradient(135deg, #198754, #0f2b1d); min-height: 320px;">
    <div>
      <span class="badge bg-white bg-opacity-25 text-white rounded-pill px-3 py-1 small mb-3">Live Valuation</span>
      <h3 class="fw-normal opacity-75 fs-5">Available Balance</h3>
      <div class="display-4 fw-bold my-2">$0.20</div>
    </div>

    <div class="pt-3 border-top gap-2 border-white border-opacity-10 small d-flex justify-content-end">
      <a href="/withdraw.php" class="btn btn-light text-success fw-bold py-2 col-lg-12 rounded-3 shadow-sm d-flex align-items-center justify-content-center">
        <i class="bi bi-arrow-down-left-circle me-2"></i>Withdraw
      </a>
      <!-- <a href="/withdraw.php" class="btn btn-light text-success fw-bold w-100 py-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center">
        <i class="bi bi-bank me-2"></i>Re Invest
      </a> -->
    </div>
  </div>
</div>

            <!-- Center & Right Metrics & Live Widget Containers -->
            <div class="col-lg-7">
              <div class="card border-0 shadow-sm bg-white rounded-4 p-4 h-100">
                <h5 class="fw-bold text-dark mb-4">Account Summary</h5>
                
                <div class="row g-3">
                  <!-- Grid Column: Profit Cards Stack -->
                  <div class="col-12 d-flex flex-column gap-3">
                    
                    <!-- Total Profit Box -->
                    <div class="p-3 bg-success rounded-4 text-white shadow-sm">
                      <span class="small d-block opacity-75">Total Profit</span>
                      <span class="fs-4 fw-bold">$1,109.20</span>
                    </div>

                    <!-- Total Bonus Box -->
                    <div class="p-3 bg-light rounded-4 border">
                      <span class="small d-block text-muted">Total Bonus</span>
                      <span class="fs-5 fw-bold text-dark">$0.00</span>
                    </div>

                    <!-- Total Deposit Box -->
                    <div class="p-3 bg-light rounded-4 border">
                      <span class="small d-block text-muted">Total Deposit</span>
                      <span class="fs-5 fw-bold text-dark">$3,290.00</span>
                    </div>
                  </div>
                </div>

              </div>
            </div>

          </div>

        </div>
      </div>
    </div>

    <script src="/app.js"></script>