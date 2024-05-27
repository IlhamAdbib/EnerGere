<?php
require_once('../tcpdf/tcpdf.php');
require_once('../connection.php'); // Include database connection

// Function to generate PDF invoice
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
            <td colspan="2" style="vertical-align: top;"><img src="../' . $photo_compteur . '" alt="Photo du Compteur" style="width: 100px; height: 100px;"></td>
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

// Retrieve invoice details from the database
if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_facture = $_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM facture WHERE id_facture = :id_facture");
    $stmt->bindParam(':id_facture', $id_facture);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Check if data is retrieved
    if ($row) {
        // Retrieve client details from users table
        $stmt_client = $pdo->prepare("SELECT * FROM users WHERE id_user = :id_user");
        $stmt_client->bindParam(':id_user', $row['id_user']);
        $stmt_client->execute();
        $clientDetails = $stmt_client->fetch(PDO::FETCH_ASSOC);
        
        // Generate PDF invoice
        generateInvoicePDF($clientDetails, $row['consommation_mensuelle'], $row['mois'], $row['prix_HT'], $row['prix_TTC'], $row['photo_compteur']);
    } else {
        // Invoice not found
        echo 'Invoice not found.';
    }
} else {
    // Invoice ID not provided
    echo 'Invoice ID not provided.';
}

// Close database connection
$pdo = null;
?>
