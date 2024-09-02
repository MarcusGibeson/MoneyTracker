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
                    break;
                case 'earnings':
                    initializeModal('add-earning'); //For create earning modals
                    break;
                case 'expenses':
                    initializeModal('add-expense'); //For create expense modals
                    break;
            }
        })
        .catch(function(error) {
            console.log('Error loading content:', error);
        });
}

document.addEventListener('DOMContentLoaded', function() {
    loadContent('account-overview');

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
