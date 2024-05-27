<?php
/*session_start();

// Check if the user is logged in and retrieve their ID
if (!isset($_SESSION["id_user"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from session
    $user_id = $_SESSION["id_user"];

    // Check if form data is set
    if (isset($_POST["type"]) && isset($_POST["description"])) {
        // Retrieve form data
        $type = $_POST["type"];
        $description = $_POST["description"];

        // Include the database connection file
        require_once "connection.php";

        try {
            // Prepare SQL statement
            $stmt = $pdo->prepare("INSERT INTO reclamation (type_reclamation, content, users_ID) VALUES (:type, :description, :user_id)");

            // Bind parameters
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":user_id", $user_id);

            // Execute the statement
            $stmt->execute();

            // Redirect to a success page or do something else
            header("Location: faireReclamation.php");
            exit();
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Handle case where form data is not set
       // echo "Form data not set";
    }
} else {
    // If the form is not submitted, redirect back to the form page
   // header("Location: reclamation_form.php");
    exit();
}*/
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faire Réclamation</title>
    <!-- ======= Styles ====== -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f4;
            font-family: Arial, sans-serif;
        }

        .reclamation-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            position: relative;
        }

        .reclamation-form h2 {
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .form-group textarea {
            height: 100px;
        }

        button[type="submit"],
        button[type="button"] {
            width: calc(30% - 5px);
            padding: 10px;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        button[type="submit"]:hover,
        button[type="button"]:hover {
            background-color: #0056b3;
        }

        button[type="button"] {
            background-color: #007bff;
            margin-left: 0;
            float: left;
        }

        button[type="button"]:hover {
            background-color: #0056b3;
        }

        button[type="submit"] {
            float: right;
        }
    </style>
</head>

<body>
    <!-- Reclamation Form -->
    <div class="reclamation-form">
        <h2>Faire Réclamation</h2>
        <form action="faireReclamation.php" method="POST">
            <div class="form-group">
                <label for="type">Type de Réclamation:</label>
                <select id="type" name="type">
                    <option value="fuite_interne">Fuite interne</option>
                    <option value="fuite_externe">Fuite externe</option>
                    <option value="facture">Facture</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea id="description" name="description" rows="4" cols="50"></textarea>
            </div>
            <button type="button" onclick="window.history.back()">Retour</button>
            <button type="submit">Envoyer</button>
        </form>
    </div>
</body>

</html>



<?php
/*session_start();
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


$anomaly_message = "";
$anomaly_detected = false; // Initialize anomaly detection variable

try {
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Retrieve the id_user from the session
        $id_user = $_SESSION['id_user'];

        // Retrieve consumption from the form
        $current_consumption = $_POST['consommation'];

        // Retrieve previous month's consumption from the database
        $previous_month = date('Y-m', strtotime('-1 month', strtotime($_POST['mois'])));
        $stmt_previous = $pdo->prepare("SELECT consommation_mensuelle FROM facture WHERE mois < :current_month AND id_user = :id_user ORDER BY mois DESC LIMIT 1");
        $stmt_previous->bindParam(':current_month', $_POST['mois']);
        $stmt_previous->bindParam(':id_user', $id_user);
        $stmt_previous->execute();
        $previous_consumption_row = $stmt_previous->fetch(PDO::FETCH_ASSOC);
        $previous_consumption = $previous_consumption_row ? $previous_consumption_row['consommation_mensuelle'] : 0;

        // Calculate current month's consumption by subtracting previous month's consumption
        $consumption = $current_consumption - $previous_consumption;

        // Check for anomalies in consumption
        if ($consumption < 0 || $consumption > 20000) {
            $anomaly_detected = true;
            $anomaly_message = "Il y-a une anomalie dans votre consommation et la facture est en cours de traitement";
        } else {
            $anomaly_message = "No anomaly detected.";
        }

        // Store data in the database even if an anomaly occurs
        try {
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
$stmt = $pdo->prepare("INSERT INTO facture (consommation_mensuelle, mois, photo_compteur, status_facture, prix_HT, prix_TTC, id_user, anomalie) VALUES (:consommation, :mois, :photo, :status, :prix_HT, :prix_TTC, :id_user, :anomalie)");

// Bind parameters
$stmt->bindParam(':consommation', $consumption);
$stmt->bindParam(':mois', $_POST['mois']);
$stmt->bindParam(':prix_HT', $prix_HT);
$stmt->bindParam(':prix_TTC', $prix_TTC);
$stmt->bindParam(':anomalie', $anomaly_detected, PDO::PARAM_BOOL); // Bind the anomaly detection result

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

        } catch(PDOException $e) {
            echo "Error storing data in the database: " . $e->getMessage();
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}*/
?>

<?php
session_start();

// Check if the user is logged in and retrieve their ID
if (!isset($_SESSION["id_user"])) {
    // Redirect the user to the login page if not logged in
    header("Location: login.php");
    exit();
}

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user ID from session
    $user_id = $_SESSION["id_user"];

    // Check if form data is set
    if (isset($_POST["type"]) && isset($_POST["description"])) {
        // Retrieve form data
        $type = $_POST["type"];
        $description = $_POST["description"];

        // Include the database connection file
        require_once "connection.php";

        try {
            // Prepare SQL statement
            $stmt = $pdo->prepare("INSERT INTO reclamation (type_reclamation, content, users_ID) VALUES (:type, :description, :user_id)");

            // Bind parameters
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":user_id", $user_id);

            // Execute the statement
            $stmt->execute();

            // Redirect to a success page or do something else
            header("Location: faireReclamation.php");
            exit();
        } catch (PDOException $e) {
            // Handle database errors
            echo "Error: " . $e->getMessage();
        }
    } else {
        // Handle case where form data is not set
       // echo "Form data not set";
    }
} else {
    // If the form is not submitted, redirect back to the form page
   // header("Location: reclamation_form.php");
    exit();
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
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
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
            <a href="Dashboard.php">
                <span class="icon">
                    <ion-icon name="home-outline"></ion-icon>
                </span>
                <span class="title">Dashboard</span>
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
                    <a href="#">
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