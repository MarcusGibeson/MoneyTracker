function loadContent(fragmentName) {
    var url = fragmentName + '.php';

    fetch(url) 
        .then(function(response) {
            if(!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.text();
        })
        .then(function(html) {
            var contentContainer = document.getElementById('dynamic-content');
            contentContainer.innerHTML = html;

            //Call initialization function based on the loaded fragment
            switch(fragmentName) {
                case 'login':
                    initializeModal('signup'); //For create user modal
                    break;
                case 'account-overview':
                    initializeModal('add-account'); //For create account modals
                    createClickableRows();
                    break;
                case 'earnings':
                    initializeModal('add-earning'); //For create earning modals
                    break;
                case 'expenses':
                    initializeModal('add-expense'); //For create expense modals
                    break;
                case 'work-calendar':
                    initializeModal('add-workday');
                    handleWorkCalendar();
                    addDay();
                    break;
            }
        })
        .catch(function(error) {
            console.log('Error loading content:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    let defaultFragment = 'work-calendar';
    let fragmentName = defaultFragment;
    
    loadContent(fragmentName);

    //handle navigation clicks
    document.querySelectorAll('.navigation-bar a').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            var fragmentName = event.target.closest('a').getAttribute('data-fragment');
            loadContent(fragmentName);

            //change active class
            document.querySelector('.navigation-bar .active')?.classList.remove('active');
            event.target.closest('div').classList.add('active');
        });
    });
});

//Modal initialization
function initializeModal(modalType) {
    var modal = document.getElementById(modalType + '-modal');
    var btn = document.getElementById(modalType + '-link');
    var span = document.getElementsByClassName('close')[0];

    if (!modal || !btn || !span) {
        console.error(`Modal elements for ${modalType} not found.`);
        return;
    }

    //Open the modal
    btn.onclick = function(event) {
        event.preventDefault();
        loadModalContent(modalType);
        modal.style.display = 'block';
    }

    //Close the modal
    span.onclick = function() {
        modal.style.display = 'none';
    }

    //Close the modal if clicked outside
    window.onclick = function(event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
}

//Load the specific modal content
function loadModalContent(modalType) {
    fetch(modalType + '.php')
        .then(function(response) {
            if (!response.ok) {
                throw new Error('Network response was not okay');
            }
            return response.text();
        })
        .then(function(html) {
            var modalContent = document.getElementById(modalType + '-content');
            if (modalContent) {
                modalContent.innerHTML = html;
            } else {
                console.error(`Content container for ${modalType} not found.`);
            }
        })
        .catch(function(error) {
            console.log(`Error loading ${modalType} content:`, error);
        });
}

function createClickableRows() {
    document.querySelectorAll('.account-row span').forEach(function(span) {
        span.addEventListener('click', function() {
            // Remove any existing details rows
            document.querySelectorAll('.account-details').forEach(function(detailsRow) {
                detailsRow.remove();
            });

            // Get the clicked row
            var row = this.closest('.account-row');
            var accountId = row.getAttribute('data-account-id');

            // Create a new row for details
            var detailsRow = document.createElement('tr');
            detailsRow.classList.add('account-details');

            // Create a new cell to span across all columns
            var detailsCell = document.createElement('td');
            detailsCell.setAttribute('colspan', '3');
            detailsCell.textContent = 'Loading details...'; // Placeholder text

            // Append the cell to the details row
            detailsRow.appendChild(detailsCell);

            // Insert the details row after the clicked row
            row.insertAdjacentElement('afterend', detailsRow);

            // Fetch the actual details via AJAX
            fetch(`get_account_details.php?account_id=${accountId}`)
                .then(response => response.text())
                .then(data => {
                    detailsCell.innerHTML = data;
                })
                .catch(error => {
                    console.error('Error fetching account details:', error);
                    detailsCell.textContent = 'Failed to load details.';
                });
        });
    });
}

function addDay() {
            // Clone the first day input set
            const form = document.getElementById('add-workday-form');
            const dayInputs = document.querySelector('.day-inputs');
            const newDay = dayInputs.cloneNode(true);

            // Clear the input values
            newDay.querySelectorAll('input').forEach(input => input.value = '');

            // Append the new day input set
            form.insertBefore(newDay, document.getElementById('add-day-button'));
}

function handleWorkCalendar() {
    let selectedDates = [];


    //Function to toggle day selection
    function attachDayClickListeners(){
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
    }
    
    attachDayClickListeners();


    //On button click, send selected dates to server for calculation
    document.getElementById('calculate-wage-btn').addEventListener('click', function() {
        console.log('Calculate wage button clicked');
        fetch('/MoneyTracker/includes/calculate_wage.inc.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ dates: selectedDates})
        })
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Response data:', data);
            if (data.totalWage != undefined) {
                alert('Net Wage: ' + data.netWage);
            } else {
                alert('Error calculating wage: ' + (data.error || 'Unknown error'));
            }
            
        })
        .catch(error => {
            console.error('Error:', error);
            const errorElement = document.getElementById('error-message');
            errorElement.innerHTML = `<b>Error:</b> ${error.message}`; 

        });
    });

    //On button click, display the details for the selected dates
    document.getElementById('display-details-btn').addEventListener('click', function() {
        console.log(selectedDates);
        if (!selectedDates || selectedDates.length === 0) {
            clearDayDetails();
        } else {
            fetchDayDetails(selectedDates);
        }
    });

    //Add event listeners to previous and next buttons
    document.querySelectorAll('.calendar-navigation a').forEach(function(link) {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const urlParams = new URLSearchParams(event.target.search);
            const year = urlParams.get('year');
            const month = urlParams.get('month');
            loadContent('work-calendar', year, month);
        });
    });

    highlightCurrentDay();
}

