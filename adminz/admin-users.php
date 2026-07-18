<?php 
include __DIR__."/header.php"; 

// 1. Handle Search & Filter Inputs
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$theme_filter = isset($_GET['theme']) && in_array($_GET['theme'], ['light', 'dark']) ? $_GET['theme'] : 'all';

// 2. Pagination Setup
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 3; // 10 users per page
$offset = ($page - 1) * $limit;

// 3. Count Total matching records dynamically
$count_sql = "SELECT COUNT(*) total FROM users u JOIN user_wallet w ON u.uid = w.user_uid WHERE 1=1";
$params = [];
$types = "";

if (!empty($search)) {
    $count_sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.uid LIKE ?)";
    $search_param = "%$search%";
    array_push($params, $search_param, $search_param, $search_param, $search_param);
    $types .= "ssss";
}
if ($theme_filter !== 'all') {
    $count_sql .= " AND u.theme = ?";
    array_push($params, $theme_filter);
    $types .= "s";
}

$count_stmt = $conn->prepare($count_sql);
if (!empty($params)) {
    $count_stmt->bind_param($types, ...$params);
}
$count_stmt->execute();
$total = $count_stmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($total / $limit);

// 4. Fetch Users with Wallet Context
$sql = "SELECT u.*, w.wallet_balance, w.status AS wallet_status, w.wallet_address, w.wallet_network 
        FROM users u 
        LEFT JOIN user_wallet w ON u.uid = w.user_uid 
        WHERE 1=1";

if (!empty($search)) {
    $sql .= " AND (u.first_name LIKE ? OR u.last_name LIKE ? OR u.email LIKE ? OR u.uid LIKE ?)";
}
if ($theme_filter !== 'all') {
    $sql .= " AND u.theme = ?";
}
$sql .= " ORDER BY u.created_at DESC LIMIT ?, ?";

// Adjust parameters for main retrieval query
$query_params = $params;
$query_types = $types . "ii";
array_push($query_params, $offset, $limit);

