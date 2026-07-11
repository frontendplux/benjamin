<?php
/**
 * upload.php
 * Handles a single file upload (image or video) sent via AJAX/XHR
 * from index.html. Returns a JSON response.
 */

header('Content-Type: application/json');

// ---- Configuration ----
$uploadDir       = __DIR__ . '/uploads/';          // folder to store files
$maxFileSize     = 10000 * 1024 * 1024;               // 50 MB max
$allowedImageExt = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp'];
$allowedVideoExt = ['mp4', 'mov', 'avi', 'mkv', 'webm', 'wmv'];
$allowedExt      = array_merge($allowedImageExt, $allowedVideoExt);

$allowedMimeTypes = [
    // images
    'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/bmp',
    // videos
    'video/mp4', 'video/quicktime', 'video/x-msvideo', 'video/x-matroska',
    'video/webm', 'video/x-ms-wmv'
];

function respond($success, $message, $extra = []) {
    echo json_encode(array_merge([
        'success' => $success,
        'message' => $message
    ], $extra));
    exit;
}

// ---- Basic checks ----
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    respond(false, 'Invalid request method.');
}

if (!isset($_FILES['file'])) {
    respond(false, 'No file received.');
}

$file = $_FILES['file'];

// ---- Handle upload errors ----
if ($file['error'] !== UPLOAD_ERR_OK) {
    $errors = [
        UPLOAD_ERR_INI_SIZE   => 'File exceeds server max size limit.',
        UPLOAD_ERR_FORM_SIZE  => 'File exceeds form max size limit.',
        UPLOAD_ERR_PARTIAL    => 'File was only partially uploaded.',
        UPLOAD_ERR_NO_FILE    => 'No file was uploaded.',
        UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder on server.',
        UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
        UPLOAD_ERR_EXTENSION  => 'Upload stopped by a PHP extension.',
    ];
    respond(false, $errors[$file['error']] ?? 'Unknown upload error.');
}

// ---- Size check ----
if ($file['size'] > $maxFileSize) {
    respond(false, 'File is too large. Max allowed size is ' . ($maxFileSize / 1024 / 1024) . 'MB.');
}

// ---- Extension check ----
$originalName = basename($file['name']);
$ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

if (!in_array($ext, $allowedExt)) {
    respond(false, 'File type not allowed: .' . $ext);
}

// ---- MIME type check (extra safety, don't trust extension alone) ----
$finfo = finfo_open(FILEINFO_MIME_TYPE);
$mimeType = finfo_file($finfo, $file['tmp_name']);
finfo_close($finfo);

if (!in_array($mimeType, $allowedMimeTypes)) {
    respond(false, 'Detected file type not allowed: ' . $mimeType);
}

// ---- Ensure upload directory exists ----
if (!is_dir($uploadDir)) {
    if (!mkdir($uploadDir, 0755, true)) {
        respond(false, 'Could not create upload directory.');
    }
}

// ---- Generate a safe, unique filename ----
$safeBaseName = preg_replace('/[^A-Za-z0-9_\-]/', '_', pathinfo($originalName, PATHINFO_FILENAME));
$uniqueName   = $safeBaseName . '_' . uniqid() . '.' . $ext;
$destination  = $uploadDir . $uniqueName;

// ---- Move the uploaded file ----
if (!move_uploaded_file($file['tmp_name'], $destination)) {
    respond(false, 'Failed to move uploaded file to destination.');
}

// ---- Success ----
$type = in_array($ext, $allowedImageExt) ? 'image' : 'video';

respond(true, 'File uploaded successfully.', [
    'filename' => $uniqueName,
    'original' => $originalName,
    'type'     => $type,
    'size'     => $file['size'],
    'url'      => 'uploads/' . $uniqueName
]);