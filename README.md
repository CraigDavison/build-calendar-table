# build-calendar-table
This short PHP script will populate a table in a mySQL database with calendar values.
It will populate the table with following informatino; 
  Date (yyyy-mm-dd)
  year (yyyy
  month (numeric 1-12)
  Day of week (1 Mon - 7 Sun)
  Day of week (Mon - Sun)
  Day of month (1-31)
  Day of year (1-365)
  nth *day of month (i.e 1st Monday, 3rd Friday, 4th Tuesday)
  Is Leap year (if year is a leap year then 1 else 0)
  Is Weekday (if yes then 1 else 0)
  Week number starting Monday (1 - 52)

It requires a start and end date. The start date is derived by querying the table to 
see if it has already been populated and uses the last date as reference point for the
start date. Currently the end date is set for 2099-12-31 but of course you can change
this.
