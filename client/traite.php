<?php
session_start();

// Check if the user is logged in and retrieve their ID
if (!isset($_SESSION["id_user"])) {
    // Redirect the user to the login page if not logged in
    header("Location: ../login.php");
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
        require_once "../connection.php";

        try {
            // Prepare SQL statement
            $stmt = $pdo->prepare("INSERT INTO reclamation (type_reclamation, content, users_ID) VALUES (:type, :description, :user_id)");

            // Bind parameters
            $stmt->bindParam(":type", $type);
            $stmt->bindParam(":description", $description);
            $stmt->bindParam(":user_id", $user_id);

            // Execute the statement
            $stmt->execute();
            echo "Votre Réclamation a été enregistrée avec succès";
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