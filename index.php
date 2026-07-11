<?php
include __DIR__."/member/country.php";
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
              include __DIR__."/member/login.php";
        break;
    
    default:
       
    break;
}