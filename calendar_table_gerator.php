<?php
date_default_timezone_set('Europe/London');
set_time_limit (360000);

// this is the authors initialisation script which will be used to establish database connection.
// You will have your own way of doing this.
include_once '../core/init.php';

// Authors method for connecting to DB
$db = new DB();
// Table to update
$table = 'tbl_calendar';

// Start date (comment out as required). The first option is a defined by you.
// The second method finds the last date in the table.
$Start = '2036-10-07';
$Start = $db->query('SELECT max(`cal_date`) AS s_date FROM `tbl_calendar`')->results()[0]->s_date;

$date = new DateTime($Start); 
// Regardless of which method you used to define your start date
// the following adds 1 day to that, if you used the query method you need to keep this
// if you defined it yourself you can remove it or define the start as one day earlier
$date = $date->add(new DateInterval('P1D'));

// End date will either be static or x days after start date

// static end date
$end = new DateTime('2099-12-31');

// x days after start
//$days = 3650; // 10 years
//$end = $end->add(new DateInterval('P'.$days.'D'));

// loop through each day from the start date until is equal to the end date
while( $date != $end) {

    // nthOfDay = for example 1st Monday of Month 2nd Friday of Month or 3rd Sunday
    // To do this I establis the current day (Mon, Tue etc) and then query the table to find 
    // out how many times that day has occured in the current month of the current year.
    $year = $date->format('Y');
    $month = $date->format('n');
    $day = $date->format('N');
    $Date = $date->format('Y-m-d');
    
    // SQL required for above query
    $sql = 'SELECT * FROM tbl_calendar WHERE cal_year = ? AND cal_month = ? AND cal_day_of_week = ? AND cal_date < ?';
    
    // Query database. How you query the database may be different. I have not included all the code that I use for 
    // doing that as we all have different method but all I am doing here is using a prepared statement to query the
    // database to establish how many times the current day has occured in the current month.
    $nthOfDay = $db->query($sql,array($year,$month,$day,$Date))->count() + 1;
    
    // This array is used by my insert function and holds all the field names and values to be inserted
    $fields = array('cal_date' => $date->format('Y-m-d'),
                    'cal_year' => $date->format('Y'),
                    'cal_isLeapYear' => $date->format('L'),
                    'cal_day_of_week_string' => $date->format('D'),
                    'cal_day_of_month' => $date->format('d'),
                    'cal_day_of_week' => $date->format('N'),
                    'cal_month' => $date->format('n'),
                    'cal_week' => $date->format('W'),
                    'cal_day_of_year' => $date->format('z')+1,
                    'cal_is_weekday' => ($date->format('N') < 6 ? 1 : 0),
                    'cal_x_weekday_of_month' => $nthOfDay);  
    
    // Again this is my method of interacting with the database but all it is doing is inserting all the
    // the values in the the table.... you will likely have your own method of doing this. 
    $insert = $db->insert($table, $fields);

    $date = $date->add(new DateInterval('P1D'));    
 } // end of while loop

echo 'done!';
