<?php
// Database connection parameters
include '../connection.php';

try {
    // Connect to the database
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch data
    $sql_client_count = "SELECT COUNT(*) AS client_count FROM users WHERE role = 'client'";
    $sql_processed_reclamations = "SELECT COUNT(*) AS processed_reclamations FROM reclamation WHERE status_rec = 'traité'";
    $sql_unprocessed_reclamations = "SELECT COUNT(*) AS unprocessed_reclamations FROM reclamation WHERE status_rec = 'non traité'";
    $sql_anomaly_count = "SELECT COUNT(*) AS anomaly_count FROM facture WHERE anomalie = 1";
    $sql_paid_invoices = "SELECT COUNT(*) AS paid_invoices FROM facture WHERE status_facture = 'paye'";
    $sql_unpaid_invoices = "SELECT COUNT(*) AS unpaid_invoices FROM facture WHERE status_facture = 'non_paye'";

    // Prepare and execute queries
    $stmt_client_count = $pdo->prepare($sql_client_count);
    $stmt_processed_reclamations = $pdo->prepare($sql_processed_reclamations);
    $stmt_unprocessed_reclamations = $pdo->prepare($sql_unprocessed_reclamations);
    $stmt_anomaly_count = $pdo->prepare($sql_anomaly_count);
    $stmt_paid_invoices = $pdo->prepare($sql_paid_invoices);
    $stmt_unpaid_invoices = $pdo->prepare($sql_unpaid_invoices);

    $stmt_client_count->execute();
    $stmt_processed_reclamations->execute();
    $stmt_unprocessed_reclamations->execute();
    $stmt_anomaly_count->execute();
    $stmt_paid_invoices->execute();
    $stmt_unpaid_invoices->execute();

    // Fetch data
    $client_count = $stmt_client_count->fetch(PDO::FETCH_ASSOC)['client_count'];
    $processed_reclamations = $stmt_processed_reclamations->fetch(PDO::FETCH_ASSOC)['processed_reclamations'];
    $unprocessed_reclamations = $stmt_unprocessed_reclamations->fetch(PDO::FETCH_ASSOC)['unprocessed_reclamations'];
    $anomaly_count = $stmt_anomaly_count->fetch(PDO::FETCH_ASSOC)['anomaly_count'];
    $paid_invoices = $stmt_paid_invoices->fetch(PDO::FETCH_ASSOC)['paid_invoices'];
    $unpaid_invoices = $stmt_unpaid_invoices->fetch(PDO::FETCH_ASSOC)['unpaid_invoices'];

    // Close connection
    $pdo = null;
} catch (PDOException $e) {
    // Handle database connection error
    echo "Connection failed: " . $e->getMessage();
}

// Function to map statistic names to icon names
function iconName($statisticName) {
    switch ($statisticName) {
        case "Factures payées":
            return "checkmark-outline"; // Example icon name for paid invoices
        case "Réclamations traitées":
            return "checkmark-done-outline"; // Example icon name for processed reclamations
        case "Anomalie":
            return "warning-outline"; // Example icon name for anomalies
        case "Factures non payées":
            return "alert-circle-outline"; // Example icon name for unpaid invoices
        case "Réclamations non traitées":
            return "alert-outline"; // Example icon name for unprocessed reclamations
        default:
            return ""; // Default icon name
    }
}
?>

<!-- HTML part with PHP variables embedded -->
<div class="cardBox">
    <div class="card" id="clientCard">
        <div>
            <div class="numbers" id="clientCount"><?php echo $client_count; ?></div>
            <div class="cardName">Clients</div>
        </div>
        <div class="iconBx">
            <ion-icon name="people-outline"></ion-icon>
        </div>
    </div>
    <?php
    // PHP loop to generate cards dynamically
    $statistics = array(
        "Factures payées" => $paid_invoices,
        "Réclamations traitées" => $processed_reclamations,
        "Anomalie" => $anomaly_count,
        "Factures non payées" => $unpaid_invoices,
        "Réclamations non traitées" => $unprocessed_reclamations
    );

    foreach ($statistics as $name => $value) {
        echo '<div class="card">';
        echo '<div>';
        echo '<div class="numbers">+</div>'; // Displaying dynamic numbering
        echo '<div class="cardName">' . $name . ': ' . $value . '</div>';
        echo '</div>';
        echo '<div class="iconBx">';
        echo '<ion-icon name="' . iconName($name) . '"></ion-icon>'; // Assuming there's a function to map names to icon names
        echo '</div>';
        echo '</div>';
    }
    ?>
</div>
<script>
    // JavaScript to dynamically display numbers after the page is loaded
    window.addEventListener('DOMContentLoaded', (event) => {
        var numbers = document.querySelectorAll('.numbers');
        var index = 0;
        
        // Function to display numbers incrementally for a statistic
        function displayNumbers(number, count) {
            var currentNumber = 1;
            var interval = setInterval(function() {
                number.textContent = currentNumber;
                if (currentNumber >= count) clearInterval(interval);
                else currentNumber++;
            }, 100);
        }
        
        // Incrementally display numbers for each statistic
        var interval = setInterval(function() {
            if (index < numbers.length) {
                var count = parseInt(numbers[index].textContent);
                displayNumbers(numbers[index], count);
                index++;
            } else {
                clearInterval(interval);
            }
        }, 1000); // Adjust the interval value as needed
    });
</script>