<?php
    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 2020-2021 Nicolas Dufresne and Contributors.

        This program is free software;
        you can redistribute it and/or modify it
        under the terms of the GNU General Public License
        as published by the Free Software Foundation;
        either version 3 of the License, or (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
        See the GNU General Public License for more details.

        You should have received a copy of the *GNU General Public License* along with this program.
        If not, see http://www.gnu.org/licenses/.
	*/

    function createEntry($uuid, $userUuid, $stepUuid, $date)
    {
        $q = new DBQuery();
        $userId = $q->id('users', $userUuid);
        $stepId = $q->id('steps', $stepUuid);

        $q->insert( "schedule", array( 'uuid', 'userId', 'stepId', 'date' ));

        $q->bindStr( "uuid", $uuid, true );
		$q->bindStr( "date", $date );
		$q->bindInt( "userId", $userId );
		$q->bindInt( "stepId", $stepId );

        $q->execute("Schedule updated.");
		$q->close();
    }

    function updateEntry($uuid, $userUuid, $stepUuid, $date, $comment)
    {
        $q = new DBQuery();
        $userId = $q->id('users', $userUuid);
        $stepId = $q->id('steps', $stepUuid);

		$q->update(
			"schedule",
			array(
				'userId',
				'stepId',
				'date',
                'comment'
			),
			$uuid
		);

		$q->bindStr( "date", $date );
		$q->bindStr( "comment", $comment );
		$q->bindInt( "userId", $userId );
		$q->bindInt( "stepId", $stepId );

        $q->execute("Schedule updated.");
		$q->close();
    }

    function updateScheduleComment( $uuid, $projectUuid, $date, $comment, $color)
    {
        $q = new DBQuery();

        $projectId = $q->id('projects', $projectUuid);

        $q->insert(
            "schedulecomments",
            array(
                'projectId',
                'date',
                'comment',
                'color',
                'uuid'
            )
        );

        $q->bindStr("comment", $comment);
        $q->bindStr("color", $color);
        $q->bindStr("date", $date);
        $q->bindStr("projectId", $projectId);
        $q->bindStr("uuid", $uuid, true);

        $q->execute("Schedule comment updated.");
		$q->close();
    }

    function deleteEntry( $uuid )
    {
        $q = new DBQuery();
        $q->update(
            "schedule",
            array(
                'stepId'
            ),
            $uuid );
		$q->bindNull( "stepId" );
        $q->execute("Schedule updated.");
		$q->close();
    }

    // ========= CREATE ENTRY ==========
    if ( acceptReply( "createSchedule", 'lead' ) )
    {
        acceptReply( "createSchedule" );

        $uuid = getArg("uuid", uuid());
        $userUuid = getArg("userUuid");
        $stepUuid = getArg("stepUuid");
        $date = getArg("date");

        createEntry($uuid, $userUuid, $stepUuid, $date);
    }

    // ========= CREATE ENTRIES ==========
    else if (acceptReply( "createSchedules", 'lead' ))
    {
        $entries = getArg("entries", array());

        if (count($entries) == 0)
        {
            $reply["message"] = "Schedule updated.";
            $reply["success"] = true;
        }

        foreach($entries as $entry)
        {
            $uuid = getAttr("uuid", $entry, uuid());
            $userUuid = getAttr("userUuid", $entry);
            $stepUuid = getAttr("stepUuid", $entry);
            $date = getAttr("date", $entry);

            createEntry($uuid, $userUuid, $stepUuid, $date);
        }
    }

    // ========= UPDATE ENTRY ==========
    else if (acceptReply( "updateSchedule", 'lead' ))
    {
        $uuid = getArg("uuid", uuid());
        $userUuid = getArg("userUuid");
        $stepUuid = getArg("stepUuid");
        $date = getArg("date");
        $comment = getArg("comment");

        updateEntry($uuid, $userUuid, $stepUuid, $date, $comment);
    }

    // ========= UPDATE ENTRIES ==========
    else if (acceptReply( "updateSchedules", 'lead' ))
    {
        $entries = getArg("entries", array());

        if (count($entries) == 0)
        {
            $reply["message"] = "Schedule updated.";
            $reply["success"] = true;
        }

        foreach($entries as $entry)
        {
            $uuid = getAttr("uuid", $entry, uuid());
            $userUuid = getAttr("userUuid", $entry);
            $stepUuid = getAttr("stepUuid", $entry);
            $date = getAttr("date", $entry);
            $comment = getAttr("comment", $entry);

            updateEntry($uuid, $userUuid, $stepUuid, $date, $comment);
        }
    }

    // ========= DELETE ENTRY ==========
    else if (acceptReply( "removeSchedule", 'lead' ))
    {
        $uuid = getArg("uuid", uuid());

        deleteEntry( $uuid );
    }

    // =========== DELETE ENTRIES ======
    else if (acceptReply( "removeSchedules", 'lead' ))
    {
        $entries = getArg("entries", array());

        if (count($entries) == 0)
        {
            $reply["message"] = "Schedule updated.";
            $reply["success"] = true;
        }

        foreach($entries as $entry)
        {
            $uuid = getAttr("uuid", $entry );

            deleteEntry( $uuid );
        }
    }

    // ============ SCHEDULE COMMENT ========
    else if (acceptReply( "updateScheduleComment", 'lead'))
    {
        $uuid = getArg("uuid", uuid());
        $projectUuid = getArg("projectUuid");
        $date = getArg("date");
        $comment = getArg("comment");
        $color = getArg("color", "#e3e3e3");

        updateScheduleComment( $uuid, $projectUuid, $date, $comment, $color);
    }

    // ========= UPDATE SCHEDULE COMMENTS ==========
    else if (acceptReply( "updateScheduleComments", 'lead' ))
    {
        $comments = getArg("comments", array());

        if (count($comments) == 0)
        {
            $reply["message"] = "Schedule comments updated.";
            $reply["success"] = true;
        }

        foreach($comments as $c)
        {
            $uuid = getAttr("uuid", $c, uuid());
            $projectUuid = getAttr("projectUuid", $c);
            $date = getAttr("date", $c);
            $comment = getAttr("comment", $c);
            $color = getAttr("color", $c, "#e3e3e3");

            updateScheduleComment( $uuid, $projectUuid, $date, $comment, $color);
        }
    }

?>