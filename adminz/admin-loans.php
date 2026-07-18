<?php 
include __DIR__."/header.php"; 

// 1. Handle Status Filters for Loans
$allowed_statuses = ['pending', 'approved', 'rejected', 'cancelled'];
$filter = isset($_GET['status']) && in_array($_GET['status'], $allowed_statuses) ? $_GET['status'] : 'all';

// 2. Pagination Setup
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 15;
$offset = ($page - 1) * $limit;

// Count Total matching records
if ($filter === 'all') {
    $count_stmt = $conn->query("SELECT COUNT(*) total FROM loans");
    $total = $count_stmt->fetch_assoc()['total'];
} else {
    $count_stmt = $conn->prepare("SELECT COUNT(*) total FROM loans WHERE status = ?");
    $count_stmt->bind_param("s", $filter);
    $count_stmt->execute();
    $total = $count_stmt->get_result()->fetch_assoc()['total'];
}
$totalPages = ceil($total / $limit);

// 3. Fetch Loans with User profile context
$sql = "SELECT l.*, u.first_name, u.last_name, u.email 
        FROM loans l 
        JOIN users u ON l.user_uid = u.uid";
if ($filter !== 'all') {
    $sql .= " WHERE l.status = ?";
}
$sql .= " ORDER BY l.created_at DESC LIMIT ?, ?";

$stmt = $conn->prepare($sql);
if ($filter !== 'all') {
    $stmt->bind_param("sii", $filter, $offset, $limit);
} else {
    $stmt->bind_param("ii", $offset, $limit);
}
$stmt->execute();
$loans = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>

<div class="container-fluid bg-light min-vh-100 p-0" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
    
    <!-- Top Navigation Bar -->
    <header class="p-3 bg-white border-bottom shadow-sm sticky-top">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <a href="/admin-dashboard" class="btn btn-outline-secondary btn-sm d-inline-flex align-items-center gap-2 rounded-3 me-3">
                    <i class="bi bi-chevron-left"></i> Dashboard
                </a>
                <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-bank text-primary me-2"></i>Loan Application Center</h5>
            </div>
            <span class="badge bg-secondary-subtle text-secondary px-3 py-2 rounded-pill font-monospace">Total Applications: <?= $total ?></span>
        </div>
    </header>

    <div class="container-fluid p-4">
        
        <!-- Filter Tabs Component -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white p-2">
            <ul class="nav nav-pills gap-1">
                <li class="nav-item">
                    <a class="nav-link px-4 rounded-3 fw-medium <?= $filter === 'all' ? 'active bg-primary' : 'text-secondary' ?>" href="?status=all">All Applications</a>
                </li>
                <?php foreach ($allowed_statuses as $status): ?>
                    <li class="nav-item">
                        <a class="nav-link px-4 rounded-3 text-capitalize fw-medium <?= $filter === $status ? 'active bg-primary' : 'text-secondary' ?>" href="?status=<?= $status ?>">
                            <?= $status ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Loans Table Container -->
        <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light border-bottom text-secondary font-monospace" style="font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-3">Applicant details</th>
                            <th class="py-3">Requested Amount</th>
                            <th class="py-3">Term Duration</th>
                            <th class="py-3">Application Reason</th>
                            <th class="py-3">Status</th>
                            <th class="py-3">Applied Date</th>
                            <th class="pe-4 py-3 text-end">Administrative Controls</th>
                        </tr>
                    </thead>
                    <tbody style="font-size: 14px;">
                        <?php if (empty($loans)): ?>
                            <tr>
                                <td colspan="7" class="text-center py-5 text-muted">
                                    <i class="bi bi-folder-x fs-2 d-block mb-2 text-secondary"></i>
                                    No loan applications matched this filter criteria.
                                </td>
                            </tr>
                        <?php endif; ?>
                        
                        <?php foreach ($loans as $l): ?>
                            <tr>
                                <!-- User Data -->
                                <td class="ps-4 py-3">
                                    <span class="d-block fw-bold text-dark"><?= htmlspecialchars($l['first_name'])." ". htmlspecialchars($l['last_name']) ?></span>
                                    <span class="text-muted small font-monospace" style="font-size: 11px;"><?= htmlspecialchars($l['email']) ?></span>
                                </td>
                                
                                <!-- Principal Capital Requested -->
                                <td class="py-3 fw-bold font-monospace text-dark">
                                    $<?= number_format($l['amount'], 2) ?>
                                </td>
                                
                                <!-- Duration Term -->
                                <td class="py-3 font-monospace">
                                    <span class="badge bg-light border text-dark px-2 py-1">
                                        <?= htmlspecialchars($l['duration'] ?? 'Not specified') ?>
                                    </span>
                                </td>

                                <!-- Reason Notes -->
                                <td class="py-3 text-secondary">
                                    <div class="text-truncate" style="max-width: 220px;" title="<?= htmlspecialchars($l['reason']) ?>">
                                        <?= htmlspecialchars($l['reason']) ?>
                                    </div>
                                    <?php if(!empty($l['admin_note'])): ?>
                                        <small class="d-block text-danger font-monospace mt-1" style="font-size: 11px;">
                                            <strong>Note:</strong> <?= htmlspecialchars($l['admin_note']) ?>
                                        </small>
                                    <?php endif; ?>
                                </td>
                                
                                <!-- Dynamic Status Badging -->
                                <td class="py-3">
                                    <?php 
                                    $badge_class = match($l['status']) {
                                        'pending' => 'bg-warning-subtle text-warning border-warning-subtle',
                                        'approved' => 'bg-success-subtle text-success border-success-subtle',
                                        'rejected' => 'bg-danger-subtle text-danger border-danger-subtle',
                                        'cancelled' => 'bg-secondary-subtle text-secondary border-secondary-subtle',
                                    };
                                    ?>
                                    <span class="badge px-3 py-1.5 border rounded-pill text-uppercase font-monospace <?= $badge_class ?>" style="font-size: 11px;">
                                        <?= $l['status'] ?>
                                    </span>
                                </td>
                                
                                <!-- Creation Timestamp -->
                                <td class="py-3 text-secondary font-monospace small" style="font-size: 12px;">
                                    <?= date('Y-m-d H:i', strtotime($l['created_at'])) ?>
                                </td>
                                
                                <!-- Processing Control Switch Triggers -->
                                <td class="pe-4 py-3 text-end">
                                    <?php if ($l['status'] === 'pending'): ?>
                                        <div class="d-inline-flex gap-2">
                                            <button type="button" class="btn btn-sm btn-primary rounded-3 px-3 action-btn" 
                                                    data-uid="<?= $l['loan_uid'] ?>" data-action="approve">
                                                <i class="bi bi-check-lg me-1"></i> Approve
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger rounded-3 px-3 action-btn" 
                                                    data-uid="<?= $l['loan_uid'] ?>" data-action="reject">
                                                <i class="bi bi-x-lg me-1"></i> Reject
                                            </button>
                                        </div>
                                    <?php else: ?>
                                        <span class="text-muted small fs-7 border border-dashed p-1 rounded font-monospace bg-light">Finalized</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination Routing -->
        <?php if ($totalPages > 1): ?>
            <nav class="mt-4">
                <ul class="pagination justify-content-center">
                    <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                        <a class="page-link border-0 shadow-sm rounded-3 me-1 bg-white text-dark" href="?status=<?= $filter ?>&page=<?= $page - 1 ?>">Previous</a>
                    </li>
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
                            <a class="page-link border-0 shadow-sm rounded-3 me-1 <?= $i == $page ? 'bg-primary text-white' : 'bg-white text-dark' ?>" href="?status=<?= $filter ?>&page=<?= $i ?>"><?= $i ?></a>
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

