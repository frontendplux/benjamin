<?php
include __DIR__."/header.php";

$user_uid = $_SESSION['user_id'];

$getUser = $conn->prepare("
SELECT
uid,
first_name,
last_name,
email,
phone,
country
FROM users
WHERE uid=?
LIMIT 1
");

$getUser->bind_param("s",$user_uid);
$getUser->execute();

$userData = $getUser->get_result()->fetch_assoc();


$getKyc = $conn->prepare("
SELECT
is_verified,
verified_at
FROM kyc
WHERE user_uid=?
LIMIT 1
");

$getKyc->bind_param("s",$user_uid);
$getKyc->execute();

$kyc = $getKyc->get_result()->fetch_assoc();

$isVerified = !empty($kyc['is_verified']);
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
        <span class="text-success fw-bold">My Profile Settings</span>
      </div>

      <!-- Main Profile Core Grid System Layout -->
      <div class="row g-4">
        
        <!-- Left Side Primary Grid Segment: Account Details Update Form -->
        <div class="col-lg-7 col-xl-8">
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4">
            <div class="mb-4 pb-2 border-bottom border-light-subtle">
              <h5 class="fw-bold text-dark mb-1">Personal Profile Settings</h5>
              <p class="text-muted mb-0 small">Update your system communication preferences and core primary account metadata records.</p>
            </div>

            <!-- Profile Entry Submission Form Action Router -->
            <form id="update-profile-form">
              <div class="row g-3 mb-4">
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-1">First Name</label>
                  <input type="text" name="first_name" class="form-control bg-light bg-opacity-50 border-light-subtle rounded-3 p-2.5 text-dark small shadow-none" value="<?= htmlspecialchars($userData['first_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-1">Last Name</label>
                  <input type="text" name="last_name" class="form-control bg-light bg-opacity-50 border-light-subtle rounded-3 p-2.5 text-dark small shadow-none" value="<?= htmlspecialchars($userData['last_name'] ?? '') ?>" required>
                </div>
                <div class="col-md-6">
<label class="form-label small">
Phone Number
</label>

<input
type="text"
class="form-control"
name="phone"
value="<?= htmlspecialchars($userData['phone']) ?>">
</div>
<div class="col-md-6">
<label class="form-label small">
Country
</label>

<input
type="text"
class="form-control"
name="country"
value="<?= htmlspecialchars($userData['country']) ?>">
</div>
                <div class="col-12">
                  <label class="form-label text-muted small mb-1">Registered Email Address</label>
                  <input type="email" class="form-control bg-light border-light-subtle rounded-3 p-2.5 text-muted small font-monospace shadow-none" value="<?= htmlspecialchars($userData['email'] ?? '') ?>" readonly disabled>
                  <div class="form-text text-muted" style="font-size: 11px;"><i class="bi bi-info-circle me-1"></i>Email alterations require primary hardware passkey validation sequences.</div>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-1">Account Currency Standard</label>
                  <select name="currency" class="form-select bg-light bg-opacity-50 border-light-subtle rounded-3 p-2.5 text-dark small shadow-none">
                    <option value="USD" selected>USD ($) - United States Dollar</option>
                    <option value="EUR">EUR (€) - Eurozone Euro</option>
                    <option value="GBP">GBP (£) - British Pound</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-1">Interface Color Scheme</label>
                  <select name="theme" class="form-select bg-light bg-opacity-50 border-light-subtle rounded-3 p-2.5 text-dark small shadow-none">
                    <option value="light" <?= $theme !== 'dark' ? 'selected' : '' ?>>Standard Light</option>
                    <option value="dark" <?= $theme === 'dark' ? 'selected' : '' ?>>Institutional Dark</option>
                  </select>
                </div>
              </div>

              <!-- Form Post Processing Button Trigger -->
              <div class="d-flex justify-content-end pt-2 border-top border-light-subtle">
                <button type="submit" class="btn btn-success rounded-3 px-4 py-2.5 fw-medium small shadow-sm">
                  Save Changes <i class="bi bi-check2 ms-1"></i>
                </button>
              </div>
            </form>
          </div>

          <!-- Cryptographic Access Password Section Box -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
            <div class="mb-4 pb-2 border-bottom border-light-subtle">
              <h5 class="fw-bold text-dark mb-1">Change Account Password</h5>
              <p class="text-muted mb-0 small">Ensure your platform credentials use random character sequences to secure wallet access pools.</p>
            </div>

            <form id="change-password-form">
              <div class="row g-3 mb-4">
                <div class="col-12">
                  <label class="form-label text-muted small mb-1">Current Password</label>
                  <input type="password" name="old_password" class="form-control bg-light bg-opacity-50 border-light-subtle rounded-3 p-2.5 text-dark small shadow-none" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-1">New Password</label>
                  <input type="password" name="new_password" class="form-control bg-light bg-opacity-50 border-light-subtle rounded-3 p-2.5 text-dark small shadow-none" required>
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted small mb-1">Confirm New Password</label>
                  <input type="password" name="confirm_password" class="form-control bg-light bg-opacity-50 border-light-subtle rounded-3 p-2.5 text-dark small shadow-none" required>
                </div>
              </div>

              <div class="d-flex justify-content-end pt-2 border-top border-light-subtle">
                <button type="submit" class="btn btn-outline-secondary border-light-subtle text-dark rounded-3 px-4 py-2.5 fw-medium small shadow-sm">
                  Update Passkey Structure
                </button>
              </div>
            </form>
          </div>
        </div>

        <!-- Right Side Secondary Grid Segment: Quick Verification Status & 2FA Metrics Info cards -->
        <div class="col-lg-5 col-xl-4">
          
          <!-- Identity Verification Card Status Hub -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4 mb-4 text-center">
            <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 64px; height: 64px;">
              <i class="bi bi-patch-check-fill display-6"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">Identity Compliance Verified</h6>
            <span class="text-muted font-monospace small d-block mb-3" style="font-size: 11px;">Verified on: 2026-03-14</span>
            <div class="p-2 bg-light rounded-3 text-secondary small font-monospace fs-8 mb-0 border">
              Daily Asset Outflow Cap: $50,000.00
            </div>
          </div>

          <!-- Two Factor Security (2FA) Multi-layer Status Panel -->
          <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
              <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                <i class="bi bi-shield-lock text-warning fs-5"></i> Multi-Factor Security
              </h6>
              <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-10 font-monospace fs-8">Enabled</span>
            </div>
            <p class="text-secondary small mb-3">
              Dynamic verification keys protect critical interactions like withdrawal distributions and api-endpoint generation hooks.
            </p>
            <hr class="my-3 border-light-subtle">
            
            <div class="d-flex align-items-center justify-content-between font-monospace fs-7">
              <div class="d-flex align-items-center gap-2 text-dark">
                <i class="bi bi-phone text-muted fs-5"></i>
                <div>
                  <span class="d-block fw-medium">Google Authenticator</span>
                  <span class="text-muted fs-8">Token validation running active</span>
                </div>
              </div>
              <button class="btn btn-light btn-sm border text-danger rounded-3 fs-8 fw-medium">Disable</button>
            </div>
          </div>

        </div>

      </div>

    </div>
  </div>
</div>

<script>

document.getElementById("update-profile-form").addEventListener("submit",async function(e){

e.preventDefault();

const form=new FormData(this);

Swal.fire({
title:"Updating Profile",
allowOutsideClick:false,
didOpen:()=>{
Swal.showLoading();
}
});

const response=await fetch(
"<?= $company_info['main-server']?>",
{
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({

action:"/member/profile/update",

first_name:form.get("first_name"),

last_name:form.get("last_name"),

phone:form.get("phone"),

country:form.get("country"),

currency:form.get("currency"),

theme:form.get("theme")

})
}
);

const data=await response.json();

if(data.success){

Swal.fire({
icon:"success",
title:"Updated",
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

});

</script>


<script>

document.getElementById("change-password-form").addEventListener("submit",async function(e){

e.preventDefault();

const form=new FormData(this);

if(form.get("new_password")!==form.get("confirm_password")){

Swal.fire({
icon:"warning",
title:"Password Mismatch",
text:"New passwords do not match."
});

return;

}

const response=await fetch(
"<?= $company_info['main-server']?>",
{
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({

action:"/member/change-password",

old_password:form.get("old_password"),

new_password:form.get("new_password")

})
}
);

const data=await response.json();

if(data.success){

Swal.fire({
icon:"success",
title:"Success",
text:data.message
});

this.reset();

}else{

Swal.fire({
icon:"error",
title:"Failed",
text:data.message
});

}

});

</script>