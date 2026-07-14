CREATE TABLE IF NOT EXISTS users (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    uid VARCHAR(100) NOT NULL UNIQUE,
    token VARCHAR(255) DEFAULT NULL,

    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,

    email VARCHAR(255) NOT NULL UNIQUE,
    phone VARCHAR(30) NOT NULL,

    country VARCHAR(100) NOT NULL,

    password VARCHAR(255) NOT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);
ALTER TABLE users
ADD column IF NOT EXISTS currency  VARCHAR(10) DEFAULT 'USD',
ADD column IF NOT EXISTS theme  VARCHAR(10) DEFAULT 'light';

CREATE TABLE IF NOT EXISTS referrals (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    uid VARCHAR(100) NOT NULL,
    referred_by VARCHAR(100) NOT NULL,
    status ENUM('pending', 'rewarded') DEFAULT 'pending',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_uid (uid),
    INDEX idx_referred_by (referred_by),

    CONSTRAINT fk_referrals_user
        FOREIGN KEY (uid)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_referrals_referrer
        FOREIGN KEY (referred_by)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS kyc (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_uid VARCHAR(100) NOT NULL,

    identification_type ENUM(
        'national_id',
        'international_passport',
        'drivers_license',
        'voters_card'
    ) NOT NULL,

    doc_image VARCHAR(255) NOT NULL,
    doc_number VARCHAR(100) NOT NULL,

    expired_date DATE DEFAULT NULL,

    is_verified BOOLEAN DEFAULT FALSE,

    verified_at TIMESTAMP NULL DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    UNIQUE KEY unique_user (user_uid),

    INDEX idx_user_uid (user_uid),
    INDEX idx_doc_number (doc_number),
    INDEX idx_verified (is_verified),

    CONSTRAINT fk_kyc_user
        FOREIGN KEY (user_uid)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS wallets (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    network VARCHAR(100) NOT NULL,
    coin_symbol VARCHAR(100) NOT NULL,
    wallet_address TEXT NOT NULL,
    qr_image VARCHAR(255) DEFAULT NULL,
    description TEXT DEFAULT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_network (network),
    INDEX idx_coin_symbol (coin_symbol),
    INDEX idx_active (is_active)
);

CREATE TABLE IF NOT EXISTS investment_plan (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    name VARCHAR(100) NOT NULL,
    description TEXT DEFAULT NULL,

    roi DECIMAL(10,2) NOT NULL COMMENT 'Return on Investment (%)',

    duration_value INT UNSIGNED NOT NULL,
    duration_unit ENUM('hours', 'days', 'weeks', 'months', 'years') NOT NULL,

    min_limit DECIMAL(18,2) NOT NULL,
    max_limit DECIMAL(18,2) NOT NULL,

    approval ENUM('automated', 'manual') NOT NULL DEFAULT 'automated',

    status ENUM('active', 'inactive') NOT NULL DEFAULT 'active',

    is_bonus  BOOLEAN DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_status (status),
    INDEX idx_approval (approval)
);

CREATE TABLE IF NOT EXISTS deposits (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    deposit_uid VARCHAR(100) NOT NULL UNIQUE,

    user_uid VARCHAR(100) NOT NULL,

    investment_plan_id BIGINT UNSIGNED NOT NULL,

    wallet_id BIGINT UNSIGNED NOT NULL,

    amount DECIMAL(18,2) NOT NULL,

    proof_of_payment VARCHAR(255) DEFAULT NULL,

    transaction_reference VARCHAR(150) DEFAULT NULL,

    status ENUM(
        'pending',
        'reviewing',
        'approved',
        'rejected',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    approved_at TIMESTAMP NULL DEFAULT NULL,

    approved_by VARCHAR(100) DEFAULT NULL,

    remarks TEXT DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user (user_uid),
    INDEX idx_plan (investment_plan_id),
    INDEX idx_wallet (wallet_id),
    INDEX idx_status (status),
    INDEX idx_reference (transaction_reference),

    CONSTRAINT fk_deposit_user
        FOREIGN KEY (user_uid)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE,

    CONSTRAINT fk_deposit_plan
        FOREIGN KEY (investment_plan_id)
        REFERENCES investment_plan(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE,

    CONSTRAINT fk_deposit_wallet
        FOREIGN KEY (wallet_id)
        REFERENCES wallets(id)
        ON DELETE RESTRICT
        ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS notifications (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_uid VARCHAR(100) NOT NULL,
    title VARCHAR(400) NOT NULL,
    message TEXT NOT NULL,
    seen BOOLEAN DEFAULT FALSE,
    notification_type ENUM('system','deposit','investment','kyc','referral') DEFAULT 'system',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_user_uid (user_uid),
    INDEX idx_seen (seen),
    INDEX idx_type (notification_type),
    CONSTRAINT fk_notification_user
        FOREIGN KEY (user_uid)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
ALTER TABLE notifications
ADD COLUMN IF NOT EXISTS read_at TIMESTAMP NULL DEFAULT NULL AFTER seen;

CREATE TABLE IF NOT EXISTS user_wallet (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_uid VARCHAR(100) NOT NULL UNIQUE,
    wallet_balance DECIMAL(18,2) NOT NULL DEFAULT 0.00,
    wallet_address VARCHAR(255) DEFAULT NULL,
    status ENUM('active','suspended') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user_uid (user_uid),

    CONSTRAINT fk_wallet_user
        FOREIGN KEY (user_uid)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);

ALTER TABLE user_wallet
ADD COLUMN if not EXISTS wallet_network VARCHAR(50) DEFAULT NULL AFTER wallet_address;



CREATE TABLE if not EXISTS login_history(
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    user_uid VARCHAR(100) NOT NULL,

    ip_address VARCHAR(100) NOT NULL,

    device VARCHAR(255) NOT NULL,

    browser VARCHAR(255) NOT NULL,

    status ENUM('login','logout') DEFAULT 'login',

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    INDEX(user_uid),

    FOREIGN KEY(user_uid)
    REFERENCES users(uid)
    ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS transactions (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    transaction_uid VARCHAR(100) NOT NULL UNIQUE,

    user_uid VARCHAR(100) NOT NULL,

    type ENUM(
        'deposit',
        'withdrawal',
        'investment',
        'roi',
        'referral_bonus',
        'loan'
    ) NOT NULL,

    reference_id VARCHAR(100) DEFAULT NULL,

    asset VARCHAR(100) DEFAULT 'USD',

    amount DECIMAL(18,2) NOT NULL,

    direction ENUM(
        'credit',
        'debit'
    ) NOT NULL DEFAULT 'credit',

    status ENUM(
        'pending',
        'processing',
        'success',
        'failed',
        'cancelled'
    ) DEFAULT 'pending',

    description TEXT DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,


    INDEX idx_user_uid(user_uid),
    INDEX idx_type(type),
    INDEX idx_status(status),


    CONSTRAINT fk_transaction_user
        FOREIGN KEY(user_uid)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE
);
CREATE INDEX IF NOT EXISTS idx_user_created
ON transactions(user_uid, created_at);


CREATE TABLE IF NOT EXISTS loans (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    loan_uid VARCHAR(100) NOT NULL UNIQUE,

    user_uid VARCHAR(100) NOT NULL,

    amount DECIMAL(18,2) NOT NULL,

    reason TEXT NOT NULL,

    duration VARCHAR(50) DEFAULT NULL,

    status ENUM(
        'pending',
        'approved',
        'rejected',
        'cancelled'
    ) DEFAULT 'pending',

    admin_note TEXT DEFAULT NULL,

    approved_at TIMESTAMP NULL DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,


    INDEX idx_user(user_uid),
    INDEX idx_status(status),


    CONSTRAINT fk_loan_user

    FOREIGN KEY(user_uid)

    REFERENCES users(uid)

    ON DELETE CASCADE

    ON UPDATE CASCADE

);

CREATE TABLE IF NOT EXISTS withdrawals (

    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    withdrawal_uid VARCHAR(100) NOT NULL UNIQUE,

    user_uid VARCHAR(100) NOT NULL,

    amount DECIMAL(18,2) NOT NULL,

    wallet_address VARCHAR(255) NOT NULL,

    network VARCHAR(100) NOT NULL,

    status ENUM(
        'pending',
        'processing',
        'approved',
        'rejected',
        'cancelled'
    ) NOT NULL DEFAULT 'pending',

    transaction_hash VARCHAR(255) DEFAULT NULL,

    admin_note TEXT DEFAULT NULL,

    approved_at TIMESTAMP NULL DEFAULT NULL,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP,

    INDEX idx_user(user_uid),
    INDEX idx_status(status),

    CONSTRAINT fk_withdraw_user
        FOREIGN KEY(user_uid)
        REFERENCES users(uid)
        ON DELETE CASCADE
        ON UPDATE CASCADE

);


CREATE TABLE IF NOT EXISTS admin (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,

    uid VARCHAR(100) NOT NULL UNIQUE,

    token VARCHAR(255) DEFAULT NULL,

    email VARCHAR(255) NOT NULL UNIQUE,

    password VARCHAR(255) NOT NULL,

    app_password VARCHAR(255) DEFAULT NULL,

    is_active BOOLEAN NOT NULL DEFAULT FALSE,

    is_main_admin BOOLEAN NOT NULL DEFAULT FALSE,

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ON UPDATE CURRENT_TIMESTAMP
);