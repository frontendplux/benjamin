<?php
include __DIR__ . "/conn.php";
include __DIR__."/mailer.php";

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$data = json_decode(file_get_contents("php://input"), true);
if (!$data) {
    $data = $_POST;
}
$action = $data['action'] ?? '';
switch ($action) {
    case '/register':
    $firstname = trim($data['firstname'] ?? '');
    $lastname  = trim($data['lastname'] ?? '');
    $email     = strtolower(trim($data['email'] ?? ''));
    $phone     = trim($data['phone'] ?? '');
    $country   = trim($data['country'] ?? '');
    $password  = $data['password'] ?? '';
    $confirm   = $data['confirm_password'] ?? '';
    $referral  = trim($data['referral_code'] ?? '');

    // Validation
    if (empty($firstname) || empty($lastname) || empty($email) || empty($phone) || empty($country) || empty($password)) {
        exit(json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]));
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        exit(json_encode([
            "success" => false,
            "message" => "Invalid email address."
        ]));
    }

    if (strlen($password) < 8) {
        exit(json_encode([
            "success" => false,
            "message" => "Password must be at least 8 characters."
        ]));
    }

    if ($password !== $confirm) {
        exit(json_encode([
            "success" => false,
            "message" => "Passwords do not match."
        ]));
    }

    // Email exists?
    $check = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $check->bind_param("s", $email);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        exit(json_encode([
            "success" => false,
            "message" => "Email already exists."
        ]));
    }

    // Phone exists?
    $check = $conn->prepare("SELECT id FROM users WHERE phone=? LIMIT 1");
    $check->bind_param("s", $phone);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        exit(json_encode([
            "success" => false,
            "message" => "Phone number already exists."
        ]));
    }

    do {
        $uid = "INV-" .
            strtoupper(substr(bin2hex(random_bytes(4)), 0, 8)) .
            strtoupper(substr(bin2hex(random_bytes(2)), 0, 4));

        $stmt = $conn->prepare("SELECT id FROM users WHERE uid=? LIMIT 1");
        $stmt->bind_param("s", $uid);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);

    do {
        $token = bin2hex(random_bytes(32));

        $stmt = $conn->prepare("SELECT id FROM users WHERE token=? LIMIT 1");
        $stmt->bind_param("s", $token);
        $stmt->execute();
        $stmt->store_result();
    } while ($stmt->num_rows > 0);

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $insert = $conn->prepare("
        INSERT INTO users
        (
            uid,
            token,
            first_name,
            last_name,
            email,
            phone,
            country,
            password
        )
        VALUES
        (?,?,?,?,?,?,?,?)
    ");

    $insert->bind_param(
        "ssssssss",
        $uid,
        $token,
        $firstname,
        $lastname,
        $email,
        $phone,
        $country,
        $hashed_password
    );

    if (!$insert->execute()) {
        exit(json_encode([
            "success" => false,
            "message" => "Unable to create account."
        ]));
    }

    if (!empty($referral)) {
        $ref = $conn->prepare("SELECT uid FROM users WHERE uid=? LIMIT 1");
        $ref->bind_param("s", $referral);
        $ref->execute();
        $result = $ref->get_result();

        if ($result->num_rows > 0) {
            $save = $conn->prepare("
                INSERT INTO referrals
                (
                    uid,
                    referred_by
                )
                VALUES
                (?,?)
            ");

            $save->bind_param(
                "ss",
                $uid,
                $referral
            );

            $save->execute();
        }
    }

    $_SESSION['user_id'] = $uid;
    $_SESSION['token'] = $token;

    // Send Welcome Email
    $fullName = trim($firstname . ' ' . $lastname);
    // $subject  = "Welcome aboard, " . htmlspecialchars($firstname) . "!";
    $subject="Dear Valued Investor";
    $emailBody = '
    <!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Welcome</title>
      <style>
        body { margin: 0; padding: 0; background-color: #f4f6f9; font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background-color: #1a73e8; padding: 30px; text-align: center; color: #ffffff; }
        .header h1 { margin: 0; font-size: 24px; font-weight: 600; }
        .body-content { padding: 30px; color: #333333; line-height: 1.6; }
        .body-content h2 { color: #1a73e8; margin-top: 0; }
        .btn { display: inline-block; padding: 12px 24px; margin-top: 20px; background-color: #1a73e8; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: bold; }
        .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; color: #777777; border-top: 1px solid #eeeeee; }
      </style>
    </head>
    <body>
      <div class="container">
        <div class="body-content">
          <h2>Dear ' . htmlspecialchars($firstname) . ',</h2>
          <p>Welcome to Bright Part Investment Platform.</p>
          <p>Thank you for choosing to join our growing community of investors. We are honored to be part of your financial journey and are committed to providing you with a secure, transparent, and rewarding investment experience.</p>
          <p>At Bright Part Investment Platform, we believe in creating opportunities that help our members build a stronger financial future. Our dedicated team is here to support you every step of the way.</p>
          <p>We encourage you to explore your account, familiarize yourself with the available features, and take advantage of the opportunities designed to help you achieve your investment goals.</p>
          <p>Thank you for your trust and confidence in Bright Part Investment Platform.</p>
          <p>We look forward to growing together.</p>
          <p>Kind regards,</p>
          <p>Bright Part Investment Platform <br> Customer Relations Team</p>
        </div>
        <div class="footer">
          <p>&copy; ' . date("Y") . ' <a href="https://brighpartinvestment.com">Brightpartinvestment</a>. All rights reserved.</p>
        </div>
      </div>
    </body>
    </html>';

    // Invoke mail function
    sendMail($email, "Bright Part investment", $subject, $emailBody);

    echo json_encode([
        "success" => true,
        "message" => "Registration completed successfully.",
        "data" => [
            "uid" => $uid,
            "redirect" => "/dashboard"
        ]
    ]);

    break;

    case '/login':
    $email = strtolower(trim($data['email'] ?? ''));
    $password = $data['password'] ?? '';
    $remember = !empty($data['remember']);

    if (empty($email) || empty($password)) {
        exit(json_encode([
            "success" => false,
            "message" => "Email and password are required."
        ]));
    }

    $stmt = $conn->prepare("
        SELECT
            uid,
            token,
            first_name,
            password
        FROM users
        WHERE email=?
        LIMIT 1
    ");

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 0) {
        exit(json_encode([
            "success" => false,
            "message" => "Invalid email or password."
        ]));
    }

    $user = $result->fetch_assoc();

    if (!password_verify($password, $user['password'])) {
        exit(json_encode([
            "success" => false,
            "message" => "Invalid email or password."
        ]));
    }

    /*
    |--------------------------------------------------------------------------
    | Generate New Login Token
    |--------------------------------------------------------------------------
    */

    do {

        $token = bin2hex(random_bytes(32));

        $check = $conn->prepare("SELECT id FROM users WHERE token=? LIMIT 1");
        $check->bind_param("s", $token);
        $check->execute();
        $check->store_result();

    } while ($check->num_rows > 0);

    $update = $conn->prepare("
        UPDATE users
        SET token=?
        WHERE uid=?
    ");

    $update->bind_param("ss", $token, $user['uid']);
    $update->execute();

    $_SESSION['user_id'] = $user['uid'];
    $_SESSION['token'] = $token;

    if ($remember) {
        setcookie(
            "remember_token",
            $token,
            time() + (60 * 60 * 24 * 30),
            "/",
            "",
            isset($_SERVER['HTTPS']),
            true
        );
    }

    echo json_encode([
        "success" => true,
        "message" => "Login successful.",
        "data" => [
            "uid" => $user['uid'],
            "name" => $user['first_name'],
            "redirect" => "/dashboard"
        ]
    ]);
    break;

    case '/member/kyc':

    if (
        empty($_SESSION['user_id']) ||
        empty($_SESSION['token'])
    ) {
        exit(json_encode([
            "success" => false,
            "message" => "Please login first."
        ]));
    }

    $uid = $_SESSION['user_id'];
    $token = $_SESSION['token'];

    // Verify session
    $login = $conn->prepare("
        SELECT uid
        FROM users
        WHERE uid=?
        AND token=?
        LIMIT 1
    ");

    $login->bind_param("ss", $uid, $token);
    $login->execute();

    if ($login->get_result()->num_rows == 0) {
        exit(json_encode([
            "success" => false,
            "message" => "Session expired."
        ]));
    }

    $type   = trim($_POST['type'] ?? '');
    $number = trim($_POST['code'] ?? '');
    $expire = trim($_POST['expire'] ?? '');

    if ($type == "passport") {
        $type = "international_passport";
    }

    if (empty($type) || empty($number) || empty($_FILES['image'])) {
        exit(json_encode([
            "success" => false,
            "message" => "All fields are required."
        ]));
    }

    // Only one KYC per user
    $check = $conn->prepare("
        SELECT id
        FROM kyc
        WHERE user_uid=?
        LIMIT 1
    ");

    $check->bind_param("s", $uid);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        exit(json_encode([
            "success" => false,
            "message" => "KYC has already been submitted."
        ]));
    }

    // Upload directory
    $uploadDir = __DIR__ . "/../assest/kyc/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
    }

    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (!in_array($ext, ["jpg", "jpeg", "png"])) {
        exit(json_encode([
            "success" => false,
            "message" => "Only JPG and PNG images are allowed."
        ]));
    }

    $filename = "KYC_" . $uid . "_" . time() . "." . $ext;

    if (!move_uploaded_file(
        $_FILES['image']['tmp_name'],
        $uploadDir . $filename
    )) {

        exit(json_encode([
            "success" => false,
            "message" => "Unable to upload image."
        ]));
    }

    $image = $filename;

    $insert = $conn->prepare("
        INSERT INTO kyc
        (
            user_uid,
            identification_type,
            doc_image,
            doc_number,
            expired_date
        )
        VALUES
        (?,?,?,?,?)
    ");

    $insert->bind_param(
        "sssss",
        $uid,
        $type,
        $image,
        $number,
        $expire
    );

    if (!$insert->execute()) {

        exit(json_encode([
            "success" => false,
            "message" => "Unable to submit KYC."
        ]));
    }

    echo json_encode([
        "success" => true,
        "message" => "Your KYC has been submitted successfully."
    ]);

    break;

    case "/member/select-package":

    if (
        empty($data['package_id']) ||
        empty($data['wallet_id']) ||
        empty($data['amount'])
    ) {

        echo json_encode([
            "success"=>false,
            "message"=>"Invalid deposit information"
        ]);

        break;
    }


   $user_uid=(isset($_SESSION['user_id'])) ?  $_SESSION['user_id'] : '';
   if(empty($user_uid)){
    echo json_encode([
            "success" => false,
            "message" => "Account Not Found!"
        ]);
        break;
   }

    $package_id = intval($data['package_id']);
    $wallet_id  = intval($data['wallet_id']);
    $amount     = floatval($data['amount']);



    // CHECK INVESTMENT PLAN LIMIT

    $plan = $conn->prepare("
        SELECT 
            min_limit,
            max_limit,
            status
        FROM investment_plan
        WHERE id=?
        LIMIT 1
    ");


    $plan->bind_param(
        "i",
        $package_id
    );


    $plan->execute();


    $planData = $plan->get_result()->fetch_assoc();



    if(!$planData){

        echo json_encode([
            "success"=>false,
            "message"=>"Investment plan not found"
        ]);

        break;

    }



    if($planData['status'] !== "active"){

        echo json_encode([
            "success"=>false,
            "message"=>"This investment plan is currently unavailable"
        ]);

        break;

    }



    if($amount < $planData['min_limit']){

        echo json_encode([

            "success"=>false,

            "message"=>"Minimum investment amount is $".
                number_format($planData['min_limit'],2)

        ]);

        break;

    }



    if($amount > $planData['max_limit']){

        echo json_encode([

            "success"=>false,

            "message"=>"Maximum investment amount is $".
                number_format($planData['max_limit'],2)

        ]);

        break;

    }





    // GENERATE UNIQUE DEPOSIT UID

    do {

        $deposit_uid = "DEP".strtoupper(bin2hex(random_bytes(8)));


        $check = $conn->prepare("
            SELECT id 
            FROM deposits
            WHERE deposit_uid=?
        ");


        $check->bind_param(
            "s",
            $deposit_uid
        );


        $check->execute();


        $exists = $check->get_result()->num_rows;


    } while($exists > 0);





    // CREATE DEPOSIT

    $insert = $conn->prepare("

        INSERT INTO deposits
        (
            deposit_uid,
            user_uid,
            investment_plan_id,
            wallet_id,
            amount
        )

        VALUES
        (?,?,?,?,?)

    ");



    $insert->bind_param(
        "ssiid",
        $deposit_uid,
        $user_uid,
        $package_id,
        $wallet_id,
        $amount
    );



    if($insert->execute()){


        $_SESSION['deposit_uid']=$deposit_uid;


        echo json_encode([

            "success"=>true,

            "message"=>"Deposit created successfully",

            "deposit_uid"=>$deposit_uid

        ]);


    }else{


        echo json_encode([

            "success"=>false,

            "message"=>"Failed to create deposit"

        ]);

    }
break;
case "/member/submit-deposit-proof":

    header("Content-Type: application/json");

    // Check login
    if (
        session_status() === PHP_SESSION_NONE ||
        empty($_SESSION['user_id'])
    ) {
        echo json_encode([
            "success" => false,
            "message" => "Please login to continue."
        ]);
        break;
    }

    $user_uid = trim($_SESSION['user_id']);

    // Validate required fields
    if (
        empty($_POST['deposit_uid']) ||
        empty($_POST['transaction_ref'])
    ) {
        echo json_encode([
            "success" => false,
            "message" => "Invalid deposit information."
        ]);
        break;
    }

    $deposit_uid     = trim($_POST['deposit_uid']);
    $transaction_ref = trim($_POST['transaction_ref']);

    /*
    |--------------------------------------------------------------------------
    | Check Deposit
    |--------------------------------------------------------------------------
    */

    $check = $conn->prepare("
        SELECT
            id,
            status,
            user_uid
        FROM deposits
        WHERE deposit_uid=?
        LIMIT 1
    ");

    $check->bind_param(
        "s",
        $deposit_uid
    );

    $check->execute();

    $deposit = $check->get_result()->fetch_assoc();

    if (!$deposit) {

        echo json_encode([
            "success" => false,
            "message" => "Deposit not found."
        ]);

        break;
    }

    // Ensure deposit belongs to logged in user
    if ($deposit['user_uid'] !== $user_uid) {

        echo json_encode([
            "success" => false,
            "message" => "Unauthorized request."
        ]);

        break;
    }

    // Only pending deposits can be submitted
    if ($deposit['status'] !== "pending") {

        echo json_encode([
            "success" => false,
            "message" => "This deposit has already been submitted."
        ]);

        break;
    }

    /*
    |--------------------------------------------------------------------------
    | Validate Upload
    |--------------------------------------------------------------------------
    */

    if (
        !isset($_FILES['proof_image']) ||
        $_FILES['proof_image']['error'] !== UPLOAD_ERR_OK
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Payment proof is required."
        ]);

        break;
    }

    $file = $_FILES['proof_image'];

    // Maximum 5MB
    $maxSize = 5 * 1024 * 1024;

    if ($file['size'] > $maxSize) {

        echo json_encode([
            "success" => false,
            "message" => "Image must not exceed 5MB."
        ]);

        break;
    }

    $finfo = finfo_open(FILEINFO_MIME_TYPE);

    $mime = finfo_file(
        $finfo,
        $file['tmp_name']
    );

    finfo_close($finfo);

    $allowed = [
        "image/jpeg" => "jpg",
        "image/png"  => "png",
        "image/webp" => "webp"
    ];

    if (!isset($allowed[$mime])) {

        echo json_encode([
            "success" => false,
            "message" => "Only JPG, PNG and WEBP images are allowed."
        ]);

        break;
    }

    /*
    |--------------------------------------------------------------------------
    | Upload Image
    |--------------------------------------------------------------------------
    */

    $uploadDir = __DIR__ . "/../uploads/deposits/";

    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0755, true);
    }

    $filename =
        "proof_" .
        time() .
        "_" .
        bin2hex(random_bytes(8)) .
        "." .
        $allowed[$mime];

    $uploadPath = $uploadDir . $filename;

    if (
        !move_uploaded_file(
            $file['tmp_name'],
            $uploadPath
        )
    ) {

        echo json_encode([
            "success" => false,
            "message" => "Unable to upload payment proof."
        ]);

        break;
    }

    /*
    |--------------------------------------------------------------------------
    | Update Deposit
    |--------------------------------------------------------------------------
    */

    $conn->begin_transaction();

    try {

        $update = $conn->prepare("
            UPDATE deposits
            SET
                proof_of_payment=?,
                transaction_reference=?,
                status='reviewing'
            WHERE
                deposit_uid=?
                AND user_uid=?
        ");

        $update->bind_param(
            "ssss",
            $filename,
            $transaction_ref,
            $deposit_uid,
            $user_uid
        );

        if (!$update->execute()) {
            throw new Exception("Unable to update deposit.");
        }

        /*
        |--------------------------------------------------------------------------
        | Create Notification
        |--------------------------------------------------------------------------
        */

        $title = "Deposit Submitted";

        $message = "Your deposit ($deposit_uid) has been submitted successfully and is awaiting administrator review.";

        $notification = $conn->prepare("
            INSERT INTO notifications
            (
                user_uid,
                title,
                message,
                notification_type
            )
            VALUES
            (?, ?, ?, 'deposit')
        ");

        $notification->bind_param(
            "sss",
            $user_uid,
            $title,
            $message
        );

        if (!$notification->execute()) {
            throw new Exception("Unable to create notification.");
        }

        $conn->commit();

        echo json_encode([
            "success" => true,
            "message" => "Deposit submitted for review."
        ]);

    } catch (Exception $e) {

        $conn->rollback();

        if (file_exists($uploadPath)) {
            unlink($uploadPath);
        }

        echo json_encode([
            "success" => false,
            "message" => $e->getMessage()
        ]);
    }

break;

case "/member/wallet-link":


    if($_SERVER['REQUEST_METHOD'] !== "POST"){

        echo json_encode([
            "success"=>false,
            "message"=>"Invalid request method"
        ]);

        break;
    }



    if(session_status() === PHP_SESSION_NONE){

        session_start();

    }



    if(empty($_SESSION['user_id'])){


        echo json_encode([

            "success"=>false,

            "message"=>"Session expired"

        ]);


        break;

    }



    $user_uid=$_SESSION['user_id'];



    $data=json_decode(file_get_contents("php://input"),true);



    $wallet_address=trim($data['wallet_address'] ?? "");

    $wallet_network=trim($data['wallet_network'] ?? "");




    if(empty($wallet_address) || empty($wallet_network)){


        echo json_encode([

            "success"=>false,

            "message"=>"Wallet address and network required"

        ]);

        break;

    }



    /*
    |--------------------------------------------------------------------------
    | Allowed Networks
    |--------------------------------------------------------------------------
    */


    $allowedNetworks=[

        "TRC20",
        "ERC20",
        "BEP20",
        "BTC",
        "ETH"

    ];



    if(!in_array($wallet_network,$allowedNetworks)){


        echo json_encode([

            "success"=>false,

            "message"=>"Unsupported wallet network"

        ]);


        break;

    }




    /*
    |--------------------------------------------------------------------------
    | Check existing wallet
    |--------------------------------------------------------------------------
    */


    $check=$conn->prepare("
        SELECT id
        FROM user_wallet
        WHERE user_uid=?
        LIMIT 1
    ");


    $check->bind_param(
        "s",
        $user_uid
    );


    $check->execute();


    $exists=$check->get_result();




    if($exists->num_rows > 0){



        /*
        Update wallet
        */


        $update=$conn->prepare("

            UPDATE user_wallet SET

            wallet_address=?,

            wallet_network=?,

            status='active'

            WHERE user_uid=?

        ");



        $update->bind_param(

            "sss",

            $wallet_address,

            $wallet_network,

            $user_uid

        );



        $update->execute();




    }else{



        /*
        Create wallet
        */


        $insert=$conn->prepare("

            INSERT INTO user_wallet

            (
                user_uid,
                wallet_address,
                wallet_network
            )

            VALUES
            (?,?,?)

        ");



        $insert->bind_param(

            "sss",

            $user_uid,

            $wallet_address,

            $wallet_network

        );



        $insert->execute();



    }





    echo json_encode([

        "success"=>true,

        "message"=>"Wallet address linked successfully"

    ]);
break;

case "/member/wallet-remove":

    if($_SERVER['REQUEST_METHOD'] !== "POST"){

        echo json_encode([
            "success"=>false,
            "message"=>"Invalid request method"
        ]);

        break;
    }


    if(session_status() === PHP_SESSION_NONE){
        session_start();
    }


    if(empty($_SESSION['user_id'])){

        echo json_encode([
            "success"=>false,
            "message"=>"Session expired"
        ]);

        break;
    }


    $user_uid = $_SESSION['user_id'];


    /*
    |--------------------------------------------------------------------------
    | Remove Wallet Address Only
    | Keep Balance And Funding History
    |--------------------------------------------------------------------------
    */

    $remove = $conn->prepare("
        UPDATE user_wallet
        SET
            wallet_address = NULL,
            wallet_network = NULL,
            status = 'suspended'
        WHERE user_uid = ?
    ");


    $remove->bind_param(
        "s",
        $user_uid
    );


    if($remove->execute()){


        echo json_encode([

            "success"=>true,

            "message"=>"Wallet address removed. Your funds remain safe."

        ]);


    }else{


        echo json_encode([

            "success"=>false,

            "message"=>"Unable to remove wallet address"

        ]);

    }


break;


case "/member/notification/read":

$data=json_decode(file_get_contents("php://input"),true);

$id=(int)$data['id'];

$user_uid=$_SESSION['user_id'];

$query=$conn->prepare("
UPDATE notifications
SET
seen=1,
read_at=NOW()
WHERE
id=?
AND
user_uid=?
");

$query->bind_param(
"is",
$id,
$user_uid
);

$query->execute();

echo json_encode([
"success"=>true
]);

break;


case "/member/notification/read-all":

$user_uid=$_SESSION['user_id'];

$query=$conn->prepare("
UPDATE notifications
SET
seen=1,
read_at=NOW()
WHERE
user_uid=?
AND
seen=0
");

$query->bind_param(
"s",
$user_uid
);

$query->execute();

echo json_encode([
"success"=>true
]);

break;

case "/member/notification/delete":

$data=json_decode(file_get_contents("php://input"),true);

$id=(int)$data['id'];

$user_uid=$_SESSION['user_id'];

$query=$conn->prepare("
DELETE
FROM notifications
WHERE
id=?
AND
user_uid=?
");

$query->bind_param(
"is",
$id,
$user_uid
);

$query->execute();

echo json_encode([
"success"=>true
]);

break;

case "/member/profile/update":

if(empty($_SESSION['user_id'])){

echo json_encode([
"success"=>false,
"message"=>"Session expired"
]);

break;

}

$data=json_decode(file_get_contents("php://input"),true);

$first_name=trim($data['first_name'] ?? "");
$last_name=trim($data['last_name'] ?? "");
$phone=trim($data['phone'] ?? "");
$country=trim($data['country'] ?? "");
$currency=trim($data['currency'] ?? "USD");
$theme=trim($data['theme'] ?? "light");

if(
empty($first_name) ||
empty($last_name)
){

echo json_encode([
"success"=>false,
"message"=>"All required fields are required."
]);

break;

}

$update=$conn->prepare("
UPDATE users
SET
first_name=?,
last_name=?,
phone=?,
country=?,
currency=?,
theme=?
WHERE uid=?
");

$update->bind_param(
"sssssss",
$first_name,
$last_name,
$phone,
$country,
$currency,
$theme,
$_SESSION['user_id']
);

$update->execute();

$_SESSION['theme']=$theme;

echo json_encode([
"success"=>true,
"message"=>"Profile updated successfully."
]);

break;

case "/member/change-password":

if(empty($_SESSION['user_id'])){

echo json_encode([
"success"=>false,
"message"=>"Session expired"
]);

break;

}

$data=json_decode(file_get_contents("php://input"),true);

$old=trim($data['old_password']);
$new=trim($data['new_password']);

$get=$conn->prepare("
SELECT password
FROM users
WHERE uid=?
LIMIT 1
");

$get->bind_param("s",$_SESSION['user_id']);
$get->execute();

$get->bind_result($hash);
$get->fetch();
$get->close();

if(!password_verify($old,$hash)){

echo json_encode([
"success"=>false,
"message"=>"Current password is incorrect."
]);

break;

}

$newHash=password_hash($new,PASSWORD_DEFAULT);

$update=$conn->prepare("
UPDATE users
SET password=?
WHERE uid=?
");

$update->bind_param(
"ss",
$newHash,
$_SESSION['user_id']
);

$update->execute();

echo json_encode([
"success"=>true,
"message"=>"Password updated successfully."
]);

break;

case "/member/create-loan":

$user_uid=$_SESSION['user_id'] ?? null;


if(!$user_uid){

echo json_encode([
"success"=>false,
"message"=>"Unauthorized"
]);

break;

}


$amount=floatval($data['amount'] ?? 0);

$reason=trim($data['reason'] ?? '');

$duration=trim($data['duration'] ?? '');



if($amount<=0 || empty($reason)){


echo json_encode([

"success"=>false,

"message"=>"Invalid loan information"

]);


break;

}



$loan_uid="LN-".strtoupper(bin2hex(random_bytes(5)));



$stmt=$conn->prepare("
INSERT INTO loans
(
loan_uid,
user_uid,
amount,
reason,
duration
)

VALUES(?,?,?,?,?)
");



$stmt->bind_param(
"ssdss",
$loan_uid,
$user_uid,
$amount,
$reason,
$duration
);



if($stmt->execute()){


echo json_encode([

"success"=>true,

"message"=>"Loan application submitted successfully"

]);


}else{


echo json_encode([

"success"=>false,

"message"=>"Unable to submit loan"

]);


}


break;

case "/member/create-withdrawal":

    if(empty($_SESSION['user_id'])){

        echo json_encode([
            "success"=>false,
            "message"=>"Login required"
        ]);
        break;
    }

    $user_uid = $_SESSION['user_id'];

    $amount = (float)($data['amount'] ?? 0);

    if($amount <= 0){

        echo json_encode([
            "success"=>false,
            "message"=>"Invalid amount"
        ]);
        break;
    }

    $wallet = $conn->prepare("
        SELECT wallet_balance
        FROM user_wallet
        WHERE user_uid=?
        LIMIT 1
    ");

    $wallet->bind_param("s",$user_uid);

    $wallet->execute();

    $walletData =
    $wallet->get_result()
    ->fetch_assoc();

    if(!$walletData){

        echo json_encode([
            "success"=>false,
            "message"=>"Wallet not found"
        ]);
        break;
    }

    $balance = (float)$walletData['wallet_balance'];

    if($amount > $balance){

        echo json_encode([
            "success"=>false,
            "message"=>"Insufficient balance"
        ]);
        break;
    }

    $withdrawal_uid =
    "WD".time().rand(1000,9999);

    $conn->begin_transaction();

    try{

        $newBalance =
        $balance - $amount;

        $update = $conn->prepare("
            UPDATE user_wallet
            SET wallet_balance=?
            WHERE user_uid=?
        ");

        $update->bind_param(
            "ds",
            $newBalance,
            $user_uid
        );

        $update->execute();

        $insert = $conn->prepare("
            INSERT INTO withdrawals(
                withdrawal_uid,
                user_uid,
                amount,
                status
            )
            VALUES(
                ?,
                ?,
                ?,
                'pending'
            )
        ");

        $insert->bind_param(
            "ssd",
            $withdrawal_uid,
            $user_uid,
            $amount
        );

        $insert->execute();

        $trx_uid =
        "TRX".time().rand(1000,9999);

        $trx = $conn->prepare("
            INSERT INTO transactions(
                transaction_uid,
                user_uid,
                type,
                asset,
                amount,
                direction,
                status,
                description
            )
            VALUES(
                ?,
                ?,
                'withdrawal',
                'Wallet Balance',
                ?,
                'debit',
                'pending',
                'Withdrawal request submitted'
            )
        ");

        $trx->bind_param(
            "ssd",
            $trx_uid,
            $user_uid,
            $amount
        );

        $trx->execute();

        $conn->commit();

        echo json_encode([
            "success"=>true,
            "message"=>"Withdrawal request submitted successfully."
        ]);

    }catch(Exception $e){

        $conn->rollback();

        echo json_encode([
            "success"=>false,
            "message"=>"Withdrawal failed"
        ]);
    }

break;


case '/member/verify-deposit-status':
        // 1. Session Authorization Gate
        if (empty($_SESSION['user_id'])) {
            echo json_encode([
                "success" => false,
                "message" => "Authentication required"
            ]);
            break;
        }

        $user_uid = $_SESSION['user_id'];
        $deposit_uid = $data['deposit_uid'] ?? '';

        if (empty($deposit_uid)) {
            echo json_encode([
                "success" => false,
                "message" => "Missing identification metric"
            ]);
            break;
        }

        // 2. Query matching records securely using prepared statements
        // Note: Make sure your deposits table has a user column (e.g., user_uid) to match session identity
        $checkDeposit = $conn->prepare("
            SELECT status 
            FROM deposits 
            WHERE deposit_uid = ? AND user_uid = ? 
            LIMIT 1
        ");

        $checkDeposit->bind_param("ss", $deposit_uid, $user_uid);
        $checkDeposit->execute();
        $result = $checkDeposit->get_result()->fetch_assoc();

        if (!$result) {
            echo json_encode([
                "success" => false,
                "status" => "not_found",
                "message" => "Record not located in system ledger"
            ]);
            break;
        }

        // 3. Evaluate state and return the payload to the waiting UI component
        $status = strtolower($result['status']); // Standardize comparison matching

        echo json_encode([
            "success" => ($status === 'approved'),
            "status" => $status,
            "message" => "Status checked successfully."
        ]);
        break;

        echo json_encode([
            "success" => false,
            "message" => "Invalid request."
        ]);
        break;
}