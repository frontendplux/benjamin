<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include __DIR__."/memberz/country.php";
include __DIR__."/server/conn.php";
$dataUrl=parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$company_info=[
    "name" => "brightpath",
    "title" => "BrightPath - Your Financial Journey Starts Here",
    "keywords" =>"",
    "logo" => "/logo.png",
    "logo2" => "/logo2.png",
    "description" => "description for the site", 
    "main-server" => "/server/main.php",
    "admin-server" => "/server/admin.php",
];
switch ($dataUrl) {
    case '/':
    case '/home':
        include __DIR__."/main/home.php";
        break;

    case  '/investment':
       include __DIR__."/main/investement.php";
        break;
    
    case '/about-us':
       include __DIR__."/main/about.php";
        break;
    
    case '/faq':
            include __DIR__."/main/faq.php";
        break;

    case '/contact-us':
              include __DIR__."/main/contact-us.php";
        break;

    case '/login':
              include __DIR__."/memberz/login.php";
        break;
        
    case '/register':
        include __DIR__."/memberz/register.php";
        break;

    case '/dashboard':
    case '/member':
            include __DIR__."/memberz/member.php";
        break;

    case '/kyc':
            include __DIR__."/memberz/kyc.php";
        break;

    case '/make-deposit':
                include __DIR__."/memberz/deposit.php";
        break;

    case '/reinvest':
                include __DIR__."/memberz/reinvest.php";
        break;


    
    case '/deposit-payment':
                include __DIR__."/memberz/deposit-payment.php";
        break;
        
    case '/investment-plans':
        include __DIR__."/memberz/investment-plan.php";
        break;
    
    case '/wallet-connect':
        include __DIR__."/memberz/wallet-connect.php";
        break;
    
    case '/apply-for-loan':
        include __DIR__."/memberz/loan.php";
        break;

    case '/notification':
            include __DIR__."/memberz/notification.php";
    break;
    
    
    case '/profile':
            include __DIR__."/memberz/profile.php";
    break;
    
    case '/referrals':
            include __DIR__."/memberz/referrals.php";
    break;
    
    
    case '/account-history':
            include __DIR__."/memberz/history.php";
    break;

    case '/settings':
        include __DIR__."/memberz/settings.php";
    break;

    case '/withdraw-funds':
         include __DIR__."/memberz/withdraw.php";
        break;

    case '/signout':
        session_destroy();
        header('location:/login');
        break;

    case '/admin':
        include __DIR__."/adminz/login.php";
        break;
    
    
    case '/installation':
        include __DIR__."/adminz/index.php";
        break;
    
    case '/admin-dashboard':
        include __DIR__."/adminz/dashbord.php";
        break;
    
    case '/admin-deposit':
         include __DIR__."/adminz/deposit.php";
        break;

    case '/admin-withdrawals':
        include __DIR__."/adminz/withdrawal.php";
        break;

    case '/admin-logout':
        session_destroy();
        header("location:/admin");
        break;
    

    default:
    break;
}