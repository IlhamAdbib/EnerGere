<?php
include '../connection.php'; 

if (isset($_GET['id'])) {
    $clientId = $_GET['id'];

    // Retrieve client data based on the client ID
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id_user = ?");
    $stmt->execute([$clientId]);
    $clientData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if client data is found
    if ($clientData) {
        // Populate the form fields with the retrieved client data
        // This part depends on your HTML structure and how you want to populate the form fields
        // For example:
        $nom = $clientData['nom'];
        $prenom = $clientData['prenom'];
        $cin = $clientData['cin'];
        $email = $clientData['email'];
        $password = $clientData['password'];
        $adresse = $clientData['adresse'];
    } else {
        echo "Client not found.";
    }
} else {
    echo "Client ID not provided.";
}

$pdo = null;
?>
<?php
include '../connection.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $clientId = $_POST['clientId'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $cin = $_POST['cin'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $adresse = $_POST['adresse'];

    try {
        // Prepare SQL statement to update client details
        $sql = "UPDATE users SET nom = :nom, prenom = :prenom, cin = :cin, email = :email, password = :password, adresse = :adresse WHERE id_user = :clientId";

        // Prepare the SQL statement
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':cin', $cin);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':adresse', $adresse);
        $stmt->bindParam(':clientId', $clientId);

        // Execute the SQL statement
        if ($stmt->execute()) {
            echo "Client details updated successfully.";
        } else {
            echo "Error updating client details.";
        }
    } catch (PDOException $e) {
        echo "Database error: " . $e->getMessage();
    }
}

$pdo = null;
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client</title>
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
    <h2>Modifier Client</h2>
    <form action="#" method="post">
        <input type="hidden" id="clientId" name="clientId" value="<?php echo $clientId; ?>">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" required value="<?php echo $nom; ?>">
        </div>
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" required value="<?php echo $prenom; ?>">
        </div>
        <div class="form-group">
            <label for="cin">CIN:</label>
            <input type="text" id="cin" name="cin" required value="<?php echo $cin; ?>">
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo $email; ?>">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" required value="<?php echo $password; ?>">
        </div>
        <div class="form-group">
            <label for="adresse">Adresse:</label>
            <input type="text" id="adresse" name="adresse" required value="<?php echo $adresse; ?>">
        </div>
        <div class="form-group">
            <button type="submit">Enregistrer</button>
        </div>
    </form>
</div>

</body>
</html>
