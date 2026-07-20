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

$duration = htmlspecialchars(
    $depositData['duration_value']." ".ucfirst($depositData['duration_unit'])
);

$min_limit = number_format($depositData['min_limit'],2);

$max_limit = number_format($depositData['max_limit'],2);

$approval = ucfirst(htmlspecialchars($depositData['approval']));

$deposit_status = ucfirst(htmlspecialchars($depositData['deposit_status']));


$trc20_address = $wallet_address;



// Security check
if(
    $amount < $depositData['min_limit'] ||
    $amount > $depositData['max_limit']
){

    session_destroy();

    header("Location:/investment-plans");

    exit;

}



// Stop payment if already processed

if(
    in_array(
        $depositData['deposit_status'],
        [
            "approved",
            "rejected",
            "cancelled"
        ]
    )
){

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
        
        <!-- Center Action Card Area -->
        <div class="">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 text-center">
            
            <!-- Protocol Header Branding -->
            <div class="mb-4">
              <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 py-1.5 px-3 rounded-pill fs-8 fw-medium mb-2">
                TRON Network Protocol Enabled
              </span>
              <h5 class="fw-bold text-dark mb-1">Send USDT (TRC20)</h5>
              <p class="text-muted small mb-0">Initiate your external blockchain transaction matching your intended node sizing.</p>
            </div>

            <!-- Inbound Target Funding Callout Box -->
            <div class="p-3 bg-light bg-opacity-70 border border-light-subtle rounded-4 mb-4">


                <span class="text-muted font-monospace d-block mb-1"
                style="font-size:11px;text-transform:uppercase;">
                Deposit Amount
                </span>


                <span class="display-6 fw-bold text-dark font-monospace">

                $<?= $requested_amount ?>

                <span class="fs-4 text-secondary">
                <?= $coin_symbol ?>
                </span>

                </span>



                <div class="mt-4 text-start small text-secondary">


                <div class="d-flex justify-content-between py-2 border-bottom">
                <span>Deposit ID</span>

                <strong class="text-dark">
                <?= htmlspecialchars($deposit_uid) ?>
                </strong>

                </div>



                <div class="d-flex justify-content-between py-2 border-bottom">

                <span>Investment Plan</span>

                <strong class="text-success">
                <?= $package_name ?>
                </strong>

                </div>



                <div class="d-flex justify-content-between py-2 border-bottom">

                <span>ROI</span>

                <strong class="text-dark">
                <?= $roi ?>%
                </strong>

                </div>



                <div class="d-flex justify-content-between py-2 border-bottom">

                <span>Duration</span>

                <strong class="text-dark">
                <?= $duration ?>
                </strong>

                </div>




                <div class="d-flex justify-content-between py-2 border-bottom">

                <span>Investment Range</span>

                <strong class="text-dark">
                $<?= $min_limit ?> -
                $<?= $max_limit ?>
                </strong>

                </div>



                <div class="d-flex justify-content-between py-2 border-bottom">

                <span>Approval Type</span>

                <strong class="text-dark">
                <?= $approval ?>
                </strong>

                </div>



                <div class="d-flex justify-content-between py-2">

                <span>Status</span>

                <strong class="text-success">
                <?= $deposit_status ?>
                </strong>

                </div>


                </div>


                </div>

            <!-- Secured Copy Node Vector Component -->
            <div class="mb-4 text-start">
              <label class="small text-muted mb-1 font-monospace d-block text-center" style="font-size: 11px;">Official <?= $coin_symbol ?> <?= $network ?> Deposit Address</label>
              <div class="input-group">
                <input type="text" id="walletAddress" class="form-control bg-light border-light-subtle py-2.5 font-monospace text-center small text-dark fw-medium rounded-start-3" value="<?= htmlspecialchars($trc20_address) ?>" readonly>
                <button class="btn btn-success px-3 rounded-end-3" type="button" onclick="copyAddressText()">
                  <i class="bi bi-copy" id="copyIcon"></i>
                </button>
              </div>
            </div>

            <hr class="text-light-subtle my-4">

            <!-- PROOF OF PAYMENT SUBMISSION FORM -->
            <form id="update_payment" enctype="multipart/form-data" class="text-start">
              <input type="hidden" name="deposit_uid" value="<?= htmlspecialchars($deposit_uid) ?>">
              
              <h6 class="fw-bold text-dark mb-3">
                <i class="bi bi-shield-check text-success me-2"></i>Manual Verification
              </h6>
              
              <!-- Transaction Reference Input -->
              <div class="mb-3">
                <label for="transaction_ref" class="form-label small text-secondary fw-medium">Transaction Hash / Reference ID</label>
                <input type="text" class="form-control border-light-subtle rounded-3 py-2 small font-monospace" id="transaction_ref" name="transaction_ref" placeholder="e.g., 0x7a8e... or Tron Transaction ID" required>
              </div>

              <!-- Drag and Drop Image Upload Container -->
              <div class="mb-4">
                <label class="form-label small text-secondary fw-medium">Upload Proof of Payment (Screenshot)</label>
                <div id="drop-zone" class="border border-dashed border-secondary-subtle rounded-4 p-4 text-center bg-light bg-opacity-50 position-relative style-pointer" style="cursor: pointer;">
                  <input type="file" name="proof_image" id="file-input" accept="image/*" class="position-absolute top-0 start-0 w-100 h-100 opacity-0" style="cursor: pointer;" required>
                  <i class="bi bi-cloud-arrow-up text-secondary display-6 d-block mb-2"></i>
                  <span class="d-block small text-dark fw-medium mb-1" id="file-status-text">Drag & drop your screenshot here or click to browse</span>
                  <span class="text-muted font-monospace" style="font-size: 11px;">Supports: JPG, PNG, WEBP</span>
                </div>
              </div>

              <hr>
              
            <!-- Operational Flow Disclaimer Alert Block -->
            <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-start mb-4">
              <div class="d-flex gap-2">
                <i class="bi bi-exclamation-triangle text-warning fs-5 mt-1"></i>
                <div class="small text-secondary" style="line-height: 1.45;">
                  <strong>Important Network Notice:</strong> <?=  $depositData['wallet_description'] ?>
                </div>
              </div>
            </div>



<input type="hidden" 
name="amount" 
value="<?= $amount ?>">


<input type="hidden" 
name="wallet" 
value="<?= htmlspecialchars($coin_symbol) ?>">

<input type="hidden" name="action" value="/member/submit-deposit-proof">
<input type="hidden" 
name="network" 
value="<?= htmlspecialchars($network) ?>">
              <!-- Action Confirmation Button -->
             <button 
type="submit" 
id="submitPayment"
class="btn btn-success w-100 py-2 rounded-3 fw-bold shadow-sm">

<i class="bi bi-wallet2 me-2"></i>
Submit Payment For Review

</button>
            </form>

          </div>
        </div>

        <!-- Right Side Diagnostic Metric Sidebar Block -->

      </div>

    </div>
  </div>
</div>

<!-- CLIENT ACCELERATION INTERACTION SCRIPT -->
<script>
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

// Interactive file-drop UI enhancement
const dropZone = document.getElementById('drop-zone');
const fileInput = document.getElementById('file-input');
const statusText = document.getElementById('file-status-text');

fileInput.addEventListener('change', function(e) {
  if (this.files && this.files[0]) {
    statusText.innerText = "Selected: " + this.files[0].name;
    dropZone.classList.remove('border-secondary-subtle');
    dropZone.classList.add('border-success', 'bg-success', 'bg-opacity-10');
  }
});

['dragenter', 'dragover'].forEach(eventName => {
  dropZone.addEventListener(eventName, (e) => {
    e.preventDefault();
    dropZone.classList.add('border-success', 'bg-success', 'bg-opacity-10');
  }, false);
});

['dragleave', 'drop'].forEach(eventName => {
  dropZone.addEventListener(eventName, (e) => {
    e.preventDefault();
    if(!fileInput.files.length) {
      dropZone.classList.remove('border-success', 'bg-success', 'bg-opacity-10');
      dropZone.classList.add('border-secondary-subtle');
    }
  }, false);
});

document.getElementById("update_payment").addEventListener("submit", async function(e){
    e.preventDefault();
    const form = this;
    const button = document.getElementById("submitPayment");
    const formData = new FormData(form);
    button.disabled = true;
    button.innerHTML = `<span class="spinner-border spinner-border-sm"></span> Submitting...`;
    try{
        const response = await fetch("<?= $company_info['main-server'] ?>",{
        method:"POST",
        body:formData
    });
    const data = await response.json();
    button.disabled=false;
        button.innerHTML=`<i class="bi bi-wallet2 me-2"></i> Submit Payment For Review`;
        if(!data.success){
            Swal.fire({
                icon:"error",
                title:"Submission Failed",
                text:data.message
            });
            return;
        }
        Swal.fire({
            icon:"success",
            title:"Payment Submitted",
            text:"Your deposit is now under review"
        }).then(()=>{
            location.href="/investment-plans";
        });
    }catch(error){
        button.disabled=false;
        button.innerHTML=`
        <i class="bi bi-wallet2 me-2"></i> Submit Payment For Review`;
        Swal.fire({
            icon:"error",
            title:"Server Error",
            text:"Please try again"
        });
    }
});
</script>