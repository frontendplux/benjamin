<?php  include __DIR__."/header.php"; ?>
<body class="bg-light" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">

    <!-- MAIN GRID MENU LAYOUT -->
    <main class="container my-4">
        
        <!-- Welcome Hub Segment -->
        <div class="mb-4 d-flex justify-content-between">
            <h5 class="fw-bold text-dark mb-1">Admin</h5>
            <div>
                <a href="/admin-logout" class="bi bi-power fs-3"></a>
                <?php if($adminery['firstname'] != '' || $adminery['lastname'] != ''): ?>
                <span class="btn btn-primary rounded-circle">
                      <?= substr($adminery['firstname'], 0, 1) . substr($adminery['lastname'], 0, 1); ?>
                </span>
                <?php endif ?>
            </div>
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