<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>
<!-- Smartsupp Live Chat script -->
<script type="text/javascript">
var _smartsupp = _smartsupp || {};
_smartsupp.key = '4e7f83c13584d8a5fbc3b3b250cb690d90b0a20b';
window.smartsupp||(function(d) {
  var s,c,o=smartsupp=function(){ o..push(arguments)};o.=[];
  s=d.getElementsByTagName('script')[0];c=d.createElement('script');
  c.type='text/javascript';c.charset='utf-8';c.async=true;
  c.src='https://www.smartsuppchat.com/loader.js?';s.parentNode.insertBefore(c,s);
})(document);
</script>
<noscript>Powered by <a href="https://www.smartsupp.com" target="_blank">Smartsupp</a></noscript>
<script src="/memberz/script.js"></script>
<link rel="shortcut icon" href="<?= $company_info['logo'] ?>" type="image/x-icon">
<title><?= $company_info['title'] ?></title>
</head>

<style>
    #goog-gt-vt{display: none;}
</style>
<body>
<header class="bg-white sticky-top border-bottom shadow-sm">
    <div class="container d-flex justify-content-between py-3 align-items-center">
        <div class="text-center">
            <a class="d-inline-flex align-items-center fw-bold text-success fs-3 text-decoration-none" href="/">
                <img src="<?= htmlspecialchars($company_info['logo']) ?>" style="width:60px" alt="<?= htmlspecialchars($company_info['name'] )?>">
            </a>
        </div>
        
        <!-- Language Selector Dropdown -->
        <div class="dropdown">
            <button id="selectedLanguage" class="btn btn-outline-secondary dropdown-toggle btn-sm px-3 py-2 fw-semibold" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-globe me-1"></i> Language
            </button>
            <ul style="max-height: 300px; overflow: auto;" class="dropdown-menu dropdown-menu-end shadow border-light-subtle">
                <?php foreach ($languages as $language): ?>
                    <li>
                       <a class="dropdown-item small" href="#"
                        onclick="changeLang('<?= htmlspecialchars($language[0], ENT_QUOTES) ?>', '<?= htmlspecialchars($language[1], ENT_QUOTES) ?>'); return false;">
                            <?= htmlspecialchars($language[1]) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
</header>


