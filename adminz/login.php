<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="shortcut icon" href="<?= $company_info['logo'] ?>" type="image/x-icon">
    <title><?= $company_info['title']; ?></title>
</head>
<body class="bg-light" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <!-- TOP SYSTEM BRAND HEADER -->
    <div class="bg-white border-bottom border-light-subtle p-3">
        <header class="container-fluid d-flex align-items-center gap-2">
            <a href="/" class="bi bi-chevron-left fs-5 text-dark text-decoration-none"></a> 
            <span class="text-secondary font-monospace small text-uppercase" style="font-size: 13px;"><?= htmlspecialchars($company_info['name']) ?></span>
        </header>
    </div>

    <!-- MAIN INTERACTIVE PORTAL HOLDER -->
    <div class="container my-5">
        <div class="w-100 mx-auto" style="max-width: 440px;">
            
            <!-- STATE MODULE 1: AUTHENTICATION LOGIN CARD -->
            <div id="loginViewState" class="card border-0 shadow-sm bg-white rounded-4 p-4">
                <div class="text-center mb-4">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-shield-lock-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Admin Panel Login</h5>
                    <p class="text-muted small mb-0">Provide operational node parameters to establish terminal access.</p>
                </div>

                <form id="loginForm" onsubmit="handleAdminLogin(event)">
                    <!-- Input Layer: Identifier -->
                    <div class="mb-3">
                        <label class="small text-dark fw-medium mb-1" for="adminEmail">
                            Administrator Email
                        </label>

                        <div class="input-group">
                            <span class="input-group-text bg-light border-light-subtle text-secondary">
                                <i class="bi bi-envelope-fill"></i>
                            </span>

                            <input
                                type="email"
                                id="adminEmail"
                                class="form-control border-light-subtle py-2 text-dark font-monospace"
                                placeholder="admin@example.com"
                                required
                            >
                        </div>
                    </div>

                    <!-- Input Layer: Secure Terminal PIN -->
                    <div class="mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <label class="small text-dark fw-medium mb-0" for="adminPin">Secure Access PIN</label>
                            <button type="button" class="btn btn-link p-0 text-success text-decoration-none small fw-medium" style="font-size: 12px;" onclick="toggleAuthenticationState('forgot')">
                                Forgot PIN?
                            </button>
                        </div>
                 <div class="input-group">
                    <span class="input-group-text bg-light border-light-subtle text-secondary">
                        <i class="bi bi-key-fill"></i>
                    </span>

                    <input
                        type="password"
                        id="adminPin"
                        class="form-control border-light-subtle py-2 text-dark font-monospace"
                        placeholder="••••••"
                        required
                    >

                    <button
                        class="input-group-text bg-light border-light-subtle text-secondary"
                        type="button"
                        onclick="togglePassword('adminPin', this)"
                    >
                        <i class="bi bi-eye"></i>
                    </button>
</div>
                        <div class="form-text text-secondary mt-1.5" style="font-size: 11px;">
                            Enter your unique 6-digit cryptographic identity node access value.
                        </div>
                    </div>

                    <!-- Action Pipeline Target Trigger -->
                    <button type="submit" class="btn btn-success w-100 py-2.5 rounded-3 fw-medium shadow-sm mt-2">
                        <i class="bi bi-unlock-fill me-2"></i> Authenticate Session
                    </button>
                </form>
            </div>


            <!-- STATE MODULE 2: RESET PIN VIA APP PASSWORD GATE -->
            <div id="forgotViewState" class="card border-0 shadow-sm bg-white rounded-4 p-4 d-none">
                <div class="text-center mb-4">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 56px; height: 56px;">
                        <i class="bi bi-patch-exclamation-fill fs-3"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-1">Identity Challenge Layer</h5>
                    <p class="text-muted small mb-0">To re-map security parameters, please provide your structural root application key.</p>
                </div>

                <form id="recoveryForm" onsubmit="handleParamRecovery(event)">
                    <!-- Input Layer: Secure Main System App Password Override -->
                    <div class="mb-4">
                        <label class="small text-dark fw-medium mb-1" for="appPassword">Root App Password Code</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light-subtle text-secondary"><i class="bi bi-terminal-box-fill"></i></span>
                            <input type="password" id="appPassword" class="form-control border-light-subtle py-2 text-dark font-monospace" placeholder="Enter root app password string" required>
                        </div>
                        <div class="form-text text-secondary mt-1.5" style="font-size: 11px; line-height: 1.4;">
                            <i class="bi bi-shield-fill-exclamation text-warning me-1"></i> Inputting the correct application override bypasses active hardware node lockdowns instantly.
                        </div>
                    </div>

                    <div class="mb-3">
    <label class="small text-dark fw-medium mb-1">
        New Password
    </label>

    <input
        type="password"
        id="newPassword"
        class="form-control"
        minlength="8"
        required
    >
</div>

<div class="mb-4">
    <label class="small text-dark fw-medium mb-1">
        Confirm Password
    </label>

    <input
        type="password"
        id="confirmPassword"
        class="form-control"
        minlength="8"
        required
    >
