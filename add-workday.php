
<h2>Work Calendar</h2>
    <form id="add-workday-form" method="post" action="includes/add_workday.inc.php">
        <div class="day-inputs">
            <label for="date">Date:</label>
            <input type="date" name="work_date[]" required>

            <label for="start_time">Start Time:</label>
            <input type="time" name="start_time[]" required>

            <label for="end_time">End Time:</label>
            <input type="time" name="end_time[]" required>

            <label for="break_minutes">Break Minutes:</label>
            <input type="number" name="break_minutes[]" min="0" required>
        </div>

        <div>
            <label for="hourly_rate">Hourly Rate ($):</label>
            <input type="number" step="0.01" name="hourly_rate" required>
        </div>

        <div>
            <label for="overtime_rate">Overtime Rate ($):</label>
            <input type="number" step="0.01" name="overtime_rate" required>
        </div>

        <button type="button" id="add-day-button" onclick="addDay()">Add Another Day</button>
        <button type="submit">Add work day</button>
    </form>
