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
                    addDay();
                    break;
            }
        })
        .catch(function(error) {
            console.log('Error loading content:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    loadContent('work-calendar');

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