function clearDayDetails() {
    const detailsContainer = document.getElementById('selected-day-details');
    detailsContainer.innerHTML = '<p>No dates selected. </p>';
}

function highlightCurrentDay() {
    const today = new Date();
    const year = today.getFullYear();
    const month = today.getMonth() + 1;
    const day = today.getDate();

    //Format the current date as YYYY-MM-DD
    const currentDateString = `${year} - ${month.toString().padStart(2, '0')} - ${day.toString().padStart(2, '0')}`;

    const calendarDays = document.querySelectorAll('.calendar-day');

    calendarDays.forEach(dayElement => {
        if (dayElement.getAttribute('data-date') === currentDateString) {
            dayElement.classList.add('highlight-today');
        }
    });
}



function showWorkInfo(event) {
    const modal = document.getElementById('work-info-modal');
    const cell = event.currentTarget;
    const workTime = cell.getAttribute('data-time');
    const wage = cell.getAttribute('data-wage');

    //Set the modal content
    modal.innerHTML = `Worked: ${workTime}<br>Wage: $${wage}`;

    //Position the modal near the hovered cell
    const rect = cell.getBoundingClientRect();
    modal.style.left = `${rect.left + window.scrollX + 10}px`;
    modal.style.top = `${rect.top + window.scrollY + 30}px`;
    modal.style.display = 'block';
}

function hideWorkInfo() {
    const modal = document.getElementById('work-info-modal');
    modal.style.display = 'none';
}

function deleteWorkEntry(dayId, selectedDates) {
    console.log('Deleting work entry with ID:', dayId);
    if (confirm('Are you sure you want to delete this entry?')) {
        fetch('/MoneyTracker/includes/handle_day_action.inc.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'delete',
                dayId: dayId
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Delete response:', data);
            if (data.status === 'success') {
                alert('Work entry deleted successfully.');
                fetchDayDetails(selectedDates);
            } else {
                alert('Failed to delete work entry: ' + data.message);
            }
        })
        .catch(error => {
            console.error('Error deleting entry:', error);
        });
    }
}

