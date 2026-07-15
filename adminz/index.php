<!DOCTYPE html>
<html lang="en">
<head>
    <?php
    ?>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>System Initialization | Admin Panel</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
  </style>
</head>
<body>
<header class="navbar navbar-expand-lg navbar-dark bg-success border-bottom border-secondary border-opacity-25 py-3 px-4 shadow-sm">
  <div class="container">
    <a class="navbar-brand d-flex align-items-center gap-2 fw-bold text-success" href="/">
        <img src="<?= $company_info['logo2'] ?>" style="width:70px" alt="">
    </a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-end" id="topNavbar">
      <div class="d-flex align-items-center gap-3">
        <div class="text-end d-none d-md-block">
          <small class="text-muted d-block font-monospace" style="font-size: 10px;">ENVIRONMENT STATUS</small>
          <span class="text-warning small fw-bold font-monospace"><i class="bi bi-circle-fill me-1" style="font-size: 8px;"></i> INITIALIZATION PENDING</span>
        </div>
      </div>
    </div>
  </div>
</header>

<div class="container bg-light p-3 p-md-5 h-100 mt-2 rounded-4" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  

  <div class="row g-4 justify-content-center">
    
    <div class="col-lg-8">
  <div class="d-flex align-items-center gap-2 small mb-4 text-muted">
    <i class="bi bi-gear-fill text-success"></i> 
    <span>System Initialization</span> 
    <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
    <span class="text-success fw-bold">Admin Installation Wizard</span>
  </div>
      <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
        <div class="mb-4 pb-2 border-bottom border-light-subtle">
          <h5 class="fw-bold text-dark mb-1">
            <i class="bi bi-shield-lock-fill text-success me-2"></i>Configure Master Admin Account
          </h5>
          <p class="text-muted mb-0 small">
            Establish the root administrative profile. These credentials control global platform properties, accounting matrix protocols, and system routing paths.
          </p>
        </div>

        <form id="installationForm">
          <div class="row g-3">
            
            <div class="col-12">
              <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Administrative Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted"><i class="bi bi-envelope"></i></span>
                <input type="email" class="form-control bg-light border-light-subtle py-2.5 text-dark font-monospace" name="admin_email" placeholder="admin@platform.domain" required>
              </div>
              <span class="text-muted d-block mt-1 font-monospace" style="font-size: 10px;">Used as the primary administrative login identifier.</span>
            </div>

            <div class="col-md-6">
              <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Master Password</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted"><i class="bi bi-key"></i></span>
                <input type="password" class="form-control bg-light border-light-subtle py-2.5 text-dark font-monospace" name="admin_password" placeholder="Create strong password" required>
              </div>
            </div>

            <div class="col-md-6">
              <label class="small text-muted mb-1 font-monospace" style="font-size: 11px;">Confirm Password</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted"><i class="bi bi-key-fill"></i></span>
                <input type="password" class="form-control bg-light border-light-subtle py-2.5 text-dark font-monospace" name="admin_password_confirm" placeholder="Repeat master password" required>
              </div>
            </div>

            <div class="col-12 mt-4">
              <div class="p-3 bg-light bg-opacity-50 border border-light-subtle rounded-3">
                <label class="small text-dark fw-bold mb-1 font-monospace" style="font-size: 11px;">
                  <i class="bi bi-safe2 text-success me-1"></i>Set App Security Recovery Code
                </label>
                <p class="text-muted small mb-2" style="font-size: 11.5px; line-height: 1.4;">
                  This security code serves as an emergency bypass. If you forget your admin email or password, this app code can be used to authenticate and reset the master admin profile. Keep this code secret.
                </p>
                
                <div class="input-group">
                  <span class="input-group-text bg-light border-light-subtle font-monospace text-muted"><i class="bi bi-shield-shaded"></i></span>
                  <input type="text" minlength="8" maxlength="32" class="form-control bg-light border-light-subtle py-2.5 text-dark font-monospace fw-bold" name="app_security_code" placeholder="Create a unique App Code (e.g., APP-983x-ZP92)" required>
                </div>
                <span class="text-muted d-block mt-1 font-monospace" style="font-size: 10px;">Minimum 8 alphanumeric characters recommended.</span>
              </div>
            </div>

            <div class="col-12 mt-4">
              <div class="form-check">
                <input class="form-check-input text-success border-light-subtle bg-light" type="checkbox" id="agree_terms" required>
                <label class="form-check-label text-secondary small" for="agree_terms" style="line-height: 1.4;">
                  I understand that losing both my password and my App Security Code will lock me out of system configuration permanently.
                </label>
              </div>
            </div>

            <button type="submit" id="installBtn" class="btn btn-success w-100 py-3 rounded-3 fw-bold shadow-sm">
                <span class="btn-text">
                    Complete Installation & Initialize
                    <i class="bi bi-cpu-fill ms-1"></i>
                </span>
            </button>

          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
const form = document.getElementById("installationForm");
const btn = document.getElementById("installBtn");

form.addEventListener("submit", async function(e){

    e.preventDefault();

    const password = form.admin_password.value.trim();
    const confirmPassword = form.admin_password_confirm.value.trim();

    if(password !== confirmPassword){

        Swal.fire({
            icon:"error",
            title:"Password Mismatch",
            text:"The passwords you entered do not match."
        });

        return;
    }

    const formData = new FormData(form);
formData.append("action", "/admin/installation");

    btn.disabled = true;
    btn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Initializing System...
    `;

    try{

        const response = await fetch("<?= $company_info['admin-server'] ?>",{
            method:"POST",
            body:formData
        });

        const result = await response.json();

        if(result.success){

            await Swal.fire({
                icon:"success",
                title:"Installation Complete",
                text:result.message,
                confirmButtonColor:"#198754"
            });

            if(result.redirect){
                window.location.href = result.redirect;
            }

        }else{

            Swal.fire({
                icon:"error",
                title:"Installation Failed",
                text:result.message
            });

        }

    }catch(error){

        Swal.fire({
            icon:"error",
            title:"Server Error",
            text:"Unable to connect to the installation server."
        });

    }finally{

        btn.disabled = false;

        btn.innerHTML = `
            <span class="btn-text">
                Complete Installation & Initialize
                <i class="bi bi-cpu-fill ms-1"></i>
            </span>
        `;

    }

});
</script>
</body>
</html>