<div id="google_translate_element" style="display:none;"></div>
<div class="bg-light d-flex align-items-center justify-content-center py-3 px-3">
  <div class="w-100" style="max-width: 600px;">
    <!-- Registration Card Wrapper -->
    <div class="card border-0 shadow-sm bg-white p-4 p-md-5 rounded-4">
      <div class="card-body p-0">
        
        <!-- Header Summary Text -->
        <div class="mb-4">
          <h2 class="fw-bold text-dark h3 mb-1">Create Account</h2>
          <p class="text-muted small">Establish your verified profile credentials and explore global yield paths.</p>
        </div>

        <!-- Registration Input Form Grid -->
        <form autocomplete="off">
          
          <div class="row g-3">
            <!-- Full Name Input -->
            <div class="col-sm-6">
              <label for="registerFirstName" class="form-label fw-semibold text-dark small mb-1">First Name</label>
              <input type="text" class="form-control bg-light border-light-subtle py-2 text-muted" id="registerFirstName" placeholder="John" required>
            </div>
            
            <div class="col-sm-6">
              <label for="registerLastName" class="form-label fw-semibold text-dark small mb-1">Last Name</label>
              <input type="text" class="form-control bg-light border-light-subtle py-2 text-muted" id="registerLastName" placeholder="Doe" required>
            </div>

            <!-- Email Address Input -->
            <div class="col-12">
              <label for="registerEmail" class="form-label fw-semibold text-dark small mb-1">Email Address</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted">
                  <i class="bi bi-envelope"></i>
                </span>
                <input type="email" class="form-control bg-white border-light-subtle py-2 text-muted" id="registerEmail" placeholder="name@example.com" required>
              </div>
            </div>
            <!-- phone number Input -->
            <div class="col-12">
              <label for="registerPhone" class="form-label fw-semibold text-dark small mb-1">Phone Number</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted">
                  <i class="bi bi-telephone"></i>
                </span>
                <input type="tel" class="form-control bg-white border-light-subtle py-2 text-muted" id="registerPhone" placeholder="+1-234-567-8900" required>
              </div>
            </div>

             <!-- phone number Input -->
            <div class="col-12"> 
              <label for="registerPhone" class="form-label fw-semibold text-dark small mb-1">country</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted">
                  <i class="bi bi-geo-alt"></i>
                </span>
                 <select name="countryCode" id="countryCode" class="form-select bg-white border-light-subtle py-2 text-muted" required>
                  <option selected disabled>Select Country</option>
                  <?php foreach($country as $code => $name): ?>
                    <option value="<?= $code ?>"><?= $name ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
            </div>

           <div class="col-12 mb-3">
              <label for="registerPassword" class="form-label fw-semibold text-dark small mb-1">Password</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted">
                  <i class="bi bi-lock"></i>
                </span>
                <input type="password" class="form-control bg-white border-light-subtle py-2 text-muted" id="registerPassword" placeholder="Minimum 8 characters" required>
                <button class="btn btn-outline-light-subtle border-light-subtle bg-white text-muted" type="button" id="toggleRegisterPassword">
                  <i class="bi bi-eye" id="toggleRegisterIcon"></i>
                </button>
              </div>
            </div>

            <div class="col-12 mb-3">
              <label for="confirmPassword" class="form-label fw-semibold text-dark small mb-1">Confirm Password</label>
              <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted">
                  <i class="bi bi-shield-lock"></i>
                </span>
                <input type="password" class="form-control bg-white border-light-subtle py-2 text-muted" id="confirmPassword" placeholder="Repeat password security string" required>
                <button class="btn btn-outline-light-subtle border-light-subtle bg-white text-muted" type="button" id="toggleConfirmPassword">
                  <i class="bi bi-eye" id="toggleConfirmIcon"></i>
                </button>
              </div>
            </div>
          </div>

          <!-- Legal Terms & Disclosures Agreement Checkbox -->
          <div class="form-check my-4">
            <input class="form-check-input text-success border-light-subtle" type="checkbox" value="" id="agreeTerms" required>
            <label class="form-check-label text-muted small" for="agreeTerms">
              I acknowledge the platform's <a href="#" class="text-success text-decoration-none fw-medium">Risk Disclosures</a> and agree to the <a href="#" class="text-success text-decoration-none fw-medium">Terms of Service</a>.
            </label>
          </div>

          <!-- Action Button Submit -->
          <button type="submit" class="btn btn-success w-100 py-2.5 fw-medium shadow-sm mb-3">
            Open My Portfolio <i class="bi bi-person-plus-fill ms-1"></i>
          </button>

        </form>

        <!-- Dynamic Redirection Separator -->
        <div class="position-relative my-4 text-center">
          <hr class="text-muted opacity-25 m-0">
          <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">Already Registered?</span>
        </div>

        <!-- Navigation back to Login Layout -->
        <div class="text-center">
          <p class="text-muted small mb-0">
            Have an existing investment node? 
            <a href="/login" class="text-success fw-bold text-decoration-none ms-1">Sign In Here</a>
          </p>
        </div>

      </div>
    </div>

    <!-- Security Session Label Element -->
    <div class="text-center mt-4">
      <p class="text-muted" style="font-size: 11px;">
        <i class="bi bi-shield-fill-check text-success me-1"></i> All transactions and personal parameters remain architecture encrypted.
      </p>
    </div>

  </div>
</div>
<style>
  iframe {
    display: none !important;
  }
</style>
<script>

