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

    case '/deposit-payment':
                include __DIR__."/memberz/deposit-payment.php";
        break;
        
    case '/investment-plans':
        include __DIR__."/memberz/investment-plan.php";
        break;
    
    default:
       
    break;
}