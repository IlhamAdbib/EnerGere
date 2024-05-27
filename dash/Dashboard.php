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
              <!-- ======================= Cards ================== -->
                        <div id="dashboard-section">
                <!-- ======================= Cards ================== -->
                <?php include 'fetch_stat.php'; ?>
            </div>         
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


</body>

</html>  