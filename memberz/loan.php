<?php 
include __DIR__."/header.php"; 

// Check active UI state view parameter ('request' or 'history')
$active_view = isset($_GET['view']) ? strtolower(trim($_GET['view'])) : 'request';
if (!in_array($active_view, ['request', 'history'])) {
    $active_view = 'request';
}

// Dummy/Placeholder logic mapping for the History array (Replace with your SQL fetch loop later)
$user_id = $_SESSION['uid'] ?? 1;
$loans_count = 0; // Set up dynamically via query count if required
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN CREDIT CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
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
              <form action="process_loan.php" method="POST">
                <div class="row g-3">
                  
                  <!-- Input: Loan Purpose Selection -->
                  <div class="col-md-6">
                    <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Primary Financing Vector</label>
                    <select class="form-select bg-light border-light-subtle py-2.5 small font-monospace text-dark" name="loan_purpose" required>
                      <option selected disabled value="">Choose purpose...</option>
                      <option value="staking_leverage">Staking Capital Leverage</option>
                      <option value="liquidity_buffer">Node Liquidity Buffer</option>
                      <option value="corporate_expansion">Corporate Operations Expansion</option>
                      <option value="equity_bridge">Asset Bridge Financing</option>
                    </select>
                  </div>

                  <!-- Input: Loan Term Duration -->
                  <div class="col-md-6">
                    <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Maturity Term Duration</label>
                    <select class="form-select bg-light border-light-subtle py-2.5 small font-monospace text-dark" name="loan_term" required>
                      <option selected disabled value="">Select runtime...</option>
                      <option value="3_months">3 Months (Quarterly Node Renewal)</option>
                      <option value="6_months">6 Months (Standard Yield Cycle)</option>
                      <option value="12_months">12 Months (Extended Term Matrix)</option>
                    </select>
                  </div>

                  <!-- Input: Capital Principal Target -->
                  <div class="col-12">
                    <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Requested Capital Principal (USD)</label>
                    <div class="input-group">
                      <span class="input-group-text bg-light border-light-subtle font-monospace text-muted">$</span>
                      <input type="number" min="1000" max="25000" step="100" class="form-control bg-light border-light-subtle py-2.5 text-dark font-monospace" name="loan_amount" placeholder="Enter amount between 1,000 and 25,000" required>
                    </div>
                    <span class="text-muted d-block mt-1 font-monospace" style="font-size: 10px;">Your designated tier cap is dynamically locked at <strong class="text-success">$25,000.00</strong></span>
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
                  <th class="py-3">INTEREST RATE</th>
                  <th class="py-3">TERM LIMIT</th>
                  <th class="py-3">STATUS</th>
                  <th class="pe-3 py-3 text-end">MANAGEMENT</th>
                </tr>
              </thead>
              <tbody class="small">
                <!-- Example Static Layout Entry: Pending Verification Case -->
                <tr class="border-bottom border-light-subtle">
                  <td class="ps-3 py-3">
                    <span class="text-muted font-monospace d-block" style="font-size: 11px;">#LN-78322-A</span>
                    <strong class="text-dark">Node Liquidity Buffer</strong>
                  </td>
                  <td class="py-3 fw-bold font-monospace text-dark">$5,000.00</td>
                  <td class="py-3 font-monospace text-secondary">4.75% APR</td>
                  <td class="py-3 text-secondary">6 Months</td>
                  <td class="py-3">
                    <span class="badge bg-warning bg-opacity-10 text-dark border border-warning border-opacity-20 rounded-pill px-2.5 py-1.5 font-monospace" style="font-size: 10px;">Awaiting Review</span>
                  </td>
                  <td class="pe-3 py-3 text-end">
                    <button class="btn btn-light btn-sm text-muted rounded-2 border border-light-subtle py-1 px-2 font-monospace" style="font-size: 11px;" disabled>Awaiting Action</button>
                  </td>
                </tr>

                <!-- Example Static Layout Entry: Active Running Approved Case -->
                <tr class="border-bottom border-light-subtle">
                  <td class="ps-3 py-3">
                    <span class="text-muted font-monospace d-block" style="font-size: 11px;">#LN-61029-B</span>
                    <strong class="text-dark">Staking Capital Leverage</strong>
                  </td>
                  <td class="py-3 fw-bold font-monospace text-dark">$12,500.00</td>
                  <td class="py-3 font-monospace text-secondary">4.75% APR</td>
                  <td class="py-3 text-secondary">12 Months</td>
                  <td class="py-3">
                    <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-20 rounded-pill px-2.5 py-1.5 font-monospace" style="font-size: 10px;">Active Allocation</span>
                  </td>
                  <td class="pe-3 py-3 text-end">
                    <a href="/repay.php?id=LN-61029-B" class="btn btn-success btn-sm rounded-2 py-1 px-2.5 fw-medium" style="font-size: 12px;">Repay Loan</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

        </div>

      <?php endif; ?>

    </div>
  </div>
</div>