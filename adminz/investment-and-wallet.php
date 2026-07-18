<?php 
include __DIR__."/header.php"; 

// --- PAGINATION CONFIGURATION ---
$per_page = 5; // Adjust how many items per table you want to display

// 1. Wallets Pagination
$w_page = isset($_GET['w_page']) && is_numeric($_GET['w_page']) ? (int)$_GET['w_page'] : 1;
if ($w_page < 1) $w_page = 1;
$w_offset = ($w_page - 1) * $per_page;

$total_wallets_res = $conn->query("SELECT COUNT(*) as total FROM wallets");
$total_wallets = $total_wallets_res ? $total_wallets_res->fetch_assoc()['total'] : 0;
$w_total_pages = ceil($total_wallets / $per_page);

// Fetch Paginated Deposit Wallets
$wallets_stmt = $conn->prepare("SELECT * FROM wallets ORDER BY network ASC, coin_symbol ASC LIMIT ?, ?");
$wallets = [];
if ($wallets_stmt) {
    $wallets_stmt->bind_param("ii", $w_offset, $per_page);
    $wallets_stmt->execute();
    $wallets = $wallets_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// 2. Investment Plans Pagination
$p_page = isset($_GET['p_page']) && is_numeric($_GET['p_page']) ? (int)$_GET['p_page'] : 1;
if ($p_page < 1) $p_page = 1;
$p_offset = ($p_page - 1) * $per_page;

$total_plans_res = $conn->query("SELECT COUNT(*) as total FROM investment_plan");
$total_plans = $total_plans_res ? $total_plans_res->fetch_assoc()['total'] : 0;
$p_total_pages = ceil($total_plans / $per_page);

// Fetch Paginated Investment Plans
$plans_stmt = $conn->prepare("SELECT * FROM investment_plan ORDER BY min_limit ASC LIMIT ?, ?");
$plans = [];
if ($plans_stmt) {
    $plans_stmt->bind_param("ii", $p_offset, $per_page);
    $plans_stmt->execute();
    $plans = $plans_stmt->get_result()->fetch_all(MYSQLI_ASSOC);
}
?>

<div class="container-fluid bg-light min-vh-100 p-0" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- Top Navigation Header -->
    <header class="p-3 bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="/admin-dashboard" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2 rounded-3 me-3">
                    <i class="bi bi-chevron-left"></i> Dashboard
                </a>
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-safe2 text-success me-2"></i>Wallets & Plans Management</h5>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-sm btn-dark rounded-3 px-3 fw-bold shadow-sm" onclick="openWalletModal()">
                    <i class="bi bi-plus-lg me-1"></i> New Wallet
                </button>
                <button type="button" class="btn btn-sm btn-success rounded-3 px-3 fw-bold shadow-sm" onclick="openPlanModal()">
                    <i class="bi bi-plus-lg me-1"></i> New Plan
                </button>
            </div>
        </div>
    </header>

    <div class="container-fluid p-4">
        <div class="row g-4">
            
            <!-- ================= LEFT COLUMN: DEPOSIT WALLETS ================= -->
            <div class="col-xl-5 col-lg-6">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-secondary fw-bold text-uppercase font-monospace mb-0" style="font-size: 12px; letter-spacing: 0.5px;">Company Deposit Wallets</h6>
                    <span class="badge bg-dark-subtle text-dark-emphasis px-2 py-1 rounded-pill font-monospace"><?= $total_wallets ?> Total</span>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-3">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="bg-light rounded-3 font-monospace small text-secondary" style="font-size: 11px; border-bottom: 2px solid #f8f9fa; text-transform: uppercase;">
                                    <th class="ps-4 py-3">Asset info</th>
                                    <th class="py-3">Address & Destination</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="pe-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <?php if (empty($wallets)): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="bi bi-wallet2 fs-3 d-block mb-2 text-secondary"></i>
                                            No collection wallets configured.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                                
                                <?php foreach ($wallets as $w): ?>
                                    <tr class="border-bottom border-light-subtle">
                                        <td class="ps-4 py-3">
                                            <span class="d-block fw-bold text-dark"><?= htmlspecialchars($w['coin_symbol']) ?></span>
                                            <span class="badge bg-light border text-secondary font-monospace text-uppercase" style="font-size: 9px;"><?= htmlspecialchars($w['network']) ?></span>
                                        </td>
                                        <td class="py-3">
                                            <div class="text-muted font-monospace small text-truncate mb-1" style="max-width: 130px;" title="<?= htmlspecialchars($w['wallet_address']) ?>">
                                                <code><?= htmlspecialchars($w['wallet_address']) ?></code>
                                            </div>
                                            <?php if (!empty($w['description'])): ?>
                                                <small class="text-secondary d-block italic text-truncate" style="max-width: 130px; font-size: 11px;"><?= htmlspecialchars($w['description']) ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td class="py-3 text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input update-toggle" type="checkbox" data-type="wallet" data-id="<?= $w['id'] ?>" <?= $w['is_active'] ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-link text-dark p-0" onclick='editWallet(<?= json_encode($w, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="deleteItem('wallet', <?= $w['id'] ?>)">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Wallets Pagination Controls -->
                <?php if ($w_total_pages > 1): ?>
                    <nav>
                        <ul class="pagination pagination-sm justify-content-center font-monospace">
                            <li class="page-item <?= ($w_page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-item page-link border-0 rounded-3 text-dark bg-transparent" href="?w_page=<?= $w_page - 1 ?>&p_page=<?= $p_page ?>"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            <?php for ($i = 1; $i <= $w_total_pages; $i++): ?>
                                <li class="page-item mx-1 <?= ($w_page == $i) ? 'active' : '' ?>">
                                    <a class="page-link border-0 rounded-3 <?= ($w_page == $i) ? 'bg-dark text-white fw-bold' : 'text-dark bg-white shadow-sm' ?>" href="?w_page=<?= $i ?>&p_page=<?= $p_page ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($w_page >= $w_total_pages) ? 'disabled' : '' ?>">
                                <a class="page-item page-link border-0 rounded-3 text-dark bg-transparent" href="?w_page=<?= $w_page + 1 ?>&p_page=<?= $p_page ?>"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>

            <!-- ================= RIGHT COLUMN: INVESTMENT PLANS ================= -->
            <div class="col-xl-7 col-lg-6">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h6 class="text-secondary fw-bold text-uppercase font-monospace mb-0" style="font-size: 12px; letter-spacing: 0.5px;">Investment Packages</h6>
                    <span class="badge bg-success-subtle text-success-emphasis px-2 py-1 rounded-pill font-monospace"><?= $total_plans ?> Tiers</span>
                </div>

                <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-3">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr class="bg-light rounded-3 font-monospace small text-secondary" style="font-size: 11px; border-bottom: 2px solid #f8f9fa; text-transform: uppercase;">
                                    <th class="ps-4 py-3">Tier Package</th>
                                    <th class="py-3">Limits (USD)</th>
                                    <th class="py-3">Yield Rate / Term</th>
                                    <th class="py-3">Routing</th>
                                    <th class="py-3 text-center">Status</th>
                                    <th class="pe-4 py-3 text-end">Action</th>
                                </tr>
                            </thead>
                            <tbody style="font-size: 14px;">
                                <?php if (empty($plans)): ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">
                                            <i class="bi bi-bar-chart fs-3 d-block mb-2 text-secondary"></i>
                                            No yield brackets configured yet.
                                        </td>
                                    </tr>
                                <?php endif; ?>

                                <?php foreach ($plans as $p): ?>
                                    <tr class="border-bottom border-light-subtle">
                                        <td class="ps-4 py-3">
                                            <span class="d-block fw-bold text-dark">
                                                <?= htmlspecialchars($p['name']) ?>
                                                <?php if($p['is_bonus']): ?>
                                                    <span class="badge bg-warning text-warning-emphasis bg-opacity-10 rounded-pill small ms-1" style="font-size: 9px;">Promo</span>
                                                <?php endif; ?>
                                            </span>
                                            <small class="text-muted text-truncate d-block" style="max-width: 140px;" title="<?= htmlspecialchars($p['description']) ?>"><?= htmlspecialchars($p['description'] ?: 'No custom terms.') ?></small>
                                        </td>
                                        <td class="py-3 font-monospace small">
                                            <span class="d-block text-dark fw-bold">$<?= number_format($p['min_limit'], 0) ?></span>
                                            <span class="text-muted" style="font-size: 11px;">to $<?= number_format($p['max_limit'], 0) ?></span>
                                        </td>
                                        <td class="py-3">
                                            <span class="d-block text-success fw-bold font-monospace">+<?= number_format($p['roi'], 2) ?>%</span>
                                            <span class="text-secondary font-monospace small" style="font-size: 11px;"><?= $p['duration_value'] ?> <?= htmlspecialchars($p['duration_unit']) ?></span>
                                        </td>
                                        <td class="py-3 text-capitalize small">
                                            <span class="badge <?= $p['approval'] === 'automated' ? 'bg-info-subtle text-info-emphasis' : 'bg-warning-subtle text-warning-emphasis' ?> rounded-pill font-monospace" style="font-size: 10px;">
                                                <?= $p['approval'] ?>
                                            </span>
                                        </td>
                                        <td class="py-3 text-center">
                                            <div class="form-check form-switch d-inline-block">
                                                <input class="form-check-input update-toggle" type="checkbox" data-type="plan" data-id="<?= $p['id'] ?>" <?= $p['status'] === 'active' ? 'checked' : '' ?>>
                                            </div>
                                        </td>
                                        <td class="pe-4 py-3 text-end">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button type="button" class="btn btn-sm btn-link text-dark p-0" onclick='editPlan(<?= json_encode($p, JSON_HEX_APOS | JSON_HEX_QUOT) ?>)'>
                                                    <i class="bi bi-pencil-square"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-link text-danger p-0" onclick="deleteItem('plan', <?= $p['id'] ?>)">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Plans Pagination Controls -->
                <?php if ($p_total_pages > 1): ?>
                    <nav>
                        <ul class="pagination pagination-sm justify-content-center font-monospace">
                            <li class="page-item <?= ($p_page <= 1) ? 'disabled' : '' ?>">
                                <a class="page-item page-link border-0 rounded-3 text-dark bg-transparent" href="?w_page=<?= $w_page ?>&p_page=<?= $p_page - 1 ?>"><i class="bi bi-chevron-left"></i></a>
                            </li>
                            <?php for ($i = 1; $i <= $p_total_pages; $i++): ?>
                                <li class="page-item mx-1 <?= ($p_page == $i) ? 'active' : '' ?>">
                                    <a class="page-link border-0 rounded-3 <?= ($p_page == $i) ? 'bg-success text-white fw-bold' : 'text-dark bg-white shadow-sm' ?>" href="?w_page=<?= $w_page ?>&p_page=<?= $i ?>"><?= $i ?></a>
                                </li>
                            <?php endfor; ?>
                            <li class="page-item <?= ($p_page >= $p_total_pages) ? 'disabled' : '' ?>">
                                <a class="page-item page-link border-0 rounded-3 text-dark bg-transparent" href="?w_page=<?= $w_page ?>&p_page=<?= $p_page + 1 ?>"><i class="bi bi-chevron-right"></i></a>
                            </li>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>

<!-- Dynamic Dialog Layout Controllers -->
<script>
const ENGINE_URL = "<?= $company_info['admin-server'] ?? '' ?>";

// --- TOGGLE SWITCH OPERATIONS ---
document.querySelectorAll(".update-toggle").forEach(toggle => {
    toggle.onchange = async () => {
        const id = toggle.dataset.id;
        const type = toggle.dataset.type;
        const isChecked = toggle.checked;

        try {
            const res = await fetch(ENGINE_URL, {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    action: type === 'wallet' ? "/admin/toggle-wallet" : "/admin/toggle-plan",
                    id: id,
                    status: isChecked ? 1 : 0
                })
            });
            const data = await res.json();
            if (!data.success) {
                toggle.checked = !isChecked;
                Swal.fire({ icon: 'error', text: data.message || 'Operation rejected.' });
            }
        } catch(e) {
            toggle.checked = !isChecked;
            Swal.fire({ icon: 'error', text: 'System interaction drop.' });
        }
    };
});

