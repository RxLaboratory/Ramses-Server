<?php
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    // Parse metadata given by the client with each and every request
    $_SESSION["clientVersion"] = strtolower( getArg("version", "< 0.2.9 or unknown") );
