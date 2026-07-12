<?php 
include __DIR__."/header.php"; 

// Check current connection state array variable if set in core DB
$wallet = $conn->prepare("
SELECT
    wallet_address,
    wallet_network
FROM user_wallet
WHERE user_uid = ?
LIMIT 1
");

$wallet->bind_param("s",$user['uid']);
$wallet->execute();

$result = $wallet->get_result();

$walletData = $result->fetch_assoc() ?? [];

$is_connected = !empty($walletData['wallet_address']);

$linked_address = $walletData['wallet_address'] ?? "";
$wallet_network = $walletData['wallet_network'] ?? "";
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN WALLET INTEGRATION CONTENT WINDOW -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Wallet Link</span>
      </div>

<?php if($is_connected): ?>

<div class="p-2 bg-white rounded-3 border mb-2">
    <strong>Network</strong><br>
    <?= htmlspecialchars($wallet_network) ?>
</div>

<div class="p-2 bg-white rounded-3 border font-monospace text-break mb-3">
  <strong>Wallet Address</strong><br>
    <?= htmlspecialchars($linked_address) ?>
</div>

<?php endif; ?>
      <!-- Core Operational Interface Split -->
      <div class="row g-4 justify-content-center">
        
        <!-- Left Column: Authentication Hub -->
        <div class="col-lg-7">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 text-center">
            
            <!-- Protocol Header Branding -->
            <div class="mb-4">
              <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 py-1.5 px-3 rounded-pill fs-8 fw-medium mb-2">
                Automated Deposit Tracking
              </span>
              <h5 class="fw-bold text-dark mb-1">Link Receiving Wallet</h5>
              <p class="text-muted small mb-0">Provide your personal decentralized wallet address. Our automated pipeline uses this parameter to track and auto-credit incoming transactions.</p>
            </div>

            <!-- Contextual Status Card Matrix -->
            <?php if (!$is_connected): ?>
              <!-- STATE A: Disconnected / Awaiting Manual Entry Form -->
              <form id="process_wallet_link" class="text-start mb-3">
                <div class="mb-3">
                  <label for="crypto_address" class="form-label small text-secondary fw-medium">Your Public Wallet Address (TRC20 / ERC20)</label>
                  <div class="input-group">
                    <span class="input-group-text bg-light border-light-subtle text-secondary"><i class="bi bi-wallet2"></i></span>
                    <input type="text" class="form-control border-light-subtle rounded-end-3 py-2.5 font-monospace small text-dark" id="crypto_address" name="crypto_address" placeholder="Paste your public receiving address here" required>
                  </div>
                  <div class="form-text text-muted" style="font-size: 11px;">
                    Ensure this matches the exact address you send funds from. Transactions will be credited automatically.
                  </div>
                </div>

                <div class="mb-3">
    <label class="form-label small fw-medium">
        Wallet Network
    </label>

    <select
        class="form-select"
        name="wallet_network"
        required>

        <option value="">Select Network</option>

        <option value="TRC20">USDT (TRC20)</option>

        <option value="ERC20">USDT (ERC20)</option>

        <option value="BEP20">USDT (BEP20)</option>

        <option value="BTC">Bitcoin</option>

        <option value="ETH">Ethereum</option>

    </select>
</div>

                <button type="submit" class="btn btn-success w-100 py-2.5 rounded-3 fw-bold shadow-sm">
                  <i class="bi bi-shield-plus me-2"></i> Save & Enable Auto-Deposits
                </button>
              </form>
            <?php else: ?>
              <!-- STATE B: Wallet Manually Tracked -->
              <div class="p-4 bg-success bg-opacity-10 border border-success border-opacity-10 rounded-4 mb-3">
                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 70px; height: 70px;">
                  <i class="bi bi-shield-check fs-3"></i>
                </div>
                <h6 class="fw-bold text-success mb-1">Active Monitoring Enabled</h6>
                <p class="text-muted small mb-2">Deposits sent from this node source auto-allocate into your active balances:</p>
                <p class="text-muted font-monospace small mb-0 p-2 bg-white rounded-3 border border-light-subtle text-truncate">
                  <?= $linked_address ?>
                </p>
              </div>

              <!-- Unlink/Change Session Call -->
              <button 
                id="remove_wallet"
                class="btn btn-outline-danger w-100 py-2 rounded-3 small mb-3 font-monospace">

                <i class="bi bi-trash me-1"></i>
                Remove Wallet Address

              </button>
            <?php endif; ?>

            <!-- Structural Security Bullet List -->
            <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3 text-start">
              <div class="d-flex gap-2.5">
                <i class="bi bi-info-circle-fill text-success fs-5 mt-n0.5"></i>
                <div class="small text-secondary" style="line-height: 1.45;">
                  <strong>Automated Syncing Protocols:</strong> Your public key address serves exclusively as a monitoring target index. This configuration never requests transaction authorization privileges, signature approvals, or direct private wallet interaction matrices.
                </div>
              </div>
            </div>

          </div>
        </div>

        <!-- Right Side Configuration Metadata -->
        <div class="col-lg-4">
          
          <!-- Supported Infrastructure Ecosystem -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-cpu text-success me-2"></i>Monitored Providers</h6>
            <div class="d-flex flex-wrap gap-2">
              <span class="badge bg-light text-secondary border border-light-subtle px-2.5 py-1.5 rounded-pill font-monospace" style="font-size: 11px;">MetaMask</span>
              <span class="badge bg-light text-secondary border border-light-subtle px-2.5 py-1.5 rounded-pill font-monospace" style="font-size: 11px;">Trust Wallet</span>
              <span class="badge bg-light text-secondary border border-light-subtle px-2.5 py-1.5 rounded-pill font-monospace" style="font-size: 11px;">Coinbase Wallet</span>
              <span class="badge bg-light text-secondary border border-light-subtle px-2.5 py-1.5 rounded-pill font-monospace" style="font-size: 11px;">Binance Pay</span>
            </div>
          </div>

          <!-- Network Environment Variables -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
            <h6 class="fw-bold text-dark mb-3"><i class="bi bi-globe text-success me-2">Tracking Parameters</i></h6>
            <div class="font-monospace text-secondary small" style="font-size: 12px;">
              <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
                <span>Scanner Engine:</span>
                <span class="text-dark fw-medium">RPC RPC-Node v4</span>
              </div>
              <div class="d-flex justify-content-between py-1.5 border-bottom border-light-subtle">
                <span>Validation Speed:</span>
                <span class="text-dark">Instant (~1 min)</span>
              </div>
              <div class="d-flex justify-content-between py-1.5">
                <span>Network Costs:</span>
                <span class="text-success fw-bold">0.00 (Zero Fee)</span>
              </div>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
</div>
<script>

document.getElementById("process_wallet_link")?.addEventListener("submit", async function(e){
    e.preventDefault();
    const address = document.getElementById("crypto_address").value.trim();
    const network = this.wallet_network.value;
    if(address.length < 10){
        Swal.fire({
            icon:"error",
            title:"Invalid Wallet",
            text:"Please enter a valid wallet address"
        });
        return;
    }
    if(!network){
        Swal.fire({
            icon:"warning",
            title:"Select Network",
            text:"Please select your wallet network"
        });
        return;
    }
    Swal.fire({
        title:"Saving Wallet",
        text:"Connecting wallet address...",
        allowOutsideClick:false,
        didOpen:()=>{
            Swal.showLoading();
        }
    });
    try{
        const response = await fetch("<?= $company_info['main-server'] ?>",{
            method:"POST",
            headers:{
                "Content-Type":"application/json"
            },
            body:JSON.stringify({
                action:"/member/wallet-link",
                wallet_address:address,

                wallet_network:network

            })

        });
        const data = await response.json();
        if(data.success){
            Swal.fire({
                icon:"success",
                title:"Wallet Connected",
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

            title:"Server Error",

            text:"Unable to connect wallet"

        });


        console.log(error);

    }


});


document.getElementById("remove_wallet")?.addEventListener("click", async function(){

    const confirm = await Swal.fire({

        icon:"warning",
        title:"Remove Wallet Address?",
        text:"Your wallet address will be removed but your investment funds will remain.",
        showCancelButton:true,
        confirmButtonText:"Yes, Remove",
        cancelButtonText:"Cancel"

    });


    if(!confirm.isConfirmed){
        return;
    }



    Swal.fire({

        title:"Removing Wallet",
        text:"Please wait...",
        allowOutsideClick:false,
        didOpen:()=>{
            Swal.showLoading();
        }

    });



    try{


        const response = await fetch("<?= $company_info['main-server'] ?>",{

            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },


            body:JSON.stringify({

                action:"/member/wallet-remove"

            })

        });



        const data = await response.json();



        if(data.success){


            Swal.fire({

                icon:"success",
                title:"Removed",
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
            title:"Server Error",
            text:"Unable to process request"

        });


    }


});
</script>