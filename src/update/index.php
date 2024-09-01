<?php

	/*
		Rainbox Asset Manager
		Updates the SQL Database
	*/

    $__ROOT__ = dirname(__FILE__, 2);

    // INIT
    require_once($__ROOT__.'/global.php');
	include($__ROOT__."/init.php");

    // connect to database
	require_once($__ROOT__.'/db.php');
    // functions
    require_once($__ROOT__.'/functions.php');

    echo "<strong>Updating database using {$sqlMode}...</strong>";
    flush();

    // Tables must have a "project" column

    echo "<p>Updating project data...</p>";
    flush();

    include($__ROOT__."/update/project_columns.php");
    addProjectColumns();

    // Must have a Project-User table association
    
    echo "<p>Updating the project-user assignments table...</p>";
    include($__ROOT__."/update/project_users_table.php");
    createProjectUserTable(false);
    updateProjectUserTable();

    // Run a database clean
    echo "<p>Cleaning database...</p>";
    flush();
    require_once($__ROOT__."/db_clean.php");
    db_clean(true);

    echo "<p><strong>Update finished</strong></p>";
    flush();

?>