<script>
document.querySelectorAll(".action-btn").forEach(btn => {
    btn.onclick = async () => {
        const uid = btn.dataset.uid;
        const decisionAction = btn.dataset.action; // 'approve' or 'reject'
        
        const { value: textInput } = await Swal.fire({
            title: decisionAction === 'approve' ? 'Approve Loan Application' : 'Reject Loan Application',
            input: 'text',
            inputLabel: decisionAction === 'approve' ? 'Approval Terms Note / Comments (Optional)' : 'Reason for Loan Rejection (Required)',
            placeholder: decisionAction === 'approve' ? 'Approved based on credit evaluation.' : 'Provide the explicit reason...',
            showCancelButton: true,
            confirmButtonColor: decisionAction === 'approve' ? '#0d6efd' : '#dc3545',
            inputValidator: (value) => {
                if (decisionAction === 'reject' && !value) {
                    return 'You must specify a reason for rejecting this loan application.';
                }
            }
        });

        if (textInput === undefined) return; // Escape modal context

        btn.disabled = true;

        try {
            const res = await fetch("<?= $company_info['admin-server'] ?>", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    action: "/admin/process-loan", 
                    decision: decisionAction,           
                    loan_uid: uid,
                    note: textInput
                })
            });

            const data = await res.json();
            btn.disabled = false;

            if (!data.success) {
                Swal.fire({ icon: "error", title: "Operation Failed", text: data.message });
                return;
            }

            Swal.fire({ icon: "success", text: data.message }).then(() => location.reload());

        } catch (error) {
            btn.disabled = false;
            Swal.fire({ icon: "error", text: "Network communication failure." });
        }
    };
});
</script>