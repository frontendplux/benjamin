<?php 
include __DIR__."/header.php"; 

// Check active UI state view parameter ('request' or 'history')
$active_view = isset($_GET['view']) ? strtolower(trim($_GET['view'])) : 'request';
if (!in_array($active_view, ['request', 'history'])) {
    $active_view = 'request';
}

// Dummy/Placeholder logic mapping for the History array (Replace with your SQL fetch loop later)
$user_uid = $_SESSION['user_id'] ?? null;

if (!$user_uid) {
    header("Location: /login");
    exit;
}
$loans_count = 0; // Set up dynamically via query count if required

$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$count = $conn->prepare("
    SELECT COUNT(*) total
    FROM loans
    WHERE user_uid=?
");

$count->bind_param("s", $user_uid);
$count->execute();
$totalLoans = $count->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalLoans / $limit);

$stmt = $conn->prepare("
    SELECT *
    FROM loans
    WHERE user_uid=?
    ORDER BY id DESC
    LIMIT ? OFFSET ?
");

$stmt->bind_param("sii", $user_uid, $limit, $offset);
$stmt->execute();
$loans = $stmt->get_result();
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN CREDIT CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= (isset($theme) && $theme === 'dark') ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Credit Line Management</span>
      </div>

      <!-- VIEW NAVIGATION TABS -->
      <div class="card border-0 shadow-sm bg-white rounded-4 p-2 mb-4">
        <ul class="nav nav-pills nav-fill gap-1">
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2.5 small fw-bold <?= $active_view === 'request' ? 'bg-success text-white active' : 'text-secondary bg-transparent' ?>" href="?view=request">
              <i class="bi bi-bank2 me-2"></i>Apply for a Loan
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2.5 small fw-bold <?= $active_view === 'history' ? 'bg-success text-white active' : 'text-secondary bg-transparent' ?>" href="?view=history">
              <i class="bi bi-clock-history me-2"></i>My Loans & History
            </a>
          </li>
        </ul>
      </div>

      <!-- CONDITIONAL VIEW LAYER DISPLAY ROUTER -->
      <?php if ($active_view === 'request'): ?>
        
        <!-- VIEW 1: REQUEST LOAN APPLICATION FORM -->
        <div class="row g-4">
          <!-- Left Side: Interactive Application Form Card -->
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
              <div class="mb-4 pb-2 border-bottom border-light-subtle">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-wallet-fill text-success me-2"></i>Apply for a Term Loan</h5>
                <p class="text-muted mb-0 small">Configure your credit line thresholds. Decisions are calculated systematically against network equity blocks.</p>
              </div>

              <!-- Submission Form Pipeline -->
              <form id="loanForm">
                <div class="row g-3">
                  
                  <div class="col-12">
                    <label class="small text-muted mb-1">Loan Amount (USD)</label>
                    <input type="number" name="amount" class="form-control rounded-3" placeholder="Enter amount" required>
                  </div>

                  <div class="col-12">
                    <label class="small text-muted mb-1">Loan Duration</label>
                    <select name="duration" class="form-select rounded-3" required>
                      <option value="">Select duration</option>
                      <option value="3 months">3 Months</option>
                      <option value="6 months">6 Months</option>
                      <option value="12 months">12 Months</option>
                    </select>
                  </div>

                  <div class="col-12">
                    <label class="small text-muted mb-1">Reason For Loan</label>
                    <textarea name="reason" class="form-control rounded-3" rows="4" placeholder="Explain why you need this loan" required></textarea>
                  </div>

                  <!-- Info Block: Transparent Legal Compliance Note -->
                  <div class="col-12 mt-4">
                    <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3">
                      <div class="d-flex gap-2">
                        <i class="bi bi-info-circle text-success mt-0.5"></i>
                        <p class="mb-0 text-secondary small" style="line-height: 1.4;">
                          <strong>Automated Collateral Verification:</strong> By pushing this application to the processing kernel, you agree to auto-liquidation parameters if portfolio thresholds slip below a critical 115% debt-to-equity margin ratio. No external physical asset pulling is initiated.
                        </p>
                      </div>
                    </div>
                  </div>

                  <!-- Action Button -->
                  <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm">
                      Submit Credit Application File <i class="bi bi-shield-check ms-1"></i>
                    </button>
                  </div>

                </div>
              </form>
            </div>
          </div>

          <!-- Right Side: Structural Financial Health Parameters & Limits -->
          <div class="col-lg-4">
            <!-- Card: Risk Engine Assessment Previews -->
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-activity text-success me-2"></i>Risk Profile Analytics</h6>
              <div class="text-center py-3 mb-3 bg-light bg-opacity-50 border border-light-subtle rounded-3">
                <span class="text-muted d-block font-monospace small" style="font-size: 11px;">PRE-APPROVED CREDIT RATING</span>
                <span class="display-6 fw-bold text-success font-monospace">A+</span>
              </div>
              <div class="font-monospace text-secondary small" style="font-size: 12px;">
                <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
                  <span>Base Interest Rate:</span>
                  <span class="text-dark fw-bold">4.75% APR</span>
                </div>
                <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
                  <span>Origination Fee:</span>
                  <span class="text-dark">0.00% (Waived)</span>
                </div>
                <div class="d-flex justify-content-between py-1.5">
                  <span>Processing Index:</span>
                  <span class="text-success fw-medium">Instant Node Route</span>
                </div>
              </div>
            </div>

            <!-- Card: Account Structural Verification Checklists -->
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-patch-check text-success me-2"></i>Prerequisite Matrix</h6>
              <ul class="list-unstyled mb-0 font-monospace text-secondary" style="font-size: 12px;">
                <li class="d-flex align-items-center gap-2 mb-2.5">
                  <i class="bi bi-check2-circle text-success fs-6"></i>
                  <span>Identity Dossier Complete</span>
                </li>
                <li class="d-flex align-items-center gap-2 mb-2.5">
                  <i class="bi bi-check2-circle text-success fs-6"></i>
                  <span>Active 2FA Authentication</span>
                </li>
                <li class="d-flex align-items-center gap-2 mb-2.5">
                  <i class="bi bi-check2-circle text-success fs-6"></i>
                  <span>Minimum Node Tenure (>30d)</span>
                </li>
                <li class="d-flex align-items-center gap-2 opacity-50">
                  <i class="bi bi-circle text-muted fs-6" style="font-size: 12px;"></i>
                  <span>No Active Defaults Outstanding</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

      <?php else: ?>
        
        <!-- VIEW 2: MY LOANS HISTORY LEDGER -->
        <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
          <div class="mb-4 pb-2 border-bottom border-light-subtle d-flex justify-content-between align-items-center">
            <div>
              <h5 class="fw-bold text-dark mb-1"><i class="bi bi-journal-text text-success me-2"></i>Credit Ledger</h5>
              <p class="text-muted mb-0 small">Overview of active financing arrangements, running maturity terms, and historical approvals.</p>
            </div>
          </div>

          <!-- LOANS ARCHITECTURE TABLE CONTAINER -->
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
              <thead>
                <tr class="bg-light rounded-3 font-monospace small text-secondary" style="font-size: 11px; border-bottom: 2px solid #f8f9fa;">
                  <th class="ps-3 py-3">LOAN ID / PURPOSE</th>
                  <th class="py-3">PRINCIPAL AMOUNT</th>
                  <th class="py-3">TERM LIMIT</th>
                  <th class="py-3">STATUS</th>
                  <th class="pe-3 py-3 text-end">DATE SUBMITTED</th>
                </tr>
              </thead>
              <tbody class="small">
                <?php if ($totalLoans === 0): ?>
                  <tr>
                    <td colspan="5" class="text-center py-4 text-muted">No structural loan applications found.</td>
                  </tr>
                <?php else: ?>
                  <?php while($loan = $loans->fetch_assoc()): ?>
                    <tr class="border-bottom border-light-subtle">
                      <td class="ps-3 py-3">
                        <span class="text-muted font-monospace d-block" style="font-size: 11px;"><?= htmlspecialchars($loan['loan_uid'] ?? '') ?></span>
                        <strong class="text-dark"><?= htmlspecialchars($loan['reason']) ?></strong>
                      </td>
                      <td class="py-3 fw-bold text-dark">$<?= number_format($loan['amount'], 2) ?></td>
                      <td class="py-3 text-secondary"><?= htmlspecialchars($loan['duration']) ?></td>
                      <td class="py-3">
                        <?php
                        $status = strtolower($loan['status']);
                        $class = [
                          'pending'   => 'warning text-warning-emphasis bg-warning-subtle',
                          'approved'  => 'success text-success-emphasis bg-success-subtle',
                          'rejected'  => 'danger text-danger-emphasis bg-danger-subtle',
                          'cancelled' => 'secondary text-secondary-emphasis bg-secondary-subtle'
                        ][$status] ?? 'secondary bg-secondary-subtle';
                        ?>
                        <span class="badge border-0 rounded-pill px-2.5 py-1.5 <?= $class ?>">
                          <?= ucfirst($status) ?>
                        </span>
                      </td>
                      <td class="pe-3 py-3 text-end text-secondary font-monospace" style="font-size: 12px;">
                        <?= date("d M Y", strtotime($loan['created_at'])) ?>
                      </td>
                    </tr>
                  <?php endwhile; ?>
                <?php endif; ?>
              </tbody>
            </table>
          </div>

          <!-- CLEAN MODERN PAGINATION ROW -->
          <?php if ($totalPages > 1): ?>
            <nav class="mt-4 pt-2">
              <ul class="pagination justify-content-center pagination-sm gap-1 mb-0">
                <!-- Previous Button -->
                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                  <a class="page-link rounded-2 border-0 bg-light text-dark px-3 py-2" href="?view=history&page=<?= $page - 1 ?>">
                    <i class="bi bi-chevron-left"></i>
                  </a>
                </li>

                <!-- Page Matrix loop -->
                <?php for($i = 1; $i <= $totalPages; $i++): ?>
                  <li class="page-item fw-bold">
                    <a class="page-link rounded-2 border-0 px-3 py-2 <?= $page == $i ? 'bg-success text-white' : 'bg-light text-dark' ?>" href="?view=history&page=<?= $i ?>">
                      <?= $i ?>
                    </a>
                  </li>
                <?php endfor; ?>

                <!-- Next Button -->
                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                  <a class="page-link rounded-2 border-0 bg-light text-dark px-3 py-2" href="?view=history&page=<?= $page + 1 ?>">
                    <i class="bi bi-chevron-right"></i>
                  </a>
                </li>
              </ul>
            </nav>
          <?php endif; ?>

        </div>
      <?php endif; ?>

    </div>
  </div>
</div>

<script>
document.getElementById("loanForm")?.addEventListener("submit", function(e){
  e.preventDefault();
  let formData = new FormData(this);

  fetch("<?= $company_info['main-server'] ?? '' ?>", {
    method: "POST",
    headers: {
      "Content-Type": "application/json"
    },
    body: JSON.stringify({
      action: "/member/create-loan",
      amount: formData.get("amount"),
      duration: formData.get("duration"),
      reason: formData.get("reason")
    })
  })
  .then(res => res.json())
  .then(data => {
    if(data.success){
      Swal.fire({
        icon: "success",
        title: "Loan Submitted",
        text: data.message
      }).then(() => {
        location.href = "?view=history";
      });
    } else {
      Swal.fire({
        icon: "error",
        title: "Failed",
        text: data.message
      });
    }
  });
});
</script>