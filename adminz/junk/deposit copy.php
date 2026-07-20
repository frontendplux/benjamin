<?php 
include __DIR__."/header.php";

// 1. Get current page tab filter (Default to 'pending' if no tab is clicked)
$status = $_GET['page'] ?? 'pending';

// 2. Handle Action Click (Approve / Reject / Cancel)
if (isset($_GET['action']) && isset($_GET['id'])) {
    $action = $_GET['action'];
    $deposit_id = (int)$_GET['id'];
    $admin_user = $adminery['uid']; // Replace with your active session admin username if available

    // Map actions to matching database statuses
    $new_status = null;
    if ($action === 'approve') $new_status = 'approved';
    if ($action === 'reject') $new_status = 'rejected';
    if ($action === 'cancel') $new_status = 'cancelled';

    if ($new_status) {
        $update = $conn->prepare("UPDATE deposits SET status = ?, approved_by = ? WHERE id = ?");
        $update->bind_param("ssi", $new_status, $admin_user, $deposit_id);
        $update->execute();
        
        // Refresh page to clear URL parameters and reflect updates safely
        header("Location: ?page=" . $status);
        exit;
    }
}

// 3. Fetch filtered data based on selected tab
$deposit = $conn->prepare("SELECT d.*, ip.name as plan_name 
                           FROM deposits d
                           JOIN investment_plan ip ON d.investment_plan_id = ip.id 
                           WHERE d.status = ?");
$deposit->bind_param("s", $status);
$deposit->execute();
$result = $deposit->get_result();
$all_deposits = $result->fetch_all(MYSQLI_ASSOC);
?>

<body class="bg-light" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
    
    <!-- Top Navigation Bar -->
    <header class="p-2 bg-white border-bottom shadow-sm">
        <div class="container d-flex align-items-center">
            <a href="/admin-dashboard" class="btn btn-light d-inline-flex align-items-center gap-2">
                <i class="bi bi-chevron-left"></i> Back to Dashboard
            </a>
            <h5 class="mb-0 ms-3 fw-semibold">Payment Management</h5>
        </div>
    </header>

    <!-- Sub Navigation Tabs -->
    <div class="container mt-4">
        <nav class="nav nav-pills gap-2 bg-white p-2 rounded shadow-sm border">
            <a class="nav-link <?= $status === 'pending' ? 'active bg-primary' : 'text-secondary' ?>" href="?page=pending">Pending</a>
            <a class="nav-link <?= $status === 'reviewing' ? 'active bg-primary' : 'text-secondary' ?>" href="?page=reviewing">Reviewing</a>
            <a class="nav-link <?= $status === 'approved' ? 'active bg-primary' : 'text-secondary' ?>" href="?page=approved">Approved</a>
            <a class="nav-link <?= $status === 'rejected' ? 'active bg-primary' : 'text-secondary' ?>" href="?page=rejected">Rejected</a>
            <a class="nav-link <?= $status === 'cancelled' ? 'active bg-primary' : 'text-secondary' ?>" href="?page=cancelled">Cancelled</a>
        </nav>
    </div>

    <!-- Main Content Area -->
    <main class="container my-4">
        <div class="card shadow-sm border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover table-striped align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-3">User UID</th>
                                <th>Plan</th>
                                <th>Amount</th>
                                <th>Proof of Payment</th>
                                <th>Reference</th>
                                <th>Status / Actions</th>
                                <th class="pe-3">Approved By</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($all_deposits)): ?>
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">No deposits found in this section.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach($all_deposits as $depositor): ?>
                                    <tr>
                                        <!-- Dynamic User UID -->
                                        <td class="ps-3"><code class="small"><?= htmlspecialchars($depositor['user_uid']) ?></code></td>
                                        
                                        <!-- Plan Name -->
                                        <td><span class="badge bg-primary-subtle text-primary border border-primary-subtle"><?= htmlspecialchars($depositor['plan_name']) ?></span></td>
                                        
                                        <!-- Amount -->
                                        <td class="fw-bold">$<?= htmlspecialchars(number_format($depositor['amount'], 2)) ?></td>
                                        
                                        <!-- Proof View Link -->
                                        <td>
                                            <?php if (!empty($depositor['proof_of_payment'])): ?>
                                                <a href="/uploads/deposits/<?= htmlspecialchars($depositor['proof_of_payment']) ?>" target="_blank" class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1">
                                                    <i class="bi bi-eye"></i> View Attachment
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted small">No proof</span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <!-- Reference -->
                                        <td><span class="text-monospace text-muted small"><?= htmlspecialchars($depositor['transaction_reference'] ?? 'N/A') ?></span></td>
                                        
                                        <!-- Status Action Buttons / Badges -->
                                        <td>
                                            <?php if ($depositor['status'] === 'pending'): ?>
                                                <!-- Action triggers for Pending status items -->
                                                <div class="d-flex gap-1">
                                                    <a href="?page=<?= $status ?>&action=approve&id=<?= $depositor['id'] ?>" class="btn btn-sm btn-success py-0 px-2 small shadow-sm">Approve</a>
                                                    <a href="?page=<?= $status ?>&action=reject&id=<?= $depositor['id'] ?>" class="btn btn-sm btn-danger py-0 px-2 small shadow-sm">Reject</a>
                                                </div>
                                            <?php else: ?>
                                                <!-- Fixed labels for processed states -->
                                                <?php 
                                                    $curr_status = strtolower($depositor['status']);
                                                    $badge_color = 'bg-secondary-subtle text-secondary border-secondary-subtle';
                                                    if ($curr_status === 'approved') $badge_color = 'bg-success-subtle text-success border-success-subtle';
                                                    if ($curr_status === 'rejected') $badge_color = 'bg-danger-subtle text-danger border-danger-subtle';
                                                    if ($curr_status === 'cancelled') $badge_color = 'bg-dark-subtle text-dark border-dark-subtle';
                                                ?>
                                                <span class="badge <?= $badge_color ?>"><?= ucfirst(htmlspecialchars($depositor['status'])) ?></span>
                                            <?php endif; ?>
                                        </td>
                                        
                                        <!-- Validator Info -->
                                        <td class="text-muted pe-3"><?= htmlspecialchars($depositor['approved_by'] ?? '—') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
</body>