<?php

	/*
		Rainbox Asset Manager
		Updates the SQL Database
	*/

    define('RAMROOT',dirname(dirname(__FILE__)));

    // INIT
    require_once(RAMROOT.'/global.php');
	include(RAMROOT."/init.php");

    // connect to database
	require_once(RAMROOT.'/db.php');
    // functions
    require_once(RAMROOT.'/functions.php');

    echo "<strong>Updating database using {$sqlMode}...</strong>";
    flush();

    // Tables must have a "project" column

    echo "<p>Updating project data...</p>";
    flush();

    include(RAMROOT."/update/project_columns.php");
    addProjectColumns();

    // Must have a Project-User table association
    
    echo "<p>Updating the project-user assignments table...</p>";
    include(RAMROOT."/update/project_users_table.php");
    createProjectUserTable(false);
    updateProjectUserTable();

    // Must have an email column
    include(RAMROOT."/update/user_email.php");
    addEmailColumn(false);

    // Run a database clean
    echo "<p>Cleaning database...</p>";
    flush();
    require_once(RAMROOT."/db_clean.php");
    db_clean(true);

    echo "<p><strong>Update finished</strong></p>";
    flush();
