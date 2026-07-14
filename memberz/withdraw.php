<?php 
include __DIR__."/header.php"; 

// Check active UI state view parameter ('request' or 'history')
$active_view = isset($_GET['view']) ? strtolower(trim($_GET['view'])) : 'request';
if (!in_array($active_view, ['request', 'history'])) {
    $active_view = 'request';
}

$user_uid = $_SESSION['user_id'] ?? null;

if (!$user_uid) {
    header("Location: /login");
    exit;
}

$available_balance = 0;

$stmt = $conn->prepare("
SELECT wallet_balance
FROM user_wallet
WHERE user_uid=?
LIMIT 1
");

$stmt->bind_param("s",$user_uid);

$stmt->execute();

$stmt->bind_result($available_balance);

$stmt->fetch();

$stmt->close();


$pending_withdrawals = 0;

$stmt = $conn->prepare("
SELECT COALESCE(SUM(amount),0)
FROM withdrawals
WHERE user_uid=?
AND status IN('pending','processing')
");

$stmt->bind_param("s",$user_uid);

$stmt->execute();

$stmt->bind_result($pending_withdrawals);

$stmt->fetch();

$stmt->close();


$total_withdrawn = 0;

$stmt = $conn->prepare("
SELECT COALESCE(SUM(amount),0)
FROM withdrawals
WHERE user_uid=?
AND status='approved'
");

$stmt->bind_param("s",$user_uid);

$stmt->execute();

$stmt->bind_result($total_withdrawn);

$stmt->fetch();

$stmt->close();


$page = max(1,(int)($_GET['page'] ?? 1));

$limit = 10;

$offset = ($page - 1) * $limit;

$count = $conn->prepare("
SELECT COUNT(*)
FROM withdrawals
WHERE user_uid=?
");

$count->bind_param("s",$user_uid);

$count->execute();

$count->bind_result($totalRows);

$count->fetch();

$count->close();

$totalPages = max(1,ceil($totalRows/$limit));

$stmt = $conn->prepare("
SELECT *
FROM withdrawals
WHERE user_uid=?
ORDER BY id DESC
LIMIT ? OFFSET ?
");

$stmt->bind_param(
    "sii",
    $user_uid,
    $limit,
    $offset
);

$stmt->execute();

$withdrawals = $stmt->get_result();
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN WITHDRAWAL CONTENT WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Withdrawal Hub</span>
      </div>

      <!-- VIEW NAVIGATION TABS -->
      <div class="card border-0 shadow-sm bg-white rounded-4 p-2 mb-4">
        <ul class="nav nav-pills nav-fill gap-1">
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2.5 small fw-bold <?= $active_view === 'request' ? 'bg-success text-white active' : 'text-secondary bg-transparent' ?>" href="?view=request">
              <i class="bi bi-cash-stack me-2"></i>Request Withdrawal
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link rounded-3 py-2.5 small fw-bold <?= $active_view === 'history' ? 'bg-success text-white active' : 'text-secondary bg-transparent' ?>" href="?view=history">
              <i class="bi bi-clock-history me-2"></i>Withdrawal Ledger
            </a>
          </li>
        </ul>
      </div>

      <!-- CONDITIONAL VIEW LAYER DISPLAY ROUTER -->
      <?php if ($active_view === 'request'): ?>
        
        <!-- VIEW 1: REQUEST WITHDRAWAL FORM -->
        <div class="row g-4">
          <!-- Left Side: Dynamic Request Form Card -->
          <div class="col-lg-8">
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
              <div class="mb-4 pb-2 border-bottom border-light-subtle">
                <h5 class="fw-bold text-dark mb-1"><i class="bi bi-arrow-up-right-square text-success me-2"></i>Initiate Payout Transfer</h5>
                <p class="text-muted mb-0 small">Transfer settled capital to your verified external accounts. Processing is executed systematically across standard clearance networks.</p>
              </div>

              <!-- Withdrawal Form Pipeline -->
             <form id="withdrawForm">

    <div class="mb-3">

        <label class="form-label">
            Available Balance
        </label>

        <input
            type="text"
            class="form-control"
            value="$<?= number_format($available_balance,2) ?>"
            readonly>

    </div>

    <div class="mb-3">

        <label class="form-label">
            Withdrawal Amount
        </label>

        <input
            type="number"
            min="1"
            step="0.01"
            name="amount"
            id="amount"
            class="form-control"
            required>

    </div>

    <button
        type="submit"
        class="btn btn-success w-100"
        id="withdrawBtn">

        Request Withdrawal

    </button>

</form>
            </div>
          </div>

          <!-- Right Side: Account Balances & Status Checklists -->
          <div class="col-lg-4">
            
            <!-- Card: Dynamic Balance Displays -->
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-wallet2 text-success me-2"></i>Account Ledger Matrix</h6>
              
              <div class="mb-1 p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-center">
                <span class="text-muted d-block font-monospace small" style="font-size: 11px;">AVAILABLE BALANCE</span>
                <span class="display-6 fw-bold text-success font-monospace">$<?= number_format($available_balance, 2) ?></span>
              </div>

              <div class="p-3 mb-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-center mt-3">
                    <span class="text-muted d-block font-monospace small">
                        TOTAL WITHDRAWN
                    </span>

                    <span class="h4 fw-bold text-info font-monospace">
                        $<?= number_format($total_withdrawn,2) ?>
                    </span>
                </div>

              <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-center">
                <span class="text-muted d-block font-monospace small" style="font-size: 11px;">PENDING WITHDRAWAL</span>
                <span class="h4 fw-bold text-dark font-monospace">$<?= number_format($pending_withdrawals, 2) ?></span>
              </div>
            </div>

            <!-- Card: Withdrawal Security Pre-checks -->
            <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
              <h6 class="fw-bold text-dark mb-3"><i class="bi bi-shield-check text-success me-2"></i>Security Compliance Matrix</h6>
              <ul class="list-unstyled mb-0 font-monospace text-secondary" style="font-size: 12px;">
                <li class="d-flex align-items-center gap-2 mb-2.5">
                  <i class="bi bi-check2-circle text-success fs-6"></i>
                  <span>Verified Recipient Address</span>
                </li>
                <li class="d-flex align-items-center gap-2 mb-2.5">
                  <i class="bi bi-check2-circle text-success fs-6"></i>
                  <span>2FA Dynamic Security Enabled</span>
                </li>
                <li class="d-flex align-items-center gap-2 mb-2.5">
                  <i class="bi bi-check2-circle text-success fs-6"></i>
                  <span>Within Daily Limit ($50K cap)</span>
                </li>
                <li class="d-flex align-items-center gap-2 opacity-50">
                  <i class="bi bi-circle text-muted fs-6" style="font-size: 12px;"></i>
                  <span>No Active Security Cooldowns</span>
                </li>
              </ul>
            </div>
          </div>
        </div>

      <?php else: ?>
        
        <!-- VIEW 2: WITHDRAWAL HISTORY LEDGER -->
        <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
          <div class="mb-4 pb-2 border-bottom border-light-subtle">
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-journal-text text-success me-2"></i>Withdrawal History</h5>
            <p class="text-muted mb-0 small">Overview of cleared transfers, pending releases, and network fee indexes.</p>
          </div>

          <!-- HISTORY TABLE CONTAINER -->
          <div class="table-responsive">
            <table class="table table-borderless align-middle mb-0">
              <thead>
                <tr class="bg-light rounded-3 font-monospace small text-secondary" style="font-size: 11px; border-bottom: 2px solid #f8f9fa;">
                  <th class="ps-3 py-3">PAYOUT ID / METHOD</th>
                  <th class="py-3">SETTLED AMOUNT</th>
                  <th class="py-3">TARGET ROUTE</th>
                  <th class="py-3">INITIATED ON</th>
                  <th class="py-3">STATUS</th>
                  <th class="pe-3 py-3 text-end">AUDIT CHECK</th>
                </tr>
              </thead>
              <tbody class="small">
                
                <?php if($withdrawals->num_rows): ?>

<?php while($row = $withdrawals->fetch_assoc()): ?>

<tr>

<td>

<small class="text-muted d-block">

<?= $row['withdrawal_uid'] ?>

</small>

<strong>

<?= htmlspecialchars($row['network']) ?>

</strong>

</td>

<td>

$<?= number_format($row['amount'],2) ?>

</td>

<td>

<?= htmlspecialchars($row['wallet_address']) ?>

</td>

<td>

<?= date("d M Y H:i",strtotime($row['created_at'])) ?>

</td>

<td>

<?php

$badge = [
    'pending'=>'warning',
    'processing'=>'info',
    'approved'=>'success',
    'rejected'=>'danger',
    'cancelled'=>'secondary'
][$row['status']];
?>

<span class="badge bg-<?= $badge ?>">
    <?= ucfirst($row['status']) ?>
</span>

</td>

<td class="text-end">

<?php if(!empty($row['transaction_hash'])): ?>

<small class="text-success">

<?= substr($row['transaction_hash'],0,18) ?>...

</small>

<?php else: ?>

-

<?php endif; ?>

</td>

</tr>

<?php endwhile; ?>

<?php else: ?>

<tr>

<td colspan="6" class="text-center py-5 text-muted">

No withdrawal history found.

</td>

</tr>

<?php endif; ?>     
              </tbody>
            </table>
          </div>
          <?php if($totalPages > 1): ?>

<nav class="mt-4">

<ul class="pagination justify-content-center">

<?php for($i=1;$i<=$totalPages;$i++): ?>

<li class="page-item <?= $page==$i ? 'active' : '' ?>">

<a class="page-link" href="?view=history&page=<?= $i ?>">

<?= $i ?>

</a>

</li>

<?php endfor; ?>

</ul>

</nav>

<?php endif; ?>
        </div>

      <?php endif; ?>

    </div>
  </div>
</div>

<script>

document
.getElementById("withdrawForm")
.addEventListener("submit", async function(e){

    e.preventDefault();

    const btn = document.getElementById("withdrawBtn");

    const amount =
    parseFloat(
        document.getElementById("amount").value
    );

    btn.disabled = true;
    btn.innerHTML = "Processing...";

    try{

        const response = await fetch(
            "<?= $company_info['main-server'] ?>",
            {
                method:"POST",

                headers:{
                    "Content-Type":"application/json"
                },

                body:JSON.stringify({
                    action:"/member/create-withdrawal",
                    amount:amount
                })
            }
        );

        const data = await response.json();

        if(data.success){

            Swal.fire({
                icon:"success",
                title:"Success",
                text:data.message
            }).then(()=>{
                location.reload();
            });

        }else{

            Swal.fire({
                icon:"error",
                title:"Failed",
                text:data.message
            });

        }

    }catch(error){

        Swal.fire({
            icon:"error",
            title:"Error",
            text:"Unable to connect to server."
        });

    }

    btn.disabled = false;
    btn.innerHTML = "Request Withdrawal";

});

</script>