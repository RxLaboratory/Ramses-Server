<?php
    function updateProjectUserTable()
    {
        // Parse all projects and users
        $q = new DBQuery();

        $projects = $q->get("RamProject", true );
        $users = $q->get("RamUser", true);

        $assignments = array();

        foreach( $projects as $project) {
            $projectUsers = $project["data"]["users"] ?? array();
            foreach ($projectUsers as $userUuid) {
                $user = $users[$userUuid];
                $assignments[] = array($user["id"], $project["id"]);
            }
        }

        $q->assignUsers($assignments);
    }
?>