
// JavaScript to toggle visibility of reclamation section
/*document.querySelector('.navigation li:nth-child(4) a').addEventListener('click', function (event) {
    event.preventDefault();
    document.querySelector('.details').style.display = 'none';
    document.querySelector('.reclamation-section').style.display = 'block';
    document.getElementById('facture-section').style.display = 'none';
});

// JavaScript to toggle visibility of sections when clicking on links
document.querySelector('.navigation li:nth-child(3) a').addEventListener('click', function(event) {
event.preventDefault();
document.getElementById('facture-section').style.display = 'block';
document.getElementById('client-section').style.display = 'none';
document.querySelector('.reclamation-section').style.display = 'none';
// Hide other sections if needed
});

document.querySelector('.navigation li:nth-child(2) a').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default behavior
    event.stopPropagation(); // Stop event propagation
    document.getElementById('client-section').style.display = 'block'; // Display client section
    document.querySelector('.reclamation-section').style.display = 'none'; // Hide other sections if needed
    document.getElementById('facture-section').style.display = 'none';
});

// Add similar event listeners for other navigation links

// JavaScript function to load Consommation annuelle section
function loadConsommationAnnuelle() {
    window.location.href = "#consommationAnnuelle";
}
*/
document.querySelectorAll('.edit-consommation-trigger').forEach(trigger => {
trigger.addEventListener('click', function(event) {
    event.preventDefault();
    const container = this.closest('tr').querySelector('.consommation-container');
    const textSpan = container.querySelector('.consommation-text');
    const editForm = container.querySelector('.edit-consommation-form');

    textSpan.style.display = 'none'; // Hide the text
    editForm.style.display = 'inline-block'; // Show the edit form
});
});

document.addEventListener('DOMContentLoaded', function() {
// Get all edit consommation forms
const editForms = document.querySelectorAll('.edit-consommation-form');

// Add event listener to each edit consommation form
editForms.forEach(form => {
    form.addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent default form submission
        const formData = new FormData(form); // Create FormData object from form
        fetch(window.location.href, { // Submit form data to the current page
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log the response from the server
            // You can add further handling here if needed
            location.reload(); // Reload the page after successful update
        })
        .catch(error => {
            console.error('Error:', error); // Log any errors
        });
    });
});
});
/*
// JavaScript function to toggle visibility of Consommation annuelle section
function toggleConsommationAnnuelle() {
var consommationAnnuelleSection = document.getElementById("consommationAnnuelle");
if (consommationAnnuelleSection.style.display === "none") {
    consommationAnnuelleSection.style.display = "block";
} else {
    consommationAnnuelleSection.style.display = "none";
}
}
document.addEventListener('DOMContentLoaded', function() {
    // Get all navigation menu items
    const menuItems = document.querySelectorAll('.navigation li a');

    // Add event listener to each menu item
    menuItems.forEach(item => {
        item.addEventListener('click', function(event) {
            event.preventDefault();

            // Hide all sections
            document.querySelector('.reclamation-section').style.display = 'none';
            document.getElementById('client-section').style.display = 'none';
            document.getElementById('facture-section').style.display = 'none';
            document.getElementById('consommationAnnuelle').style.display = 'none';

            // Determine which section to display based on the clicked menu item
            if (item.getAttribute('href') === '#reclamation-section') {
                document.querySelector('.reclamation-section').style.display = 'block';
            } else if (item.getAttribute('href') === '#client-section') {
                document.getElementById('client-section').style.display = 'block';
            } else if (item.getAttribute('href') === '#facture-section') {
                document.getElementById('facture-section').style.display = 'block';
            } else if (item.getAttribute('href') === '#consommationAnnuelle') {
                document.getElementById('consommationAnnuelle').style.display = 'block';
            }
        });
    });
});

function toggleConsommationAnnuelle() {
    var consommationAnnuelleSection = document.getElementById("consommationAnnuelle");
    if (consommationAnnuelleSection.style.display === "none") {
        consommationAnnuelleSection.style.display = "block";
    } else {
        consommationAnnuelleSection.style.display = "none";
    }
}

document.addEventListener('DOMContentLoaded', function() {
const searchInput = document.querySelector('.search input');
const tableRows = document.querySelectorAll('#client-section table tbody tr');

searchInput.addEventListener('input', function() {
    const searchText = this.value.trim().toLowerCase();

    tableRows.forEach(row => {
        const cells = row.querySelectorAll('td');
        let found = false;

        cells.forEach(cell => {
            if (cell.textContent.trim().toLowerCase().includes(searchText)) {
                found = true;
            }
        });

        if (found) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
});
});*/
/*function toggleUserSection() {
var clientSection = document.getElementById("client-section");
var clientCountDiv = document.getElementById("clientCount");
var clientCard = document.getElementById("clientCard");
var cardBox = document.querySelector('.cardBox');

if (clientSection.style.display === "none") {
// Fetch the client count dynamically
fetch('fetch_stat.php')
    .then(response => response.text())
    .then(data => {
        clientCountDiv.innerHTML = data; // Update client count
        clientSection.style.display = 'block'; // Show client section
        cardBox.style.display = 'none'; // Hide existing cards
        // Change icon for clients card
        clientCard.querySelector('.iconBx ion-icon').setAttribute('name', 'people-outline');
    })
    .catch(error => {
        console.error('Error fetching client count:', error);
    });
} else {
clientSection.style.display = "none";
cardBox.style.display = 'flex'; // Show existing cards
// Change back icon for clients card
clientCard.querySelector('.iconBx ion-icon').setAttribute('name', 'chatbubbles-outline');
}
}

// Function to execute when the Dashboard link is clicked
document.querySelector('.navigation li:nth-child(2) a').addEventListener('click', function(event) {
event.preventDefault(); // Prevent default behavior
toggleDashboard(); // Toggle the visibility of the Dashboard section
});

// Function to execute when the page loads
document.addEventListener('DOMContentLoaded', function() {
// Check if the current URL contains the Dashboard section's hash
if (window.location.hash === "#dashboard-section") {
    toggleDashboard(); // Display the Dashboard section if the hash is present
}
else {
    // Hide other sections if needed
    document.querySelector('.reclamation-section').style.display = 'none';
    document.getElementById('client-section').style.display = 'none';
    document.getElementById('facture-section').style.display = 'none';
    document.getElementById('consommationAnnuelle').style.display = 'none';
}
});

function toggleDashboard() {
var cardBox = document.querySelector('.cardBox');
if (cardBox.style.display === "none") {
    cardBox.style.display = "flex"; // Display the card box
} else {
    cardBox.style.display = "none"; // Hide the card box
}
}*/
document.querySelectorAll('.traiter-link').forEach(link => {
    link.addEventListener('click', function(event) {
        event.preventDefault();
        const row = this.closest('tr');
        const responseForm = row.querySelector('.response-form');
        responseForm.style.display = 'block';
    });
});