// --- RECORD DELETION PIPELINE ---
function deleteItem(type, id) {
    Swal.fire({
        title: 'Are you sure?',
        text: `This will permanently remove this ${type}. If it is linked to user transactions, the database might reject the deletion.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            executeDataTransaction({
                action: type === 'wallet' ? "/admin/delete-wallet" : "/admin/delete-plan",
                id: id
            });
        }
    });
}

// --- WALLET MUTATION OVERLAYS ---
async function openWalletModal(wallet = null) {
    const isEdit = wallet !== null;
    const { value: formValues } = await Swal.fire({
        title: isEdit ? 'Modify Deposit Wallet' : 'Add Collection Wallet',
        html: `
            <div class="text-start font-monospace small">
                <label class="form-label mb-1">Blockchain Network Structure</label>
                <input id="swal-w-network" class="form-control form-control-sm mb-2 font-monospace text-uppercase" placeholder="e.g., ERC20, TRC20" value="${wallet?.network || ''}">
                
                <label class="form-label mb-1">Coin / Asset Symbol</label>
                <input id="swal-w-symbol" class="form-control form-control-sm mb-2 font-monospace text-uppercase" placeholder="e.g., USDT, BTC" value="${wallet?.coin_symbol || ''}">
                
                <label class="form-label mb-1">Public Address Target</label>
                <input id="swal-w-address" class="form-control form-control-sm mb-2 font-monospace" placeholder="0x..." value="${wallet?.wallet_address || ''}">
                
                <label class="form-label mb-1">Custom Display Description</label>
                <textarea id="swal-w-desc" class="form-control form-control-sm mb-2" placeholder="Instructional notes for users...">${wallet?.description || ''}</textarea>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#198754',
        preConfirm: () => {
            const network = document.getElementById('swal-w-network').value.trim();
            const coin_symbol = document.getElementById('swal-w-symbol').value.trim();
            const wallet_address = document.getElementById('swal-w-address').value.trim();
            const description = document.getElementById('swal-w-desc').value.trim();

            if (!network || !coin_symbol || !wallet_address) {
                Swal.showValidationMessage('Please populate all structural mandatory tracking parameters!');
                return false;
            }
            return { action: isEdit ? "/admin/update-wallet" : "/admin/create-wallet", id: wallet?.id, network, coin_symbol, wallet_address, description };
        }
    });

    if (formValues) executeDataTransaction(formValues);
}

