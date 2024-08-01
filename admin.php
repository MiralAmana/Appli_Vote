<?php
include 'db.php';
session_start();

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $candidate_name = $_POST['candidate_name'];
        $stmt = $conn->prepare("INSERT INTO candidates (name) VALUES (:name)");
        $stmt->bindParam(':name', $candidate_name);
        $stmt->execute();
    }
}

// Récupérer les candidats
$candidates = $conn->query("SELECT * FROM candidates")->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les résultats
$results = $conn->query("SELECT candidates.id, candidates.name, COUNT(votes.id) as vote_count FROM candidates 
                         LEFT JOIN votes ON candidates.id = votes.candidate_id
                         GROUP BY candidates.id")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - Gestion des Candidats</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .navbar {
            background-color: #6f42c1; /* Violet pour la barre de navigation */
        }
        .navbar-brand, .navbar-nav .nav-link {
            color: #ffffff !important;
        }
        .btn-primary {
            background-color: #6f42c1; /* Violet pour les boutons */
            border-color: #6f42c1;
        }
        .btn-primary:hover {
            background-color: #5a31a5; /* Couleur plus foncée au survol */
            border-color: #5a31a5;
        }
        .table thead th {
            background-color: #6f42c1; /* Violet pour l'en-tête du tableau */
            color: #ffffff;
        }
        .btn-edit {
            color: #6f42c1; /* Violet pour le bouton d'édition */
        }
        .btn-edit:hover {
            color: #5a31a5;
        }
        .btn-delete {
            color: #dc3545; /* Rouge pour le bouton de suppression */
        }
        .btn-delete:hover {
            color: #c82333;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="#">Admin Panel</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
    <li class="nav-item">
        <a class="nav-link" href="admin_results.php">Résultats</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Deconnex.php">Déconnexion</a>
      </li>
    </ul>
  </div>
</nav>

<div class="container mt-5">
    <h2>Ajouter un Candidat</h2>
    <form action="admin.php" method="post">
        <div class="form-group">
            <label for="candidate_name">Nom du Candidat:</label>
            <input type="text" class="form-control" id="candidate_name" name="candidate_name" required>
        </div>
        <input type="hidden" name="action" value="add">
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>

    <h2 class="mt-5">Résultats des Votes</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nom du Candidat</th>
                <th>Nombre de Votes</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($results as $result): ?>
            <tr>
                <td><?= htmlspecialchars($result['name']) ?></td>
                <td><?= $result['vote_count'] ?></td>
                <td>
                    <a href="edit.php?id=<?= $result['id'] ?>" class="btn btn-edit btn-sm">
                        <i class="fa-solid fa-pencil-alt"></i>
                    </a>
                    <a href="delete.php?id=<?= $result['id'] ?>" class="btn btn-delete btn-sm">
                        <i class="fa-solid fa-trash"></i>
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>
