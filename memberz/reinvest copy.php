<?php 
include __DIR__."/header.php"; 

// 1. Pagination Configuration
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 600;
$offset = ($page - 1) * $limit;

$count = $conn->query("
    SELECT COUNT(*) total
    FROM investment_plan
    WHERE status='active'
");

$total = $count->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// 2. Fetch Active Investment Packages
$investment = $conn->prepare("
    SELECT
        id,
        name,
        description,
        roi,
        duration_value,
        duration_unit,
        min_limit,
        max_limit,
        approval
    FROM investment_plan
    WHERE status='active'
    ORDER BY min_limit ASC
    LIMIT ?, ?
");

$investment->bind_param("ii", $offset, $limit);
$investment->execute();
$investment = $investment->get_result()->fetch_all(MYSQLI_ASSOC);

// 3. Fetch current logged-in user's system wallet balance
// Assumes $user['uid'] or similar session identity context is defined in your header.php
$user_uid = $_SESSION['user_uid'] ?? $user['uid'] ?? ''; 

$user_wallet_balance = 0.00;
if (!empty($user_uid)) {
    $wallet_stmt = $conn->prepare("SELECT wallet_balance FROM user_wallet WHERE user_uid = ? AND status = 'active'");
    $wallet_stmt->bind_param("s", $user_uid);
    $wallet_stmt->execute();
    $wallet_res = $wallet_stmt->get_result()->fetch_assoc();
    if ($wallet_res) {
        $user_wallet_balance = (float)$wallet_res['wallet_balance'];
    }
}
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN DEPOSIT CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Investment Plans</span>
      </div>

      <!-- Overview Header Section displaying available wallet funds -->
      <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-layers-half text-success me-2"></i>Investment Packages</h5>
            <p class="text-muted small mb-0">Select a preferred plan to build your staking allocation portfolio.</p>
          </div>
          <div class="bg-success-subtle border border-success-subtle text-success px-3 py-2 rounded-3 d-flex align-items-center gap-2">
            <i class="bi bi-wallet2 fs-5"></i>
            <div>
                <span class="d-block small text-uppercase fw-semibold font-monospace" style="font-size: 10px; letter-spacing: 0.5px;">Wallet Balance</span>
                <span class="fw-bold font-monospace">$<?= number_format($user_wallet_balance, 2) ?></span>
            </div>
          </div>
        </div>
      </div>

      <!-- Packages Structural Grid -->
      <div class="row g-4">
        <?php foreach ($investment as $invest): ?>
          <div class="col-xl-4 col-md-6">
            <div class="card h-100 border-0 shadow-sm bg-white rounded-4 overflow-hidden d-flex flex-column">
              <div class="card-body p-4 d-flex flex-column">
                
                <!-- Package Title Header -->
                <div class="text-center mb-3">
                  <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($invest['name']) ?></h5>
                  <span class="text-muted small"><?= htmlspecialchars($invest['description']) ?></span>
                </div>
                
                <!-- Large ROI Callout Display -->
                <div class="my-3 py-3 bg-light rounded-4 text-center border border-light-subtle">
                  <span class="display-6 fw-bold text-success font-monospace"><?= htmlspecialchars($invest['roi']) ?>%</span>
                  <span class="text-muted d-block small mt-1" style="font-size: 11px; text-transform: uppercase; letter-spacing: 0.5px;">Return on Investment</span>
                </div>

                <!-- Package Parameters Feature Matrix -->
                <p class="text-success small fw-semibold border-bottom border-light-subtle pb-2 mb-3"><?= htmlspecialchars($invest['name']) ?> features include:</p>
                <ul class="list-unstyled text-secondary small flex-grow-1 mb-4 font-monospace" style="font-size: 12px;">
                  <li class="mb-2.5 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i>Duration:</span>
                    <span class="text-dark fw-medium"><?= htmlspecialchars($invest['duration_value']) . ' ' . ucfirst($invest['duration_unit']) ?></span>
                  </li>
                  <li class="mb-2.5 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i>Minimum Limit:</span>
                    <span class="text-dark fw-medium">$<?= number_format($invest['min_limit'],2) ?></span>
                  </li>
                  <li class="mb-2.5 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i>Maximum Limit:</span>
                    <span class="text-dark fw-medium">$<?= number_format($invest['max_limit'],2) ?></span>
                  </li>
                  <li class="mb-2.5 d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i>Approval Engine:</span>
                    <span class="text-dark"><?= ucfirst($invest['approval']) ?></span>
                  </li>
                  <li class="d-flex align-items-center justify-content-between">
                    <span><i class="bi bi-check-circle-fill text-success me-2"></i>System Support:</span>
                    <span class="text-dark">24/7 Live</span>
                  </li>
                </ul>

                <!-- Interactive Interface Controls Block -->
                <div class="mt-auto pt-3 border-top border-light-subtle">
                  <form action="process_deposit.php" method="POST">
                    <input type="hidden" name="package_id" value="<?= $invest['id'] ?>">
                    
                    <!-- Capital Allocation input -->
                    <div class="mb-3">
                      <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Staking Capital Allocation</label>
                      <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-light-subtle text-muted">$</span>
                        <input type="number" min="<?= $invest['min_limit'] ?>" max="<?= $invest['max_limit'] ?>" step="any" class="form-control bg-light border-light-subtle py-2 text-dark font-monospace" name="amount" placeholder="0.00" required>
                      </div>
                    </div>

                    <!-- Reinvest Toggle Switch -->
                    <div class="mb-3">
                      <div class="form-check form-switch bg-light p-2 rounded-3 border border-light-subtle ps-5">
                        <input class="form-check-input ms- -4" type="checkbox" role="switch" id="reinvest_<?= $invest['id'] ?>" name="reinvest" value="1">
                        <label class="form-check-label small text-dark fw-semibold font-monospace ms-1" style="font-size: 11px;" for="reinvest_<?= $invest['id'] ?>">
                          Reinvest Earnings from Balance
                        </label>
                      </div>
                    </div>

                    <!-- Execution CTA -->
                    <button
                      type="button"
                      class="btn btn-success w-100 select-package"
                      data-package="<?= $invest['id'] ?>">
                      Select Package
                      <i class="bi bi-arrow-right-short"></i>
                    </button>
                  </form>
                </div>

              </div>
            </div>
          </div>
        <?php endforeach ?>
      </div>

      <!-- Pagination Footer System -->
      <?php if($totalPages>1): ?>
        <nav class="mt-4">
          <ul class="pagination justify-content-center">
            <?php if($page>1): ?>
              <li class="page-item">
                <a class="page-link" href="?page=<?= $page-1 ?>">Previous</a>
              </li>
            <?php endif ?>

            <?php for($i=1;$i<=$totalPages;$i++): ?>
              <li class="page-item <?= $i==$page?'active':'' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
              </li>
            <?php endfor ?>

            <?php if($page<$totalPages): ?>
              <li class="page-item">
                <a class="page-link" href="?page=<?= $page+1 ?>">Next</a>
              </li>
            <?php endif ?>
          </ul>
        </nav>
      <?php endif ?>
    </div>
  </div>
</div>

<script>
document.querySelectorAll(".select-package").forEach(btn => {
    btn.onclick = async () => {
        const form = btn.closest("form");
        const package_id = btn.dataset.package;
        const amount = form.querySelector("[name='amount']").value;
        // Check if the checkbox element is checked (true/false)
        const reinvest = form.querySelector("[name='reinvest']").checked ? 1 : 0;

        if(!amount){
            Swal.fire({
                icon:"warning",
                text:"Please enter amount"
            });
            return;
        }

        btn.disabled = true;

        try {
            const res = await fetch("<?= $company_info['main-server'] ?>", {
                method:"POST",
                headers:{
                    "Content-Type":"application/json"
                },
                body:JSON.stringify({
                    action:"/member/select-package",
                    package_id:package_id,
                    amount:amount,
                    reinvest:reinvest // Passes 1 or 0 indicating whether it's a structural reinvestment action
                })
            });

            const data = await res.json();
            btn.disabled = false;

            if(!data.success){
                Swal.fire({
                    icon:"error",
                    text:data.message
                });
                return;
            }

            Swal.fire({
                icon:"success",
                title:"Package Processed",
                text:data.message || "Your choice has been saved successfully.",
                showCancelButton: reinvest ? false : true, // If reinvesting direct from balance, skip manual payment routes
                confirmButtonText: reinvest ? "Done" : "Deposit Now"
            }).then(result=>{
                if(result.isConfirmed && !reinvest){
                    location.href="/deposit-payment";
                } else if(result.isConfirmed && reinvest) {
                    location.reload();
                }
            });

        }catch(error){
            btn.disabled=false;
            Swal.fire({
                icon:"error",
                text:"Something went wrong"
            });
        }
    }
});
</script>