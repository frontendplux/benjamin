<?php 
include __DIR__."/header.php";
$user_uid = $_SESSION['user_id'] ?? null;
if(!$user_uid){
    header("Location: /login");
    exit;
}
// Get transaction history
$stmt = $conn->prepare("
    SELECT
        transaction_uid,
        type,
        asset,
        amount,
        direction,
        status,
        description,
        created_at

    FROM transactions

    WHERE user_uid = ?

    ORDER BY id DESC
");
if(!$stmt){
    throw new Exception($conn->error);
}

$stmt->bind_param(
    "s",
    $user_uid
);


$stmt->execute();


$result = $stmt->get_result();


$transactions=[];


while($row=$result->fetch_assoc()){

    $transactions[]=$row;

}

$totalDeposit = $conn->prepare("
SELECT SUM(amount) total
FROM transactions
WHERE user_uid=?
AND type='deposit'
AND direction='credit'
AND status='success'
");

$totalDeposit->bind_param("s",$user_uid);

$totalDeposit->execute();

$totalFunding =
$totalDeposit->get_result()
->fetch_assoc()['total'] ?? 0;

$activeInvestment=$conn->prepare("
SELECT SUM(amount) total
FROM deposits
WHERE user_uid=?
AND status='approved'
");


$activeInvestment->bind_param(
"s",
$user_uid
);


$activeInvestment->execute();


$activeValue=
$activeInvestment
->get_result()
->fetch_assoc()['total'] ?? 0;

$pending=$conn->prepare("
SELECT SUM(amount) total
FROM deposits
WHERE user_uid=?
AND status IN('pending','reviewing')
");


$pending->bind_param(
"s",
$user_uid
);


$pending->execute();


$pendingValue=
$pending
->get_result()
->fetch_assoc()['total'] ?? 0;

?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN HISTORY CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Transaction History</span>
      </div>

      <!-- Quick Metrics Overview Row -->
      <div class="row g-3 mb-4">
        <div class="col-sm-4">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-3">
            <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">TOTAL FUNDING VOLUME</span>
            <span class="fs-4 fw-bold text-dark font-monospace">
              $<?=number_format($totalFunding,2)?>
            </span>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-3">
            <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">ACTIVE STAKING VALUE</span>
            <span class="fs-4 fw-bold text-success font-monospace">
              $<?=number_format($activeValue,2)?>
            </span>
          </div>
        </div>
        <div class="col-sm-4">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-3">
            <span class="text-muted d-block small font-monospace mb-1" style="font-size: 11px;">PENDING CLEARANCE</span>
            <span class="fs-4 fw-bold text-warning font-monospace">
              $<?=number_format($pendingValue,2)?>
            </span>
          </div>
        </div>
      </div>

      <!-- Core Ledger Architecture Wrapper -->
      <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light-subtle">
          <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-clock-history text-success me-2"></i>Account Ledger Matrix</h5>
            <p class="text-muted mb-0 small">Comprehensive, unalterable historical registry of system node executions.</p>
          </div>
          <span class="badge bg-light text-secondary border border-light-subtle rounded-pill font-monospace px-2.5 py-1.5" style="font-size: 11px;">
            <i class="bi bi-shield-lock text-success me-1"></i> Ledger Immutable
          </span>
        </div>

        <!-- High Contrast Data Grid Matrix -->
        <div class="table-responsive">
          <table class="table table-borderless align-middle mb-0 text-secondary font-monospace" style="font-size: 13px;">
            <thead>
              <tr class="table-light border-bottom border-light-subtle">
                <th scope="col" class="py-2.5 text-dark fw-medium">TRANSACTION ID</th>
                <th scope="col" class="py-2.5 text-dark fw-medium">VECTOR TYPE</th>
                <th scope="col" class="py-2.5 text-dark fw-medium">ASSET LAYER</th>
                <th scope="col" class="py-2.5 text-dark fw-medium">QUANTITY VALUE</th>
                <th scope="col" class="py-2.5 text-dark fw-medium">EXECUTION</th>
                <th scope="col" class="py-2.5 text-dark fw-medium text-end">TIMESTAMP</th>
              </tr>
            </thead>
           <tbody>

<?php if(count($transactions)>0): ?>

<?php foreach($transactions as $txn): ?>

<tr class="border-bottom border-light-subtle">

<td class="py-3 fw-bold text-dark">
<?=htmlspecialchars($txn['transaction_uid'])?>
</td>


<td class="py-3 text-dark text-capitalize">
<?=htmlspecialchars($txn['type'])?>
</td>


<td class="py-3">
<?=htmlspecialchars($txn['asset'])?>
</td>


<td class="py-3 fw-semibold 
<?= $txn['direction']=="credit" 
? 'text-success'
: 'text-danger' ?>">


<?= $txn['direction']=="credit" ? '+' : '-' ?>

$<?=number_format($txn['amount'],2)?>


</td>


<td class="py-3">

<?php if($txn['status']=="success"): ?>

<span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 rounded-pill px-2 py-1">

<i class="bi bi-check-circle-fill me-1"></i>
Success

</span>


<?php elseif($txn['status']=="pending" || $txn['status']=="processing"): ?>


<span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-10 rounded-pill px-2 py-1">

<i class="bi bi-hourglass-split me-1"></i>
<?=ucfirst($txn['status'])?>

</span>


<?php else: ?>


<span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-10 rounded-pill px-2 py-1">

<i class="bi bi-x-circle me-1"></i>
<?=ucfirst($txn['status'])?>

</span>


<?php endif; ?>


</td>


<td class="py-3 text-end text-muted small">

<?=date(
"d M Y H:i",
strtotime($txn['created_at'])
)?>

</td>


</tr>


<?php endforeach; ?>


<?php else: ?>


<tr>

<td colspan="6" class="text-center py-5 text-muted">

<i class="bi bi-inbox fs-3 d-block mb-2"></i>

No transaction history available

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