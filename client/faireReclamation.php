<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Admin Dashboard</title>
    <!-- ======= Styles ====== -->
    <link rel="stylesheet" href="assets/css/table.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <style>
      

        .reclamation-form {
            background-color: #fff;
            border-radius: 10px;
            padding: 60px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            position: relative;
            margin: 60px auto;

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
            margin-top:-5px;
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
                <a href="saisirconsommation.php" >
                    <span class="icon">
                        <ion-icon name="home-outline"></ion-icon>
                    </span>
                    <span class="title">Saisir Consommation</span>
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
                    <a href="faireReclamation.php">
                        <span class="icon">
                            <ion-icon name="document-text-outline"></ion-icon>
                        </span>
                        <span class="title">Faire Réclamation</span>
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
    <!--
    <div class="search">
        <label>
            <input type="text" placeholder="Search here">
            <ion-icon name="search-outline"></ion-icon>
        </label>
    </div>-->

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


            
            
<script>
    // JavaScript to toggle visibility of form when "Traiter" link is clicked
    document.querySelectorAll('.traiter-link').forEach(link => {
        link.addEventListener('click', function(event) {
            event.preventDefault();
            const row = this.closest('tr');
            const form = row.querySelector('.response-form');
            form.style.display = form.style.display === 'none' ? 'table-cell' : 'none';
        });
    });
</script>
<div class="reclamation-form">
        <h2>Faire Réclamation</h2>
        <form action="traite.php" method="POST">
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
        </div>
    </div>

    <!-- =========== Scripts =========  -->
 <script src="assets/js/main.js">
</script>
    <!-- ====== ionicons ======= -->
    <script type="module"
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule
        src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
   
     <!-- <script src="assets/js/script.js"></script>-->
        <!-- JavaScript code for filtering facture table -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector('.search input');
        
        searchInput.addEventListener('input', function () {
            const searchText = this.value.trim().toLowerCase();
            const rows = document.querySelectorAll('#facture-section tbody tr');
            rows.forEach(row => {
                let found = false;
                row.querySelectorAll('td').forEach(cell => {
                    if (cell.textContent.toLowerCase().includes(searchText)) {
                        found = true;
                    }
                });
                if (found) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    });
</script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const searchInput = document.querySelector('.search input');
        
        searchInput.addEventListener('input', function () {
            const searchText = this.value.trim().toLowerCase();
            const tables = document.querySelectorAll('.main > div table');
            tables.forEach(table => {
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    let found = false;
                    row.querySelectorAll('td').forEach(cell => {
                        if (cell.textContent.toLowerCase().includes(searchText)) {
                            found = true;
                        }
                    });
                    if (found) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });
    });
</script>

</body>

</html>  