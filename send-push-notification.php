<?php

require __DIR__.'/vendor/autoload.php';
require __DIR__.'/function.php';

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

$auth=[
    
'VAPID'=>[

'subject'=>'mailto:info@air9ja.com',

'publicKey'=>'BDQZfn8Pxwu8Wrn4ltvVF6etP30ELZFSCVA8o74TBvoX4qOh5P3kkK3haZk16VssI5si-VOBeksP5do7u_6iKWM',

'privateKey'=>'Les2ZUcxPLd8N4CG7BuqVteDPUwq8EAbXCJUBPGkOj4'

]

];

$webPush=new WebPush($auth);

$result=$conn->query("SELECT * FROM push_subscriptions");

while($row=$result->fetch_assoc()){

    $subscription=Subscription::create([

        'endpoint'=>$row['endpoint'],

        'keys'=>[

            'p256dh'=>$row['p256dh'],

            'auth'=>$row['auth']

        ]

    ]);

    $payload=json_encode([

        'title'=>'Bright Path',

        'body'=>'Your investment has been approved.',

        'url'=>'https://yourdomain.com/member'

    ]);

    $webPush->queueNotification($subscription,$payload);

}

foreach($webPush->flush() as $report){

    if(!$report->isSuccess()){

        echo $report->getReason();

    }

}