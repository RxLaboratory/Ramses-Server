<?php
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    //result of the request
    $reply = Array();
    $reply["accepted"] = false;
    $reply["success"] = false;
    $reply["message"] = "";
    $reply["query"] = "unknown";
    $reply["content"] = Array();
    $reply["serverUuid"] = "";
    $reply["debug"] = Array();
