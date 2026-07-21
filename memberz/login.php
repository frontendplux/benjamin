<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script type="text/javascript" src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit2"></script>
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

<!-- Required mount point for the Google Translate widget (hidden — we drive it via changeLang()) -->
<div id="google_translate_element" style="display:none"></div>

<div class="bg-light py-5 px-3">
  <div class="w-100 mx-auto" style="max-width: 550px;">

    <!-- Login Card Wrapper -->
    <div class="card border-0 bg-white p-4 p-md-5 rounded-4">
      <div class="card-body p-0">

        <!-- Header text -->
        <div class="mb-4">
          <h2 class="fw-bold text-dark h3 mb-1">Welcome Back</h2>
          <p class="text-muted small">Access your dashboard portfolio configuration parameters.</p>
        </div>

        <!-- Form Elements -->
        <form autocomplete="off" id="loginForm">

          <!-- Email Field -->
          <div class="mb-3">
            <label for="loginEmail" class="form-label fw-semibold text-dark small">Email Address</label>
            <div class="input-group">
              <span class="input-group-text bg-light border-light-subtle text-muted">
                <i class="bi bi-envelope"></i>
              </span>
              <input type="email" class="form-control bg-white border-light-subtle py-2 text-muted" id="loginEmail" placeholder="name@example.com" required>
            </div>
          </div>

          <!-- Password Field -->
        <div class="mb-3">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <label for="loginPassword" class="form-label fw-semibold text-dark small mb-0">Password</label>
                <a href="#" class="text-success text-decoration-none small fw-medium">Forgot Password?</a>
            </div>
            <div class="input-group">
                <span class="input-group-text bg-light border-light-subtle text-muted">
                <i class="bi bi-lock"></i>
                </span>
                <input type="password" class="form-control bg-white border-light-subtle py-2 text-muted" id="loginPassword" placeholder="••••••••" required>
                <!-- Toggle Button Added Here -->
                <button class="btn btn-outline-light-subtle border-light-subtle bg-white text-muted" type="button" id="togglePassword">
                <i class="bi bi-eye" id="toggleIcon"></i>
                </button>
            </div>
        </div>

          <!-- Remember me checkbox selector -->
          <div class="form-check mb-4 mt-2">
            <input class="form-check-input text-success border-light-subtle" type="checkbox" id="rememberMe">
            <label class="form-check-label text-muted small" for="rememberMe">
              Keep my terminal authenticated for 30 days
            </label>
          </div>

          <!-- Action Button Submit -->
          <button type="submit" class="btn btn-success w-100 py-2 fw-medium shadow-sm mb-3">
            Secure Sign In <i class="bi bi-box-arrow-in-right ms-1"></i>
          </button>

        </form>

        <!-- Divider Interface element -->
        <div class="position-relative my-4 text-center">
          <hr class="text-muted opacity-25 m-0">
          <span class="position-absolute top-50 start-50 translate-middle bg-white px-3 small text-muted">New Here?</span>
        </div>

        <!-- Secondary Navigation redirect to Register page -->
        <div class="text-center">
          <p class="text-muted small mb-0">
            Don't have an investment profile yet?
            <a href="/register" class="text-success fw-bold text-decoration-none ms-1">Create Account</a>
          </p>
        </div>

      </div>
    </div>

    <!-- Security Integrity Footnote -->
    <div class="text-center mt-4">
      <p class="text-muted" style="font-size: 11px;">
        <i class="bi bi-shield-lock-fill text-success me-1"></i> 256-Bit Encrypted Secure Session Node.
      </p>
    </div>

  </div>
</div>
<style>
    iframe{display: none;}
</style>
<script>
const LOGIN_ENDPOINT = <?= json_encode($company_info['main-server']) ?>;
document.getElementById('togglePassword').addEventListener('click', function () {
  const passwordInput = document.getElementById('loginPassword');
  const toggleIcon = document.getElementById('toggleIcon');
  
  // Toggle the type attribute
  if (passwordInput.type === 'password') {
    passwordInput.type = 'text';
    // Swap icon to eye-slash
    toggleIcon.classList.remove('bi-eye');
    toggleIcon.classList.add('bi-eye-slash');
  } else {
    passwordInput.type = 'password';
    // Swap icon back to eye
    toggleIcon.classList.remove('bi-eye-slash');
    toggleIcon.classList.add('bi-eye');
  }
});
document.addEventListener("DOMContentLoaded", () => {

    const form = document.getElementById("loginForm");

    form.addEventListener("submit", async function (e) {
        e.preventDefault();

        const email = document.getElementById("loginEmail").value.trim();
        const password = document.getElementById("loginPassword").value;
        const rememberMe = document.getElementById("rememberMe").checked;

        const submitBtn = form.querySelector("button[type='submit']");
        const originalText = submitBtn.innerHTML;

        // Validation
        if (!email) {
            Swal.fire("Validation Error", "Email is required.", "warning");
            return;
        }

        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailPattern.test(email)) {
            Swal.fire("Validation Error", "Enter a valid email address.", "warning");
            return;
        }

        if (!password) {
            Swal.fire("Validation Error", "Password is required.", "warning");
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <span class="spinner-border spinner-border-sm me-2"></span>
            Signing In...
        `;

        try {

            const response = await fetch(LOGIN_ENDPOINT, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    action: "/login",
                    email: email,
                    password: password,
                    remember: rememberMe
                })
            });

            if (!response.ok) {
                throw new Error("Server error");
            }

            const res = await response.json();

            if (res.success) {

                Swal.fire({
                    icon: "success",
                    title: "Login Successful",
                    text: res.message || "Welcome back!",
                    confirmButtonColor: "#198754",
                    timer: 1500,
                    showConfirmButton: false
                });

                if (rememberMe) {
                    localStorage.setItem("remember_user", email);
                } else {
                    localStorage.removeItem("remember_user");
                }

                if (res.data && res.data.redirect) {
                    setTimeout(() => {
                        window.location.href = res.data.redirect;
                    }, 1200);
                }

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Login Failed",
                    text: res.message || "Invalid login credentials.",
                    confirmButtonColor: "#dc3545"
                });

            }

        } catch (error) {

            console.error(error);

            Swal.fire({
                icon: "error",
                title: "Network Error",
                text: "Unable to connect to server. Please try again.",
                confirmButtonColor: "#dc3545"
            });

        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }

    });

    const savedEmail = localStorage.getItem("remember_user");
    if (savedEmail) {
        document.getElementById("loginEmail").value = savedEmail;
        document.getElementById("rememberMe").checked = true;
    }

});
</script>
</body>
</html>

<style>
    #goog-gt-vt{display: none;}
</style>