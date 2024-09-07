<?php
require_once 'includes/configurations/calendar_configurations.inc.php'; 
?>

<link rel="stylesheet" href="css/calendar.css">

<button id="add-workday-link">Add new work day</button>

<div class="calendar-navigation">
    <a href="?year=<?php echo ($month == 1 ? $year - 1 : $year); ?>&month=<?php echo ($month == 1 ? 12 : $month - 1); ?>">Previous</a> |
    <a href="?year=<?php echo ($month == 12 ? $year + 1 : $year); ?>&month=<?php echo ($month == 12 ? 1 : $month + 1); ?>">Next</a> |
</div>

<div class="calendar">
    <?php generate_calendar($year, $month, $worked_days); ?>
</div>

<button id="calculate-wage-btn">Calculate Wage for Selected Days</button>

<!-- Modal Structure -->
<div id="add-workday-modal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <div id="add-workday-content">
            <!-- Add work day form will be loaded here -->
        </div>
    </div>
</div>

<script>
    let selectedDates = [];

    //Function to toggle day selection
    document.querySelectorAll('.calendar-day').forEach(day => {
        day.addEventListener('click', function() {
            const date = this.getAttribute('data-date');

            if(selectedDates.includes(date)) {
                selectedDates = selectedDates.filter(d => d != date); //Remove if already selected
                this.classList.remove('selected-day');
            } else {
                selectedDates.push(date); //Add to selected dates
                this.classList.add('selected-day');
            }
        });
    });

    //On button click, send selected dates to server for calculation
    document.getElementById('calculate-wage-btn').addEventListener('click', function() {
        fetch('calculate_wage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'applications/json',
            },
            body: JSON.stringify({ dates: selectedDates})
        })
        .then(response => response.json())
        .then(data => {
            alert('Total Wage: ' + data.totalWage);
        })
        .catch(error => console.error('Error:', error));
    });
</script>

<style>
    .selected-day {
        border: 2px solid green;
    }
</style>

<?php
require_once 'calendar-form.php';
?>