function editWallet(walletData) { openWalletModal(walletData); }

// --- INVESTMENT PLAN MUTATION OVERLAYS ---
async function openPlanModal(plan = null) {
    const isEdit = plan !== null;
    const units = ['hours', 'days', 'weeks', 'months', 'years'];
    
    let unitOptions = '';
    units.forEach(u => {
        unitOptions += `<option value="${u}" ${plan?.duration_unit === u ? 'selected' : ''}>${u}</option>`;
    });

    const { value: formValues } = await Swal.fire({
        title: isEdit ? 'Modify Yield Bracket' : 'Configure New Yield Bracket',
        html: `
            <div class="text-start font-monospace small">
                <label class="form-label mb-1">Package Tier Identity Title</label>
                <input id="swal-p-name" class="form-control form-control-sm mb-2 fw-bold" placeholder="e.g., Premium Yield Tier" value="${plan?.name || ''}">
                
                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label mb-1">Minimum Stake ($)</label>
                        <input id="swal-p-min" type="number" step="0.01" class="form-control form-control-sm" value="${plan?.min_limit || ''}">
                    </div>
                    <div class="col-6">
                        <label class="form-label mb-1">Maximum Stake ($)</label>
                        <input id="swal-p-max" type="number" step="0.01" class="form-control form-control-sm" value="${plan?.max_limit || ''}">
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-4">
                        <label class="form-label mb-1">ROI Rate (%)</label>
                        <input id="swal-p-roi" type="number" step="0.01" class="form-control form-control-sm" placeholder="e.g., 4.5" value="${plan?.roi || ''}">
                    </div>
                    <div class="col-4">
                        <label class="form-label mb-1">Term Integer</label>
                        <input id="swal-p-durval" type="number" class="form-control form-control-sm" placeholder="30" value="${plan?.duration_value || ''}">
                    </div>
                    <div class="col-4">
                        <label class="form-label mb-1">Term Unit</label>
                        <select id="swal-p-durunit" class="form-select form-select-sm">${unitOptions}</select>
                    </div>
                </div>

                <div class="row g-2 mb-2">
                    <div class="col-6">
                        <label class="form-label mb-1">Routing Mode</label>
                        <select id="swal-p-approval" class="form-select form-select-sm">
                            <option value="automated" ${plan?.approval === 'automated' ? 'selected' : ''}>Automated Approval</option>
                            <option value="manual" ${plan?.approval === 'manual' ? 'selected' : ''}>Manual Admin Check</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label mb-1">Class Type</label>
                        <select id="swal-p-bonus" class="form-select form-select-sm">
                            <option value="0" ${plan?.is_bonus == 0 ? 'selected' : ''}>Standard Package</option>
                            <option value="1" ${plan?.is_bonus == 1 ? 'selected' : ''}>Promotional / Bonus Tier</option>
                        </select>
                    </div>
                </div>

                <label class="form-label mb-1">Package Summary Terms</label>
                <textarea id="swal-p-desc" class="form-control form-control-sm" placeholder="Brief metadata description for client dashboard...">${plan?.description || ''}</textarea>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonColor: '#198754',
        preConfirm: () => {
            const name = document.getElementById('swal-p-name').value.trim();
            const min_limit = document.getElementById('swal-p-min').value;
            const max_limit = document.getElementById('swal-p-max').value;
            const roi = document.getElementById('swal-p-roi').value;
            const duration_value = document.getElementById('swal-p-durval').value;
            const duration_unit = document.getElementById('swal-p-durunit').value;
            const approval = document.getElementById('swal-p-approval').value;
            const is_bonus = document.getElementById('swal-p-bonus').value;
            const description = document.getElementById('swal-p-desc').value.trim();

            if (!name || !min_limit || !max_limit || !roi || !duration_value) {
                Swal.showValidationMessage('All mathematical structural inputs are required for plan evaluation rules!');
                return false;
            }
            return { action: isEdit ? "/admin/update-plan" : "/admin/create-plan", id: plan?.id, name, min_limit, max_limit, roi, duration_value, duration_unit, approval, is_bonus, description };
        }
    });

    if (formValues) executeDataTransaction(formValues);
}

function editPlan(planData) { openPlanModal(planData); }

// --- GENERAL DATA INTERACTION API PIPELINE ---
async function executeDataTransaction(payload) {
    try {
        const res = await fetch(ENGINE_URL, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload)
        });
        const data = await res.json();
        if (data.success) {
            Swal.fire({ icon: "success", text: data.message || "Data synced safely." }).then(() => location.reload());
        } else {
            Swal.fire({ icon: "error", title: "Transaction Discarded", text: data.message });
        }
    } catch (err) {
        Swal.fire({ icon: "error", text: "Connectivity drops occurred during structural database push context." });
    }
}
</script>