<?php 
    // If this file is called directly, abort.
    if (!defined('RAMROOT')) die;

    /*
		Ramses: Rx Asset Management System
        
        This program is licensed under the GNU General Public License.

        Copyright (C) 2020-2024 Nicolas Dufresne and Contributors.

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

    use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;

    require_once(RAMROOT.'/include/PHPMailer/Exception.php');
    require_once(RAMROOT.'/include/PHPMailer/PHPMailer.php');
    require_once(RAMROOT.'/include/PHPMailer/SMTP.php');

    // Edit this configuration file before running the install script at /install/index.php

    // ==== E-MAIL SETTINGS ====

    // The admin email where the administrator 
    // will receive maintenance and warning emails
    define('EMAIL_ADMIN', 'admin@example.com');
    // The address which will appear in the 'From' header
    // of the emaoils sent to users and administrator
    define('EMAIL_FROM', 'ramses@example.com');
    // The address which will appear in the 'Reply to' header
    // of the emaoils sent to users and administrator
    define('EMAIL_REPLYTO', 'admin@example.com');

	// Set this to true if you have
	// issues sending emails with the 
	// server's sendmail program,
	// or if sendmail is not available
	// on your server
	$useSMTP = false;

	// If $useSMTP is true,
	// Set the SMTP settings here

	// SMTP host name
	$SMTPHost = 'smtp.example.com';
	// SMTP needs authentication
	$SMTPAuth = true;
	// SMTP username
	$SMTPUsername = 'duduf@example.com';
	// SMTP password
	$SMTPPassword = 'password';
    // SMTP security layer,
    // PHPMailer::ENCRYPTION_SMTPS (=SSL/TLS)
    // or
    // PHPMailer::ENCRYPTION_STARTTLS
	$SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    // SMTP Port
	$SMTPPort = 465;