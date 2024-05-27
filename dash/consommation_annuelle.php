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
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';
// Define the number of records per page
$records_per_page = 10;

// Get the current page number
$current_page = isset($_GET['page']) ? $_GET['page'] : 1;

// Calculate the starting record for the current page
$start_from = ($current_page - 1) * $records_per_page;

// Open the file for reading
$file = fopen("Consommation_annuelle.txt", "r");

// Check if the file opened successfully
// Check if the file opened successfully
if ($file) {
    // Read each line from the file
    while (($line = fgets($file)) !== false) {
        // Split the line into fields using comma as delimiter
        $fields = explode(',', $line);
        
        // Extract the values from the fields
        $id_client = trim($fields[0]);
        $consommation = trim($fields[1]);
        $annee = trim($fields[2]);
        $date_saisie = trim($fields[3]);
        
        // Check if the data already exists in the database
        $checkSql = "SELECT COUNT(*) FROM consommation_annuelle WHERE id_client = :id_client AND consommation = :consommation AND annee = :annee AND date_saisie = :date_saisie";
        $checkStmt = $pdo->prepare($checkSql);
        $checkStmt->bindParam(':id_client', $id_client);
        $checkStmt->bindParam(':consommation', $consommation);
        $checkStmt->bindParam(':annee', $annee);
        $checkStmt->bindParam(':date_saisie', $date_saisie);
        $checkStmt->execute();
        $count = $checkStmt->fetchColumn();

        // If the data doesn't exist, insert it into the database
        if ($count == 0) {
            $insertSql = "INSERT INTO consommation_annuelle (id_client, consommation, annee, date_saisie) VALUES (:id_client, :consommation, :annee, :date_saisie)";
            $insertStmt = $pdo->prepare($insertSql);
            $insertStmt->bindParam(':id_client', $id_client);
            $insertStmt->bindParam(':consommation', $consommation);
            $insertStmt->bindParam(':annee', $annee);
            $insertStmt->bindParam(':date_saisie', $date_saisie);
            $insertStmt->execute();
        }
    }
    
    // Close the file
    fclose($file);
    
    // Display the consommation table
    echo "<table>";
    echo "<thead>";
    echo "<tr>";
    echo "<th>ID Client</th>";
    echo "<th>Consommation</th>";
    echo "<th>Année</th>";
    echo "<th>Date de saisie</th>";
    echo "<th>Difference</th>"; // New column header
    echo "</tr>";
    echo "</thead>";
    echo "<tbody>";
    
    // Query to fetch data from the consommation_annuelle table with pagination
    $sql = "SELECT id_client, consommation, annee, date_saisie FROM consommation_annuelle LIMIT :start_from, :records_per_page";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':start_from', $start_from, PDO::PARAM_INT);
    $stmt->bindParam(':records_per_page', $records_per_page, PDO::PARAM_INT);
    $stmt->execute();

    // Check if there are any results
    if ($stmt->rowCount() > 0) {
        // Loop through the results and output data
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // Query to calculate the sum of consumption from the "facture" table for the current user
            $sql_total_consumption = "SELECT COALESCE(SUM(consommation_mensuelle), 0) AS total_consommation 
                                      FROM facture 
                                      WHERE id_user = :id_user";
            $stmt_total_consumption = $pdo->prepare($sql_total_consumption);
            $stmt_total_consumption->bindParam(':id_user', $row['id_client']);
            $stmt_total_consumption->execute();
            $total_consumption_result = $stmt_total_consumption->fetch(PDO::FETCH_ASSOC);

            // Calculate the difference
            $difference = $row['consommation'] - $total_consumption_result['total_consommation'];
            if ($difference > 50) {
                // Fetch user's email address from the database
                $emailSql = "SELECT email FROM users WHERE id_user = :id_user";
                $emailStmt = $pdo->prepare($emailSql);
                $emailStmt->bindParam(':id_user', $row['id_client']);
                $emailStmt->execute();
                $userEmail = $emailStmt->fetchColumn();
    
                // Compose email message
                $subject = "High Consumption Notification";
                $message = "Dear User,\n\nYour annual consumption has exceeded the expected limit by more than 50 units. Please consider reducing your energy consumption.\n\nRegards,\nThe EnerGère Team";
    
                // Send email using PHPMailer
                $mail = new PHPMailer;
                $mail->isSMTP();                                      // Set mailer to use SMTP
                $mail->Host = 'smtp.gmail.com';                       // Specify main and backup SMTP servers
                $mail->SMTPAuth = true;                               // Enable SMTP authentication
                $mail->Username = 'ilhamadbib30@gmail.com';             // SMTP username
                $mail->Password = 'dtth vlei qkyx txcb';                    // SMTP password
                $mail->SMTPSecure = 'ssl';                            // Enable TLS encryption, `ssl` also accepted
                $mail->Port = 465;                                    // TCP port to connect to
    
                $mail->setFrom('your_email@gmail.com', 'EnerGère');
                $mail->addAddress($userEmail);                        // Add a recipient
                $mail->isHTML(false);                                 // Set email format to HTML
                $mail->Subject = $subject;
                $mail->Body    = $message;
    
                if(!$mail->send()) {
                    echo 'Message could not be sent.';
                    echo 'Mailer Error: ' . $mail->ErrorInfo;
                } else {
                    //echo 'Message has been sent';
                }
            }
            // Output the data
            echo "<tr>";
            echo "<td>" . $row['id_client'] . "</td>";
            echo "<td>" . $row['consommation'] . "</td>";
            echo "<td>" . $row['annee'] . "</td>";
            echo "<td>" . $row['date_saisie'] . "</td>";
            echo "<td>" . $difference . "</td>"; // Display the difference
            echo "</tr>";
        }
    } else {
        // If no records found, display a message
        echo "<tr><td colspan='5'>No records found</td></tr>";
    }
    
    echo "</tbody>";
    echo "</table>";

    // Pagination links
    $sql_pagination = "SELECT COUNT(*) AS total_records FROM consommation_annuelle";
    $stmt_pagination = $pdo->query($sql_pagination);
    $row_pagination = $stmt_pagination->fetch(PDO::FETCH_ASSOC);
    $total_pages = ceil($row_pagination['total_records'] / $records_per_page);

    echo "<div class='pagination-container'>";
    echo "<ul class='pagination'>";
    for ($i = 1; $i <= $total_pages; $i++) {
        echo "<li><a href='?page=$i'>$i</a></li>";
    }
    echo "</ul>";
    echo "</div>";

} else {
    // Error opening the file
    echo "Error opening the file.";
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
   
     <!--   <script src="assets/js/script.js"></script>-->
        <!-- JavaScript code for filtering facture table -->


</body>

</html>  