<?php
session_start();
include("connection.php");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['login_btn'])) {
      $_SESSION['id_user'] = $user_ID;
        login();
    }
}

// LOGIN USER
function login(){
  global $pdo;

  // Grap form values
  $email = $_POST['email'];
  $password = $_POST['password'];

  // Make sure form is filled properly
  if (empty($email)) {
      $_SESSION['error'] = "Email is required";
      header("Location: login.php");
      exit();
  }
  if (empty($password)) {
      $_SESSION['error'] = "Password is required";
      header("Location: login.php");
      exit();
  }

  // Attempt login if no errors on form
  try {
      $stmt = $pdo->prepare("SELECT * FROM users WHERE email=? LIMIT 1");
      $stmt->execute([$email]);
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if ($user) {
          // Check password
          if ($password === $user['password']) {
              // Set the id_user in the session
              $_SESSION['id_user'] = $user['id_user'];
              $_SESSION['user'] = $user;
              $_SESSION['success'] = "You are now logged in";
              
              // Redirect based on user role
              if ($user['role'] == 'fournisseur') {
                  header('Location: dash/Dashboard.php');
                  exit();
              } else {
                  header('Location: client/saisirconsommation.php');
                  exit();
              }
          } else {
              $_SESSION['error'] = "Wrong email/password combination";
              header("Location: login.php");
              exit();
          }
      } else {
          $_SESSION['error'] = "User not found";
          header("Location: login.php");
          exit();
      }
  } catch (PDOException $e) {
      $_SESSION['error'] = "Database error: " . $e->getMessage();
      header("Location: login.php");
      exit();
  }
}
?>
</head>
<!DOCTYPE html>
<html lang="en" >
<head>
  <meta charset="UTF-8">
  <title> Login</title>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- partial:index.partial.html -->
<div class="wrapper">
  <div class="login_box">
    <div class="login-header">
      <span>Login</span>
    </div>
    <form action="" method="post">
    <div class="input_box">
      <input type="text" id="user" name="email" class="input-field" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$"  required>
     <!-- <label for="user" class="label">Username</label>-->
      <i class="bx bx-user icon"></i>
    </div>

    <div class="input_box">
      <input type="password"  name="password" id="pass" class="input-field" required>
     <!--<label for="pass" class="label">Password</label>-->
      <i class="bx bx-lock-alt icon"></i>
    </div>

     <div class="remember-forgot">
      <div class="remember-me">
         <input type="checkbox" id="remember">
       <label for="remember">Remember me</label>
      </div>

    <div class="forgot">
        <a href="#">Forgot password?</a>
      </div>
    </div>

    <div class="input_box">
      <input type="submit" name="login_btn"  class="input-submit" value="Login">
    </div>
  </div>
</div>
    </form>
</body>
</html>
<?php include 'components/footer.php'; ?>

