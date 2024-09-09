<?php


function generate_calendar($year, $month, $worked_days) {
    // Ensure $worked_days is an array
    if (!is_array($worked_days)) {
        $worked_days = [];
    }

    //Prepare a map of worked days with work time and hourly_rate
    $worked_days_map = [];
    foreach($worked_days as $work_data) {
        if (!isset($work_data['work_date'])) {
            throw new Exception("work_date missing from data");
        }
        $currentDate = $work_data['work_date'];
        $worked_days_map[$currentDate] = [
            'time' => $work_data['time_worked'],
            'hourly_rate' => $work_data['hourly_rate']
        ];
    }

    //Days of the week
    $daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    $firstDayOfMonth = mktime(0,0,0, $month, 1, $year);
    $numberOfDays = date('t', $firstDayOfMonth);
    $dateComponents = getdate($firstDayOfMonth);
    $monthName = $dateComponents['month'];
    $dayOfWeek = $dateComponents['wday'];

    //Create the calender header
    echo "<h2>$monthName $year </h2>";
    echo "<table class='calendar'>";
    echo "<tr>";
    foreach($daysOfWeek as $day) {
        echo "<th>$day</th>";
    }
    echo "</tr><tr>";

    //Fill in the days of the week before the first day of the month
    if ($dayOfWeek > 0) {
        for ($i = 0; $i < $dayOfWeek; $i++) {
            echo "<td></td>";
        }
    }

    $currentDay = 1;

    //Populate calendar with the days of the month
    while ($currentDay <= $numberOfDays) {
        if ($dayOfWeek == 7) {
            $dayOfWeek = 0;
            echo "</tr><tr>";
        }

        //Format current date as YYYY-MM-DD
        $currentDate = sprintf("%04d-%02d-%02d", $year, $month, $currentDay);

        //Highlight worked days and show times
        if (isset($worked_days_map[$currentDate])) {
            $work_data = $worked_days_map[$currentDate];
            $work_time = $work_data['time'];
            $hourly_rate = $work_data['hourly_rate'];
            echo "<td class='calendar-day worked-day' 
                        data-date='$currentDate'
                        data-time='$work_time'
                        data-wage='$hourly_rate'
                        onmouseover='showWorkInfo(event)'
                        onmouseout='hideWorkInfo()'
                    >";
            echo "$currentDay";
            echo "</td>";
        } else {
            echo "<td class='calendar-day' data-date='$currentDate'>$currentDay</td>";
        }

        $currentDay++;
        $dayOfWeek++;
    }

    //Fill in the remaining cells of the last week
    if ($dayOfWeek != 7) {
        $remainingDays = 7 - $dayOfWeek;
        for ($i = 0; $i < $remainingDays; $i++) {
            echo "<td></td>";
        }
    }

    echo "</tr>";
    echo "</table>";
}

//Determine the current month and year
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');

//Add navigation to previous and next months
echo "<div class='calendar-navigation'>";
echo "<a href='?year=" . ($month == 1 ? $year - 1 : $year) . "&month=". ($month == 1 ? 12 : $month - 1) . "'>Previous</a> |";
echo "<a href='?year=" . ($month == 12 ? $year + 1 : $year) . "&month=" . ($month == 12 ? 1 : $month + 1) . "'>Next</a>";
echo "</div>";

//Generate the calendar
generate_calendar($year, $month, $worked_days);

