<?php
    include('../config.php');
    include('../config_security.php');
    include('../functions.php');
    include('../db.php');

    $sequences = "(7,39,40,41,42,43,44,45,55,58,59)";

    $q = "SELECT * FROM {$tablePrefix}`shots`
        WHERE sequenceId IN {$sequences}
        AND removed = 0
        ORDER BY `order`;";

    $rep = $db->prepare($q);
    $rep->execute();
    $i = 0;
    while( $s = $rep->fetch())
    {
        $index = (string)$i;
        $id = $s["id"];
        $q = "UPDATE {$tablePrefix}`shots` SET `order` = {$index} WHERE `id` = {$id};"
        $rep2 = $db->prepare($q);
        $rep2->execute();
        $i = $i+1;
    }
    echo("fixed");
?>