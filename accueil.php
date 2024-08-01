<?php
include 'db.php';
session_start();

// Vérification de la connexion de l'utilisateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'user') {
    header('Location: admin.php');
    exit;
}

$winnerStmt = $conn->query("SELECT winner FROM election_results WHERE id = 1");
$winner = $winnerStmt->fetch(PDO::FETCH_ASSOC)['winner'];

// Récupérer les candidats
$candidates = $conn->query("SELECT * FROM candidates")->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $candidate_id = $_POST['candidate_id'];
    $user_id = $_SESSION['user_id'];

    // Vérification si l'utilisateur a déjà voté
    $stmt = $conn->prepare("SELECT * FROM votes WHERE user_id = :user_id");
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();

    if ($stmt->rowCount() == 0) {
        $stmt = $conn->prepare("INSERT INTO votes (user_id, candidate_id) VALUES (:user_id, :candidate_id)");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':candidate_id', $candidate_id, PDO::PARAM_INT);
        $stmt->execute();
        $message = "<div class='alert alert-yellow'>Votre vote a été pris en compte.</div>";
    } else {
        $message = "<div class='alert alert-yellow'>Vous avez déjà voté.</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Vote</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        /* Style de la barre de navigation */
        .navbar {
            background-color: #6f42c1; /* Violet */
        }
        .navbar-nav .nav-item + .nav-item {
            margin-left: 20px;
        }
        .alert-yellow {
            background-color: #fff3cd; /* Jaune pâle */
            color: #856404; /* Texte en jaune foncé */
        }
        .btn-success {
            background-color: #6f42c1; /* Violet */
            border-color: #6f42c1; /* Violet */
        }
        .btn-success:hover {
            background-color: #5a2e91; /* Teinte plus foncée de violet pour le survol */
            border-color: #5a2e91; /* Teinte plus foncée de violet pour le survol */
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light">
  <a class="navbar-brand" href="#">Election</a>
  <div class="collapse navbar-collapse">
    <ul class="navbar-nav mr-auto">
      <li class="nav-item">
        <a class="nav-link" href="Deconnex.php">Déconnexion</a>
      </li>
    </ul>
  </div>
</nav>

    <div class="container mt-4">
        <h2>Liste des Candidats</h2>
        <?php if (isset($message)) echo $message; ?>
        <ul class="list-group">
            <?php foreach ($candidates as $candidate): ?>
            <li class="list-group-item d-flex justify-content-between align-items-center">
                <?= htmlspecialchars($candidate['name']) ?>
                <form action="vote.php" method="post" style="margin: 0;">
                    <input type="hidden" name="candidate_id" value="<?= $candidate['id'] ?>">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-vote-yea"></i> Voter
                    </button>
                </form>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="container mt-4">
        <h2>Résultat de l'Élection</h2>
        <?php if ($winner): ?>
            <div class="alert alert-info">
                <h3>Vainqueur: <?= htmlspecialchars($winner) ?></h3>
            </div>
        <?php else: ?>
            <div class="alert alert-warning">
                <h3>Les résultats ne sont pas encore disponibles.</h3>
            </div>
        <?php endif; ?>
    </div>
</html>
