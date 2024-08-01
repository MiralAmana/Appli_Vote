<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Connexion</title>
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
  <style>
    /* Center the content horizontally and vertically */
    .container {
      display: flex;
      justify-content: center;
      align-items: center;
      min-height: 100vh; 
    }

    /* Adjust image and form containers */
    .image-container {
      flex: 0 0 200px; /* Set fixed width for the image */
      margin-right: 2rem; /* Add margin between image and form */
    }

    .form-container {
      flex: 1; /* Allow the form to take up the remaining space */
    }

    .form-group {
      margin-bottom: 1rem;
    }

    .form-control {
      border-radius: 0.5rem;
      border-color: #ccc;
    }

    .btn-primary {
      background-color: #6f42c1; /* Couleur violet */
      color: #ffffff; /* Couleur du texte en blanc pour le contraste */
      border: none; /* Supprime la bordure par défaut */
      border-radius: 0.5rem;
    }

    .social-icons {
      display: flex;
      justify-content: center;
      margin-top: 1rem;
    }

    .social-icons a {
      margin: 0 10px;
      color: #000; /* Couleur par défaut des icônes */
      font-size: 24px; /* Taille des icônes */
    }

    .social-icons a:hover {
      color: #6f42c1; /* Couleur au survol */
    }
  </style>
</head>
<body>
  <div class="container">
    <div class="row">
      <div class="col-md-6 image-container">
        <img src="img/3.jpg" alt="une photo" class="img-fluid">
      </div>
      <div class="col-md-6 form-container">
        <h2>Connexion</h2>
        <form action="index.php" method="post">
          <div class="form-group">
            <label for="username">Nom d'utilisateur:</label>
            <input type="text" class="form-control" id="username" name="username" required> 
          </div>
          <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="password" required> 
          </div>
          <button type="submit" class="btn btn-primary">Se connecter</button>
          <p class="mt-3">Pas de compte ? Inscrivez-vous <a href="register.php">ici</a></p>
        </form>
        <!-- Social Media Icons -->
        <div class="social-icons">
          <a href="#" class="fab fa-google"></a>
          <a href="#" class="fab fa-facebook-f"></a>
          <a href="#" class="fab fa-twitter"></a>
        </div>
        <?php
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'];
            $password = $_POST['password'];
        
            $stmt = $conn->prepare("SELECT * FROM users WHERE username = :username");
            $stmt->bindParam(':username', $username, PDO::PARAM_STR);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role']; // Stocke le rôle dans la session
                header('Location: accueil.php'); // Redirige vers la nouvelle page d'accueil
                exit;
            } else {
                echo "<div class='alert alert-danger'>Nom d'utilisateur ou mot de passe incorrect.</div>";
            }
        }
        ?>
      </div>
    </div>
  </div>

  <script src="https://kit.fontawesome.com/your-fontawesome-kit-code.js"></script>
</body>
</html>
