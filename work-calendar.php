<?php
require_once 'includes/configurations/calendar_configurations.inc.php'; 
?>

<link rel="stylesheet" href="css/calendar.css">

<button id="add-workday-link">Add new work day</button>

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

<style>
</style>
