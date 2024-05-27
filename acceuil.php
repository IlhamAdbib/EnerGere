<?php
    session_start();
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Client</title>
    <link rel="stylesheet" href="css/button.css">
</head>
<body>
    <div class="button-container">
        <form action="saisirconsommation.php" method="get">
            <button class="button-22" type="submit" name="saisir_consommation">Saisir consommation</button><br>
        </form>
        
        <button class="button-22" role="button">Consulter Facture</button><br>
        <form action="faireReclamation.php" method="post">
        <button class="button-22" role="button">Faire Réclamation</button>
        </form>
    </div>
</body>
</html>
<?php include 'components/footer.php'; ?>

