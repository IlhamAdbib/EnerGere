<?php
// Include database connection
include '../connection.php';

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Retrieve form data
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $cin = $_POST['cin'];
        $email = $_POST['email'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
        $adresse = $_POST['adresse'];
        $telephone = $_POST['telephone'];
        $role = 'client'; // Assuming the role is set to 'client'

        // Prepare SQL statement to insert data into users table
        $sql = "INSERT INTO users (nom, prenom, cin, adresse, email, telephone, password, role) 
                VALUES (:nom, :prenom, :cin, :adresse, :email, :telephone, :password, :role)";

        // Prepare and execute the SQL statement
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':cin', $cin);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':telephone', $telephone);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $role);
        
        if ($stmt->execute()) {
            echo "Client ajouté avec succès.";
        } else {
            echo "Erreur lors de l'ajout du client.";
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

// Close database connection
$pdo = null;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Client</title>
    <style>
        /* Add your CSS styles here */
        body {
    font-family: Arial, sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 0;
}

.form-container {
    max-width: 500px;
    margin: 50px auto;
    padding: 30px;
    background-color: #b5cbbd; /* Modern color */
    border-radius: 12px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.form-container h2 {
    margin-bottom: 20px;
    text-align: center;
    color: #333;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    font-weight: bold;
    margin-bottom: 5px;
    color: #666;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"] {
    width: calc(90% - 20px); /* Adjusted width to accommodate the button */
    padding: 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    box-sizing: border-box;
    font-size: 16px;
    transition: border-color 0.3s;
}

.form-group input[type="text"]:focus,
.form-group input[type="email"]:focus,
.form-group input[type="password"]:focus {
    border-color: #4CAF50;
    outline: none;
}

.form-group button {
    padding: 6px 5px;
    border: none;
    border-radius: 6px;
    background-color: #0276FF; /* Blue color */
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: background-color 0.3s;
    float: right;
}

.form-group button:hover {
    background-color: #1C84FF; /* Hover color */
}
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Ajouter Client</h2>
        <form action="" method="post">
            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" required>
            </div>
            <div class="form-group">
                <label for="cin">CIN:</label>
                <input type="text" id="cin" name="cin" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse:</label>
                <input type="text" id="adresse" name="adresse" required>
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone:</label> <!-- Corrected name -->
                <input type="text" id="telephone" name="telephone" required> <!-- Corrected name -->
            </div>
            <div class="form-group">
                <button type="submit">Enregistrer</button>
            </div>
        </form>
    </div>
</body>
</html>
