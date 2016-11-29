<?php
date_default_timezone_set('Europe/London');
set_time_limit (360000);
include_once '../core/init.php';
$db = new DB();
//$days = 36500;
$Start = '2036-10-07';
$Start = $db->query('SELECT max(`cal_date`) AS s_date FROM `tbl_calendar`')->results()[0]->s_date;
$table = 'tbl_calendar';

$date = new DateTime($Start); 
$date = $date->add(new DateInterval('P1D'));
$end = new DateTime('2099-12-31'); 
//$end = $end->add(new DateInterval('P'.$days.'D'));

$y = 0;
while( $date != $end) {
    if($date->format('Y') != $y){
        echo $date->format('Y');
    }
    
    $year = $date->format('Y');
    $month = $date->format('n');
    $day = $date->format('N');
    $Date = $date->format('Y-m-d');
    $sql = 'SELECT * FROM tbl_calendar WHERE cal_year = ? AND cal_month = ? AND cal_day_of_week = ? AND cal_date < ?';
    // nthOfDay = for example 1st Monday of Month 2nd Friday of Month or 3rd Sunday
    // To do this I establis the current day and then query the table to find 
    // out how many times that day has occured in the current month
    $nthOfDay = $db->query($sql,array($year,$month,$day,$Date))->count() + 1;
    
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
    
    $insert = $db->insert($table, $fields);

    $date = $date->add(new DateInterval('P1D'));
    $y = $date->format('Y');
    
 }
echo 'done!';
?>