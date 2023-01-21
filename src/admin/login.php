<?php
    $failReason = "";

    if (RequestParser::hasArg("login"))
    {
        $name = RequestParser::getAttr("name", "Invalid name");
        $pswd = RequestParser::getAttr("pswd");

        $failReason = SecurityManager::generatePassword(8);
        //$_SESSION["loggedin"] = true;

        // Check in the database
        /*$q = new DBQuery();
		$q->prepare( "SELECT `id`, `admin`,`number`,`k`,`pswd`, `name` FROM `users` WHERE `name` = :name;" );
		$q->bindStr( 'name', $name );
		$q->execute();

        $test = $q->fetch();
        $q->close();

        if ($test) {
            // Check password
            if (SecurityManager::checkPassword($pswd, $test['pswd']))
            {
                $_SESSION["name"] = $test['name'];
                $_SESSION["number"] = $test['number'];
                $_SESSION["admin"] = boolval( $test['admin']);
                $_SESSION["key"] = $test['k'];
                $_SESSION["id"] = (int)$test['id'];
            }
            else {
                $failReason = "Wrong user name or password";
                logout();
            }
        }
        else {
            $failReason = "Wrong number or password";
            logout();
        }*/
    }

    //if (!$_SESSION["loggedin"])
    //{
        // Let's log in!
        ?>

        <div id="welcome">
            <img src="favicon.png" />
            <h1>Welcome to the Ramses Server Manager</h1>
        </div> <!-- end: welcome -->

        <div id="content">
            <form id="login" action="index.php?login" method="post">
                <input type="text" id="name" name="name" placeholder="User name" required>
                <input type="password" id="pswd" name="pswd" placeholder="Password" required>
                <input type="submit" value="Sign in">
                <?php if ($failReason != "") echo("<p class=\"submit-failed\">" . $failReason . "</p>") ?>
            </form>
            
        </div> <!-- end: content -->


        <?php
        Utils::endHtmlAndExit();
    //}
?>