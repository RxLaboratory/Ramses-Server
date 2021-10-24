<?php
    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 20202-2021 Nicolas Dufresne and Contributors.

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

	// ========= CREATE STEP ==========
	if ( acceptReply("createTemplateStep", 'admin') )
	{
		$name = getArg("name");
		$shortName = getArg("shortName");
		$uuid = getArg("uuid", uuid());

		$q = new DBQuery();
		$q->insert( "templatesteps", array( 'name', 'shortName', 'uuid' ));

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "uuid", $uuid, true );

		$q->execute("Template Step '{$shortName}' added.");
		$q->close();
	}

	// ========= GET STEPS ==========
	else if (acceptReply("getTemplateSteps") || hasArg("init"))
	{
		$q = new DBQuery();
        $steps = $q->getAll("templatesteps",
			array(
				'name',
				'shortName',
				'uuid',
				'type',
				'comment',
				'color',
				'estimationMethod',
				'estimationVeryEasy',
				'estimationEasy',
				'estimationMedium',
				'estimationHard',
				'estimationVeryHard'
			),
			array(
				'shortName',
				'name'
			)
		);

		// Adjust values
		for ($s = 0; $s < count($steps); $s++)
		{
			$steps[$s]['estimationVeryEasy'] = (float)$steps[$s]['estimationVeryEasy'];
			$steps[$s]['estimationEasy'] = (float)$steps[$s]['estimationEasy'];
			$steps[$s]['estimationMedium'] = (float)$steps[$s]['estimationMedium'];
			$steps[$s]['estimationHard'] = (float)$steps[$s]['estimationHard'];
			$steps[$s]['estimationVeryHard'] = (float)$steps[$s]['estimationVeryHard'];
		}

		if (hasArg("init") )
        {
            $reply["content"]["templateSteps"] = $steps;
        }
        else 
        {
            $reply["content"] = $steps;
            $reply["message"] = "Template Step list retreived";
            $reply["success"] = true;
        }
	}

	// ========= UPDATE STEP ==========
	else if (acceptReply("updateTemplateStep", 'admin'))
	{
		$name = getArg( "name" );
		$shortName = getArg( "shortName" );
		$uuid = getArg( "uuid" );
		$type = getArg( "type" );
		$comment = getArg( "comment" );
		$color = getArg( "color" );

		$q = new DBQuery();
		$q->update(
			"templatesteps",
			array(
				'name',
				'shortName',
				'comment',
				'type',
				'color'
			),
			$uuid
		);

		$q->bindName( $name );
		$q->bindShortName( $shortName );
		$q->bindStr( "comment", $comment );
		$q->bindStr( "type", $type );
		$q->bindStr( "color", $color );

		$q->execute("Template Step \"{$shortName}\" updated.");
		$q->close();			
	}

	// =========== STEP ESTIMATIONS ==========
	else if ( acceptReply("setTemplateStepEstimations", 'admin') )
	{
		$uuid = getArg( "uuid" );
		$method = getArg( "method", "shot" );
		$veryEasy = getArg( "veryEasy", "0.2" );
		$easy = getArg( "easy", "0.5" );
		$medium = getArg( "medium", "1" );
		$hard = getArg( "hard", "2" );
		$veryHard = getArg( "veryHard", "3" );

		$q = new DBQuery();
		$q->update(
			"templatesteps",
			array(
				'estimationMethod',
				'estimationVeryEasy',
				'estimationEasy',
				'estimationMedium',
				'estimationHard',
				'estimationVeryHard'
			),
			$uuid
		);

		$q->bindStr( "estimationMethod", $method );
		$q->bindStr( "estimationVeryEasy", $veryEasy );
		$q->bindStr( "estimationEasy", $easy );
		$q->bindStr( "estimationMedium", $medium );
		$q->bindStr( "estimationHard", $hard );
		$q->bindStr( "estimationVeryHard", $veryHard );

		$q->execute("Template Step estimations updated.");
		$q->close();
	}

	// ========= REMOVE STEP ==========
	else if (acceptReply("removeTemplateStep", 'admin'))
	{
		$uuid = getArg("uuid");
		$q = new DBQuery();
		$q->remove( "templatesteps", $uuid );
	}
?>
