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