<?php
require_once 'includes/calendar_configurations.inc.php'; 
?>

<link rel="stylesheet" href="css/calendar.css">

<?php
    generate_calendar($year, $month, $worked_days);
?>

<div class="calendar-navigation">
    <a href="?year=<?php echo ($month == 1 ? $year - 1 : $year); ?>&month=<?php echo ($month == 1 ? 12 : $month - 1); ?>">Previous</a> |
    <a href="?year=<?php echo ($month == 12 ? $year + 1 : $year); ?>&month=<?php echo ($month == 12 ? 1 : $month + 1); ?>">Next</a> |
</div>

