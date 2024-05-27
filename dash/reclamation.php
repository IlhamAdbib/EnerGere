<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <!-- ======= Styles ====== --> <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/table.css">
   
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>
 table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 20px;
    margin-left:80px;
    position: relative;
}

th, td {
    border: 1px solid #ddd;
    padding: 8px;
    text-align: left;
}

th {
    background-color: #f2f2f2;
}
        .table-container {
            margin: 0 auto; /* Center align the container */
            max-width: 800px; /* Limit the maximum width of the container */
        }

        .button-container {
            text-align: center; /* Center align the buttons */
            margin-top: 20px; /* Add some top margin */
        }
.pagination a:hover {
    background-color: #ddd;
}

    .pagination-container {
        text-align: center;
        margin-top: 20px;
    }

    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 0;
        border-radius: 4px;
    }

    .pagination li {
        display: inline;
        margin: 0 2px;
    }

    .pagination a {
        display: inline-block;
        padding: 8px 16px;
        background-color: #f2f2f2;
        color: #333;
        border-radius: 4px;
        text-decoration: none;
        transition: background-color 0.3s;
    }



</style>

</head>

<body>
    <!-- =============== Navigation ================ -->
    <div class="container">
        <div class="navigation">
            <ul>
                <li>
                    <a href="#">
                        <span class="icon">
                            <ion-icon name="flash"></ion-icon>
                        </span>
                        <span class="title">EnerGère</span>
                    </a>
                </li>
                 <li>
            <a href="Dashboard.php">
                <span class="icon">
                    <ion-icon name="stats-chart-outline"></ion-icon>
                </span>
                <span class="title">Dashboard</span>
            </a>
        </li>

                 <li>
                <a href="client.php" >
                    <span class="icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </span>
                    <span class="title">Client</span>
                </a>
            </li>

                <li>
                    <a href="facture.php">
                        <span class="icon">
                            <ion-icon name="receipt-outline"></ion-icon>
                        </span>
                        <span class="title">Espace Facture</span>
                    </a>
                </li>
                

                <li>
                    <a href="reclamation.php">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span class="title">Espace Réclamation</span>
                    </a>
                </li>

                <li>
    <a href="consommation_annuelle.php">
        <span class="icon">
            <ion-icon name="stats-chart-outline"></ion-icon>
        </span>
        <span class="title">Consommation annuelle</span>
    </a>
</li>


<li>
                    <a href="logout.php">
                        <span class="icon">
                            <ion-icon name="log-out-outline"></ion-icon>
                        </span>
                        <span class="title">Sign Out</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- ========================= Main ==================== -->
        <div class="main">
        <div class="topbar">
    <div class="toggle">
        <ion-icon name="menu-outline"></ion-icon>
    </div>
    
    <div class="search">
        <label>
            <input type="text" placeholder="Search here">
            <ion-icon name="search-outline"></ion-icon>
        </label>
    </div>

    <div class="user" id="userIcon">
        <ion-icon name="person-outline" onclick="toggleLogout()"></ion-icon>
    </div>

    <div class="user" id="logoutButton" style="display: none;">
        <button onclick="logout()">Log Out</button>
    </div>
</div>

<script>
    function toggleLogout() {
        var userIcon = document.getElementById("userIcon");
        var logoutButton = document.getElementById("logoutButton");

        if (userIcon.style.display === "block") {
            userIcon.style.display = "none";
            logoutButton.style.display = "block";
        } else {
            userIcon.style.display = "block";
            logoutButton.style.display = "none";
        }
    }

    function logout() {
        // Add logout functionality here
        // For example, redirect to the logout page
        window.location.href = "logout.php";
    }
</script>


                      



            <!-- ================ Client Details List ================= -->
<div class="details" id="client-section">

    <?php
    
// Include database connection
include '../connection.php';

// Define variables for pagination
$recordsPerPage = 10; // Number of records to display per page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1; // Current page number

// Calculate the limit for the SQL query
$offset = ($page - 1) * $recordsPerPage;

// Query to fetch reclamations data with pagination
$sql = "SELECT * FROM reclamation WHERE status_rec = 'non traité' LIMIT $offset, $recordsPerPage";

// Execute the query
$result = $pdo->query($sql);

// Check if there are any results
if ($result->rowCount() > 0) {
    // Echo the HTML table header
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID</th>";
    echo "<th>Type de Réclamation</th>";
    echo "<th>Contenu</th>";
    echo "<th>Date de Saisie</th>";
    echo "<th>Statut</th>";
    echo "<th>Réponse</th>";
    echo "<th>Action</th>";
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";

    // Loop through the results and output data
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id_reclamation'] . "</td>";
        echo "<td>" . $row['type_reclamation'] . "</td>";
        echo "<td>" . $row['content'] . "</td>";
        echo "<td>" . $row['date_saisie'] . "</td>";
        echo "<td>" . $row['status_rec'] . "</td>";
        echo "<td><form method='post' action ='traiterReclamation.php' class='response-form' style='display:none;'><textarea name='response' class='response-textarea' placeholder='Enter your response here'></textarea><input type='hidden' name='id' value='" . $row['id_reclamation'] . "'><button type='submit'>Submit</button></form></td>";
        echo "<td><a href='#' class='action-btn traiter-link'><ion-icon name='checkmark-circle-outline'></ion-icon></a></td>"; // Ionicon added here
        echo "</tr>";
    }

    // Echo the HTML table footer
    echo "</tbody>";
    echo "</table>";

    // Pagination links
    $totalPages = ceil($result->rowCount() / $recordsPerPage);
    echo "<div class='pagination-container'>";
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<a href='?page=$i'>$i</a>";
    }
    echo "</div>";
    echo "</div>";
} else {
    // If no records found, display a message
    echo "<p>No reclamations found</p>";
}

// Close database connection
$pdo = null;
?>
</div>


    <!-- =========== Scripts =========  -->

</script>
    <!-- ====== ionicons ======= -->
    <script type="module"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
   
        <script src="assets/js/script.js"></script>
        <!-- JavaScript code for filtering facture table -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector('.search input');
        
        searchInput.addEventListener('input', function () {
            const searchText = this.value.trim().toLowerCase();
            const rows = document.querySelectorAll('#facture-section tbody tr');
            rows.forEach(row => {
                let found = false;
                row.querySelectorAll('td').forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchText)) {
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
    });
</script>


</body>

</html>  