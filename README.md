# build-calendar-table
<p>This short PHP script will populate a table in a mySQL database with calendar values.
It will populate the table with following informatino; </p>
  <ul>
  <li>Date (yyyy-mm-dd)</li>
  <li>Year (yyyy)</li>
  <li>Month (numeric 1-12)</li>
  <li>Day of week (1 Mon - 7 Sun)</li>
  <li>Day of week (Mon - Sun)</li>
  <li>Day of month (1-31)</li>
  <li>Day of year (1-365)</li>
  <li>nth *day of month (i.e 1st Monday, 3rd Friday, 4th Tuesday)</li>
  <li>Is Leap year (if year is a leap year then 1 else 0)</li>
  <li>Is Weekday (if yes then 1 else 0)</li>
  <li>Week number starting Monday (1 - 52)</li>
  </ul>
<p>It requires a start and end date. The start date is derived by querying the table to 
see if it has already been populated and uses the last date as reference point for the
start date. Currently the end date is set for 2099-12-31 but of course you can change
this.</p>
