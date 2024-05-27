<?php
session_start();
// Include the TCPDF library
require_once('../tcpdf/tcpdf.php');
function generateInvoicePDF($clientDetails, $consumption, $month, $prix_HT, $prix_TTC, $photo_compteur) {
    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Your Company');
    $pdf->SetTitle('Invoice');
    $pdf->SetSubject('Invoice');
    $pdf->SetKeywords('Invoice, TCPDF, PHP');

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 12);

    // Add content to the PDF
    $html = '
        <table border="0" cellpadding="10" style="margin:auto;">
        <tr>
                <td colspan="2" style="height: 20px;"></td> <!-- Spacer between content sections -->
            </tr>
            <tr>
                <td colspan="2" style="vertical-align: top;"><img src="' . $photo_compteur . '" alt="Photo du Compteur" style="width: 100px; height: 100px;"></td>
            </tr>
            <tr>
                <td colspan="2" style="height: 20px;"></td> <!-- Spacer between image and content -->
            </tr>
            <tr>
                <td colspan="2">
                    <h1 style="text-align: center;">Invoice</h1>
                </td>
            </tr>
            <tr>
                <td><strong>Client:</strong></td>
                <td style="text-align: right;">' . $clientDetails['nom'] . ' ' . $clientDetails['prenom'] . '</td>
            </tr>
            <tr>
                <td><strong>Address:</strong></td>
                <td style="text-align: right;">' . $clientDetails['adresse'] . '</td>
            </tr>
            <tr>
                <td><strong>Consumption:</strong></td>
                <td style="text-align: right;">' . $consumption . ' kWh</td>
            </tr>
            <tr>
                <td><strong>Month:</strong></td>
                <td style="text-align: right;">' . $month . '</td>
            </tr>
            <tr>
                <td><strong>Price HT:</strong></td>
                <td style="text-align: right;">' . $prix_HT . ' €</td>
            </tr>
            <tr>
                <td><strong>Price TTC:</strong></td>
                <td style="text-align: right;">' . $prix_TTC . ' €</td>
            </tr>
            
            <tr>
                <td colspan="2" style="height: 20px;"></td> <!-- Spacer between content sections -->
            </tr>
            <tr>
                <td colspan="2" style="height: 20px;"></td> <!-- Spacer between content sections -->
            </tr>
            
            <tr>
                <td colspan="2">
                    <p style="text-align: center; font-style: italic;">Please make the payment within 10 days.</p>
                </td>
            </tr>
        </table>
    ';

    // Write HTML content to the PDF
    $pdf->writeHTML($html, true, false, true, false, '');

    // Close and output PDF document
    $pdf->Output('invoice.pdf', 'I');
}
// Include the database connection file
include("../connection.php");

// Start the session
//session_start();

$anomaly_message = "";
$anomaly_detected = false; // Initialize anomaly detection variable