function fetchDayDetails(selectedDates) {
    fetch('/MoneyTracker/includes/handle_day_action.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            action: 'fetch-details',
            selectedDates: selectedDates
        })
    })
    .then(response => {
        console.log('Response Status:', response.status);
        return response.text();
    })
    .then(text => {
        console.log('Response text:', text);
        try {
            const data = JSON.parse(text);
            updateDayDetailsUI(data, selectedDates);
        } catch (e) {
            console.error('Error parsing JSON:', e);
        }
    })
    .catch(error => {
        console.error('Error fetching day details:', error);
    });
}

function updateDayDetailsUI(data, selectedDates) {
    const detailsContainer = document.getElementById('selected-day-details');

    if(!data || !data.details) {
        detailsContainer.innerHTML = '<p>No details available</p>';
        return;
    }

    //Clear previous content
    detailsContainer.innerHTML = '';

    //Create a new table to display details
    const table = document.createElement('table');
    const header = document.createElement('tr');

    //Create and append table headers
    const headers = ['Date','Start Time', 'End Time', 'Break (min)', 'Hourly Rate', 'Overtime Rate'];
    headers.forEach(text => {
        const th = document.createElement('th');
        th.textContent = text;
        header.appendChild(th);
    });
    table.appendChild(header);

    //Append data rows
    data.details.forEach(detail => {
        const row = document.createElement('tr');

        //Create a hidden input field to store the id
        const hiddenIdInput = document.createElement('input');
        hiddenIdInput.type = 'hidden';
        hiddenIdInput.value = detail.id;
        row.appendChild(hiddenIdInput);

        //Create table cells for each value and allow inline editing
        const editableFields = ['work_date', 'start_time', 'end_time', 'break_minutes', 'hourly_rate', 'overtime_rate'];
        editableFields.forEach(field => {
            const td = document.createElement("td");
            const input = document.createElement("input");
            input.type = "text";
            input.value = detail[field];
            input.disabled = true; 
            td.appendChild(input);
            row.appendChild(td);
        });

        //Create a cell for action buttons
        const actionCell = document.createElement('td');

        //Create Edit button
        const editButton = document.createElement('button');
        editButton.textContent = 'Edit';
        editButton.addEventListener('click', () => {
            row.querySelectorAll('input').forEach(input => input.disabled = false);
            editButton.style.display = 'none';
            
            //Create submit button
            const submitButton = document.createElement('button');
            submitButton.textContent = 'Submit';
            submitButton.addEventListener('click', () => {
                saveEditRow(detail.id, row, submitButton, editButton);
            });
            actionCell.appendChild(submitButton);
        });
        actionCell.appendChild(editButton);

        //Create Delete button
        const deleteButton = document.createElement('button');
        deleteButton.textContent = 'Delete';
        deleteButton.addEventListener('click', () => {
            deleteWorkEntry(detail.id, selectedDates);
        });
        actionCell.appendChild(deleteButton);

        row.appendChild(actionCell);

        table.appendChild(row);
    });

    //Append the table to the container
    detailsContainer.appendChild(table);
}

function saveEditRow(dayId, row, submitButton, editButton) {
    const editedData = {};
    const inputs = row.querySelectorAll('input');

    inputs.forEach((input, index) => {
        const field = ['work_date', 'start_time', 'end_time', 'break_minutes', 'hourly_rate', 'overtime_rate'][index];
        editedData[field] = input.value;
    });

    //Send the edited data to the backend
    fetch('/MoneyTracker/includes/handle_day_action.inc.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            action: 'edit',
            dayId: dayId,
            editedData: editedData
        }),
    })
    .then(response => response.json())
    .then(result => {
        if (result.status === 'success') {
            alert ('Row updated successfully!');
            inputs.forEach(input => input.disabled = true); //disables inputs
            submitButton.remove();  //removes submit button
            editButton.style.display = 'inline'; //shows edit button
        } else {
            alert('Failed to update row');
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}