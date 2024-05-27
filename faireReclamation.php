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