</div>

                    <!-- Actions Stack Matrix -->
                    <div class="d-flex flex-column gap-2">
                        <button type="submit" class="btn btn-warning text-dark w-100 py-2.5 rounded-3 fw-medium shadow-sm">
                            <i class="bi bi-arrow-repeat me-2"></i> Verify App Password & Reset PIN
                        </button>
                        <button type="button" class="btn btn-light border border-light-subtle w-100 py-2 small text-secondary rounded-3" onclick="toggleAuthenticationState('login')">
                            Cancel & Return to Login
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- VIEW TRANSITION & SUBMISSION ARCHITECTURE INTERACTIVE LOGIC -->
<script>
// document.addEventListener("DOMContentLoaded", function () {

//     const loginForm = document.getElementById("loginForm");
//     const recoveryForm = document.getElementById("recoveryForm");

//     if (loginForm) {
//         loginForm.addEventListener("submit", handleAdminLogin);
//     }

//     if (recoveryForm) {
//         recoveryForm.addEventListener("submit", handleParamRecovery);
//     }

// });


async function handleAdminLogin(event) {

    event.preventDefault();

   const emailInput = document.getElementById("adminEmail");
    const passwordInput = document.getElementById("adminPin");

    if (!emailInput || !passwordInput) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Login form not found."
        });
        return;
    }

    const email = emailInput.value.trim().toLowerCase();
    const password = passwordInput.value;

    if (email === "") {
        Swal.fire({
            icon: "warning",
            title: "Username Required",
            text: "Please enter your username."
        });
        emailInput.focus();
        return;
    }

    if (password === "") {
        Swal.fire({
            icon: "warning",
            title: "Password Required",
            text: "Please enter your password."
        });
        passwordInput.focus();
        return;
    }

    const loginButton = event.target.querySelector("button[type='submit']");

    loginButton.disabled = true;

    const originalText = loginButton.innerHTML;

    loginButton.innerHTML =
        '<span class="spinner-border spinner-border-sm me-2"></span>Authenticating...';

    Swal.fire({
        title: "Authenticating...",
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });

    try {
        
        const response = await fetch("<?= $company_info['admin-server'] ?>", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                action: "/admin/login",
                email: email,
                password: password
            })
        });

        const result = await response.json();
        // console.log(result);
        

        Swal.close();

        if (result.success) {
            Swal.fire({
                icon: "success",
                title: "Login Successful",
                text: result.message,
                timer: 1500,
                showConfirmButton: false
            }).then(() => {
                window.location.href = "/admin-dashboard";
            });

        } else {

            Swal.fire({
                icon: "error",
                title: "Login Failed",
                text: result.message
            });
        }
        loginButton.innerHTML=originalText;

    } catch (error) {

        Swal.close();

        Swal.fire({
            icon: "error",
            title: "Connection Error",
            text: error.message
        });
        loginButton.innerHTML=originalText
        console.error(error);

    } finally {

        loginButton.disabled = false;
        loginButton.innerHTML = originalText;
    }

}

async function handleParamRecovery(event){

    event.preventDefault();

    const appPassword = document.getElementById("appPassword").value.trim();
    const newPassword = document.getElementById("newPassword").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if(appPassword===""){
        Swal.fire({
            icon:"warning",
            title:"Required",
            text:"Enter your App Security Code."
        });
        return;
    }

    if(newPassword.length<8){

        Swal.fire({
            icon:"warning",
            title:"Weak Password",
            text:"Password must contain at least 8 characters."
        });

        return;
    }

    if(newPassword!==confirmPassword){

        Swal.fire({
            icon:"error",
            title:"Password Mismatch",
            text:"The passwords do not match."
        });

        return;
    }

    Swal.fire({
        title:"Resetting Password...",
        allowOutsideClick:false,
        didOpen:()=>{
            Swal.showLoading();
        }
    });

    try{

        const response = await fetch("<?= $company_info['admin-server'] ?>",{

            method:"POST",

            headers:{
                "Content-Type":"application/json"
            },

            body:JSON.stringify({

                action:"/admin/reset-password",

                app_password:appPassword,

                password:newPassword

            })

        });

        const result = await response.json();

        Swal.close();

        if(result.success){

            Swal.fire({

                icon:"success",

                title:"Password Updated",

                text:result.message

            }).then(()=>{

                toggleAuthenticationState("login");

            });

        }else{

            Swal.fire({

                icon:"error",

                title:"Reset Failed",

                text:result.message

            });

        }

    }catch(e){

        Swal.close();

        Swal.fire({

            icon:"error",

            title:"Connection Error",

            text:"Unable to connect to the server."

        });

    }

}

function toggleAuthenticationState(state) {

    const login = document.getElementById("loginViewState");
    const forgot = document.getElementById("forgotViewState");

    if (state === "forgot") {

        login.classList.add("d-none");
        forgot.classList.remove("d-none");

    } else {

        forgot.classList.add("d-none");
        login.classList.remove("d-none");

    }

}


function togglePassword(inputId, button) {

    const input = document.getElementById(inputId);

    if (!input) return;

    const icon = button.querySelector("i");

    if (input.type === "password") {

        input.type = "text";
        icon.classList.replace("bi-eye", "bi-eye-slash");

    } else {
        input.type = "password";
        icon.classList.replace("bi-eye-slash", "bi-eye");
    }

}
</script>
</body>
</html>