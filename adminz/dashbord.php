<?php  include __DIR__."/header.php"; ?>
<body class="bg-light" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <!-- TOP GLOBAL STICKY HEADER BLOCK -->
    <div class="bg-white sticky-top border-bottom border-light-subtle py-2">
        <header class="container d-flex justify-content-between align-items-center">
            
            <!-- Left Element: Operational Exit Trigger -->
            <div>
                <a href="/admin" class="btn btn-light btn-sm text-dark border border-light-subtle rounded-3 d-inline-flex align-items-center gap-1.5 py-1.5 px-2.5">
                    <i class="bi bi-arrow-left-short fs-5 lh-1"></i>
                    <span class="small fw-medium"><?=  $auth['data']['username']; ?></span>
                </a>
            </div>
            
            <!-- Right Element: Multi-Segment Operational Notification & Identity Dropdown Area -->
            <div class="d-flex align-items-center gap-2">
                
                <!-- Notification Element Anchor -->
                <div class="dropdown">
                    <button class="btn btn-light position-relative p-2 rounded-circle border-0 text-secondary" type="button" id="noticeBell" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill fs-5"></i>
                        <span class="position-absolute top-1 start-75 translate-middle badge rounded-circle bg-danger p-1" style="width: 8px; height: 8px;">
                            <span class="visually-hidden">Unread Alert Logs</span>
                        </span>
                    </button>
                    <!-- Notification Queue Matrix Dropdown -->
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 p-2 mt-2" aria-labelledby="noticeBell" style="width: 280px; font-size: 12px;">
                        <li class="dropdown-header text-dark fw-bold border-bottom border-light-subtle pb-2 mb-1">Active Event Stream</li>
                        <li><a class="dropdown-item rounded-2 py-2 text-wrap text-secondary" href="#"><span class="text-warning fw-bold">●</span> Node 04 requested outbound clearing clearance signature.</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 text-wrap text-secondary" href="#"><span class="text-success fw-bold">●</span> Security credential reset compiled safely.</a></li>
                    </ul>
                </div>

                <!-- Primary Identity Account Dropdown Element Holder -->
                <div class="dropdown">
                    <button class="btn btn-light d-flex align-items-center gap-2 border border-light-subtle rounded-pill py-1 ps-1 pe-2.5" type="button" id="adminAccountProfile" data-bs-toggle="dropdown" aria-expanded="false">
                        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center fw-bold font-monospace shadow-sm" style="width: 28px; height: 28px; font-size: 11px;">
                            <?= strtoupper($auth['data']['firstname'][0] . $auth['data']['lastname'][0]); ?>
                        </div>
                        <i class="bi bi-chevron-down text-secondary" style="font-size: 10px;"></i>
                    </button>
                    <!-- Action List -->
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm rounded-3 p-1.5 mt-2" aria-labelledby="adminAccountProfile" style="font-size: 13px;">
                        <li><a class="dropdown-item rounded-2 py-2 text-secondary" href="#"><i class="bi bi-gear-fill me-2"></i>Crypto Node Setup</a></li>
                        <li><a class="dropdown-item rounded-2 py-2 text-secondary" href="#"><i class="bi bi-shield-check me-2"></i>Security Matrices</a></li>
                        <li><hr class="dropdown-divider my-1 border-light-subtle"></li>
                        <li><a class="dropdown-item rounded-2 py-2 text-danger fw-medium" href="#" onclick="triggerLockdownSession()"><i class="bi bi-power me-2"></i>Terminate Token</a></li>
                    </ul>
                </div>

            </div>
        </header>
    </div>

    <!-- MAIN GRID MENU LAYOUT -->
    <main class="container my-4">
        
        <!-- Welcome Hub Segment -->
        <div class="mb-4">
            <h5 class="fw-bold text-dark mb-1">Administrative Terminal Core</h5>
        </div>

        <!-- Matrix Operations Grid -->
        <div class="row g-3">
            
            <!-- Card Module 1: Clearances & Settlements -->
            <div class="col-6 col-md-4 col-xl-3">
                <a href="/admin-deposit" class="card border-0 shadow-sm bg-white rounded-4 p-3.5 h-100 text-decoration-none transition-hover">
                    <div class="text-success mb-3">
                        <div class="bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-arrow-down-left-circle-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Deposit</h6>
                    <p class="text-secondary small font-monospace mb-0" style="font-size: 11px; line-height: 1.35;">Authorize outbound node pipelines.</p>
                </a>
            </div>
            <div class="col-6 col-md-4 col-xl-3">
                <a href="/admin/withdrawals" class="card border-0 shadow-sm bg-white rounded-4 p-3.5 h-100 text-decoration-none transition-hover">
                    <div class="text-success mb-3">
                        <div class="bg-success bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-arrow-up-right-circle-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Withdrawals</h6>
                    <p class="text-secondary small font-monospace mb-0" style="font-size: 11px; line-height: 1.35;">Authorize outbound node pipelines.</p>
                </a>
            </div>

            <!-- Card Module 2: User Index Metrics -->
            <div class="col-6 col-md-4 col-xl-3">
                <a href="/admin/users" class="card border-0 shadow-sm bg-white rounded-4 p-3.5 h-100 text-decoration-none transition-hover">
                    <div class="text-info mb-3">
                        <div class="bg-info bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-people-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">User Registers</h6>
                    <p class="text-secondary small font-monospace mb-0" style="font-size: 11px; line-height: 1.35;">Modify synchronized profiles.</p>
                </a>
            </div>

            <!-- Card Module 3: Share Distribution Allocation Pools -->
            <div class="col-6 col-md-4 col-xl-3">
                <a href="/admin/shares" class="card border-0 shadow-sm bg-white rounded-4 p-3.5 h-100 text-decoration-none transition-hover">
                    <div class="text-primary mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-pie-chart-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Pool Shares</h6>
                    <p class="text-secondary small font-monospace mb-0" style="font-size: 11px; line-height: 1.35;">Re-weight snapshot metrics.</p>
                </a>
            </div>

            <!-- Card Module 4: Affiliates & Override Layers -->
            <div class="col-6 col-md-4 col-xl-3">
                <a href="/admin/referrals" class="card border-0 shadow-sm bg-white rounded-4 p-3.5 h-100 text-decoration-none transition-hover">
                    <div class="text-warning mb-3">
                        <div class="bg-warning bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-diagram-3-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">Referral Hub</h6>
                    <p class="text-secondary small font-monospace mb-0" style="font-size: 11px; line-height: 1.35;">Map tree node dependencies.</p>
                </a>
            </div>

            <!-- Card Module 5: Diagnostic Server Health Logs -->
            <div class="col-6 col-md-4 col-xl-3">
                <a href="/admin/system-logs.php" class="card border-0 shadow-sm bg-white rounded-4 p-3.5 h-100 text-decoration-none transition-hover">
                    <div class="text-danger mb-3">
                        <div class="bg-danger bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-cpu-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">System Logs</h6>
                    <p class="text-secondary small font-monospace mb-0" style="font-size: 11px; line-height: 1.35;">Review crash & core dumps.</p>
                </a>
            </div>

            <!-- Card Module 6: App Parameter Setup Frameworks -->
            <div class="col-6 col-md-4 col-xl-3">
                <a href="/admin/pwa-config.php" class="card border-0 shadow-sm bg-white rounded-4 p-3.5 h-100 text-decoration-none transition-hover">
                    <div class="text-secondary mb-3">
                        <div class="bg-secondary bg-opacity-10 rounded-3 d-inline-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="bi bi-download fs-4"></i>
                        </div>
                    </div>
                    <h6 class="fw-bold text-dark mb-1">PWA Settings</h6>
                    <p class="text-secondary small font-monospace mb-0" style="font-size: 11px; line-height: 1.35;">Update service worker hashes.</p>
                </a>
            </div>

        </div>
    </main>

    <!-- INLINE HOVER INTERFACE ENHANCEMENTS -->
    <style>
        .transition-hover {
            transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
        }
        .transition-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 .4rem .8rem rgba(0,0,0,.06)!important;
        }
        /* Fix padding precision variant */
        .p-3\.5 {
            padding: 1.15rem !important;
        }
    </style>

    <!-- INTERACTIVE LOGOUT SESSION LOGIC CONTAINER -->
    <script>
        function triggerLockdownSession() {
            Swal.fire({
                title: 'Terminate Session Token?',
                text: "Dropping execution authentication logs wipes temporary state arrays immediately.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, Terminate',
                customClass: { confirmButton: 'rounded-3 small', cancelButton: 'rounded-3 small' }
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '/logout.php';
                }
            });
        }
    </script>
</body>
</html>