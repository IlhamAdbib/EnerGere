<?php
// Include the TCPDF library
require_once('tcpdf/tcpdf.php');
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
include("connection.php");

// Start the session
session_start();

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
$target_dir = "uploads/";
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
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saisir Consommation</title>
    <style>
        /* Add your CSS styles here */
        /* Just basic styling for demonstration purposes */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .form-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input[type="text"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group input[type="file"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .form-group button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #0276FF;
            color: #fff;
            cursor: pointer;
        }
        .form-group button:not(:first-child) {
            margin-left: 10px; /* Add space between buttons */
        }
        .form-group button:hover {
            background-color: #1C84FF;
        }
        .form-group .right {
            margin-right: 0px; /* Add some spacing between the buttons */
        }
        .form-group input[type="number"] {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .anomaly-message {
            color: red;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Saisir Consommation</h2>
        <!-- Display anomaly message -->
        <?php if ($anomaly_detected): ?>
            <div class="anomaly-message"><?php echo $anomaly_message; ?></div>
        <?php endif; ?>
        <div id="messageBox"></div> <!-- Add this line -->
        <form action="#" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="consommation">Consommation en kWh:</label>
                <input type="number" id="consommation" name="consommation" required>
            </div>
            <div class="form-group">
                <label for="mois">Mois:</label>
                <input type="text" id="mois" name="mois" required>
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
</body>
</html>
