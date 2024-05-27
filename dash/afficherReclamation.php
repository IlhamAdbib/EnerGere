<?php
// Include database connection
include '../connection.php';

// Query to fetch reclamations with status "non traité"
$sql = "SELECT * FROM reclamation WHERE status_rec = 'non traité'";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$reclamations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Réclamation - Reclamations Non Traitées</title>
    <style>
        /* Add your CSS styles here */
        /* Just basic styling for demonstration purposes */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Reclamations Non Traitées</h2>
    <table>
        <thead>
            <tr>
                <th>ID Réclamation</th>
                <th>Type Réclamation</th>
                <th>Contenu</th>
                <th>Date de Saisie</th>
                <!-- Add more table headers as needed -->
            </tr>
        </thead>
        <tbody>
            <?php foreach ($reclamations as $reclamation): ?>
                <tr>
                    <td><?php echo $reclamation['id_reclamation']; ?></td>
                    <td><?php echo $reclamation['type_reclamation']; ?></td>
                    <td><?php echo $reclamation['content']; ?></td>
                    <td><?php echo $reclamation['date_saisie']; ?></td>
                    <!-- Add more table cells to display additional information -->
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
