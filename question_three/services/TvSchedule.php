<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Description: TV schedule class
 */

class TvSchedule extends ShowRecords
{
    public $searchQuery = [];
    private $queryRecord = [];

    public function __construct($conn)
    {
        parent::__construct($conn);
        $this->queryRecord = $this->getSearchQueryRecord();
    }


    /**
     * Filters the query results based on the provided parameters
     *
     * @param PDO $conn Database connection
     * @param string $target Target table for the query
     * @param DateTime|null $currentWeekDay Current week day for date search
     * @param string|null $filter Filter parameter for query
     * @return array Query results after filtering
     */
    public function filterQueryDB($conn, string $target, $currentWeekDay = null, $filter = null): array
    {

        $this->searchQuery = $this->queryDB($conn, $target, $currentWeekDay, $filter);
        return $this->searchQuery;
    }

    /**
     * Calculates the next date for scheduled shows based on the current week day and filter
     *
     * @param DateTime|null $currentWeekDay Current week day for date search
     * @param string|null $filter Filter parameter for query
     */

    public function getNextDate($currentWeekDay = null, $filter = null)
    {
        $currentShowTime = new DateTime();
        $this->calculateNextDate($currentWeekDay, $currentShowTime, $filter);
    }


    /**
     * Calculates the next date for scheduled shows based on the current week day
     *
     * @param DateTime|null $currentWeekDay The current week day for date search
     */

    private function calculateNextDate($currentWeekDay)
    {
        $currentWeekValue = $currentWeekDay; // Store the date-time input value
        $days_of_the_week = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        $records = !empty($this->queryRecord) ? $this->queryRecord : $this->searchQuery;
        $this->searchQuery = [];

        foreach ($records as &$record) {
            $scheduledWeekDay = $record['week_day'];
            $scheduledShowTimeStr = $record['show_time'];

            // Convert the schedule week day and time to dateTime object
            $ScheduleDateTime = new DateTime('this ' .   $scheduledWeekDay);
            list($hours, $minutes, $seconds) =   explode(':', $scheduledShowTimeStr);
            $ScheduleDateTime->setTime($hours, $minutes, $seconds);


            if ($currentWeekValue !== null) {
                $currentWeekDay = array_search($scheduledWeekDay, $days_of_the_week);

                // Current time has passed on the scheduled day
                $ScheduleDateTime < $currentWeekValue ?
                    $nextDate = 7 - $currentWeekDay + array_search($scheduledWeekDay, $days_of_the_week) :
                    $nextDate = 0;
            } else {

                // Current time hasn't passed or no specific day provided
                array_search($record['week_day'], $days_of_the_week);
                $currentWeekValue = new DateTime();

                $currentWeekDay = array_search($scheduledWeekDay, $days_of_the_week);

                $nextDate = $ScheduleDateTime > $currentWeekValue ?
                    ($currentWeekDay - $currentWeekDay) : ($currentWeekDay + (7 - $currentWeekDay));
            }


            // Clone the current show time
            $newDate = clone $ScheduleDateTime;
            $newDate = $newDate->modify("+$nextDate days")->format("l d/m/Y");

            $record['next_date'] = "$newDate is valid with show time: $scheduledShowTimeStr";

            $this->searchQuery[] = $record;
        }
    }
}
