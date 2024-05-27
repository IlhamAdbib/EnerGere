<?php

?>



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

// Pagination variables
$records_per_page = 10;
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $records_per_page;

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Check if the "Consommation" value is set and not empty
    if (isset($_POST['consommation']) && !empty($_POST['consommation']) && isset($_POST['id_facture']) && !empty($_POST['id_facture'])) {
        // Retrieve the consommation value from the form
        $consommation = $_POST['consommation'];

        // Retrieve the ID of the facture
        $id_facture = $_POST['id_facture'];

        // Calculate the consumption
        // Note: You may need to adjust the calculation based on your specific requirements
        // Start the session
        session_start();

        // Retrieve the id_user from the session
        $id_user = $_SESSION['id_user'];

        // Retrieve previous month's consumption from the database
        $previous_month = date('Y-m', strtotime('-1 month', strtotime($_POST['mois'])));
        $stmt_previous = $pdo->prepare("SELECT consommation_mensuelle FROM facture WHERE mois < :current_month AND id_user = :id_user ORDER BY mois DESC LIMIT 1");
        $stmt_previous->bindParam(':current_month', $_POST['mois']);
        $stmt_previous->bindParam(':id_user', $id_user);
        $stmt_previous->execute();
        $previous_consumption_row = $stmt_previous->fetch(PDO::FETCH_ASSOC);
        $previous_consumption = $previous_consumption_row ? $previous_consumption_row['consommation_mensuelle'] : 0;

        // Calculate current month's consumption by subtracting previous month's consumption
        $consumption = $consommation - $previous_consumption;

        // Calculate unit price based on consumption
        if ($consumption >= 0 && $consumption <= 100) {
            $unit_price = 0.8; // Price for consumption <= 100 kWh
        } elseif ($consumption >= 101 && $consumption <= 200) {
            $unit_price = 0.9; // Price for consumption between 101 and 200 kWh
        } else {
            $unit_price = 1.0; // Price for consumption > 200 kWh
        }

        // Calculate total price excluding VAT
        $prix_HT = $consumption * $unit_price;

        // Calculate total price including VAT (14%)
        $prix_TTC = $prix_HT * 1.14;

        // Update the "Consommation", "Prix HT", and "Prix TTC" values in the database
        // Update the "Consommation", "Prix HT", "Prix TTC", and "Anomalie" values in the database
        $sql = "UPDATE facture SET consommation_mensuelle = :consommation, prix_HT = :prix_HT, prix_TTC = :prix_TTC, anomalie = 0 WHERE id_facture = :id_facture";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':consommation', $consommation);
        $stmt->bindParam(':prix_HT', $prix_HT);
        $stmt->bindParam(':prix_TTC', $prix_TTC);
        $stmt->bindParam(':id_facture', $id_facture);
        $stmt->execute();
    }
}

// Query to fetch data from the facture table with pagination
$sql = "SELECT * FROM facture LIMIT :offset, :records_per_page";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
$stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
$stmt->execute();

// Count total records for pagination
$total_records = $pdo->query("SELECT COUNT(*) FROM facture")->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);

// Check if there are any results
if ($stmt->rowCount() > 0) {
    // Output table start tag
    echo "<table>";
    echo "<thead><tr><th>ID Facture</th><th>Consommation</th><th>Mois</th><th>Status Facture</th><th>Prix HT</th><th>Prix TTC</th><th>Photo Compteur</th><th>ID User</th><th>Anomalie</th><th>Actions</th></tr></thead>";
    echo "<tbody>";

    // Loop through the results and output data
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . $row['id_facture'] . "</td>";
        echo "<td>";
        echo "<div class='consommation-container'>";
        echo "<span class='consommation-text'>" . $row['consommation_mensuelle'] . "</span>";
        echo "<form class='edit-consommation-form' style='display: none;'>";
        echo "<input type='text' name='consommation' value='" . $row['consommation_mensuelle'] . "' style='width: 50px;'>";
        echo "<input type='hidden' name='id_facture' value='" . $row['id_facture'] . "'>";
        echo "<input type='submit'>";
        echo "</form>";
        echo "</div>";
        echo "</td>";
        echo "<td>" . $row['mois'] . "</td>";
        echo "<td>" . $row['status_facture'] . "</td>";
        echo "<td>" . $row['prix_HT'] . "</td>";
        echo "<td>" . $row['prix_TTC'] . "</td>";
        echo "<td><img src='../uploads/" . $row['photo_compteur'] . "' width='50px' height='50px' alt=''></td>";
        echo "<td>" . $row['id_user'] . "</td>";
        if ($row['anomalie'] == 1) {
            echo "<td style='color: black;  padding: 5px; border-radius: 5px;'>1</td>"; // Display anomaly status with red background
        } else {
            echo "<td style='color: black; padding: 5px; border-radius: 5px;'>0</td>"; // Display no anomaly status with green background
        }
        echo "<td>";
        echo "<a href='#' class='edit-consommation-trigger' data-id='" . $row['id_facture'] . "'><ion-icon name='create'></ion-icon></a>&nbsp;&nbsp;"; // Edit icon with added space
        echo "<a href='generate_invoice.php?id=" . $row['id_facture'] . "' style='margin-left: 10px;'><ion-icon name='document-text-outline'></ion-icon></a>"; // Generate invoice icon with margin-left
        echo "</td>";
        echo "</tr>";
    }

    // Output table end tag
    echo "</tbody></table>";

    // Pagination links
    echo "<div class='pagination-container'>";
    echo "<ul class='pagination'>";
    if ($page > 1) {
        echo "<li><a href='?page=" . ($page - 1) . "'>Previous</a></li>";
    }
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li" . ($i == $page ? " class='active'" : "") . "><a href='?page=" . $i . "'>" . $i . "</a></li>";
    }
    if ($page < $total_pages) {
        echo "<li><a href='?page=" . ($page + 1) . "'>Next</a></li>";
    }
    echo "</ul>";
    echo "</div>";
} else {
    // If no records found, display a message
    echo "<p>No records found</p>";
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
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector('.search input');
        
        searchInput.addEventListener('input', function () {
            const searchText = this.value.trim().toLowerCase();
            const tables = document.querySelectorAll('.main > div table');
            tables.forEach(table => {
                const rows = table.querySelectorAll('tbody tr');
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
    });
 
</script>

</body>

</html>  