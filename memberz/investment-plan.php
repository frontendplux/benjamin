<?php
include __DIR__."/header.php";

$user_id = $_SESSION['user_id'];

// 1. DETERMINE CURRENT ACTIVE FILTER STATUS
// Supported states: 'pending', 'reviewing', 'approved', 'all'
$current_status = isset($_GET['status']) ? strtolower(trim($_GET['status'])) : 'all';
if (!in_array($current_status, ['pending', 'reviewing', 'approved', 'all'])) {
    $current_status = 'all';
}

// 2. PAGINATION CONFIGURATION SETUP
$items_per_page = 5;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($current_page < 1) $current_page = 1;
$offset = ($current_page - 1) * $items_per_page;

// 3. FETCH METRIC COUNTS FOR NAVIGATION BADGES
$counts = ['pending' => 0, 'reviewing' => 0, 'approved' => 0, 'all' => 0];
$count_query = $conn->prepare("
    SELECT status, COUNT(*) as aggregate 
    FROM deposits 
    WHERE user_uid = ? 
    GROUP BY status
");
$count_query->bind_param("i", $user_id);
$count_query->execute();
$count_result = $count_query->get_result();

while ($row = $count_result->fetch_assoc()) {
    $status_key = strtolower($row['status']);
    if (array_key_exists($status_key, $counts)) {
        $counts[$status_key] = (int)$row['aggregate'];
    }
    $counts['all'] += (int)$row['aggregate'];
}

// 4. CONSTRUCT FILTERED STATEMENT BASED ON NAVIGATION
$where_clause = "WHERE d.user_uid = ?";
if ($current_status !== 'all') {
    $where_clause .= " AND d.status = ?";
}

// Calculate Total Pages for Contextual Pagination Footers
$total_items = $counts[$current_status];
$total_pages = ceil($total_items / $items_per_page);
if ($total_pages < 1) $total_pages = 1;

// 5. QUERY PRIMARY DATA PAYLOAD
$data_sql = "
    SELECT 
        d.deposit_uid,
        d.amount,
        d.status,
        d.created_at,
        d.approved_at,

        p.name AS package_name,
        p.duration_value,
        p.duration_unit
    FROM deposits d
    INNER JOIN investment_plan p ON p.id = d.investment_plan_id
    $where_clause
    ORDER BY d.created_at DESC
    LIMIT ? OFFSET ?
";

$data_stmt = $conn->prepare($data_sql);
if ($current_status !== 'all') {
    $data_stmt->bind_param("isii", $user_id, $current_status, $items_per_page, $offset);
} else {
    $data_stmt->bind_param("iii", $user_id, $items_per_page, $offset);
}
$data_stmt->execute();
$plans_result = $data_stmt->get_result();
?>

<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
  <div class="row g-0 h-100">
    <?php include __DIR__."/aside.php"; ?>

    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <div class="d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center gap-2 small <?=$theme==='dark'?'text-light':'text-muted'?>">
          <i class="bi bi-house-door"></i> 
          <span>Dashboard</span> 
          <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
          <span class="text-success fw-bold">My Investment Portfolios</span>
        </div>
        <a href="/make-deposit" class="btn btn-success btn-sm rounded-pill px-3 fw-medium small">
            <i class="bi bi-plus-lg me-1"></i> New Plan
        </a>
      </div>

      <div class="card border-0 shadow-sm bg-white rounded-4 p-2 mb-4">
        <ul class="nav nav-pills nav-fill gap-1">
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2 small fw-medium text-start text-md-center position-relative <?= $current_status === 'all' ? 'bg-success text-white active' : 'text-secondary' ?>" href="?status=all">
              <i class="bi bi-collection-play me-2"></i>All Portfolios
              <span class="badge position-absolute top-50 end-0 translate-middle-y me-2 <?= $current_status === 'all' ? 'bg-white text-success' : 'bg-light text-dark' ?> rounded-pill font-monospace" style="font-size: 10px;"><?= $counts['all'] ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2 small fw-medium text-start text-md-center position-relative <?= $current_status === 'pending' ? 'bg-warning text-dark active' : 'text-secondary' ?>" href="?status=pending">
              <i class="bi bi-hourglass-split me-2"></i>Pending
              <span class="badge position-absolute top-50 end-0 translate-middle-y me-2 <?= $current_status === 'pending' ? 'bg-dark text-warning' : 'bg-light text-dark' ?> rounded-pill font-monospace" style="font-size: 10px;"><?= $counts['pending'] ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2 small fw-medium text-start text-md-center position-relative <?= $current_status === 'reviewing' ? 'bg-info text-dark active' : 'text-secondary' ?>" href="?status=reviewing">
              <i class="bi bi-eye me-2"></i>Reviewing
              <span class="badge position-absolute top-50 end-0 translate-middle-y me-2 <?= $current_status === 'reviewing' ? 'bg-dark text-info' : 'bg-light text-dark' ?> rounded-pill font-monospace" style="font-size: 10px;"><?= $counts['reviewing'] ?></span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2 small fw-medium text-start text-md-center position-relative <?= $current_status === 'approved' ? 'bg-success text-white active' : 'text-secondary' ?>" href="?status=approved">
              <i class="bi bi-check2-circle me-2"></i>Approved / Active
              <span class="badge position-absolute top-50 end-0 translate-middle-y me-2 <?= $current_status === 'approved' ? 'bg-white text-success' : 'bg-light text-dark' ?> rounded-pill font-monospace" style="font-size: 10px;"><?= $counts['approved'] ?></span>
            </a>
          </li>
        </ul>
      </div>

      <?php if ($plans_result->num_rows === 0): ?>
        <div class="card border-0 shadow-sm bg-white rounded-4 p-5 text-center my-4">
            <i class="bi bi-folder2-open display-4 text-muted mb-3"></i>
            <h6 class="fw-bold text-dark">No portfolios records mapped</h6>
            <p class="text-secondary small mb-0">No entries matched the selected "<?= ucfirst($current_status) ?>" filter context state tier.</p>
        </div>
      <?php else: ?>
        <div class="d-flex flex-column gap-3 mb-4">
          <?php while ($plan = $plans_result->fetch_assoc()): ?>
            
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4 overflow-hidden position-relative">
              
              <div class="position-absolute top-0 start-0 h-100" style="width: 5px; background-color: <?= $plan['status'] === 'approved' ? '#198754' : ($plan['status'] === 'reviewing' ? '#0dcaf0' : '#ffc107') ?>;"></div>
              
              <div class="row align-items-center g-3">
                
                <div class="col-md-4">
                  <span class="text-muted font-monospace d-block mb-1" style="font-size: 11px;">UID: <?= $plan['deposit_uid'] ?></span>
                  <h6 class="fw-bold text-dark mb-1"><?= ucwords(str_replace('_', ' ', $plan['package_name'])) ?></h6>
                  <span class="fs-5 fw-bold font-monospace text-dark">$<?= number_format($plan['amount'], 2) ?> <span class="fs-7 text-secondary fw-normal">USDT</span></span>
                </div>

                <div class="col-md-3 text-md-center">
                  <span class="text-muted small d-block mb-1">Verification Link</span>
                  <?php if ($plan['status'] === 'pending'): ?>
                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-20 px-3 py-2 rounded-pill font-monospace small">
                      <i class="bi bi-hourglass-split me-1 animate-spin"></i> Pending Pay
                    </span>
                  <?php elseif ($plan['status'] === 'reviewing'): ?>
                    <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-20 px-3 py-2 rounded-pill font-monospace small">
                      <i class="bi bi-shield-shaded me-1"></i> Under Review
                    </span>
                  <?php else: ?>
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 px-3 py-2 rounded-pill font-monospace small">
                      <i class="bi bi-patch-check-fill me-1"></i> Active Contract
                    </span>
                  <?php endif; ?>
                </div>

                <div class="col-md-5 text-md-end">
                  <?php if ($plan['status'] === 'approved' && !empty($plan['approved_at'])): 
                      // Calculate maturation terminal metrics dynamically
                    //  $seconds_duration = (int)$plan['duration_days'] * 86400;
                    //  $expiry_timestamp = strtotime($plan['approved_at']) + $seconds_duration;
                  ?>

                  <?php

$approvedDate = new DateTime($plan['approved_at']);

switch ($plan['duration_unit']) {

    case 'hours':
        $approvedDate->modify("+{$plan['duration_value']} hours");
        break;

    case 'days':
        $approvedDate->modify("+{$plan['duration_value']} days");
        break;

    case 'weeks':
        $approvedDate->modify("+{$plan['duration_value']} weeks");
        break;

    case 'months':
        $approvedDate->modify("+{$plan['duration_value']} months");
        break;

    case 'years':
        $approvedDate->modify("+{$plan['duration_value']} years");
        break;
}

$expiry_timestamp = $approvedDate->getTimestamp();

?>
                    <span class="text-muted small d-block mb-1"><i class="bi bi-clock-history me-1"></i>Contract Maturity Countdown</span>
                    <div class="d-flex justify-content-md-end gap-2 font-monospace live-countdown" data-expire="<?= $expiry_timestamp ?>">
                        <div class="bg-light px-2 py-1 rounded text-center" style="min-width: 45px;">
                            <span class="fw-bold text-dark d-block days">00</span>
                            <small class="text-muted" style="font-size: 9px; text-transform: uppercase;">Days</small>
                        </div>
                        <div class="bg-light px-2 py-1 rounded text-center" style="min-width: 45px;">
                            <span class="fw-bold text-dark d-block hours">00</span>
                            <small class="text-muted" style="font-size: 9px; text-transform: uppercase;">Hrs</small>
                        </div>
                        <div class="bg-light px-2 py-1 rounded text-center" style="min-width: 45px;">
                            <span class="fw-bold text-dark d-block minutes">00</span>
                            <small class="text-muted" style="font-size: 9px; text-transform: uppercase;">Min</small>
                        </div>
                        <div class="bg-light px-2 py-1 rounded text-center" style="min-width: 45px;">
                            <span class="fw-bold text-success d-block seconds">00</span>
                            <small class="text-muted" style="font-size: 9px; text-transform: uppercase;">Sec</small>
                        </div>
                    </div>
                  <?php elseif ($plan['status'] === 'pending'): ?>
                    <div class="d-flex flex-column align-items-md-end gap-1">
                        <span class="text-muted small">No structural funding hash submitted</span>
                        <a href="/deposit-payment?suid=<?= $plan['deposit_uid'] ?>" class="btn btn-outline-warning btn-sm py-1 px-3 rounded-pill fw-medium font-monospace" style="font-size:12px;">
                          Submit Proof <i class="bi bi-arrow-right-short"></i>
                        </a>
                    </div>
                  <?php else: ?>
                    <span class="text-muted small d-block mb-1">Queue Sequencing Initialization</span>
                    <span class="text-secondary small font-monospace"><i class="bi bi-cpu me-1"></i> Awaiting Cron Node Confirmation...</span>
                  <?php endif; ?>
                </div>

              </div>
            </div>
          <?php endwhile; ?>
        </div>

        <?php if ($total_pages > 1): ?>
          <nav class="d-flex justify-content-between align-items-center bg-white p-3 rounded-4 shadow-sm border-0">
            <div class="small text-muted font-monospace">
              Page <strong><?= $current_page ?></strong> of <?= $total_pages ?>
            </div>
            <ul class="pagination pagination-sm m-0 gap-1 border-0">
              <li class="page-item <?= ($current_page <= 1) ? 'disabled' : '' ?>">
                <a class="page-line page-link border-0 rounded-3 px-3 py-1.5 bg-light text-dark small font-monospace" href="?status=<?= $current_status ?>&page=<?= $current_page - 1 ?>">
                  <i class="bi bi-chevron-left"></i>
                </a>
              </li>
              
              <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <li class="page-item <?= ($current_page === $i) ? 'active' : '' ?>">
                  <a class="page-line page-link border-0 rounded-3 px-3 py-1.5 font-monospace small <?= ($current_page === $i) ? 'bg-success text-white' : 'bg-light text-dark' ?>" href="?status=<?= $current_status ?>&page=<?= $i ?>">
                    <?= $i ?>
                  </a>
                </li>
              <?php endfor; ?>

              <li class="page-item <?= ($current_page >= $total_pages) ? 'disabled' : '' ?>">
                <a class="page-line page-link border-0 rounded-3 px-3 py-1.5 bg-light text-dark small font-monospace" href="?status=<?= $current_status ?>&page=<?= $current_page + 1 ?>">
                  <i class="bi bi-chevron-right"></i>
                </a>
              </li>
            </ul>
          </nav>
        <?php endif; ?>

      <?php endif; ?>

    </div>
  </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    function initializationCountdownEngines() {
        const structuralElements = document.querySelectorAll('.live-countdown');
        
        structuralElements.forEach(element => {
            const contractExpiryUnix = parseInt(element.getAttribute('data-expire')) * 1000;
            const currentClientUnix = new Date().getTime();
            const differentialMetric = contractExpiryUnix - currentClientUnix;

            if (differentialMetric <= 0) {
                element.innerHTML = '<span class="badge bg-danger rounded-pill px-3 py-2 font-monospace"><i class="bi bi-patch-exclamation-fill me-1"></i> Mature / Terminated</span>';
                return;
            }

            // Time calculations for days, hours, minutes and seconds
            const days = Math.floor(differentialMetric / (1000 * 60 * 60 * 24));
            const hours = Math.floor((differentialMetric % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((differentialMetric % (1000 * 60 * 60)) / (1000 * 60));
            const seconds = Math.floor((differentialMetric % (1000 * 60)) / 1000);

            // Print calculated metrics safely to targeted classes within container block
            element.querySelector('.days').innerText = String(days).padStart(2, '0');
            element.querySelector('.hours').innerText = String(hours).padStart(2, '0');
            element.querySelector('.minutes').innerText = String(minutes).padStart(2, '0');
            element.querySelector('.seconds').innerText = String(seconds).padStart(2, '0');
        });
    }

    // Execute engine sequence processing every second interval loops
    initializationCountdownEngines();
    setInterval(initializationCountdownEngines, 1000);
});
</script>

<style>
/* Smooth rendering animations for icons */
@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.animate-spin {
    display: inline-block;
    animation: spin 2s linear infinite;
}
.page-link:focus {
    box-shadow: none;
}
</style>