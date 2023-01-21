<?php

    if ($maintenance)
    {
        ?>

        <div id="welcome">
            <h1>This server is under maintenance. Please try again later.</h1>
        </div> <!-- end: welcome -->

        <div id="content">
            <p id="maintenance">Sorry, there's some work being done on this server. This shouldn't last long, come back a bit later.</p>
        </div> <!-- end: content -->

        <?php
        Utils::endHtmlAndExit();
    }

?>