// Helper function to handle password visibility toggling
function setupPasswordToggle(buttonId, inputId, iconId) {
  const toggleButton = document.getElementById(buttonId);
  const passwordInput = document.getElementById(inputId);
  const toggleIcon = document.getElementById(iconId);

  if (toggleButton && passwordInput && toggleIcon) {
    toggleButton.addEventListener('click', function () {
      if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('bi-eye');
        toggleIcon.classList.add('bi-eye-slash');
      } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('bi-eye-slash');
        toggleIcon.classList.add('bi-eye');
      }
    });
  }
}

// Initialize the toggles for both fields
setupPasswordToggle('toggleRegisterPassword', 'registerPassword', 'toggleRegisterIcon');
setupPasswordToggle('toggleConfirmPassword', 'confirmPassword', 'toggleConfirmIcon');

const form = document.querySelector("form");

form.addEventListener("submit", async function (e) {
    e.preventDefault();

    const firstName = document.getElementById("registerFirstName").value.trim();
    const lastName = document.getElementById("registerLastName").value.trim();
    const email = document.getElementById("registerEmail").value.trim();
    const phone = document.getElementById("registerPhone").value.trim();
  const country = document.getElementById("countryCode").value;
    const password = document.getElementById("registerPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;
    const agreeTerms = document.getElementById("agreeTerms").checked;

    const submitBtn = form.querySelector('button[type="submit"]');
    const btnText = submitBtn.innerHTML;

    // Validation
    if (!firstName) {
        Swal.fire("Validation Error", "First name is required.", "warning");
        return;
    }

    if (!lastName) {
        Swal.fire("Validation Error", "Last name is required.", "warning");
        return;
    }

    if (!email) {
        Swal.fire("Validation Error", "Email address is required.", "warning");
        return;
    }

    const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailPattern.test(email)) {
        Swal.fire("Validation Error", "Please enter a valid email address.", "warning");
        return;
    }

    if (!phone) {
    Swal.fire("Validation Error", "Phone number is required.", "warning");
    return;
}

const phonePattern = /^[0-9+\-\s()]{7,20}$/;

if (!phonePattern.test(phone)) {
    Swal.fire("Validation Error", "Please enter a valid phone number.", "warning");
    return;
}

if (!country || country === "Select Country") {
    Swal.fire("Validation Error", "Please select your country.", "warning");
    return;
}

    if (password.length < 8) {
        Swal.fire("Validation Error", "Password must be at least 8 characters.", "warning");
        return;
    }

    if (password !== confirmPassword) {
        Swal.fire("Validation Error", "Passwords do not match.", "warning");
        return;
    }

    if (!agreeTerms) {
        Swal.fire("Validation Error", "You must agree to the Terms of Service.", "warning");
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = `
        <span class="spinner-border spinner-border-sm me-2"></span>
        Creating Account...
    `;

    try {

        const response = await fetch("<?= $company_info['main-server'] ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
           body: JSON.stringify({
            action: "/register",
            firstname: firstName,
            lastname: lastName,
            email: email,
            phone: phone,
            country: country,
            password: password,
            confirm_password: confirmPassword,
            referral_code: new URLSearchParams(window.location.search).get("ref") || null
        })
        });

        const res = await response.json();

        if (res.success) {

            await Swal.fire({
                icon: "success",
                title: "Registration Successful",
                text: res.message || "Your account has been created successfully.",
                confirmButtonColor: "#198754"
            });

            if (res.data && res.data.redirect) {
                window.location.href = res.data.redirect;
            }

        } else {

            Swal.fire({
                icon: "error",
                title: "Registration Failed",
                text: res.message || "Unable to register your account.",
                confirmButtonColor: "#dc3545"
            });

        }

    } catch (error) {

        console.error(error);

        Swal.fire({
            icon: "error",
            title: "Network Error",
            text: "Unable to connect to the server. Please try again.",
            confirmButtonColor: "#dc3545"
        });

    } finally {

        submitBtn.disabled = false;
        submitBtn.innerHTML = btnText;

    }
});
</script>


