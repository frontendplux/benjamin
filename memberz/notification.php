<?php 
include __DIR__."/header.php"; 
$user_uid = $_SESSION['user_id'];

$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 2;
$offset = ($page - 1) * $limit;

/*
|--------------------------------------------------------------------------
| Total Notifications
|--------------------------------------------------------------------------
*/

$count = $conn->prepare("
SELECT COUNT(*)
FROM notifications
WHERE user_uid=?
");

$count->bind_param("s",$user_uid);
$count->execute();
$count->bind_result($totalRows);
$count->fetch();
$count->close();

$totalPages = max(1, ceil($totalRows / $limit));

$unread = $conn->prepare("
SELECT COUNT(*)
FROM notifications
WHERE user_uid=?
AND seen=0
");

$unread->bind_param("s",$user_uid);
$unread->execute();
$unread->bind_result($unreadCount);
$unread->fetch();
$unread->close();

/*
|--------------------------------------------------------------------------
| Fetch Notifications
|--------------------------------------------------------------------------
*/

$get = $conn->prepare("
SELECT
id,
title,
message,
seen,
notification_type,
created_at
FROM notifications
WHERE user_uid=?
ORDER BY created_at DESC
LIMIT ?
OFFSET ?
");

$get->bind_param(
"sii",
$user_uid,
$limit,
$offset
);

$get->execute();

$notifications = $get->get_result();

function notificationStyle($type){

    switch($type){

        case "deposit":
            return [
                "icon"=>"bi-wallet2",
                "bg"=>"success",
                "title"=>"text-success"
            ];

        case "investment":
            return [
                "icon"=>"bi-graph-up-arrow",
                "bg"=>"info",
                "title"=>"text-info"
            ];

        case "referral":
            return [
                "icon"=>"bi-people-fill",
                "bg"=>"primary",
                "title"=>"text-primary"
            ];

        case "kyc":
            return [
                "icon"=>"bi-shield-check",
                "bg"=>"warning",
                "title"=>"text-warning"
            ];

        default:

            return [
                "icon"=>"bi-bell",
                "bg"=>"secondary",
                "title"=>"text-secondary"
            ];

    }

}

function time_elapsed_string($datetime){

$time = time() - strtotime($datetime);

if($time < 60)
return $time." sec ago";

if($time < 3600)
return floor($time/60)." min ago";

if($time < 86400)
return floor($time/3600)." hrs ago";

if($time < 604800)
return floor($time/86400)." days ago";

return date("d M Y h:i A",strtotime($datetime));

}
?>

<!-- SYSTEM CONTAINER FRAME -->
<div class="container bg-light p-0 h-100 mt-2" style="font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
  <div class="row g-0 h-100">
    <!-- LEFT SIDEBAR: Standard Structural Navigation Include -->
    <?php include __DIR__."/aside.php"; ?>

    <!-- MAIN NOTIFICATIONS WINDOW AREA -->
    <div class="col-md-9 col-xl-10 p-3 p-md-4">
      
      <!-- Top Breadcrumb Section -->
      <div class="d-flex align-items-center gap-2 small mb-4 <?= $theme === 'dark' ? 'text-light' : 'text-muted' ?>">
        <i class="bi bi-house-door"></i> 
        <span>Dashboard</span> 
        <i class="bi bi-chevron-right" style="font-size: 10px;"></i> 
        <span class="text-success fw-bold">Notifications</span>
      </div>

      <!-- Main Notification Panel Container -->
      <div class="card border-0 shadow-sm bg-white rounded-4 p-4">
        <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom border-light-subtle">
          <div>
            <h5 class="fw-bold text-dark mb-1">Notification Center</h5>
            <p class="text-muted mb-0 small">Stay updated on your institutional profile activity, system updates, and security logs.</p>
          </div>
         <button
id="mark-all-read"
class="btn btn-light btn-sm border"
<?= $unreadCount==0 ? "disabled" : "" ?>>

Mark all as read

<?php if($unreadCount): ?>

<span class="badge bg-danger ms-2">

<?= $unreadCount ?>

</span>

<?php endif; ?>

</button>
        </div>

        <!-- Notification Feed Stack -->
        <div class="d-flex flex-column gap-3">

          <!-- Security Alert Item (Unread State) -->
          <?php if($notifications->num_rows): ?>

<div class="d-flex flex-column gap-3">

<?php while($row=$notifications->fetch_assoc()):

$style = notificationStyle($row['notification_type']);

?>

<div class="p-3 rounded-4 border <?= $row['seen'] ? 'border-light-subtle bg-light' : 'border-success bg-success bg-opacity-10' ?> d-flex justify-content-between">

<div class="d-flex gap-3">

<div class="bg-<?= $style['bg'] ?> bg-opacity-10 text-<?= $style['bg'] ?> rounded-circle d-flex justify-content-center align-items-center"

style="width:45px;height:45px;">

<i class="bi <?= $style['icon'] ?>"></i>

</div>

<div>

<h6 class="mb-1 fw-bold">

<?= htmlspecialchars($row['title']) ?>

</h6>

<p class="mb-2 text-muted small">

<?= nl2br(htmlspecialchars($row['message'])) ?>

</p>

<small class="text-muted">

<?= time_elapsed_string($row['created_at']) ?>

</small>

</div>

</div>

<div class="d-flex flex-column gap-2">

<?php if(!$row['seen']): ?>

<button
class="btn btn-success btn-sm mark-read"
data-id="<?= $row['id'] ?>">

Mark Read

</button>

<?php endif; ?>

<button
class="btn btn-outline-danger btn-sm delete-notification"
data-id="<?= $row['id'] ?>">

Delete

</button>

</div>

</div>

<?php endwhile; ?>

</div>

<?php else: ?>

<div class="text-center py-5">

<i class="bi bi-bell display-4 text-muted"></i>

<h5 class="mt-3">

No Notifications

</h5>

<p class="text-muted">

We'll notify you when something important happens.

</p>

</div>

<?php endif; ?>

        </div>

        <!-- Pagination Footer -->
        <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">

<span class="small text-muted">

Showing

<strong>

<?= min($offset+1,$totalRows) ?>

</strong>

-

<strong>

<?= min($offset+$limit,$totalRows) ?>

</strong>

of

<strong>

<?= $totalRows ?>

</strong>

notifications

</span>

<?php if($totalPages>1): ?>

<ul class="pagination pagination-sm mb-0">

<li class="page-item <?= $page==1?'disabled':'' ?>">

<a class="page-link"

href="?page=<?= $page-1 ?>">

<i class="bi bi-chevron-left"></i>

</a>

</li>

<?php for($i=1;$i<=$totalPages;$i++): ?>

<li class="page-item <?= $page==$i?'active':'' ?>">

<a class="page-link"

href="?page=<?= $i ?>">

<?= $i ?>

</a>

</li>

<?php endfor; ?>

<li class="page-item <?= $page==$totalPages?'disabled':'' ?>">

<a class="page-link"

href="?page=<?= $page+1 ?>">

<i class="bi bi-chevron-right"></i>

</a>

</li>

</ul>

<?php endif; ?>

</div>

      </div>
    </div>
  </div>
</div>

<script>
  document.querySelectorAll(".mark-read").forEach(button=>{

button.addEventListener("click",async()=>{

const response = await fetch(
"<?= $company_info['main-server'] ?>",
{
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({
action:"/member/notification/read",
id:button.dataset.id
})
});

const data=await response.json();

if(data.success){

location.reload();

}

});

});

document.getElementById("mark-all-read")?.addEventListener("click",async()=>{

const response = await fetch(
"<?= $company_info['main-server'] ?>",
{
method:"POST",
headers:{
"Content-Type":"application/json"
},
body:JSON.stringify({
action:"/member/notification/read-all"
})
});

const data=await response.json();

if(data.success){

location.reload();

}

});
</script>

<script>
  document.querySelectorAll(".delete-notification").forEach(button=>{

button.onclick=async()=>{

const ask=await Swal.fire({

title:"Delete Notification?",

text:"This cannot be undone.",

icon:"warning",

showCancelButton:true

});

if(!ask.isConfirmed) return;

const response=await fetch("<?= $company_info['main-server'] ?>",{
  method:"POST",
  headers:{
  "Content-Type":"application/json"
  },
  body:JSON.stringify({
  action:"/member/notification/delete",
  id:button.dataset.id
})

});

const data=await response.json();

if(data.success){

button.closest(".p-3").remove();

}

};

});
</script>



<!-- <a href="/notifications" class="position-relative">

<i class="bi bi-bell fs-5"></i>

<?php if($unreadCount): ?>

<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">

<?= $unreadCount ?>

</span>

<?php endif; ?>

</a> -->