<?php 
include __DIR__."/header.php"; 

// 1. Handle Status Filters
$allowed_statuses = ['pending', 'processing', 'approved', 'rejected', 'cancelled'];
$filter = isset($_GET['status']) && in_array($_GET['status'], $allowed_statuses) ? $_GET['status'] : 'all';

// 2. Pagination Setup
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 15;
$offset = ($page - 1) * $limit;

// Count Total matching records
if ($filter === 'all') {
    $count_stmt = $conn->query("SELECT COUNT(*) total FROM withdrawals");
    $total = $count_stmt->fetch_assoc()['total'];
} else {
    $count_stmt = $conn->prepare("SELECT COUNT(*) total FROM withdrawals WHERE status = ?");
    $count_stmt->bind_param("s", $filter);
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];
}
$totalPages = ceil($total / $limit);

// 3. Fetch Withdrawals with User context
$sql = "SELECT w.*, u.first_name, u.last_name, u.email 
        FROM withdrawals w 
        JOIN users u ON w.user_uid = u.uid";
if ($filter !== 'all') {
    $sql .= " WHERE w.status = ?";
}
$sql .= " ORDER BY w.created_at DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);
if ($filter !== 'all') {
    $stmt->bind_param("sii", $filter, $offset, $limit);
} else {
    $stmt->bind_param("ii", $offset, $limit);
}
$stmt->execute();
$withdrawals = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid bg-light min-vh-100 p-0" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- Top Navigation Bar -->
    <header class="p-3 bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="/admin-dashboard" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2 rounded-3 me-3">
                    <i class="bi bi-chevron-left"></i> Dashboard
                </a>
                <h5 class="mb-0 fw-bold text-dark"><i class="text-success me-2"></i>Withdrawal</h5>
            </div>
            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill font-monospace">Total: <?= $total ?> requests</span>
        </div>
    </header>

    <div class="container-fluid p-4">
        
        <!-- Sub Navigation Tabs Filter -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-2">
            <ul class="nav nav-pills gap-1">
                <li class="nav-item">
                    <a class="nav-link px-4 rounded-3 fw-medium <?= $filter === 'all' ? 'active bg-success' : 'text-secondary' ?>" href="?status=all">All Requests</a>
                </li>
                <?php foreach ($allowed_statuses as $status): ?>
                    <li class="nav-item">
                        <a class="nav-link px-4 rounded-3 text-capitalize fw-medium <?= $filter === $status ? 'active bg-success' : 'text-secondary' ?>" href="?status=<?= $status ?>">
                            <?= $status ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Withdrawals Table Container -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom text-secondary font-monospace" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3">User info</th>
                            <th class="py-3">Amount Requested</th>
                            <th class="py-3">Payout Destination</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Created Date</th>
                            <th class="pe-4 py-3 text-end">Administrative Controls</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px;">
                        <?php if (empty($withdrawals)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-muted">
                                    <i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>
                                    No withdrawal requests matched this status filter.
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($withdrawals as $w): ?>
                            <tr>
                                <!-- User Meta Information -->
                                <td class="ps-4 py-3">
                                    <span class="d-block fw-bold text-dark"><?= htmlspecialchars($w['first_name'])."&nbsp;". htmlspecialchars($w['last_name']) ?></span>
                                    <span class="text-muted small font-monospace" style="font-size: 11px;"><?= htmlspecialchars($w['email']) ?></span>
                                </td>
                                
                                <!-- Amount Display -->
                                <td class="py-3 fw-bold font-monospace text-dark">
                                    $<?= number_format($w['amount'], 2) ?>
                                </td>
                                
                                <!-- Wallet Address Destination Details -->
                                <td class="py-3">
                                    <span class="badge bg-light border text-dark font-monospace mb-1 px-2 py-1" style="font-size: 10px;"><?= htmlspecialchars($w['network']) ?></span>
                                    <div class="text-muted font-monospace small text-truncate" style="max-width: 200px; font-size: 11px;" title="<?= htmlspecialchars($w['wallet_address']) ?>">
                                        <?= htmlspecialchars($w['wallet_address']) ?>
                                    </div>
                                </td>
                                
                                <!-- Visual Badging Status -->
                                <td class="py-3">
                                    <?php 
                                    $badge_class = match($w['status']) {
                                        'pending' => 'bg-warning-subtle text-warning border-warning-subtle',
                                        'processing' => 'bg-info-subtle text-info border-info-subtle',
                                        'approved' => 'bg-success-subtle text-success border-success-subtle',
                                        'rejected' => 'bg-danger-subtle text-danger border-danger-subtle',
                                        'cancelled' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                    };
                                    ?>
                                    <span class="badge px-3 py-1.5 border rounded-pill text-uppercase font-monospace <?= $badge_class ?>" style="font-size: 11px;">
                                        <?= $w['status'] ?>
                                    </span>
                                </td>
                                
                                <!-- Request Date -->
                                <td class="py-3 text-secondary font-monospace small" style="font-size: 12px;">
                                    <?= date('Y-m-d H:i', strtotime($w['created_at'])) ?>
                                </td>
                                
                                <!-- Controls -->
                                <td class="pe-4 py-3 text-end">
                                    <?php if (in_array($w['status'], ['pending', 'processing'])): ?>
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-success rounded-3 px-3 action-btn" 
                                                    data-uid="<?= $w['withdrawal_uid'] ?>" data-action="approve">
                                                <i class="bi bi-check-lg me-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-3 action-btn" 
                                                    data-uid="<?= $w['withdrawal_uid'] ?>" data-action="reject">
                                                <i class="bi bi-x-lg me-1"></i> Reject
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small italic">Processed</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Footer Engine -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link border-0 shadow-sm rounded-3 me-1 bg-white text-dark" href="?status=<?= $filter ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link border-0 shadow-sm rounded-3 me-1 <?= $i == $page ? 'bg-success text-white' : 'bg-white text-dark' ?>" href="?status=<?= $filter ?>&page=<?= $i ?>"><?= $i ?></a>
                        </li>
                    <?php endfor; ?>
                    <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                        <a class="page-link border-0 shadow-sm rounded-3 bg-white text-dark" href="?status=<?= $filter ?>&page=<?= $page + 1 ?>">Next</a>
                    </li>
                </ul>
            </nav>
        <?php endif; ?>
    </div>
</div>

<!-- Form Processing Script Interface Handling Operations dynamically -->
<script>
// Helper utility to auto-generate a mock 64-character blockchain transaction hash reference
function generateTxHash() {
    const chars = '0123456789abcdef';
    let hash = '0x';
    for (let i = 0; i < 64; i++) {
        hash += chars[Math.floor(Math.random() * 16)];
    }
    return hash;
}

document.querySelectorAll(".action-btn").forEach(btn => {
    btn.onclick = async () => {
        const uid = btn.dataset.uid;
        const decisionAction = btn.dataset.action; // 'approve' or 'reject'
        
        // Auto-populate transaction hash if approving, keep empty string if rejecting
        const defaultValue = decisionAction === 'approve' ? generateTxHash() : '';

        // Dynamic input configuration depending on execution contextual parameters
        const { value: textInput } = await Swal.fire({
            title: decisionAction === 'approve' ? 'Approve Withdrawal' : 'Reject Withdrawal',
            input: 'text',
            inputValue: defaultValue, // Pre-populates the input box automatically
            inputLabel: decisionAction === 'approve' ? 'Transaction Hash / Reference (Auto-Generated)' : 'Reason for rejection (Required)',
            placeholder: decisionAction === 'approve' ? '0x...' : 'Insufficient valid trading documentation...',
            showCancelButton: true,
            confirmButtonColor: decisionAction === 'approve' ? '#198754' : '#dc3545',
            inputValidator: (value) => {
                if (decisionAction === 'reject' && !value) {
                    return 'You must provide a rejection reason code statement!';
                }
                if (decisionAction === 'approve' && !value) {
                    return 'A valid transaction reference hash is required for tracking approvals!';
                }
            }
        });

        if (textInput === undefined) return; // Action cancelled by operator

        btn.disabled = true;

        try {
            const res = await fetch("<?= $company_info['admin-server'] ?>", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    action: "/admin/process-withdrawal", 
                    decision: decisionAction,           
                    withdrawal_uid: uid,
                    note: textInput
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
            Swal.fire({ icon: "error", text: "Critical system connectivity loss execution failure." });
        }
    };
});
</script>