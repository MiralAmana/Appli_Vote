<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa; /* Couleur de fond */
        }
        .container {
            max-width: 600px;
            margin-top: 50px;
            padding: 20px;
            background-color: #ffffff; /* Fond blanc pour le formulaire */
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .btn-primary {
            background-color: #6f42c1; /* Couleur violet pour les boutons */
            border: none;
        }
        .btn-primary:hover {
            background-color: #5a2d91; /* Couleur violet plus foncé au survol */
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
        .text-center {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center">Inscription</h2>
        <form action="register.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">S'inscrire</button>
        </form>

        <div class="text-center mt-4">
            <p>Déjà un compte ? <a href="index.php">Connectez-vous ici</a></p>
        </div>

        <div class="text-center mt-4">
            <p>Ou inscrivez-vous avec :</p>
            <div class="social-icons">
          <a href="#" class="fab fa-google"></a>
          <a href="#" class="fab fa-facebook-f"></a>
          <a href="#" class="fab fa-twitter"></a>
        </div>
        </div>
    </div>
</body>
</html>
