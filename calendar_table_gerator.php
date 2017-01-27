<?php
date_default_timezone_set('Europe/London');
set_time_limit (360000);
include_once '../core/init.php';
$db = new DB();
$table = 'tbl_dates_calendar';
//$days = 36500;

$createSQL = 'CREATE TABLE '.$table.' (
               cal_date date NOT NULL,
               cal_year int(4) NOT NULL,
               cal_isLeapYear int(1) NOT NULL,
               cal_day_of_week_string varchar(10) COLLATE utf8_unicode_ci NOT NULL,
               cal_day_of_month int(2) NOT NULL,
               cal_day_of_week int(2) NOT NULL,
               cal_month int(2) NOT NULL,
               cal_week int(2) NOT NULL,
               cal_day_of_year int(3) NOT NULL,
               cal_is_weekday int(1) NOT NULL,
               cal_x_weekday_of_month int(2) NOT NULL
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci';
// Check to see if table exists
$sql = 'SHOW TABLES LIKE "'.$table.'"';


// count the number of tables returned with the table name
$tableExists = $db->query($sql)->count();
// if is 0 then table does not exist
if(!$tableExists) {
  $createTable = $db->query($createSQL);
}
// check table was created and if populate table.
if($db->query($sql)){
  if(!isset($_GET['start'])){
    $start = $db->query('SELECT max(cal_date) AS s_date FROM '.$table)->results()[0]->s_date;
    if(!$start){
      $start = date('Y-m-d',time());
    }
  } else {
    $start = $_GET['start'];
  }
  $date = new DateTime($start);
  $date = $date->add(new DateInterval('P1D'));
  if(!isset($_GET['end'])){
    $end = new DateTime('2050-12-31');
  } else {
    $end = $_GET['end'];
  }
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
      $sql = 'SELECT * FROM '.$table.' WHERE cal_year = ? AND cal_month = ? AND cal_day_of_week = ? AND cal_date < ?';
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
  echo 'Complete!';
} else {
  echo 'table does not exist and it could not be created';
}
?>