try {
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the id_user from the session
        $id_user = $_SESSION['id_user'];

        $mois = $_POST['mois'];

        // Check if the same month's data already exists for the user
        $stmt_check = $pdo->prepare("SELECT COUNT(*) AS count FROM facture WHERE mois = :mois AND id_user = :id_user");
        $stmt_check->bindParam(':mois', $mois);
        $stmt_check->bindParam(':id_user', $id_user);
        $stmt_check->execute();
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

        // If the count is greater than 0, it means data for the same month already exists
        if ($result['count'] > 0) {
            echo "Data for the same month already exists.";
            exit(); // You can redirect or handle the situation as per your requirement
        }

        // Calculate consumption for month 1
       
            // Retrieve consumption for month 12 from the previous year
            if ($mois == '01') {
                // Retrieve consumption for month 12 from the previous year
                $stmt_previous_year = $pdo->prepare("SELECT consommation_mensuelle FROM facture WHERE mois = '12' AND id_user = :id_user ORDER BY mois DESC LIMIT 1");
                $stmt_previous_year->bindParam(':id_user', $id_user);
                $stmt_previous_year->execute();
                $previous_consumption_row = $stmt_previous_year->fetch(PDO::FETCH_ASSOC);
                $previous_consumption = $previous_consumption_row ? $previous_consumption_row['consommation_mensuelle'] : 0;
    
                // Retrieve consumption from the form
                $current_consumption = $_POST['consommation'];
    
                // Calculate consumption for month 1
                $consumption = $current_consumption - $previous_consumption;
        } else {
            // Retrieve consumption from the form
            $current_consumption = $_POST['consommation'];

            // Retrieve previous month's consumption from the database
            $previous_month = date('Y-m', strtotime('-1 month', strtotime($mois)));
            $stmt_previous = $pdo->prepare("SELECT consommation_mensuelle FROM facture WHERE mois < :current_month AND id_user = :id_user ORDER BY mois DESC LIMIT 1");
            $stmt_previous->bindParam(':current_month', $mois);
            $stmt_previous->bindParam(':id_user', $id_user);
            $stmt_previous->execute();
            $previous_consumption_row = $stmt_previous->fetch(PDO::FETCH_ASSOC);
            $previous_consumption = $previous_consumption_row ? $previous_consumption_row['consommation_mensuelle'] : 0;

            // Calculate current month's consumption by subtracting previous month's consumption
            $consumption = $current_consumption - $previous_consumption;
        }

        // Check for anomalies in consumption
        if ($consumption < 0 || $consumption > 20000) {
            $anomaly_detected = true;
            $anomaly_message = "Anomaly detected: Consumption is out of expected range.";
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

            // Prepare SQL statement to insert data into the facture table
            $stmt = $pdo->prepare("INSERT INTO facture (consommation_mensuelle, mois, photo_compteur, status_facture, prix_HT, prix_TTC, id_user) VALUES (:consommation, :mois, :photo, :status, :prix_HT, :prix_TTC, :id_user)");

            // Bind parameters
            $stmt->bindParam(':consommation', $consumption);
            $stmt->bindParam(':mois', $mois);
            $stmt->bindParam(':prix_HT', $prix_HT);
            $stmt->bindParam(':prix_TTC', $prix_TTC);

            // Upload file
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

            // Bind uploaded file path to the statement
            $stmt->bindParam(':photo', $target_file);

            // Default status_facture to 'non_paye'
            $status = 'non_paye';
            $stmt->bindParam(':status', $status);

            // Bind id_user
            $stmt->bindParam(':id_user', $id_user);

            // Execute the prepared statement
            $stmt->execute();

            // Generate the invoice PDF
            // Fetch client details from the database
            $stmt_client = $pdo->prepare("SELECT nom, prenom, adresse FROM users WHERE id_user = :id_user");
            $stmt_client->bindParam(':id_user', $id_user);
            $stmt_client->execute();
            $clientDetails = $stmt_client->fetch(PDO::FETCH_ASSOC);


        } else {
            $anomaly_message = "No anomaly detected.";

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

            // Prepare SQL statement to insert data into the facture table
            $stmt = $pdo->prepare("INSERT INTO facture (consommation_mensuelle, mois, photo_compteur, status_facture, prix_HT, prix_TTC, id_user) VALUES (:consommation, :mois, :photo, :status, :prix_HT, :prix_TTC, :id_user)");

            // Bind parameters
            $stmt->bindParam(':consommation', $consumption);
            $stmt->bindParam(':mois', $mois);
            $stmt->bindParam(':prix_HT', $prix_HT);
            $stmt->bindParam(':prix_TTC', $prix_TTC);

            // Upload file
            $target_dir = "../uploads/";
            $target_file = $target_dir . basename($_FILES["photo"]["name"]);
            move_uploaded_file($_FILES["photo"]["tmp_name"], $target_file);

            // Bind uploaded file path to the statement
            $stmt->bindParam(':photo', $target_file);

            // Default status_facture to 'non_paye'
            $status = 'non_paye';
            $stmt->bindParam(':status', $status);

            // Bind id_user
            $stmt->bindParam(':id_user', $id_user);

            // Execute the prepared statement
            $stmt->execute();

            // Generate the invoice PDF
            // Fetch client details from the database
            $stmt_client = $pdo->prepare("SELECT nom, prenom, adresse FROM users WHERE id_user = :id_user");
            $stmt_client->bindParam(':id_user', $id_user);
            $stmt_client->execute();
            $clientDetails = $stmt_client->fetch(PDO::FETCH_ASSOC);

            // Generate the invoice PDF
            generateInvoicePDF($clientDetails, $consumption, $mois, $prix_HT, $prix_TTC, $target_file);

            // Redirect to success page or do something else
            //header("Location: success.php");
            exit();
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>
         .form-container {
        width: 400px;
        margin: 60px auto;
        padding: 20px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .form-group {
        margin-bottom: 20px;
    }

    label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    input[type="text"],
    input[type="number"],
    input[type="file"] {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        box-sizing: border-box;
        font-size: 16px;
    }

    button {
        padding: 10px 20px;
        background-color: #007bff;
        color: #fff;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s ease;
    }

    button:hover {
        background-color: #0056b3;
    }

    .right {
        margin-left: 10px;
    }

    .anomaly-message {
        background-color: #ffc107;
        color: #333;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 20px;
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
                <a href="saisirconsommation.php" >
                    <span class="icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </span>
                    <span class="title">Saisir Consommation</span>
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
                    <a href="faireReclamation.php">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span class="title">Faire Réclamation</span>
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
    <!--
    <div class="search">
        <label>
            <input type="text" placeholder="Search here">
            <ion-icon name="search-outline"></ion-icon>
        </label>
    </div>-->

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


            
            
<script>
    // JavaScript to toggle visibility of form when "Traiter" link is clicked
    document.querySelectorAll('.traiter-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const row = this.closest('tr');
            const form = row.querySelector('.response-form');
            form.style.display = form.style.display === 'none' ? 'table-cell' : 'none';
        });
    });
</script>

<div class="form-container">
        <h2>Saisir Consommation</h2>
        
        <?php if ($anomaly_detected): ?>
            <div class="anomaly-message"><?php echo $anomaly_message; ?></div>
        <?php endif; ?>
        <div id="messageBox"></div> 
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="consommation">Consommation en kWh:</label>
                <input type="number" id="consommation" name="consommation" required min="50" pattern="[5-9]\d*">

            </div>
            <div class="form-group">
                <label for="mois">Mois:</label>
                <input type="number" id="mois" name="mois" required min="1" max="12" pattern="[1-9]|1[0-2]">
            </div>
            <div class="form-group">
                <label for="photo">Photo du Compteur:</label>
                <input type="file" id="photo" name="photo" accept=".jpg,.jpeg,.png" required>
            </div>
            <div class="form-group">
                <button type="button" onclick="precedent()">Précédent</button>
                <button class="right" type="submit">Enregistrer</button>
                <button class="right" type="button" onclick="effacer()">Effacer</button>
            </div>
        </form>
    </div>

    <script>
    function effacer() {
        // Clear the form fields
        document.getElementById("consommation").value = "";
        document.getElementById("mois").value = "";
        document.getElementById("photo").value = "";
    }

    function precedent() {
        // Redirect to the previous page
        window.history.back();
    }
    </script>

        </div>
    </div>
    
    <!-- =========== Scripts =========  -->
 <script src="assets/js/main.js">
</script>
    <!-- ====== ionicons ======= -->
    <script type="module"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
   
     <!-- <script src="assets/js/script.js"></script>-->
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