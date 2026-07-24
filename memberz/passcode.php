<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>
<body>
<!-- Include Bootstrap 5, Bootstrap Icons, and SweetAlert2 in your <head> -->
<!-- 
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
-->

<div class="bg-light min-vh-100 d-flex align-items-center justify-content-center py-5 px-3">
  <div class="w-100" style="max-width: 460px;">
    
    <!-- Top Identity Branding Link Element -->
    <div class="text-center mb-4">
      <a class="d-inline-flex align-items-center fw-bold text-success fs-3 text-decoration-none" href="#">
        <i class="bi bi-graph-up-arrow me-2"></i>
        <span>Bright Part</span>
      </a>
    </div>

    <!-- Security PIN Verification Card -->
    <div class="card border-0 shadow-sm bg-white p-4 p-md-5 rounded-4">
      <div class="card-body p-0">
        
        <!-- Header Information -->
        <div class="mb-4 text-center">
          <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-3 mx-auto" style="width: 48px; height: 48px;">
            <i class="bi bi-shield-lock-fill fs-4"></i>
          </div>
          <h2 class="fw-bold text-dark h3 mb-1">Security Verification</h2>
          <p class="text-muted small">Enter the 6-digit dynamic authentication PIN transmitted to your profile terminal.</p>
        </div>

        <!-- Verification Input Grid Framework -->
        <form id="pinForm" onsubmit="event.preventDefault(); verifyPin();">
          
          <div class="d-flex justify-content-between gap-2 mb-4">
            <input type="text" class="form-control bg-light border-light-subtle text-center fw-bold fs-4 py-2 pin-field" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="form-control bg-light border-light-subtle text-center fw-bold fs-4 py-2 pin-field" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="form-control bg-light border-light-subtle text-center fw-bold fs-4 py-2 pin-field" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="form-control bg-light border-light-subtle text-center fw-bold fs-4 py-2 pin-field" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="form-control bg-light border-light-subtle text-center fw-bold fs-4 py-2 pin-field" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
            <input type="text" class="form-control bg-light border-light-subtle text-center fw-bold fs-4 py-2 pin-field" maxlength="1" pattern="[0-9]*" inputmode="numeric" required>
          </div>

          <!-- Action Submission Trigger -->
          <button type="submit" class="btn btn-success w-100 py-2.5 fw-medium shadow-sm mb-3">
            Authenticate Node <i class="bi bi-shield-check ms-1"></i>
          </button>

        </form>

        <hr class="text-muted opacity-25 my-4">

        <!-- Countdown Context & Code Resend Controls -->
        <div class="text-center">
          <p class="text-muted small mb-0">
            Didn't receive the verification string? 
            <a href="#" class="text-success fw-bold text-decoration-none ms-1" onclick="resendPin(event)">Resend Code</a>
          </p>
        </div>

      </div>
    </div>

    <!-- Cancellation Return Routing -->
    <div class="text-center mt-4">
      <a href="#" class="text-muted small text-decoration-none d-inline-flex align-items-center">
        <i class="bi bi-x-circle me-2"></i> Cancel Session Authentication
      </a>
    </div>

  </div>
</div>

<script>
// Auto-focus stepping script logic for standard sequential input cell flow
document.addEventListener("DOMContentLoaded", function () {
  const fields = document.querySelectorAll('.pin-field');
  
  fields.forEach((field, index) => {
    field.addEventListener('input', (e) => {
      // Allow only numbers
      field.value = field.value.replace(/[^0-9]/g, '');
      
      // Move forward if a digit is entered
      if (field.value.length === 1 && index < fields.length - 1) {
        fields[index + 1].focus();
      }
      
      // Auto-trigger verification if all fields are populated
      const allFilled = Array.from(fields).every(f => f.value.length === 1);
      if (allFilled) {
        verifyPin();
      }
    });

    field.addEventListener('keydown', (e) => {
      // Step backward on backspace if cell is blank
      if (e.key === 'Backspace' && field.value.length === 0 && index > 0) {
        fields[index - 1].focus();
      }
    });
  });
});

// Verification Execution using SweetAlert2
function verifyPin() {
  const fields = document.querySelectorAll('.pin-field');
  let pinValue = "";
  fields.forEach(f => pinValue += f.value);

  // Check if PIN is fully formed before processing
  if (pinValue.length < 6) return;

  Swal.fire({
    title: 'Authenticating...',
    text: 'Validating safety tokens against security mesh architecture.',
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
      
      // Simulate real-time server runtime authentication delay (1.5s)
      setTimeout(() => {
        Swal.fire({
          icon: 'success',
          title: 'Identity Confirmed',
          text: 'Secure dashboard session node authorized successfully.',
          confirmButtonColor: '#198754', // Matches Bootstrap's clean text-success green
          timer: 2500,
          timerProgressBar: true
        }).then(() => {
          // Redirect user node location parameters here
          // window.location.href = '/dashboard';
          console.log("Redirecting to dashboard framework portfolio...");
        });
      }, 1500);
    }
  });
}

// Resend PIN action configuration with SweetAlert2 notice trigger
function resendPin(e) {
  e.preventDefault();
  
  Swal.fire({
    icon: 'info',
    title: 'New Token Generated',
    text: 'A secondary 6-digit confirmation string has been sent to your email profile.',
    confirmButtonColor: '#198754'
  });
}
</script>