$stmt = $conn->prepare($sql);
$stmt->bind_param($query_types, ...$query_params);
$stmt->execute();
$users = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid bg-light min-vh-100 p-0" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- Top Navigation Bar -->
    <header class="p-3 bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="/admin-dashboard" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2 rounded-3 me-3">
                    <i class="bi bi-chevron-left"></i> Dashboard
                </a>
                <h5 class="mb-0 fw-bold text-dark"><i class="text-success me-2"></i>User Management Ledgers</h5>
            </div>
            <span class="badge bg-success-subtle text-success px-3 py-2 rounded-pill font-monospace">Total Registered: <?= $total ?> users</span>
        </div>
    </header>

    <div class="container-fluid p-4">
        
        <!-- Search & Advanced Filtering Controls -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-3">
            <form method="GET" class="row g-3 align-items-center">
                <div class="col-md-6">
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-search"></i></span>
                        <input type="text" name="search" class="form-control bg-light border-start-0 ps-0" placeholder="Search by name, email, or UID index target..." value="<?= htmlspecialchars($search) ?>">
                    </div>
                </div>
                <div class="col-md-3">
                    <select name="theme" class="form-select bg-light" onchange="this.form.submit()">
                        <option value="all" <?= $theme_filter === 'all' ? 'selected' : '' ?>>All Interface Themes</option>
                        <option value="light" <?= $theme_filter === 'light' ? 'selected' : '' ?>>Light Mode Preferred</option>
                        <option value="dark" <?= $theme_filter === 'dark' ? 'selected' : '' ?>>Dark Mode Preferred</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" class="btn btn-success rounded-3 w-100 fw-medium">Execute Filter</button>
                    <?php if(!empty($search) || $theme_filter !== 'all'): ?>
                        <a href="?" class="btn btn-outline-secondary rounded-3"><i class="bi bi-arrow-counterclockwise"></i></a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Users Table Container -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom text-secondary font-monospace" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3">Profile Identity</th>
                            <th class="py-3">Location & Contact</th>
                            <th class="py-3">Financial Ledger Balance</th>
                            <th class="py-3">Wallet Node Security</th>
                            <th class="py-3">Registration Date</th>
                            <th class="pe-4 py-3 text-end">Administrative Interventions</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px;">
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-people fs-2 d-block mb-2 text-secondary"></i>
                                    No user accounts matched the active index search metrics.
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <!-- Profile Identity -->
                                <td class="ps-4 py-3">
                                    <span class="d-block fw-bold text-dark"><?= htmlspecialchars($u['first_name'])." ". htmlspecialchars($u['last_name']) ?></span>
                                    <span class="text-muted small font-monospace d-block" style="font-size: 11px;"><?= htmlspecialchars($u['email']) ?></span>
                                    <span class="badge bg-light border text-secondary font-monospace px-2 py-0.5 mt-1" style="font-size: 10px;">UID: <?= substr($u['uid'], 0, 15) ?>...</span>
                                </td>
                                
                                <!-- Contact Metrics -->
                                <td class="py-3">
                                    <span class="d-block text-dark fw-medium small"><i class="bi bi-geo-alt-fill text-muted me-1"></i><?= htmlspecialchars($u['country']) ?></span>
                                    <span class="text-muted font-monospace small" style="font-size: 11px;"><?= htmlspecialchars($u['phone']) ?></span>
                                </td>
                                
                                <!-- Ledger Balance Tracking -->
                                <td class="py-3">
                                    <span class="d-block fw-bold text-dark font-monospace">$<?= number_format($u['wallet_balance'] ?? 0, 2) ?></span>
                                    <span class="badge bg-secondary-subtle text-secondary font-monospace px-2 py-0.5" style="font-size: 10px;"><?= htmlspecialchars($u['currency']) ?> System Default</span>
                                </td>
                                
                                <!-- Wallet Node Security Status -->
                                <td class="py-3">
                                    <?php if(!empty($u['wallet_address'])): ?>
                                        <span class="badge bg-light border text-dark font-monospace mb-1 d-inline-block" style="font-size: 10px;"><?= htmlspecialchars($u['wallet_network']) ?></span>
                                        <div class="text-muted font-monospace small text-truncate" style="max-width: 150px; font-size: 11px;" title="<?= htmlspecialchars($u['wallet_address']) ?>">
                                            <?= htmlspecialchars($u['wallet_address']) ?>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small italic font-monospace" style="font-size: 11px;">No Address Bonded</span>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Creation Datetime Structuring -->
                                <td class="py-3 text-secondary font-monospace small" style="font-size: 12px;">
                                    <?= date('Y-m-d H:i', strtotime($u['created_at'])) ?>
                                </td>
                                
                                <!-- Operations Control Matrix -->
                                <td class="pe-4 py-3 text-end">
                                    <div class="d-inline-flex gap-2">
                                        <!-- Adjust Balance Engine -->
                                        <button type="button" class="btn btn-sm btn-outline-success rounded-3 px-2 credit-debit-btn" 
                                                data-uid="<?= $u['uid'] ?>" data-name="<?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?>" data-balance="<?= $u['wallet_balance'] ?>">
                                            <i class="bi bi-currency-exchange"></i> Balance
                                        </button>
                                        
                                        <!-- System Status Adjustments -->
                                        <?php if ($u['wallet_status'] === 'active'): ?>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-2 toggle-status-btn" 
                                                    data-uid="<?= $u['uid'] ?>" data-target-status="suspended">
                                                <i class="bi bi-slash-circle me-1"></i> Suspend
                                            </button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-sm btn-success rounded-3 px-2 toggle-status-btn" 
                                                    data-uid="<?= $u['uid'] ?>" data-target-status="active">
                                                <i class="bi bi-check-circle me-1"></i> Activate
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Routing Footprint -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link border-0 shadow-sm rounded-3 me-1 bg-white text-dark" href="?search=<?= urlencode($search) ?>&theme=<?= $theme_filter ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link border-0 shadow-sm rounded-3 me-1 <?= $i == $page ? 'bg-success text-white' : 'bg-white text-dark' ?>" href="?search=<?= urlencode($search) ?>&theme=<?= $theme_filter ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link border-0 shadow-sm rounded-3 bg-white text-dark" href="?search=<?= urlencode($search) ?>&theme=<?= $theme_filter ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<!-- Form Processing Script Interface Handling Operations Dynamically -->
<script>
// Action 1: Adjust Balance Module Interface (Credit / Debit)
document.querySelectorAll(".credit-debit-btn").forEach(btn => {
    btn.onclick = async () => {
        const uid = btn.dataset.uid;
        const userName = btn.dataset.name;
        const currentBalance = parseFloat(btn.dataset.balance).toFixed(2);

        const { value: formValues } = await Swal.fire({
            title: `Adjust Balance: ${userName}`,
            html:
                `<div class="text-start mb-3 small text-muted">Current Ledger Standing: <strong>$${currentBalance}</strong></div>` +
                '<select id="swal-type" class="form-select mb-3">' +
                '  <option value="credit">Credit (+) Add Capital</option>' +
                '  <option value="debit">Debit (-) Deduct Capital</option>' +
                '</select>' +
                '<input id="swal-amount" class="form-control mb-3" type="number" step="0.01" placeholder="Amount ($)">' +
                '<input id="swal-description" class="form-control" type="text" placeholder="Audit description note (e.g. Compensation Bonus)">',
            focusConfirm: false,
            showCancelButton: true,
            confirmButtonColor: '#198754',
            preConfirm: () => {
                const amount = document.getElementById('swal-amount').value;
                if (!amount || parseFloat(amount) <= 0) {
                    Swal.showValidationMessage('Please input a valid correction amount value!');
                }
                return {
                    type: document.getElementById('swal-type').value,
                    amount: amount,
                    description: document.getElementById('swal-description').value || 'Administrative Adjustment Ledger entry'
                }
            }
        });

        if (!formValues) return;

        btn.disabled = true;
        try {
            const res = await fetch("<?= $company_info['admin-server'] ?>", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    action: "/admin/adjust-balance", 
                    user_uid: uid,
                    adjustment_type: formValues.type,
                    amount: formValues.amount,
                    description: formValues.description
                })
            });

            const data = await res.json();
            btn.disabled = false;

            if (!data.success) {
                Swal.fire({ icon: "error", title: "Adjustment Failed", text: data.message });
                return;
            }
            Swal.fire({ icon: "success", text: data.message }).then(() => location.reload());
        } catch (error) {
            btn.disabled = false;
            Swal.fire({ icon: "error", text: "Critical communications error accessing admin ledger matrix processing." });
        }
    };
});

// Action 2: Suspension/Activation System Toggles
document.querySelectorAll(".toggle-status-btn").forEach(btn => {
    btn.onclick = async () => {
        const uid = btn.dataset.uid;
        const targetStatus = btn.dataset.target-status;

        const confirmation = await Swal.fire({
            title: `Confirm Account Status Shift`,
            text: `Are you certain you wish to change this user's wallet engine availability mode to ${targetStatus}?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: targetStatus === 'suspended' ? '#dc3545' : '#198754',
            confirmButtonText: `Proceed to execution`
        });

        if (!confirmation.isConfirmed) return;

        btn.disabled = true;
        try {
            const res = await fetch("<?= $company_info['admin-server'] ?>", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    action: "/admin/toggle-user-status", 
                    user_uid: uid,
                    status: targetStatus
                })
            });

            const data = await res.json();
            btn.disabled = false;

            if (!data.success) {
                Swal.fire({ icon: "error", title: "Execution Failure", text: data.message });
                return;
            }
            Swal.fire({ icon: "success", text: data.message }).then(() => location.reload());
        } catch (error) {
            btn.disabled = false;
            Swal.fire({ icon: "error", text: "Error syncing request mutation commands down to database server clusters." });
        }
    };
});
</script>