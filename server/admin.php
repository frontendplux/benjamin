<?php
include __DIR__ . "/conn.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$data = json_decode(file_get_contents("php://input"), true);

if (!$data) {
    $data = $_POST;
}

$action = trim($data['action'] ?? '');

switch ($action) {

    case "/admin/installation":

        $email        = strtolower(trim($data['admin_email'] ?? ''));
        $password     = trim($data['admin_password'] ?? '');
        $confirm      = trim($data['admin_password_confirm'] ?? '');
        $securityCode = trim($data['app_security_code'] ?? '');

        if (
            empty($email) ||
            empty($password) ||
            empty($confirm) ||
            empty($securityCode)
        ) {
            echo json_encode([
                "success" => false,
                "message" => "Please complete all required fields."
            ]);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo json_encode([
                "success" => false,
                "message" => "Invalid email address."
            ]);
            exit;
        }

        if ($password !== $confirm) {
            echo json_encode([
                "success" => false,
                "message" => "Passwords do not match."
            ]);
            exit;
        }

        if (strlen($password) < 8) {
            echo json_encode([
                "success" => false,
                "message" => "Password must be at least 8 characters."
            ]);
            exit;
        }

        if (strlen($securityCode) < 8) {
            echo json_encode([
                "success" => false,
                "message" => "App Security Code must be at least 8 characters."
            ]);
            exit;
        }

        // Check if installation already exists
        $check = $conn->prepare("SELECT id FROM admin LIMIT 1");
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {

            echo json_encode([
                "success" => false,
                "message" => "System has already been initialized."
            ]);
            exit;
        }

        $uid   = "ADM-" . strtoupper(bin2hex(random_bytes(5)));
        $token = bin2hex(random_bytes(32));

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);
        $appHash      = password_hash($securityCode, PASSWORD_DEFAULT);

        $isActive = 1;
        $isMain   = 1;

        $insert = $conn->prepare("
            INSERT INTO admin
            (
                uid,
                token,
                email,
                password,
                app_password,
                is_active,
                is_main_admin
            )
            VALUES
            (
                ?,?,?,?,?,?,?
            )
        ");

        $insert->bind_param(
            "sssssii",
            $uid,
            $token,
            $email,
            $passwordHash,
            $appHash,
            $isActive,
            $isMain
        );

        if (!$insert->execute()) {

            echo json_encode([
                "success" => false,
                "message" => "Unable to initialize the system."
            ]);

            exit;
        }

        $_SESSION["admin_uid"] = $uid;
        $_SESSION["admin_token"] = $token;

        echo json_encode([
            "success" => true,
            "message" => "System initialized successfully.",
            "redirect" => "./admin"
        ]);

        exit;

    case "/admin/login":

    $email = strtolower(trim($data['email'] ?? ''));
    $password = trim($data['password'] ?? '');

    if (empty($email) || empty($password)) {

        echo json_encode([
            "success" => false,
            "message" => "Email and password are required."
        ]);

        exit;
    }

    $stmt = $conn->prepare("
        SELECT
            uid,
            token,
            email,
            password,
            is_active
        FROM admin
        WHERE email=?
        LIMIT 1
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid login credentials."
        ]);

        exit;
    }

    $admin = $result->fetch_assoc();

    if (!$admin['is_active']) {

        echo json_encode([
            "success" => false,
            "message" => "Administrator account is disabled."
        ]);

        exit;
    }

    if (!password_verify($password, $admin['password'])) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid login credentials."
        ]);

        exit;
    }

    $newToken = bin2hex(random_bytes(32));

    $update = $conn->prepare("
        UPDATE admin
        SET token=?
        WHERE uid=?
    ");

    $update->bind_param(
        "ss",
        $newToken,
        $admin['uid']
    );

    $update->execute();

    $_SESSION['admin_uid'] = $admin['uid'];
    $_SESSION['admin_token'] = $newToken;
    $_SESSION['admin_email'] = $admin['email'];

    echo json_encode([
        "success" => true,
        "message" => "Login successful."
    ]);

    exit;


    case "/admin/reset-password":

    $appPassword = trim($data['app_password'] ?? '');
    $newPassword = trim($data['password'] ?? '');

    if (empty($appPassword) || empty($newPassword)) {

        echo json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]);

        exit;
    }

    if (strlen($newPassword) < 8) {

        echo json_encode([
            "success" => false,
            "message" => "Password must be at least 8 characters."
        ]);

        exit;
    }

    $stmt = $conn->prepare("
        SELECT
            uid,
            app_password
        FROM admin
        WHERE is_main_admin=1
        LIMIT 1
    ");

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 0) {

        echo json_encode([
            "success" => false,
            "message" => "Administrator account not found."
        ]);

        exit;
    }

    $admin = $result->fetch_assoc();

    if (!password_verify($appPassword, $admin['app_password'])) {

        echo json_encode([
            "success" => false,
            "message" => "Invalid App Security Code."
        ]);

        exit;
    }

    $passwordHash = password_hash(
        $newPassword,
        PASSWORD_DEFAULT
    );

    $token = bin2hex(random_bytes(32));

    $update = $conn->prepare("
        UPDATE admin
        SET
            password=?,
            token=?
        WHERE uid=?
    ");

    $update->bind_param(
        "sss",
        $passwordHash,
        $token,
        $admin['uid']
    );

    if (!$update->execute()) {

        echo json_encode([
            "success" => false,
            "message" => "Unable to reset password."
        ]);

        exit;
    }

    echo json_encode([
        "success" => true,
        "message" => "Password has been updated successfully."
    ]);

    exit;


    default:

        echo json_encode([
            "success" => false,
            "message" => "Invalid request."
        ]);
        exit;
}