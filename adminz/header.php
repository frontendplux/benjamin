<!DOCTYPE html>
<html lang="en">
<head>
<?php
// Check if system has been initialized
$checkAdmin = $conn->prepare("
    SELECT id 
    FROM admin 
    LIMIT 1
");
$checkAdmin->execute();
$result = $checkAdmin->get_result();
if ($result->num_rows === 0) {
    // No admin installed yet
    header("Location: /installation");
    exit;
}
?>

<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}


// Check installation status
$checkAdmin = $conn->prepare("
    SELECT id 
    FROM admin 
    LIMIT 1
");

$checkAdmin->execute();

$adminExists = $checkAdmin
    ->get_result()
    ->num_rows > 0;


// Redirect to installation
if (!$adminExists) {

    header("Location: /admin");
    exit;

}


// Check login session
if (
    empty($_SESSION['admin_uid']) ||
    empty($_SESSION['admin_token'])
) {

    header("Location: /admin/login");
    exit;

}


// Validate session token
$verify = $conn->prepare("
    SELECT id
    FROM admin
    WHERE uid = ?
    AND token = ?
    AND is_active = 1
    LIMIT 1
");

$verify->bind_param(
    "ss",
    $_SESSION['admin_uid'],
    $_SESSION['admin_token']
);

$verify->execute();

if ($verify->get_result()->num_rows === 0) {

    session_destroy();

    header("Location: /admin/login");
    exit;

}

?>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title><?= $company_info['title'] ?></title>
</head>