<?php
require_once("vendor/autoload.php");

use Minishlink\WebPush\Subscription;
use Minishlink\WebPush\WebPush;


$auth = [
    'VAPID' => [
        'subject' => 'mailto:me@website.com', // can be a mailto: or your website address
        'publicKey' => 'BFmbASE5gxCruK3gt6won6XwqtsB6gR4NMuwbYUrL45UtzSZHLnPLaZk8xB6F_mvx3l8nKQWXM1xty0G8LpSYvY', // (recommended) uncompressed public key P-256 encoded in Base64-URL
        'privateKey' => 'tewdCyOiF4aDxAz4KrreUbUNPstsweCrUnhMuKjnnuE', // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL 
    ],
];

$webPush = new WebPush($auth);

$report = $webPush->sendOneNotification(
    Subscription::create(json_decode('{"endpoint":"https://fcm.googleapis.com/fcm/send/eETGjsOk0-I:APA91bGOTUT1h-E0hzl6C0A-4nvWtmKGW8BmixAWK343t4F-TQ3JzitakiqIBCiSnlqpWQfNA0shmrnTXSt5SLM9Gzob7OJZsqxg8Dldo_a_4MBFHW_Sn8wCnNZUpliuRc3xr60uzx7X","expirationTime":null,"keys":{"p256dh":"BHzhDNrBrmlKUZSAp_g8iDqyIpSbB7Pc2Y8jzsGuWlXG0gZkrmUnsWiSfFp99LoA2sGiMU_JsdsXTCKQiQBeH7s","auth":"q8J2RUaHr-xWCaK3tS-eYA"}}',true))
    , '{"title":"Hi from php" , "body":"php is amazing!" , "url":"./?message=123"}', ['TTL' => 5000]);

    print_r($report);