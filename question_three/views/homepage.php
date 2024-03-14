<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Description: Parent file for the homepage. Do not directly extend on this page.
 */

// Include home controller for interacting with the layout
include(__DIR__ . '/includes/homeController.php');

function renderPage($allSeries, $series, $notification)
{
    // Html Layout File
    include(__DIR__ . '/includes/Layout.php');
}
