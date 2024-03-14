<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Description: Main project file responsible for including core files and initializing the application.
 */


// This is only required for first installation,
// and should be deleted or commented out after that 
require_once(__DIR__ . '/install/install.php');


// Include core files
require_once(__DIR__ . '/services/ShowRecords.php');
require_once(__DIR__ . '/services/TvSchedule.php');
require_once(__DIR__ . '/db/connection.php');

// Include homepage view
include(__DIR__ . '/views/homepage.php');
