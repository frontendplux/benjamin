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


// ==================================================

$user_uid = $user['uid'];

/*
|--------------------------------------------------------------------------
| Wallet Balance
|--------------------------------------------------------------------------
*/
$wallet_balance = 0;

$wallet = $conn->prepare("
    SELECT wallet_balance
    FROM user_wallet
    WHERE user_uid = ?
    LIMIT 1
");
$wallet->bind_param("s", $user_uid);
$wallet->execute();
$wallet_result = $wallet->get_result();

if ($wallet_result->num_rows) {
    $wallet_balance = (float)$wallet_result->fetch_assoc()['wallet_balance'];
}

/*
|--------------------------------------------------------------------------
| Active Investments
|--------------------------------------------------------------------------
*/

$total_investment = 0;
$total_profit = 0;

$investment = $conn->prepare("
SELECT
    d.amount,
    d.approved_at,

    p.roi,
    p.duration_value,
    p.duration_unit

FROM deposits d

INNER JOIN investment_plan p
ON p.id=d.investment_plan_id

WHERE
    d.user_uid=?
AND d.status='approved'
");

$investment->bind_param("s",$user_uid);
$investment->execute();

$result=$investment->get_result();

$now=time();

while($row=$result->fetch_assoc()){

    $expiry=new DateTime($row['approved_at']);

    switch($row['duration_unit']){

        case 'hours':
            $expiry->modify("+{$row['duration_value']} hours");
        break;

        case 'days':
            $expiry->modify("+{$row['duration_value']} days");
        break;

        case 'weeks':
            $expiry->modify("+{$row['duration_value']} weeks");
        break;

        case 'months':
            $expiry->modify("+{$row['duration_value']} months");
        break;

        case 'years':
            $expiry->modify("+{$row['duration_value']} years");
        break;
    }

    if($expiry->getTimestamp()>$now){

        $total_investment += $row['amount'];

        $total_profit += ($row['amount'] * $row['roi']) / 100;
    }
}

/*
|--------------------------------------------------------------------------
| Pending Referral Bonus
|--------------------------------------------------------------------------
|
| 10% of each referred user's FIRST approved investment
|
*/

$pending_referral_bonus=0;

$referral=$conn->prepare("
SELECT uid
FROM referrals
WHERE referred_by=?
AND status='pending'
");

$referral->bind_param("s",$user_uid);
$referral->execute();

$referrals=$referral->get_result();

while($ref=$referrals->fetch_assoc()){

    $firstDeposit=$conn->prepare("
        SELECT
            d.amount
        FROM deposits d
        WHERE
            d.user_uid=?
        AND d.status='approved'
        ORDER BY d.approved_at ASC
        LIMIT 1
    ");

    $firstDeposit->bind_param("s",$ref['uid']);
    $firstDeposit->execute();

    $deposit=$firstDeposit->get_result();

    if($deposit->num_rows){

        $amount=(float)$deposit->fetch_assoc()['amount'];

        $pending_referral_bonus += ($amount * 10)/100;
    }
}

/*
|--------------------------------------------------------------------------
| Total Withdrawals
|--------------------------------------------------------------------------
*/

$total_withdrawal = 0;

$withdrawal = $conn->prepare("
    SELECT COALESCE(SUM(amount),0) AS total
    FROM withdrawals
    WHERE user_uid = ?
    AND status = 'approved'
");

$withdrawal->bind_param("s", $user_uid);

$withdrawal->execute();

$total_withdrawal = (float)$withdrawal
    ->get_result()
    ->fetch_assoc()['total'];
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
      <div class="display-4 fw-bold my-2">
          $<?= number_format($wallet_balance,2) ?>
      </div>
    </div>

    <div class="pt-3 border-top gap-2 border-white border-opacity-10 small d-flex justify-content-end">
      <a href="/withdraw-funds" class="btn btn-light text-success fw-bold py-2 col-lg-12 rounded-3 shadow-sm d-flex align-items-center justify-content-center">
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
                      <span class="small d-block opacity-75">Total Investment</span>
                      <span class="fs-4 fw-bold">$<?= number_format($total_investment,2) ?></span>
                    </div>

                    <!-- Total Bonus Box -->
                    <div class="p-3 bg-light rounded-4 border">
                      <span class="small d-block text-muted">Total Profit</span>
                      <span class="fs-5 fw-bold text-dark">$<?= number_format($total_profit,2) ?></span>
                    </div>
                    <!-- Total Deposit Box -->
                    <!-- Total Withdrawal Box -->
                    <div class="p-3 bg-light rounded-4 border">
                        <span class="small d-block text-muted">
                            Total Withdrawals
                        </span>

                        <span class="fs-5 fw-bold text-success">
                            $<?= number_format($total_withdrawal, 2) ?>
                        </span>
                    </div>
                    <!-- Total Deposit Box -->
                    <div class="p-3 bg-light rounded-4 border">
                      <span class="small d-block text-muted">Pending Referral Bonus</span>
                      <span class="fs-5 fw-bold text-dark">$<?= number_format($pending_referral_bonus,2) ?></span>
                      <div><small>when referral made transaction you get 10% bonus automatically in your wallet</small></div>
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