<?php

namespace App\Traits;

trait BusinessDateHelper {

    /**
     * List of public holidays for 2018 and 2019
     * 
     * @var Array
     */
    private $publicHolidays = [
        '2019-01-01', '2019-01-21', '2019-02-18', '2019-05-27', '2019-06-04', '2019-09-02', '2019-10-14', '2019-11-11', '2019-11-28', '2019-12-25',
        '2018-01-01', '2018-01-15', '2018-02-19', '2019-05-28', '2018-06-04', '2018-09-03', '2018-10-08', '2018-11-12', '2018-11-22', '2018-12-25'
    ];

    /**
     * List of weekday names
     * 
     * @var Array
     */
    private $weekDays = ['mon', 'tue', 'wed', 'thu', 'fri'];

    /**
     * List of weekend names
     * 
     * @var Array
     */
    private $weekEnds = ['sat', 'sun'];


    /**
     * Determine of the date is a holiday day
     * 
     * @param   DateTime    $date   The date
     * @return  Boolean             
     */
    public function isHolidayDay($date) {
        $day = $date->format('Y-m-d');
        return in_array($day, $this->publicHolidays);
    }

    /**
     * Determine of the date is a weekend day
     * 
     * @param   DateTime    $date   The date
     * @return  Boolean             
     */
    public function isWeekendDay($date) {
        $day = strtolower($date->format('D'));
        return in_array($day, $this->weekEnds);
    }

    /**
     * Determine of the date is a weekday day
     * 
     * @param   DateTime    $date   The date
     * @return  Boolean             
     */
    public function isWeekDay($date) {
        $day = strtolower($date->format('D'));
        return in_array($day, $this->weekDays);
    }

    /**
     * Determine of the date is a business day
     * 
     * @param   DateTime    $date   The date
     * @return  Boolean             
     */
    public function isBusinessDay($date) {
        return $this->isWeekDay($date) && ($this->isHolidayDay($date) || $this->isWeekendDay($date));
    }
}
