<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Description: The file is responsible for interacting with the homepage
 */

// Include necessary service files
require_once(__DIR__ . '/../../services/TvSchedule.php');
require_once(__DIR__ . '/../../services/ShowRecords.php');

// Instantiate objects for TV series records and TV schedule
$series = new ShowRecords($conn);
$schedule = new TvSchedule($conn);
$notification = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handlePostRequest($schedule, $series, $conn);
} else {
    handleGetRequest($schedule, $series);
}


/**
 * Handles POST requests.
 *
 * @param TvSchedule $schedule - Object for TV schedule
 * @param ShowRecords $series - Object for TV series records
 * @param PDO $conn - Database connection
 */
function handlePostRequest($schedule, $series, $conn)
{
    $dateRange = $_POST['date_range'];
    $datetime = new DateTime($dateRange);
    $currentWeekDay = $datetime;
    $filter = isset($_POST['tv_series']) ? htmlspecialchars($_POST['tv_series']) : null;

    if ($filter && $filter !== "Show all") {
        $notification = "You searched for " . $currentWeekDay->format('d/m/Y H:i:s') . " using the following filter $filter";
        $schedule->filterQueryDB($conn, "filtersearch", $currentWeekDay, $filter);
    } else {
        $notification = "You searched for " . $currentWeekDay->format('d/m/Y H:i:s');
        $schedule->filterQueryDB($conn, "datesearch", $currentWeekDay);
    }

    $schedule->getNextDate($currentWeekDay);
    $allSeries = $schedule->searchQuery;
    renderPage($allSeries, $series, $notification);
}

/**
 * Handles GET requests.
 *
 * @param TvSchedule $schedule - Object for TV schedule
 * @param ShowRecords $series - Object for TV series records
 */
function handleGetRequest($schedule, $series)
{
    $notification = "Schedule generated based on the current date";
    $schedule->getNextDate();
    $allSeries = $schedule->searchQuery;
    renderPage($allSeries, $series, $notification);
}
