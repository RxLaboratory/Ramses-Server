<?php
    include ("../config.php");
    $pswd = str_replace("/", "", $serverAdress) . "password" . "H6BuYLsW"; // the last part can be changed, but it must be changed also in the clients config.
    $pswd = hash("sha3-512", $pswd);
    echo($pswd);
?>