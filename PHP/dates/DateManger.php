<?php

/**
 * Class DateManager
 * 
 * A class for managing date-related operations.
 */
class DateManager {
    private $currentDate;

    /**
     * DateManager constructor.
     *
     * @param string $date The initial date. Defaults to 'now'.
     */
    public function __construct($date = 'now') {
        $this->currentDate = new DateTime($date);
    }

    /**
     * Get the current date in the specified format.
     *
     * @return string The formatted current date.
     */
    public function getCurrentDate() {
        return $this->currentDate->format('Y-m-d H:i:s');
    }

    /**
     * Add a specified number of days to the current date.
     *
     * @param int $days The number of days to add.
     */
    public function addDays($days) {
        $this->currentDate->modify("+$days days");
    }

    /**
     * Subtract a specified number of days from the current date.
     *
     * @param int $days The number of days to subtract.
     */
    public function subtractDays($days) {
        $this->currentDate->modify("-$days days");
    }

    /**
     * Format the current date according to the provided format.
     *
     * @param string $format The format to use for the date.
     * @return string The formatted date.
     */
    public function format($format) {
        return $this->currentDate->format($format);
    }

    /**
     * Check if a given date is in the future compared to the current date.
     *
     * @param string $compareDate The date to compare.
     * @return bool True if the compared date is in the future, false otherwise.
     */
    public function isDateInFuture($compareDate) {
        $compareDateTime = new DateTime($compareDate);
        return $this->currentDate < $compareDateTime;
    }
}
