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

case "/admin/process-withdrawal":

        // 1. Session Auth Protection Check
        $session_uid   = $_SESSION['admin_uid'] ?? '';
        $session_token = $_SESSION['admin_token'] ?? '';

        if (empty($session_uid) || empty($session_token)) {
            echo json_encode(["success" => false, "message" => "Unauthorized access encounter."]);
            exit;
        }

        $auth_check = $conn->prepare("SELECT id FROM admin WHERE uid = ? AND token = ? AND is_active = 1 LIMIT 1");
        $auth_check->bind_param("ss", $session_uid, $session_token);
        $auth_check->execute();
        if ($auth_check->get_result()->num_rows === 0) {
            echo json_encode(["success" => false, "message" => "Session invalid or expired."]);
            exit;
        }

        // 2. Extract Processing Payload Parameters
        $withdrawal_uid = trim($data['withdrawal_uid'] ?? '');
        $decision       = trim($data['decision'] ?? ''); // 'approve' or 'reject'
        $note           = trim($data['note'] ?? '');

        if (empty($withdrawal_uid) || !in_array($decision, ['approve', 'reject'])) {
            echo json_encode(["success" => false, "message" => "Required context variables missing."]);
            exit;
        }

        // 3. Begin Atomic Transaction Thread
        $conn->begin_transaction();

        try {
            // Fetch targets with complete structural row locks
            $wd_stmt = $conn->prepare("SELECT * FROM withdrawals WHERE withdrawal_uid = ? FOR UPDATE");
            $wd_stmt->bind_param("s", $withdrawal_uid);
            $wd_stmt->execute();
            $withdrawal = $wd_stmt->get_result()->fetch_assoc();

            if (!$withdrawal) {
                throw new Exception("Withdrawal document record not identified.");
            }

            if (!in_array($withdrawal['status'], ['pending', 'processing'])) {
                throw new Exception("This entry transaction pipeline is already finalized.");
            }

            $user_uid = $withdrawal['user_uid'];
            $amount   = (float)$withdrawal['amount'];
            $notif_type = 'withdrawal'; // Custom label map parameter context

            if ($decision === 'approve') {
                // Generate ledger transaction entry confirming release
                $tx_uid       = "TXW" . strtoupper(bin2hex(random_bytes(8)));
                $tx_type      = 'withdrawal';
                $tx_direction = 'debit';
                $tx_status    = 'success';
                $tx_desc      = "Withdrawal approved & sent. Target Destination: " . $withdrawal['wallet_address'];

                $tx_stmt = $conn->prepare("
                    INSERT INTO transactions 
                    (transaction_uid, user_uid, type, reference_id, amount, direction, status, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $tx_stmt->bind_param("ssssdsss", $tx_uid, $user_uid, $tx_type, $withdrawal_uid, $amount, $tx_direction, $tx_status, $tx_desc);
                $tx_stmt->execute();

                // Update withdrawal status to 'approved'
                $final_stmt = $conn->prepare("UPDATE withdrawals SET status = 'approved', transaction_hash = ?, approved_at = NOW() WHERE withdrawal_uid = ?");
                $final_stmt->bind_param("ss", $note, $withdrawal_uid);
                $final_stmt->execute();

                // Generate User Notification entry tracking exact table properties
                $notif_title = "Withdrawal Successful";
                $notif_msg   = "Your withdrawal request for $" . number_format($amount, 2) . " has been approved. Reference Hash: " . $note;
                
                // Matches your exact system: user_uid, title, message, seen, notification_type
                $notif_stmt  = $conn->prepare("INSERT INTO notifications (user_uid, title, message, seen, notification_type, created_at) VALUES (?, ?, ?, 0, 'system', NOW())");
                $notif_stmt->bind_param("sss", $user_uid, $notif_title, $notif_msg);
                $notif_stmt->execute();

                $response_msg = "Withdrawal request has been verified and processed.";
            } else {
                // Handle Rejection scenario (Requires a refund since funds were debited upfront)
                if (empty($note)) {
                    throw new Exception("Rejection operations require an administrative note explanation.");
                }

                // Refund the capital allocation back to the user's wallet table 'user_wallet'
                $refund_stmt = $conn->prepare("UPDATE user_wallet SET wallet_balance = wallet_balance + ? WHERE user_uid = ?");
                $refund_stmt->bind_param("ds", $amount, $user_uid);
                $refund_stmt->execute();

                // Generate ledger transaction entry tracking the refund credit
                $tx_uid       = "TXR" . strtoupper(bin2hex(random_bytes(8)));
                $tx_type      = 'withdrawal'; 
                $tx_direction = 'credit';
                $tx_status    = 'failed'; 
                $tx_desc      = "Withdrawal rejected - Funds Refunded. Reason: " . $note;

                $tx_stmt = $conn->prepare("
                    INSERT INTO transactions 
                    (transaction_uid, user_uid, type, reference_id, amount, direction, status, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $tx_stmt->bind_param("ssssdsss", $tx_uid, $user_uid, $tx_type, $withdrawal_uid, $amount, $tx_direction, $tx_status, $tx_desc);
                $tx_stmt->execute();

                // Mark withdrawal row status as 'rejected'
                $final_stmt = $conn->prepare("UPDATE withdrawals SET status = 'rejected', admin_note = ? WHERE withdrawal_uid = ?");
                $final_stmt->bind_param("ss", $note, $withdrawal_uid);
                $final_stmt->execute();

                // Generate User Notification entry for Rejection tracking system status definitions
                $notif_title = "Withdrawal Rejected";
                $notif_msg   = "Your withdrawal request for $" . number_format($amount, 2) . " was rejected. Reason: " . $note . ". Funds have been returned to your wallet.";
                
                $notif_stmt  = $conn->prepare("INSERT INTO notifications (user_uid, title, message, seen, notification_type, created_at) VALUES (?, ?, ?, 0, 'system', NOW())");
                $notif_stmt->bind_param("sss", $user_uid, $notif_title, $notif_msg);
                $notif_stmt->execute();

                $response_msg = "Withdrawal request rejected and funds successfully refunded.";
            }

            $conn->commit();
            echo json_encode(["success" => true, "message" => $response_msg]);
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
            exit;
        }
    break;

    case "/admin/process-loan":
        // 1. Structural Session Security Integrity Handshake Check
        $session_uid   = $_SESSION['admin_uid'] ?? '';
        $session_token = $_SESSION['admin_token'] ?? '';

        if (empty($session_uid) || empty($session_token)) {
            echo json_encode(["success" => false, "message" => "Unauthorized access attempt."]);
            exit;
        }

        $auth_check = $conn->prepare("SELECT id FROM admin WHERE uid = ? AND token = ? AND is_active = 1 LIMIT 1");
        $auth_check->bind_param("ss", $session_uid, $session_token);
        $auth_check->execute();
        if ($auth_check->get_result()->num_rows === 0) {
            echo json_encode(["success" => false, "message" => "Invalid execution credentials context."]);
            exit;
        }

        // 2. Extract Processing Values
        $loan_uid = trim($data['loan_uid'] ?? '');
        $decision = trim($data['decision'] ?? ''); // 'approve' or 'reject'
        $note     = trim($data['note'] ?? '');

        if (empty($loan_uid) || !in_array($decision, ['approve', 'reject'])) {
            echo json_encode(["success" => false, "message" => "Missing required workflow values."]);
            exit;
        }

        // 3. Begin Atomic Isolation Transaction
        $conn->begin_transaction();

        try {
            // Fetch target application entry with write database row lock
            $loan_stmt = $conn->prepare("SELECT * FROM loans WHERE loan_uid = ? FOR UPDATE");
            $loan_stmt->bind_param("s", $loan_uid);
            $loan_stmt->execute();
            $loan = $loan_stmt->get_result()->fetch_assoc();

            if (!$loan) {
                throw new Exception("Target loan instance data records not found.");
            }

            if ($loan['status'] !== 'pending') {
                throw new Exception("This loan request has already been finalized.");
            }

            $user_uid = $loan['user_uid'];
            $amount   = (float)$loan['amount'];

            if ($decision === 'approve') {
                // 1. Update user balance pool inside 'user_wallet'
                $credit_stmt = $conn->prepare("UPDATE user_wallet SET wallet_balance = wallet_balance + ? WHERE user_uid = ?");
                $credit_stmt->bind_param("ds", $amount, $user_uid);
                $credit_stmt->execute();

                // 2. Generate systematic transaction statement record tracking trace
                $tx_uid       = "TXL" . strtoupper(bin2hex(random_bytes(8)));
                $tx_type      = 'loan';
                $tx_direction = 'credit';
                $tx_status    = 'success';
                $tx_desc      = "Approved Loan Disbursement. Term: " . ($loan['duration'] ?? 'Standard');

                $tx_stmt = $conn->prepare("
                    INSERT INTO transactions 
                    (transaction_uid, user_uid, type, reference_id, amount, direction, status, description) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ");
                $tx_stmt->bind_param("ssssdsss", $tx_uid, $user_uid, $tx_type, $loan_uid, $amount, $tx_direction, $tx_status, $tx_desc);
                $tx_stmt->execute();

                // 3. Update master structural table parameters state
                $final_stmt = $conn->prepare("UPDATE loans SET status = 'approved', admin_note = ?, approved_at = NOW() WHERE loan_uid = ?");
                $final_stmt->bind_param("ss", $note, $loan_uid);
                $final_stmt->execute();

                // 4. Fire notifications pipeline table write mapping seen context
                $notif_title = "Loan Application Approved";
                $notif_msg   = "Congratulations! Your loan application for $" . number_format($amount, 2) . " has been approved. The funds have been added directly to your account balance.";
                
                $notif_stmt  = $conn->prepare("INSERT INTO notifications (user_uid, title, message, seen, notification_type, created_at) VALUES (?, ?, ?, 0, 'loan', NOW())");
                $notif_stmt->bind_param("sss", $user_uid, $notif_title, $notif_msg);
                $notif_stmt->execute();

                $response_msg = "Loan application approved and funds disbursed.";
            } else {
                // Handle loan rejection route logic scenario
                if (empty($note)) {
                    throw new Exception("Rejection requires an administrative comment explanation.");
                }

                // Update application execution status to rejected
                $final_stmt = $conn->prepare("UPDATE loans SET status = 'rejected', admin_note = ? WHERE loan_uid = ?");
                $final_stmt->bind_param("ss", $note, $loan_uid);
                $final_stmt->execute();

                // Fire notification alerting applicant to failure tracking reasons
                $notif_title = "Loan Application Rejected";
                $notif_msg   = "Your loan request for $" . number_format($amount, 2) . " was rejected. Reason: " . $note;
                
                $notif_stmt  = $conn->prepare("INSERT INTO notifications (user_uid, title, message, seen, notification_type, created_at) VALUES (?, ?, ?, 0, 'loan', NOW())");
                $notif_stmt->bind_param("sss", $user_uid, $notif_title, $notif_msg);
                $notif_stmt->execute();

                $response_msg = "Loan application has been rejected.";
            }

            $conn->commit();
            echo json_encode(["success" => true, "message" => $response_msg]);
            exit;

        } catch (Exception $e) {
            $conn->rollback();
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
            exit;
        }
        break;

    default:

        echo json_encode([
            "success" => false,
            "message" => "Invalid request."
        ]);
        exit;
}