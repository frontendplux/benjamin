<?php 
include __DIR__."/header.php"; 
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

$count = $conn->query("
    SELECT COUNT(*) total
    FROM investment_plan
    WHERE status='active'
");

$total = $count->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

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
$wallet = $conn->prepare("
    SELECT
        id,
        network,
        coin_symbol,
        wallet_address
    FROM wallets
    WHERE is_active=1
    ORDER BY coin_symbol ASC
");

$wallet->execute();

$wallet = $wallet->get_result()->fetch_all(MYSQLI_ASSOC);

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

      <!-- Overview Header Section -->
      <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
          <div>
            <h5 class="fw-bold text-dark mb-1"><i class="bi bi-layers-half text-success me-2"></i>Investment Packages</h5>
          </div>
        </div>
      </div>

      <!-- Packages Structural Grid -->
      <div class="row g-4">
        <?php foreach ($investment as $invest): ?>
          <!-- Plan 1: Starter Growth Plan -->
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
                    <div class="mb-2">
                      <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Select Asset Source</label>
                      <select class="form-select bg-light border-light-subtle py-2 small font-monospace text-dark" name="wallet" required>
                        <option selected disabled value="">Select Wallet...</option>
                          <?php foreach ($wallet as $item): ?>
                          <option value="<?= $item['id'] ?>">
                              <?= htmlspecialchars($item['coin_symbol']) ?>
                              (<?= htmlspecialchars($item['network']) ?>)
                          </option>
                          <?php endforeach; ?>
                      </select>
                    </div>
                    
                    <div class="mb-3">
                      <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Staking Capital Allocation</label>
                      <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light border-light-subtle text-muted">$</span>
                        <input type="number" min="<?= $invest['min_limit'] ?>" max="<?= $invest['max_limit'] ?>" step="any" class="form-control bg-light border-light-subtle py-2 text-dark font-monospace" name="amount" placeholder="0.00" required>
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
      <?php if($totalPages>1): ?>

        <nav class="">
        <ul class="pagination justify-content-center">

        <?php if($page>1): ?>

        <li class="page-item">
        <a class="page-link" href="?page=<?= $page-1 ?>">
        Previous
        </a>
        </li>

        <?php endif ?>

        <?php for($i=1;$i<=$totalPages;$i++): ?>

        <li class="page-item <?= $i==$page?'active':'' ?>">
        <a class="page-link" href="?page=<?= $i ?>">
        <?= $i ?>
        </a>
        </li>

        <?php endfor ?>

        <?php if($page<$totalPages): ?>

        <li class="page-item">
        <a class="page-link" href="?page=<?= $page+1 ?>">
        Next
        </a>
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
        const wallet_id = form.querySelector("[name='wallet']").value;
        const amount = form.querySelector("[name='amount']").value;


        if(!wallet_id){

            Swal.fire({
                icon:"warning",
                text:"Please select wallet"
            });

            return;
        }


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

                    wallet_id:wallet_id,

                    amount:amount

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

                title:"Package Selected",

                text:"Proceed to deposit now?",

                showCancelButton:true,

                confirmButtonText:"Deposit Now"

            }).then(result=>{

                if(result.isConfirmed){

                    location.href="/deposit-payment";

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