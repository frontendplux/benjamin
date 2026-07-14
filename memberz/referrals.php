<?php 
include __DIR__."/header.php"; 
$user_uid = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| Referral Code & Link
|--------------------------------------------------------------------------
*/

$referral_code = $user_uid;

$referral_link =
(isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on' ? 'https://' : 'http://')
. $_SERVER['HTTP_HOST']
. "/register?ref="
. urlencode($referral_code);

/*
|--------------------------------------------------------------------------
| Total Referrals
|--------------------------------------------------------------------------
*/

$totalReferrals = 0;

$stmt = $conn->prepare("
SELECT COUNT(*)
FROM referrals
WHERE referred_by=?
");

$stmt->bind_param("s",$user_uid);
$stmt->execute();
$stmt->bind_result($totalReferrals);
$stmt->fetch();
$stmt->close();

/*
|--------------------------------------------------------------------------
| Active Referrals
| A referral becomes active after first approved investment
|--------------------------------------------------------------------------
*/

$activeNodes = 0;

$stmt = $conn->prepare("
SELECT COUNT(DISTINCT r.uid)

FROM referrals r

INNER JOIN deposits d
ON d.user_uid=r.uid

WHERE
r.referred_by=?
AND d.status='approved'
");

$stmt->bind_param("s",$user_uid);
$stmt->execute();
$stmt->bind_result($activeNodes);
$stmt->fetch();
$stmt->close();

/*
|--------------------------------------------------------------------------
| Earned Referral Commission
| 10% of first approved deposit only
|--------------------------------------------------------------------------
*/

$totalCommission = 0;

$stmt = $conn->prepare("

SELECT
COALESCE(SUM(firstDeposit.amount*0.10),0)

FROM
(
    SELECT
        MIN(id) id,
        user_uid,
        amount

    FROM deposits

    WHERE status='approved'

    GROUP BY user_uid

) firstDeposit

INNER JOIN referrals r

ON r.uid=firstDeposit.user_uid

WHERE r.referred_by=?

");

$stmt->bind_param("s",$user_uid);
$stmt->execute();
$stmt->bind_result($totalCommission);
$stmt->fetch();
$stmt->close();

/*
|--------------------------------------------------------------------------
| Recent Referrals
|--------------------------------------------------------------------------
*/

$recentReferral = $conn->prepare("

SELECT

u.first_name,
u.last_name,
u.created_at,

CASE
WHEN EXISTS(
SELECT 1
FROM deposits d
WHERE d.user_uid=u.uid
AND d.status='approved'
LIMIT 1
)
THEN 'Active'
ELSE 'Pending'
END status

FROM referrals r

INNER JOIN users u
ON u.uid=r.uid

WHERE r.referred_by=?

ORDER BY u.created_at DESC

LIMIT 10

");

$recentReferral->bind_param("s",$user_uid);
$recentReferral->execute();

$recentResult = $recentReferral->get_result();
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN REFERRAL CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Affiliate Network</span>
      </div>

      <!-- Network Performance Analytics Overview Row -->
      <div class="row g-3 mb-4">
        <div class="col-sm-4">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-3">
            <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">TOTAL REFERRALS</span>
            <span class="fs-4 fw-bold text-dark font-monospace"><?= number_format($totalReferrals) ?><span class="fs-6 text-secondary fw-normal">Accounts</span></span>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-3">
            <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">ACTIVE STAKING NODES</span>
            <span class="fs-4 fw-bold text-success font-monospace"><?= number_format($activeNodes) ?> <span class="fs-6 text-secondary fw-normal">Active</span></span>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-3">
            <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">EARNED COMMISSIONS</span>
            <span class="fs-4 fw-bold text-success font-monospace">$<?= number_format($totalCommission,2) ?></span>
          </div>
        </div>
      </div>

      <div class="row g-4">
        
        <!-- Left Side: Gateway and Clipboard Link Distribution Control -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 h-100">
            <div class="mb-4 pb-2 border-bottom border-light-subtle">
              <h5 class="fw-bold text-dark mb-1"><i class="bi bi-people-fill text-success me-2"></i>Network Expansion Hub</h5>
              <p class="text-muted mb-0 small">Invite friends and earn a 10% commission on their first approved investment.</p>
            </div>

            <!-- Secured Copy Invite Link Box -->
            <div class="mb-4 bg-light p-3 rounded-4 border border-light-subtle">
              <label class="small text-muted mb-1 font-monospace d-block" style="font-size: 11px;">Your Unique Invitation Hook</label>
              <div class="input-group">
                <input type="text" id="inviteLink" class="form-control bg-white border-light-subtle py-2.5 font-monospace text-center small text-dark fw-medium rounded-start-3" value="<?= $referral_link ?>" readonly>
                <button class="btn btn-success px-3 rounded-end-3" type="button" onclick="copyInviteText()">
                  <i class="bi bi-copy" id="copyIcon"></i>
                </button>
              </div>
            </div>

            <!-- Read Only Parameter Breakouts -->
            <div class="row g-3">
              <div class="col-6">
                <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-center">
                  <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">Your Node Code</span>
                  <span class="text-dark fw-bold font-monospace fs-5"><?= $referral_code ?></span>
                </div>
              </div>
              <div class="col-6">
                <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-center">
                  <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">Reward Rate</span>
                  <span class="text-success fw-bold font-monospace fs-5">10% First Investment Bonus</span>
                </div>
              </div>
            </div>
            
            <!-- Contextual Operational Policy Disclaimer -->
            <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-start mt-4">
              <div class="d-flex gap-2.5">
                <i class="bi bi-info-circle text-success fs-5 mt-n0.5"></i>
                <div class="small text-secondary" style="line-height: 1.45;">
                  <strong>Settlement Framework:</strong> Network margin overrides clear straight into your primary funding balance immediately after your linked companion initiates a validated node deployment. There are no tracking locks or layout hold delays.
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Right Side: Recent Signups Dynamic Register View -->
        <div class="col-lg-5">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 h-100">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-hdd-network text-success me-2"></i>Recent Network Nodes</h6>
            
            <div class="table-responsive">
              <table class="table table-borderless align-middle mb-0 text-secondary font-monospace" style="font-size: 12px;">
                <thead>
                  <tr class="table-light border-bottom border-light-subtle">
                    <th scope="col" class="py-2 text-dark fw-medium">USER NODE</th>
                    <th scope="col" class="py-2 text-dark fw-medium">STATUS</th>
                    <th scope="col" class="py-2 text-dark fw-medium text-end">DATE</th>
                  </tr>
                </thead>
             <tbody>

<?php if($recentResult->num_rows): ?>

<?php while($row=$recentResult->fetch_assoc()): ?>

<tr>

<td class="py-2 text-dark fw-medium">

<?= htmlspecialchars($row['first_name']." ".$row['last_name']) ?>

</td>

<td>

<?php if($row['status']=="Active"): ?>

<span class="badge bg-success">

Active

</span>

<?php else: ?>

<span class="badge bg-warning text-dark">

Pending

</span>

<?php endif; ?>

</td>

<td class="text-end text-muted">

<?= date("d M",strtotime($row['created_at'])) ?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="3" class="text-center text-muted py-4">

No referrals yet.

</td>

</tr>

<?php endif; ?>

</tbody>
              </table>
            </div>

          </div>
        </div>

      </div>

    </div>
  </div>
</div>

<!-- INSTANT UTILITY COPY OVERRIDE INTERACTION -->
<script>
function copyInviteText() {
  var copyText = document.getElementById("inviteLink");
  copyText.select();
  copyText.setSelectionRange(0, 99999);
  navigator.clipboard.writeText(copyText.value);

  var icon = document.getElementById("copyIcon");
  icon.className = "bi bi-check-lg text-white";
  setTimeout(function() {
    icon.className = "bi bi-copy";
  }, 2000);
}
</script>