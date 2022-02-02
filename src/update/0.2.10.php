<?php
    if ( versionLowerThan('0.2.11-alpha', $currentVersion) )
    {

        // ==== Update Table Structure ====

        echo ( " ▸ Updating Database structure.<br />" );

        $rep = $db->query( "LOCK TABLES
            {$tablePrefix}steps WRITE;

        ALTER TABLE `ram_schedule` CHANGE `stepId` `stepId` INT(11) NULL DEFAULT NULL;

        UNLOCK TABLES;");
    }

    $ok = $rep->execute();
    $rep->closeCursor();

    if (!$ok)
    {
        echo( "    ▫ Failed. Could not update data, here's the error:<br />" );
        die( print_r($db->errorInfo(), true) );
    }

    echo ( "     ▪ OK!<br />" );

    ob_flush();
    flush();

?>
