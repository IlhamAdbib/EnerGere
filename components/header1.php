<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <style>
        body, html {
            margin: 0;
            padding: 0;
        }

        .site-header {
            background-color: #0276FF;
            color: #fff;
            /*padding: 10px 0;*/
            text-align: right;
        }

        .container {
            width: 90%;
            margin: 0 auto;
        }

        .header-links a {
            color: #fff;
            text-decoration: none;
            margin-left: 20px; /* Espacement entre les liens */
        }

        .header-links a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="site-header">
        <div class="container">
            <div class="header-links">
                <a href="index.php">Home</a>
                <a href="logout.php">Log out</a>
            </div>
        </div>
    </div>
</body>
</html>
