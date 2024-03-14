<?php

/**
 * Author: Chidi E. E
 * Company: Exads Interview
 * 
 * Description: Queries the database for records on TV series and TV series intervals
 */

class ShowRecords
{
    /**
     * @var array $tvSeriesNames - Array to store TV series names
     * @var array $searchQueryRecord - Array to store search query results
     */
    public $tvSeriesNames = [];
    protected $searchQueryRecord = [];


    /**
     * Constructor function
     * @param PDO $conn - Database connection
     */
    public function __construct($conn)
    {
        $this->tvSeriesNames = $this->getTvSeries($conn);

        // Show the default next date based on the current date
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->setSearchQuery($conn, "tv_series_intervals");
        }
    }

    /**
     * Get the search query record
     * @return array - Search query record
     */
    protected function getSearchQueryRecord(): array
    {
        return $this->searchQueryRecord;
    }


    /**
     * Handles the search for the next date
     *
     * @param PDO $conn - Database connection
     * @param string $target - Target table for the search query
     */
    protected function setSearchQuery($conn, $target)
    {
        $this->searchQueryRecord = $this->getSearchQuery($conn, $target);
    }


    /**
     * Retrieves TV series names from the database
     *
     * @param PDO $conn - Database connection
     * @return array - TV series names
     */
    private function getTvSeries($conn): array
    {
        return $this->queryDB($conn, 'tv_series');
    }


    /**
     * Executes the search query and retrieves results from the database
     *
     * @param PDO $conn - Database connection
     * @param string $target - Target table for the search query
     * @return array - Query results
     */
    private function getSearchQuery($conn, $target): array
    {
        return $this->queryDB($conn, $target);
    }


    /**
     * Executes a database query
     *
     * @param PDO $conn - Database connection
     * @param string $target - Target table for the query
     * @param DateTime|null $currentWeekDay - Current week day for date search
     * @param string|null $filter - Filter parameter for query
     * @return array - Query results
     */
    protected function queryDB($conn, $target, $currentWeekDay = null, $filter = null): array
    {
        return match ($target) {
            'tv_series' => $this->connectToDatabase($conn, "SELECT * FROM $target"),
            'tv_series_intervals' => $this->connectToDatabase($conn, "SELECT tvs.title, tvsi.week_day, tvsi.show_time
                                                    FROM tv_series_intervals AS tvsi
                                                    JOIN tv_series AS tvs ON tvsi.id_tv_series = tvs.id;"),
            'datesearch' => $this->connectToDatabase($conn, "SELECT tvs.title, tvsi.week_day, tvsi.show_time
                                            FROM tv_series_intervals AS tvsi
                                            JOIN tv_series AS tvs ON tvsi.id_tv_series = tvs.id
                                            WHERE week_day = :currentWeekDay ;", $currentWeekDay),
            'filtersearch' => $this->connectToDatabase($conn, "SELECT tvs.title, tvsi.week_day, tvsi.show_time
                                            FROM tv_series_intervals AS tvsi
                                            JOIN tv_series AS tvs ON tvsi.id_tv_series = tvs.id
                                            WHERE week_day = :currentWeekDay AND title = :filter;", $currentWeekDay, $filter),
            default => throw new \Exception('Unsupported table'),
        };
    }


    /**
     * Connects to the database and executes the query
     *
     * @param PDO $conn - Database connection
     * @param string $query - SQL query
     * @param DateTime|null $currentWeekDay - Current week day for date search
     * @param string|null $filter - Filter parameter for query
     * @return array - Query results
     */
    private function connectToDatabase($conn, $query, $currentWeekDay = null, $filter = null): array
    {
        $stmt = $conn->prepare($query);

        if (!is_null($currentWeekDay)) {
            $stmt->bindValue(':currentWeekDay', $currentWeekDay->format('l'));
        }

        if (!is_null($filter)) {
            $stmt->bindValue(':filter', $filter);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
