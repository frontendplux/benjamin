<?php 
include __DIR__."/header.php"; 
$user_uid = $_SESSION['user_id'];

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 6;
$offset = ($page - 1) * $limit;

/*
|--------------------------------------------------------------------------
| Total Notifications Count
|--------------------------------------------------------------------------
*/
$count = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_uid=?");
$count->bind_param("s", $user_uid);
$count->execute();
$count->bind_result($totalRows);
$count->fetch();
$count->close();

$totalPages = max(1, ceil($totalRows / $limit));

/*
|--------------------------------------------------------------------------
| Unread Notifications Count
|--------------------------------------------------------------------------
*/
$unread = $conn->prepare("SELECT COUNT(*) FROM notifications WHERE user_uid=? AND seen=0");
$unread->bind_param("s", $user_uid);
$unread->execute();
$unread->bind_result($unreadCount);
$unread->fetch();
$unread->close();

/*
|--------------------------------------------------------------------------
| Fetch Notifications for Current Page
|--------------------------------------------------------------------------
*/
$get = $conn->prepare("
    SELECT id, title, message, seen, notification_type, created_at
    FROM notifications
    WHERE user_uid=?
    ORDER BY created_at DESC
    LIMIT ? OFFSET ?
");
$get->bind_param("sii", $user_uid, $limit, $offset);
$get->execute();
$result = $get->get_result();

$notifications = [];
$unreadIdsToMark = [];

while ($row = $result->fetch_assoc()) {
    $notifications[] = $row;
    // Collect unread notification IDs displayed on this page
    if ((int)$row['seen'] === 0) {
        $unreadIdsToMark[] = (int)$row['id'];
    }
}

/*
|--------------------------------------------------------------------------
| Auto-Mark Displayed Notifications as Seen
|--------------------------------------------------------------------------
*/
if (!empty($unreadIdsToMark)) {
    $idList = implode(',', array_map('intval', $unreadIdsToMark));
    $conn->query("
        UPDATE notifications 
        SET seen = 1, read_at = CURRENT_TIMESTAMP 
        WHERE id IN ({$idList}) AND user_uid = '{$user_uid}'
    ");
}

/*
|--------------------------------------------------------------------------
| Helpers & Styles
|--------------------------------------------------------------------------
*/
function notificationStyle($type) {
    switch ($type) {
        case "deposit":
            return ["icon" => "bi-wallet2", "badge_bg" => "bg-success-subtle text-success border-success-subtle", "icon_bg" => "bg-success text-white"];
        case "investment":
            return ["icon" => "bi-graph-up-arrow", "badge_bg" => "bg-info-subtle text-info border-info-subtle", "icon_bg" => "bg-info text-white"];
        case "referral":
            return ["icon" => "bi-people-fill", "badge_bg" => "bg-primary-subtle text-primary border-primary-subtle", "icon_bg" => "bg-primary text-white"];
        case "kyc":
            return ["icon" => "bi-shield-check", "badge_bg" => "bg-warning-subtle text-warning border-warning-subtle", "icon_bg" => "bg-warning text-dark"];
        default:
            return ["icon" => "bi-bell-fill", "badge_bg" => "bg-secondary-subtle text-secondary border-secondary-subtle", "icon_bg" => "bg-secondary text-white"];
    }
}

function time_elapsed_string($datetime) {
    $time = time() - strtotime($datetime);
    if ($time < 60) return "Just now";
    if ($time < 3600) return floor($time / 60) . "m ago";
    if ($time < 86400) return floor($time / 3600) . "h ago";
    if ($time < 604800) return floor($time / 86400) . "d ago";
    return date("M d, Y • h:i A", strtotime($datetime));
}
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN NOTIFICATIONS CONTENT -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Breadcrumb Header -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Notification Center</span>
      </div>

      <!-- Main Notification Panel Card -->
      <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
        
        <!-- Header Controls -->
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 pb-3 border-bottom border-light-subtle gap-3">
          <div>
            <h5 class="fw-bold text-dark mb-1">Activity Log & Alerts</h5>
            <p class="text-muted mb-0 small">Real-time system updates, account verification logs, and financial activity records.</p>
          </div>
          
          <button id="mark-all-read" class="btn btn-outline-secondary btn-sm px-3 rounded-pill d-flex align-items-center gap-2 shadow-xs" <?= $unreadCount == 0 ? "disabled" : "" ?>>
            <i class="bi bi-check2-all"></i>
            <span>Mark all read</span>
            <?php if ($unreadCount > 0): ?>
              <span class="badge bg-danger rounded-pill"><?= $unreadCount ?></span>
            <?php endif; ?>
          </button>
        </div>

        <!-- Notification List -->
        <?php if (!empty($notifications)): ?>
          <div class="d-flex flex-column gap-2.5">
            <?php foreach ($notifications as $row): 
              $style = notificationStyle($row['notification_type']);
              $isUnread = ((int)$row['seen'] === 0);
            ?>
              <div class="notification-item p-3 rounded-3  transition-all position-relative <?= $isUnread ? 'bg-success bg-opacity-10 border-success border-opacity-25' : 'bg-white border-light-subtle' ?>" id="notif-card-<?= $row['id'] ?>">
                
                <div class="d-flex align-items-start justify-content-between gap-3">
                  <div class="d-flex gap-3 align-items-start">
                    
                    <!-- Notification Icon -->
                    <div class="<?= $style['icon_bg'] ?> rounded-circle d-flex justify-content-center align-items-center flex-shrink-0 shadow-xs" style="width:42px; height:42px;">
                      <i class="bi <?= $style['icon'] ?> fs-6"></i>
                    </div>

                    <!-- Details -->
                    <div>
                      <div class="d-flex align-items-center gap-2 mb-1 flex-wrap">
                        <h6 class="mb-0 fw-bold text-dark fs-6"><?= htmlspecialchars($row['title']) ?></h6>
                        <span class="badge border rounded-pill text-uppercase font-monospace <?= $style['badge_bg'] ?>" style="font-size: 9px; tracking: 0.5px;">
                          <?= htmlspecialchars($row['notification_type']) ?>
                        </span>
                        <?php if ($isUnread): ?>
                          <span class="badge bg-success rounded-circle p-1" title="Unread">
                            <span class="visually-hidden">Unread</span>
                          </span>
                        <?php endif; ?>
                      </div>

                      <p class="mb-2 text-secondary small lh-base" style="font-size: 13.5px;">
                        <?= nl2br(htmlspecialchars($row['message'])) ?>
                      </p>

                      <span class="text-muted font-monospace" style="font-size: 11px;">
                        <i class="bi bi-clock me-1"></i><?= time_elapsed_string($row['created_at']) ?>
                      </span>
                    </div>

                  </div>

                  <!-- Item Actions -->
                  <div class="d-flex align-items-center gap-1">
                    <button class="btn btn-link text-danger p-1 rounded-circle delete-notification" data-id="<?= $row['id'] ?>" title="Delete Notification">
                      <i class="bi bi-trash fs-6"></i>
                    </button>
                  </div>

                </div>
              </div>
            <?php endforeach; ?>
          </div>

        <?php else: ?>

          <!-- Empty State Container -->
          <div class="text-center py-5 my-3">
            <div class="bg-light rounded-circle d-inline-flex p-4 mb-3 text-muted border border-light-subtle">
              <i class="bi bi-bell-slash display-6"></i>
            </div>
            <h6 class="fw-bold text-dark mb-1">No Notifications Found</h6>
            <p class="text-muted small mb-0">Your activity updates and system notifications will appear here.</p>
          </div>

        <?php endif; ?>

        <!-- Matured & Sleek Pagination Footer -->
        <?php if ($totalRows > 0): ?>
          <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mt-4 pt-3 border-top border-light-subtle gap-3">
            
            <div class="text-muted small font-monospace" style="font-size: 12px;">
              Showing <span class="fw-bold text-dark"><?= min($offset + 1, $totalRows) ?></span> to <span class="fw-bold text-dark"><?= min($offset + $limit, $totalRows) ?></span> of <span class="fw-bold text-dark"><?= $totalRows ?></span> entries
            </div>

            <?php if ($totalPages > 1): ?>
              <nav aria-label="Notification Navigation">
                <ul class="pagination pagination-sm mb-0 gap-1">
                  
                  <!-- Previous Page -->
                  <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                    <a class="page-link rounded-2 px-2 py-1 text-dark border-0 bg-light" href="?page=<?= $page - 1 ?>" aria-label="Previous">
                      <i class="bi bi-chevron-left"></i>
                    </a>
                  </li>

                  <!-- First Page Jump -->
                  <?php if ($page > 3): ?>
                    <li class="page-item">
                      <a class="page-link rounded-2 px-2.5 py-1 text-dark border-0 bg-light fw-medium" href="?page=1">1</a>
                    </li>
                    <li class="page-item disabled"><span class="page-link border-0 bg-transparent text-muted">...</span></li>
                  <?php endif; ?>

                  <!-- Middle Pages -->
                  <?php for ($i = max(1, $page - 1); $i <= min($totalPages, $page + 1); $i++): ?>
                    <li class="page-item <?= $page == $i ? 'active' : '' ?>">
                      <a class="page-link rounded-2 px-3 py-1 border-0 fw-bold <?= $page == $i ? 'bg-success text-white shadow-xs' : 'bg-light text-dark' ?>" href="?page=<?= $i ?>">
                        <?= $i ?>
                      </a>
                    </li>
                  <?php endfor; ?>

                  <!-- Last Page Jump -->
                  <?php if ($page < $totalPages - 2): ?>
                    <li class="page-item disabled"><span class="page-link border-0 bg-transparent text-muted">...</span></li>
                    <li class="page-item">
                      <a class="page-link rounded-2 px-2.5 py-1 text-dark border-0 bg-light fw-medium" href="?page=<?= $totalPages ?>"><?= $totalPages ?></a>
                    </li>
                  <?php endif; ?>

                  <!-- Next Page -->
                  <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                    <a class="page-link rounded-2 px-2 py-1 text-dark border-0 bg-light" href="?page=<?= $page + 1 ?>" aria-label="Next">
                      <i class="bi bi-chevron-right"></i>
                    </a>
                  </li>

                </ul>
              </nav>
            <?php endif; ?>

          </div>
        <?php endif; ?>

      </div>
    </div>
  </div>
</div>

<!-- Inline Hover & Utility Micro-styles -->
<style>
.transition-all { transition: all 0.2s ease-in-out; }
.hover-danger:hover { color: #dc3545 !important; background-color: rgba(220, 53, 69, 0.1); }
.shadow-xs { box-shadow: 0 1px 3px rgba(0,0,0,0.05); }
.p-3\.5 { padding: 0.95rem 1.15rem; }
</style>

<!-- Client Interactivity JS -->
<script>
document.getElementById("mark-all-read")?.addEventListener("click", async () => {
  try {
    const response = await fetch("<?= $company_info['main-server'] ?>", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ action: "/member/notification/read-all" })
    });
    const data = await response.json();
    if (data.success) {
      location.reload();
    }
  } catch (err) {
    console.error("Failed marking all read:", err);
  }
});

document.querySelectorAll(".delete-notification").forEach(button => {
  button.onclick = async () => {
    const notifId = button.dataset.id;
    const ask = await Swal.fire({
      title: "Remove Alert?",
      text: "This entry will be permanently deleted from your logs.",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#dc3545",
      confirmButtonText: "Yes, delete",
      cancelButtonText: "Cancel"
    });

    if (!ask.isConfirmed) return;

    try {
      const response = await fetch("<?= $company_info['main-server'] ?>", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({
          action: "/member/notification/delete",
          id: notifId
        })
      });

      const data = await response.json();
      if (data.success) {
        const card = document.getElementById(`notif-card-${notifId}`);
        if (card) {
          card.style.opacity = '0';
          card.style.transform = 'translateY(-10px)';
          setTimeout(() => card.remove(), 200);
        }
      }
    } catch (err) {
      console.error("Failed to delete notification:", err);
    }
  };
});
</script>