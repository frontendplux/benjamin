<?php

header('Content-Type: application/json');

require __DIR__ . '/function.php'; // Your database connection

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON data."
    ]);
    exit;
}

if (
    empty($data['endpoint']) ||
    empty($data['keys']['p256dh']) ||
    empty($data['keys']['auth'])
) {
    echo json_encode([
        "success" => false,
        "message" => "Missing subscription data."
    ]);
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO push_subscriptions
    (endpoint, p256dh, auth, user_agent)
    VALUES (?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        p256dh = VALUES(p256dh),
        auth = VALUES(auth),
        user_agent = VALUES(user_agent)
");

if (!$stmt) {
    echo json_encode([
        "success" => false,
        "message" => $conn->error
    ]);
    exit;
}

$userAgent = $_SERVER['HTTP_USER_AGENT'];

$stmt->bind_param(
    "ssss",
    $data['endpoint'],
    $data['keys']['p256dh'],
    $data['keys']['auth'],
    $userAgent
);

if ($stmt->execute()) {

    echo json_encode([
        "success" => true,
        "message" => "Subscription saved successfully."
    ]);

} else {

    echo json_encode([
        "success" => false,
        "message" => $stmt->error
    ]);

}

$stmt->close();