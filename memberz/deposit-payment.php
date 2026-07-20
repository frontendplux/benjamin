<?php 
include __DIR__."/header.php";
if(isset($_GET['suid'])){
  $_SESSION['deposit_uid']=$_GET['suid'];
}

if(empty($_SESSION['deposit_uid'])){
    header("Location: /investment-plans");
    exit;
}

$deposit_uid = $_SESSION['deposit_uid'];

$deposit = $conn->prepare("
    SELECT
        d.deposit_uid,
        d.amount,
        d.status AS deposit_status,

        p.name AS package_name,
        p.description,
        p.roi,
        p.duration_value,
        p.duration_unit,
        p.min_limit,
        p.max_limit,
        p.approval,

        w.network,
        w.coin_symbol,
        w.wallet_address,
        w.description as wallet_description

    FROM deposits d

    INNER JOIN investment_plan p
        ON p.id = d.investment_plan_id

    INNER JOIN wallets w
        ON w.id = d.wallet_id

    WHERE d.deposit_uid=?

    LIMIT 1
");

$deposit->bind_param("s", $deposit_uid);
$deposit->execute();
$depositData = $deposit->get_result()->fetch_assoc();

if(!$depositData){
    header("Location: /investment-plans");
    exit;
}

$package_name = htmlspecialchars($depositData['package_name']);
$description = htmlspecialchars($depositData['description']);
$amount = (float)$depositData['amount'];
$requested_amount = number_format($amount,2);
$wallet_address = htmlspecialchars($depositData['wallet_address']);
$network = htmlspecialchars($depositData['network']);
$coin_symbol = htmlspecialchars($depositData['coin_symbol']);
$roi = htmlspecialchars($depositData['roi']);
$duration = htmlspecialchars($depositData['duration_value']." ".ucfirst($depositData['duration_unit']));
$min_limit = number_format($depositData['min_limit'],2);
$max_limit = number_format($depositData['max_limit'],2);
$approval = ucfirst(htmlspecialchars($depositData['approval']));
$deposit_status = ucfirst(htmlspecialchars($depositData['deposit_status']));
$trc20_address = $wallet_address;

// Security check
if($amount < $depositData['min_limit'] || $amount > $depositData['max_limit']){
    session_destroy();
    header("Location:/investment-plans");
    exit;
}

// Stop payment if already completed
if(in_array($depositData['deposit_status'], ["approved", "rejected", "cancelled"])){
    header("Location:/dashboard");
    exit;
}
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>
    <!-- MAIN DEPOSIT PROCESSING CONTENT AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span>Investment Plans</span>
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">USDT Gateway</span>
      </div>

      <!-- Main Layout Configuration Split -->
      <div class="row g-4 justify-content-center">
        <div class="col-10 col-lg-8 w-100">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 text-center">
            
            <!-- Protocol Header Branding -->
            <div class="mb-4">
              <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 py-1.5 px-3 rounded-pill fs-8 fw-medium mb-2">
                <?= $network ?> Network Protocol Enabled
              </span>
              <h5 class="fw-bold text-dark mb-1">Send <?= $coin_symbol ?> (<?= $network ?>)</h5>
              <p class="text-muted small mb-0">Transfer the exact asset balance required to clear your custom staking slot requirements.</p>
            </div>

            <!-- Inbound Target Funding Callout Box -->
            <div class="p-3 bg-light bg-opacity-70 border border-light-subtle rounded-4 mb-4">
              <span class="text-muted font-monospace d-block mb-1" style="font-size:11px;text-transform:uppercase;">
                Deposit Amount
              </span>
              <span class="display-6 fw-bold text-dark font-monospace">
                $<?= $requested_amount ?> <span class="fs-4 text-secondary"><?= $coin_symbol ?></span>
              </span>

              <div class="mt-4 text-start small text-secondary">
                <div class="d-flex justify-content-between py-2 border-bottom">
                  <span>Deposit ID</span>
                  <strong class="text-dark"><?= htmlspecialchars($deposit_uid) ?></strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                  <span>Investment Plan</span>
                  <strong class="text-success"><?= $package_name ?></strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                  <span>ROI</span>
                  <strong class="text-dark"><?= $roi ?>%</strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                  <span>Duration</span>
                  <strong class="text-dark"><?= $duration ?></strong>
                </div>
                <div class="d-flex justify-content-between py-2 border-bottom">
                  <span>Investment Range</span>
                  <strong class="text-dark">$<?= $min_limit ?> - $<?= $max_limit ?></strong>
                </div>
                <div class="d-flex justify-content-between py-2">
                  <span>Initial Status</span>
                  <strong class="text-warning" id="live-status-badge"><?= $deposit_status ?></strong>
                </div>
              </div>
            </div>

            <!-- Secured Copy Node Vector Component -->
            <div class="mb-4 text-start">
              <label class="small text-muted mb-1 font-monospace d-block text-center" style="font-size: 11px;">Official Deposit Address</label>
              <div class="input-group">
                <input type="text" id="walletAddress" class="form-control bg-light border-light-subtle py-2.5 font-monospace text-center small text-dark fw-medium rounded-start-3" value="<?= htmlspecialchars($trc20_address) ?>" readonly>
                <button class="btn btn-success px-3 rounded-end-3" type="button" onclick="copyAddressText()">
                  <i class="bi bi-copy" id="copyIcon"></i>
                </button>
              </div>
            </div>

            <!-- Operational Flow Disclaimer Alert Block -->
            <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-start mb-4">
              <div class="d-flex gap-2">
                <i class="bi bi-exclamation-triangle text-warning fs-5 mt-1"></i>
                <div class="small text-secondary" style="line-height: 1.45;">
                  <strong>Important Network Notice:</strong> <?= $depositData['wallet_description'] ?>
                </div>
              </div>
            </div>

            <hr class="text-light-subtle my-4">

            <!-- TRACKING AND INTERACTION HUB -->
            <div id="payment-action-zone">
              <button type="button" id="confirmPaymentBtn" class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm fs-5" onclick="startPaymentVerification()">
                <i class="bi bi-check2-circle me-2"></i>I Have Made the Payment
              </button>
            </div>

          </div>
        </div>
      </div>

    </div>
  </div>
</div>

<!-- CLIENT INTERACTION & REAL-TIME CHECK SCRIPT -->
<script>
let checkInterval = null;

function copyAddressText() {
  var copyText = document.getElementById("walletAddress");
  copyText.select();
  copyText.setSelectionRange(0, 99999); 
  navigator.clipboard.writeText(copyText.value);

  var icon = document.getElementById("copyIcon");
  icon.className = "bi bi-check-lg text-white";
  setTimeout(function() {
    icon.className = "bi bi-copy";
  }, 2000);
}

function startPaymentVerification() {
  // 1. Transform the action button zone into a real-time status loading state
  const container = document.getElementById("payment-action-zone");
  container.innerHTML = `
    <div class="p-4 border border-light-subtle bg-light rounded-4 text-center shadow-inner">
      <div class="spinner-border text-success mb-3" style="width: 2.5rem; height: 2.5rem;" role="status">
        <span class="visually-hidden">Loading...</span>
      </div>
      <h6 class="fw-bold text-dark mb-1">Verifying Blockchain Settlement</h6>
      <p class="text-muted small mb-0">Checking database records for confirmation. Please keep this screen open.</p>
    </div>
  `;

  // Update visual status metric badge to pending/processing state
  const statusBadge = document.getElementById("live-status-badge");
  if(statusBadge) {
    statusBadge.innerText = "Processing";
    statusBadge.className = "text-primary";
  }

  // 2. Fire immediate call, then poll database every 4 seconds
  checkPaymentStatus();
  checkInterval = setInterval(checkPaymentStatus, 4000);
}

async function checkPaymentStatus() {
  try {
    // Queries the same central data architecture via POST payload checks
    const response = await fetch("<?= $company_info['main-server'] ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json"
      },
      body: JSON.stringify({
        action: "/member/verify-deposit-status",
        deposit_uid: "<?= htmlspecialchars($deposit_uid) ?>"
      })
    });

    const data = await response.json();

    if (data.success && data.status === "approved") {
      // Success criteria met! Stop the cycle loop wrapper and redirect
      clearInterval(checkInterval);
      
      Swal.fire({
        icon: "success",
        title: "Payment Confirmed!",
        text: "Your capital stake allocation is now active.",
        confirmButtonText: "Go to Dashboard"
      }).then(() => {
        location.href = "/dashboard";
      });
    } else if (data.status === "rejected" || data.status === "cancelled") {
      clearInterval(checkInterval);
      Swal.fire({
        icon: "error",
        title: "Transaction Stopped",
        text: data.message || "This transaction sequence was declined."
      }).then(() => {
        location.href = "/investment-plans";
      });
    }
    // else if (data.status === "pending") {
    //   clearInterval(checkInterval);
    //   Swal.fire({
    //     icon: "error",
    //     title: "Transaction Stopped",
    //     text: data.message || "This transaction sequence was declined."
    //   }).then(() => {
    //     location.href = "/investment-plans";
    //   });
    // }
  } catch (error) {
    console.error("Status check ping failed:", error);
  }
}
</script>