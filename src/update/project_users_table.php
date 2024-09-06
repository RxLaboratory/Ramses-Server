<?php 
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    function updateProjectUserTable()
    {
        // Parse all projects and users
        $q = new DBQuery();

        $projects = $q->get("RamProject", true, true );
        $users = $q->get("RamUser", true, true);

        $assignments = array();

        foreach( $projects as $project) {
            $projectUsers = $project["data"]["users"] ?? array();
            $userIds = array();
            foreach ($projectUsers as $userUuid) {
                $user = $users[$userUuid];
                $userIds[] = (int)$user["id"];
            }
            $q->assignUsers($userIds, (int)$project["id"]);
        }        
    }
