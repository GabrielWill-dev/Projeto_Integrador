<?php
require_once("vendor/autoload.php");

use Minishlink\WebPush\VAPID;

print_r(VAPID::createVapidKeys());

/*
[publicKey] => BFmbASE5gxCruK3gt6won6XwqtsB6gR4NMuwbYUrL45UtzSZHLnPLaZk8xB6F_mvx3l8nKQWXM1xty0G8LpSYvY
[privateKey] => tewdCyOiF4aDxAz4KrreUbUNPstsweCrUnhMuKjnnuE
*/