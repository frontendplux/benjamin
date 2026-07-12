<?php 
include __DIR__."/header.php";
$user_uid = $_SESSION['user_id'];

$getUser = $conn->prepare("
SELECT
    u.uid,
    u.first_name,
    u.last_name,
    u.email,
    u.phone,
    u.country,
    u.created_at,

    uw.wallet_balance,

    k.is_verified,

    (
        SELECT COUNT(*)
        FROM deposits
        WHERE user_uid=u.uid
        AND status='approved'
    ) AS active_investments,

    (
        SELECT COUNT(*)
        FROM referrals
        WHERE referred_by=u.uid
    ) AS total_referrals

FROM users u

LEFT JOIN user_wallet uw
ON uw.user_uid=u.uid

LEFT JOIN kyc k
ON k.user_uid=u.uid

WHERE u.uid=?
LIMIT 1
");

$getUser->bind_param("s",$user_uid);
$getUser->execute();

$userData = $getUser->get_result()->fetch_assoc();

$userData['wallet_balance'] = $userData['wallet_balance'] ?? 0;
$userData['active_investments'] = $userData['active_investments'] ?? 0;
$userData['total_referrals'] = $userData['total_referrals'] ?? 0;

$verified = !empty($userData['is_verified']); 

$getLogs = $conn->prepare("
SELECT
ip_address,
device,
status,
created_at
FROM login_history
WHERE user_uid=?
ORDER BY created_at DESC
LIMIT 3
");

$getLogs->bind_param("s",$user_uid);
$getLogs->execute();

$logs = $getLogs->get_result();
?>



<?php
// $ip = $_SERVER['REMOTE_ADDR'] ?? '';
// $device = $_SERVER['HTTP_USER_AGENT'] ?? '';

// $history = $conn->prepare("
// INSERT INTO login_history
// (
//     user_uid,
//     ip_address,
//     device,
//     browser
// )
// VALUES
// (?,?,?,?)
// ");

// $history->bind_param(
//     "ssss",
//     $user_uid,
//     $ip,
//     $device,
//     $device
// );

// $history->execute();

?>
<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN PROFILE CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">My Profile</span>
      </div>

      <!-- Main Profile Layout Grid Row -->
      <div class="row g-4">
        
        <!-- Left Column: Identity Card Overview -->
        <div class="col-lg-4">
          <!-- Main Identity Card Wrap -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 text-center mb-4">
            <div class="position-relative d-inline-block mx-auto mb-3">
              <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold display-6 shadow-sm" style="width: 90px; height: 90px;">
                <?= strtoupper(substr($userData['first_name'], 0, 1) . substr($userData['last_name'], 0, 1)) ?>
              </div>
              <span class="position-absolute bottom-0 end-0 bg-success border border-white border-3 rounded-circle" style="width: 18px; height: 18px;" title="Account Active"></span>
            </div>

            <h5 class="fw-bold text-dark mb-1"><?= $userData['first_name'] . ' ' . $userData['last_name'] ?></h5>
            <span class="text-muted font-monospace d-block small mb-3" style="font-size: 12px;">UID: <?= htmlspecialchars($userData['uid']) ?></span>

            <div class="d-flex justify-content-center gap-2 mb-3">
              <span class="badge <?= $verified ? 'bg-success' : 'bg-warning' ?> bg-opacity-10 <?= $verified ? 'text-success' : 'text-warning' ?> border border-success border-opacity-10 py-1 px-3 rounded-pill fs-8 fw-medium">
                <i class="bi bi-patch-check-fill me-1"></i> <?= $verified ? 'KYC Verified' : 'KYC Pending' ?>
              </span>
            </div>

            <hr class="my-3 border-light-subtle">

            <!-- System Join Milestones -->
            <div class="text-start font-monospace text-secondary" style="font-size: 12px;">
              <div class="d-flex justify-content-between py-1">
                <span>Account Tier:</span>
                <span class="text-dark fw-bold"><?= $verified ? 'Verified Member' : 'Standard Member' ?></span>
              </div>
              <div class="d-flex justify-content-between py-1">
                <span>Node Member Since:</span>
                <span class="text-dark"><?= date("M Y",strtotime($userData['created_at'])) ?></span>
              </div>
            </div>
          </div>

          <!-- Account Operational Limits Box -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-shaded text-success me-2"></i>Account Controls & Limits</h6>
            
            <div class="mb-3">
              <div class="d-flex justify-content-between text-secondary small mb-1" style="font-size: 12px;">
                <span>Daily Withdrawal Remaining</span>
                <span class="text-dark fw-medium">$<?= number_format($userData['wallet_balance'],2) ?></span>
              </div>
              <div class="progress bg-light" style="height: 6px;">
                <div class="progress-bar bg-success" role="progressbar" style="width:<?= min(100, ($userData['wallet_balance']/50000)*100) ?>%"></div>
              </div>
            </div>

            <div class="mb-0">
              <div class="d-flex justify-content-between text-secondary small mb-1" style="font-size: 12px;">
                <span>Concurrent Active Staking Slots</span>
                <span class="text-dark fw-medium"><?= $userData['active_investments'] ?> Active</span>
              </div>
              <div class="progress bg-light" style="height: 6px;">
                <div class="progress-bar bg-success bg-opacity-50" role="progressbar" style="width:<?= min(100,($userData['active_investments']/10)*100) ?>%"></div>
              </div>
            </div>
          </div>
        </div>

        <!-- Right Column: Institutional Profile & Security Parameter Summaries -->
        <div class="col-lg-8">
          <!-- Information Block Component Grid -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
            <div class="mb-4 pb-2 border-bottom border-light-subtle">
              <h5 class="fw-bold text-dark mb-1">Profile Dossier Overview</h5>
              <p class="text-muted mb-0 small">Verified registration variables matching legal identity compliance data packages.</p>
            </div>

            <!-- Profile Summary Matrix -->
            <div class="row g-3">
              <div class="col-sm-6">
                <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3">
                  <span class="text-muted d-block small mb-1" style="font-size: 11px; text-uppercase; tracking-wider;">First Name</span>
                  <span class="text-dark fw-semibold font-monospace"><?= $userData['first_name'] ?></span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3">
                  <span class="text-muted d-block small mb-1" style="font-size: 11px; text-uppercase; tracking-wider;">Last Name</span>
                  <span class="text-dark fw-semibold font-monospace"><?= $userData['last_name'] ?></span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3">
                  <span class="text-muted d-block small mb-1" style="font-size: 11px; text-uppercase; tracking-wider;">Registered Email Address</span>
                  <span class="text-dark fw-semibold font-monospace"><?= $userData['email'] ?></span>
                </div>
              </div>
              <div class="col-sm-6">
                <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3">
                  <span class="text-muted d-block small mb-1" style="font-size: 11px; text-uppercase; tracking-wider;">Mobile Identification</span>
                  <span class="text-dark fw-semibold font-monospace"><?= !empty($userData['phone']) ? $userData['phone'] : 'Not Linked' ?></span>
                </div>
              </div>
              <div class="col-sm-6">
    <div class="p-3 bg-light border rounded-3">
        <span class="text-muted d-block small mb-1">
            Wallet Balance
        </span>

        <span class="fw-bold text-success">
            $<?= number_format($userData['wallet_balance'],2) ?>
        </span>
    </div>
</div>

<div class="col-sm-6">
    <div class="p-3 bg-light border rounded-3">
        <span class="text-muted d-block small mb-1">
            Country
        </span>

        <span class="fw-bold">
            <?= htmlspecialchars($userData['country']) ?>
        </span>
    </div>
</div>

<div class="col-sm-6">
    <div class="p-3 bg-light border rounded-3">
        <span class="text-muted d-block small mb-1">
            Referrals
        </span>

        <span class="fw-bold">
            <?= number_format($userData['total_referrals']) ?>
        </span>
    </div>
</div>

<div class="col-sm-6">
    <div class="p-3 bg-light border rounded-3">
        <span class="text-muted d-block small mb-1">
            Active Investments
        </span>

        <span class="fw-bold">
            <?= number_format($userData['active_investments']) ?>
        </span>
    </div>
</div>
            </div>
          </div>

          <!-- Dynamic Activity Clearance Registry logs -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold text-dark mb-0"><i class="bi bi-clock-history text-success me-2"></i>Security & Session Diagnostics</h6>
              <span class="text-muted font-monospace" style="font-size: 11px;">E2E Encryption Active</span>
            </div>

            <div class="table-responsive">
              <table class="table table-borderless align-middle mb-0 text-secondary font-monospace" style="font-size: 12px;">
                <thead>
                  <tr class="table-light border-bottom border-light-subtle">
                    <th scope="col" class="py-2 text-dark fw-medium">EVENT TYPE</th>
                    <th scope="col" class="py-2 text-dark fw-medium">IP LOCATION</th>
                    <th scope="col" class="py-2 text-dark fw-medium">DEVICE TYPE</th>
                    <th scope="col" class="py-2 text-dark fw-medium text-end">TIMESTAMP</th>
                  </tr>
                </thead>
                <tbody>
                  <?php while($log = $logs->fetch_assoc()): ?>

<tr>

<td>
<?= ucfirst($log['status']) ?>
</td>

<td>
<?= htmlspecialchars($log['ip_address']) ?>
</td>

<td>
<?= htmlspecialchars(substr($log['device'],0,40)) ?>
</td>

<td class="text-end">
<?= date("d M Y h:i A",strtotime($log['created_at'])) ?>
</td>

</tr>


<?php endwhile; ?>
                 
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>

    </div>
  </div>
</div>