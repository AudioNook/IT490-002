<?php
require(__DIR__ . "/../lib/functions.php");
$logoutReq = new DBRequests();
$logoutReq->logout();


// TODO logout not working